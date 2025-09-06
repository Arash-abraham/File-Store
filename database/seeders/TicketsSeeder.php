<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TicketsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $userId = DB::table('users')->where('email', 'user@example.com')->value('id');

        DB::table('tickets')->insert([
            [
                'ticket_number' => strtoupper(Str::random(8)),
                'user_id' => $userId,
                'subject' => 'مشکل در دانلود فایل',
                'category' => 'technical',
                'priority' => 'high',
                'status' => 'open',
                'message' => 'فایل دانلودی باز نمی‌شود.',
                'response' => null,
                'assigned_to' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

