<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Selesai — SAVANA Coffee</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background: #F0FDF4; min-height: 100vh; }
        @keyframes bounce-in { 0%{opacity:0;transform:scale(.6)} 70%{transform:scale(1.1)} 100%{opacity:1;transform:scale(1)} }
        .bounce-in { animation: bounce-in .6s cubic-bezier(.34,1.56,.64,1) forwards; }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
        .float { animation: float 3s ease-in-out infinite; }
    </style>
</head>
<body>

<div class="max-w-lg mx-auto px-4 py-8">

    {{-- Success animation --}}
    <div class="text-center mb-8">
        <div class="float inline-block text-7xl mb-4">☕</div>
        <div class="bounce-in">
            <h1 class="text-2xl font-extrabold text-gray-800">Pesanan Dikonfirmasi!</h1>
            <p class="text-gray-500 text-sm mt-2">Tim kami sedang menyiapkan pesanan kamu</p>
        </div>
    </div>

    {{-- Info card --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-5">
        <div class="h-1.5" style="background:linear-gradient(90deg,#d97706,#ea580c,#d97706)"></div>
        <div class="p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="font-extrabold text-gray-800 text-base">SAVANA Coffee</p>
                    <p class="text-xs text-gray-400">{{ $meja->nama }} · {{ now()->format('d M Y, H:i') }}</p>
                </div>
                @if($sesi->pembayaran)
                <div class="text-right">
                    <p class="text-xs text-gray-400">{{ $sesi->pembayaran->nomor_faktur }}</p>
                    <span class="inline-block text-xs font-bold px-2 py-0.5 rounded-full mt-0.5"
                        style="{{ $sesi->pembayaran->metode === 'qris' ? 'background:#eef2ff;color:#4f46e5' : 'background:#fffbeb;color:#d97706' }}">
                        {{ $sesi->pembayaran->metode_label }}
                    </span>
                </div>
                @endif
            </div>

            {{-- Items --}}
            <div class="space-y-1.5 text-sm mb-4">
                @foreach($sesi->pesanan->where('status','!=','dibatalkan') as $p)
                @foreach($p->items as $item)
                <div class="flex justify-between">
                    <span class="text-gray-700">{{ $item->jumlah }}× {{ $item->nama_produk }}{{ $item->nama_varian ? ' ('.$item->nama_varian.')' : '' }}</span>
                    <span class="font-semibold text-gray-800">Rp {{ number_format($item->subtotal) }}</span>
                </div>
                @endforeach
                @endforeach
            </div>

            @if($sesi->pembayaran)
            <div class="border-t border-dashed border-gray-100 pt-4 flex justify-between font-extrabold text-base">
                <span class="text-gray-800">Total Dibayar</span>
                <span style="color:#059669">Rp {{ number_format($sesi->pembayaran->total) }}</span>
            </div>
            @if($sesi->pembayaran->kembalian > 0)
            <div class="flex justify-between text-sm mt-1.5">
                <span class="text-gray-500">Kembalian</span>
                <span class="font-semibold text-emerald-600">Rp {{ number_format($sesi->pembayaran->kembalian) }}</span>
            </div>
            @endif
            @endif
        </div>
    </div>

    {{-- Status pesanan --}}
    <div class="bg-white rounded-2xl shadow-sm p-5 mb-5">
        <p class="font-bold text-gray-800 text-sm mb-4">Status Pesanan</p>
        <div class="flex items-center gap-3">
            <div class="flex flex-col items-center gap-1">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm"
                    style="background:linear-gradient(135deg,#059669,#047857)">✓</div>
                <div class="w-0.5 h-6 bg-gray-200"></div>
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm bg-gray-100">
                    <span class="text-gray-400">⏳</span>
                </div>
                <div class="w-0.5 h-6 bg-gray-200"></div>
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm bg-gray-100">
                    <span class="text-gray-400">🛎</span>
                </div>
            </div>
            <div class="flex flex-col gap-4">
                <div>
                    <p class="text-sm font-bold text-emerald-600">Pembayaran Dikonfirmasi</p>
                    <p class="text-xs text-gray-400">Pesanan masuk ke dapur</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500">Sedang Disiapkan</p>
                    <p class="text-xs text-gray-400">Tim kami sedang bekerja</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500">Siap Diantar</p>
                    <p class="text-xs text-gray-400">Pesanan akan dibawa ke meja</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Action --}}
    <a href="/menu/{{ $meja->barcode }}"
        class="block w-full text-center py-4 rounded-2xl font-bold text-white text-base shadow-lg transition active:scale-[.98]"
        style="background:linear-gradient(135deg,#d97706,#ea580c)">
        ☕ Lihat Menu / Tambah Pesanan
    </a>

    <p class="text-center text-xs text-gray-400 mt-4">Terima kasih telah berkunjung ke SAVANA Coffee! ✨</p>
</div>

</body>
</html>
