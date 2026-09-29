@extends('layouts.app')
@section('title', 'Tambah Produk')
@section('header', 'Tambah Produk')

@section('content')
    <div class="max-w-lg">
        <div class="bg-white rounded-xl border p-6">
            <form method="POST" action="{{ route('manajer.products.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="category_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" required>
                        <option value="">Pilih kategori...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                    <input type="text" name="name" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" required value="{{ old('name') }}">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="2" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="has_size" id="has-size" value="1" {{ old('has_size') ? 'checked' : '' }} class="rounded" onchange="toggleSizeFields()">
                        <span class="text-sm font-medium text-gray-700">Ada pilihan ukuran (S/M/L)</span>
                    </label>
                </div>
                <div id="base-price-field">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                    <input type="number" name="base_price" min="0" step="1000" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" value="{{ old('base_price') }}">
                    @error('base_price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div id="size-fields" class="hidden space-y-2">
                    @foreach(['S' => 'Small', 'M' => 'Medium', 'L' => 'Large'] as $size => $label)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga {{ $label }} ({{ $size }})</label>
                            <input type="number" name="sizes[{{ $size }}]" min="0" step="1000" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" value="{{ old('sizes.'.$size) }}">
                        </div>
                    @endforeach
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Produk</label>
                    <input type="file" name="image" accept="image/*" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2.5 rounded-lg">Simpan</button>
                    <a href="{{ route('manajer.products.index') }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-lg">Batal</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleSizeFields() {
            const hasSize = document.getElementById('has-size').checked;
            document.getElementById('base-price-field').classList.toggle('hidden', hasSize);
            document.getElementById('size-fields').classList.toggle('hidden', !hasSize);
        }
        toggleSizeFields();
    </script>
    @endpush
@endsection
