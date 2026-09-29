@extends('layouts.app')
@section('title', 'Laporan Pemasukan')
@section('header', 'Laporan Pemasukan')

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

    <div class="bg-green-50 border border-green-200 rounded-xl p-5 mb-6">
        <p class="text-sm text-green-600">Total Pemasukan — {{ $months[$month-1] }} {{ $year }}</p>
        <p class="text-3xl font-bold text-green-700 mt-1">Rp {{ number_format($totalIncome) }}</p>
        <p class="text-sm text-green-500 mt-1">{{ $orders->count() }} transaksi</p>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">No. Order</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Meja</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Pembayaran</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Total</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($orders as $order)
                    <tr>
                        <td class="px-4 py-3 font-mono">{{ $order->order_number }}</td>
                        <td class="px-4 py-3">{{ $order->table->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $order->payment?->method_label ?? '—' }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-green-600">Rp {{ number_format($order->total) }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $order->created_at->format('d M, H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Tidak ada data pemasukan pada periode ini</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
