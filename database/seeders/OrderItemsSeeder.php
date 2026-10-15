<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $orders = [
            'DEMO-PAID-001' => 'linux-for-hackers',
            'DEMO-PENDING-001' => 'http-definitive-guide',
        ];

        foreach ($orders as $transactionId => $slug) {
            $order = DB::table('orders')->where('transaction_id', $transactionId)->first();
            $product = DB::table('products')->where('slug', $slug)->first();
            if (!$order || !$product) {
                continue;
            }
            $unitPrice = (int) ($product->price ?? $product->original_price);
            DB::table('order_items')->updateOrInsert(
                ['order_id' => $order->id, 'product_id' => $product->id],
                [
                    'product_title' => $product->title,
                    'unit_price' => $unitPrice,
                    'quantity' => 1,
                    'subtotal' => $unitPrice,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ],
            );
        }
    }
}
