<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketsSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->where('email', mb_strtolower(env('DEMO_USER_EMAIL', 'user@example.com')))->value('id');
        if (!$userId) {
            return;
        }

        $now = now();
        DB::table('tickets')->updateOrInsert(
            ['user_id' => $userId, 'subject' => 'راهنمای استفاده از کتابخانه'],
            [
                'status' => 'closed',
                'message' => 'چطور می‌توانم فایل‌هایی را که خریده‌ام دوباره پیدا کنم؟',
                'response' => 'از منوی حساب کاربری وارد کتابخانه‌ی من شوید؛ فایل‌های خریداری‌شده از همان‌جا قابل دریافت‌اند.',
                'assigned_to' => 'پشتیبانی فایل‌استور',
                'response_time' => $now->copy()->subHours(2),
                'created_at' => $now->copy()->subDays(1),
                'updated_at' => $now,
                'deleted_at' => null,
            ],
        );
    }
}
