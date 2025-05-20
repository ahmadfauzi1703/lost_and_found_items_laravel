<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
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
            'user_id' => 1,
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
            'user_id' => 2,
        ]);
    }
}
