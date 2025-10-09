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
        Schema::table('klaim', function (Blueprint $table) {
            // Tambahkan kolom claimer_id setelah kolom item_id
            $table->unsignedBigInteger('claimer_id')->after('item_id')->nullable();

            // Tambahkan foreign key jika diperlukan
            $table->foreign('claimer_id')->references('id')->on('pengguna')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('klaim', function (Blueprint $table) {
            // Hapus foreign key terlebih dahulu
            $table->dropForeign(['claimer_id']);

            // Kemudian hapus kolom
            $table->dropColumn('claimer_id');
        });
    }
};
