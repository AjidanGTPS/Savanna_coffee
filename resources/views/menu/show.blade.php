<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $meja->nama }} — SAVANA Coffee</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background: #FDF8F3; }
        .cat-btn { padding: 6px 14px; border-radius: 99px; font-size: 12px; font-weight: 600;
            color: rgba(255,255,255,.7); background: rgba(255,255,255,.1);
            border: none; cursor: pointer; transition: all .15s; white-space: nowrap; }
        .cat-btn:hover, .cat-btn.active { background: linear-gradient(135deg,#d97706,#ea580c); color: #fff; }
        .varian-btn { padding: 9px 14px; border: 2px solid #e5e7eb; border-radius: 12px;
            font-size: 13px; font-weight: 600; cursor: pointer; transition: all .15s; background: #fff; }
        .varian-btn.selected { border-color: #f59e0b; background: #fffbeb; }
    </style>
</head>
<body class="min-h-screen">

{{-- Sticky header --}}
<header class="sticky top-0 z-40 shadow-lg" style="background:linear-gradient(135deg,#1C0A00,#2d1207)">
    <div class="max-w-2xl mx-auto px-4 pt-4 pb-0 flex items-center justify-between">
        <div>
            <p class="text-xs text-amber-400 font-semibold uppercase tracking-wider">SAVANA Coffee</p>
            <h1 class="font-extrabold text-white text-lg leading-tight">☕ {{ $meja->nama }}</h1>
        </div>
        <button onclick="bukaKeranjang()"
            class="relative flex items-center gap-2 text-white text-sm font-bold px-4 py-2.5 rounded-xl transition active:scale-95"
            style="background:linear-gradient(135deg,#d97706,#ea580c)">
            🛒 Keranjang
            <span id="badge"
                class="hidden absolute -top-2 -right-2 bg-white text-amber-700 text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold shadow">0</span>
        </button>
    </div>
    {{-- Category pill nav --}}
    <div class="max-w-2xl mx-auto px-4 py-3 flex gap-2 overflow-x-auto scrollbar-hide">
        @foreach($kategoris as $indukNama => $subKategoris)
            @foreach($subKategoris as $kat)
            <button onclick="scrollKeKategori('kat-{{ $kat->id }}')" class="cat-btn">{{ $kat->nama }}</button>
            @endforeach
        @endforeach
    </div>
</header>

{{-- Product list --}}
<div class="max-w-2xl mx-auto px-4 py-5 pb-36">
    @foreach($kategoris as $indukNama => $subKategoris)
    <div class="mb-2">
        {{-- Parent category divider --}}
        <div class="flex items-center gap-3 my-5">
            <div class="h-px flex-1" style="background:linear-gradient(90deg,#f59e0b,transparent)"></div>
            <span class="text-xs font-extrabold uppercase tracking-widest text-amber-600">{{ $indukNama }}</span>
            <div class="h-px flex-1" style="background:linear-gradient(270deg,#f59e0b,transparent)"></div>
        </div>

        @foreach($subKategoris as $kat)
        <section id="kat-{{ $kat->id }}" class="mb-7 scroll-mt-36">
            <div class="flex items-center gap-2 mb-4">
                <h2 class="font-bold text-gray-800">{{ $kat->nama }}</h2>
                <div class="h-px flex-1 bg-gray-200"></div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                @foreach($kat->produkAktif as $produk)
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden cursor-pointer border border-gray-100 hover:shadow-md hover:border-amber-200 active:scale-95 transition"
                    onclick="bukaProduk({{ json_encode([
                        'id' => $produk->id,
                        'nama' => $produk->nama,
                        'deskripsi' => $produk->deskripsi,
                        'harga_dasar' => $produk->harga_dasar,
                        'punya_varian' => $produk->punya_varian,
                        'varian' => $produk->varian->map(fn($v) => ['id'=>$v->id,'nama'=>$v->nama,'harga'=>$v->harga]),
                        'grup_opsi' => $produk->grupOpsi->map(fn($g) => [
                            'id'=>$g->id,'nama'=>$g->nama,'tipe'=>$g->tipe,'wajib'=>$g->wajib,
                            'opsi'=>$g->opsi->map(fn($o) => ['id'=>$o->id,'nama'=>$o->nama,'harga_tambahan'=>$o->harga_tambahan,'bawaan'=>$o->bawaan]),
                        ]),
                    ]) }})">
                    @if($produk->gambar)
                        <img src="{{ Storage::url($produk->gambar) }}" alt="{{ $produk->nama }}" class="w-full h-28 object-cover">
                    @else
                        <div class="w-full h-28 flex items-center justify-center text-4xl"
                            style="background:linear-gradient(135deg,#fef3c7,#fde68a)">☕</div>
                    @endif
                    <div class="p-3">
                        <h3 class="font-semibold text-gray-800 text-sm leading-tight">{{ $produk->nama }}</h3>
                        <p class="font-extrabold text-sm mt-1.5" style="color:#d97706">
                            @if($produk->punya_varian && $produk->varian->count())
                                Rp {{ number_format($produk->varian->min('harga') / 1000) }}K+
                            @else
                                Rp {{ number_format($produk->harga_dasar / 1000) }}K
                            @endif
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endforeach
    </div>
    @endforeach
</div>

{{-- Modal: Produk --}}
<div id="modal-produk" class="hidden fixed inset-0 bg-black/60 z-50 flex items-end justify-center" onclick="tutupProdukLuar(event)">
    <div class="bg-white rounded-t-3xl w-full max-w-lg flex flex-col" style="max-height:92vh" onclick="event.stopPropagation()">

        {{-- Handle --}}
        <div class="pt-3 pb-1 shrink-0">
            <div class="w-10 h-1 bg-gray-200 rounded-full mx-auto"></div>
        </div>

        {{-- Fixed header --}}
        <div class="px-5 pt-2 pb-3 border-b border-gray-100 shrink-0">
            <div class="flex items-start justify-between">
                <div>
                    <h3 id="mp-nama" class="text-lg font-extrabold text-gray-800"></h3>
                    <p id="mp-harga" class="font-semibold text-sm mt-0.5" style="color:#d97706"></p>
                </div>
                <button onclick="tutupProduk()" class="text-gray-300 hover:text-gray-500 text-3xl leading-none ml-4 shrink-0">×</button>
            </div>
            <p id="mp-desc" class="text-sm text-gray-500 mt-1"></p>
        </div>

        {{-- Scrollable body --}}
        <div class="flex-1 overflow-y-auto overscroll-contain px-5 py-4 space-y-4">
            <div id="mp-varian" class="hidden">
                <p class="font-bold text-gray-700 text-sm mb-2">Pilih Varian <span class="text-red-500">*</span></p>
                <div id="mp-varian-list" class="flex flex-wrap gap-2"></div>
            </div>

            <div id="mp-opsi"></div>

            <div>
                <label class="text-sm font-bold text-gray-700 block mb-1.5">Catatan (opsional)</label>
                <textarea id="mp-catatan" rows="2"
                    placeholder="Contoh: tanpa es, ekstra pedas..."
                    class="w-full border-2 border-gray-200 focus:border-amber-400 rounded-xl px-3 py-2.5 text-sm outline-none transition resize-none"></textarea>
            </div>
        </div>

        {{-- Fixed footer: jumlah + tambah --}}
        <div class="px-5 py-4 border-t border-gray-100 shrink-0 bg-white">
            <div class="flex items-center justify-between mb-3">
                <p class="font-bold text-gray-700 text-sm">Jumlah</p>
                <div class="flex items-center gap-4">
                    <button onclick="ubahJumlah(-1)"
                        class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 font-bold text-lg flex items-center justify-center transition">−</button>
                    <span id="mp-jumlah" class="font-extrabold text-xl w-8 text-center">1</span>
                    <button onclick="ubahJumlah(1)"
                        class="w-9 h-9 rounded-full text-white font-bold text-lg flex items-center justify-center transition"
                        style="background:linear-gradient(135deg,#d97706,#ea580c)">+</button>
                </div>
            </div>
            <button onclick="tambahKeKeranjang()"
                class="w-full font-bold text-white py-4 rounded-2xl text-base transition active:scale-[.98]"
                style="background:linear-gradient(135deg,#d97706,#ea580c)">
                + Tambah — <span id="mp-total">Rp 0</span>
            </button>
        </div>
    </div>
</div>

{{-- Modal: Keranjang --}}
<div id="modal-keranjang" class="hidden fixed inset-0 bg-black/60 z-50 flex items-end justify-center" onclick="tutupKeranjangLuar(event)">
    <div class="bg-white rounded-t-3xl w-full max-w-lg flex flex-col" style="max-height:92vh" onclick="event.stopPropagation()">

        {{-- Handle --}}
        <div class="pt-3 pb-1 shrink-0">
            <div class="w-10 h-1 bg-gray-200 rounded-full mx-auto"></div>
        </div>

        {{-- Fixed header --}}
        <div class="px-5 pt-2 pb-3 shrink-0 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-extrabold text-gray-900">Keranjang</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $meja->nama }}</p>
            </div>
            <button onclick="tutupKeranjang()"
                class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 text-xl font-bold transition">×</button>
        </div>

        {{-- Scrollable body --}}
        <div class="flex-1 overflow-y-auto overscroll-contain px-5 pb-2">

            {{-- Kosong --}}
            <div id="k-kosong" class="text-center py-14 text-gray-400">
                <div class="text-5xl mb-3">🛒</div>
                <p class="font-semibold text-gray-500">Keranjang masih kosong</p>
                <p class="text-sm mt-1">Pilih menu yang ingin dipesan</p>
            </div>

            {{-- Items --}}
            <div id="k-items" class="space-y-2 pb-2"></div>

            {{-- Catatan --}}
            <div id="k-catatan-wrap" class="hidden mt-4">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Catatan Pesanan</label>
                <textarea id="k-catatan" rows="2"
                    placeholder="Permintaan khusus untuk seluruh pesanan..."
                    class="w-full border-2 border-gray-200 focus:border-amber-400 rounded-xl px-3 py-2.5 text-sm outline-none transition resize-none"></textarea>
            </div>
        </div>

        {{-- Fixed footer --}}
        <div id="k-footer" class="hidden px-5 pt-3 pb-6 border-t border-gray-100 shrink-0 bg-white space-y-3">

            {{-- Total --}}
            <div class="flex items-center justify-between">
                <span class="text-sm font-bold text-gray-700">Total Pesanan</span>
                <span id="k-total" class="text-xl font-extrabold" style="color:#d97706">Rp 0</span>
            </div>

            {{-- Cara bayar --}}
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Cara Bayar</p>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" id="k-btn-qris" onclick="pilihMetodeKeranjang('qris')"
                        class="flex items-center gap-3 p-3.5 rounded-2xl border-2 transition select-none"
                        style="border-color:#6366f1;background:#eef2ff">
                        <span class="text-2xl shrink-0">📱</span>
                        <div class="text-left">
                            <p class="text-sm font-extrabold text-indigo-700">QRIS</p>
                            <p class="text-[11px] text-indigo-400 mt-0.5">Bayar dari HP</p>
                        </div>
                    </button>
                    <button type="button" id="k-btn-tunai" onclick="pilihMetodeKeranjang('tunai')"
                        class="flex items-center gap-3 p-3.5 rounded-2xl border-2 transition select-none"
                        style="border-color:#e5e7eb;background:#f9fafb">
                        <span class="text-2xl shrink-0">💵</span>
                        <div class="text-left">
                            <p class="text-sm font-extrabold text-gray-600">Tunai</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">Bayar di kasir</p>
                        </div>
                    </button>
                </div>
            </div>

            {{-- CTA --}}
            <button id="btn-pesan" onclick="konfirmasiPesan()" disabled
                class="w-full font-extrabold text-white py-4 rounded-2xl text-base transition active:scale-[.98] disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                style="background:linear-gradient(135deg,#059669,#047857)">
                <span>Lanjutkan ke Pembayaran</span>
                <span>→</span>
            </button>
        </div>
    </div>
</div>

<script>
const BARCODE = '{{ $barcode }}';
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

let produkAktif = null;
let varianDipilih = null;
let opsiDipilih = {};
let jumlah = 1;
let keranjang = [];
let metodeBayar = 'qris'; // default QRIS

try { keranjang = JSON.parse(localStorage.getItem('savana_keranjang_' + BARCODE) || '[]'); } catch(e) {}

function rp(angka) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka); }

function scrollKeKategori(id) {
    document.getElementById(id)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function simpanKeranjang() {
    try { localStorage.setItem('savana_keranjang_' + BARCODE, JSON.stringify(keranjang)); } catch(e) {}
    updateBadge();
}

function updateBadge() {
    const badge = document.getElementById('badge');
    if (keranjang.length > 0) {
        badge.textContent = keranjang.length;
        badge.classList.remove('hidden');
    } else {
        badge.classList.add('hidden');
    }
}

function bukaProduk(p) {
    produkAktif = p;
    varianDipilih = null;
    opsiDipilih = {};
    jumlah = 1;

    document.getElementById('mp-nama').textContent = p.nama;
    document.getElementById('mp-desc').textContent = p.deskripsi || '';
    document.getElementById('mp-jumlah').textContent = '1';
    document.getElementById('mp-catatan').value = '';

    const varianWrap = document.getElementById('mp-varian');
    const varianList = document.getElementById('mp-varian-list');
    if (p.punya_varian && p.varian.length) {
        varianWrap.classList.remove('hidden');
        document.getElementById('mp-harga').textContent = 'Pilih varian';
        varianList.innerHTML = p.varian.map(v => `
            <button onclick="pilihVarian(${v.id},'${v.nama}',${v.harga})" id="varian-${v.id}" class="varian-btn">
                ${v.nama}<br><span style="color:#d97706;font-size:12px">${rp(v.harga)}</span>
            </button>`).join('');
    } else {
        varianWrap.classList.add('hidden');
        document.getElementById('mp-harga').textContent = rp(p.harga_dasar);
    }

    const opsiWrap = document.getElementById('mp-opsi');
    opsiWrap.innerHTML = p.grup_opsi.map(g => `
        <div class="mb-4">
            <p class="font-bold text-gray-700 text-sm mb-2">${g.nama}${g.wajib ? ' <span style="color:#ef4444">*</span>' : ''}</p>
            <div class="space-y-2">
                ${g.opsi.map(o => `
                    <label class="flex items-center justify-between p-3 border-2 border-gray-100 rounded-xl cursor-pointer hover:border-amber-300 transition text-sm">
                        <div class="flex items-center gap-3">
                            <input type="${g.tipe === 'ganda' ? 'checkbox' : 'radio'}"
                                name="opsi_${g.id}" value="${o.id}"
                                data-grup-id="${g.id}" data-grup-nama="${g.nama}"
                                data-nama="${o.nama}" data-harga="${o.harga_tambahan}"
                                ${o.bawaan ? 'checked' : ''}
                                onchange="updateOpsi()"
                                class="w-4 h-4 accent-amber-500">
                            <span class="font-medium text-gray-700">${o.nama}</span>
                        </div>
                        ${o.harga_tambahan > 0 ? `<span class="font-semibold text-amber-600 text-xs">+${rp(o.harga_tambahan)}</span>` : ''}
                    </label>`).join('')}
            </div>
        </div>`).join('');

    updateOpsi();
    hitungTotal();
    document.getElementById('modal-produk').classList.remove('hidden');
}

function tutupProduk() { document.getElementById('modal-produk').classList.add('hidden'); }
function tutupProdukLuar(e) { if (e.target === document.getElementById('modal-produk')) tutupProduk(); }
function tutupKeranjangLuar(e) { if (e.target === document.getElementById('modal-keranjang')) tutupKeranjang(); }

function pilihVarian(id, nama, harga) {
    varianDipilih = { id, nama, harga };
    document.querySelectorAll('.varian-btn').forEach(b => b.classList.remove('selected'));
    document.getElementById('varian-' + id).classList.add('selected');
    document.getElementById('mp-harga').textContent = rp(harga);
    hitungTotal();
}

function updateOpsi() {
    opsiDipilih = {};
    document.querySelectorAll('[name^="opsi_"]:checked').forEach(el => {
        const gid = el.dataset.grupId;
        if (!opsiDipilih[gid]) opsiDipilih[gid] = [];
        opsiDipilih[gid].push({
            opsi_id: parseInt(el.value),
            nama_grup_opsi: el.dataset.grupNama,
            nama_opsi: el.dataset.nama,
            harga_tambahan: parseInt(el.dataset.harga),
        });
    });
    hitungTotal();
}

function hitungTotal() {
    if (!produkAktif) return;
    const base = produkAktif.punya_varian ? (varianDipilih ? varianDipilih.harga : 0) : produkAktif.harga_dasar;
    const opsiTotal = Object.values(opsiDipilih).flat().reduce((s, o) => s + o.harga_tambahan, 0);
    document.getElementById('mp-total').textContent = rp((base + opsiTotal) * jumlah);
}

function ubahJumlah(d) {
    jumlah = Math.max(1, jumlah + d);
    document.getElementById('mp-jumlah').textContent = jumlah;
    hitungTotal();
}

function tambahKeKeranjang() {
    if (produkAktif.punya_varian && !varianDipilih) {
        alert('Silakan pilih varian terlebih dahulu.');
        return;
    }
    const base = produkAktif.punya_varian ? varianDipilih.harga : produkAktif.harga_dasar;
    const semuaOpsi = Object.values(opsiDipilih).flat();
    const harga_opsi = semuaOpsi.reduce((s, o) => s + o.harga_tambahan, 0);
    const subtotal = (base + harga_opsi) * jumlah;

    keranjang.push({
        produk_id: produkAktif.id,
        varian_produk_id: varianDipilih ? varianDipilih.id : null,
        nama_produk: produkAktif.nama,
        nama_varian: varianDipilih ? varianDipilih.nama : null,
        jumlah,
        harga_satuan: base,
        harga_opsi,
        subtotal,
        catatan: document.getElementById('mp-catatan').value,
        opsi: semuaOpsi,
    });

    simpanKeranjang();
    tutupProduk();
    tampilToast('✅ ' + produkAktif.nama + ' ditambahkan ke keranjang');
}

function bukaKeranjang() {
    renderKeranjang();
    document.getElementById('modal-keranjang').classList.remove('hidden');
}

function tutupKeranjang() { document.getElementById('modal-keranjang').classList.add('hidden'); }

function renderKeranjang() {
    const items   = document.getElementById('k-items');
    const kosong  = document.getElementById('k-kosong');
    const footer  = document.getElementById('k-footer');
    const catWrap = document.getElementById('k-catatan-wrap');
    const btn     = document.getElementById('btn-pesan');

    if (!keranjang.length) {
        items.innerHTML = '';
        kosong.classList.remove('hidden');
        footer.classList.add('hidden');
        catWrap.classList.add('hidden');
        btn.disabled = true;
        document.getElementById('k-total').textContent = 'Rp 0';
        return;
    }

    kosong.classList.add('hidden');
    footer.classList.remove('hidden');
    catWrap.classList.remove('hidden');
    btn.disabled = false;
    let total = 0;

    items.innerHTML = keranjang.map((item, i) => {
        total += item.subtotal;
        const opsiLabel = item.opsi.map(o => o.nama_opsi).join(' · ');
        return `
        <div class="flex items-stretch gap-3 p-3.5 rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <p class="font-bold text-sm text-gray-800 leading-tight">
                        ${item.jumlah}× ${item.nama_produk}
                        ${item.nama_varian ? `<span class="font-normal text-gray-400 text-xs">(${item.nama_varian})</span>` : ''}
                    </p>
                    <p class="font-extrabold text-sm shrink-0" style="color:#d97706">${rp(item.subtotal)}</p>
                </div>
                ${opsiLabel ? `<p class="text-xs text-gray-400 mt-1">${opsiLabel}</p>` : ''}
                ${item.catatan ? `<p class="text-xs text-gray-400 italic mt-0.5">"${item.catatan}"</p>` : ''}
            </div>
            <button onclick="hapusItem(${i})"
                class="shrink-0 w-7 h-7 self-start rounded-full bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600 flex items-center justify-center text-base leading-none transition mt-0.5">×</button>
        </div>`;
    }).join('');

    document.getElementById('k-total').textContent = rp(total);
}

function hapusItem(i) {
    keranjang.splice(i, 1);
    simpanKeranjang();
    renderKeranjang();
}

function pilihMetodeKeranjang(m) {
    metodeBayar = m;
    const btnQris  = document.getElementById('k-btn-qris');
    const btnTunai = document.getElementById('k-btn-tunai');
    if (m === 'qris') {
        btnQris.style.cssText  = 'border-color:#6366f1;background:#eef2ff';
        btnQris.querySelector('p').style.color = '#4338ca';
        btnTunai.style.cssText = 'border-color:#e5e7eb;background:#fff';
    } else {
        btnTunai.style.cssText = 'border-color:#d97706;background:#fffbeb';
        btnQris.style.cssText  = 'border-color:#e5e7eb;background:#fff';
        btnQris.querySelector('p').style.color = '';
    }
}

async function konfirmasiPesan() {
    if (!keranjang.length) return;
    const btn = document.getElementById('btn-pesan');
    btn.disabled = true;
    btn.textContent = 'Memproses...';

    try {
        const res = await fetch(`/menu/${BARCODE}/pesan`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({
                catatan: document.getElementById('k-catatan').value,
                metode: metodeBayar,
                items: keranjang,
            }),
        });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const data = await res.json();

        if (data.success && data.redirect) {
            keranjang = [];
            simpanKeranjang();
            window.location.href = data.redirect;
        } else {
            alert(data.message || 'Gagal mengirim pesanan.');
            btn.disabled = false;
            btn.textContent = 'Lanjutkan ke Pembayaran →';
        }
    } catch (e) {
        alert('Gagal mengirim pesanan. Coba lagi. (' + e.message + ')');
        btn.disabled = false;
        btn.textContent = 'Lanjutkan ke Pembayaran →';
    }
}

function tampilToast(msg, dur = 3000) {
    const t = document.createElement('div');
    t.className = 'fixed bottom-8 left-1/2 -translate-x-1/2 text-white px-6 py-3.5 rounded-2xl shadow-2xl z-50 text-sm font-semibold text-center';
    t.style.background = 'linear-gradient(135deg,#1C0A00,#3d1a0a)';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), dur);
}

updateBadge();
</script>
</body>
</html>
