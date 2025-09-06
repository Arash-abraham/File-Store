<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('settings')->insert([
            ['key' => 'site_name', 'value' => 'فروشگاه آنلاین', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'support_phone', 'value' => '021-12345678', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'support_email', 'value' => 'support@shop.com', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}

