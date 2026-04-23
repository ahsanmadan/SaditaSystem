<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $guarded = ['id'];

    public function riwayatPesanan()
    {
        return $this->hasMany(Pesanan::class, 'pelanggan_id', 'id');
    }
}
