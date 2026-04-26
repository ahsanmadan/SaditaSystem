<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        User::create([
            'name' => 'Admin Sadita',
            'email' => 'admin@sadita.com',
            'password' => Hash::make('Sadita@Admin2026!'),
        ]);

        $this->call([
            MasterSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
