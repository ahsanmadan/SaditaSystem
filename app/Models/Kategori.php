<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kategori extends Model
{
    use SoftDeletes;

    protected $table = 'kategori';

    protected $guarded = ['id'];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    public function daftarProduk()
    {
        return $this->hasMany(Produk::class, 'kategori_id', 'id');
    }
}
