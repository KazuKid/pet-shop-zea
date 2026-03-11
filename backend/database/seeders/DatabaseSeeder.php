<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Default admin account
        User::firstOrCreate(
            ['email' => 'admin@zeapetshop.com'],
            [
                'name'     => 'Admin Zea Pet Shop',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
