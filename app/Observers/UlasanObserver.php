<?php

namespace App\Observers;

use App\Models\Ulasan;
use Illuminate\Support\Facades\Cache;

class UlasanObserver
{
    /**
     * Saat ini tidak ada ulasan di KPI dashboard cache.
     * Observer ini disiapkan untuk saat rating summary ditambahkan ke dashboard.
     */
    private array $cacheKeys = [
        // Tambahkan key jika dashboard pakai avg rating / count ulasan
        // 'kpi_rating_summary',
    ];

    public function updated(Ulasan $ulasan): void
    {
        // Log aksi moderasi untuk audit trail
        if ($ulasan->isDirty('is_tampil')) {
            $action = $ulasan->is_tampil ? 'DITAMPILKAN' : 'DISEMBUNYIKAN';
            \Log::info("[UlasanModeasi] Ulasan #{$ulasan->id} ({$ulasan->nama_pengulas}) {$action}", [
                'admin_id' => auth()->id(),
                'produk_id' => $ulasan->produk_id,
                'rating' => $ulasan->rating,
            ]);
        }
    }
}
