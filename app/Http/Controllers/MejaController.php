<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MejaController extends Controller
{
    public function index(): View
    {
        $meja = Meja::orderBy('nomor')->get();

        return view('admin.meja.index', compact('meja'));
    }

    public function create(): View
    {
        return view('admin.meja.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nomor' => ['required', 'integer', 'unique:meja,nomor'],
            'nama' => ['required', 'string', 'max:50'],
            'kapasitas' => ['required', 'integer', 'min:1'],
            'area' => ['nullable', 'string', 'max:50'],
        ]);

        Meja::create(array_merge($validated, [
            'barcode' => 'SVN-MEJA-'.str_pad($validated['nomor'], 2, '0', STR_PAD_LEFT),
            'aktif' => true,
        ]));

        return redirect()->route('admin.meja.index')->with('success', 'Meja berhasil ditambahkan.');
    }

    public function qr(Meja $meja): View
    {
        $url = route('menu.show', $meja->barcode);
        $qrCode = QrCode::size(250)->generate($url);

        return view('admin.meja.qr', compact('meja', 'qrCode', 'url'));
    }

    public function toggle(Meja $meja): RedirectResponse
    {
        $meja->update(['aktif' => ! $meja->aktif]);

        return redirect()->back()->with('success', $meja->nama.' '.($meja->aktif ? 'diaktifkan' : 'dinonaktifkan').'.');
    }
}
