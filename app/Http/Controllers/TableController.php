<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TableController extends Controller
{
    public function index(): View
    {
        $tables = Table::orderBy('number')->get();

        return view('manajer.tables.index', compact('tables'));
    }

    public function create(): View
    {
        return view('manajer.tables.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'number' => ['required', 'integer', 'min:1', 'unique:tables,number'],
            'name' => ['required', 'string', 'max:50'],
            'capacity' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        Table::create(array_merge($validated, ['barcode_token' => Str::random(32)]));

        return redirect()->route('manajer.tables.index')->with('success', 'Meja berhasil ditambahkan.');
    }

    public function showQr(Table $table): View
    {
        return view('manajer.tables.qr', compact('table'));
    }

    public function regenerateToken(Table $table): RedirectResponse
    {
        $table->update(['barcode_token' => Str::random(32)]);

        return redirect()->route('manajer.tables.qr', $table)->with('success', 'QR Code berhasil diperbarui.');
    }

    public function toggleActive(Table $table): RedirectResponse
    {
        $table->update(['is_active' => ! $table->is_active]);
        $status = $table->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()->with('success', "Meja {$table->name} berhasil {$status}.");
    }
}
