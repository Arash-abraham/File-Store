<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenusSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $items = [
            ['title' => 'خانه', 'url' => '/', 'sort_order' => 1, 'icon' => 'home'],
            ['title' => 'کتاب‌ها', 'url' => '/catalog', 'sort_order' => 2, 'icon' => 'book-open'],
            ['title' => 'درباره‌ی ما', 'url' => '/about', 'sort_order' => 3, 'icon' => 'sparkles'],
            ['title' => 'پرسش‌های پرتکرار', 'url' => '/faq', 'sort_order' => 4, 'icon' => 'help-circle'],
        ];

        foreach ($items as $item) {
            DB::table('menus')->updateOrInsert(['url' => $item['url']], $item + [
                'target' => '_self',
                'status' => 'active',
                'description' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
