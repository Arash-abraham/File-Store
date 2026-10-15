<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('print_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('print_house_id')->nullable()->constrained('print_houses')->nullOnDelete();
            // Snapshots: a later rename or price change must not rewrite order history.
            $table->string('product_title', 200);
            $table->string('print_house_name', 150)->nullable();
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->enum('paper_type', ['standard', 'premium', 'cream'])->default('standard');
            $table->enum('binding', ['paperback', 'hardcover', 'spiral'])->default('paperback');
            $table->boolean('color_print')->default(false);
            $table->string('shipping_name', 150);
            $table->string('shipping_phone', 30);
            $table->string('shipping_city', 80);
            $table->string('shipping_address', 500);
            $table->string('postal_code', 20)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('unit_price')->default(0);
            $table->unsignedBigInteger('delivery_fee')->default(0);
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->unsignedBigInteger('total_price')->default(0);
            // True when the buyer already owns the digital copy and received the print discount.
            $table->boolean('digital_owner')->default(false);
            $table->unsignedSmallInteger('delivery_days')->default(7);
            $table->enum('status', ['pending', 'confirmed', 'printing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['print_house_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('print_orders');
    }
};
