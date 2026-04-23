<?php

namespace App\Services\Analytics;

use App\Models\Pesanan;
use App\Models\PengembalianPesanan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardReportService
{
    public function getDaftarPesananTerbaru($limit = 5)
    {
        return Pesanan::with('pelanggan')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getPesananOverduePembayaran()
    {
        return Pesanan::with('pelanggan')
            ->where('status', 'menunggu_pembayaran')
            ->where('batas_waktu_bayar', '<', Carbon::now())
            ->get();
    }

    public function getPengembalianDendaBelumLunas()
    {
        return PengembalianPesanan::with('pesanan.pelanggan')
            ->where('status_denda', 'belum_dibayar')
            ->get();
    }

    public function getTopPelangganByRevenue($limit = 10)
    {
        // Agregasi di level database (SUM grand_total)
        return Pesanan::select('pelanggan_id', DB::raw('SUM(grand_total) as total_revenue'))
            ->where('status', 'selesai')
            ->groupBy('pelanggan_id')
            ->orderByDesc('total_revenue')
            ->limit($limit)
            ->with('pelanggan')
            ->get();
    }
}
