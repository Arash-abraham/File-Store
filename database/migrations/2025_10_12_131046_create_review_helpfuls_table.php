<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewHelpfulsTable extends Migration
{
    public function up()
    {
        Schema::create('review_helpfuls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['review_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('review_helpfuls');
    }
}