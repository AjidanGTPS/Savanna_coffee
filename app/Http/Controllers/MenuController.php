<?php

namespace App\Http\Controllers;

use App\Models\ItemPesanan;
use App\Models\Kategori;
use App\Models\Meja;
use App\Models\OpsiItemPesanan;
use App\Models\Pembayaran;
use App\Models\Pengaturan;
use App\Models\Pesanan;
use App\Models\SesiMeja;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MenuController extends Controller
{
    private function tokenSesi(SesiMeja $sesi): string
    {
        return substr(hash_hmac('sha256', (string) $sesi->id, config('app.key')), 0, 16);
    }

    private function hitungTotal(SesiMeja $sesi): array
    {
        $persen_pajak   = (float) Pengaturan::ambil('persen_pajak', 0);
        $persen_layanan = (float) Pengaturan::ambil('persen_layanan', 0);
        $subtotal       = $sesi->pesanan->where('status', '!=', 'dibatalkan')->sum('subtotal');
        $jumlah_pajak   = (int) round($subtotal * $persen_pajak / 100);
        $jumlah_layanan = (int) round($subtotal * $persen_layanan / 100);
        $total          = $subtotal + $jumlah_pajak + $jumlah_layanan;

        return compact('subtotal', 'persen_pajak', 'jumlah_pajak', 'persen_layanan', 'jumlah_layanan', 'total');
    }

    public function show(string $barcode): View
    {
        $meja = Meja::where('barcode', $barcode)->where('aktif', true)->firstOrFail();

        $kategoris = Kategori::whereNotNull('induk_id')
            ->where('aktif', true)
            ->with([
                'induk',
                'produkAktif.varian',
                'produkAktif.grupOpsi.opsi',
            ])
            ->orderBy('urutan')
            ->get()
            ->groupBy(fn ($k) => $k->induk->nama ?? 'Lainnya');

        return view('menu.show', compact('meja', 'kategoris', 'barcode'));
    }

    public function pesan(Request $request, string $barcode): JsonResponse
    {
        $meja = Meja::where('barcode', $barcode)->where('aktif', true)->firstOrFail();

        $validated = $request->validate([
            'metode'                           => ['nullable', 'in:tunai,qris'],
            'catatan'                          => ['nullable', 'string', 'max:500'],
            'items'                            => ['required', 'array', 'min:1'],
            'items.*.produk_id'                => ['required', 'exists:produk,id'],
            'items.*.varian_produk_id'         => ['nullable', 'exists:varian_produk,id'],
            'items.*.nama_produk'              => ['required', 'string'],
            'items.*.nama_varian'              => ['nullable', 'string'],
            'items.*.jumlah'                   => ['required', 'integer', 'min:1'],
            'items.*.harga_satuan'             => ['required', 'integer', 'min:0'],
            'items.*.harga_opsi'               => ['required', 'integer', 'min:0'],
            'items.*.subtotal'                 => ['required', 'integer', 'min:0'],
            'items.*.catatan'                  => ['nullable', 'string'],
            'items.*.opsi'                     => ['nullable', 'array'],
            'items.*.opsi.*.opsi_id'           => ['nullable', 'integer'],
            'items.*.opsi.*.nama_grup_opsi'    => ['required', 'string'],
            'items.*.opsi.*.nama_opsi'         => ['required', 'string'],
            'items.*.opsi.*.harga_tambahan'    => ['required', 'integer', 'min:0'],
        ]);

        $metode   = $validated['metode'] ?? 'tunai';
        $subtotal = collect($validated['items'])->sum('subtotal');
        $sesi     = null;

        DB::transaction(function () use ($meja, $validated, $subtotal, &$sesi) {
            $sesi = SesiMeja::firstOrCreate(
                ['meja_id' => $meja->id, 'status' => 'buka'],
                ['dibuka_pada' => now()]
            );

            $pesanan = Pesanan::create([
                'nomor_pesanan' => Pesanan::generateNomor(),
                'sesi_meja_id'  => $sesi->id,
                'meja_id'       => $meja->id,
                'pengguna_id'   => null,
                'status'        => 'baru',
                'catatan'       => $validated['catatan'] ?? null,
                'subtotal'      => $subtotal,
                'dipesan_pada'  => now(),
            ]);

            foreach ($validated['items'] as $itemData) {
                $item = ItemPesanan::create([
                    'pesanan_id'       => $pesanan->id,
                    'produk_id'        => $itemData['produk_id'],
                    'varian_produk_id' => $itemData['varian_produk_id'] ?? null,
                    'nama_produk'      => $itemData['nama_produk'],
                    'nama_varian'      => $itemData['nama_varian'] ?? null,
                    'jumlah'           => $itemData['jumlah'],
                    'harga_satuan'     => $itemData['harga_satuan'],
                    'harga_opsi'       => $itemData['harga_opsi'],
                    'subtotal'         => $itemData['subtotal'],
                    'catatan'          => $itemData['catatan'] ?? null,
                ]);

                foreach ($itemData['opsi'] ?? [] as $opsiData) {
                    OpsiItemPesanan::create([
                        'item_pesanan_id' => $item->id,
                        'opsi_id'         => $opsiData['opsi_id'] ?? null,
                        'nama_grup_opsi'  => $opsiData['nama_grup_opsi'],
                        'nama_opsi'       => $opsiData['nama_opsi'],
                        'harga_tambahan'  => $opsiData['harga_tambahan'],
                    ]);
                }
            }

            $meja->update(['status' => 'terisi']);
        });

        $token = $this->tokenSesi($sesi);

        return response()->json([
            'success'  => true,
            'redirect' => route('menu.bayar', [
                'barcode' => $barcode,
                'sesi'    => $sesi->id,
                'token'   => $token,
                'metode'  => $metode,
            ]),
        ]);
    }

    public function halamanBayar(string $barcode, SesiMeja $sesi, Request $request): View
    {
        $meja = Meja::where('barcode', $barcode)->where('aktif', true)->firstOrFail();

        abort_if($sesi->meja_id !== $meja->id, 404);
        abort_if(! hash_equals($this->tokenSesi($sesi), (string) $request->query('token', '')), 403);

        $sesi->load('pesanan.items.opsi');
        $metode = $request->query('metode', 'tunai');
        $kalkulasi = $this->hitungTotal($sesi);
        $token = $this->tokenSesi($sesi);

        // QR untuk kasir scan (URL kasir payment page)
        $urlKasir = route('kasir.sesi-qr', ['sesi' => $sesi->id, 'token' => $token]);

        return view('menu.bayar', array_merge(
            compact('meja', 'sesi', 'barcode', 'metode', 'token', 'urlKasir'),
            $kalkulasi
        ));
    }

    public function konfirmasiQrisPelanggan(Request $request, string $barcode, SesiMeja $sesi): JsonResponse
    {
        $meja = Meja::where('barcode', $barcode)->where('aktif', true)->firstOrFail();
        abort_if($sesi->meja_id !== $meja->id, 404);
        abort_if(! hash_equals($this->tokenSesi($sesi), (string) $request->input('token', '')), 403);
        abort_if($sesi->status !== 'buka', 409, 'Sesi sudah dibayar.');

        $sesi->load('pesanan', 'meja');
        $kalkulasi = $this->hitungTotal($sesi);

        DB::transaction(function () use ($sesi, $kalkulasi) {
            Pembayaran::create([
                'nomor_faktur'    => Pembayaran::generateNomorFaktur(),
                'sesi_meja_id'    => $sesi->id,
                'kasir_id'        => null,
                'subtotal'        => $kalkulasi['subtotal'],
                'persen_pajak'    => $kalkulasi['persen_pajak'],
                'jumlah_pajak'    => $kalkulasi['jumlah_pajak'],
                'persen_layanan'  => $kalkulasi['persen_layanan'],
                'jumlah_layanan'  => $kalkulasi['jumlah_layanan'],
                'jumlah_diskon'   => 0,
                'total'           => $kalkulasi['total'],
                'metode'          => 'qris',
                'penyedia'        => 'Pelanggan',
                'nomor_referensi' => 'SELF-' . strtoupper(substr(uniqid(), -8)),
                'jumlah_dibayar'  => $kalkulasi['total'],
                'kembalian'       => 0,
                'status'          => 'lunas',
                'dibayar_pada'    => now(),
            ]);

            $sesi->update(['status' => 'dibayar', 'ditutup_pada' => now()]);
            $sesi->meja->update(['status' => 'kosong']);
        });

        return response()->json([
            'success'  => true,
            'redirect' => route('menu.selesai', [
                'barcode' => $sesi->meja->barcode,
                'sesi'    => $sesi->id,
                'token'   => $this->tokenSesi($sesi),
            ]),
        ]);
    }

    public function cekStatus(string $barcode, SesiMeja $sesi): JsonResponse
    {
        $sesi->refresh();

        return response()->json([
            'dibayar' => $sesi->status === 'dibayar',
            'redirect' => $sesi->status === 'dibayar'
                ? route('menu.selesai', [
                    'barcode' => $barcode,
                    'sesi'    => $sesi->id,
                    'token'   => $this->tokenSesi($sesi),
                ])
                : null,
        ]);
    }

    public function selesai(string $barcode, SesiMeja $sesi, Request $request): View
    {
        $meja = Meja::where('barcode', $barcode)->where('aktif', true)->firstOrFail();
        abort_if($sesi->meja_id !== $meja->id, 404);
        abort_if(! hash_equals($this->tokenSesi($sesi), (string) $request->query('token', '')), 403);

        $sesi->load(['pesanan.items', 'pembayaran']);

        return view('menu.selesai', compact('meja', 'sesi'));
    }
}
