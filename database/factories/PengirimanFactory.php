<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PengirimanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_penerima' => $this->faker->name(),
            'no_hp_penerima' => '08'.$this->faker->numerify('##########'),
            'alamat_lengkap' => $this->faker->address(),
            'patokan_lokasi' => 'Dekat '.$this->faker->word(),
            'tanggal_pengiriman' => $this->faker->date(),
            'jam_pengiriman' => $this->faker->time(),
            'nama_kurir' => $this->faker->name(),
            'status' => 'terkirim',
        ];
    }
}
