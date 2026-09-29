<?php

namespace App\Http\Controllers;

use App\Models\LogStatusPesanan;
use App\Models\Pesanan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KitchenController extends Controller
{
    public function index(): View
    {
        $pesanan = Pesanan::with(['meja', 'items.opsi'])
            ->whereHas('sesiMeja', fn ($q) => $q->where('status', 'dibayar'))
            ->whereIn('status', ['baru', 'dimasak', 'siap'])
            ->orderBy('dipesan_pada')
            ->get();

        return view('kitchen.index', compact('pesanan'));
    }

    public function updateStatus(Request $request, Pesanan $pesanan): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:dimasak,siap,diantar,dibatalkan'],
        ]);

        $statusAwal = $pesanan->status;

        $timestamps = match ($validated['status']) {
            'dimasak' => ['dimasak_pada' => now()],
            'siap' => ['siap_pada' => now()],
            'diantar' => ['diantar_pada' => now()],
            default => [],
        };

        $pesanan->update(array_merge(['status' => $validated['status']], $timestamps));

        LogStatusPesanan::create([
            'pesanan_id' => $pesanan->id,
            'pengguna_id' => auth()->id(),
            'status_awal' => $statusAwal,
            'status_akhir' => $validated['status'],
            'dibuat_pada' => now(),
        ]);

        return response()->json([
            'success' => true,
            'status' => $pesanan->status,
            'status_label' => $pesanan->status_label,
        ]);
    }

    public function poll(): JsonResponse
    {
        $pesanan = Pesanan::with(['meja', 'items.opsi'])
            ->whereHas('sesiMeja', fn ($q) => $q->where('status', 'dibayar'))
            ->whereIn('status', ['baru', 'dimasak', 'siap'])
            ->orderBy('dipesan_pada')
            ->get();

        return response()->json($pesanan->map(fn ($p) => [
            'id' => $p->id,
            'nomor_pesanan' => $p->nomor_pesanan,
            'meja' => $p->meja->nama,
            'status' => $p->status,
            'status_label' => $p->status_label,
            'catatan' => $p->catatan,
            'dipesan_pada' => $p->dipesan_pada?->diffForHumans(),
            'items' => $p->items->map(fn ($i) => [
                'nama_produk' => $i->nama_produk,
                'nama_varian' => $i->nama_varian,
                'jumlah' => $i->jumlah,
                'catatan' => $i->catatan,
                'opsi' => $i->opsi->map(fn ($o) => $o->nama_opsi)->implode(', '),
            ]),
        ]));
    }
}
