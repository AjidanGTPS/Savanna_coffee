@extends('layouts.app')
@section('title', 'Sesi — '.$sesi->meja->nama)
@section('header', $sesi->meja->nama)
@section('subheader', 'Detail pesanan & sesi aktif')

@section('actions')
@if(!$sesi->pembayaran)
<a href="{{ route('kasir.bayar.show', $sesi) }}"
    class="flex items-center gap-2 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition active:scale-95"
    style="background:linear-gradient(135deg,#059669,#047857)">
    💳 Proses Pembayaran
</a>
@endif
@endsection

@section('content')
<div class="max-w-3xl">

    {{-- Back --}}
    <a href="{{ route('kasir.meja') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-gray-700 mb-5 transition">
        ← Kembali ke Meja
    </a>

    {{-- Sesi info --}}
    <div class="rounded-2xl p-5 mb-5 border border-white"
        style="background:linear-gradient(135deg,#fef9f0,#fff7ed)">
        <div class="grid grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-400 text-xs mb-1">Meja</p>
                <p class="font-bold text-gray-800 text-lg">{{ $sesi->meja->nama }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-1">Status Sesi</p>
                <span class="inline-block font-bold px-3 py-1 rounded-full text-sm
                    {{ $sesi->status === 'buka' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ ucfirst($sesi->status) }}
                </span>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-1">Dibuka Pukul</p>
                <p class="font-semibold text-gray-800">{{ $sesi->dibuka_pada?->format('H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- Order cards --}}
    @php $grandTotal = 0 @endphp
    @foreach($sesi->pesanan as $p)
    @php if($p->status !== 'dibatalkan') $grandTotal += $p->subtotal; @endphp

    @php
        $headerBg = match($p->status) {
            'baru'      => 'linear-gradient(90deg,#fef2f2,#fee2e2)',
            'dimasak'   => 'linear-gradient(90deg,#fffbeb,#fef3c7)',
            'siap'      => 'linear-gradient(90deg,#f0fdf4,#dcfce7)',
            'diantar'   => 'linear-gradient(90deg,#eff6ff,#dbeafe)',
            default     => 'linear-gradient(90deg,#f9fafb,#f3f4f6)',
        };
        $badgeCls = match($p->status) {
            'baru'    => 'bg-red-100 text-red-700',
            'dimasak' => 'bg-yellow-100 text-yellow-700',
            'siap'    => 'bg-emerald-100 text-emerald-700',
            'diantar' => 'bg-blue-100 text-blue-700',
            default   => 'bg-gray-100 text-gray-500',
        };
    @endphp

    <div class="bg-white rounded-2xl shadow-sm mb-4 overflow-hidden border border-gray-100">
        {{-- Order header --}}
        <div class="flex items-center justify-between px-5 py-3.5 border-b" style="background: {{ $headerBg }}">
            <div class="flex items-center gap-3">
                <span class="font-bold text-gray-800 text-sm">{{ $p->nomor_pesanan }}</span>
                <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $badgeCls }}">{{ $p->status_label }}</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="font-bold text-amber-600">Rp {{ number_format($p->subtotal) }}</span>
                @if(in_array($p->status, ['baru','dimasak']))
                <form method="POST" action="{{ route('kasir.pesanan.batal', [$sesi, $p]) }}"
                    onsubmit="return confirm('Batalkan pesanan {{ $p->nomor_pesanan }}?')">
                    @csrf
                    <button class="text-xs text-red-400 hover:text-red-600 font-medium transition">✕ Batal</button>
                </form>
                @endif
            </div>
        </div>

        {{-- Items --}}
        <div class="px-5 py-4 space-y-3">
            @foreach($p->items as $item)
            <div class="flex justify-between text-sm">
                <div class="flex gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-700 text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">{{ $item->jumlah }}</span>
                    <div>
                        <span class="font-medium text-gray-800">{{ $item->nama_produk }}</span>
                        @if($item->nama_varian) <span class="text-gray-400">({{ $item->nama_varian }})</span> @endif
                        @if($item->opsi->count())
                        <p class="text-xs text-gray-400">{{ $item->opsi->pluck('nama_opsi')->implode(', ') }}</p>
                        @endif
                        @if($item->catatan)
                        <p class="text-xs text-amber-600 italic">"{{ $item->catatan }}"</p>
                        @endif
                    </div>
                </div>
                <span class="text-gray-500 font-medium">Rp {{ number_format($item->subtotal) }}</span>
            </div>
            @endforeach

            @if($p->catatan)
            <div class="bg-amber-50 border border-amber-100 rounded-xl px-3 py-2 text-xs text-gray-600">
                📝 {{ $p->catatan }}
            </div>
            @endif
        </div>
    </div>
    @endforeach

    {{-- Grand total --}}
    <div class="rounded-2xl p-5 flex justify-between items-center"
        style="background:linear-gradient(135deg,#1C0A00,#2d1207)">
        <span class="font-bold text-white">Total Semua Pesanan</span>
        <span class="font-extrabold text-2xl text-amber-400">Rp {{ number_format($grandTotal) }}</span>
    </div>
</div>
@endsection
