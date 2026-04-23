<?php

namespace App\Observers;

use App\Models\Pesanan;
use Illuminate\Support\Facades\Cache;

class PesananObserver
{
    private array $cacheKeys = [
        'kpi_gross_profit_month',
        'kpi_repeat_customers',
        'kpi_top_pelanggan',
    ];

    public function updated(Pesanan $pesanan): void
    {
        // Invalidate hanya jika status berubah ke selesai atau dari selesai
        if ($pesanan->isDirty('status')) {
            $this->invalidateCache(
                "Pesanan #{$pesanan->kode_pesanan} status: {$pesanan->getOriginal('status')} → {$pesanan->status}"
            );
        }
    }

    private function invalidateCache(string $reason): void
    {
        foreach ($this->cacheKeys as $key) {
            Cache::forget($key);
        }

        \Log::info("[CacheInvalidation] {$reason} → cache KPI di-reset.");
    }
}
