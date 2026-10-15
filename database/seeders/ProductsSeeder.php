<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $categories = DB::table('categories')->pluck('id', 'slug');
        $products = [
            [
                'title' => 'لینوکس برای هکرها',
                'slug' => 'linux-for-hackers',
                'category_id' => $categories['programming'] ?? null,
                'original_price' => 890000,
                'price' => 690000,
                'image' => 'products/linux-basics.svg',
                'description' => 'راهنمایی خوش‌خوان و کاربردی برای کار با لینوکس، خط فرمان و ابزارهای ضروری امنیت.',
                'features' => ['آشنایی با خط فرمان لینوکس', 'مدیریت فایل و دسترسی‌ها', 'مناسب برای شروع مسیر امنیت'],
                'status' => 'active',
                'availability' => true,
            ],
            [
                'title' => 'HTTP؛ راهنمای قطعی وب',
                'slug' => 'http-definitive-guide',
                'category_id' => $categories['programming'] ?? null,
                'original_price' => 740000,
                'price' => 590000,
                'image' => 'products/McDl6J1erH5209ZDd31XZmq7q6xADgn9QHIIxqTU.png',
                'description' => 'از پروتکل HTTP تا معماری وب؛ مرجعی برای توسعه‌دهندگان و علاقه‌مندان شبکه.',
                'features' => ['مبانی HTTP و ارتباطات وب', 'مثال‌های عملی و قابل پیگیری', 'مناسب برای توسعه‌دهندگان وب'],
                'status' => 'active',
                'availability' => true,
            ],
            [
                'title' => 'آشپزخانه‌ی NGINX',
                'slug' => 'nginx-cookbook',
                'category_id' => $categories['programming'] ?? null,
                'original_price' => 980000,
                'price' => 790000,
                'image' => 'products/PaOudnv2sC89NiTqgvaakKlgdiKD05IxBnxUxnPL.png',
                'description' => 'دستورالعمل‌های عملی برای پیکربندی NGINX، بهینه‌سازی عملکرد و توزیع بار.',
                'features' => ['پیکربندی سرور وب', 'بهینه‌سازی و کش', 'تعادل بار و امنیت'],
                'status' => 'active',
                'availability' => true,
            ],
            [
                'title' => 'نادرشاه افشار',
                'slug' => 'nader-shah-afshar',
                'category_id' => $categories['history-literature'] ?? null,
                'original_price' => 420000,
                'price' => 350000,
                'image' => 'products/xyU5n7zAVBYRyBDjJOFOIz4A3YYR5N5kvsCSMYMa.png',
                'description' => 'روایتی خواندنی از زندگی و زمانه‌ی یکی از تأثیرگذارترین چهره‌های تاریخ ایران.',
                'features' => ['روایت تاریخی مستند', 'تصویرسازی و صفحه‌آرایی باکیفیت', 'نسخه‌ی دیجیتال قابل دریافت'],
                'status' => 'active',
                'availability' => true,
            ],
            [
                'title' => 'ریاضیات جامع تجربی',
                'slug' => 'comprehensive-math',
                'category_id' => $categories['science'] ?? null,
                'original_price' => 520000,
                'price' => 430000,
                'image' => 'products/AKavkAP8JhKtVQSnaMnY9pSTyVrLL3ScmL59tNFL.png',
                'description' => 'مرور مفهومی و تمرین‌محور مباحث منتخب ریاضی برای مطالعه‌ی هدفمند.',
                'features' => ['درس‌نامه‌ی طبقه‌بندی‌شده', 'تمرین‌های متنوع', 'مناسب مرور و جمع‌بندی'],
                'status' => 'active',
                'availability' => true,
            ],
        ];

        foreach ($products as $product) {
            $slug = $product['slug'];
            $image = $product['image'];
            $features = $product['features'];
            unset($product['slug'], $product['image'], $product['features']);

            DB::table('products')->updateOrInsert(['slug' => $slug], $product + [
                'slug' => $slug,
                'image_urls' => json_encode([$image], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'key_features' => json_encode($features, JSON_UNESCAPED_UNICODE),
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]);
        }
    }
}
