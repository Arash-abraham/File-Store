<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // new update -> Arash-abraham
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 200)->unique();
            $table->foreignId('category_id')->constrained('categories');
            $table->enum('status', ['active','inactive','draft'])->default('active');
            $table->boolean('availability')->default(true);
            $table->unsignedBigInteger('original_price');
            $table->unsignedBigInteger('price')->nullable();
            $table->string('image_url', 500)->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

