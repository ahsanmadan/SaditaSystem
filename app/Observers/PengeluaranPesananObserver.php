<?php

namespace App\Observers;

use App\Models\PengeluaranPesanan;
use Illuminate\Support\Facades\Cache;

class PengeluaranPesananObserver
{
    private array $cacheKeys = [
        'kpi_gross_profit_month', // Profit = omzet - pengeluaran
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
