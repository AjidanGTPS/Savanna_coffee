<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function ringkasan(Request $request): View
    {
        $bulan = $request->integer('bulan', now()->month);
        $tahun = $request->integer('tahun', now()->year);

        $pembayaran = Pembayaran::whereYear('dibayar_pada', $tahun)
            ->whereMonth('dibayar_pada', $bulan)
            ->where('status', 'lunas');

        $total_pendapatan = $pembayaran->sum('total');
        $jumlah_transaksi = $pembayaran->count();
        $rata_rata = $jumlah_transaksi > 0 ? $total_pendapatan / $jumlah_transaksi : 0;

        $per_hari = Pembayaran::selectRaw('DATE(dibayar_pada) as tanggal, COUNT(*) as transaksi, SUM(total) as pendapatan')
            ->whereYear('dibayar_pada', $tahun)
            ->whereMonth('dibayar_pada', $bulan)
            ->where('status', 'lunas')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return view('laporan.ringkasan', compact('total_pendapatan', 'jumlah_transaksi', 'rata_rata', 'per_hari', 'bulan', 'tahun'));
    }

    public function penjualan(Request $request): View
    {
        $bulan = $request->integer('bulan', now()->month);
        $tahun = $request->integer('tahun', now()->year);

        $pembayaran = Pembayaran::with(['sesiMeja.meja', 'kasir'])
            ->whereYear('dibayar_pada', $tahun)
            ->whereMonth('dibayar_pada', $bulan)
            ->where('status', 'lunas')
            ->latest('dibayar_pada')
            ->paginate(20);

        return view('laporan.penjualan', compact('pembayaran', 'bulan', 'tahun'));
    }

    public function produkTerlaris(Request $request): View
    {
        $bulan = $request->integer('bulan', now()->month);
        $tahun = $request->integer('tahun', now()->year);

        $produk = DB::table('item_pesanan as ip')
            ->join('pesanan as ps', 'ps.id', '=', 'ip.pesanan_id')
            ->join('sesi_meja as sm', 'sm.id', '=', 'ps.sesi_meja_id')
            ->join('pembayaran as pb', 'pb.sesi_meja_id', '=', 'sm.id')
            ->whereYear('pb.dibayar_pada', $tahun)
            ->whereMonth('pb.dibayar_pada', $bulan)
            ->where('pb.status', 'lunas')
            ->where('ps.status', '!=', 'dibatalkan')
            ->selectRaw('ip.nama_produk, ip.nama_varian, SUM(ip.jumlah) as total_terjual, SUM(ip.subtotal) as total_penjualan')
            ->groupBy('ip.nama_produk', 'ip.nama_varian')
            ->orderByDesc('total_terjual')
            ->limit(20)
            ->get();

        return view('laporan.produk-terlaris', compact('produk', 'bulan', 'tahun'));
    }
}
