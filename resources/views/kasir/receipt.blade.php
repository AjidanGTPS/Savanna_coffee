@extends('layouts.app')

@section('title', 'Struk — ' . $order->order_number)
@section('header', 'Struk Pembayaran')

@section('content')
    <div class="max-w-sm mx-auto">
        <div class="bg-white rounded-xl border p-6 font-mono text-sm" id="receipt">
            <div class="text-center mb-4">
                <h2 class="text-lg font-bold">☕ SAVANA Coffee</h2>
                <p class="text-xs text-gray-500">Jl. Contoh No. 1, Jakarta</p>
                <p class="text-xs text-gray-500">Telp: (021) 000-0000</p>
            </div>

            <div class="border-t border-dashed pt-3 mb-3 space-y-1 text-xs">
                <div class="flex justify-between">
                    <span>No. Order</span>
                    <span class="font-bold">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Meja</span>
                    <span>{{ $order->table->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Kasir</span>
                    <span>{{ $order->payment->receiver->name ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Waktu</span>
                    <span>{{ $order->payment->paid_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <div class="border-t border-dashed pt-3 mb-3 space-y-2">
                @foreach($order->items as $item)
                    <div>
                        <div class="flex justify-between">
                            <span>{{ $item->quantity }}x {{ $item->product_name }}{{ $item->size ? '('.$item->size.')' : '' }}</span>
                            <span>{{ number_format($item->subtotal) }}</span>
                        </div>
                        @foreach($item->options as $opt)
                            <p class="text-xs text-gray-400 ml-3">- {{ $opt->option_name }}</p>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <div class="border-t border-dashed pt-3 space-y-1 text-xs">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($order->subtotal) }}</span>
                </div>
                <div class="flex justify-between font-bold text-base mt-1">
                    <span>TOTAL</span>
                    <span>Rp {{ number_format($order->total) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Pembayaran</span>
                    <span>{{ $order->payment->method_label }}</span>
                </div>
                @if($order->payment->cash_received)
                    <div class="flex justify-between">
                        <span>Diterima</span>
                        <span>Rp {{ number_format($order->payment->cash_received) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Kembalian</span>
                        <span>Rp {{ number_format($order->payment->change_returned) }}</span>
                    </div>
                @endif
            </div>

            <div class="border-t border-dashed mt-3 pt-3 text-center text-xs text-gray-400">
                <p>Terima kasih sudah mengunjungi</p>
                <p class="font-semibold">SAVANA Coffee!</p>
                <p>Sampai jumpa lagi ☕</p>
            </div>
        </div>

        <div class="mt-4 flex gap-3">
            <button onclick="window.print()" class="flex-1 bg-gray-800 hover:bg-gray-900 text-white py-3 rounded-xl font-semibold">
                🖨️ Cetak Struk
            </button>
            <a href="{{ route('dashboard') }}" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white text-center py-3 rounded-xl font-semibold">
                🏠 Dashboard
            </a>
        </div>
    </div>
@endsection
