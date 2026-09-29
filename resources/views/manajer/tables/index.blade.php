@extends('layouts.app')

@section('title', 'Kelola Meja')
@section('header', 'Kelola Meja')

@section('header-actions')
    <a href="{{ route('manajer.tables.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        + Tambah Meja
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($tables as $table)
            <div class="bg-white rounded-xl border p-4 flex items-center justify-between">
                <div>
                    <p class="font-bold text-lg">{{ $table->name }}</p>
                    <p class="text-xs text-gray-400 font-mono mt-1">Token: {{ substr($table->barcode_token, 0, 12) }}...</p>
                    <div class="flex gap-2 mt-2">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $table->status === 'occupied' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-700' }}">
                            {{ $table->status === 'occupied' ? 'Terisi' : 'Tersedia' }}
                        </span>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $table->is_active ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $table->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $table->capacity }} kursi</span>
                    </div>
                </div>
                <div class="flex flex-col gap-2 items-end">
                    <a href="{{ route('manajer.tables.qr', $table) }}" class="text-xs bg-amber-100 hover:bg-amber-200 text-amber-700 px-3 py-1.5 rounded-lg">
                        📱 QR Code
                    </a>
                    <form method="POST" action="{{ route('manajer.tables.toggle', $table) }}">
                        @csrf
                        <button type="submit" class="text-xs {{ $table->is_active ? 'text-gray-500 hover:text-red-600' : 'text-blue-600 hover:text-blue-800' }}">
                            {{ $table->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
