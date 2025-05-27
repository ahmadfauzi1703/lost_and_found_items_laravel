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
            $table->string('type')->default('claim')->after('item_id'); // 'claim' atau 'return'
            $table->string('where_found')->nullable()->after('proof_document'); // lokasi untuk return
            $table->string('item_photo')->nullable()->after('where_found'); // foto untuk return
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn(['type', 'where_found', 'item_photo']);
        });
    }
};
