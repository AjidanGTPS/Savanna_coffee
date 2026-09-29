@extends('layouts.app')
@section('title', 'Kelola Pengguna')
@section('header', 'Kelola Pengguna')
@section('subheader', 'Manajemen akun dan hak akses')

@section('actions')
<a href="{{ route('admin.pengguna.create') }}"
    class="flex items-center gap-2 text-white text-sm font-bold px-4 py-2.5 rounded-xl transition active:scale-95"
    style="background:linear-gradient(135deg,#d97706,#ea580c)">
    + Tambah Pengguna
</a>
@endsection

@section('content')

<div class="mb-4">
    <p class="text-sm text-gray-400">{{ $pengguna->count() }} akun terdaftar</p>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-x-auto border border-gray-100">
    <table class="w-full text-sm min-w-[640px]">
        <thead>
            <tr style="background:linear-gradient(135deg,#1C0A00,#2d1207)">
                <th class="px-5 py-3.5 text-left text-xs font-bold text-amber-300 uppercase tracking-wider">Pengguna</th>
                <th class="px-5 py-3.5 text-left text-xs font-bold text-amber-300 uppercase tracking-wider">Email</th>
                <th class="px-5 py-3.5 text-left text-xs font-bold text-amber-300 uppercase tracking-wider">Peran</th>
                <th class="px-5 py-3.5 text-center text-xs font-bold text-amber-300 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3.5"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($pengguna as $p)
            <tr class="hover:bg-amber-50/20 transition">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold text-white shrink-0"
                            style="background:linear-gradient(135deg,#d97706,#ea580c)">
                            {{ substr($p->nama, 0, 1) }}
                        </div>
                        <span class="font-semibold text-gray-800">{{ $p->nama }}</span>
                    </div>
                </td>
                <td class="px-5 py-4 text-gray-500">{{ $p->email }}</td>
                <td class="px-5 py-4">
                    @php
                        $peranCls = match($p->peran) {
                            'admin'   => 'bg-purple-100 text-purple-700',
                            'kasir'   => 'bg-blue-100 text-blue-700',
                            'pelayan' => 'bg-sky-100 text-sky-700',
                            'owner'   => 'bg-emerald-100 text-emerald-700',
                            'manajer' => 'bg-teal-100 text-teal-700',
                            default   => 'bg-gray-100 text-gray-600',
                        };
                    @endphp
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $peranCls }}">{{ $p->peran_label }}</span>
                </td>
                <td class="px-5 py-4 text-center">
                    @if($p->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.pengguna.toggle', $p) }}">@csrf
                        <button class="text-xs font-bold px-3 py-1.5 rounded-full transition
                            {{ $p->aktif ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                            {{ $p->aktif ? '✓ Aktif' : '✗ Nonaktif' }}
                        </button>
                    </form>
                    @else
                    <span class="text-xs text-gray-400 italic">Anda</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-right">
                    <a href="{{ route('admin.pengguna.edit', $p) }}"
                        class="text-xs font-semibold text-amber-600 hover:text-amber-800 transition">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
