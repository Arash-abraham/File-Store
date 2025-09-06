<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductFilesSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $photoshopId = DB::table('products')->where('slug', 'adobe-photoshop-2024')->value('id');
        $reactCourseId = DB::table('products')->where('slug', 'dwrh-kaml-amwzsh-react-js')->value('id');

        if ($photoshopId) {
            DB::table('product_files')->insert([
                [
                    'product_id' => $photoshopId,
                    'name' => 'Setup Files',
                    'path' => '/storage/products/photoshop/setup.zip',
                    'size_label' => '2GB',
                    'type' => 'zip',
                    'sort_order' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }

        if ($reactCourseId) {
            DB::table('product_files')->insert([
                [
                    'product_id' => $reactCourseId,
                    'name' => 'Video Pack 1',
                    'path' => '/storage/products/react/video-pack-1.zip',
                    'size_label' => '1.2GB',
                    'type' => 'zip',
                    'sort_order' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }
}

