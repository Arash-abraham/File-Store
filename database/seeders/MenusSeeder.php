<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenusSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('menus')->insert([
            [
                'title' => 'خانه',
                'url' => '/',
                'icon' => null,
                'sort_order' => 1,
                'target' => 'self',
                'status' => 'active',
                'description' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'محصولات',
                'url' => '/products',
                'icon' => null,
                'sort_order' => 2,
                'target' => 'self',
                'status' => 'active',
                'description' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'سوالات متداول',
                'url' => '/faq',
                'icon' => null,
                'sort_order' => 3,
                'target' => 'self',
                'status' => 'active',
                'description' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

