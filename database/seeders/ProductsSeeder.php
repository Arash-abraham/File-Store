<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Ensure categories exist with provided slugs
        $softwareCategoryId = DB::table('categories')->where('slug', 'software')->value('id');
        $coursesCategoryId = DB::table('categories')->where('slug', 'courses')->value('id');
        $ebooksCategoryId = DB::table('categories')->where('slug', 'ebooks')->value('id');

        DB::table('products')->insert([
            [
                'title' => 'Adobe Photoshop 2024',
                'slug' => 'adobe-photoshop-2024',
                'type' => 'software',
                'category_id' => $softwareCategoryId,
                'status' => 'active',
                'availability' => true,
                'original_price' => 3000000,
                'price' => 2500000,
                'rating' => 4.8,
                'reviews_count' => 126,
                'image_url' => 'https://images.pexels.com/photos/4348401/pexels-photo-4348401.jpeg?auto=compress&cs=tinysrgb&w=400',
                'description' => 'نرم‌افزار Adobe Photoshop 2024 نسخه کامل',
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'title' => 'دوره کامل آموزش React JS',
                'slug' => Str::slug('دوره کامل آموزش React JS', '-'),
                'type' => 'course',
                'category_id' => $coursesCategoryId,
                'status' => 'active',
                'availability' => true,
                'original_price' => 500000,
                'price' => 450000,
                'rating' => 4.6,
                'reviews_count' => 85,
                'image_url' => 'https://images.pexels.com/photos/11035380/pexels-photo-11035380.jpeg?auto=compress&cs=tinysrgb&w=400',
                'description' => 'دوره پروژه‌محور React JS',
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'title' => 'AutoCAD 2024',
                'slug' => 'autocad-2024',
                'type' => 'software',
                'category_id' => $softwareCategoryId,
                'status' => 'inactive',
                'availability' => false,
                'original_price' => 3000000,
                'price' => null,
                'rating' => 4.2,
                'reviews_count' => 50,
                'image_url' => 'https://images.pexels.com/photos/4348401/pexels-photo-4348401.jpeg?auto=compress&cs=tinysrgb&w=400',
                'description' => 'نرم‌افزار AutoCAD 2024',
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
        ]);
    }
}

