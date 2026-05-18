<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            'Papan Ucapan' => [
                'slug' => 'papan-ucapan',
                'desc' => 'Hadirkan kesan pertama yang tak terlupakan',
                'items' => [
                    ['Papan Standing Mirror Premium', 150000, 'papan-1.jpg', false], 
                    ['Papan Congratulations Eksklusif', 120000, 'papan-2.jpg', false], 
                    ['Papan Rustic Custom', 100000, 'papan-3.jpg', false], 
                    ['Papan Ucapan Selamatan', 85000, 'papan-4.jpg', false], 
                    ['Standing Mirror Besar', 200000, 'papan-5.jpg', false]
                ],
            ],
            'Hantaran' => [
                'slug' => 'hantaran',
                'desc' => 'Persembahan terbaik untuk hari paling bahagia',
                'items' => [
                    ['Bridesmaid Gift Box', 45000, 'hantaran-1.jpg', true], 
                    ['Set Hantaran Nikah', 65000, 'hantaran-2.jpg', true], 
                    ['Hantaran Premium Wedding', 85000, 'hantaran-3.jpg', true], 
                    ['Seserahan Adat Minang', 75000, 'hantaran-4.jpg', true], 
                    ['Hantaran Gold Edition', 100000, 'hantaran-5.jpg', true]
                ],
            ],
            'Dekorasi' => [
                'slug' => 'dekorasi',
                'desc' => 'Ubah ruangan biasa menjadi momen luar biasa',
                'items' => [
                    ['Dekorasi Lamaran', 400000, 'dekorasi-1.jpg', true], 
                    ['Dekorasi Tunangan', 500000, 'dekorasi-2.jpg', true], 
                    ['Table Setting Premium', 350000, 'dekorasi-3.jpg', true], 
                    ['Dekorasi Grand Opening', 850000, 'dekorasi-4.jpg', true], 
                    ['Dekorasi Akad Nikah', 1500000, 'dekorasi-5.jpg', true]
                ],
            ],
        ];

        foreach ($products as $catName => $catData) {
            $katSlug = $catData['slug'];
            DB::table('kategori')->updateOrInsert(
                ['slug' => $katSlug],
                [
                    'nama' => $catName,
                    'deskripsi' => $catData['desc'],
                    'is_aktif' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );

            $kat = DB::table('kategori')->where('slug', $katSlug)->first();

            foreach ($catData['items'] as $item) {
                $prodName = $item[0];
                $prodPrice = $item[1];
                $prodImg = $item[2];
                $isSewa = $item[3];

                DB::table('produk')->updateOrInsert(
                    ['slug' => Str::slug($prodName)],
                    [
                        'kategori_id' => $kat->id,
                        'nama' => $prodName,
                        'deskripsi' => $prodName . ' by Sadita.',
                        'harga_dasar' => $prodPrice,
                        'is_aktif' => true,
                        'is_customizable' => true,
                        'is_sewa' => $isSewa,
                        'galeri_foto' => json_encode(['produk/' . $prodImg]),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]
                );
            }
        }
    }
}
