<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Support\Str;

class MasterSeeder extends Seeder {
    public function run(): void {
        $data = [
            'Buket' => [
                ['nama' => 'Buket Mawar Premium', 'harga' => 350000, 'sewa' => false],
                ['nama' => 'Buket Wisuda Elegan', 'harga' => 150000, 'sewa' => false],
                ['nama' => 'Buket Uang Custom', 'harga' => 500000, 'sewa' => false],
            ],
            'Papan Bunga' => [
                ['nama' => 'Papan Ucapan Pernikahan', 'harga' => 600000, 'sewa' => false],
                ['nama' => 'Papan Duka Cita Eksklusif', 'harga' => 1200000, 'sewa' => false],
            ],
            'Dekorasi' => [
                ['nama' => 'Dekorasi Lamaran Silver', 'harga' => 3500000, 'sewa' => true],
                ['nama' => 'Dekorasi Pernikahan Gold Package', 'harga' => 15000000, 'sewa' => true],
            ],
            'Hantaran' => [
                ['nama' => 'Paket Hantaran Basic', 'harga' => 500000, 'sewa' => true],
                ['nama' => 'Paket Hantaran Premium', 'harga' => 2500000, 'sewa' => true],
            ],
            'Standing Flower' => [
                ['nama' => 'Standing Flower Grand Opening', 'harga' => 800000, 'sewa' => false],
            ],
            'Balon / Surprise Box' => [
                ['nama' => 'Surprise Box Ulang Tahun', 'harga' => 300000, 'sewa' => false],
            ]
        ];

        foreach ($data as $katName => $produks) {
            $kat = Kategori::create([
                'nama' => $katName,
                'slug' => Str::slug($katName),
                'deskripsi' => 'Kategori ' . $katName
            ]);

            foreach ($produks as $prod) {
                Produk::create([
                    'kategori_id' => $kat->id,
                    'nama' => $prod['nama'],
                    'slug' => Str::slug($prod['nama']),
                    'harga_dasar' => $prod['harga'],
                    'is_sewa' => $prod['sewa'],
                    'is_aktif' => true,
                ]);
            }
        }

        // Seed some demo discounts
        \App\Models\Diskon::create([
            'kode' => 'SADITA10',
            'nama' => 'Diskon Grand Opening 10%',
            'tipe' => 'persen',
            'nilai' => 10,
            'minimal_pembelian' => 100000,
            'kuota' => 100,
            'is_aktif' => true,
        ]);

        \App\Models\Diskon::create([
            'kode' => 'CASHBACK50K',
            'nama' => 'Potongan Langsung 50 Ribu',
            'tipe' => 'nominal',
            'nilai' => 50000,
            'minimal_pembelian' => 300000,
            'kuota' => 50,
            'is_aktif' => true,
        ]);
    }
}
