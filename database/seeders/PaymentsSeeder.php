<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsSeeder extends Seeder
{
    public function run(): void
    {
        $order = DB::table('orders')->where('transaction_id', 'DEMO-PAID-001')->first();
        if (!$order) {
            return;
        }

        $userId = $order->user_id;
        $now = now();
        DB::table('payments')->updateOrInsert(['transaction_id' => 'DEMO-PAYMENT-001'], [
            'order_id' => $order->id,
            'user_id' => $userId,
            'gateway' => 'zarinpal',
            'gateway_name' => 'زرین‌پال (نمونه)',
            'amount' => max(0, (int) $order->total_amount - (int) $order->discount_amount),
            'status' => 'completed',
            'transaction_id' => 'DEMO-PAYMENT-001',
            'external_ref' => 'DEMO-REF-001',
            'meta' => json_encode(['seed' => true], JSON_UNESCAPED_UNICODE),
            'paid_at' => $order->paid_at,
            'response_code' => '100',
            'created_at' => $now->copy()->subDays(2),
            'updated_at' => $now,
            'deleted_at' => null,
        ]);
    }
}
