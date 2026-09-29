<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kitchen Display — SAVANA Coffee</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background: #0f1117; }

        .kartu-baru    { border-color: #ef4444; background: linear-gradient(145deg, #1a0505, #1f0808); }
        .kartu-dimasak { border-color: #f59e0b; background: linear-gradient(145deg, #1a1200, #1f1500); }
        .kartu-siap    { border-color: #22c55e; background: linear-gradient(145deg, #021408, #041a0c); }

        .badge-baru    { background: #ef4444; color: #fff; }
        .badge-dimasak { background: #f59e0b; color: #000; }
        .badge-siap    { background: #22c55e; color: #fff; }

        @keyframes pulse-red { 0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,.4)} 50%{box-shadow:0 0 0 8px rgba(239,68,68,0)} }
        .kartu-baru { animation: pulse-red 2.5s infinite; }

        .btn-aksi { padding: 8px 0; border-radius: 8px; font-size: 13px; font-weight: 700;
            cursor: pointer; transition: opacity .15s, transform .1s; border: none; width: 100%; color: #fff; }
        .btn-aksi:hover { opacity: .9; }
        .btn-aksi:active { transform: scale(.97); }
        .btn-aksi:disabled { opacity: .5; cursor: not-allowed; }
    </style>
</head>
<body class="min-h-screen">

<header style="background: linear-gradient(90deg, #1C0A00, #2d1207); border-bottom: 1px solid rgba(255,255,255,.08);"
    class="px-6 py-4 flex items-center justify-between sticky top-0 z-20">
    <div class="flex items-center gap-4">
        <span class="text-2xl">☕</span>
        <div>
            <h1 class="font-bold text-white text-lg leading-tight">Kitchen Display</h1>
            <p class="text-amber-400 text-xs">SAVANA Coffee · {{ auth()->user()->nama }} ({{ auth()->user()->peran_label }})</p>
        </div>
    </div>
    <div class="flex items-center gap-5">
        <div class="flex gap-2 text-xs font-semibold">
            <span class="px-3 py-1.5 rounded-full bg-red-500/20 text-red-400 border border-red-500/30">● Baru</span>
            <span class="px-3 py-1.5 rounded-full bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">● Dimasak</span>
            <span class="px-3 py-1.5 rounded-full bg-green-500/20 text-green-400 border border-green-500/30">● Siap</span>
        </div>
        <div class="text-gray-400 text-sm">
            Refresh <span id="countdown" class="font-bold text-white">8</span>s
        </div>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="text-xs text-gray-400 hover:text-white border border-white/10 px-3 py-1.5 rounded-lg transition">Keluar</button>
        </form>
    </div>
</header>

<div class="p-5">
    <div id="pesanan-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($pesanan as $p)
        @include('kitchen._kartu', ['p' => $p])
        @empty
        <div id="kosong" class="col-span-full flex flex-col items-center justify-center py-28 text-gray-600">
            <p class="text-7xl mb-4">🎉</p>
            <p class="text-2xl font-bold text-gray-400">Semua Beres!</p>
            <p class="text-sm text-gray-600 mt-1">Tidak ada pesanan yang perlu diproses</p>
        </div>
        @endforelse
    </div>
</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let sebelumnya = new Set(@json($pesanan->pluck('id')));

const warnaBorder = { baru: 'kartu-baru', dimasak: 'kartu-dimasak', siap: 'kartu-siap' };
const warnaBadge  = { baru: 'badge-baru', dimasak: 'badge-dimasak', siap: 'badge-siap' };
const labelStatus = { baru: 'Baru Masuk', dimasak: 'Dimasak', siap: 'Siap Disajikan' };
const aksiMap = {
    baru:    [{ status:'dimasak', label:'▶ Mulai Masak', bg:'#f59e0b', color:'#000' }, { status:'dibatalkan', label:'✕ Batalkan', bg:'#4b5563', color:'#fff' }],
    dimasak: [{ status:'siap',    label:'✔ Siap Disajikan', bg:'#22c55e', color:'#fff' }],
    siap:    [{ status:'diantar', label:'🍽 Sudah Diantar',  bg:'#3b82f6', color:'#fff' }],
};

function waktuSingkat(iso) {
    if (!iso) return '-';
    const d = new Date(iso);
    return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
}

function kartuHTML(p) {
    const aksi = aksiMap[p.status] || [];
    const borderCls = warnaBorder[p.status] || '';
    const badgeCls  = warnaBadge[p.status] || '';
    const lbl       = labelStatus[p.status] || p.status_label;

    return `
    <div class="border-2 rounded-2xl p-4 ${borderCls}" data-id="${p.id}">
        <div class="flex items-start justify-between mb-3">
            <div>
                <span class="text-xs font-bold ${badgeCls} px-2.5 py-1 rounded-full">${lbl}</span>
                <h3 class="font-extrabold text-white text-xl mt-2 leading-tight">${p.meja}</h3>
                <p class="text-gray-500 text-xs mt-0.5">${p.nomor_pesanan} · ${waktuSingkat(p.dipesan_pada)}</p>
            </div>
            <span class="text-2xl">${p.status === 'baru' ? '🔴' : p.status === 'dimasak' ? '🟡' : '🟢'}</span>
        </div>

        <div class="border-t border-white/10 pt-3 mb-3 space-y-2">
            ${p.items.map(i => `
            <div class="flex gap-2.5">
                <span class="w-6 h-6 bg-white/10 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0">${i.jumlah}</span>
                <div class="min-w-0">
                    <p class="text-white text-sm font-semibold">${i.nama_produk}${i.nama_varian ? ` <span class="text-gray-400 font-normal">(${i.nama_varian})</span>` : ''}</p>
                    ${i.opsi ? `<p class="text-gray-500 text-xs">${i.opsi}</p>` : ''}
                    ${i.catatan ? `<p class="text-yellow-400 text-xs italic">"${i.catatan}"</p>` : ''}
                </div>
            </div>`).join('')}
        </div>

        ${p.catatan ? `<div class="bg-yellow-500/10 border border-yellow-500/20 rounded-lg px-3 py-2 mb-3 text-xs text-yellow-300">📝 ${p.catatan}</div>` : ''}

        <div class="flex flex-col gap-2">
            ${aksi.map(a => `<button onclick="updateStatus(${p.id},'${a.status}',this)" class="btn-aksi" style="background:${a.bg};color:${a.color}">${a.label}</button>`).join('')}
        </div>
    </div>`;
}

async function updateStatus(id, status, btn) {
    btn.disabled = true;
    try {
        const res = await fetch(`/kitchen/pesanan/${id}/status`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ status }),
        });
        if (res.ok) poll();
    } finally { btn.disabled = false; }
}

async function poll() {
    try {
        const res = await fetch('/kitchen/poll');
        const data = await res.json();
        const grid = document.getElementById('pesanan-grid');

        const adaBaru = data.some(p => !sebelumnya.has(p.id));
        if (adaBaru) bunyi();
        sebelumnya = new Set(data.map(p => p.id));

        if (!data.length) {
            grid.innerHTML = `<div id="kosong" class="col-span-full flex flex-col items-center justify-center py-28 text-gray-600">
                <p class="text-7xl mb-4">🎉</p>
                <p class="text-2xl font-bold text-gray-400">Semua Beres!</p>
                <p class="text-sm text-gray-600 mt-1">Tidak ada pesanan yang perlu diproses</p>
            </div>`;
            return;
        }
        grid.innerHTML = data.map(kartuHTML).join('');
    } catch (e) { console.error(e); }
}

function bunyi() {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        [0, 0.2, 0.4].forEach(t => {
            const o = ctx.createOscillator();
            const g = ctx.createGain();
            o.connect(g); g.connect(ctx.destination);
            o.frequency.value = 880;
            g.gain.setValueAtTime(0.3, ctx.currentTime + t);
            g.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + t + 0.15);
            o.start(ctx.currentTime + t); o.stop(ctx.currentTime + t + 0.15);
        });
    } catch (e) {}
}

let hitungan = 8;
setInterval(() => {
    hitungan--;
    document.getElementById('countdown').textContent = hitungan;
    if (hitungan <= 0) { hitungan = 8; poll(); }
}, 1000);
</script>
</body>
</html>
