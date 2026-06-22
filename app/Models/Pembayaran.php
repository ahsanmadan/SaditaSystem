<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    // ─── Konstanta Status Pembayaran ─────────────────────────────────────────
    const STATUS_MENUNGGU = 'menunggu';

    const STATUS_LUNAS = 'lunas';

    const STATUS_DITOLAK = 'ditolak';

    const METODE_DOKU_CHECKOUT = 'doku_checkout';

    protected $table = 'pembayaran';

    protected $guarded = ['id'];

    protected $casts = [
        'waktu_dibayar' => 'datetime',
        'waktu_diverifikasi' => 'datetime',
        'expires_at' => 'datetime',
        'gateway_payload' => 'array',
        'gateway_response' => 'array',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id', 'id');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh', 'id');
    }
}
