<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        // Cari atau buat user berdasarkan email, lalu gunakan id mereka
        $user1 = User::firstOrCreate(
            ['email' => 'user1@example.com'],
            ['first_name' => 'User', 'last_name' => 'One', 'password' => bcrypt('password'), 'role' => 'user']
        );

        $user2 = User::firstOrCreate(
            ['email' => 'user2@example.com'],
            ['first_name' => 'User', 'last_name' => 'Two', 'password' => bcrypt('password'), 'role' => 'user']
        );

        // Seed Item dengan kategori hilang dan ditemukan
        Item::create([
            'type' => 'hilang',
            'item_name' => 'Handphone',
            'category' => 'Dokumen',  // Kategori hilang
            'date_of_event' => '2024-06-01',
            'description' => 'Hilang di sekitar kantin kampus',
            'email' => 'user1@example.com',
            'phone_number' => '081234567890',
            'location' => 'Kampus A',
            'photo_path' => 'assets/img/fursuit.jpg',
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => $user1->id,
        ]);

        Item::create([
            'type' => 'ditemukan',
            'item_name' => 'Jam tangan',
            'category' => 'Dokumen',  // Kategori ditemukan
            'date_of_event' => '2024-06-03',
            'description' => 'Ditemukan di parkiran motor',
            'email' => 'user2@example.com',
            'phone_number' => '089876543210',
            'location' => 'Parkiran B',
            'photo_path' => 'assets/img/fursuit.jpg',
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => $user2->id,
        ]);
    }
}
