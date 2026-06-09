<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * ActivityLogger Service
 *
 * Dipakai di semua aksi penting di Filament resource untuk audit trail.
 *
 * Usage:
 *   ActivityLogger::log('verifikasi_pembayaran', $pembayaran, [
 *       'status_before' => 'menunggu',
 *       'status_after'  => 'lunas',
 *   ]);
 */
class ActivityLogger
{
    public static function log(
        string $action,
        ?Model $subject = null,
        array $payload = []
    ): void {
        try {
            ActivityLog::create([
                'user_id'      => Auth::id(),
                'action'       => $action,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id'   => $subject?->getKey(),
                'payload'      => empty($payload) ? null : $payload,
                'ip_address'   => Request::ip(),
            ]);
        } catch (\Throwable $e) {
            // Jangan crash aplikasi karena gagal log
            \Log::error("[ActivityLogger] Gagal menulis log: {$e->getMessage()}");
        }
    }

    // ─── Shortcut Methods ─────────────────────────────────────────────────────

    public static function verifikasiPembayaran(
        \App\Models\Pembayaran $pembayaran,
        string $status,
        ?string $alasan = null
    ): void {
        self::log('verifikasi_pembayaran', $pembayaran, [
            'kode_pesanan'  => $pembayaran->pesanan?->kode_pesanan,
            'jumlah'        => $pembayaran->jumlah_dibayar,
            'status_baru'   => $status,
            'alasan'        => $alasan,
        ]);
    }

    public static function ubahStatusPesanan(
        \App\Models\Pesanan $pesanan,
        string $statusLama,
        string $statusBaru
    ): void {
        self::log('ubah_status_pesanan', $pesanan, [
            'kode_pesanan' => $pesanan->kode_pesanan,
            'status_lama'  => $statusLama,
            'status_baru'  => $statusBaru,
        ]);
    }

    public static function moderasiUlasan(
        \App\Models\Ulasan $ulasan,
        bool $isTampil
    ): void {
        self::log('moderasi_ulasan', $ulasan, [
            'nama_pengulas' => $ulasan->nama_pengulas,
            'produk_id'     => $ulasan->produk_id,
            'rating'        => $ulasan->rating,
            'is_tampil'     => $isTampil,
        ]);
    }

    public static function editHargaProduk(
        \App\Models\Produk $produk,
        int $hargaLama,
        int $hargaBaru
    ): void {
        self::log('edit_harga_produk', $produk, [
            'nama'       => $produk->nama,
            'harga_lama' => $hargaLama,
            'harga_baru' => $hargaBaru,
        ]);
    }
}
