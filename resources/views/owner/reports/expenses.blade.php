@extends('layouts.app')
@section('title', 'Laporan Pengeluaran')
@section('header', 'Laporan Pengeluaran')

@section('content')
    @php
        $months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    @endphp

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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-red-50 border border-red-200 rounded-xl p-5">
            <p class="text-sm text-red-600">Total Pengeluaran — {{ $months[$month-1] }} {{ $year }}</p>
            <p class="text-3xl font-bold text-red-700 mt-1">Rp {{ number_format($totalExpenses) }}</p>
            <p class="text-sm text-red-400 mt-1">{{ $expenses->count() }} item</p>
        </div>
        <div class="bg-white rounded-xl border p-5">
            <p class="text-sm text-gray-600 font-medium mb-3">Per Kategori</p>
            <div class="space-y-2">
                @foreach($byCategory as $cat => $total)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ \App\Models\Expense::make(['category' => $cat])->category_label }}</span>
                        <span class="font-semibold">Rp {{ number_format($total) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Keterangan</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Kategori</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Jumlah</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($expenses as $expense)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $expense->title }}</td>
                        <td class="px-4 py-3"><span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ $expense->category_label }}</span></td>
                        <td class="px-4 py-3 text-right font-semibold text-red-600">Rp {{ number_format($expense->amount) }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $expense->date->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Tidak ada pengeluaran pada periode ini</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
