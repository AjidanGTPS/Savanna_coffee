<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PenggunaController extends Controller
{
    public function index(): View
    {
        $pengguna = Pengguna::orderBy('nama')->get();

        return view('admin.pengguna.index', compact('pengguna'));
    }

    public function create(): View
    {
        return view('admin.pengguna.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:pengguna,email'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'peran' => ['required', 'in:admin,kasir,pelayan,owner,manajer'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        Pengguna::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'telepon' => $validated['telepon'] ?? null,
            'kata_sandi' => Hash::make($validated['password']),
            'peran' => $validated['peran'],
            'aktif' => true,
        ]);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(Pengguna $pengguna): View
    {
        return view('admin.pengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, Pengguna $pengguna): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:pengguna,email,'.$pengguna->id],
            'telepon' => ['nullable', 'string', 'max:20'],
            'peran' => ['required', 'in:admin,kasir,pelayan,owner,manajer'],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        $data = [
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'telepon' => $validated['telepon'] ?? null,
            'peran' => $validated['peran'],
        ];

        if (! empty($validated['password'])) {
            $data['kata_sandi'] = Hash::make($validated['password']);
        }

        $pengguna->update($data);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function toggle(Pengguna $pengguna): RedirectResponse
    {
        abort_if($pengguna->id === auth()->id(), 403, 'Tidak bisa menonaktifkan akun sendiri.');
        $pengguna->update(['aktif' => ! $pengguna->aktif]);

        return redirect()->back()->with('success', $pengguna->nama.' '.($pengguna->aktif ? 'diaktifkan' : 'dinonaktifkan').'.');
    }
}
