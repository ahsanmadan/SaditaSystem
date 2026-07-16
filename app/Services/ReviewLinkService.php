<?php

namespace App\Services;

use App\Models\Pesanan;
use App\Models\Ulasan;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ReviewLinkService
{
    public function linksForOrder(Pesanan $order): Collection
    {
        $order->loadMissing([
            'pelanggan',
            'detailItems.produk',
        ]);

        return $order->detailItems
            ->filter(fn ($item) => filled($item->produk_id))
            ->map(function ($item) use ($order) {
                $ulasan = Ulasan::query()->firstOrCreate(
                    [
                        'pesanan_id' => $order->id,
                        'produk_id' => $item->produk_id,
                    ],
                    [
                        'nama_pengulas' => $order->pelanggan?->nama_lengkap ?? 'Pelanggan Sadita',
                        'rating' => 0,
                        'komentar' => null,
                        'foto_ulasan' => null,
                        'token_ulasan' => (string) Str::uuid(),
                        'used_at' => null,
                        'is_tampil' => false,
                    ]
                );

                if (blank($ulasan->token_ulasan)) {
                    $ulasan->forceFill([
                        'token_ulasan' => (string) Str::uuid(),
                    ])->save();
                }

                return [
                    'product_name' => $item->nama_produk_snapshot ?: ($item->produk?->nama ?? 'Produk Sadita'),
                    'url' => route('review.show', ['token' => $ulasan->token_ulasan]),
                    'used' => $ulasan->used_at !== null || (int) $ulasan->rating > 0,
                ];
            })
            ->values();
    }
}
