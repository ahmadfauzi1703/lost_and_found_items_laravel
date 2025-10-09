<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('barang', function (Blueprint $table) {
            // Mengubah kolom 'type' menjadi enum baru dengan tambahan nilai
            $table->enum('type', ['hilang', 'ditemukan', 'Elektronik', 'Handphone', 'Aksesoris', 'Pakaian', 'Dokumen', 'Lainnya'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('barang', function (Blueprint $table) {
            // Mengembalikan kolom 'type' ke nilai enum sebelumnya jika migrasi dibatalkan
            $table->enum('type', ['hilang', 'ditemukan'])->change();
        });
    }
};
