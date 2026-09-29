@extends('layouts.app')
@section('title', 'Edit Bahan Baku')
@section('header', 'Edit — ' . $rawMaterial->name)
@section('content')
    <div class="max-w-md">
        <div class="bg-white rounded-xl border p-6">
            <div class="mb-4 p-3 bg-gray-50 rounded-lg flex justify-between">
                <span class="text-sm text-gray-600">Stok Saat Ini</span>
                <span class="font-bold">{{ $rawMaterial->current_stock }} {{ $rawMaterial->unit }}</span>
            </div>
            <form method="POST" action="{{ route('gudang.materials.update', $rawMaterial) }}" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bahan Baku</label>
                    <input type="text" name="name" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" required value="{{ old('name', $rawMaterial->name) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                    <input type="text" name="unit" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" required value="{{ old('unit', $rawMaterial->unit) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <input type="text" name="category" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" value="{{ old('category', $rawMaterial->category) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok Minimum</label>
                    <input type="number" name="minimum_stock" min="0" step="0.01" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" required value="{{ old('minimum_stock', $rawMaterial->minimum_stock) }}">
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2.5 rounded-lg">Perbarui</button>
                    <a href="{{ route('gudang.materials.index') }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-lg">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
