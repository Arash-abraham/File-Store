<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
            $table->enum('role', ['admin', 'user'])->default('user')->after('password');
            $table->string('avatar_url', 255)->nullable()->after('role');
            $table->date('birthdate')->nullable()->after('avatar_url');
            $table->boolean('marketing_opt_in')->default(false)->after('remember_token');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

