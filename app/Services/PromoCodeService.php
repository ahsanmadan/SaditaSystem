<?php

namespace App\Services;

use App\Models\KodePromo;
use App\Models\Pesanan;

class PromoCodeService
{
    public function applyToOrder(Pesanan $pesanan, ?string $promoCode): array
    {
        $subtotal = (int) $pesanan->total_harga;

        if (blank($promoCode)) {
            $this->clearFromOrder($pesanan, $subtotal);

            return [
                'valid' => true,
                'promo' => null,
                'discount' => 0,
                'message' => 'Kode promo dibersihkan.',
                'cleared' => true,
            ];
        }

        $result = $this->evaluate($promoCode, $subtotal);

        if (! $result['valid']) {
            return $result;
        }

        /** @var \App\Models\KodePromo $promo */
        $promo = $result['promo'];
        $discount = (int) $result['discount'];

        $pesanan->update([
            'kode_promo_id' => $promo->id,
            'kode_promo_snapshot' => $promo->kode,
            'diskon' => $discount,
            'grand_total' => max(0, $subtotal - $discount),
        ]);

        return [
            'valid' => true,
            'promo' => $promo,
            'discount' => $discount,
            'message' => 'Kode promo berhasil dipakai.',
            'cleared' => false,
        ];
    }

    public function evaluate(?string $promoCode, int $subtotal): array
    {
        if (blank($promoCode)) {
            return [
                'valid' => true,
                'promo' => null,
                'discount' => 0,
                'message' => null,
            ];
        }

        $normalizedCode = strtoupper(trim((string) $promoCode));
        $promo = KodePromo::query()->where('kode', $normalizedCode)->first();

        if (! $promo) {
            return $this->invalidResult('Kode promo tidak ditemukan.');
        }

        if (! $promo->is_aktif) {
            return $this->invalidResult('Kode promo sedang tidak aktif.');
        }

        if ($promo->isNotStarted()) {
            return $this->invalidResult('Kode promo belum mulai berlaku.');
        }

        if ($promo->isExpired()) {
            return $this->invalidResult('Kode promo sudah melewati masa berlaku.');
        }

        if ($promo->isQuotaExceeded()) {
            return $this->invalidResult('Kuota penggunaan kode promo sudah habis.');
        }

        if (! $promo->meetsMinimumOrder($subtotal)) {
            return $this->invalidResult(
                'Minimal transaksi untuk promo ini adalah Rp '.number_format((int) $promo->minimum_order, 0, ',', '.').'.'
            );
        }

        return [
            'valid' => true,
            'promo' => $promo,
            'discount' => $promo->calculateDiscount($subtotal),
            'message' => null,
        ];
    }

    public function clearFromOrder(Pesanan $pesanan, ?int $subtotal = null): void
    {
        $amount = $subtotal ?? (int) $pesanan->total_harga;

        $pesanan->update([
            'kode_promo_id' => null,
            'kode_promo_snapshot' => null,
            'diskon' => 0,
            'grand_total' => $amount,
        ]);
    }

    private function invalidResult(string $message): array
    {
        return [
            'valid' => false,
            'promo' => null,
            'discount' => 0,
            'message' => $message,
        ];
    }
}
