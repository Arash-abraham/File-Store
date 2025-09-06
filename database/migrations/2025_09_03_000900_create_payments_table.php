<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('user_id')->constrained('users');
            $table->string('gateway', 50);
            $table->string('gateway_name', 50);
            $table->unsignedBigInteger('amount');
            $table->enum('status', ['pending','completed','failed'])->default('pending');
            $table->string('transaction_id', 191);
            $table->string('external_ref', 191)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'user_id', 'status', 'transaction_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

