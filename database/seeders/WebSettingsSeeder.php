<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('web_settings')->updateOrInsert(['id' => 1], [
            'site_title' => 'فایل‌استور',
            'site_description' => 'کتاب‌های دیجیتال، برای مسیرهای تازه‌ی یادگیری.',
            'phone' => '021-9100-2626',
            'email' => 'hello@filestore.local',
            'address' => 'تهران، ایران',
            'logo_path' => null,
            'icon_path' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
