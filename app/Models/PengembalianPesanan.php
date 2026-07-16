<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengembalianPesanan extends Model
{
    protected $table = 'pengembalian_pesanan';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_jemput' => 'date',
        'tanggal_pengambilan' => 'date',
        'waktu_dijemput' => 'datetime',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id', 'id');
    }
}
