@extends('layouts.app')
@section('title', 'Kelola Produk')
@section('header', 'Kelola Produk')
@section('subheader', 'Atur menu yang tampil di katalog')

@section('actions')
<a href="{{ route('admin.produk.create') }}"
    class="flex items-center gap-2 text-white text-sm font-bold px-4 py-2.5 rounded-xl transition active:scale-95"
    style="background:linear-gradient(135deg,#d97706,#ea580c)">
    + Tambah Produk
</a>
@endsection

@section('content')

<div class="mb-4">
    <p class="text-sm text-gray-400">{{ $produk->flatten()->count() }} produk terdaftar</p>
</div>

@foreach($produk as $kategoriNama => $items)
<div class="mb-7">
    <div class="flex items-center gap-3 mb-3">
        <span class="w-1 h-5 rounded-full" style="background:linear-gradient(180deg,#d97706,#ea580c)"></span>
        <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wider">{{ $kategoriNama }}</h3>
        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ count($items) }}</span>
    </div>

    {{-- Mobile: cards | Desktop: table --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        {{-- Table (md+) --}}
        <div class="hidden md:block">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nama</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Harga</th>
                        <th class="px-5 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($items as $p)
                    <tr class="hover:bg-amber-50/20 transition">
                        <td class="px-5 py-3.5">
                            <p class="font-semibold text-gray-800">{{ $p->nama }}</p>
                            @if($p->deskripsi)<p class="text-xs text-gray-400 truncate max-w-xs mt-0.5">{{ $p->deskripsi }}</p>@endif
                        </td>
                        <td class="px-5 py-3.5">
                            @if($p->punya_varian)
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-purple-50 text-purple-700">Punya Varian</span>
                            @else
                            <span class="font-semibold text-gray-700">Rp {{ number_format($p->harga_dasar) }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <form method="POST" action="{{ route('admin.produk.toggle', $p) }}">@csrf
                                <button class="text-xs font-bold px-3 py-1.5 rounded-full transition {{ $p->tersedia ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                    {{ $p->tersedia ? '✓ Tersedia' : '✗ Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('admin.produk.edit', $p) }}" class="text-xs font-semibold text-amber-600 hover:text-amber-800 mr-4 transition">Edit</a>
                            <form method="POST" action="{{ route('admin.produk.destroy', $p) }}" class="inline" onsubmit="return confirm('Hapus produk {{ $p->nama }}?')">
                                @csrf @method('DELETE')
                                <button class="text-xs font-semibold text-red-400 hover:text-red-600 transition">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- Cards (mobile) --}}
        <div class="md:hidden divide-y divide-gray-100">
            @foreach($items as $p)
            <div class="px-4 py-3.5 flex items-start gap-3">
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm">{{ $p->nama }}</p>
                    @if($p->deskripsi)<p class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ $p->deskripsi }}</p>@endif
                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                        @if($p->punya_varian)
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-purple-50 text-purple-700">Punya Varian</span>
                        @else
                        <span class="text-xs font-semibold text-gray-600">Rp {{ number_format($p->harga_dasar) }}</span>
                        @endif
                        <form method="POST" action="{{ route('admin.produk.toggle', $p) }}">@csrf
                            <button class="text-xs font-bold px-2 py-0.5 rounded-full transition {{ $p->tersedia ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $p->tersedia ? '✓ Tersedia' : '✗ Nonaktif' }}
                            </button>
                        </form>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-2 shrink-0">
                    <a href="{{ route('admin.produk.edit', $p) }}" class="text-xs font-semibold text-amber-600 hover:text-amber-800">Edit</a>
                    <form method="POST" action="{{ route('admin.produk.destroy', $p) }}" onsubmit="return confirm('Hapus {{ $p->nama }}?')">
                        @csrf @method('DELETE')
                        <button class="text-xs font-semibold text-red-400 hover:text-red-600">Hapus</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endforeach
@endsection
