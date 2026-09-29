@php
    $borderCls = match($p->status) {
        'baru'    => 'kartu-baru',
        'dimasak' => 'kartu-dimasak',
        'siap'    => 'kartu-siap',
        default   => '',
    };
    $pillCls = match($p->status) {
        'baru'    => 'pill-baru',
        'dimasak' => 'pill-dimasak',
        'siap'    => 'pill-siap',
        default   => '',
    };
    $lbl = match($p->status) {
        'baru'    => 'Baru Masuk',
        'dimasak' => 'Dimasak',
        'siap'    => 'Siap Disajikan',
        default   => $p->status_label,
    };
@endphp
<div class="kartu rounded-2xl p-4 {{ $borderCls }}" data-id="{{ $p->id }}">
    <div class="flex items-start justify-between mb-3">
        <div>
            <span class="pill {{ $pillCls }}"><span class="pill-dot"></span>{{ $lbl }}</span>
            <h3 class="font-extrabold text-white text-xl mt-2 leading-tight">{{ $p->meja->nama }}</h3>
            <p class="text-gray-500 text-xs mt-0.5">{{ $p->nomor_pesanan }} · {{ $p->dipesan_pada?->format('H:i') }}</p>
        </div>
    </div>

    <div class="border-t border-white/10 pt-3 mb-3 space-y-2">
        @foreach($p->items as $item)
        <div class="flex gap-2.5">
            <span class="w-6 h-6 bg-white/10 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0">{{ $item->jumlah }}</span>
            <div class="min-w-0">
                <p class="text-white text-sm font-semibold">
                    {{ $item->nama_produk }}
                    @if($item->nama_varian) <span class="text-gray-400 font-normal">({{ $item->nama_varian }})</span> @endif
                </p>
                @if($item->opsi->count())
                <p class="text-gray-500 text-xs">{{ $item->opsi->pluck('nama_opsi')->implode(', ') }}</p>
                @endif
                @if($item->catatan)
                <p class="text-yellow-400 text-xs italic">"{{ $item->catatan }}"</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @if($p->catatan)
    <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-lg px-3 py-2 mb-3 text-xs text-yellow-300">
        Catatan: {{ $p->catatan }}
    </div>
    @endif

    <div class="flex flex-col gap-2">
        @if($p->status === 'baru')
            <button onclick="updateStatus({{ $p->id }},'dimasak',this)" class="btn-aksi" style="background:#f59e0b;color:#000">▶ Mulai Masak</button>
            <button onclick="updateStatus({{ $p->id }},'dibatalkan',this)" class="btn-aksi" style="background:#4b5563;color:#fff">✕ Batalkan</button>
        @elseif($p->status === 'dimasak')
            <button onclick="updateStatus({{ $p->id }},'siap',this)" class="btn-aksi" style="background:#22c55e;color:#fff">✔ Siap Disajikan</button>
        @elseif($p->status === 'siap')
            <button onclick="updateStatus({{ $p->id }},'diantar',this)" class="btn-aksi" style="background:#3b82f6;color:#fff">🍽 Sudah Diantar</button>
        @endif
    </div>
</div>
