<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Public menu (via barcode meja)
Route::get('/menu/{barcode}', [MenuController::class, 'show'])->name('menu.show');
Route::post('/menu/{barcode}/pesan', [MenuController::class, 'pesan'])->name('menu.pesan');
Route::get('/menu/{barcode}/bayar/{sesi}', [MenuController::class, 'halamanBayar'])->name('menu.bayar');
Route::post('/menu/{barcode}/konfirmasi-qris/{sesi}', [MenuController::class, 'konfirmasiQrisPelanggan'])->name('menu.konfirmasi-qris');
Route::get('/menu/{barcode}/cek-status/{sesi}', [MenuController::class, 'cekStatus'])->name('menu.cek-status');
Route::get('/menu/{barcode}/selesai/{sesi}', [MenuController::class, 'selesai'])->name('menu.selesai');

// Dashboard
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Root: redirect to login if not authenticated, else to dashboard
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Kitchen Display — pelayan dan admin/kasir bisa melihat
Route::middleware(['auth', 'role:admin,kasir,pelayan'])->prefix('kitchen')->name('kitchen.')->group(function () {
    Route::get('/', [KitchenController::class, 'index'])->name('index');
    Route::post('/pesanan/{pesanan}/status', [KitchenController::class, 'updateStatus'])->name('update-status');
    Route::get('/poll', [KitchenController::class, 'poll'])->name('poll');
});

// Kasir
Route::middleware(['auth', 'role:admin,kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/meja', [PesananController::class, 'daftarMeja'])->name('meja');
    Route::get('/sesi-qr/{sesi}/{token}', [PesananController::class, 'bukaViaQr'])->name('sesi-qr');
    Route::get('/sesi/{sesi}', [PesananController::class, 'detailSesi'])->name('sesi.show');
    Route::post('/sesi/{sesi}/pesanan/{pesanan}/batal', [PesananController::class, 'batalPesanan'])->name('pesanan.batal');
    Route::get('/bayar/{sesi}', [PembayaranController::class, 'show'])->name('bayar.show');
    Route::post('/bayar/{sesi}', [PembayaranController::class, 'proses'])->name('bayar.proses');
    Route::post('/bayar/{sesi}/qris', [PembayaranController::class, 'konfirmasiQris'])->name('bayar.qris');
    Route::get('/struk/{pembayaran}', [PembayaranController::class, 'struk'])->name('struk');
    Route::post('/struk/{pembayaran}/sesi-baru', [PembayaranController::class, 'bukaSesiBaru'])->name('struk.sesi-baru');
    Route::get('/riwayat', [PesananController::class, 'riwayat'])->name('riwayat');
});

// Admin — hanya admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Produk
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
    Route::post('/produk/{produk}/toggle', [ProdukController::class, 'toggle'])->name('produk.toggle');
    Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');

    // Meja
    Route::get('/meja', [MejaController::class, 'index'])->name('meja.index');
    Route::get('/meja/create', [MejaController::class, 'create'])->name('meja.create');
    Route::post('/meja', [MejaController::class, 'store'])->name('meja.store');
    Route::get('/meja/{meja}/qr', [MejaController::class, 'qr'])->name('meja.qr');
    Route::post('/meja/{meja}/toggle', [MejaController::class, 'toggle'])->name('meja.toggle');

    // Pengguna
    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
    Route::get('/pengguna/create', [PenggunaController::class, 'create'])->name('pengguna.create');
    Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
    Route::get('/pengguna/{pengguna}/edit', [PenggunaController::class, 'edit'])->name('pengguna.edit');
    Route::put('/pengguna/{pengguna}', [PenggunaController::class, 'update'])->name('pengguna.update');
    Route::post('/pengguna/{pengguna}/toggle', [PenggunaController::class, 'toggle'])->name('pengguna.toggle');
});

// Laporan — admin, kasir, owner bisa lihat laporan penjualan
Route::middleware(['auth', 'role:admin,kasir,owner'])->prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/ringkasan', [ReportController::class, 'ringkasan'])->name('ringkasan');
    Route::get('/penjualan', [ReportController::class, 'penjualan'])->name('penjualan');
    Route::get('/produk-terlaris', [ReportController::class, 'produkTerlaris'])->name('produk-terlaris');
});

// Gudang Bahan Baku — manajer dan admin
Route::middleware(['auth', 'role:admin,manajer,owner'])->prefix('gudang')->name('gudang.')->group(function () {
    Route::get('/bahan-baku', [BahanBakuController::class, 'index'])->name('bahan-baku.index');
    Route::get('/bahan-baku/create', [BahanBakuController::class, 'create'])->name('bahan-baku.create');
    Route::post('/bahan-baku', [BahanBakuController::class, 'store'])->name('bahan-baku.store');
    Route::get('/bahan-baku/{bahanBaku}/edit', [BahanBakuController::class, 'edit'])->name('bahan-baku.edit');
    Route::put('/bahan-baku/{bahanBaku}', [BahanBakuController::class, 'update'])->name('bahan-baku.update');
    Route::post('/bahan-baku/{bahanBaku}/transaksi', [BahanBakuController::class, 'transaksi'])->name('bahan-baku.transaksi');
    Route::get('/laporan-stok', [BahanBakuController::class, 'laporan'])->name('laporan-stok');
});
