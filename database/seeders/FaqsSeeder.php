<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('faqs')->insert([
            [
                'question' => 'چگونه می‌توانم خرید کنم؟',
                'answer' => 'برای خرید محصول، ابتدا وارد حساب کاربری خود شوید، سپس محصول مورد نظر را به سبد خرید اضافه کنید و مراحل پرداخت را تکمیل نمایید.',
                'category' => 'purchase',
                'status' => 'published',
                'sort_order' => 1,
                'views' => 0,
                'helpful' => 0,
                'not_helpful' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'question' => 'روش‌های پرداخت کدامند؟',
                'answer' => 'کارت‌های شتاب، درگاه آنلاین، کیف پول و رمزارزها.',
                'category' => 'payment',
                'status' => 'published',
                'sort_order' => 2,
                'views' => 0,
                'helpful' => 0,
                'not_helpful' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

