@extends('layouts.app')
@section('title', 'Kelola Meja')
@section('header', 'Kelola Meja & QR')
@section('subheader', 'Atur meja dan kode QR untuk pemesanan')

@section('actions')
<a href="{{ route('admin.meja.create') }}"
    class="flex items-center gap-2 text-white text-sm font-bold px-4 py-2.5 rounded-xl transition active:scale-95"
    style="background:linear-gradient(135deg,#d97706,#ea580c)">
    + Tambah Meja
</a>
@endsection

@section('content')

<div class="mb-4">
    <p class="text-sm text-gray-400">{{ $meja->count() }} meja terdaftar</p>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-x-auto border border-gray-100">
    <table class="w-full text-sm min-w-[760px]">
        <thead>
            <tr style="background:linear-gradient(135deg,#1C0A00,#2d1207)">
                <th class="px-5 py-3.5 text-left text-xs font-bold text-amber-300 uppercase tracking-wider">Nama Meja</th>
                <th class="px-5 py-3.5 text-left text-xs font-bold text-amber-300 uppercase tracking-wider">Area</th>
                <th class="px-5 py-3.5 text-center text-xs font-bold text-amber-300 uppercase tracking-wider">Kapasitas</th>
                <th class="px-5 py-3.5 text-left text-xs font-bold text-amber-300 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3.5 text-left text-xs font-bold text-amber-300 uppercase tracking-wider">Barcode</th>
                <th class="px-5 py-3.5 text-center text-xs font-bold text-amber-300 uppercase tracking-wider">Aktif</th>
                <th class="px-5 py-3.5"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($meja as $m)
            <tr class="hover:bg-amber-50/20 transition">
                <td class="px-5 py-4 font-bold text-gray-800">{{ $m->nama }}</td>
                <td class="px-5 py-4 text-gray-500">{{ $m->area ?? '—' }}</td>
                <td class="px-5 py-4 text-center text-gray-600">{{ $m->kapasitas }} org</td>
                <td class="px-5 py-4">
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full
                        {{ $m->status === 'kosong'  ? 'bg-emerald-100 text-emerald-700' :
                           ($m->status === 'terisi' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                        {{ $m->status_label }}
                    </span>
                </td>
                <td class="px-5 py-4 font-mono text-xs text-gray-400">{{ $m->barcode }}</td>
                <td class="px-5 py-4 text-center">
                    <form method="POST" action="{{ route('admin.meja.toggle', $m) }}">@csrf
                        <button class="text-xs font-bold px-3 py-1.5 rounded-full transition
                            {{ $m->aktif ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                            {{ $m->aktif ? '✓ Aktif' : '✗ Nonaktif' }}
                        </button>
                    </form>
                </td>
                <td class="px-5 py-4 text-right">
                    <a href="{{ route('admin.meja.qr', $m) }}"
                        class="text-xs font-semibold text-amber-600 hover:text-amber-800 mr-3 transition">🔲 QR</a>
                    <a href="{{ route('menu.show', $m->barcode) }}" target="_blank"
                        class="text-xs font-semibold text-gray-400 hover:text-gray-600 transition">Menu ↗</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
