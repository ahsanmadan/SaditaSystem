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
        'berlaku_sampai' => 'datetime',
        'is_aktif' => 'boolean',
    ];

    public function daftarPesanan()
    {
        return $this->hasMany(Pesanan::class, 'kode_promo_id', 'id');
    }
}
