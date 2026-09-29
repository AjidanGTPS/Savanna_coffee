@extends('layouts.app')
@section('title', 'Edit Bahan Baku')
@section('header', 'Edit Bahan Baku')
@section('subheader', 'Perbarui informasi bahan baku')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 text-white font-bold text-base" style="background:linear-gradient(135deg,#1C0A00,#2d1207)">
            {{ $bahanBaku->nama }}
        </div>
        <form method="POST" action="{{ route('gudang.bahan-baku.update', $bahanBaku) }}" class="p-6 space-y-5">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Bahan Baku <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $bahanBaku->nama) }}" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 @error('nama') border-red-400 @enderror">
                    @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Satuan <span class="text-red-500">*</span></label>
                    <input type="text" name="satuan" value="{{ old('satuan', $bahanBaku->satuan) }}" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga per Satuan (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga_per_satuan" value="{{ old('harga_per_satuan', $bahanBaku->harga_per_satuan) }}" min="0" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>

                <div class="col-span-2 p-4 rounded-xl border border-amber-200 bg-amber-50">
                    <p class="text-sm font-semibold text-amber-800 mb-1">Stok saat ini: <span class="text-amber-600">{{ fmt_stok($bahanBaku->stok_saat_ini) }} {{ $bahanBaku->satuan }}</span></p>
                    <p class="text-xs text-amber-600">Untuk mengubah stok, gunakan fitur Stok Masuk / Stok Keluar di halaman daftar bahan baku.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Stok Minimum <span class="text-red-500">*</span></label>
                    <input type="number" name="stok_minimum" value="{{ old('stok_minimum', $bahanBaku->stok_minimum) }}" min="0" step="0.01" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Stok Maksimum</label>
                    <input type="number" name="stok_maksimum" value="{{ old('stok_maksimum', $bahanBaku->stok_maksimum) }}" min="0" step="0.01"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400"
                        placeholder="Opsional">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Keterangan</label>
                    <input type="text" name="keterangan" value="{{ old('keterangan', $bahanBaku->keterangan) }}"
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
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
