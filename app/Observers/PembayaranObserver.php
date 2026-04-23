<?php

namespace App\Observers;

use App\Models\Pembayaran;
use Illuminate\Support\Facades\Cache;

class PembayaranObserver
{
    /**
     * Cache keys selaras dengan DashboardKpiService dan DashboardReportService.
     */
    private array $cacheKeys = [
        'kpi_gross_profit_month',   // DashboardKpiService
        'kpi_repeat_customers',     // DashboardKpiService
        'kpi_top_pelanggan',        // DashboardReportService
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
