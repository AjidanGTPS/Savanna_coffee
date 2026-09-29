@extends('layouts.app')
@section('title', 'Laporan Pembelian Bahan')
@section('header', 'Laporan Pembelian Bahan Baku')

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
        <div class="bg-orange-50 border border-orange-200 rounded-xl p-5">
            <p class="text-sm text-orange-600">Total Pembelian Bahan — {{ $months[$month-1] }} {{ $year }}</p>
            <p class="text-3xl font-bold text-orange-700 mt-1">Rp {{ number_format($totalPurchases) }}</p>
            <p class="text-sm text-orange-400 mt-1">{{ $purchases->count() }} transaksi</p>
        </div>
        <div class="bg-white rounded-xl border p-5">
            <p class="text-sm text-gray-600 font-medium mb-3">Per Bahan Baku</p>
            <div class="space-y-2 max-h-40 overflow-y-auto">
                @foreach($byMaterial as $name => $data)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 truncate">{{ $name }}</span>
                        <span class="font-semibold shrink-0 ml-2">Rp {{ number_format($data['total_price']) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Bahan Baku</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Qty</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Harga/Satuan</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Total</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Supplier</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($purchases as $purchase)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $purchase->rawMaterial->name }}</td>
                        <td class="px-4 py-3 text-right">{{ $purchase->quantity }} {{ $purchase->rawMaterial->unit }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($purchase->price_per_unit) }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-orange-600">Rp {{ number_format($purchase->total_price) }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $purchase->supplier ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $purchase->purchased_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Tidak ada pembelian bahan pada periode ini</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
