<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // new update -> Arash-abraham
        Schema::create('product_tag', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('tag_id')->constrained('tags');
            $table->primary(['product_id', 'tag_id']);
            $table->timestamps();
            $table->softDeletes();
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_tag');
    }
};

