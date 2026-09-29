<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function show(Order $order): View
    {
        $order->load(['table', 'items.options', 'payment']);

        abort_if(in_array($order->status, ['paid', 'cancelled']), 404);

        return view('kasir.payment', compact('order'));
    }

    public function process(Request $request, Order $order): RedirectResponse
    {
        abort_if($order->status === 'paid', 404);

        $validated = $request->validate([
            'payment_method' => ['required', 'in:tunai,kartu,ewallet'],
            'ewallet_type' => ['nullable', 'required_if:payment_method,ewallet', 'string'],
            'cash_received' => ['nullable', 'required_if:payment_method,tunai', 'integer', 'min:'.$order->total],
        ]);

        $changeReturned = $validated['payment_method'] === 'tunai'
            ? (int) $validated['cash_received'] - $order->total
            : null;

        DB::transaction(function () use ($order, $validated, $changeReturned) {
            Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total,
                'cash_received' => $validated['cash_received'] ?? null,
                'change_returned' => $changeReturned,
                'payment_method' => $validated['payment_method'],
                'ewallet_type' => $validated['ewallet_type'] ?? null,
                'received_by' => Auth::id(),
                'paid_at' => now(),
            ]);

            $order->update(['status' => 'paid']);
            $order->table->update(['status' => 'available']);
        });

        return redirect()->route('kasir.payment.receipt', $order)
            ->with('success', 'Pembayaran berhasil diproses!');
    }

    public function receipt(Order $order): View
    {
        abort_if($order->status !== 'paid', 404);
        $order->load(['table', 'items.options', 'payment.receiver']);

        return view('kasir.receipt', compact('order'));
    }
}
