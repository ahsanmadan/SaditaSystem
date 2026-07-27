<?php

namespace App\Observers;

use App\Models\PengeluaranPesanan;
use App\Support\DashboardCache;

class PengeluaranPesananObserver
{
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
        DashboardCache::forgetAll();

        \Log::info("[CacheInvalidation] {$reason} -> cache gross profit di-reset.");
    }
}
