<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diskon extends Model
{
    use HasFactory;

    protected $table = 'diskon';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_berakhir' => 'datetime',
        'is_aktif' => 'boolean',
    ];

    /**
     * Check if the discount is valid for a given subtotal.
     */
    public function isValidFor(int $subtotal, ?string &$errorMsg = null): bool
    {
        if (!$this->is_aktif) {
            $errorMsg = 'Kode promo sudah tidak aktif.';
            return false;
        }

        $now = now();

        if ($this->tanggal_mulai && $now->lt($this->tanggal_mulai)) {
            $errorMsg = 'Periode promo belum dimulai.';
            return false;
        }

        if ($this->tanggal_berakhir && $now->gt($this->tanggal_berakhir)) {
            $errorMsg = 'Kode promo sudah kedaluwarsa.';
            return false;
        }

        if ($this->kuota !== null && $this->digunakan >= $this->kuota) {
            $errorMsg = 'Kuota promo sudah habis.';
            return false;
        }

        if ($subtotal < $this->minimal_pembelian) {
            $errorMsg = 'Minimal pembelian untuk promo ini adalah Rp ' . number_format($this->minimal_pembelian, 0, ',', '.');
            return false;
        }

        return true;
    }

    /**
     * Calculate discount amount based on subtotal.
     */
    public function calculateDiscount(int $subtotal): int
    {
        $potongan = 0;

        if ($this->tipe === 'persen') {
            $potongan = ($this->nilai / 100) * $subtotal;
            if ($this->maksimal_potongan !== null) {
                $potongan = min($potongan, $this->maksimal_potongan);
            }
        } elseif ($this->tipe === 'nominal') {
            $potongan = $this->nilai;
        }

        // Discount cannot exceed subtotal
        return (int) min($potongan, $subtotal);
    }
}
