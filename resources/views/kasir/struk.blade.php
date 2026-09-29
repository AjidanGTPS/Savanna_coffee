@extends('layouts.app')
@section('title', 'Struk — '.$pembayaran->nomor_faktur)
@section('header', 'Pembayaran Selesai')

@section('content')
<div class="max-w-sm mx-auto">

    {{-- Success banner --}}
    <div class="rounded-2xl p-6 text-center mb-5"
        style="background:linear-gradient(135deg,#064e3b,#065f46)">
        <div class="text-5xl mb-3">✅</div>
        <h2 class="text-xl font-extrabold text-white">Pembayaran Berhasil!</h2>
        <p class="text-emerald-300 text-sm mt-1">{{ $pembayaran->nomor_faktur }}</p>
        <div class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold"
            style="background:rgba(255,255,255,0.15);color:#fff">
            @if($pembayaran->metode === 'tunai')
            💵 Tunai
            @else
            📱 QRIS
            @endif
            @if($pembayaran->nomor_referensi)
            · <span class="font-mono opacity-80">{{ $pembayaran->nomor_referensi }}</span>
            @endif
        </div>
    </div>

    {{-- Receipt card --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100" id="struk-print">

        <div class="h-1.5" style="background:linear-gradient(90deg,#d97706,#ea580c,#d97706)"></div>

        <div class="p-6">
            {{-- Store header --}}
            <div class="text-center border-b border-dashed border-gray-200 pb-4 mb-4">
                <p class="text-2xl">☕</p>
                <p class="font-extrabold text-gray-800 text-lg">SAVANA Coffee</p>
                <p class="text-gray-400 text-xs mt-1">{{ $pembayaran->dibayar_pada?->format('d M Y, H:i') }}</p>
                <p class="text-gray-400 text-xs">Kasir: {{ $pembayaran->kasir?->nama ?? '-' }}</p>
                <p class="text-gray-400 text-xs">Meja: {{ $pembayaran->sesiMeja->meja->nama }}</p>
            </div>

            {{-- Items --}}
            <div class="space-y-1.5 text-sm font-mono mb-4">
                @foreach($pembayaran->sesiMeja->pesanan->where('status','!=','dibatalkan') as $p)
                @foreach($p->items as $item)
                <div class="flex justify-between">
                    <span class="text-gray-700 flex-1 pr-2">{{ $item->jumlah }}× {{ $item->nama_produk }}{{ $item->nama_varian ? ' ('.$item->nama_varian.')' : '' }}</span>
                    <span class="text-gray-700 shrink-0">{{ number_format($item->subtotal) }}</span>
                </div>
                @if($item->opsi->count())
                <div class="text-gray-400 text-xs pl-4">+ {{ $item->opsi->map->nama_opsi->implode(', ') }}</div>
                @endif
                @endforeach
                @endforeach
            </div>

            {{-- Totals --}}
            <div class="border-t border-dashed border-gray-200 pt-4 space-y-1.5 text-sm font-mono">
                <div class="flex justify-between text-gray-500">
                    <span>Subtotal</span><span>{{ number_format($pembayaran->subtotal) }}</span>
                </div>
                @if($pembayaran->jumlah_pajak > 0)
                <div class="flex justify-between text-gray-500">
                    <span>Pajak ({{ $pembayaran->persen_pajak }}%)</span>
                    <span>{{ number_format($pembayaran->jumlah_pajak) }}</span>
                </div>
                @endif
                @if($pembayaran->jumlah_layanan > 0)
                <div class="flex justify-between text-gray-500">
                    <span>Layanan ({{ $pembayaran->persen_layanan }}%)</span>
                    <span>{{ number_format($pembayaran->jumlah_layanan) }}</span>
                </div>
                @endif
                <div class="flex justify-between font-extrabold text-gray-900 border-t border-dashed border-gray-200 pt-2 mt-2 text-base">
                    <span>TOTAL</span><span>Rp {{ number_format($pembayaran->total) }}</span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>{{ $pembayaran->metode_label }}</span>
                    <span>{{ number_format($pembayaran->jumlah_dibayar) }}</span>
                </div>
                @if($pembayaran->kembalian > 0)
                <div class="flex justify-between font-semibold text-emerald-600">
                    <span>Kembalian</span><span>{{ number_format($pembayaran->kembalian) }}</span>
                </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="text-center mt-5 pt-4 border-t border-dashed border-gray-200 text-xs text-gray-400">
                Terima kasih telah berkunjung!<br>
                <span class="font-semibold">SAVANA Coffee</span>
            </div>
        </div>
    </div>

    {{-- Cetak --}}
    <div class="mt-4">
        <button onclick="window.print()"
            class="w-full py-3 rounded-xl font-semibold text-sm border-2 border-gray-200 text-gray-600 hover:bg-gray-50 transition flex items-center justify-center gap-2">
            🖨 Cetak Struk
        </button>
    </div>

    {{-- Pilihan lanjutan --}}
    <div class="mt-4 bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
        <p class="text-sm font-bold text-gray-700 mb-4 text-center">Apa yang ingin dilakukan selanjutnya?</p>
        <div class="grid grid-cols-2 gap-3">

            {{-- Take Away --}}
            <a href="{{ route('kasir.meja') }}"
                class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-amber-200 bg-amber-50 hover:bg-amber-100 transition text-center group">
                <span class="text-3xl group-hover:scale-110 transition-transform">🥡</span>
                <span class="text-sm font-bold text-amber-700">Take Away</span>
                <span class="text-xs text-amber-500">Selesai, kembali ke meja</span>
            </a>

            {{-- Tambah Pesanan --}}
            <form method="POST" action="{{ route('kasir.struk.sesi-baru', $pembayaran) }}">
                @csrf
                <button type="submit"
                    class="w-full flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-indigo-200 bg-indigo-50 hover:bg-indigo-100 transition text-center group h-full">
                    <span class="text-3xl group-hover:scale-110 transition-transform">➕</span>
                    <span class="text-sm font-bold text-indigo-700">Tambah Pesanan</span>
                    <span class="text-xs text-indigo-500">Buka sesi baru meja ini</span>
                </button>
            </form>

        </div>
    </div>

</div>

<style>
@media print {
    aside, header, .mt-4, .mt-5:last-child { display: none !important; }
    .ml-60 { margin-left: 0 !important; }
    .max-w-sm { max-width: 100%; }
    #struk-print { box-shadow: none !important; border: none !important; }
}
</style>
@endsection
