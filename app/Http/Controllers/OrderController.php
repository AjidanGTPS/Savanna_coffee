<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with(['table', 'items', 'payment'])
            ->whereNotIn('status', ['paid', 'cancelled'])
            ->latest()
            ->get();

        return view('kasir.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['table', 'items.options', 'payment', 'kasir']);

        return view('kasir.orders.show', compact('order'));
    }

    public function scanTable(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'table_number' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $table = Table::where('number', $validated['table_number'])
            ->where('is_active', true)
            ->firstOrFail();

        return redirect()->route('menu.show', $table->barcode_token);
    }

    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:confirmed,cooking,ready,delivered,cancelled'],
        ]);

        $order->update(['status' => $validated['status']]);

        if ($validated['status'] === 'delivered') {
            $order->table->update(['status' => 'occupied']);
        }

        return response()->json([
            'success' => true,
            'status' => $order->status,
            'status_label' => $order->status_label,
        ]);
    }

    public function history(): View
    {
        $orders = Order::with(['table', 'payment'])
            ->whereIn('status', ['paid', 'cancelled'])
            ->latest()
            ->paginate(20);

        return view('kasir.orders.history', compact('orders'));
    }
}
