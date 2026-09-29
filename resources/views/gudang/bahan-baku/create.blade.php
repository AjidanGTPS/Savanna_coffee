@extends('layouts.app')
@section('title', 'Tambah Bahan Baku')
@section('header', 'Tambah Bahan Baku')
@section('subheader', 'Daftarkan bahan baku baru ke gudang')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 text-white font-bold text-base" style="background:linear-gradient(135deg,#1C0A00,#2d1207)">
            Informasi Bahan Baku
        </div>
        <form method="POST" action="{{ route('gudang.bahan-baku.store') }}" class="p-6 space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Bahan Baku <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 @error('nama') border-red-400 @enderror"
                        placeholder="cth. Biji Kopi Arabica">
                    @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Satuan <span class="text-red-500">*</span></label>
                    <input type="text" name="satuan" value="{{ old('satuan') }}" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400"
                        placeholder="cth. kg, liter, pcs">
                    @error('satuan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga per Satuan (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga_per_satuan" value="{{ old('harga_per_satuan', 0) }}" min="0" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                    @error('harga_per_satuan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Stok Awal <span class="text-red-500">*</span></label>
                    <input type="number" name="stok_saat_ini" value="{{ old('stok_saat_ini', 0) }}" min="0" step="0.01" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                    @error('stok_saat_ini')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Stok Minimum <span class="text-red-500">*</span></label>
                    <input type="number" name="stok_minimum" value="{{ old('stok_minimum', 0) }}" min="0" step="0.01" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <p class="text-xs text-gray-400 mt-1">Sistem akan beri peringatan jika stok di bawah nilai ini</p>
                    @error('stok_minimum')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Stok Maksimum</label>
                    <input type="number" name="stok_maksimum" value="{{ old('stok_maksimum') }}" min="0" step="0.01"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400"
                        placeholder="Opsional">
                    @error('stok_maksimum')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Keterangan</label>
                    <input type="text" name="keterangan" value="{{ old('keterangan') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400"
                        placeholder="Catatan tambahan (opsional)">
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <a href="{{ route('gudang.bahan-baku.index') }}"
                    class="flex-1 text-center py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl text-sm font-bold text-white shadow-lg transition hover:opacity-90"
                    style="background:linear-gradient(135deg,#d97706,#ea580c)">
                    Simpan Bahan Baku
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
