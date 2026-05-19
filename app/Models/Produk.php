<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use SoftDeletes;

    protected $table = 'produk';
    protected $guarded = ['id'];

    protected $casts = [
        'is_customizable' => 'boolean',
        'is_sewa'         => 'boolean',
        'is_aktif'        => 'boolean',
        'galeri_foto'     => 'array',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }

    public function gambarItems()
    {
        return $this->hasMany(GambarProduk::class, 'produk_id', 'id');
    }

    public function itemTerjual()
    {
        return $this->hasMany(DetailPesanan::class, 'produk_id', 'id');
    }

    public function ulasanItems()
    {
        return $this->hasMany(Ulasan::class, 'produk_id', 'id');
    }
}
