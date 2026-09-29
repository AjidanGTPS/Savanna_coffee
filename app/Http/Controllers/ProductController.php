<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSizePrice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')
            ->orderBy('category_id')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category.name');

        return view('manajer.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        return view('manajer.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'has_size' => ['boolean'],
            'base_price' => ['required_if:has_size,0', 'nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'sizes' => ['required_if:has_size,1', 'nullable', 'array'],
            'sizes.S' => ['nullable', 'integer', 'min:0'],
            'sizes.M' => ['nullable', 'integer', 'min:0'],
            'sizes.L' => ['nullable', 'integer', 'min:0'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'base_price' => $validated['base_price'] ?? 0,
            'has_size' => $request->boolean('has_size'),
            'image' => $imagePath,
            'is_available' => true,
        ]);

        if ($product->has_size && isset($validated['sizes'])) {
            foreach ($validated['sizes'] as $size => $price) {
                if ($price) {
                    ProductSizePrice::create(['product_id' => $product->id, 'size' => $size, 'price' => $price]);
                }
            }
        }

        return redirect()->route('manajer.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $product->load(['sizePrices', 'optionGroups.options']);

        return view('manajer.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'base_price' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'sizes' => ['nullable', 'array'],
            'sizes.S' => ['nullable', 'integer', 'min:0'],
            'sizes.M' => ['nullable', 'integer', 'min:0'],
            'sizes.L' => ['nullable', 'integer', 'min:0'],
            'is_available' => ['boolean'],
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'base_price' => $validated['base_price'] ?? 0,
            'is_available' => $request->boolean('is_available'),
            'image' => $validated['image'] ?? $product->image,
        ]);

        if ($product->has_size && isset($validated['sizes'])) {
            foreach ($validated['sizes'] as $size => $price) {
                ProductSizePrice::updateOrCreate(
                    ['product_id' => $product->id, 'size' => $size],
                    ['price' => $price ?? 0]
                );
            }
        }

        return redirect()->route('manajer.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function toggleAvailable(Product $product): RedirectResponse
    {
        $product->update(['is_available' => ! $product->is_available]);
        $status = $product->is_available ? 'tersedia' : 'tidak tersedia';

        return redirect()->back()->with('success', "{$product->name} sekarang {$status}.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('manajer.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
