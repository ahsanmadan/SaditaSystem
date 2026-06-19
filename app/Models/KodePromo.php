<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KodePromo extends Model
{
    protected $table = 'kode_promo';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
        'is_aktif' => 'boolean',
    ];

    public function pesananItems()
    {
        return $this->hasMany(Pesanan::class, 'kode_promo_id', 'id');
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

    public function calculateDiscount(int $subtotal): int
    {
        if ($this->tipe_diskon === 'persentase') {
            return (int) floor(($subtotal * $this->nilai_diskon) / 100);
        }

        return min($subtotal, (int) $this->nilai_diskon);
    }
}
