<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class PelangganFactory extends Factory {
    public function definition(): array {
        return [
            'nama_lengkap' => $this->faker->name(),
            'no_hp' => '08' . $this->faker->numerify('##########'),
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}
