@extends('layouts.app')
@section('title', 'Riwayat Transaksi')
@section('header', 'Riwayat Transaksi')
@section('subheader', 'Semua sesi yang telah selesai')

@section('content')
<div class="bg-white rounded-2xl shadow-sm overflow-x-auto border border-gray-100">
    <table class="w-full text-sm min-w-[760px]">
        <thead>
            <tr style="background:linear-gradient(135deg,#1C0A00,#2d1207)">
                <th class="px-5 py-3.5 text-left text-xs font-bold text-amber-300 uppercase tracking-wider">No. Faktur</th>
                <th class="px-5 py-3.5 text-left text-xs font-bold text-amber-300 uppercase tracking-wider">Meja</th>
                <th class="px-5 py-3.5 text-left text-xs font-bold text-amber-300 uppercase tracking-wider">Kasir</th>
                <th class="px-5 py-3.5 text-left text-xs font-bold text-amber-300 uppercase tracking-wider">Metode</th>
                <th class="px-5 py-3.5 text-right text-xs font-bold text-amber-300 uppercase tracking-wider">Total</th>
                <th class="px-5 py-3.5 text-left text-xs font-bold text-amber-300 uppercase tracking-wider">Waktu</th>
                <th class="px-5 py-3.5"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($sesi as $s)
            @php $pb = $s->pembayaran @endphp
            <tr class="hover:bg-amber-50/20 transition">
                <td class="px-5 py-3.5 font-mono text-xs text-amber-700 font-semibold">{{ $pb?->nomor_faktur ?? '—' }}</td>
                <td class="px-5 py-3.5 font-semibold text-gray-800">{{ $s->meja->nama }}</td>
                <td class="px-5 py-3.5 text-gray-500">{{ $pb?->kasir?->nama ?? '—' }}</td>
                <td class="px-5 py-3.5">
                    @if($pb)
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                        {{ $pb->metode === 'tunai' ? 'bg-green-100 text-green-700' : ($pb->metode === 'kartu' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                        {{ $pb->metode_label }}
                    </span>
                    @else —
                    @endif
                </td>
                <td class="px-5 py-3.5 text-right font-bold text-amber-600">
                    {{ $pb ? 'Rp ' . number_format($pb->total) : '—' }}
                </td>
                <td class="px-5 py-3.5 text-gray-400 text-xs">{{ $s->ditutup_pada?->format('d/m/Y H:i') ?? '—' }}</td>
                <td class="px-5 py-3.5 text-right">
                    @if($pb)
                    <a href="{{ route('kasir.struk', $pb) }}"
                        class="text-xs font-semibold text-amber-600 hover:text-amber-800 transition">Struk →</a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-5 py-16 text-center">
                    <p class="text-4xl mb-3">🗒</p>
                    <p class="text-gray-400">Belum ada transaksi.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($sesi->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $sesi->links() }}</div>
    @endif
</div>
@endsection
