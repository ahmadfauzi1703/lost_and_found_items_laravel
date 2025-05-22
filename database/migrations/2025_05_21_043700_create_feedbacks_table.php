<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbacksTable extends Migration
{
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // relasi ke user
            $table->tinyInteger('rating')->nullable(); // rating bintang (1-5)
            $table->text('description')->nullable(); // deskripsi umpan balik
            $table->text('comments')->nullable(); // komentar dan saran
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('feedbacks');
    }
}
