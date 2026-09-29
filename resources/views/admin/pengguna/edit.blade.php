@extends('layouts.app')
@section('title', 'Edit Pengguna')
@section('header', 'Edit — '.$pengguna->nama)

@section('content')
<div class="max-w-md">
    <a href="{{ route('admin.pengguna.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">← Kembali</a>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.pengguna.update', $pengguna) }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama', $pengguna->nama) }}" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $pengguna->email) }}" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                <input type="text" name="telepon" value="{{ old('telepon', $pengguna->telepon) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Peran</label>
                <select name="peran" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
                    @foreach(['admin'=>'Admin','kasir'=>'Kasir','pelayan'=>'Pelayan','owner'=>'Owner','manajer'=>'Manajer'] as $val=>$lbl)
                    <option value="{{ $val }}" {{ $pengguna->peran === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru <span class="text-gray-400">(kosongkan jika tidak diubah)</span></label>
                <input type="password" name="password" minlength="8" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
            </div>
            <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2.5 rounded-lg transition">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
