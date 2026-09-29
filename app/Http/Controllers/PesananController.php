<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Pesanan;
use App\Models\SesiMeja;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PesananController extends Controller
{
    public function daftarMeja(): View
    {
        $meja = Meja::where('aktif', true)
            ->with(['sesiAktif.pesanan'])
            ->orderBy('nomor')
            ->get();

        return view('kasir.meja', compact('meja'));
    }

    public function detailSesi(SesiMeja $sesi): View
    {
        $sesi->load(['meja', 'pesanan.items.opsi', 'pembayaran']);

        return view('kasir.sesi', compact('sesi'));
    }

    public function batalPesanan(Request $request, SesiMeja $sesi, Pesanan $pesanan): RedirectResponse
    {
        abort_if($pesanan->sesi_meja_id !== $sesi->id, 404);
        abort_if(in_array($pesanan->status, ['diantar', 'dibatalkan']), 422);

        $pesanan->update(['status' => 'dibatalkan']);

        return redirect()->route('kasir.sesi.show', $sesi)->with('success', 'Pesanan dibatalkan.');
    }

    public function bukaViaQr(SesiMeja $sesi, string $token): RedirectResponse
    {
        $expected = substr(hash_hmac('sha256', (string) $sesi->id, config('app.key')), 0, 16);
        abort_if(! hash_equals($expected, $token), 404);
        abort_if($sesi->status !== 'buka', 404, 'Sesi sudah dibayar atau ditutup.');

        return redirect()->route('kasir.bayar.show', $sesi)
            ->with('success', 'Sesi dari QR pelanggan dibuka — '.$sesi->meja->nama.'.');
    }

    public function riwayat(): View
    {
        $sesi = SesiMeja::with(['meja', 'pembayaran.kasir'])
            ->whereIn('status', ['dibayar', 'dibatalkan'])
            ->latest('ditutup_pada')
            ->paginate(20);

        return view('kasir.riwayat', compact('sesi'));
    }
}
