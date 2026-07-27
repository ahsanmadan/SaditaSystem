<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UlasanFactory extends Factory
{
    public function definition(): array
    {
        $rating = $this->faker->boolean(85) ? $this->faker->numberBetween(4, 5) : $this->faker->numberBetween(1, 3);
        $comments = [
            5 => ['Sangat puas! Dekorasi sesuai ekspektasi.', 'Bunga segar, pasangan saya suka sekali!', 'Terima kasih Sadita, mantap.'],
            4 => ['Bagus, tapi datangnya agak mepet.', 'Lumayan bagus, sesuai harga.', 'Papan bunganya rapi.'],
            3 => ['Standar saja, tidak terlalu mewah.', 'Bunga ada yang sedikit layu.'],
            2 => ['Kurang sesuai dengan gambar referensi.', 'Pengiriman sangat telat!'],
            1 => ['Sangat mengecewakan, rusak saat tiba!', 'Respon lambat, barang tidak sesuai.'],
        ];

        return [
            'nama_pengulas' => $this->faker->name(),
            'rating' => $rating,
            'komentar' => $this->faker->randomElement($comments[$rating]),
            'token_ulasan' => $this->faker->uuid(),
            'is_tampil' => true,
        ];
    }
}
