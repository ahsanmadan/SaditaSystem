<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        User::create([
            'name' => 'Admin Sadita (Demo)',
            'email' => 'admin',
            'password' => Hash::make('admin'),
            'role' => User::ROLE_OWNER,
            'is_admin' => true,
        ]);

        $this->call([
            KatalogSeeder::class,
            TransactionSeeder::class,
        ]);
        $seeders = [
            MasterSeeder::class,
        ];

        // TransactionSeeder uses fakerphp/faker (dev-only dependency)
        // Only run it in local/testing where dev dependencies are installed
        if (app()->environment('local', 'testing')) {
            $seeders[] = TransactionSeeder::class;
        }

        $this->call($seeders);
    }
}
