@extends('layouts.app')
@section('title', 'Dashboard Owner')
@section('header', 'Dashboard Owner')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border p-5">
            <p class="text-sm text-gray-500">Pendapatan Bulan Ini</p>
            <p class="text-3xl font-bold text-green-600 mt-1">Rp {{ number_format($monthlyRevenue) }}</p>
        </div>
        <div class="bg-white rounded-xl border p-5">
            <p class="text-sm text-gray-500">Pengeluaran Bulan Ini</p>
            <p class="text-3xl font-bold text-red-600 mt-1">Rp {{ number_format($monthlyExpenses) }}</p>
        </div>
        <div class="bg-white rounded-xl border p-5 {{ $monthlyProfit >= 0 ? 'bg-green-50 border-green-300' : 'bg-red-50 border-red-300' }}">
            <p class="text-sm {{ $monthlyProfit >= 0 ? 'text-green-700' : 'text-red-700' }}">Laba/Rugi Bulan Ini</p>
            <p class="text-3xl font-bold {{ $monthlyProfit >= 0 ? 'text-green-700' : 'text-red-700' }} mt-1">
                {{ $monthlyProfit >= 0 ? '' : '-' }}Rp {{ number_format(abs($monthlyProfit)) }}
            </p>
        </div>
    </div>

    <!-- Quick nav -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('reports.summary') }}" class="bg-white rounded-xl border p-4 hover:shadow-md transition text-center">
            <p class="text-3xl mb-2">📊</p>
            <p class="font-medium text-gray-700">Ringkasan</p>
        </a>
        <a href="{{ route('reports.income') }}" class="bg-white rounded-xl border p-4 hover:shadow-md transition text-center">
            <p class="text-3xl mb-2">💰</p>
            <p class="font-medium text-gray-700">Pemasukan</p>
        </a>
        <a href="{{ route('reports.expenses') }}" class="bg-white rounded-xl border p-4 hover:shadow-md transition text-center">
            <p class="text-3xl mb-2">📉</p>
            <p class="font-medium text-gray-700">Pengeluaran</p>
        </a>
        <a href="{{ route('reports.purchases') }}" class="bg-white rounded-xl border p-4 hover:shadow-md transition text-center">
            <p class="text-3xl mb-2">🛍️</p>
            <p class="font-medium text-gray-700">Pembelian Bahan</p>
        </a>
    </div>

    <!-- 6-month revenue -->
    <div class="bg-white rounded-xl border p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Pendapatan 6 Bulan Terakhir</h3>
        @if($revenueByMonth->isEmpty())
            <p class="text-gray-400 text-center py-4">Belum ada data</p>
        @else
            @php $maxRev = $revenueByMonth->max('total') ?: 1; @endphp
            <div class="flex items-end gap-3 h-40">
                @foreach($revenueByMonth->reverse() as $row)
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <span class="text-xs text-gray-500">{{ number_format($row->total / 1000000, 1) }}M</span>
                        <div class="w-full bg-green-500 rounded-t" style="height: {{ ($row->total / $maxRev) * 140 }}px; min-height: 4px"></div>
                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::create($row->year, $row->month)->format('M') }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
