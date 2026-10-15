# فایل‌استور — فروشگاه کتاب دیجیتال

فایل‌استور یک فروشگاه فارسی و راست‌چین برای کتاب‌های دیجیتال است. رابط کاربری با **React، TypeScript و Vite** به‌صورت یک برنامه‌ی مستقل ساخته شده و **Laravel 12 فقط API** و منطق سمت سرور را ارائه می‌کند؛ صفحات فروشگاه با Blade یا Inertia رندر نمی‌شوند.

## فناوری‌ها

- **API:** PHP 8.2+، Laravel 12، Laravel Sanctum، MySQL/MariaDB
- **رابط کاربری:** React 19، TypeScript، Vite 7، React Router، TanStack Query
- **رابط:** فارسی، RTL، واکنش‌گرا، فونت Vazirmatn
- **پرداخت:** زرین‌پال (اختیاری برای محیط محلی)

## امکانات

- خانه، دسته‌بندی و جست‌وجوی کتاب‌ها، جزئیات محصول و دیدگاه‌ها
- سبد خرید احراز هویت‌شده، کد تخفیف، پرداخت با کیف پول یا زرین‌پال
- حساب کاربری، سفارش‌ها، کتابخانه و دانلود امن، تیکت پشتیبانی و ویرایش اطلاعات
- ثبت‌نام، ورود نشست‌محور، خروج، بازیابی و تغییر گذرواژه
- پنل مدیریت برای نمای کلی، مدیریت محصولات، وضعیت تیکت‌ها و تأیید/رد دیدگاه‌ها
- حالت نمایشی فرانت‌اند برای بازبینی رابط بدون اتصال به API

## پیش‌نیازها

- PHP 8.2 یا جدیدتر و افزونه‌های موردنیاز Laravel
- Composer 2
- MySQL 8 / MariaDB یا یک دیتابیس سازگار با Laravel
- Node.js سازگار با Vite 7 (نسخه‌ی 20.19+ یا 22.12+) و npm

## راه‌اندازی محلی

### ۱. نصب وابستگی‌های Laravel و تنظیم محیط

از ریشه‌ی مخزن:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

در `.env` اتصال دیتابیس را تنظیم کنید. دیتابیس را پیش از اجرای migration بسازید. در صورت استفاده از MySQL، نمونه‌ی تنظیمات زیر را تکمیل کنید:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=file_store
DB_USERNAME=root
DB_PASSWORD=
```

برای توسعه‌ی محلی، مقادیر مربوط به SPA/API نیز در `.env.example` آمده‌اند. در صورت تغییر پورت‌ها، هم‌زمان این موارد را به‌روزرسانی کنید:

```env
APP_URL=http://127.0.0.1:8000
FRONTEND_URL=http://localhost:5173
CORS_ALLOWED_ORIGINS=http://localhost:5173,http://127.0.0.1:5173
SANCTUM_STATEFUL_DOMAINS=localhost:5173,127.0.0.1:5173,localhost:8000,127.0.0.1:8000
SESSION_DOMAIN=null
```

### ۲. ایجاد schema و داده‌های نمونه

```bash
php artisan migrate --seed
php artisan storage:link
```

Seederها برای راه‌اندازی و نمایش پروژه داده‌های مرتبط را می‌سازند: حساب مدیر و کاربر، دسته‌ها، برچسب‌ها، محصولات، فایل‌های نمونه در دیسک خصوصی، کدهای تخفیف، تنظیمات، پرسش‌های متداول، منو، کیف پول، سبد، سفارش‌ها، پرداخت نمونه، تیکت و دیدگاه. اجرای دوباره‌ی Seederها برای رکوردهای نمونه عمدتاً idempotent است؛ برای بازسازی کامل دیتابیس از `migrate:fresh --seed` استفاده کنید، با این هشدار که **تمام داده‌های فعلی دیتابیس حذف می‌شود**.

### ۳. اجرای API

در یک ترمینال:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Laravel در ریشه‌ی `/` یک پاسخ JSON معرفی سرویس می‌دهد. مسیر سلامت API برابر `/api/v1/health` است. رابط کاربری از routeهای Blade استفاده نمی‌کند.

### ۴. اجرای React

در ترمینال دوم:

```bash
cd frontend
cp .env.example .env.local
npm install
npm run dev
```

آدرس پیش‌فرض رابط کاربری `http://localhost:5173` است. Vite درخواست‌های `/api`، `/sanctum`، `/products` و `/storage` را به Laravel روی `http://127.0.0.1:8000` پروکسی می‌کند. مقصد پروکسی را می‌توانید در `frontend/.env.local` با `API_PROXY_TARGET` تغییر دهید.

### ورودهای نمونه‌ی Seeder

| نقش | ایمیل | گذرواژه‌ی پیش‌فرض |
|---|---|---|
| کاربر | `user@example.com` | `password` |
| مدیر | `admin@example.com` | `password` |

این اعتبارنامه‌ها فقط برای توسعه و دمو هستند. در محیط واقعی، گذرواژه‌ها را با متغیرهای `DEMO_USER_PASSWORD` و `DEMO_ADMIN_PASSWORD` عوض کنید یا داده‌های حساب نمونه را seed نکنید.

## حالت نمایشی فرانت‌اند

برای مشاهده‌ی صفحات و جریان سبد خرید بدون اجرای Laravel، مقدار زیر را در `frontend/.env.local` بگذارید و Vite را دوباره اجرا کنید:

```env
VITE_DEMO_MODE=true
```

در این حالت کاتالوگ و حساب‌ها از داده‌های نمونه‌ی داخل فرانت‌اند استفاده می‌کنند و سبد در `localStorage` می‌ماند. ورود نمایشی با همان ایمیل‌های نمونه و گذرواژه‌ی `password` انجام می‌شود. **پرداخت و ثبت داده در حالت نمایشی واقعی نیستند.** برای کار با API مقدار را روی `false` بگذارید.

## تنظیم فرانت‌اند و API در تولید

برای ساخت نسخه‌ی static:

```bash
cd frontend
npm ci
npm run build
```

خروجی در `frontend/dist` قرار می‌گیرد. آن را روی میزبانی static جداگانه منتشر کنید و `VITE_API_BASE_URL` را پیش از build به آدرس API تنظیم کنید، برای مثال:

```env
VITE_API_BASE_URL=https://api.example.com/api/v1
VITE_DEMO_MODE=false
```

برای احراز هویت نشست‌محور Sanctum، انتشار فرانت‌اند و API روی زیردامنه‌های یک دامنه‌ی اصلی (مثلاً `app.example.com` و `api.example.com`) پیشنهاد می‌شود. دامنه‌ی فرانت‌اند را در `CORS_ALLOWED_ORIGINS`، دامنه‌های میزبان را در `SANCTUM_STATEFUL_DOMAINS` و دامنه‌ی اصلی cookie را در `SESSION_DOMAIN` وارد کنید؛ در HTTPS مقدار `SESSION_SECURE_COOKIE=true` را تنظیم کنید. دامنه‌ی `FRONTEND_URL` نیز مقصد بازگشت پرداخت و پیوندهای بازیابی گذرواژه است.

## API

همه‌ی endpointهای نسخه‌ی اول زیر پیشوند `/api/v1` هستند. پاسخ‌های موفق معمولاً در کلید `data` قرار می‌گیرند.

| گروه | مسیرها |
|---|---|
| عمومی | `GET /health`, `GET /home`, `GET /products`, `GET /products/{slug}`, `GET /categories`, `GET /faqs`, `GET /navigation`, `GET /settings` |
| احراز هویت | `POST /auth/register`, `POST /auth/login`, `POST /auth/forgot-password`, `POST /auth/reset-password`, `GET /auth/me`, `POST /auth/logout` |
| سبد و پرداخت | `GET /cart`, `POST /cart/items`, `PATCH /cart/items/{id}`, `DELETE /cart/items/{id}`, `POST /cart/coupon`, `POST /checkout` |
| حساب | `GET/PUT /account/profile`, `GET /account/overview`, `GET /account/orders`, `GET /account/downloads`, `GET/POST /account/tickets` |
| کیف پول و دیدگاه | `GET /wallet`, `POST /wallet/deposits`, `POST /reviews`, مسیرهای helpful و report دیدگاه |
| مدیریت | `/admin/dashboard`, محصولات، دسته‌ها، برچسب‌ها، کوپن‌ها، FAQ، منو، تیکت‌ها، دیدگاه‌ها، پرداخت‌ها و تنظیمات |

مسیرهای مدیریت به نشست معتبر با نقش `admin` نیاز دارند. فایل‌های خریداری‌شده از endpoint حساب کاربر و پس از بررسی مالکیت سفارش تحویل داده می‌شوند؛ برای استفاده از فایل‌های واقعی باید فایل محصول را در دیسک خصوصی بارگذاری کرد.

در نصب‌های دارای Scramble، مستندات API از مسیر `/docs/api` در دسترس است.

## پیکربندی سرویس‌ها

### ایمیل

در توسعه می‌توانید `MAIL_MAILER=log` نگه دارید؛ پیام‌های ایمیلی در لاگ Laravel ثبت می‌شوند. برای ایمیل واقعی، SMTP را در `.env` تنظیم کنید. ارسال پیوند بازیابی گذرواژه و تأیید ایمیل به ایمیل قابل‌دسترسی نیاز دارد.

### زرین‌پال

برای پرداخت واقعی، `ZARINPAL_MERCHANT_ID` را تنظیم کنید و مقادیر sandbox/production را مطابق حساب درگاه قرار دهید. بدون Merchant ID، endpoint پرداخت پاسخ پیکربندی‌نشده می‌دهد؛ اجرای دمو با درگاه واقعی لازم نیست.

### فایل‌ها

تصاویر عمومی محصولات از `public/products`/دیسک public ارائه می‌شوند. فایل‌های قابل دانلود در دیسک خصوصی ذخیره شده و فقط پس از بررسی سفارش پرداخت‌شده قابل دریافت هستند. Seeder فایل‌های کوچک متنیِ نمونه را برای آزمایش مسیر دانلود ایجاد می‌کند؛ آن‌ها کتاب واقعی نیستند.

## آزمون و بررسی

فرانت‌اند:

```bash
cd frontend
npm run typecheck
npm run build
```

Laravel (در محیطی که PHP و وابستگی‌ها نصب باشند):

```bash
php artisan test
```

برای بررسی routeها نیز می‌توان از `php artisan route:list --path=api/v1` استفاده کرد.

## ساختار مهم

```text
app/Http/Controllers/Api/V1/    کنترلرهای نسخه‌ی اول API
app/Http/Resources/Api/V1/      منابع JSON محصولات و دسته‌ها
routes/api.php                  endpointهای نسخه‌ی اول
routes/web.php                  پاسخ JSON معرفی سرویس؛ بدون UI
database/seeders/               داده‌های پایه و نمونه
frontend/src/                   برنامه‌ی مستقل React/TypeScript
frontend/public/images/books/   جلدهای محلی برای حالت دمو
```

## یادداشت امنیتی

- `VITE_DEMO_MODE` را در تولید روشن نکنید.
- گذرواژه‌های نمونه، Merchant ID آزمایشی و APP_DEBUG را در محیط عمومی استفاده نکنید.
- برای SPA و API، تنظیم CORS، دامنه‌ی Sanctum و کوکی session باید با دامنه و HTTPS واقعی هماهنگ باشد.
- فایل‌های قابل خرید را در دیسک private نگه دارید؛ از لینک عمومی مستقیم برای محتوای خریداری‌شده استفاده نکنید.
