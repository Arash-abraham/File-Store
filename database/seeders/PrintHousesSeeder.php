<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrintHousesSeeder extends Seeder
{
    /**
     * Partner printing houses used by the "چاپ کتاب" section.
     * Prices are per copy in Toman.
     */
    public function run(): void
    {
        $now = now();

        $houses = [
            [
                'name' => 'چاپخانه‌ی نقش جهان',
                'slug' => 'naqsh-jahan',
                'city' => 'تهران',
                'province' => 'تهران',
                'description' => 'چاپ و صحافی کتاب با کاغذ تحریر و گلاسه؛ تیراژ کم و سفارش تک‌نسخه هم می‌پذیریم.',
                'capabilities' => ['کاغذ تحریر', 'گلاسه', 'صحافی سخت', 'چاپ رنگی'],
                'phone' => '021-55667788',
                'email' => 'order@naqshjahan.example',
                'website' => 'https://naqshjahan.example',
                'unit_price' => 245000,
                'hardcover_surcharge' => 95000,
                'delivery_fee' => 65000,
                'delivery_days' => 5,
                'min_quantity' => 1,
                'max_quantity' => 10,
                'rating' => 4.8,
            ],
            [
                'name' => 'مجموعه‌ی چاپ آفتاب',
                'slug' => 'aftab-print',
                'city' => 'اصفهان',
                'province' => 'اصفهان',
                'description' => 'تخصص ما چاپ رنگی و جلد سخت برای کتاب‌های هنری و دانشگاهی است.',
                'capabilities' => ['چاپ رنگی', 'صحافی سخت', 'گلاسه', 'ارسال سراسری'],
                'phone' => '031-32233445',
                'email' => 'hello@aftabprint.example',
                'website' => null,
                'unit_price' => 268000,
                'hardcover_surcharge' => 78000,
                'delivery_fee' => 72000,
                'delivery_days' => 6,
                'min_quantity' => 1,
                'max_quantity' => 15,
                'rating' => 4.6,
            ],
            [
                'name' => 'چاپ و صحافی پارس',
                'slug' => 'pars-print',
                'city' => 'مشهد',
                'province' => 'خراسان رضوی',
                'description' => 'قیمت مناسب برای سفارش‌های چندنسخه‌ای با کاغذ کرافت و صحافی فنر.',
                'capabilities' => ['کاغذ کرافت', 'صحافی فنر', 'تیراژ بالا', 'قیمت اقتصادی'],
                'phone' => '051-37654321',
                'email' => 'salam@parsprint.example',
                'website' => null,
                'unit_price' => 198000,
                'hardcover_surcharge' => 88000,
                'delivery_fee' => 58000,
                'delivery_days' => 7,
                'min_quantity' => 2,
                'max_quantity' => 20,
                'rating' => 4.4,
            ],
            [
                'name' => 'چاپخانه‌ی مدرن شیراز',
                'slug' => 'modern-shiraz',
                'city' => 'شیراز',
                'province' => 'فارس',
                'description' => 'چاپ سریع کتاب با تحویل یک‌هفته‌ای و بسته‌بندی شکیل برای هدیه.',
                'capabilities' => ['تحویل سریع', 'بسته‌بندی هدیه', 'کاغذ تحریر', 'چاپ رنگی'],
                'phone' => '071-36221100',
                'email' => 'info@modernshiraz.example',
                'website' => 'https://modernshiraz.example',
                'unit_price' => 232000,
                'hardcover_surcharge' => 84000,
                'delivery_fee' => 60000,
                'delivery_days' => 4,
                'min_quantity' => 1,
                'max_quantity' => 12,
                'rating' => 4.9,
            ],
            [
                'name' => 'چاپخانه‌ی تبریز آرا',
                'slug' => 'tabriz-ara',
                'city' => 'تبریز',
                'province' => 'آذربایجان شرقی',
                'description' => 'چاپ تخصصی کتاب‌های فنی و مهندسی با نقشه‌ها و جدول‌های تخصصی.',
                'capabilities' => ['کتاب فنی', 'کاغذ گلاسه', 'صحافی سخت', 'تیراژ بالا'],
                'phone' => '041-33344556',
                'email' => 'base@tabrizara.example',
                'website' => null,
                'unit_price' => 255000,
                'hardcover_surcharge' => 90000,
                'delivery_fee' => 68000,
                'delivery_days' => 8,
                'min_quantity' => 1,
                'max_quantity' => 20,
                'rating' => 4.5,
            ],
        ];

        foreach ($houses as $house) {
            DB::table('print_houses')->updateOrInsert(
                ['slug' => $house['slug']],
                array_merge($house, [
                    'capabilities' => json_encode($house['capabilities'], JSON_UNESCAPED_UNICODE),
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]),
            );
        }
    }
}
