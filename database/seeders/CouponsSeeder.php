<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('coupons')->insert([
            [
                'code' => 'WELCOME10',
                'type' => 'percentage',
                'value' => 10,
                'max_discount' => 500000,
                'min_order' => 0,
                'usage_limit' => 100,
                'usage_count' => 0,
                'start_date' => $now->toDateString(),
                'end_date' => $now->copy()->addMonth()->toDateString(),
                'status' => 'active',
                'description' => '10% تخفیف خوش‌آمدگویی',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'FIXED50',
                'type' => 'fixed',
                'value' => 50000,
                'max_discount' => null,
                'min_order' => 100000,
                'usage_limit' => null,
                'usage_count' => 0,
                'start_date' => null,
                'end_date' => null,
                'status' => 'inactive',
                'description' => '۵۰ هزار تومان تخفیف ثابت',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

