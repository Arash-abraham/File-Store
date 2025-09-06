<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 50)->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->string('subject', 200);
            $table->enum('category', ['technical','financial','general']);
            $table->enum('priority', ['low','medium','high'])->default('medium');
            $table->enum('status', ['open','in_progress','closed'])->default('open');
            $table->longText('message');
            $table->longText('response')->nullable();
            $table->string('assigned_to', 150)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status', 'category', 'priority']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};

