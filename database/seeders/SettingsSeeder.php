<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('settings')->updateOrInsert(['id' => 1], [
            'logo' => '',
            'icon' => '',
            'siteTitle' => 'فایل‌استور',
            'description' => 'جایی برای پیدا کردن کتاب بعدیِ شما.',
            'phone' => '021-9100-2626',
            'email' => 'hello@filestore.local',
            'address' => 'تهران، ایران',
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => null,
        ]);
    }
}
