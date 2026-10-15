<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductReviewsSeeder extends Seeder
{
    public function run(): void
    {
        $productId = DB::table('products')->where('slug', 'linux-for-hackers')->value('id');
        $userId = DB::table('users')->where('email', mb_strtolower(env('DEMO_USER_EMAIL', 'user@example.com')))->value('id');
        if (!$productId || !$userId) {
            return;
        }

        $now = now();
        DB::table('reviews')->updateOrInsert(
            ['product_id' => $productId, 'user_id' => $userId],
            [
                'rating' => 5,
                'body' => 'مطالب کتاب خوش‌ساخت و کاربردی است؛ برای شروع کار با لینوکس انتخاب خیلی خوبی بود.',
                'status' => 'approved',
                'created_at' => $now->copy()->subDay(),
                'updated_at' => $now,
            ],
        );
    }
}
