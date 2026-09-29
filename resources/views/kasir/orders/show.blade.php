@extends('layouts.app')

@section('title', 'Detail Order ' . $order->order_number)
@section('header', 'Detail Order — ' . $order->order_number)

@section('header-actions')
    <a href="{{ route('kasir.orders.index') }}" class="text-sm text-gray-600 hover:text-gray-800">← Kembali</a>
@endsection

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl border p-6 mb-4">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-xl font-bold">{{ $order->table->name }}</h3>
                    <p class="text-gray-500">{{ $order->order_number }} · {{ $order->created_at->format('d M Y H:i') }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    @if($order->status === 'pending') bg-gray-100 text-gray-600
                    @elseif($order->status === 'confirmed') bg-blue-100 text-blue-700
                    @elseif($order->status === 'cooking') bg-yellow-100 text-yellow-700
                    @elseif($order->status === 'ready') bg-green-100 text-green-700
                    @elseif($order->status === 'delivered') bg-purple-100 text-purple-700
                    @elseif($order->status === 'paid') bg-emerald-100 text-emerald-700
                    @elseif($order->status === 'cancelled') bg-red-100 text-red-600
                    @endif">
                    {{ $order->status_label }}
                </span>
            </div>

            <!-- Order Items -->
            <table class="w-full text-sm">
                <thead class="border-b">
                    <tr class="text-gray-500 text-left">
                        <th class="pb-2">Item</th>
                        <th class="pb-2 text-center">Qty</th>
                        <th class="pb-2 text-right">Harga</th>
                        <th class="pb-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="py-3">
                                <p class="font-medium">{{ $item->product_name }}{{ $item->size ? ' ('.$item->size.')' : '' }}</p>
                                @foreach($item->options as $opt)
                                    <p class="text-xs text-gray-400">↳ {{ $opt->option_name }}{{ $opt->additional_price > 0 ? ' (+Rp '.number_format($opt->additional_price).')' : '' }}</p>
                                @endforeach
                                @if($item->notes)
                                    <p class="text-xs text-gray-400 italic">{{ $item->notes }}</p>
                                @endif
                            </td>
                            <td class="py-3 text-center">{{ $item->quantity }}</td>
                            <td class="py-3 text-right">Rp {{ number_format($item->unit_price) }}</td>
                            <td class="py-3 text-right font-medium">Rp {{ number_format($item->subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="border-t mt-4 pt-4 space-y-1">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($order->subtotal) }}</span>
                </div>
                @if($order->tax)
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Pajak</span>
                        <span>Rp {{ number_format($order->tax) }}</span>
                    </div>
                @endif
                <div class="flex justify-between font-bold text-lg pt-2 border-t">
                    <span>Total</span>
                    <span class="text-amber-600">Rp {{ number_format($order->total) }}</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        @if(!in_array($order->status, ['paid', 'cancelled']))
            <div class="flex gap-3">
                @if($order->status !== 'paid')
                    <a href="{{ route('kasir.payment.show', $order) }}" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-3 rounded-xl font-semibold">
                        💳 Proses Pembayaran
                    </a>
                @endif
                <form method="POST" action="{{ route('kasir.orders.update-status', $order) }}" class="flex-1">
                    @csrf
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" onclick="return confirm('Batalkan order ini?')"
                        class="w-full bg-red-100 hover:bg-red-200 text-red-700 py-3 rounded-xl font-semibold">
                        ❌ Batalkan Order
                    </button>
                </form>
            </div>
        @elseif($order->status === 'paid')
            <a href="{{ route('kasir.payment.receipt', $order) }}" class="block text-center bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-semibold">
                🧾 Lihat Struk
            </a>
        @endif
    </div>
@endsection
