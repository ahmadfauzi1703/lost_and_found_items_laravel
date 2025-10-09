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
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('returner_id')->nullable();
            $table->string('returner_name');
            $table->string('returner_nim')->nullable();
            $table->string('returner_email');
            $table->string('returner_phone');
            $table->string('where_found');
            $table->string('item_photo')->nullable();
            $table->date('return_date');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->timestamps();

            // Foreign keys
            $table->foreign('item_id')->references('id')->on('barang')->onDelete('cascade');
            $table->foreign('returner_id')->references('id')->on('pengguna')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
