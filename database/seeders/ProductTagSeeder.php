<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTagSeeder extends Seeder
{
    public function run(): void
    {
        $relationships = [
            'linux-for-hackers' => ['coding', 'linux', 'bestseller'],
            'http-definitive-guide' => ['coding', 'networking'],
            'nginx-cookbook' => ['coding', 'networking'],
            'nader-shah-afshar' => ['history'],
        ];
        $now = now();

        foreach ($relationships as $productSlug => $tagSlugs) {
            $productId = DB::table('products')->where('slug', $productSlug)->value('id');
            if (!$productId) {
                continue;
            }
            foreach ($tagSlugs as $tagSlug) {
                $tagId = DB::table('tags')->where('slug', $tagSlug)->value('id');
                if ($tagId) {
                    DB::table('product_tag')->updateOrInsert(
                        ['product_id' => $productId, 'tag_id' => $tagId],
                        ['created_at' => $now, 'updated_at' => $now],
                    );
                }
            }
        }
    }
}
