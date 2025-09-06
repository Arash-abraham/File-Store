<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTagSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $photoshopId = DB::table('products')->where('slug', 'adobe-photoshop-2024')->value('id');
        $reactCourseId = DB::table('products')->where('slug', 'dwrh-kaml-amwzsh-react-js')->value('id');
        $adobeTagId = DB::table('tags')->where('slug', 'adobe')->value('id');
        $microsoftTagId = DB::table('tags')->where('slug', 'microsoft')->value('id');

        $inserts = [];
        if ($photoshopId && $adobeTagId) {
            $inserts[] = ['product_id' => $photoshopId, 'tag_id' => $adobeTagId, 'created_at' => $now, 'updated_at' => $now];
        }
        if ($reactCourseId && $microsoftTagId) {
            $inserts[] = ['product_id' => $reactCourseId, 'tag_id' => $microsoftTagId, 'created_at' => $now, 'updated_at' => $now];
        }

        if (!empty($inserts)) {
            DB::table('product_tag')->insert($inserts);
        }
    }
}

