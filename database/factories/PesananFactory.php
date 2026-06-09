<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PesananFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kode_pesanan' => 'ORD-'.date('Ymd').'-'.strtoupper(Str::random(5)),
            'status' => 'menunggu_pembayaran',
            'total_harga' => 0,
            'biaya_ongkir' => 0,
            'grand_total' => 0,
        ];
    }
}
