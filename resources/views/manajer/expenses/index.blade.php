@extends('layouts.app')
@section('title', 'Pengeluaran')
@section('header', 'Catatan Pengeluaran')

@section('header-actions')
    <a href="{{ route('manajer.expenses.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        + Tambah Pengeluaran
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-xl border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Keterangan</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Kategori</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Jumlah</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Tanggal</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Dicatat oleh</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($expenses as $expense)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $expense->title }}</td>
                        <td class="px-4 py-3"><span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ $expense->category_label }}</span></td>
                        <td class="px-4 py-3 text-right font-semibold text-red-600">Rp {{ number_format($expense->amount) }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $expense->date->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $expense->creator->name }}</td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="{{ route('manajer.expenses.destroy', $expense) }}" onsubmit="return confirm('Hapus pengeluaran ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada pengeluaran tercatat</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $expenses->links() }}</div>
    </div>
@endsection
