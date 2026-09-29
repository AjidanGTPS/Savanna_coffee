<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran — SAVANA Coffee</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background: #F5F3FF; min-height: 100vh; }
        @keyframes pulse-ring { 0%,100%{opacity:.4;transform:scale(1)} 50%{opacity:.8;transform:scale(1.06)} }
        .pulse { animation: pulse-ring 1.8s ease-in-out infinite; }
    </style>
</head>
<body>

{{-- Header --}}
<header class="sticky top-0 z-10 shadow-md" style="background:linear-gradient(135deg,#1C0A00,#2d1207)">
    <div class="max-w-lg mx-auto px-4 py-4 flex items-center gap-3">
        <a href="/menu/{{ $barcode }}" class="text-white/60 hover:text-white text-2xl leading-none">←</a>
        <div>
            <p class="text-xs text-amber-400 font-semibold">SAVANA Coffee · {{ $meja->nama }}</p>
            <h1 class="font-extrabold text-white text-base">
                @if($metode === 'qris') 📱 Pembayaran QRIS
                @else 💵 Bayar di Kasir
                @endif
            </h1>
        </div>
    </div>
</header>

<div class="max-w-lg mx-auto px-4 py-5 pb-10">

    {{-- Total card --}}
    <div class="rounded-2xl p-5 text-white text-center mb-5 shadow-lg"
        style="background:linear-gradient(135deg,{{ $metode==='qris' ? '#6366f1,#4f46e5' : '#d97706,#ea580c' }})">
        <p class="text-sm font-semibold opacity-75">Total Tagihan</p>
        <p class="text-4xl font-extrabold mt-1">Rp {{ number_format($total) }}</p>
        @if($persen_pajak > 0 || $persen_layanan > 0)
        <p class="text-xs opacity-60 mt-1">
            Sudah termasuk pajak ({{ $persen_pajak }}%)
            @if($persen_layanan > 0) + layanan ({{ $persen_layanan }}%) @endif
        </p>
        @endif
    </div>

    @if($metode === 'qris')
    {{-- =================== PANEL QRIS =================== --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 space-y-5">

        <div class="text-center">
            <p class="text-sm font-bold text-gray-700 mb-1">Scan QR ini dengan aplikasi bank / e-wallet kamu</p>

            {{-- Merchant QRIS QR --}}
            <div class="inline-block p-4 rounded-2xl border-4 border-indigo-100 bg-white shadow-md mx-auto">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)
                    ->generate('SAVANA-COFFEE|' . $sesi->id . '|' . $total . '|' . $meja->nama) !!}
            </div>
            <p class="text-xs text-indigo-500 font-semibold mt-3">
                Scan → Masukkan nominal → Bayar
            </p>
        </div>

        {{-- Item ringkasan --}}
        <div class="bg-indigo-50 rounded-xl p-4 space-y-1.5">
            @foreach($sesi->pesanan->where('status','!=','dibatalkan') as $p)
            @foreach($p->items as $item)
            <div class="flex justify-between text-sm">
                <span class="text-gray-700">{{ $item->jumlah }}× {{ $item->nama_produk }}{{ $item->nama_varian ? ' ('.$item->nama_varian.')' : '' }}</span>
                <span class="font-semibold text-gray-800">Rp {{ number_format($item->subtotal) }}</span>
            </div>
            @endforeach
            @endforeach
        </div>

        {{-- Tombol Saya Sudah Bayar --}}
        <button id="btn-sudah-bayar" onclick="konfirmasiQris()"
            class="w-full py-4 rounded-2xl font-extrabold text-white text-base transition active:scale-[.98] shadow-lg"
            style="background:linear-gradient(135deg,#059669,#047857)">
            ✅ Saya Sudah Bayar
        </button>

        <p class="text-xs text-gray-400 text-center">
            Pastikan pembayaran berhasil di aplikasi bank kamu sebelum menekan tombol ini.
        </p>
    </div>

    @else
    {{-- =================== PANEL TUNAI =================== --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 space-y-5">

        <div class="text-center">
            <p class="text-sm font-bold text-gray-700 mb-1">Tunjukkan QR ini ke kasir</p>
            <p class="text-xs text-gray-400 mb-4">Kasir akan scan QR untuk memproses pembayaran tunai</p>

            {{-- Kasir-scan QR --}}
            <div class="inline-block p-4 rounded-2xl border-4 border-amber-100 bg-white shadow-md mx-auto">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($urlKasir) !!}
            </div>

            <div class="mt-4 flex items-center justify-center gap-2">
                <div class="w-2.5 h-2.5 rounded-full bg-amber-400 pulse"></div>
                <p class="text-sm font-semibold text-amber-600" id="status-tunai">Menunggu konfirmasi kasir...</p>
            </div>
        </div>

        {{-- Item ringkasan --}}
        <div class="bg-amber-50 rounded-xl p-4 space-y-1.5">
            @foreach($sesi->pesanan->where('status','!=','dibatalkan') as $p)
            @foreach($p->items as $item)
            <div class="flex justify-between text-sm">
                <span class="text-gray-700">{{ $item->jumlah }}× {{ $item->nama_produk }}{{ $item->nama_varian ? ' ('.$item->nama_varian.')' : '' }}</span>
                <span class="font-semibold text-gray-800">Rp {{ number_format($item->subtotal) }}</span>
            </div>
            @endforeach
            @endforeach
            <div class="pt-2 border-t border-amber-200 flex justify-between font-bold">
                <span class="text-gray-800">Total</span>
                <span style="color:#d97706">Rp {{ number_format($total) }}</span>
            </div>
        </div>

        <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-xs text-amber-700 text-center">
            💡 Pergi ke kasir terdekat dan tunjukkan QR di atas. Halaman ini akan otomatis update setelah kasir mengkonfirmasi.
        </div>
    </div>
    @endif

</div>

<script>
const BARCODE = '{{ $barcode }}';
const SESI_ID = {{ $sesi->id }};
const TOKEN   = '{{ $token }}';
const CSRF    = document.querySelector('meta[name="csrf-token"]').content;
const METODE  = '{{ $metode }}';

@if($metode === 'qris')
async function konfirmasiQris() {
    const btn = document.getElementById('btn-sudah-bayar');
    btn.disabled = true;
    btn.textContent = 'Memverifikasi...';

    try {
        const res = await fetch(`/menu/${BARCODE}/konfirmasi-qris/${SESI_ID}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ token: TOKEN }),
        });
        const data = await res.json();
        if (data.success && data.redirect) {
            window.location.href = data.redirect;
        } else {
            btn.disabled = false;
            btn.textContent = '✅ Saya Sudah Bayar';
            alert(data.message || 'Gagal memverifikasi. Coba lagi.');
        }
    } catch (e) {
        btn.disabled = false;
        btn.textContent = '✅ Saya Sudah Bayar';
        alert('Gagal menghubungi server. Coba lagi.');
    }
}
@else
// Polling untuk tunai — cek setiap 3 detik apakah kasir sudah konfirmasi
async function cekStatusBayar() {
    try {
        const res = await fetch(`/menu/${BARCODE}/cek-status/${SESI_ID}`);
        const data = await res.json();
        if (data.dibayar && data.redirect) {
            document.getElementById('status-tunai').textContent = 'Pembayaran dikonfirmasi! Mengalihkan...';
            window.location.href = data.redirect;
        }
    } catch(e) {}
}

// Start polling
setInterval(cekStatusBayar, 3000);
@endif
</script>
</body>
</html>
