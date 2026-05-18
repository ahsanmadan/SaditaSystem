<?php

namespace App\Observers;

use App\Models\Pesanan;
use App\Services\Analytics\DashboardKpiService;
use Illuminate\Support\Facades\Cache;

class PesananObserver
{
    private array $cacheKeys = [
        DashboardKpiService::OVERVIEW_CACHE_KEY,
        'dashboard_chart_omzet_30_hari',
        'dashboard_chart_status_pesanan',
        'kpi_top_pelanggan',
    ];

    public function created(Pesanan $pesanan): void
    {
        $this->invalidateCache("Pesanan baru #{$pesanan->kode_pesanan}");
    }

    public function updated(Pesanan $pesanan): void
    {
        if ($pesanan->isDirty([
            'status',
            'waktu_selesai',
            'total_harga',
            'grand_total',
            'pelanggan_id',
        ])) {
            $this->invalidateCache("Pesanan #{$pesanan->kode_pesanan} diperbarui");
        }
    }

    public function deleted(Pesanan $pesanan): void
    {
        $this->invalidateCache("Pesanan #{$pesanan->kode_pesanan} dihapus");
    }

    private function invalidateCache(string $reason): void
    {
        foreach ($this->cacheKeys as $key) {
            Cache::forget($key);
        }

        \Log::info("[CacheInvalidation] {$reason} -> cache dashboard di-reset.");
    }
}
