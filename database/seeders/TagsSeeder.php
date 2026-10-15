<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        foreach ([
            ['name' => 'برنامه‌نویسی', 'slug' => 'coding'],
            ['name' => 'لینوکس', 'slug' => 'linux'],
            ['name' => 'شبکه', 'slug' => 'networking'],
            ['name' => 'تاریخ', 'slug' => 'history'],
            ['name' => 'پرفروش', 'slug' => 'bestseller'],
        ] as $tag) {
            DB::table('tags')->updateOrInsert(['slug' => $tag['slug']], $tag + [
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]);
        }
    }
}
