<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $categories = [
            ['name' => 'برنامه‌نویسی', 'slug' => 'programming', 'icon' => 'code', 'color' => 'indigo'],
            ['name' => 'کسب‌وکار و رشد فردی', 'slug' => 'business', 'icon' => 'briefcase', 'color' => 'amber'],
            ['name' => 'تاریخ و ادبیات', 'slug' => 'history-literature', 'icon' => 'book-open', 'color' => 'rose'],
            ['name' => 'طراحی و خلاقیت', 'slug' => 'design', 'icon' => 'palette', 'color' => 'violet'],
            ['name' => 'علم و ریاضی', 'slug' => 'science', 'icon' => 'sparkles', 'color' => 'cyan'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(['slug' => $category['slug']], $category + [
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]);
        }
    }
}
