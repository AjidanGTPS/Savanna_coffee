<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        $user = auth()->user();

        return match ($user->peran) {
            'admin'   => redirect()->route('admin.produk.index'),
            'kasir'   => redirect()->route('kasir.meja'),
            'pelayan' => redirect()->route('kitchen.index'),
            'owner'   => redirect()->route('laporan.ringkasan'),
            'manajer' => redirect()->route('gudang.bahan-baku.index'),
            default   => redirect()->route('login'),
        };
    }
}
