<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\TransaksiBahanBaku;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BahanBakuController extends Controller
{
    public function index(): View
    {
        $bahan = BahanBaku::where('aktif', true)->orderBy('nama')->get();
        $ringkasan = [
            'total'  => $bahan->count(),
            'habis'  => $bahan->where('status_stok', 'habis')->count(),
            'kritis' => $bahan->where('status_stok', 'kritis')->count(),
            'rendah' => $bahan->where('status_stok', 'rendah')->count(),
        ];
        return view('gudang.bahan-baku.index', compact('bahan', 'ringkasan'));
    }

    public function create(): View
    {
        return view('gudang.bahan-baku.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama'            => ['required', 'string', 'max:150'],
            'satuan'          => ['required', 'string', 'max:30'],
            'stok_saat_ini'   => ['required', 'numeric', 'min:0'],
            'stok_minimum'    => ['required', 'numeric', 'min:0'],
            'stok_maksimum'   => ['nullable', 'numeric', 'min:0'],
            'harga_per_satuan'=> ['required', 'integer', 'min:0'],
            'keterangan'      => ['nullable', 'string', 'max:255'],
        ]);

        BahanBaku::create($validated);

        return redirect()->route('gudang.bahan-baku.index')
            ->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function edit(BahanBaku $bahanBaku): View
    {
        return view('gudang.bahan-baku.edit', compact('bahanBaku'));
    }

    public function update(Request $request, BahanBaku $bahanBaku): RedirectResponse
    {
        $validated = $request->validate([
            'nama'            => ['required', 'string', 'max:150'],
            'satuan'          => ['required', 'string', 'max:30'],
            'stok_minimum'    => ['required', 'numeric', 'min:0'],
            'stok_maksimum'   => ['nullable', 'numeric', 'min:0'],
            'harga_per_satuan'=> ['required', 'integer', 'min:0'],
            'keterangan'      => ['nullable', 'string', 'max:255'],
        ]);

        $bahanBaku->update($validated);

        return redirect()->route('gudang.bahan-baku.index')
            ->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function transaksi(Request $request, BahanBaku $bahanBaku): RedirectResponse
    {
        $validated = $request->validate([
            'jenis'       => ['required', 'in:masuk,keluar,penyesuaian'],
            'jumlah'      => ['required', 'numeric', 'min:0.01'],
            'keterangan'  => ['nullable', 'string', 'max:255'],
        ]);

        $stokSebelum = $bahanBaku->stok_saat_ini;
        $stokSesudah = match ($validated['jenis']) {
            'masuk'       => $stokSebelum + $validated['jumlah'],
            'keluar'      => max(0, $stokSebelum - $validated['jumlah']),
            'penyesuaian' => $validated['jumlah'],
        };

        TransaksiBahanBaku::create([
            'bahan_baku_id' => $bahanBaku->id,
            'pengguna_id'   => auth()->id(),
            'jenis'         => $validated['jenis'],
            'jumlah'        => $validated['jumlah'],
            'stok_sebelum'  => $stokSebelum,
            'stok_sesudah'  => $stokSesudah,
            'keterangan'    => $validated['keterangan'] ?? null,
            'dicatat_pada'  => now(),
        ]);

        $bahanBaku->update(['stok_saat_ini' => $stokSesudah]);

        return redirect()->route('gudang.bahan-baku.index')
            ->with('success', 'Transaksi stok berhasil dicatat.');
    }

    public function laporan(): View
    {
        $prioritas = ['habis' => 0, 'kritis' => 1, 'rendah' => 2, 'aman' => 3];
        $bahan = BahanBaku::where('aktif', true)->orderBy('nama')->get()
            ->sortBy(fn ($b) => ($prioritas[$b->status_stok] ?? 99))
            ->values();

        $riwayat = TransaksiBahanBaku::with(['bahanBaku', 'pengguna'])
            ->latest('dicatat_pada')
            ->limit(50)
            ->get();

        return view('gudang.laporan-stok', compact('bahan', 'riwayat'));
    }
}
