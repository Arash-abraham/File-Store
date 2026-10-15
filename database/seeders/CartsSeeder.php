<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartsSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->where('email', mb_strtolower(env('DEMO_USER_EMAIL', 'user@example.com')))->value('id');
        if (!$userId) {
            return;
        }

        $now = now();
        DB::table('carts')->updateOrInsert(
            ['user_id' => $userId, 'status' => 'active'],
            [
                'session_token' => Str::random(40),
                'discount' => 0,
                'coupon_code' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        );
    }
}
