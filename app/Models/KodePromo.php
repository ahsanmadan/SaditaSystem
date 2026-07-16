<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodePromo extends Model
{
    use HasFactory;

    protected $table = 'kode_promo';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
        'is_aktif' => 'boolean',
    ];

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'kode_promo_id', 'id');
    }

    public function pesananItems()
    {
        return $this->pesanan();
    }

    public function isExpired(): bool
    {
        return $this->tanggal_berakhir !== null && now()->startOfDay()->gt($this->tanggal_berakhir);
    }

    public function isNotStarted(): bool
    {
        return $this->tanggal_mulai !== null && now()->startOfDay()->lt($this->tanggal_mulai);
    }

    public function isQuotaExceeded(): bool
    {
        return $this->kuota !== null && $this->dipakai >= $this->kuota;
    }

    public function meetsMinimumOrder(int $subtotal): bool
    {
        return $subtotal >= (int) $this->minimum_order;
    }

    public function isAvailable(): bool
    {
        return $this->is_aktif
            && ! $this->isNotStarted()
            && ! $this->isExpired()
            && ! $this->isQuotaExceeded();
    }

    public function calculateDiscount(int $subtotal): int
    {
        if ($this->tipe_diskon === 'persentase') {
            return (int) floor(($subtotal * $this->nilai_diskon) / 100);
        }

        return min($subtotal, (int) $this->nilai_diskon);
    }
}
