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
                    ['Papan Standing Mirror Premium', 150000, true],
                    ['Papan Congratulations Eksklusif', 120000, true],
                    ['Papan Rustic Custom', 100000, true],
                    ['Papan Ucapan Selamatan', 85000, true],
                    ['Standing Mirror Besar', 200000, true],
                ],
            ],
            'Hantaran' => [
                'slug' => 'hantaran',
                'desc' => 'Persembahan terbaik untuk hari paling bahagia',
                'items' => [
                    ['Bridesmaid Gift Box', 45000, true],
                    ['Set Hantaran Nikah', 65000, true],
                    ['Hantaran Premium Wedding', 85000, true],
                    ['Seserahan Adat Minang', 75000, true],
                    ['Hantaran Gold Edition', 100000, true],
                ],
            ],
            'Dekorasi' => [
                'slug' => 'dekorasi',
                'desc' => 'Ubah ruangan biasa menjadi momen luar biasa',
                'items' => [
                    ['Dekorasi Lamaran', 400000, false],
                    ['Dekorasi Tunangan', 500000, false],
                    ['Table Setting Premium', 350000, false],
                    ['Dekorasi Grand Opening', 850000, false],
                    ['Dekorasi Akad Nikah', 1500000, false],
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
                $isSewa = $item[2];
                $prodSlug = Str::slug($prodName);
                $prodImg = 'images/' . $prodSlug . '.jpg';

                DB::table('produk')->updateOrInsert(
                    ['slug' => $prodSlug],
                    [
                        'kategori_id' => $kat->id,
                        'nama' => $prodName,
                        'deskripsi' => $prodName . ' by Sadita.',
                        'foto_utama' => $prodImg,
                        'galeri_foto' => json_encode([$prodImg]),
                        'harga_dasar' => $prodPrice,
                        'is_aktif' => true,
                        'is_customizable' => true,
                        'is_sewa' => $isSewa,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]
                );
            }
        }
    }
}
