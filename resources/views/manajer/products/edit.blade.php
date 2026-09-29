@extends('layouts.app')
@section('title', 'Edit Produk')
@section('header', 'Edit — ' . $product->name)

@section('content')
    <div class="max-w-lg">
        <div class="bg-white rounded-xl border p-6">
            <form method="POST" action="{{ route('manajer.products.update', $product) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="category_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                    <input type="text" name="name" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" required value="{{ old('name', $product->name) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="2" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500">{{ old('description', $product->description) }}</textarea>
                </div>
                @if($product->has_size)
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-gray-700">Harga per Ukuran</p>
                        @foreach(['S' => 'Small', 'M' => 'Medium', 'L' => 'Large'] as $size => $label)
                            @php $sizePrice = $product->sizePrices->firstWhere('size', $size); @endphp
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">{{ $label }} ({{ $size }})</label>
                                <input type="number" name="sizes[{{ $size }}]" min="0" step="1000"
                                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500"
                                    value="{{ old('sizes.'.$size, $sizePrice?->price) }}">
                            </div>
                        @endforeach
                    </div>
                @else
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                        <input type="number" name="base_price" min="0" step="1000"
                            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500"
                            value="{{ old('base_price', $product->base_price) }}">
                    </div>
                @endif
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_available" value="1" {{ $product->is_available ? 'checked' : '' }} class="rounded">
                        <span class="text-sm font-medium text-gray-700">Produk tersedia di menu</span>
                    </label>
                </div>
                <div>
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" class="w-20 h-20 object-cover rounded-lg mb-2">
                    @endif
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ganti Foto (opsional)</label>
                    <input type="file" name="image" accept="image/*" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2.5 rounded-lg">Perbarui</button>
                    <a href="{{ route('manajer.products.index') }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-lg">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
