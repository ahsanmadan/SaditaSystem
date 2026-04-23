<?php

namespace App\Services\Analytics;

use App\Models\Pesanan;
use App\Models\Pelanggan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardKpiService
{
    /**
     * Omzet Penjualan Hari Ini (Murni produk/jasa)
     */
    public function getOmzetToday(): int
    {
        return (int) Pesanan::whereDate('created_at', Carbon::today())
            ->where('status', 'selesai')
            ->sum('total_harga');
    }

    /**
     * Penerimaan Kas Bruto Hari Ini (Termasuk Ongkir)
     */
    public function getCashInToday(): int
    {
        return (int) Pesanan::whereDate('created_at', Carbon::today())
            ->whereIn('status', ['diproses', 'selesai'])
            ->sum('grand_total');
    }

    /**
     * Profit Kotor Bulan Ini (Total Harga - Pengeluaran Modal)
     */
    public function getGrossProfitMonth(): int
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        // Omzet murni bulan ini
        $omzet = (int) Pesanan::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('status', 'selesai')
            ->sum('total_harga');

        // Pengeluaran terkait pesanan yang selesai bulan ini
        $pengeluaran = (int) DB::table('pengeluaran_pesanan')
            ->join('pesanan', 'pengeluaran_pesanan.pesanan_id', '=', 'pesanan.id')
            ->whereMonth('pesanan.created_at', $month)
            ->whereYear('pesanan.created_at', $year)
            ->where('pesanan.status', 'selesai')
            ->sum('pengeluaran_pesanan.nominal');

        return $omzet - $pengeluaran;
    }

    /**
     * Total Pesanan Menunggu Pembayaran
     */
    public function getPendingOrdersCount(): int
    {
        return Pesanan::where('status', 'menunggu_pembayaran')->count();
    }

    /**
     * Total Pelanggan Repeat Order (>1 pesanan)
     */
    public function getRepeatCustomerCount(): int
    {
        return DB::table('pesanan')
            ->select('pelanggan_id')
            ->groupBy('pelanggan_id')
            ->havingRaw('COUNT(id) > 1')
            ->get()
            ->count();
    }
}
