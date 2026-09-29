<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\VarianProduk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProdukController extends Controller
{
    public function index(): View
    {
        $produk = Produk::with('kategori')
            ->orderBy('kategori_id')
            ->orderBy('urutan')
            ->get()
            ->groupBy('kategori.nama');

        return view('admin.produk.index', compact('produk'));
    }

    public function create(): View
    {
        $kategoris = Kategori::whereNotNull('induk_id')->where('aktif', true)->orderBy('urutan')->get();

        return view('admin.produk.create', compact('kategoris'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kategori_id' => ['required', 'exists:kategori,id'],
            'nama' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'harga_dasar' => ['nullable', 'integer', 'min:0'],
            'punya_varian' => ['boolean'],
            'gambar' => ['nullable', 'image', 'max:2048'],
            'varian' => ['nullable', 'array'],
            'varian.*.nama' => ['required_with:varian', 'string'],
            'varian.*.harga' => ['required_with:varian', 'integer', 'min:0'],
        ]);

        $gambar = null;
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')->store('produk', 'public');
        }

        $punya_varian = $request->boolean('punya_varian');

        $produk = Produk::create([
            'kategori_id' => $validated['kategori_id'],
            'nama' => $validated['nama'],
            'slug' => Str::slug($validated['nama']).'-'.Str::random(4),
            'deskripsi' => $validated['deskripsi'] ?? null,
            'harga_dasar' => $punya_varian ? 0 : ($validated['harga_dasar'] ?? 0),
            'punya_varian' => $punya_varian,
            'gambar' => $gambar,
            'tersedia' => true,
        ]);

        if ($punya_varian && ! empty($validated['varian'])) {
            foreach ($validated['varian'] as $i => $v) {
                VarianProduk::create([
                    'produk_id' => $produk->id,
                    'nama' => $v['nama'],
                    'harga' => $v['harga'],
                    'urutan' => $i + 1,
                    'tersedia' => true,
                ]);
            }
        }

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $produk): View
    {
        $kategoris = Kategori::whereNotNull('induk_id')->where('aktif', true)->orderBy('urutan')->get();
        $produk->load(['varian', 'grupOpsi.opsi']);

        return view('admin.produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, Produk $produk): RedirectResponse
    {
        $validated = $request->validate([
            'kategori_id' => ['required', 'exists:kategori,id'],
            'nama' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'harga_dasar' => ['nullable', 'integer', 'min:0'],
            'gambar' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('gambar')) {
            if ($produk->gambar) Storage::disk('public')->delete($produk->gambar);
            $validated['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update([
            'kategori_id' => $validated['kategori_id'],
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'harga_dasar' => $validated['harga_dasar'] ?? 0,
            'gambar' => $validated['gambar'] ?? $produk->gambar,
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function toggle(Produk $produk): RedirectResponse
    {
        $produk->update(['tersedia' => ! $produk->tersedia]);

        return redirect()->back()->with('success', $produk->nama.' '.($produk->tersedia ? 'tersedia' : 'tidak tersedia').'.');
    }

    public function destroy(Produk $produk): RedirectResponse
    {
        if ($produk->gambar) Storage::disk('public')->delete($produk->gambar);
        $produk->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk dihapus.');
    }
}
