<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('print_contract_requests', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 150);
            $table->string('contact_name', 120);
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->string('city', 80);
            $table->unsignedInteger('monthly_capacity')->nullable();
            $table->json('capabilities')->nullable();
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'reviewing', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('print_contract_requests');
    }
};
