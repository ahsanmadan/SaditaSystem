<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';
    protected $guarded = ['id'];

    protected $casts = [
        'batas_waktu_bayar' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id', 'id');
    }

    public function detailItems()
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id', 'id');
    }

    public function riwayatPembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'pesanan_id', 'id');
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class, 'pesanan_id', 'id');
    }

    public function catatanPengeluaran()
    {
        return $this->hasMany(PengeluaranPesanan::class, 'pesanan_id', 'id');
    }

    public function pengembalian()
    {
        return $this->hasOne(PengembalianPesanan::class, 'pesanan_id', 'id');
    }

    public function ulasanItems()
    {
        return $this->hasMany(Ulasan::class, 'pesanan_id', 'id');
    }

    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class, 'pesanan_id', 'id');
    }
}
