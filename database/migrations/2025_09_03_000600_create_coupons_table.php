<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique();
            $table->enum('type', ['percentage','fixed']);
            $table->unsignedInteger('value');
            $table->unsignedInteger('max_discount')->nullable();
            $table->unsignedInteger('min_order')->default(0);
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('usage_count')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['active','inactive'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['status', 'start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};

