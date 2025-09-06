<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('categories')->insert([
            [
                'name' => 'نرم‌افزارها',
                'slug' => 'software',
                'icon' => 'fa-laptop-code',
                'color' => 'blue',
                'description' => 'انواع نرم‌افزارهای کاربردی',
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'name' => 'دوره‌های آموزشی',
                'slug' => 'courses',
                'icon' => 'fa-play-circle',
                'color' => 'green',
                'description' => 'آموزش‌های تخصصی',
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'name' => 'کتاب‌های الکترونیکی',
                'slug' => 'ebooks',
                'icon' => 'fa-book',
                'color' => 'purple',
                'description' => 'کتاب‌های PDF و EPUB',
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'name' => 'قالب‌ها',
                'slug' => 'templates',
                'icon' => 'fa-palette',
                'color' => 'orange',
                'description' => 'قالب‌های وب و گرافیک',
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
        ]);
    }
}

