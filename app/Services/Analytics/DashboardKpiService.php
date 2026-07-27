<?php

namespace App\Services\Analytics;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Support\DashboardCache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardKpiService
{
    public function getOverviewStats(): array
    {
        return Cache::remember(DashboardCache::OVERVIEW_STATS, 60 * 5, function () {
            return [
                'omzet' => $this->getOmzetTodayByCompletionDate(),
                'kas_masuk' => $this->getCashInTodayByPaymentDate(),
                'profit' => $this->getGrossProfitMonthByCompletionDate(),
                'pending' => $this->getPendingOrdersCount(),
                'repeat' => $this->getRepeatCustomerCount(),
            ];
        });
    }

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
        return (int) Pembayaran::where('status', Pembayaran::STATUS_LUNAS)
            ->whereDate('waktu_dibayar', Carbon::today())
            ->sum('jumlah_dibayar');
    }

    /**
     * Profit Kotor Bulan Ini (Total Harga - Pengeluaran Modal) - EVENT TIME
     */
    public function getGrossProfitMonthByCompletionDate(): int
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        $omzet = (int) Pesanan::whereMonth('waktu_selesai', $month)
            ->whereYear('waktu_selesai', $year)
            ->sum('total_harga');

        $pengeluaran = (int) DB::table('pengeluaran_pesanan')
            ->join('pesanan', 'pengeluaran_pesanan.pesanan_id', '=', 'pesanan.id')
            ->whereMonth('pesanan.waktu_selesai', $month)
            ->whereYear('pesanan.waktu_selesai', $year)
            ->sum('pengeluaran_pesanan.nominal');

        return $omzet - $pengeluaran;
    }

    /**
     * Total Pesanan Menunggu Pembayaran
     */
    public function getPendingOrdersCount(): int
    {
        return Pesanan::where('status', Pesanan::STATUS_MENUNGGU)->count();
    }

    /**
     * Total Pelanggan Repeat Order (>1 pesanan)
     */
    public function getRepeatCustomerCount(): int
    {
        $repeatCustomerIds = DB::table('pesanan')
            ->select('pelanggan_id')
            ->groupBy('pelanggan_id')
            ->havingRaw('COUNT(id) > 1');

        return DB::query()->fromSub($repeatCustomerIds, 'repeat_customers')->count();
    }
}
