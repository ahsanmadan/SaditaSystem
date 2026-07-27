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
        'is_sewa' => 'boolean',
        'is_aktif' => 'boolean',
        'galeri_foto' => 'array',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }

    public function gambarItems()
    {
        return $this->hasMany(GambarProduk::class, 'produk_id', 'id')->orderByDesc('is_utama')->orderBy('id');
    }

    public function fotoUtamaUrl(): string
    {
        $gambarUtama = $this->relationLoaded('gambarItems')
            ? $this->gambarItems->firstWhere('is_utama') ?? $this->gambarItems->first()
            : $this->gambarItems()->orderByDesc('is_utama')->orderBy('id')->first();

        $paths = array_filter([
            $gambarUtama?->path_gambar,
            $this->foto_utama,
            ...array_values($this->galeri_foto ?? []),
            'images/'.$this->slug.'.jpg',
            $this->kategori?->slug ? 'images/cat-'.$this->kategori->slug.'.jpg' : null,
            'images/hero-1.jpg',
        ]);

        foreach ($paths as $path) {
            if (filter_var($path, FILTER_VALIDATE_URL)) {
                return $path;
            }

            $path = ltrim($path, '/');

            if (file_exists(public_path($path))) {
                return asset($path);
            }

            if (file_exists(storage_path('app/public/'.$path))) {
                return asset('storage/'.$path);
            }
        }

        return asset('images/hero-1.jpg');
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
