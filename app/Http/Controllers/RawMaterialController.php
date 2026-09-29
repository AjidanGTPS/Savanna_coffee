<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RawMaterialController extends Controller
{
    public function index(): View
    {
        $materials = RawMaterial::where('is_active', true)->orderBy('category')->orderBy('name')->get();

        return view('gudang.materials.index', compact('materials'));
    }

    public function create(): View
    {
        return view('gudang.materials.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:20'],
            'minimum_stock' => ['required', 'numeric', 'min:0'],
            'category' => ['nullable', 'string', 'max:50'],
        ]);

        RawMaterial::create(array_merge($validated, ['current_stock' => 0, 'is_active' => true]));

        return redirect()->route('gudang.materials.index')->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function edit(RawMaterial $rawMaterial): View
    {
        return view('gudang.materials.edit', compact('rawMaterial'));
    }

    public function update(Request $request, RawMaterial $rawMaterial): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:20'],
            'minimum_stock' => ['required', 'numeric', 'min:0'],
            'category' => ['nullable', 'string', 'max:50'],
        ]);

        $rawMaterial->update($validated);

        return redirect()->route('gudang.materials.index')->with('success', 'Bahan baku berhasil diperbarui.');
    }
}
