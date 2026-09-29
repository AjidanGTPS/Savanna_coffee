@extends('layouts.app')
@section('title', 'Laporan Ringkasan')
@section('header', 'Laporan Ringkasan')
@section('subheader', 'Statistik penjualan per periode')

@section('content')

{{-- Filter --}}
<div class="flex flex-wrap items-center gap-3 mb-6">
    <form method="GET" class="flex items-center gap-2">
        <select name="bulan"
            class="border-2 border-gray-200 focus:border-amber-500 rounded-xl px-3 py-2 text-sm outline-none bg-white">
            @foreach(range(1,12) as $b)
            <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                {{ DateTime::createFromFormat('!m', $b)->format('F') }}
            </option>
            @endforeach
        </select>
        <select name="tahun"
            class="border-2 border-gray-200 focus:border-amber-500 rounded-xl px-3 py-2 text-sm outline-none bg-white">
            @foreach(range(now()->year, now()->year-2) as $t)
            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
        <button type="submit"
            class="px-4 py-2 rounded-xl text-sm font-bold text-white transition"
            style="background:linear-gradient(135deg,#d97706,#ea580c)">
            Tampilkan
        </button>
    </form>
    <div class="flex gap-2 ml-auto">
        <a href="{{ route('laporan.penjualan', request()->query()) }}"
            class="px-4 py-2 rounded-xl text-sm font-semibold border-2 border-amber-500 text-amber-600 hover:bg-amber-50 transition">
            Detail Transaksi →
        </a>
        <a href="{{ route('laporan.produk-terlaris', request()->query()) }}"
            class="px-4 py-2 rounded-xl text-sm font-semibold border-2 border-amber-500 text-amber-600 hover:bg-amber-50 transition">
            Produk Terlaris →
        </a>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-5 mb-6">
    <div class="rounded-2xl p-6" style="background:linear-gradient(135deg,#78350f,#92400e)">
        <p class="text-amber-300 text-xs font-bold uppercase tracking-wider mb-2">Total Pendapatan</p>
        <p class="text-white text-3xl font-extrabold">Rp {{ number_format($total_pendapatan) }}</p>
    </div>
    <div class="rounded-2xl p-6" style="background:linear-gradient(135deg,#1e3a5f,#1e40af)">
        <p class="text-blue-300 text-xs font-bold uppercase tracking-wider mb-2">Jumlah Transaksi</p>
        <p class="text-white text-3xl font-extrabold">{{ number_format($jumlah_transaksi) }}</p>
    </div>
    <div class="rounded-2xl p-6" style="background:linear-gradient(135deg,#064e3b,#065f46)">
        <p class="text-emerald-300 text-xs font-bold uppercase tracking-wider mb-2">Rata-rata per Transaksi</p>
        <p class="text-white text-3xl font-extrabold">Rp {{ number_format($rata_rata) }}</p>
    </div>
</div>

{{-- Per-day chart --}}
<div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
    <h3 class="font-bold text-gray-800 mb-5">📅 Pendapatan per Hari</h3>
    @forelse($per_hari as $row)
    @php $pct = $total_pendapatan > 0 ? ($row->pendapatan / $total_pendapatan * 100) : 0; @endphp
    <div class="flex items-center gap-3 mb-3">
        <span class="text-sm text-gray-400 w-16 shrink-0">{{ \Carbon\Carbon::parse($row->tanggal)->format('d M') }}</span>
        <div class="flex-1 bg-gray-100 rounded-full h-5 overflow-hidden">
            <div class="h-5 rounded-full transition-all" style="width:{{ max(2, $pct) }}%;background:linear-gradient(90deg,#f59e0b,#ea580c)"></div>
        </div>
        <span class="text-sm font-semibold text-gray-700 w-36 text-right">Rp {{ number_format($row->pendapatan) }}</span>
        <span class="text-xs text-gray-400 w-14 text-right shrink-0">{{ $row->transaksi }} trx</span>
    </div>
    @empty
    <div class="text-center py-12">
        <p class="text-4xl mb-3">📭</p>
        <p class="text-gray-400">Belum ada data untuk periode ini.</p>
    </div>
    @endforelse
</div>
@endsection
