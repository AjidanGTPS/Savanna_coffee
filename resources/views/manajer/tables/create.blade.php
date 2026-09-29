@extends('layouts.app')
@section('title', 'Tambah Meja')
@section('header', 'Tambah Meja')
@section('content')
    <div class="max-w-md">
        <div class="bg-white rounded-xl border p-6">
            <form method="POST" action="{{ route('manajer.tables.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Meja</label>
                    <input type="number" name="number" min="1" max="100" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" required value="{{ old('number') }}">
                    @error('number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Meja</label>
                    <input type="text" name="name" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" required value="{{ old('name') }}" placeholder="Meja 21">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas</label>
                    <input type="number" name="capacity" min="1" max="20" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500" value="{{ old('capacity', 4) }}">
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2.5 rounded-lg">Simpan</button>
                    <a href="{{ route('manajer.tables.index') }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-lg">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
