@extends('layouts.app')
@section('title', 'Tambah Bahan Baku')
@section('header', 'Tambah Bahan Baku')
@section('content')
    <div class="max-w-md">
        <div class="bg-white rounded-xl border p-6">
            <form method="POST" action="{{ route('gudang.materials.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bahan Baku</label>
                    <input type="text" name="name" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" required value="{{ old('name') }}">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                    <input type="text" name="unit" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" required value="{{ old('unit') }}" placeholder="kg, liter, pcs, gram">
                    @error('unit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <input type="text" name="category" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" value="{{ old('category') }}" placeholder="Kopi, Susu, Bakery...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok Minimum</label>
                    <input type="number" name="minimum_stock" min="0" step="0.01" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" required value="{{ old('minimum_stock', 0) }}">
                    <p class="text-xs text-gray-400 mt-1">Sistem akan memperingatkan jika stok di bawah batas ini</p>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2.5 rounded-lg">Simpan</button>
                    <a href="{{ route('gudang.materials.index') }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-lg">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
