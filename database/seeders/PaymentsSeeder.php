<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $paidOrderId = DB::table('orders')->where('transaction_id', 'TRX-001')->value('id');
        $userId = DB::table('users')->where('email', 'user@example.com')->value('id');

        if ($paidOrderId && $userId) {
            DB::table('payments')->insert([
                [
                    'order_id' => $paidOrderId,
                    'user_id' => $userId,
                    'gateway' => 'zarinpal',
                    'gateway_name' => 'Zarinpal',
                    'amount' => 2450000,
                    'status' => 'completed',
                    'transaction_id' => 'PAY-001',
                    'external_ref' => 'ZP-XYZ-123',
                    'meta' => json_encode(['card' => '6037 **** **** 1234']),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }
}

