<?php

namespace App\Observers;

use App\Models\Pembayaran;
use App\Services\Analytics\DashboardKpiService;
use Illuminate\Support\Facades\Cache;

class PembayaranObserver
{
    /**
     * Cache keys selaras dengan DashboardKpiService dan DashboardReportService.
     */
    private array $cacheKeys = [
        DashboardKpiService::OVERVIEW_CACHE_KEY,
        'kpi_top_pelanggan',
    ];

    public function updated(Pembayaran $pembayaran): void
    {
        // Hanya invalidate jika status berubah (verifikasi / penolakan)
        if ($pembayaran->isDirty('status')) {
            $this->invalidateCache("Pembayaran #{$pembayaran->id} status={$pembayaran->status}");
        }
    }

    public function created(Pembayaran $pembayaran): void
    {
        $this->invalidateCache("Pembayaran baru #{$pembayaran->id}");
    }

    private function invalidateCache(string $reason): void
    {
        foreach ($this->cacheKeys as $key) {
            Cache::forget($key);
        }

        \Log::info("[CacheInvalidation] {$reason} → cache dashboard di-reset.");
    }
}
