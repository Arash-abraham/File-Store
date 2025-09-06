<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $userId = DB::table('users')->where('email', 'user@example.com')->value('id');
        $couponId = DB::table('coupons')->where('code', 'WELCOME10')->value('id');

        DB::table('orders')->insert([
            [
                'user_id' => $userId,
                'status' => 'paid',
                'total_amount' => 2950000,
                'discount_amount' => 500000,
                'coupon_id' => $couponId,
                'payment_gateway' => 'zarinpal',
                'transaction_id' => 'TRX-001',
                'reference' => 'REF-001',
                'paid_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => $userId,
                'status' => 'pending',
                'total_amount' => 450000,
                'discount_amount' => 0,
                'coupon_id' => null,
                'payment_gateway' => null,
                'transaction_id' => null,
                'reference' => null,
                'paid_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

