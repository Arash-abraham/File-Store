<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('otp_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->string('email');  // email for checking
            $table->string('otp', 6);  
            $table->timestamp('expires_at');  // Expiration time (5 minutes)
            $table->boolean('used')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique('email'); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('otp_codes');
    }
};