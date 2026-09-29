@extends('layouts.app')
@section('title', 'Dashboard Gudang')
@section('header', 'Dashboard Kepala Gudang')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border p-4">
            <p class="text-sm text-gray-500">Total Bahan Baku</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalMaterials }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4 {{ $lowStockMaterials->count() > 0 ? 'border-red-300 bg-red-50' : '' }}">
            <p class="text-sm {{ $lowStockMaterials->count() > 0 ? 'text-red-600' : 'text-gray-500' }}">Stok Kritis</p>
            <p class="text-2xl font-bold {{ $lowStockMaterials->count() > 0 ? 'text-red-600' : 'text-gray-800' }} mt-1">{{ $lowStockMaterials->count() }}</p>
        </div>
        <a href="{{ route('gudang.purchases.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white rounded-xl p-4 flex items-center justify-center gap-2 font-semibold">
            + Catat Pembelian Bahan
        </a>
    </div>

    @if($lowStockMaterials->count() > 0)
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
            <h3 class="font-semibold text-red-700 mb-3">⚠️ Bahan Baku Hampir Habis</h3>
            <div class="space-y-2">
                @foreach($lowStockMaterials as $material)
                    <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2">
                        <span class="font-medium text-gray-700">{{ $material->name }}</span>
                        <span class="text-red-600 font-semibold">{{ $material->current_stock }} {{ $material->unit }} <span class="text-xs text-gray-400">(min: {{ $material->minimum_stock }})</span></span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div>
        <h3 class="font-semibold text-gray-700 mb-3">Pembelian Terbaru</h3>
        <div class="bg-white rounded-xl border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Bahan</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600">Qty</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600">Total</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($recentPurchases as $purchase)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $purchase->rawMaterial->name }}</td>
                            <td class="px-4 py-3 text-right">{{ $purchase->quantity }} {{ $purchase->rawMaterial->unit }}</td>
                            <td class="px-4 py-3 text-right font-semibold">Rp {{ number_format($purchase->total_price) }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $purchase->purchased_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400">Belum ada pembelian</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
