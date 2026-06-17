<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    const STATUS_MENUNGGU = 'menunggu_pembayaran';

    const STATUS_DIPROSES = 'diproses';

    const STATUS_SELESAI = 'selesai';

    const STATUS_SIAPKIRIM = 'siap_kirim';

    const STATUS_DIBATALKAN = 'dibatalkan';

    protected $table = 'pesanan';

    protected $guarded = ['id'];

    protected $casts = [
        'batas_waktu_bayar' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kode_pesanan)) {
                $model->kode_pesanan = 'ORD-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(5));
            }
        });
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id', 'id');
    }

    public function diskon()
    {
        return $this->belongsTo(Diskon::class, 'diskon_id', 'id');
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
