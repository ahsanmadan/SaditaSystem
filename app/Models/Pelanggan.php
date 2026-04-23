<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $guarded = ['id'];

    public function riwayatPesanan()
    {
        return $this->hasMany(Pesanan::class, 'pelanggan_id', 'id');
    }
}
