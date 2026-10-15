<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $coupons = [
            [
                'code' => 'WELCOME10',
                'type' => 'percentage',
                'value' => 10,
                'max_discount' => 300000,
                'min_order' => 200000,
                'start_date' => $now->toDateString(),
                'end_date' => $now->copy()->addYear()->toDateString(),
                'status' => 'active',
            ],
            [
                'code' => 'READMORE15',
                'type' => 'percentage',
                'value' => 15,
                'max_discount' => 500000,
                'min_order' => 500000,
                'start_date' => $now->toDateString(),
                'end_date' => $now->copy()->addMonths(2)->toDateString(),
                'status' => 'active',
            ],
        ];

        foreach ($coupons as $coupon) {
            DB::table('coupons')->updateOrInsert(['code' => $coupon['code']], $coupon + [
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]);
        }
    }
}
