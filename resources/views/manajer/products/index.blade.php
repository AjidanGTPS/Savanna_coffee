@extends('layouts.app')
@section('title', 'Kelola Produk')
@section('header', 'Kelola Produk')

@section('header-actions')
    <a href="{{ route('manajer.products.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        + Tambah Produk
    </a>
@endsection

@section('content')
    @foreach($products as $categoryName => $items)
        <div class="mb-8">
            <h3 class="text-base font-bold text-gray-700 mb-3">{{ $categoryName }}</h3>
            <div class="bg-white rounded-xl border overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600">Produk</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600">Harga</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600">Status</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($items as $product)
                            <tr class="{{ !$product->is_available ? 'opacity-50' : '' }}">
                                <td class="px-4 py-3">
                                    <p class="font-medium">{{ $product->name }}</p>
                                    @if($product->has_size)
                                        <p class="text-xs text-gray-400">Ada pilihan ukuran (S/M/L)</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($product->has_size)
                                        <span class="text-gray-500 text-xs">Lihat ukuran</span>
                                    @else
                                        Rp {{ number_format($product->base_price) }}
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs px-2 py-1 rounded-full {{ $product->is_available ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $product->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right flex items-center justify-end gap-3">
                                    <form method="POST" action="{{ route('manajer.products.toggle', $product) }}">
                                        @csrf
                                        <button type="submit" class="text-xs {{ $product->is_available ? 'text-gray-500 hover:text-red-600' : 'text-green-600 hover:text-green-800' }}">
                                            {{ $product->is_available ? 'Sembunyikan' : 'Tampilkan' }}
                                        </button>
                                    </form>
                                    <a href="{{ route('manajer.products.edit', $product) }}" class="text-xs text-blue-600 hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('manajer.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@endsection
