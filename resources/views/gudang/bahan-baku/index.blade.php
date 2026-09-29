@extends('layouts.app')
@section('title', 'Gudang Bahan Baku')
@section('header', 'Bahan Baku')
@section('subheader', 'Pantau stok dan kelola bahan baku dapur')

@section('actions')
    @if(auth()->user()->hasRole(['admin','manajer']))
    <a href="{{ route('gudang.bahan-baku.create') }}"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white shadow-lg transition hover:opacity-90"
        style="background: linear-gradient(135deg,#d97706,#ea580c)">
        + Tambah Bahan Baku
    </a>
    @endif
@endsection

@section('content')
{{-- Stat Bar --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-7" style="padding: 1rem;">
    <div class="rounded-2xl p-4 text-white shadow-lg" style="background:linear-gradient(135deg,#6366f1,#4f46e5)">
        <p class="text-xs font-semibold opacity-70 uppercase tracking-wide">Total Item</p>
        <p class="text-3xl font-bold mt-1">{{ $ringkasan['total'] }}</p>
    </div>
    <div class="rounded-2xl p-4 text-white shadow-lg" style="background:linear-gradient(135deg,#ef4444,#dc2626)">
        <p class="text-xs font-semibold opacity-70 uppercase tracking-wide">Habis</p>
        <p class="text-3xl font-bold mt-1">{{ $ringkasan['habis'] }}</p>
    </div>
    <div class="rounded-2xl p-4 text-white shadow-lg" style="background:linear-gradient(135deg,#f97316,#ea580c)">
        <p class="text-xs font-semibold opacity-70 uppercase tracking-wide">Kritis</p>
        <p class="text-3xl font-bold mt-1">{{ $ringkasan['kritis'] }}</p>
    </div>
    <div class="rounded-2xl p-4 text-white shadow-lg" style="background:linear-gradient(135deg,#eab308,#d97706)">
        <p class="text-xs font-semibold opacity-70 uppercase tracking-wide">Perlu Restock</p>
        <p class="text-3xl font-bold mt-1">{{ $ringkasan['rendah'] }}</p>
    </div>
</div>

{{-- Alert jika ada stok kritis --}}
@if($ringkasan['habis'] > 0 || $ringkasan['kritis'] > 0)
<div class="mb-6 p-4 rounded-2xl border border-red-200 bg-red-50 flex items-start gap-3">
    <span class="text-2xl">🚨</span>
    <div>
        <p class="font-semibold text-red-700 text-sm">Perhatian!</p>
        <p class="text-red-600 text-sm mt-0.5">
            Terdapat
            @if($ringkasan['habis'] > 0)<strong>{{ $ringkasan['habis'] }} item habis</strong>@endif
            @if($ringkasan['habis'] > 0 && $ringkasan['kritis'] > 0) dan @endif
            @if($ringkasan['kritis'] > 0)<strong>{{ $ringkasan['kritis'] }} item kritis</strong>@endif.
            Segera lakukan restock agar produksi tidak terhenti.
        </p>
    </div>
</div>
@endif

{{-- Tabel Bahan Baku --}}
<div class="bg-white rounded-3xl shadow-sm overflow-x-auto">
    <table class="w-full text-sm min-w-[780px]">
        <thead>
            <tr style="background:linear-gradient(135deg,#1C0A00,#2d1207)">
                <th class="text-left px-5 py-3.5 text-white font-semibold">Nama Bahan</th>
                <th class="text-center px-4 py-3.5 text-white font-semibold">Satuan</th>
                <th class="text-right px-4 py-3.5 text-white font-semibold">Stok Saat Ini</th>
                <th class="text-right px-4 py-3.5 text-white font-semibold">Stok Min.</th>
                <th class="text-right px-4 py-3.5 text-white font-semibold">Harga/Satuan</th>
                <th class="text-center px-4 py-3.5 text-white font-semibold">Status</th>
                <th class="text-center px-4 py-3.5 text-white font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($bahan as $item)
            @php
                $status = $item->status_stok;
                $statusStyle = match($status) {
                    'habis'  => 'background:#fee2e2;color:#dc2626',
                    'kritis' => 'background:#fed7aa;color:#ea580c',
                    'rendah' => 'background:#fef9c3;color:#b45309',
                    default  => 'background:#d1fae5;color:#059669',
                };
                $rowBg = match($status) {
                    'habis'  => 'background:#fff5f5',
                    'kritis' => 'background:#fff8f5',
                    default  => '',
                };
            @endphp
            <tr style="{{ $rowBg }}" class="hover:bg-amber-50/40 transition">
                <td class="px-5 py-3.5">
                    <div class="font-semibold text-gray-800">{{ $item->nama }}</div>
                    @if($item->keterangan)
                    <div class="text-xs text-gray-400 mt-0.5">{{ $item->keterangan }}</div>
                    @endif
                </td>
                <td class="px-4 py-3.5 text-center text-gray-500">{{ $item->satuan }}</td>
                <td class="px-4 py-3.5 text-right font-bold {{ $status === 'habis' ? 'text-red-600' : ($status === 'kritis' ? 'text-orange-600' : 'text-gray-800') }}">
                    {{ fmt_stok($item->stok_saat_ini) }}
                </td>
                <td class="px-4 py-3.5 text-right text-gray-400">{{ fmt_stok($item->stok_minimum) }}</td>
                <td class="px-4 py-3.5 text-right text-gray-600">Rp {{ number_format($item->harga_per_satuan, 0, ',', '.') }}</td>
                <td class="px-4 py-3.5 text-center">
                    <span class="px-3 py-1 rounded-full text-xs font-bold" style="{{ $statusStyle }}">
                        {{ $item->status_stok_label }}
                    </span>
                </td>
                <td class="px-4 py-3.5">
                    <div class="flex items-center justify-center gap-2">
                        @if(auth()->user()->hasRole(['admin','manajer']))
                        {{-- Stok Masuk --}}
                        <button onclick="bukaModal('masuk', {{ $item->id }}, '{{ $item->nama }}', '{{ $item->satuan }}')"
                            class="px-4 py-1.7 rounded-lg text-xs font-semibold text-white transition"
                            style="background:#059669" title="Stok Masuk">+ Masuk</button>
                        {{-- Stok Keluar --}}
                        <button onclick="bukaModal('keluar', {{ $item->id }}, '{{ $item->nama }}', '{{ $item->satuan }}')"
                            class="px-4 py-1.7 rounded-lg text-xs font-semibold text-white transition"
                            style="background:#dc2626" title="Stok Keluar">- Keluar</button>
                        <a href="{{ route('gudang.bahan-baku.edit', $item) }}"
                            class="px-4 py-1.7 rounded-lg text-xs font-semibold text-white transition"
                            style="background:#6366f1">Edit</a>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-16 text-gray-400">
                    <div class="text-4xl mb-3">📦</div>
                    <p class="font-medium">Belum ada bahan baku</p>
                    <p class="text-sm mt-1">Tambahkan bahan baku pertama untuk mulai memantau stok</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Transaksi Stok --}}
<div id="modalTransaksi" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="tutupModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        <div id="modalHeader" class="px-6 py-4 text-white">
            <h3 class="font-bold text-lg" id="modalJudul">Transaksi Stok</h3>
            <p class="text-sm opacity-75" id="modalSubjudul"></p>
        </div>
        <form id="formTransaksi" method="POST" class="p-6 space-y-4">
            @csrf
            @method('POST')
            <input type="hidden" name="jenis" id="inputJenis">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jumlah</label>
                <div class="flex">
                    <input type="number" name="jumlah" min="0.01" step="0.01" required
                        class="flex-1 border border-gray-200 rounded-l-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400"
                        placeholder="Masukkan jumlah...">
                    <span id="satuanLabel" class="border border-l-0 border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 rounded-r-xl font-medium"></span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Keterangan <span class="text-gray-400 font-normal">(opsional)</span></label>
                <input type="text" name="keterangan"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400"
                    placeholder="Catatan transaksi...">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="tutupModal()"
                    class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" id="tombolSubmit"
                    class="flex-1 py-2.5 rounded-xl text-sm font-bold text-white transition"
                    style="background:linear-gradient(135deg,#d97706,#ea580c)">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function bukaModal(jenis, id, nama, satuan) {
    const modal = document.getElementById('modalTransaksi');
    const header = document.getElementById('modalHeader');
    const judul = document.getElementById('modalJudul');
    const subjudul = document.getElementById('modalSubjudul');
    const form = document.getElementById('formTransaksi');
    const inputJenis = document.getElementById('inputJenis');
    const satuanLabel = document.getElementById('satuanLabel');
    const tombol = document.getElementById('tombolSubmit');

    inputJenis.value = jenis;
    satuanLabel.textContent = satuan;
    subjudul.textContent = nama;
    form.action = `/gudang/bahan-baku/${id}/transaksi`;

    if (jenis === 'masuk') {
        judul.textContent = '+ Stok Masuk';
        header.style.background = 'linear-gradient(135deg,#059669,#047857)';
        tombol.style.background = 'linear-gradient(135deg,#059669,#047857)';
    } else {
        judul.textContent = '- Stok Keluar';
        header.style.background = 'linear-gradient(135deg,#dc2626,#b91c1c)';
        tombol.style.background = 'linear-gradient(135deg,#dc2626,#b91c1c)';
    }

    modal.classList.remove('hidden');
}

function tutupModal() {
    document.getElementById('modalTransaksi').classList.add('hidden');
    document.getElementById('formTransaksi').reset();
}
</script>
@endsection
