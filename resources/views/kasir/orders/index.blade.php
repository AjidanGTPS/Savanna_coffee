@extends('layouts.app')

@section('title', 'Order Aktif')
@section('header', 'Order Aktif')

@section('content')
    @if($orders->isEmpty())
        <div class="bg-white rounded-xl border p-10 text-center text-gray-400">
            <p class="text-4xl mb-3">📋</p>
            <p class="text-lg font-medium">Tidak ada order aktif</p>
        </div>
    @else
        <div class="overflow-hidden rounded-xl border bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Order</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Meja</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Status</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Item</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600">Total</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600">Waktu</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-mono font-medium">{{ $order->order_number }}</td>
                            <td class="px-4 py-3 font-medium">{{ $order->table->name }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($order->status === 'pending') bg-gray-100 text-gray-600
                                    @elseif($order->status === 'confirmed') bg-blue-100 text-blue-700
                                    @elseif($order->status === 'cooking') bg-yellow-100 text-yellow-700
                                    @elseif($order->status === 'ready') bg-green-100 text-green-700
                                    @elseif($order->status === 'delivered') bg-purple-100 text-purple-700
                                    @endif">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $order->items->count() }} item</td>
                            <td class="px-4 py-3 text-right font-semibold text-amber-600">Rp {{ number_format($order->total) }}</td>
                            <td class="px-4 py-3 text-right text-gray-400 text-xs">{{ $order->created_at->format('H:i') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('kasir.orders.show', $order) }}" class="text-xs text-blue-600 hover:underline">Detail</a>
                                @if(in_array($order->status, ['ready', 'delivered']))
                                    · <a href="{{ route('kasir.payment.show', $order) }}" class="text-xs text-green-600 hover:underline">Bayar</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
