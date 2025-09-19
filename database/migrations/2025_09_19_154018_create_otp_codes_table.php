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
            $table->unsignedBigInteger('user_id');  // ID کاربر
            $table->string('email');  // ایمیل برای شناسایی
            $table->string('otp', 6);  // کد 6 رقمی
            $table->timestamp('expires_at');  // زمان انقضا (5 دقیقه)
            $table->boolean('used')->default(false);  // آیا استفاده شده؟
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