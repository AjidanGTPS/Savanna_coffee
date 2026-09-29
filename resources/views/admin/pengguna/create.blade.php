@extends('layouts.app')
@section('title', 'Tambah Pengguna')
@section('header', 'Tambah Pengguna')

@section('content')
<div class="max-w-md">
    <a href="{{ route('admin.pengguna.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">← Kembali</a>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.pengguna.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
                @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                <input type="text" name="telepon" value="{{ old('telepon') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Peran <span class="text-red-500">*</span></label>
                <select name="peran" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
                    <option value="">-- Pilih Peran --</option>
                    @foreach(['admin'=>'Admin','kasir'=>'Kasir','pelayan'=>'Pelayan','owner'=>'Owner','manajer'=>'Manajer'] as $val=>$lbl)
                    <option value="{{ $val }}" {{ old('peran') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" required minlength="8" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
            </div>
            <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2.5 rounded-lg transition">Tambah Pengguna</button>
        </form>
    </div>
</div>
@endsection
