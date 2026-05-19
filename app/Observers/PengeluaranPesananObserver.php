<?php

namespace App\Observers;

use App\Models\PengeluaranPesanan;
use App\Services\Analytics\DashboardKpiService;
use Illuminate\Support\Facades\Cache;

class PengeluaranPesananObserver
{
    private array $cacheKeys = [
        DashboardKpiService::OVERVIEW_CACHE_KEY,
        'dashboard_chart_omzet_30_hari',
    ];

    public function created(PengeluaranPesanan $pengeluaran): void
    {
        $this->invalidateCache("Pengeluaran baru #{$pengeluaran->id} pesanan={$pengeluaran->pesanan_id}");
    }

    public function updated(PengeluaranPesanan $pengeluaran): void
    {
        $this->invalidateCache("Pengeluaran #{$pengeluaran->id} diubah");
    }

    public function deleted(PengeluaranPesanan $pengeluaran): void
    {
        $this->invalidateCache("Pengeluaran #{$pengeluaran->id} dihapus");
    }

    private function invalidateCache(string $reason): void
    {
        foreach ($this->cacheKeys as $key) {
            Cache::forget($key);
        }

        \Log::info("[CacheInvalidation] {$reason} → cache gross profit di-reset.");
    }
}
