@extends('layouts.app')
@section('title', 'Pembelian Bahan')
@section('header', 'Riwayat Pembelian Bahan Baku')

@section('header-actions')
    <a href="{{ route('gudang.purchases.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        + Catat Pembelian
    </a>
@endsection

@section('content')
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
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Dicatat oleh</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($purchases as $purchase)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $purchase->rawMaterial->name }}</td>
                        <td class="px-4 py-3 text-right">{{ $purchase->quantity }} {{ $purchase->rawMaterial->unit }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($purchase->price_per_unit) }}</td>
                        <td class="px-4 py-3 text-right font-semibold">Rp {{ number_format($purchase->total_price) }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $purchase->supplier ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $purchase->purchased_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $purchase->purchasedBy->name }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada pembelian tercatat</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $purchases->links() }}</div>
    </div>
@endsection
