@extends('layouts.app')

@section('title', 'Dashboard Manajer')
@section('header', 'Dashboard Manajer')

@section('content')
    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border p-4">
            <p class="text-sm text-gray-500">Pendapatan Hari Ini</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">Rp {{ number_format($todayRevenue) }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-sm text-gray-500">Order Hari Ini</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $todayOrders }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-sm text-gray-500">Order Aktif</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $activeOrders }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4 {{ $lowStockMaterials > 0 ? 'border-red-300 bg-red-50' : '' }}">
            <p class="text-sm {{ $lowStockMaterials > 0 ? 'text-red-600' : 'text-gray-500' }}">Bahan Baku Kritis</p>
            <p class="text-2xl font-bold {{ $lowStockMaterials > 0 ? 'text-red-600' : 'text-gray-800' }} mt-1">{{ $lowStockMaterials }}</p>
        </div>
    </div>

    <!-- Weekly Revenue Chart -->
    <div class="bg-white rounded-xl border p-6 mb-6">
        <h3 class="font-semibold text-gray-800 mb-4">Pendapatan Minggu Ini</h3>
        @if($weeklyRevenue->isEmpty())
            <p class="text-gray-400 text-center py-4">Belum ada data minggu ini</p>
        @else
            @php $maxRevenue = $weeklyRevenue->max('total') ?: 1; @endphp
            <div class="flex items-end gap-3 h-32">
                @foreach($weeklyRevenue as $day)
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <span class="text-xs text-gray-500">{{ number_format($day->total / 1000) }}K</span>
                        <div class="w-full bg-amber-500 rounded-t" style="height: {{ ($day->total / $maxRevenue) * 100 }}px; min-height: 4px"></div>
                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($day->date)->format('D') }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Quick links -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('manajer.products.index') }}" class="bg-white rounded-xl border p-4 hover:shadow-md transition text-center">
            <p class="text-3xl mb-2">🍽️</p>
            <p class="font-medium text-gray-700">Kelola Produk</p>
        </a>
        <a href="{{ route('manajer.tables.index') }}" class="bg-white rounded-xl border p-4 hover:shadow-md transition text-center">
            <p class="text-3xl mb-2">🪑</p>
            <p class="font-medium text-gray-700">Kelola Meja</p>
        </a>
        <a href="{{ route('manajer.users.index') }}" class="bg-white rounded-xl border p-4 hover:shadow-md transition text-center">
            <p class="text-3xl mb-2">👥</p>
            <p class="font-medium text-gray-700">Kelola Pengguna</p>
        </a>
        <a href="{{ route('reports.summary') }}" class="bg-white rounded-xl border p-4 hover:shadow-md transition text-center">
            <p class="text-3xl mb-2">📊</p>
            <p class="font-medium text-gray-700">Laporan</p>
        </a>
    </div>
@endsection
