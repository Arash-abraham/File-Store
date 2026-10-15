<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->where('email', mb_strtolower(env('DEMO_USER_EMAIL', 'user@example.com')))->value('id');
        if (!$userId) {
            return;
        }

        $linux = DB::table('products')->where('slug', 'linux-for-hackers')->first();
        $http = DB::table('products')->where('slug', 'http-definitive-guide')->first();
        if (!$linux || !$http) {
            return;
        }

        $couponId = DB::table('coupons')->where('code', 'WELCOME10')->value('id');
        $now = now();
        $linuxPrice = (int) ($linux->price ?? $linux->original_price);
        $httpPrice = (int) ($http->price ?? $http->original_price);

        DB::table('orders')->updateOrInsert(['transaction_id' => 'DEMO-PAID-001'], [
            'user_id' => $userId,
            'session_token' => null,
            'status' => 'paid',
            'total_amount' => $linuxPrice,
            'discount_amount' => 0,
            'coupon_id' => null,
            'payment_gateway' => 'zarinpal',
            'payment_authority' => null,
            'transaction_id' => 'DEMO-PAID-001',
            'reference' => 'DEMO-REF-001',
            'paid_at' => $now->copy()->subDays(2),
            'paid_from_wallet' => 0,
            'remaining_amount' => 0,
            'payment_method' => 'zarinpal',
            'created_at' => $now->copy()->subDays(2),
            'updated_at' => $now,
            'deleted_at' => null,
        ]);

        DB::table('orders')->updateOrInsert(['transaction_id' => 'DEMO-PENDING-001'], [
            'user_id' => $userId,
            'session_token' => null,
            'status' => 'pending',
            'total_amount' => $httpPrice,
            'discount_amount' => 0,
            'coupon_id' => $couponId,
            'payment_gateway' => 'zarinpal',
            'payment_authority' => null,
            'transaction_id' => 'DEMO-PENDING-001',
            'reference' => null,
            'paid_at' => null,
            'paid_from_wallet' => 0,
            'remaining_amount' => $httpPrice,
            'payment_method' => 'zarinpal',
            'created_at' => $now->subDay(),
            'updated_at' => $now,
            'deleted_at' => null,
        ]);
    }
}
