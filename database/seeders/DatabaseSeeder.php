<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\ItemSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Pastikan ada user dengan email user1@example.com dan user2@example.com
        User::firstOrCreate(
            ['email' => 'user1@example.com'],
            [
                'password' => Hash::make('password'),
                'first_name' => 'User',
                'last_name' => 'One',
                'role' => 'user',
            ]
        );

        User::firstOrCreate(
            ['email' => 'user2@example.com'],
            [
                'password' => Hash::make('password'),
                'first_name' => 'User',
                'last_name' => 'Two',
                'role' => 'user',
            ]
        );

        // Buat akun admin
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'password' => Hash::make('adminpassword'),
                'first_name' => 'Admin',
                'last_name' => 'Account',
                'role' => 'admin',
            ]
        );

        // Panggil ItemSeeder
        $this->call(ItemSeeder::class);
    }
}
