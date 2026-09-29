@extends('layouts.app')

@section('title', 'Kasir Dashboard')
@section('header', 'Dashboard Kasir')

@section('header-actions')
    <form method="POST" action="{{ route('kasir.scan-table') }}" class="flex gap-2">
        @csrf
        <input type="number" name="table_number" placeholder="No. Meja" min="1" max="20"
            class="border rounded-lg px-3 py-2 text-sm w-28 focus:ring-2 focus:ring-amber-500">
        <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
            📱 Buka Meja
        </button>
    </form>
@endsection

@section('content')
    <!-- Tables Grid -->
    <div class="mb-6">
        <h3 class="text-base font-semibold text-gray-700 mb-3">Status Meja</h3>
        <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-10 gap-2">
            @foreach($tables as $table)
                <a href="{{ route('menu.show', $table->barcode_token) }}"
                    class="aspect-square rounded-xl flex flex-col items-center justify-center text-sm font-bold border-2 transition hover:scale-105
                    {{ $table->status === 'occupied' ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-50 border-green-400 text-green-700' }}">
                    <span class="text-xs">{{ $table->status === 'occupied' ? '🔴' : '🟢' }}</span>
                    {{ $table->number }}
                </a>
            @endforeach
        </div>
        <div class="flex gap-4 mt-2 text-xs text-gray-500">
            <span class="flex items-center gap-1">🟢 Tersedia</span>
            <span class="flex items-center gap-1">🔴 Terisi</span>
        </div>
    </div>

    <!-- Active Orders -->
    <div>
        <h3 class="text-base font-semibold text-gray-700 mb-3">Order Aktif ({{ $activeOrders->count() }})</h3>

        @if($activeOrders->isEmpty())
            <div class="bg-white rounded-xl border p-8 text-center text-gray-400">
                <p class="text-3xl mb-2">📋</p>
                <p>Belum ada order aktif</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($activeOrders as $order)
                    @php
                        $statusColors = [
                            'pending' => 'bg-gray-100 border-gray-300',
                            'confirmed' => 'bg-blue-50 border-blue-300',
                            'cooking' => 'bg-yellow-50 border-yellow-300',
                            'ready' => 'bg-green-50 border-green-400',
                            'delivered' => 'bg-purple-50 border-purple-300',
                        ];
                    @endphp
                    <div class="bg-white rounded-xl border-2 {{ $statusColors[$order->status] ?? 'border-gray-200' }} p-4">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <p class="font-bold text-gray-800">{{ $order->table->name }}</p>
                                <p class="text-xs text-gray-500">{{ $order->order_number }}</p>
                            </div>
                            <span class="text-xs font-semibold px-2 py-1 rounded-full
                                @if($order->status === 'pending') bg-gray-100 text-gray-600
                                @elseif($order->status === 'confirmed') bg-blue-100 text-blue-700
                                @elseif($order->status === 'cooking') bg-yellow-100 text-yellow-700
                                @elseif($order->status === 'ready') bg-green-100 text-green-700
                                @elseif($order->status === 'delivered') bg-purple-100 text-purple-700
                                @endif">
                                {{ $order->status_label }}
                            </span>
                        </div>
                        <div class="space-y-1 mb-3">
                            @foreach($order->items->take(3) as $item)
                                <p class="text-sm text-gray-600">{{ $item->quantity }}x {{ $item->product_name }}{{ $item->size ? ' ('.$item->size.')' : '' }}</p>
                            @endforeach
                            @if($order->items->count() > 3)
                                <p class="text-xs text-gray-400">+{{ $order->items->count() - 3 }} item lainnya</p>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-amber-600">Rp {{ number_format($order->total) }}</span>
                            <div class="flex gap-2">
                                <a href="{{ route('kasir.orders.show', $order) }}" class="text-xs bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded-lg">Detail</a>
                                @if(in_array($order->status, ['ready', 'delivered']))
                                    <a href="{{ route('kasir.payment.show', $order) }}" class="text-xs bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg">Bayar</a>
                                @endif
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">{{ $order->created_at->format('H:i') }} · {{ $order->created_at->diffForHumans() }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
