<?php

namespace Database\Seeders;

use App\Models\PrintHouse;
use Illuminate\Database\Seeder;

class PrintHousesSeeder extends Seeder
{
    /**
     * Partner printing houses used by the "چاپ کتاب" section.
     * Prices are per copy in Toman.
     */
    public function run(): void
    {
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
                'address' => 'تهران، خیابان کارگر شمالی، پلاک ۲۱',
                'website' => 'https://naqshjahan.example',
                'unit_price' => 245000,
                'hardcover_surcharge' => 95000,
                'delivery_fee' => 65000,
                'delivery_days' => 5,
                'min_quantity' => 1,
                'max_quantity' => 10,
                'rating' => 4.8,
                'is_featured' => true,
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
                'address' => 'اصفهان، خیابان چهارباغ بالا، ساختمان چاپ',
                'website' => null,
                'unit_price' => 268000,
                'hardcover_surcharge' => 78000,
                'delivery_fee' => 72000,
                'delivery_days' => 6,
                'min_quantity' => 1,
                'max_quantity' => 15,
                'rating' => 4.6,
                'is_featured' => true,
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
                'address' => 'مشهد، بلوار سجاد، نبش خیابان بهار',
                'website' => null,
                'unit_price' => 198000,
                'hardcover_surcharge' => 88000,
                'delivery_fee' => 58000,
                'delivery_days' => 7,
                'min_quantity' => 2,
                'max_quantity' => 20,
                'rating' => 4.4,
                'is_featured' => false,
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
                'address' => 'شیراز، بلوار زند، مجتمع چاپ و نشر',
                'website' => 'https://modernshiraz.example',
                'unit_price' => 232000,
                'hardcover_surcharge' => 84000,
                'delivery_fee' => 60000,
                'delivery_days' => 4,
                'min_quantity' => 1,
                'max_quantity' => 12,
                'rating' => 4.9,
                'is_featured' => true,
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
                'address' => 'تبریز، خیابان آزادی، ساختمان آرا',
                'website' => null,
                'unit_price' => 255000,
                'hardcover_surcharge' => 90000,
                'delivery_fee' => 68000,
                'delivery_days' => 8,
                'min_quantity' => 1,
                'max_quantity' => 20,
                'rating' => 4.5,
                'is_featured' => false,
            ],
            [
                'name' => 'چاپخانه‌ی کویر یزد',
                'slug' => 'kavir-yazd',
                'city' => 'یزد',
                'province' => 'یزد',
                'description' => 'صحافی کلاسیک با کاغذ کرافت و جلد نرم؛ مناسب کتاب‌های ادبی و شعر.',
                'capabilities' => ['کاغذ کرافت', 'صحافی نرم', 'بسته‌بندی هدیه', 'ارسال سراسری'],
                'phone' => '035-32233445',
                'email' => 'hello@kavirprint.example',
                'address' => 'یزد، خیابان کاشانی، کوچه‌ی چاپ',
                'website' => null,
                'unit_price' => 215000,
                'hardcover_surcharge' => 86000,
                'delivery_fee' => 55000,
                'delivery_days' => 6,
                'min_quantity' => 1,
                'max_quantity' => 15,
                'rating' => 4.3,
                'is_featured' => false,
            ],
        ];

        foreach ($houses as $house) {
            PrintHouse::query()->updateOrCreate(
                ['slug' => $house['slug']],
                array_merge($house, ['status' => 'active']),
            );
        }
    }
}
