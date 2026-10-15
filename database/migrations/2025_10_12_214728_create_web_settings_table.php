<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The initial web-settings table is created by an earlier migration.
     * Keep this historical duplicate safe for fresh databases and old installs.
     */
    public function up(): void
    {
        if (Schema::hasTable('web_settings')) {
            return;
        }

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

        DB::table('web_settings')->insert([
            'site_title' => 'فایل‌استور',
            'site_description' => 'کتاب‌های دیجیتال، برای مسیرهای تازه‌ی یادگیری.',
            'phone' => '021-9100-2626',
            'email' => 'hello@filestore.local',
            'address' => 'تهران، ایران',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        // The table belongs to the earlier create_web_settings migration.
    }
};
