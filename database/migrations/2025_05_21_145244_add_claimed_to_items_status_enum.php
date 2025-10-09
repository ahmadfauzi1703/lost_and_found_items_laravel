<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Dapatkan definisi ENUM saat ini
        $enumValues = DB::select("SHOW COLUMNS FROM barang WHERE Field = 'status'")[0]->Type;

        // Ekstrak nilai-nilai ENUM saat ini
        preg_match('/^enum\((.*)\)$/', $enumValues, $matches);
        $currentValues = str_getcsv($matches[1], ',', "'");

        // Tambahkan nilai "Claimed" jika belum ada
        if (!in_array('Claimed', $currentValues)) {
            $currentValues[] = 'Claimed';
            $newEnumDefinition = "'" . implode("','", $currentValues) . "'";

            // Terapkan perubahan ke tabel items
            DB::statement("ALTER TABLE barang MODIFY COLUMN status ENUM($newEnumDefinition)");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Dapatkan definisi ENUM saat ini
        $enumValues = DB::select("SHOW COLUMNS FROM barang WHERE Field = 'status'")[0]->Type;

        // Ekstrak nilai-nilai ENUM saat ini
        preg_match('/^enum\((.*)\)$/', $enumValues, $matches);
        $currentValues = str_getcsv($matches[1], ',', "'");

        // Hapus nilai "Claimed"
        $newValues = array_filter($currentValues, function ($value) {
            return $value !== 'Claimed';
        });

        $newEnumDefinition = "'" . implode("','", $newValues) . "'";

        // Terapkan perubahan ke tabel items
        DB::statement("ALTER TABLE barang MODIFY COLUMN status ENUM($newEnumDefinition)");
    }
};
