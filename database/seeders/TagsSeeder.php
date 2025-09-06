<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('tags')->insert([
            ['name' => 'Adobe', 'slug' => 'adobe', 'color' => 'red', 'description' => null, 'created_at' => $now, 'updated_at' => $now, 'deleted_at' => null],
            ['name' => 'Microsoft', 'slug' => 'microsoft', 'color' => 'blue', 'description' => null, 'created_at' => $now, 'updated_at' => $now, 'deleted_at' => null],
            ['name' => 'Autodesk', 'slug' => 'autodesk', 'color' => 'green', 'description' => null, 'created_at' => $now, 'updated_at' => $now, 'deleted_at' => null],
            ['name' => 'JetBrains', 'slug' => 'jetbrains', 'color' => 'purple', 'description' => null, 'created_at' => $now, 'updated_at' => $now, 'deleted_at' => null],
        ]);
    }
}

