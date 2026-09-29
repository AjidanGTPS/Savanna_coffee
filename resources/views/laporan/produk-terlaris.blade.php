@extends('layouts.app')
@section('title', 'Produk Terlaris')
@section('header', 'Produk Terlaris')
@section('subheader', 'Ranking produk berdasarkan penjualan')

@section('content')
<div class="flex flex-wrap items-center gap-3 mb-6">
    <a href="{{ route('laporan.ringkasan', request()->query()) }}"
        class="text-sm text-gray-400 hover:text-gray-700 flex items-center gap-1.5 transition">
        ← Ringkasan
    </a>
    <form method="GET" class="flex items-center gap-2 ml-auto">
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
            Filter
        </button>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
    <table class="w-full text-sm">
        <thead>
            <tr style="background:linear-gradient(135deg,#1C0A00,#2d1207)">
                <th class="px-5 py-3.5 text-left text-xs font-bold text-amber-300 uppercase tracking-wider w-14">#</th>
                <th class="px-5 py-3.5 text-left text-xs font-bold text-amber-300 uppercase tracking-wider">Produk</th>
                <th class="px-5 py-3.5 text-center text-xs font-bold text-amber-300 uppercase tracking-wider">Terjual</th>
                <th class="px-5 py-3.5 text-right text-xs font-bold text-amber-300 uppercase tracking-wider">Total Penjualan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($produk as $i => $p)
            <tr class="hover:bg-amber-50/30 transition">
                <td class="px-5 py-4">
                    @if($i < 3)
                    <span class="font-extrabold text-xl">{{ ['🥇','🥈','🥉'][$i] }}</span>
                    @else
                    <span class="text-gray-400 font-semibold">{{ $i + 1 }}</span>
                    @endif
                </td>
                <td class="px-5 py-4">
                    <span class="font-semibold text-gray-800">{{ $p->nama_produk }}</span>
                    @if($p->nama_varian)
                    <span class="text-gray-400 text-xs ml-1">({{ $p->nama_varian }})</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-center">
                    <span class="inline-block px-3 py-1 rounded-full font-bold text-sm"
                        style="background:linear-gradient(135deg,#fef3c7,#fde68a);color:#92400e">
                        {{ number_format($p->total_terjual) }}
                    </span>
                </td>
                <td class="px-5 py-4 text-right font-bold text-amber-600">Rp {{ number_format($p->total_penjualan) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-5 py-16 text-center">
                    <p class="text-4xl mb-3">📭</p>
                    <p class="text-gray-400">Belum ada data untuk periode ini.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
