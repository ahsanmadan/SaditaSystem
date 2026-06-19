<?php

namespace Database\Seeders;

use App\Models\KodePromo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin'],
            [
                'name' => 'Admin Sadita (Demo)',
                'password' => Hash::make('admin'),
                'role' => User::ROLE_OWNER,
                'is_admin' => true,
            ]
        );

        KodePromo::updateOrCreate(
            ['kode' => 'SADITA10'],
            [
                'tipe_diskon' => 'persentase',
                'nilai_diskon' => 10,
                'minimum_order' => 100000,
                'kuota' => 25,
                'dipakai' => 0,
                'tanggal_mulai' => now()->toDateString(),
                'tanggal_berakhir' => now()->addDays(30)->toDateString(),
                'is_aktif' => true,
                'deskripsi' => 'Promo uji coba diskon 10 persen untuk order minimal seratus ribu rupiah.',
            ]
        );

        KodePromo::updateOrCreate(
            ['kode' => 'HEMAT50'],
            [
                'tipe_diskon' => 'nominal',
                'nilai_diskon' => 50000,
                'minimum_order' => 250000,
                'kuota' => 10,
                'dipakai' => 0,
                'tanggal_mulai' => now()->toDateString(),
                'tanggal_berakhir' => now()->addDays(14)->toDateString(),
                'is_aktif' => true,
                'deskripsi' => 'Promo potongan lima puluh ribu rupiah untuk order minimal dua ratus lima puluh ribu rupiah.',
            ]
        );

        $seeders = [KatalogSeeder::class];

        // TransactionSeeder uses fakerphp/faker (dev-only dependency)
        // Only run it in local/testing where dev dependencies are installed
        if (app()->environment('local', 'testing')) {
            $seeders[] = TransactionSeeder::class;
        }

        $this->call($seeders);
    }
}
