@extends('layouts.app')
@section('title', 'Edit Produk')
@section('header', 'Edit — '.$produk->nama)

@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.produk.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">← Kembali</a>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.produk.update', $produk) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select name="kategori_id" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
                    @foreach($kategoris as $kat)
                    <option value="{{ $kat->id }}" {{ $produk->kategori_id == $kat->id ? 'selected' : '' }}>
                        {{ $kat->induk?->nama ? $kat->induk->nama.' > ' : '' }}{{ $kat->nama }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                <input type="text" name="nama" value="{{ old('nama', $produk->nama) }}" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
            </div>

            @if(!$produk->punya_varian)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                <input type="number" name="harga_dasar" value="{{ old('harga_dasar', $produk->harga_dasar) }}" min="0" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
            </div>
            @else
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Varian (read-only)</label>
                @foreach($produk->varian as $v)
                <div class="flex justify-between text-sm text-gray-600 py-1 border-b">
                    <span>{{ $v->nama }}</span><span>Rp {{ number_format($v->harga) }}</span>
                </div>
                @endforeach
            </div>
            @endif

            @if($produk->gambar)
            <div>
                <p class="text-sm text-gray-500 mb-1">Foto saat ini:</p>
                <img src="{{ Storage::url($produk->gambar) }}" class="w-24 h-24 object-cover rounded-lg">
            </div>
            @endif
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ganti Foto</label>
                <input type="file" name="gambar" accept="image/*" class="text-sm text-gray-500">
            </div>

            <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2.5 rounded-lg transition">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
