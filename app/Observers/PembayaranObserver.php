<?php

namespace App\Observers;

use App\Models\Pembayaran;
use App\Support\DashboardCache;

class PembayaranObserver
{
    public function updated(Pembayaran $pembayaran): void
    {
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
        DashboardCache::forgetAll();

        \Log::info("[CacheInvalidation] {$reason} -> cache dashboard di-reset.");
    }
}
