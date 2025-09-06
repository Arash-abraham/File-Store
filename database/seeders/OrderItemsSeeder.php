<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $order1 = DB::table('orders')->where('transaction_id', 'TRX-001')->value('id');
        $order2 = DB::table('orders')->whereNull('transaction_id')->value('id');
        $photoshop = DB::table('products')->where('slug', 'adobe-photoshop-2024')->first();
        $reactCourse = DB::table('products')->where('slug', 'dwrh-kaml-amwzsh-react-js')->first();

        if ($order1 && $photoshop) {
            DB::table('order_items')->insert([
                [
                    'order_id' => $order1,
                    'product_id' => $photoshop->id,
                    'product_title' => $photoshop->title,
                    'unit_price' => 2500000,
                    'quantity' => 1,
                    'subtotal' => 2500000,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }

        if ($order2 && $reactCourse) {
            DB::table('order_items')->insert([
                [
                    'order_id' => $order2,
                    'product_id' => $reactCourse->id,
                    'product_title' => $reactCourse->title,
                    'unit_price' => 450000,
                    'quantity' => 1,
                    'subtotal' => 450000,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }
}

