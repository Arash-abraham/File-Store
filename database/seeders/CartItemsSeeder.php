<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartItemsSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->where('email', mb_strtolower(env('DEMO_USER_EMAIL', 'user@example.com')))->value('id');
        $cartId = DB::table('carts')->where('user_id', $userId)->where('status', 'active')->value('id');
        $product = DB::table('products')->where('slug', 'http-definitive-guide')->first();
        if (!$cartId || !$product) {
            return;
        }

        $unitPrice = (int) ($product->price ?? $product->original_price);
        $now = now();
        DB::table('cart_items')->updateOrInsert(
            ['cart_id' => $cartId, 'product_id' => $product->id],
            [
                'quantity' => 1,
                'unit_price' => $unitPrice,
                'subtotal' => $unitPrice,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        );
    }
}
