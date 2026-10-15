<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductFilesSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $sampleFiles = [
            ['slug' => 'linux-for-hackers', 'name' => 'راهنمای شروع مطالعه', 'path' => 'demo/linux-guide.txt'],
            ['slug' => 'http-definitive-guide', 'name' => 'نمونه فصل اول', 'path' => 'demo/http-sample.txt'],
            ['slug' => 'nginx-cookbook', 'name' => 'فایل همراه کتاب', 'path' => 'demo/nginx-notes.txt'],
        ];

        foreach ($sampleFiles as $index => $sample) {
            $productId = DB::table('products')->where('slug', $sample['slug'])->value('id');
            if (!$productId) {
                continue;
            }

            Storage::disk('private')->put($sample['path'], "فایل نمونه‌ی نمایشی فایل‌استور\nاین فایل برای آزمایش مسیر دانلود در محیط توسعه ساخته شده است.\nمحصول: {$sample['slug']}\n");
            DB::table('product_files')->updateOrInsert(
                ['product_id' => $productId, 'name' => $sample['name']],
                [
                    'path' => $sample['path'],
                    'size_label' => 'کمتر از ۱ کیلوبایت',
                    'type' => 'txt',
                    'sort_order' => $index + 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ],
            );
        }
    }
}
