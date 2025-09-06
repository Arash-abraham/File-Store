<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->enum('status', ['pending','paid','failed','refunded','cancelled'])->default('pending');
            $table->unsignedBigInteger('total_amount');
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->foreignId('coupon_id')->nullable()->constrained('coupons');
            $table->string('payment_gateway', 50)->nullable();
            $table->string('transaction_id', 191)->nullable();
            $table->string('reference', 191)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status', 'coupon_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

