<?php

namespace App\Observers;

use App\Models\Pesanan;
use App\Support\DashboardCache;

class PesananObserver
{
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
        DashboardCache::forgetAll();

        \Log::info("[CacheInvalidation] {$reason} -> cache dashboard di-reset.");
    }
}
