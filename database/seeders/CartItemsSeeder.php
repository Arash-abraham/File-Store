<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartItemsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $cartId = DB::table('carts')->value('id');
        $productId = DB::table('products')->where('slug', 'adobe-photoshop-2024')->value('id');

        if ($cartId && $productId) {
            DB::table('cart_items')->insert([
                [
                    'cart_id' => $cartId,
                    'product_id' => $productId,
                    'quantity' => 1,
                    'unit_price' => 2500000,
                    'subtotal' => 2500000,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }
}

