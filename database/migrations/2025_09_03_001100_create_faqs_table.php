<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question', 300);
            $table->longText('answer');
            $table->string('category', 100);
            $table->enum('status', ['draft','published'])->default('published');
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('helpful')->default(0);
            $table->unsignedInteger('not_helpful')->default(0);
            $table->timestamps();

            $table->index(['status', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};

