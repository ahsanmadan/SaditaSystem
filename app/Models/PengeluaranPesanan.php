<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranPesanan extends Model
{
    protected $table = 'pengeluaran_pesanan';

    protected $guarded = ['id'];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id', 'id');
    }
}
