<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('url', 500);
            $table->string('icon', 100)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->enum('target', ['_self','_blank'])->default('_self');
            $table->enum('status', ['active','inactive'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};

