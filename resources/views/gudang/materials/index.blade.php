@extends('layouts.app')
@section('title', 'Bahan Baku')
@section('header', 'Manajemen Bahan Baku')

@section('header-actions')
    <a href="{{ route('gudang.materials.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        + Tambah Bahan
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-xl border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Nama Bahan</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Kategori</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Stok Saat Ini</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Stok Minimum</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($materials as $material)
                    <tr class="{{ $material->isLowStock() ? 'bg-red-50' : '' }}">
                        <td class="px-4 py-3 font-medium">{{ $material->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $material->category ?? '—' }}</td>
                        <td class="px-4 py-3 text-right font-semibold {{ $material->isLowStock() ? 'text-red-600' : 'text-gray-800' }}">
                            {{ $material->current_stock }} {{ $material->unit }}
                        </td>
                        <td class="px-4 py-3 text-right text-gray-500">{{ $material->minimum_stock }} {{ $material->unit }}</td>
                        <td class="px-4 py-3">
                            @if($material->isLowStock())
                                <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">⚠️ Kritis</span>
                            @else
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">✅ Aman</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('gudang.materials.edit', $material) }}" class="text-xs text-blue-600 hover:underline">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
