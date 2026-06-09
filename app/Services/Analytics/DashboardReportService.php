<?php

namespace App\Services\Analytics;

use App\Models\PengembalianPesanan;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
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
        return Cache::remember('kpi_top_pelanggan', 60 * 15, function () use ($limit) {
            return Pesanan::select('pelanggan_id', DB::raw('SUM(grand_total) as total_revenue'))
                ->where('status', 'selesai')
                ->groupBy('pelanggan_id')
                ->orderByDesc('total_revenue')
                ->limit($limit)
                ->with('pelanggan')
                ->get();
        });
    }
}
