@extends('layouts.app')
@section('title', 'QR Code — ' . $table->name)
@section('header', 'QR Code ' . $table->name)

@section('content')
    <div class="max-w-sm mx-auto">
        <div class="bg-white rounded-xl border p-8 text-center">
            <h2 class="text-xl font-bold mb-2">{{ $table->name }}</h2>
            <p class="text-gray-500 text-sm mb-6">Scan QR code ini untuk membuka menu</p>

            <div class="flex justify-center mb-6">
                {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate(route('menu.show', $table->barcode_token)) !!}
            </div>

            <p class="text-xs text-gray-400 font-mono break-all mb-6">{{ route('menu.show', $table->barcode_token) }}</p>

            <div class="flex gap-3">
                <button onclick="window.print()" class="flex-1 bg-gray-800 hover:bg-gray-900 text-white py-2.5 rounded-lg font-semibold text-sm">
                    🖨️ Cetak
                </button>
                <form method="POST" action="{{ route('manajer.tables.regenerate', $table) }}" class="flex-1">
                    @csrf
                    <button type="submit" onclick="return confirm('Generate ulang QR Code? Link lama tidak akan berfungsi.')"
                        class="w-full bg-red-100 hover:bg-red-200 text-red-700 py-2.5 rounded-lg font-semibold text-sm">
                        🔄 Regenerate
                    </button>
                </form>
            </div>
        </div>
        <div class="mt-3 text-center">
            <a href="{{ route('manajer.tables.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali ke Daftar Meja</a>
        </div>
    </div>
@endsection
