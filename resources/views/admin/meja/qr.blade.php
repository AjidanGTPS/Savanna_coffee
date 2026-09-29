@extends('layouts.app')
@section('title', 'QR Code — '.$meja->nama)
@section('header', 'QR Code '.$meja->nama)

@section('content')
<div class="max-w-sm">
    <a href="{{ route('admin.meja.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">← Kembali</a>
    <div class="bg-white rounded-xl shadow-sm p-6 text-center">
        <h3 class="font-bold text-gray-800 mb-1">{{ $meja->nama }}</h3>
        <p class="text-sm text-gray-400 mb-4 font-mono">{{ $meja->barcode }}</p>

        <div class="inline-block p-4 bg-white border-2 border-gray-200 rounded-xl mb-4">
            {!! $qrCode !!}
        </div>

        <p class="text-xs text-gray-400 break-all mb-4">{{ $url }}</p>

        <a href="{{ $url }}" target="_blank"
            class="block bg-amber-600 hover:bg-amber-700 text-white font-medium py-2 rounded-lg text-sm transition mb-2">
            Buka Menu ↗
        </a>
        <button onclick="window.print()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 rounded-lg text-sm transition">
            🖨 Cetak QR
        </button>
    </div>
</div>
@endsection
