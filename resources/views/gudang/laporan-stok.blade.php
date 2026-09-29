@extends('layouts.app')
@section('title', 'Laporan Stok Bahan Baku')
@section('header', 'Laporan Stok')
@section('subheader', 'Pantau kondisi stok seluruh bahan baku')

@section('actions')
<a href="{{ route('gudang.bahan-baku.index') }}"
    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 bg-white border border-gray-200 shadow-sm hover:bg-gray-50 transition">
    ← Kembali ke Gudang
</a>
@endsection

@section('content')

{{-- Status Overview --}}
@php
    $habis  = $bahan->where('status_stok', 'habis');
    $kritis = $bahan->where('status_stok', 'kritis');
    $rendah = $bahan->where('status_stok', 'rendah');
    $aman   = $bahan->where('status_stok', 'aman');
@endphp

{{-- Alert zone --}}
@if($habis->count() || $kritis->count())
<div class="mb-6 p-5 rounded-2xl border-2 border-red-300 bg-red-50">
    <div class="flex items-center gap-2 mb-3">
        <span class="text-2xl">🚨</span>
        <h3 class="font-bold text-red-700">Perlu Tindakan Segera</h3>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        @foreach($habis->merge($kritis) as $item)
        <div class="flex items-center justify-between bg-white rounded-xl px-4 py-3 shadow-sm">
            <div>
                <p class="font-semibold text-gray-800 text-sm">{{ $item->nama }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Min: {{ fmt_stok($item->stok_minimum) }} {{ $item->satuan }}</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-lg {{ $item->status_stok === 'habis' ? 'text-red-600' : 'text-orange-600' }}">
                    {{ fmt_stok($item->stok_saat_ini) }}
                </p>
                <span class="text-xs px-2 py-0.5 rounded-full font-semibold
                    {{ $item->status_stok === 'habis' ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700' }}">
                    {{ $item->status_stok_label }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Stat cards --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-7">
    <div class="rounded-2xl p-4 text-white shadow-lg" style="background:linear-gradient(135deg,#22c55e,#16a34a)">
        <p class="text-xs font-semibold opacity-70 uppercase tracking-wide">Aman</p>
        <p class="text-3xl font-bold mt-1">{{ $aman->count() }}</p>
    </div>
    <div class="rounded-2xl p-4 text-white shadow-lg" style="background:linear-gradient(135deg,#eab308,#d97706)">
        <p class="text-xs font-semibold opacity-70 uppercase tracking-wide">Perlu Restock</p>
        <p class="text-3xl font-bold mt-1">{{ $rendah->count() }}</p>
    </div>
    <div class="rounded-2xl p-4 text-white shadow-lg" style="background:linear-gradient(135deg,#f97316,#ea580c)">
        <p class="text-xs font-semibold opacity-70 uppercase tracking-wide">Kritis</p>
        <p class="text-3xl font-bold mt-1">{{ $kritis->count() }}</p>
    </div>
    <div class="rounded-2xl p-4 text-white shadow-lg" style="background:linear-gradient(135deg,#ef4444,#dc2626)">
        <p class="text-xs font-semibold opacity-70 uppercase tracking-wide">Habis</p>
        <p class="text-3xl font-bold mt-1">{{ $habis->count() }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Tabel status semua bahan --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 font-bold text-gray-800 flex items-center gap-2">
            <span>📦</span> Kondisi Stok Semua Bahan
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($bahan as $item)
            @php
                $pct = $item->stok_minimum > 0
                    ? min(100, ($item->stok_saat_ini / ($item->stok_minimum * 2)) * 100)
                    : 100;
                $barColor = match($item->status_stok) {
                    'habis'  => '#ef4444',
                    'kritis' => '#f97316',
                    'rendah' => '#eab308',
                    default  => '#22c55e',
                };
            @endphp
            <div class="px-5 py-3">
                <div class="flex justify-between items-start mb-1.5">
                    <div>
                        <span class="font-semibold text-sm text-gray-800">{{ $item->nama }}</span>
                        <span class="text-xs text-gray-400 ml-1.5">{{ $item->satuan }}</span>
                    </div>
                    <div class="text-right">
                        <span class="font-bold text-sm text-gray-800">{{ fmt_stok($item->stok_saat_ini) }}</span>
                        <span class="text-xs text-gray-400"> / min {{ fmt_stok($item->stok_minimum) }}</span>
                    </div>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all" style="width: {{ max(2, $pct) }}%; background: {{ $barColor }}"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Riwayat transaksi --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 font-bold text-gray-800 flex items-center gap-2">
            <span>📋</span> Riwayat Transaksi (50 Terakhir)
        </div>
        <div class="overflow-y-auto max-h-[480px] divide-y divide-gray-50">
            @forelse($riwayat as $tx)
            @php
                $jenisStyle = match($tx->jenis) {
                    'masuk'       => 'background:#d1fae5;color:#059669',
                    'keluar'      => 'background:#fee2e2;color:#dc2626',
                    default       => 'background:#e0e7ff;color:#4f46e5',
                };
            @endphp
            <div class="px-5 py-3 flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="font-semibold text-sm text-gray-800 truncate">{{ $tx->bahanBaku?->nama ?? '—' }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ $tx->pengguna?->nama ?? 'Sistem' }} ·
                        {{ $tx->dicatat_pada?->locale('id')->diffForHumans() ?? '—' }}
                    </p>
                    @if($tx->keterangan)
                    <p class="text-xs text-gray-500 mt-0.5 italic">{{ $tx->keterangan }}</p>
                    @endif
                </div>
                <div class="text-right shrink-0">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold" style="{{ $jenisStyle }}">
                        {{ $tx->jenis_label }}
                    </span>
                    <p class="text-sm font-bold text-gray-800 mt-1">
                        {{ fmt_stok($tx->stok_sebelum) }} → {{ fmt_stok($tx->stok_sesudah) }}
                    </p>
                </div>
            </div>
            @empty
            <div class="text-center py-12 text-gray-400">
                <div class="text-4xl mb-2">📋</div>
                <p class="text-sm">Belum ada transaksi stok</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
