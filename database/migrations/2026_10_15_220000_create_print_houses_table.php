<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('print_houses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->string('city', 80);
            $table->string('province', 80)->nullable();
            $table->text('description')->nullable();
            $table->json('capabilities')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('address', 255)->nullable();
            $table->string('website')->nullable();
            // Price list used by the quote service; stored per printing house.
            $table->unsignedBigInteger('unit_price')->default(0);
            $table->unsignedBigInteger('hardcover_surcharge')->default(0);
            $table->unsignedBigInteger('delivery_fee')->default(0);
            $table->unsignedSmallInteger('delivery_days')->default(7);
            $table->unsignedSmallInteger('min_quantity')->default(1);
            $table->unsignedSmallInteger('max_quantity')->default(20);
            $table->decimal('rating', 2, 1)->default(5.0);
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->timestamps();

            $table->index(['status', 'city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('print_houses');
    }
};
