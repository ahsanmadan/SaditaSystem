<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $guarded = ['id'];

    // Accessor agar bisa dipanggil $pelanggan->nama (alias dari nama_lengkap)
    public function getNamaAttribute(): ?string
    {
        return $this->nama_lengkap;
    }

    public function riwayatPesanan()
    {
        return $this->hasMany(Pesanan::class, 'pelanggan_id', 'id');
    }
}
