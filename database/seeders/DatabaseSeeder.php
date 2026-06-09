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
                'deskripsi' => 'Diskon 10% untuk semua produk premium.',
                'tipe_diskon' => 'persentase',
                'nilai_diskon' => 10,
                'minimum_order' => 50000,
                'kuota' => 100,
                'dipakai' => 0,
                'berlaku_sampai' => now()->addDays(30),
                'is_aktif' => true,
            ]
        );

        KodePromo::updateOrCreate(
            ['kode' => 'HEMAT50K'],
            [
                'deskripsi' => 'Diskon flat Rp 50.000 dengan minimal belanja Rp 200.000.',
                'tipe_diskon' => 'nominal',
                'nilai_diskon' => 50000,
                'minimum_order' => 200000,
                'kuota' => 50,
                'dipakai' => 0,
                'berlaku_sampai' => now()->addDays(30),
                'is_aktif' => true,
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
