@extends('layouts.app')
@section('title', 'Pembayaran')
@section('header', 'Pembayaran — '.$sesi->meja->nama)

@section('content')
<div class="max-w-xl">

    <a href="{{ route('kasir.sesi.show', $sesi) }}"
        class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-gray-700 mb-5 transition">
        ← Kembali ke Detail Sesi
    </a>

    {{-- Bill summary --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-5 border border-gray-100">
        <h3 class="font-bold text-gray-800 text-base mb-4 flex items-center gap-2">
            <span class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-sm"
                style="background:linear-gradient(135deg,#d97706,#ea580c)">📋</span>
            Ringkasan Tagihan
        </h3>
        <div class="space-y-2.5 text-sm">
            @foreach($sesi->pesanan->where('status','!=','dibatalkan') as $p)
            @foreach($p->items as $item)
            <div class="flex justify-between text-gray-600">
                <span>{{ $item->jumlah }}× {{ $item->nama_produk }}{{ $item->nama_varian ? ' ('.$item->nama_varian.')' : '' }}</span>
                <span>Rp {{ number_format($item->subtotal) }}</span>
            </div>
            @endforeach
            @endforeach
            <div class="flex justify-between text-gray-400 border-t border-dashed border-gray-200 pt-2.5">
                <span>Subtotal</span>
                <span>Rp {{ number_format($subtotal) }}</span>
            </div>
            @if($persen_pajak > 0)
            <div class="flex justify-between text-gray-400">
                <span>Pajak ({{ $persen_pajak }}%)</span>
                <span>Rp {{ number_format($jumlah_pajak) }}</span>
            </div>
            @endif
            @if($persen_layanan > 0)
            <div class="flex justify-between text-gray-400">
                <span>Layanan ({{ $persen_layanan }}%)</span>
                <span>Rp {{ number_format($jumlah_layanan) }}</span>
            </div>
            @endif
        </div>
        <div class="flex justify-between items-center border-t border-dashed border-gray-200 mt-4 pt-4">
            <span class="font-bold text-gray-800 text-base">TOTAL</span>
            <span class="font-extrabold text-2xl text-amber-600">Rp {{ number_format($total) }}</span>
        </div>
    </div>

    {{-- Metode pilihan --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-4 border border-gray-100">
        <label class="block text-sm font-bold text-gray-700 mb-3">Pilih Metode Pembayaran</label>
        <div class="grid grid-cols-2 gap-3">
            <button type="button" id="btn-tunai" onclick="pilihMetode('tunai')"
                class="metode-btn border-2 rounded-xl p-4 text-center transition select-none"
                style="border-color:#d97706;background:#fffbeb">
                <span class="text-3xl block mb-1.5">💵</span>
                <span class="text-sm font-bold text-amber-700">Tunai</span>
            </button>
            <button type="button" id="btn-qris" onclick="pilihMetode('qris')"
                class="metode-btn border-2 rounded-xl p-4 text-center transition select-none"
                style="border-color:#e5e7eb;background:#fff">
                <span class="text-3xl block mb-1.5">📱</span>
                <span class="text-sm font-bold text-gray-600">QRIS</span>
            </button>
        </div>
    </div>

    {{-- Panel TUNAI --}}
    <form id="form-tunai" method="POST" action="{{ route('kasir.bayar.proses', $sesi) }}"
        class="bg-white rounded-2xl shadow-sm p-6 space-y-5 border border-gray-100">
        @csrf
        <input type="hidden" name="metode" value="tunai">

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1.5">Jumlah Dibayar</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                <input type="number" name="jumlah_dibayar" id="jumlah-dibayar" min="{{ $total }}"
                    value="{{ old('jumlah_dibayar', $total) }}"
                    class="w-full border-2 border-gray-200 focus:border-amber-500 rounded-xl pl-10 pr-4 py-3 text-sm outline-none transition font-semibold">
            </div>
            <div class="flex justify-between text-sm mt-3 px-1">
                <span class="text-gray-500">Kembalian</span>
                <span id="kembalian" class="font-bold text-emerald-600 text-base">Rp 0</span>
            </div>
        </div>

        {{-- Nominal shortcuts --}}
        <div class="grid grid-cols-4 gap-2">
            @foreach([50000, 100000, 150000, 200000] as $n)
            <button type="button"
                onclick="document.getElementById('jumlah-dibayar').value={{ $n }};hitungKembalian()"
                class="text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 py-2 rounded-xl transition">
                {{ number_format($n, 0, ',', '.') }}
            </button>
            @endforeach
        </div>
        <div class="grid grid-cols-4 gap-2">
            @foreach([500000, 1000000, 0, 0] as $idx => $n)
            @if($n > 0)
            <button type="button"
                onclick="document.getElementById('jumlah-dibayar').value={{ $n }};hitungKembalian()"
                class="text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 py-2 rounded-xl transition">
                {{ number_format($n, 0, ',', '.') }}
            </button>
            @else
            <button type="button"
                onclick="document.getElementById('jumlah-dibayar').value={{ $total }};hitungKembalian()"
                class="text-xs font-semibold text-gray-600 bg-gray-50 hover:bg-gray-100 border border-gray-200 py-2 rounded-xl transition col-span-2">
                Uang Pas ({{ number_format($total, 0, ',', '.') }})
            </button>
            @break
            @endif
            @endforeach
        </div>

        @error('jumlah_dibayar') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

        <button type="submit"
            class="w-full font-bold text-white py-4 rounded-xl text-base transition active:scale-[.98]"
            style="background:linear-gradient(135deg,#059669,#047857)">
            ✅ Konfirmasi Pembayaran Tunai
        </button>
    </form>

    {{-- Panel QRIS --}}
    <div id="panel-qris" class="hidden bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">

        {{-- Header --}}
        <div class="px-6 py-4 text-white text-center" style="background:linear-gradient(135deg,#6366f1,#4f46e5)">
            <p class="text-sm font-semibold opacity-80">Tagihan untuk</p>
            <p class="font-bold text-xl">Rp {{ number_format($total) }}</p>
            <p class="text-xs opacity-70 mt-0.5">{{ $sesi->meja->nama }}</p>
        </div>

        <div class="p-6 space-y-6">
            {{-- QR kode merchant (sebagai referensi / MPM) --}}
            <div class="text-center">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">QR Merchant</p>
                <div class="inline-block p-3 bg-white border-2 border-indigo-200 rounded-2xl shadow-sm">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(160)->generate(
                        'SAVANA-COFFEE|'.$sesi->id.'|'.$total.'|'.$sesi->meja->nama
                    ) !!}
                </div>
                <p class="text-xs text-gray-400 mt-2">Scan QR ini atau minta pelanggan menunjukkan QRIS mereka</p>
            </div>

            {{-- Scanner input --}}
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <div class="h-px flex-1 bg-gray-200"></div>
                    <span class="text-xs text-gray-400 font-semibold px-2">SCAN QRIS PELANGGAN</span>
                    <div class="h-px flex-1 bg-gray-200"></div>
                </div>

                <form id="form-qris" method="POST" action="{{ route('kasir.bayar.qris', $sesi) }}">
                    @csrf
                    <input type="hidden" name="nomor_referensi" id="qris-ref-hidden">

                    <div class="relative">
                        <input type="text" id="qris-scan-input"
                            class="w-full border-2 border-indigo-300 focus:border-indigo-500 rounded-xl px-4 py-3.5 text-sm outline-none transition font-mono tracking-wide"
                            placeholder="Arahkan scanner ke QR pelanggan..."
                            autocomplete="off" spellcheck="false">
                        <div id="qris-scan-indicator"
                            class="absolute right-3 top-1/2 -translate-y-1/2 w-3 h-3 rounded-full bg-gray-300"></div>
                    </div>

                    <p class="text-xs text-gray-400 mt-1.5 text-center">
                        Scanner akan otomatis memproses saat QR berhasil dibaca
                    </p>

                    <button type="submit" id="btn-konfirmasi-qris"
                        class="w-full mt-4 font-bold text-white py-4 rounded-xl text-base transition active:scale-[.98] disabled:opacity-40"
                        style="background:linear-gradient(135deg,#6366f1,#4f46e5)"
                        disabled>
                        ✅ Konfirmasi Pembayaran QRIS
                    </button>
                </form>
            </div>

            {{-- Manual confirm note --}}
            <div class="p-3 bg-indigo-50 rounded-xl border border-indigo-100">
                <p class="text-xs text-indigo-600 text-center">
                    💡 Jika menggunakan QRIS statis, scan QR merchant di atas dengan aplikasi bank pelanggan,
                    lalu masukkan nomor referensi transaksi dari notifikasi bank di kolom scanner.
                </p>
            </div>
        </div>
    </div>

</div>

<script>
const TOTAL = {{ $total }};
const tunaiBtn  = document.getElementById('btn-tunai');
const qrisBtn   = document.getElementById('btn-qris');
const formTunai = document.getElementById('form-tunai');
const panelQris = document.getElementById('panel-qris');
const bayarInput = document.getElementById('jumlah-dibayar');
const kembalianEl = document.getElementById('kembalian');
const scanInput = document.getElementById('qris-scan-input');
const scanIndicator = document.getElementById('qris-scan-indicator');
const btnKonfirmasi = document.getElementById('btn-konfirmasi-qris');
const refHidden = document.getElementById('qris-ref-hidden');

function pilihMetode(m) {
    if (m === 'tunai') {
        tunaiBtn.style.cssText = 'border-color:#d97706;background:#fffbeb';
        tunaiBtn.querySelector('span:last-child').className = 'text-sm font-bold text-amber-700';
        qrisBtn.style.cssText = 'border-color:#e5e7eb;background:#fff';
        qrisBtn.querySelector('span:last-child').className = 'text-sm font-bold text-gray-600';
        formTunai.classList.remove('hidden');
        panelQris.classList.add('hidden');
    } else {
        qrisBtn.style.cssText = 'border-color:#6366f1;background:#eef2ff';
        qrisBtn.querySelector('span:last-child').className = 'text-sm font-bold text-indigo-700';
        tunaiBtn.style.cssText = 'border-color:#e5e7eb;background:#fff';
        tunaiBtn.querySelector('span:last-child').className = 'text-sm font-bold text-gray-600';
        formTunai.classList.add('hidden');
        panelQris.classList.remove('hidden');
        setTimeout(() => scanInput?.focus(), 100);
    }
}

function hitungKembalian() {
    const bayar = parseInt(bayarInput?.value || 0);
    const kembalian = bayar - TOTAL;
    kembalianEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.max(0, kembalian));
    kembalianEl.className = kembalian >= 0
        ? 'font-bold text-base text-emerald-600'
        : 'font-bold text-base text-red-500';
}

// QRIS scanner logic
let scanTimer = null;
if (scanInput) {
    scanInput.addEventListener('input', () => {
        const val = scanInput.value.trim();
        clearTimeout(scanTimer);

        if (val.length >= 1) {
            scanIndicator.style.background = '#eab308'; // yellow = scanning
            btnKonfirmasi.disabled = false;
        }

        // Auto-submit after 600ms silence (scanner inputs are instant, human is slow)
        scanTimer = setTimeout(() => {
            if (val.length >= 6) {
                refHidden.value = val;
                scanIndicator.style.background = '#22c55e'; // green = ready
                document.getElementById('form-qris').submit();
            }
        }, 600);
    });

    scanInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const val = scanInput.value.trim();
            if (val.length >= 1) {
                refHidden.value = val;
                document.getElementById('form-qris').submit();
            }
        }
    });
}

document.getElementById('form-qris')?.addEventListener('submit', (e) => {
    if (!refHidden.value) {
        refHidden.value = scanInput?.value?.trim() || 'MANUAL';
    }
});

bayarInput?.addEventListener('input', hitungKembalian);
hitungKembalian();
// Default: tunai selected
pilihMetode('tunai');
</script>
@endsection
