<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pengaturan;
use App\Models\SesiMeja;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PembayaranController extends Controller
{
    private function hitungTotal(SesiMeja $sesi): array
    {
        $persen_pajak    = (float) Pengaturan::ambil('persen_pajak', 0);
        $persen_layanan  = (float) Pengaturan::ambil('persen_layanan', 0);
        $subtotal        = $sesi->pesanan->where('status', '!=', 'dibatalkan')->sum('subtotal');
        $jumlah_pajak    = (int) round($subtotal * $persen_pajak / 100);
        $jumlah_layanan  = (int) round($subtotal * $persen_layanan / 100);
        $total           = $subtotal + $jumlah_pajak + $jumlah_layanan;

        return compact('subtotal', 'persen_pajak', 'jumlah_pajak', 'persen_layanan', 'jumlah_layanan', 'total');
    }

    public function show(SesiMeja $sesi): View
    {
        abort_if($sesi->status !== 'buka', 404);
        $sesi->load(['meja', 'pesanan.items.opsi', 'pembayaran']);

        return view('kasir.bayar', array_merge(compact('sesi'), $this->hitungTotal($sesi)));
    }

    public function proses(Request $request, SesiMeja $sesi): RedirectResponse
    {
        abort_if($sesi->status !== 'buka', 404);
        $sesi->load('pesanan', 'meja');

        $kalkulasi = $this->hitungTotal($sesi);
        $total = $kalkulasi['total'];

        $validated = $request->validate([
            'metode'          => ['required', 'in:tunai,qris'],
            'jumlah_dibayar'  => ['required_if:metode,tunai', 'nullable', 'integer', 'min:'.$total],
            'nomor_referensi' => ['nullable', 'string'],
        ]);

        $kembalian     = $validated['metode'] === 'tunai' ? (int) $validated['jumlah_dibayar'] - $total : 0;
        $jumlah_dibayar = $validated['metode'] === 'tunai' ? (int) $validated['jumlah_dibayar'] : $total;

        $pembayaran = $this->simpanPembayaran($sesi, $kalkulasi, [
            'metode'          => $validated['metode'],
            'nomor_referensi' => $validated['nomor_referensi'] ?? null,
            'jumlah_dibayar'  => $jumlah_dibayar,
            'kembalian'       => $kembalian,
        ]);

        return redirect()->route('kasir.struk', $pembayaran)->with('success', 'Pembayaran berhasil!');
    }

    public function konfirmasiQris(Request $request, SesiMeja $sesi): RedirectResponse
    {
        abort_if($sesi->status !== 'buka', 404);
        $sesi->load('pesanan', 'meja');

        $validated = $request->validate([
            'nomor_referensi' => ['required', 'string', 'min:1'],
        ]);

        $kalkulasi = $this->hitungTotal($sesi);

        $pembayaran = $this->simpanPembayaran($sesi, $kalkulasi, [
            'metode'          => 'qris',
            'nomor_referensi' => $validated['nomor_referensi'],
            'jumlah_dibayar'  => $kalkulasi['total'],
            'kembalian'       => 0,
        ]);

        return redirect()->route('kasir.struk', $pembayaran)->with('success', 'Pembayaran QRIS berhasil!');
    }

    private function simpanPembayaran(SesiMeja $sesi, array $kalkulasi, array $data): Pembayaran
    {
        return DB::transaction(function () use ($sesi, $kalkulasi, $data) {
            $pembayaran = Pembayaran::create([
                'nomor_faktur'    => Pembayaran::generateNomorFaktur(),
                'sesi_meja_id'    => $sesi->id,
                'kasir_id'        => Auth::id(),
                'subtotal'        => $kalkulasi['subtotal'],
                'persen_pajak'    => $kalkulasi['persen_pajak'],
                'jumlah_pajak'    => $kalkulasi['jumlah_pajak'],
                'persen_layanan'  => $kalkulasi['persen_layanan'],
                'jumlah_layanan'  => $kalkulasi['jumlah_layanan'],
                'jumlah_diskon'   => 0,
                'total'           => $kalkulasi['total'],
                'metode'          => $data['metode'],
                'penyedia'        => null,
                'nomor_referensi' => $data['nomor_referensi'] ?? null,
                'jumlah_dibayar'  => $data['jumlah_dibayar'],
                'kembalian'       => $data['kembalian'],
                'status'          => 'lunas',
                'dibayar_pada'    => now(),
            ]);

            $sesi->update(['status' => 'dibayar', 'ditutup_pada' => now()]);
            $sesi->meja->update(['status' => 'kosong']);

            return $pembayaran;
        });
    }

    public function struk(Pembayaran $pembayaran): View
    {
        $pembayaran->load(['sesiMeja.meja', 'sesiMeja.pesanan.items.opsi', 'kasir']);

        return view('kasir.struk', compact('pembayaran'));
    }

    public function bukaSesiBaru(Pembayaran $pembayaran): RedirectResponse
    {
        $meja = $pembayaran->sesiMeja->meja;

        $sesi = SesiMeja::create([
            'meja_id'    => $meja->id,
            'dibuka_oleh' => auth()->id(),
            'status'     => 'buka',
            'dibuka_pada' => now(),
        ]);

        $meja->update(['status' => 'terisi']);

        return redirect()->route('kasir.sesi.show', $sesi)->with('success', 'Sesi baru dibuka untuk '.$meja->nama.'.');
    }
}
