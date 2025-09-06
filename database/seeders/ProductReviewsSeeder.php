<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductReviewsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $productId = DB::table('products')->where('slug', 'adobe-photoshop-2024')->value('id');
        $userId = DB::table('users')->where('email', 'user@example.com')->value('id');

        if ($productId) {
            DB::table('product_reviews')->insert([
                [
                    'product_id' => $productId,
                    'user_id' => $userId,
                    'rating' => 5,
                    'body' => 'بسیار عالی و کاربردی بود.',
                    'status' => 'approved',
                    'helpful_count' => 10,
                    'reported_count' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }
}

