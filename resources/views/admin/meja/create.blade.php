@extends('layouts.app')
@section('title', 'Tambah Meja')
@section('header', 'Tambah Meja')

@section('content')
<div class="max-w-md">
    <a href="{{ route('admin.meja.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">← Kembali</a>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.meja.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Meja <span class="text-red-500">*</span></label>
                <input type="number" name="nomor" value="{{ old('nomor') }}" min="1" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
                @error('nomor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Meja <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Meja 21" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas</label>
                <input type="number" name="kapasitas" value="{{ old('kapasitas', 4) }}" min="1" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Area</label>
                <input type="text" name="area" value="{{ old('area') }}" placeholder="Indoor / Outdoor" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
            </div>
            <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2.5 rounded-lg transition">Tambah Meja</button>
        </form>
    </div>
</div>
@endsection
