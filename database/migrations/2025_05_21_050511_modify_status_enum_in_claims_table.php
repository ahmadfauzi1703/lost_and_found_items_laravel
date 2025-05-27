<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            // Hapus kolom status yang ada
            $table->dropColumn('status');
        });

        Schema::table('claims', function (Blueprint $table) {
            // Buat ulang kolom status dengan nilai ENUM yang diperbarui
            $table->enum('status', ['pending', 'approved', 'rejected', 'Claimed'])
                ->default('Claimed')
                ->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            // Hapus kolom status yang dimodifikasi
            $table->dropColumn('status');
        });

        Schema::table('claims', function (Blueprint $table) {
            // Kembalikan ke definisi awal
            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending')
                ->after('notes');
        });
    }
};
