@extends('layouts.app')
@section('title', 'Detail Penjualan')
@section('header', 'Detail Penjualan')
@section('subheader', 'Riwayat transaksi per periode')

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
                <th class="px-5 py-3.5 text-left text-xs font-bold text-amber-300 uppercase tracking-wider">No. Faktur</th>
                <th class="px-5 py-3.5 text-left text-xs font-bold text-amber-300 uppercase tracking-wider">Meja</th>
                <th class="px-5 py-3.5 text-left text-xs font-bold text-amber-300 uppercase tracking-wider">Kasir</th>
                <th class="px-5 py-3.5 text-left text-xs font-bold text-amber-300 uppercase tracking-wider">Metode</th>
                <th class="px-5 py-3.5 text-right text-xs font-bold text-amber-300 uppercase tracking-wider">Total</th>
                <th class="px-5 py-3.5 text-left text-xs font-bold text-amber-300 uppercase tracking-wider">Waktu</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($pembayaran as $pb)
            <tr class="hover:bg-amber-50/30 transition">
                <td class="px-5 py-3.5 font-mono text-xs text-amber-700 font-semibold">{{ $pb->nomor_faktur }}</td>
                <td class="px-5 py-3.5 font-medium text-gray-800">{{ $pb->sesiMeja->meja->nama }}</td>
                <td class="px-5 py-3.5 text-gray-500">{{ $pb->kasir?->nama ?? '-' }}</td>
                <td class="px-5 py-3.5">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                        {{ $pb->metode === 'tunai' ? 'bg-green-100 text-green-700' : ($pb->metode === 'kartu' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                        {{ $pb->metode_label }}
                    </span>
                </td>
                <td class="px-5 py-3.5 text-right font-bold text-amber-600">Rp {{ number_format($pb->total) }}</td>
                <td class="px-5 py-3.5 text-gray-400 text-xs">{{ $pb->dibayar_pada?->format('d/m H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-16 text-center">
                    <p class="text-4xl mb-3">📭</p>
                    <p class="text-gray-400">Tidak ada data untuk periode ini.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($pembayaran->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $pembayaran->links() }}</div>
    @endif
</div>
@endsection
