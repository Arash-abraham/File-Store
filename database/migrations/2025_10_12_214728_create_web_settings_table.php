<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('web_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_title');
            $table->text('site_description')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('icon_path')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('web_settings')->insert([
            'site_title' => 'فایل استور',
            'site_description' => 'بهترین و معتبرترین فروشگاه محصولات دیجیتال در ایران',
            'phone' => '021-12345678',
            'email' => 'info@shop.com',
            'address' => 'تهران، خیابان آزادی',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('web_settings');
    }
};