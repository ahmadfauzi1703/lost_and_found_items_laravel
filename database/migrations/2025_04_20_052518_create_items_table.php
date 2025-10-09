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
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['hilang', 'ditemukan']);
            $table->string('item_name');
            $table->string('category');
            $table->date('date_of_event');
            $table->text('description')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number', 15)->nullable();
            $table->string('location', 100)->nullable();
            $table->string('photo_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
            $table->unsignedBigInteger('user_id');  // Relasi ke tabel pengguna
            $table->foreign('user_id')->references('id')->on('pengguna')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
