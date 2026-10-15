<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $faqs = [
            ['question' => 'بعد از خرید چطور کتاب را دریافت کنم؟', 'answer' => 'پس از تأیید پرداخت، فایل‌های خریداری‌شده در بخش «کتابخانه‌ی من» حساب کاربری شما قرار می‌گیرند.'],
            ['question' => 'آیا می‌توانم قبل از خرید بخشی از کتاب را ببینم؟', 'answer' => 'اطلاعات، ویژگی‌ها و مشخصات هر عنوان در صفحه‌ی محصول آمده است. در صورت نیاز می‌توانید از بخش پشتیبانی سؤال خود را مطرح کنید.'],
            ['question' => 'پرداخت با کیف پول چطور انجام می‌شود؟', 'answer' => 'در مرحله‌ی پرداخت می‌توانید از موجودی کیف پول استفاده کنید. اگر موجودی کافی باشد، سفارش بدون خروج از سایت ثبت می‌شود.'],
            ['question' => 'اگر لینک دانلود کار نکرد چه کنم؟', 'answer' => 'از بخش پشتیبانی حساب خود یک درخواست ثبت کنید و شماره‌ی سفارش را بنویسید تا موضوع بررسی شود.'],
        ];

        foreach ($faqs as $faq) {
            DB::table('faqs')->updateOrInsert(['question' => $faq['question']], $faq + [
                'status' => 'published',
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]);
        }
    }
}
