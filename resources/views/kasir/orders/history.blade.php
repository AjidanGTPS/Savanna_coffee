@extends('layouts.app')

@section('title', 'Riwayat Order')
@section('header', 'Riwayat Order')

@section('content')
    <div class="overflow-hidden rounded-xl border bg-white">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Order</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Meja</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Status</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Pembayaran</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Total</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Tanggal</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono font-medium">{{ $order->order_number }}</td>
                        <td class="px-4 py-3">{{ $order->table->name }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $order->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $order->payment?->method_label ?? '—' }}</td>
                        <td class="px-4 py-3 text-right font-semibold">Rp {{ number_format($order->total) }}</td>
                        <td class="px-4 py-3 text-right text-gray-400 text-xs">{{ $order->created_at->format('d M, H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            @if($order->status === 'paid')
                                <a href="{{ route('kasir.payment.receipt', $order) }}" class="text-xs text-blue-600 hover:underline">Struk</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $orders->links() }}</div>
    </div>
@endsection
