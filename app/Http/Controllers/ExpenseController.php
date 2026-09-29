<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(): View
    {
        $expenses = Expense::with('creator')
            ->latest('date')
            ->paginate(20);

        return view('manajer.expenses.index', compact('expenses'));
    }

    public function create(): View
    {
        return view('manajer.expenses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'category' => ['required', 'in:bahan_baku,utilitas,gaji,sewa,peralatan,lainnya'],
            'amount' => ['required', 'integer', 'min:1'],
            'date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        Expense::create(array_merge($validated, ['created_by' => Auth::id()]));

        return redirect()->route('manajer.expenses.index')->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return redirect()->route('manajer.expenses.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
