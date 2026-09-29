@extends('layouts.app')
@section('title', 'Tambah Produk')
@section('header', 'Tambah Produk')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.produk.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">← Kembali</a>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.produk.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select name="kategori_id" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kat)
                    <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                        {{ $kat->induk?->nama ? $kat->induk->nama.' > ' : '' }}{{ $kat->nama }}
                    </option>
                    @endforeach
                </select>
                @error('kategori_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
                @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="punya_varian" id="punya_varian" value="1" {{ old('punya_varian') ? 'checked' : '' }} onchange="toggleVarian(this.checked)">
                <label for="punya_varian" class="text-sm text-gray-700">Produk punya varian (S/M/L, Normal/Double, dll)</label>
            </div>

            <div id="wrap-harga">
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                <input type="number" name="harga_dasar" value="{{ old('harga_dasar', 0) }}" min="0" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
            </div>

            <div id="wrap-varian" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Varian & Harga</label>
                <div id="varian-list" class="space-y-2">
                    <div class="flex gap-2">
                        <input type="text" name="varian[0][nama]" placeholder="Nama varian (S, M, Normal...)" class="flex-1 border rounded-lg px-3 py-2 text-sm">
                        <input type="number" name="varian[0][harga]" placeholder="Harga" min="0" class="w-32 border rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>
                <button type="button" onclick="tambahVarian()" class="mt-2 text-sm text-amber-600 hover:underline">+ Tambah Varian</button>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Produk</label>
                <input type="file" name="gambar" accept="image/*" class="text-sm text-gray-500">
            </div>

            <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2.5 rounded-lg transition">Simpan Produk</button>
        </form>
    </div>
</div>
<script>
let varianIdx = 1;
function toggleVarian(aktif) {
    document.getElementById('wrap-harga').classList.toggle('hidden', aktif);
    document.getElementById('wrap-varian').classList.toggle('hidden', !aktif);
}
function tambahVarian() {
    const list = document.getElementById('varian-list');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `<input type="text" name="varian[${varianIdx}][nama]" placeholder="Nama varian" class="flex-1 border rounded-lg px-3 py-2 text-sm">
        <input type="number" name="varian[${varianIdx}][harga]" placeholder="Harga" min="0" class="w-32 border rounded-lg px-3 py-2 text-sm">
        <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600">×</button>`;
    list.appendChild(div);
    varianIdx++;
}
toggleVarian(document.getElementById('punya_varian').checked);
</script>
@endsection
