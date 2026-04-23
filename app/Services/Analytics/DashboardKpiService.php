<?php

namespace App\Services\Analytics;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardKpiService
{
    /**
     * Omzet Penjualan Hari Ini (Murni produk/jasa) - EVENT TIME
     */
    public function getOmzetTodayByCompletionDate(): int
    {
        return (int) Pesanan::whereDate('waktu_selesai', Carbon::today())
            ->sum('total_harga');
    }

    /**
     * Penerimaan Kas Bruto Hari Ini (Termasuk Ongkir) - EVENT TIME
     */
    public function getCashInTodayByPaymentDate(): int
    {
        return (int) Pembayaran::where('status', 'lunas')
            ->whereDate('waktu_dibayar', Carbon::today())
            ->sum('jumlah_dibayar');
    }

    /**
     * Profit Kotor Bulan Ini (Total Harga - Pengeluaran Modal) - EVENT TIME
     */
    public function getGrossProfitMonthByCompletionDate(): int
    {
        return Cache::remember('kpi_gross_profit_month', 60 * 15, function () {
            $month = Carbon::now()->month;
            $year = Carbon::now()->year;

            // Omzet murni bulan ini dari pesanan selesai
            $omzet = (int) Pesanan::whereMonth('waktu_selesai', $month)
                ->whereYear('waktu_selesai', $year)
                ->sum('total_harga');

            // Pengeluaran terkait pesanan yang selesai bulan ini
            $pengeluaran = (int) DB::table('pengeluaran_pesanan')
                ->join('pesanan', 'pengeluaran_pesanan.pesanan_id', '=', 'pesanan.id')
                ->whereMonth('pesanan.waktu_selesai', $month)
                ->whereYear('pesanan.waktu_selesai', $year)
                ->sum('pengeluaran_pesanan.nominal');

            return $omzet - $pengeluaran;
        });
    }

    /**
     * Total Pesanan Menunggu Pembayaran
     */
    public function getPendingOrdersCount(): int
    {
        // Cache is not needed, simple index scan
        return Pesanan::where('status', 'menunggu_pembayaran')->count();
    }

    /**
     * Total Pelanggan Repeat Order (>1 pesanan)
     */
    public function getRepeatCustomerCount(): int
    {
        return Cache::remember('kpi_repeat_customers', 60 * 10, function () {
            return DB::table('pesanan')
                ->select('pelanggan_id')
                ->groupBy('pelanggan_id')
                ->havingRaw('COUNT(id) > 1')
                ->get()
                ->count();
        });
    }
}
