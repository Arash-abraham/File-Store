<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $userId = DB::table('users')->where('email', 'user@example.com')->value('id');
        DB::table('carts')->insert([
            [
                'user_id' => $userId,
                'session_token' => Str::random(32),
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

