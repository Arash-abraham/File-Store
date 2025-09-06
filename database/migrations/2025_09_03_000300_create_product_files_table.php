<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->string('name', 255);
            $table->string('path', 500);
            $table->string('size_label', 50);
            $table->enum('type', ['zip','rar','pdf','mp4','txt','other']);
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->timestamps();

            $table->index(['product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_files');
    }
};

