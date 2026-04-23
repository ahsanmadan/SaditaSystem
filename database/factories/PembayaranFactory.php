<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class PembayaranFactory extends Factory {
    public function definition(): array {
        return [
            'metode' => $this->faker->randomElement(['Transfer BCA', 'Transfer Mandiri', 'Qris', 'Tunai']),
            'jumlah_dibayar' => 0,
            'bukti_transfer' => 'proof_' . $this->faker->uuid() . '.jpg',
            'status' => 'lunas',
        ];
    }
}
