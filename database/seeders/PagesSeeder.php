<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagesSeeder extends Seeder
{
    /**
     * Editorial content for the static storefront pages.
     *
     * The `content` payload is structured (not HTML) so the React storefront can
     * render every section with the design system and an admin can edit each
     * field from the dashboard.
     */
    public function run(): void
    {
        $now = now();

        DB::table('pages')->updateOrInsert(
            ['slug' => 'about'],
            [
                'title' => 'درباره ما',
                'subtitle' => 'داستان شکل‌گیری و مسیر رشد فایل‌استور',
                'status' => 'published',
                'content' => json_encode($this->aboutContent(), JSON_UNESCAPED_UNICODE),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        );
    }

    private function aboutContent(): array
    {
        return [
            'story' => [
                'heading' => 'آغاز یک داستان موفق',
                'paragraphs' => [
                    'در سال ۱۳۹۹، آرش و امیرحسین با یک ایده‌ی ساده اما قدرتمند تصمیم گرفتند فروشگاه آنلاین خود را در قالب فروش پی‌دی‌اف کتاب راه‌اندازی کنند. آن‌ها باور داشتند که دانش باید به راحتی در دسترس همه قرار گیرد و کتاب‌های دیجیتال می‌توانند این امکان را فراهم کنند.',
                ],
            ],
            'founders' => [
                'heading' => 'بنیان‌گذاران',
                'items' => [
                    [
                        'name' => 'آرش',
                        'role' => 'مؤسس و مدیر فنی',
                        'bio' => 'آرش با تخصص در زمینه‌ی فناوری اطلاعات و برنامه‌نویسی، مسئولیت توسعه‌ی پلتفرم و اطمینان از عملکرد بهینه‌ی سایت را بر عهده دارد.',
                    ],
                    [
                        'name' => 'امیرحسین',
                        'role' => 'مؤسس و مدیر محتوا',
                        'bio' => 'امیرحسین با دانش گسترده در حوزه‌ی برنامه‌نویسی و بازاریابی، مسئولیت انتخاب کتاب‌ها و ارتباط با ناشران و نویسندگان را بر عهده دارد.',
                    ],
                ],
            ],
            'responsibilities' => [
                'heading' => 'مسئولیت‌های مشترک',
                'items' => [
                    [
                        'icon' => 'strategy',
                        'title' => 'توسعه استراتژی کسب‌وکار',
                        'description' => 'هر دو مؤسس در تدوین و اجرای استراتژی‌های کلان کسب‌وکار نقش فعالی دارند.',
                    ],
                    [
                        'icon' => 'team',
                        'title' => 'مدیریت تیم',
                        'description' => 'رهبری و هدایت تیم‌های فنی و محتوایی به صورت مشترک انجام می‌شود.',
                    ],
                    [
                        'icon' => 'growth',
                        'title' => 'برنامه‌ریزی رشد',
                        'description' => 'تعیین اهداف کوتاه‌مدت و بلندمدت و نظارت بر تحقق آن‌ها.',
                    ],
                    [
                        'icon' => 'innovation',
                        'title' => 'نوآوری و توسعه',
                        'description' => 'هر دو در شناسایی فرصت‌های جدید و توسعه‌ی خدمات نوین مشارکت دارند.',
                    ],
                ],
            ],
            'timeline' => [
                'heading' => 'مسیر رشد ما',
                'items' => [
                    [
                        'year' => '۱۳۹۹',
                        'title' => 'شروع فعالیت',
                        'description' => 'آغاز فعالیت با مجموعه‌ای کوچک از کتاب‌های پی‌دی‌اف.',
                    ],
                    [
                        'year' => '۱۴۰۰',
                        'title' => 'افزایش محبوبیت',
                        'description' => 'رشد چشمگیر کاربران و اضافه شدن کتاب‌های جدید به مجموعه.',
                    ],
                    [
                        'year' => '۱۴۰۱',
                        'title' => 'توسعه‌ی پلتفرم',
                        'description' => 'بهبود رابط کاربری و اضافه شدن قابلیت‌های جدید.',
                    ],
                    [
                        'year' => '۱۴۰۲',
                        'title' => 'دستاوردهای جدید',
                        'description' => 'همکاری با ناشران معتبر و ارائه‌ی بیش از ۱۰۰۰ عنوان کتاب.',
                    ],
                ],
            ],
            'mission_vision' => [
                'mission' => [
                    'icon' => 'mission',
                    'title' => 'ماموریت ما',
                    'body' => 'ما باور داریم که دسترسی به دانش باید برای همه آسان و مقرون به صرفه باشد. ماموریت ما ارائه‌ی کتاب‌های دیجیتال با کیفیت بالا، با قیمت مناسب و ایجاد تجربه‌ای لذت‌بخش برای مطالعه است.',
                ],
                'vision' => [
                    'icon' => 'vision',
                    'title' => 'چشم‌انداز ما',
                    'body' => 'چشم‌انداز ما تبدیل شدن به بزرگ‌ترین پلتفرم فروش کتاب‌های دیجیتال در ایران و ارائه‌ی خدمات نوآورانه برای بهبود تجربه‌ی مطالعه‌ی دیجیتال است.',
                ],
            ],
            'stats' => [
                'heading' => 'دستاوردهای ما در یک نگاه',
                'items' => [
                    ['value' => '۳+', 'label' => 'سال تجربه'],
                    ['value' => '۱۰۰۰+', 'label' => 'عنوان کتاب'],
                    ['value' => '۵۰ هزار+', 'label' => 'کاربر راضی'],
                    ['value' => '۹۸٪', 'label' => 'رضایت‌مندی'],
                ],
            ],
            'contact' => [
                'heading' => 'کد پروژه را ببین',
                'body' => 'برای دیدن کد فایل‌استور، خبر گرفتن از تغییرات یا فرستادن پیشنهاد، از لینک‌های زیر استفاده کن.',
                'links' => [
                    ['icon' => 'code', 'label' => 'مخزن فایل‌استور', 'url' => 'https://github.com/Arash-abraham/File-Store'],
                    ['icon' => 'github', 'label' => 'پروفایل آرش', 'url' => 'https://github.com/Arash-abraham'],
                    ['icon' => 'faq', 'label' => 'پرسش‌های پرتکرار', 'url' => '/faq'],
                ],
            ],
        ];
    }
}
