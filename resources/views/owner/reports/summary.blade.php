@extends('layouts.app')
@section('title', 'Laporan Ringkasan')
@section('header', 'Laporan Ringkasan Keuangan')

@section('content')
    @php
        $months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    @endphp

    <!-- Filter -->
    <form method="GET" class="flex gap-3 mb-6">
        <select name="month" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ $months[$m-1] }}</option>
            @endfor
        </select>
        <select name="year" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500">
            @for($y = now()->year; $y >= now()->year - 3; $y--)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
        <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-green-50 border border-green-200 rounded-xl p-5">
            <p class="text-sm text-green-600 font-medium">Total Pemasukan</p>
            <p class="text-3xl font-bold text-green-700 mt-1">Rp {{ number_format($income) }}</p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-xl p-5">
            <p class="text-sm text-red-600 font-medium">Total Pengeluaran</p>
            <p class="text-3xl font-bold text-red-700 mt-1">Rp {{ number_format($totalExpenses) }}</p>
            <p class="text-xs text-red-400 mt-1">Ops: Rp {{ number_format($expenses) }} + Bahan: Rp {{ number_format($purchases) }}</p>
        </div>
        <div class="border-2 rounded-xl p-5 {{ $profit >= 0 ? 'bg-emerald-50 border-emerald-300' : 'bg-orange-50 border-orange-300' }}">
            <p class="text-sm {{ $profit >= 0 ? 'text-emerald-600' : 'text-orange-600' }} font-medium">{{ $profit >= 0 ? 'Laba Bersih' : 'Rugi' }}</p>
            <p class="text-3xl font-bold {{ $profit >= 0 ? 'text-emerald-700' : 'text-orange-700' }} mt-1">
                {{ $profit < 0 ? '-' : '' }}Rp {{ number_format(abs($profit)) }}
            </p>
        </div>
    </div>

    <!-- Annual chart -->
    <div class="bg-white rounded-xl border p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Perbandingan Pemasukan vs Pengeluaran {{ $year }}</h3>
        @php $maxVal = max(array_merge(array_column($monthlyData, 'income'), array_column($monthlyData, 'expenses'))) ?: 1; @endphp
        <div class="flex items-end gap-2 h-40">
            @foreach($monthlyData as $m => $data)
                <div class="flex-1 flex flex-col items-center gap-1">
                    <div class="w-full flex gap-0.5 items-end" style="height: 120px">
                        <div class="flex-1 bg-green-400 rounded-t" style="height: {{ ($data['income'] / $maxVal) * 120 }}px; min-height: {{ $data['income'] > 0 ? 2 : 0 }}px"></div>
                        <div class="flex-1 bg-red-400 rounded-t" style="height: {{ ($data['expenses'] / $maxVal) * 120 }}px; min-height: {{ $data['expenses'] > 0 ? 2 : 0 }}px"></div>
                    </div>
                    <span class="text-xs text-gray-400">{{ $months[$m-1][0] }}</span>
                </div>
            @endforeach
        </div>
        <div class="flex gap-4 mt-3 text-xs text-gray-500">
            <span class="flex items-center gap-1"><span class="w-3 h-3 bg-green-400 rounded inline-block"></span> Pemasukan</span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 bg-red-400 rounded inline-block"></span> Pengeluaran</span>
        </div>
    </div>
@endsection
