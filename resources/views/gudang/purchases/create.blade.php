@extends('layouts.app')
@section('title', 'Catat Pembelian')
@section('header', 'Catat Pembelian Bahan Baku')
@section('content')
    <div class="max-w-md">
        <div class="bg-white rounded-xl border p-6">
            <form method="POST" action="{{ route('gudang.purchases.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bahan Baku</label>
                    <select name="raw_material_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" required>
                        <option value="">Pilih bahan baku...</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}" {{ old('raw_material_id') == $material->id ? 'selected' : '' }}>
                                {{ $material->name }} (stok: {{ $material->current_stock }} {{ $material->unit }})
                            </option>
                        @endforeach
                    </select>
                    @error('raw_material_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Dibeli</label>
                    <input type="number" name="quantity" min="0.01" step="0.01" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" required value="{{ old('quantity') }}">
                    @error('quantity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga per Satuan (Rp)</label>
                    <input type="number" name="price_per_unit" min="1" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" required value="{{ old('price_per_unit') }}">
                    @error('price_per_unit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier / Toko</label>
                    <input type="text" name="supplier" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" value="{{ old('supplier') }}" placeholder="Nama supplier (opsional)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembelian</label>
                    <input type="date" name="purchased_at" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" required value="{{ old('purchased_at', today()->toDateString()) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea name="notes" rows="2" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500">{{ old('notes') }}</textarea>
                </div>
                <p class="text-xs text-blue-600 bg-blue-50 p-3 rounded-lg">ℹ️ Stok bahan baku akan otomatis bertambah setelah pembelian dicatat.</p>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2.5 rounded-lg">Simpan</button>
                    <a href="{{ route('gudang.purchases.index') }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-lg">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
