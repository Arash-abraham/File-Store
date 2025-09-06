<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('session_token', 191)->nullable();
            $table->enum('status', ['active','converted','abandoned'])->default('active');
            $table->timestamps();

            $table->index(['user_id', 'session_token']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};

