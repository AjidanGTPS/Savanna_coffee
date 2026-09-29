@extends('layouts.app')
@section('title', 'Tambah Pengguna')
@section('header', 'Tambah Pengguna')
@section('content')
    <div class="max-w-md">
        <div class="bg-white rounded-xl border p-6">
            <form method="POST" action="{{ route('manajer.users.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" required value="{{ old('name') }}">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" required value="{{ old('email') }}">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                    <select name="role" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" required>
                        <option value="kasir" {{ old('role') === 'kasir' ? 'selected' : '' }}>Kasir</option>
                        <option value="kepala_gudang" {{ old('role') === 'kepala_gudang' ? 'selected' : '' }}>Kepala Gudang</option>
                        <option value="manajer" {{ old('role') === 'manajer' ? 'selected' : '' }}>Manajer</option>
                        <option value="owner" {{ old('role') === 'owner' ? 'selected' : '' }}>Owner</option>
                    </select>
                    @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" required minlength="8">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2.5 rounded-lg">Simpan</button>
                    <a href="{{ route('manajer.users.index') }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-lg">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
