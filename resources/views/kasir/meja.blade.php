@extends('layouts.app')
@section('title', 'Kelola Meja')
@section('header', 'Kelola Meja')
@section('subheader', 'Status real-time semua meja')

@section('content')

{{-- Stat bar --}}
@php
    $kosong  = $meja->where('status','kosong')->count();
    $terisi  = $meja->where('status','terisi')->count();
    $pesan   = $meja->where('status','dipesan')->count();
@endphp
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="rounded-2xl p-5 flex items-center gap-4" style="background:linear-gradient(135deg,#064e3b,#065f46)">
        <span class="text-3xl">🪑</span>
        <div>
            <p class="text-emerald-300 text-xs font-semibold uppercase tracking-wider">Kosong</p>
            <p class="text-white text-3xl font-extrabold">{{ $kosong }}</p>
        </div>
    </div>
    <div class="rounded-2xl p-5 flex items-center gap-4" style="background:linear-gradient(135deg,#7f1d1d,#991b1b)">
        <span class="text-3xl">🔴</span>
        <div>
            <p class="text-red-300 text-xs font-semibold uppercase tracking-wider">Terisi</p>
            <p class="text-white text-3xl font-extrabold">{{ $terisi }}</p>
        </div>
    </div>
    <div class="rounded-2xl p-5 flex items-center gap-4" style="background:linear-gradient(135deg,#78350f,#92400e)">
        <span class="text-3xl">📋</span>
        <div>
            <p class="text-amber-300 text-xs font-semibold uppercase tracking-wider">Dipesan</p>
            <p class="text-white text-3xl font-extrabold">{{ $pesan }}</p>
        </div>
    </div>
</div>

{{-- Table grid --}}
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
    @foreach($meja as $m)
    @php $sesi = $m->sesiAktif; @endphp
    <div class="rounded-2xl overflow-hidden border-2 transition hover:scale-[1.02]
        @if($m->status === 'kosong')   border-emerald-400 @elseif($m->status === 'terisi')  border-red-400    @else border-amber-400 @endif"
        style="background:
        @if($m->status === 'kosong')   linear-gradient(145deg,#f0fdf4,#dcfce7)
        @elseif($m->status === 'terisi')  linear-gradient(145deg,#fef2f2,#fee2e2)
        @else linear-gradient(145deg,#fffbeb,#fef3c7) @endif">

        {{-- Header --}}
        <div class="px-4 pt-4 pb-3">
            <div class="flex items-start justify-between mb-1">
                <p class="font-extrabold text-gray-800 text-lg leading-tight">{{ $m->nama }}</p>
                <span class="text-lg">
                    @if($m->status === 'kosong') ✅
                    @elseif($m->status === 'terisi') 🔴
                    @else 📋 @endif
                </span>
            </div>
            <p class="text-xs text-gray-400">
                {{ $m->kapasitas }} kursi{{ $m->area ? ' · ' . $m->area : '' }}
            </p>
        </div>

        {{-- Status badge + action --}}
        <div class="px-4 pb-4">
            <span class="inline-block text-xs font-bold px-2.5 py-1 rounded-full mb-3
                @if($m->status === 'kosong')   bg-emerald-100 text-emerald-700
                @elseif($m->status === 'terisi')  bg-red-100 text-red-700
                @else bg-amber-100 text-amber-700 @endif">
                {{ $m->status_label }}
            </span>

            @if($sesi)
            <p class="text-xs text-gray-500 mb-2">
                {{ $sesi->pesanan->whereNotIn('status',['dibatalkan'])->count() }} pesanan aktif
            </p>
            <a href="{{ route('kasir.sesi.show', $sesi) }}"
                class="block text-center text-xs font-bold text-white py-2 rounded-xl transition active:scale-95"
                style="background:linear-gradient(135deg,#d97706,#ea580c)">
                Lihat Detail →
            </a>
            @else
            <p class="text-xs text-gray-400 mb-2">Meja kosong</p>
            <a href="{{ route('menu.show', $m->barcode) }}" target="_blank"
                class="block text-center text-xs font-semibold text-gray-500 bg-white py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                Buka Menu ↗
            </a>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection
