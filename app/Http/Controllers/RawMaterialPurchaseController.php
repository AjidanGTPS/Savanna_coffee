<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use App\Models\RawMaterialPurchase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RawMaterialPurchaseController extends Controller
{
    public function index(): View
    {
        $purchases = RawMaterialPurchase::with(['rawMaterial', 'purchasedBy'])
            ->latest('purchased_at')
            ->paginate(20);

        return view('gudang.purchases.index', compact('purchases'));
    }

    public function create(): View
    {
        $materials = RawMaterial::where('is_active', true)->orderBy('name')->get();

        return view('gudang.purchases.create', compact('materials'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'raw_material_id' => ['required', 'exists:raw_materials,id'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'price_per_unit' => ['required', 'integer', 'min:1'],
            'supplier' => ['nullable', 'string', 'max:100'],
            'purchased_at' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['total_price'] = (int) ($validated['quantity'] * $validated['price_per_unit']);
        $validated['purchased_by'] = Auth::id();

        RawMaterialPurchase::create($validated);

        return redirect()->route('gudang.purchases.index')->with('success', 'Pembelian bahan baku berhasil dicatat. Stok telah diperbarui.');
    }
}
