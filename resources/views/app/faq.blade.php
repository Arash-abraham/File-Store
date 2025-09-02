@extends('app.layouts.master')

@section('title','محصولات')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/products.css')}}">
@endsection

@section('content')
    <div id="main-content">
        <!-- Cart Modal and Blur Overlay -->
        <div id="blur-overlay"></div>
        <div id="cart-modal" class="w-80 bg-gray-800 text-white rounded-lg shadow-lg p-4 hidden z-50">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-bold">سبد خرید</h2>
                <button id="close-cart" class="text-gray-400 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="cart-content" class="max-h-64 overflow-y-auto mb-4">
                <!-- محتوای سبد خرید اینجا رندر می‌شود -->
            </div>
            <div id="cart-actions" class="mt-4"></div>
        </div>

        <!-- Breadcrumb -->
        <section class="bg-white py-4">
            <div class="container mx-auto px-4">
                <nav class="text-sm text-gray-600">
                    <ol class="list-none p-0 inline-flex">
                        <li class="flex items-center">
                            <a href="index.html" class="hover:text-blue-600">خانه</a>
                            <i class="fas fa-chevron-left mx-2"></i>
                        </li>
                        <li class="text-gray-500">سوالات متداول</li>
                    </ol>
                </nav>
            </div>
        </section>

        <!-- FAQ Header -->
        <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl font-bold mb-4">سوالات متداول</h1>
                <p class="text-xl opacity-90">پاسخ سوالات رایج کاربران</p>
            </div>
        </section>

        <!-- FAQ Content -->
        <section class="pb-12">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <div id="faqContent">
                        <!-- خرید و پرداخت -->
                        <div id="purchase" class="faq-category">
                            <div class="space-y-4">
                                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                                    <button class="w-full p-6 text-right hover:bg-gray-50 transition-colors faq-question">
                                        <div class="flex justify-between items-center">
                                            <h3 class="text-lg font-semibold">چگونه می‌توانم خرید کنم؟</h3>
                                            <i class="fas fa-chevron-down transition-transform"></i>
                                        </div>
                                    </button>
                                    <div class="faq-answer p-6 border-t border-gray-200 hidden">
                                        <p class="text-gray-700 leading-relaxed">
                                            برای خرید محصول، ابتدا وارد حساب کاربری خود شوید، سپس محصول مورد نظر را به سبد خرید اضافه کنید و مراحل پرداخت را تکمیل نمایید. بعد از تأیید پرداخت، لینک دانلود برای شما ارسال می‌شود.
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                                    <button class="w-full p-6 text-right hover:bg-gray-50 transition-colors faq-question">
                                        <div class="flex justify-between items-center">
                                            <h3 class="text-lg font-semibold">روش‌های پرداخت کدامند؟</h3>
                                            <i class="fas fa-chevron-down transition-transform"></i>
                                        </div>
                                    </button>
                                    <div class="faq-answer p-6 border-t border-gray-200 hidden">
                                        <p class="text-gray-700 leading-relaxed">
                                            شما می‌توانید با کارت‌های بانکی شتاب، درگاه‌های پرداخت آنلاین، کیف پول سایت و همچنین رمزارزها پرداخت کنید. تمام پرداخت‌ها با امنیت بالا انجام می‌شود.
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                                    <button class="w-full p-6 text-right hover:bg-gray-50 transition-colors faq-question">
                                        <div class="flex justify-between items-center">
                                            <h3 class="text-lg font-semibold">آیا می‌توانم اقساطی خرید کنم؟</h3>
                                            <i class="fas fa-chevron-down transition-transform"></i>
                                        </div>
                                    </button>
                                    <div class="faq-answer p-6 border-t border-gray-200 hidden">
                                        <p class="text-gray-700 leading-relaxed">
                                            در حال حاضر امکان خرید اقساطی وجود ندارد، اما برای محصولات با قیمت بالا امکان استفاده از کدهای تخفیف و پیشنهادهای ویژه فراهم شده است.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- دانلود فایل‌ها -->
                        <div id="download" class="faq-category hidden">
                            <div class="space-y-4">
                                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                                    <button class="w-full p-6 text-right hover:bg-gray-50 transition-colors faq-question">
                                        <div class="flex justify-between items-center">
                                            <h3 class="text-lg font-semibold">چگونه فایل‌ها را دانلود کنم؟</h3>
                                            <i class="fas fa-chevron-down transition-transform"></i>
                                        </div>
                                    </button>
                                    <div class="faq-answer p-6 border-t border-gray-200 hidden">
                                        <p class="text-gray-700 leading-relaxed">
                                            بعد از خرید موفق، به پنل کاربری خود مراجعه کنید. در بخش "دانلودهای من" لیست تمام فایل‌های قابل دانلود قرار دارد. روی لینک دانلود کلیک کنید تا فایل دانلود شود.
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                                    <button class="w-full p-6 text-right hover:bg-gray-50 transition-colors faq-question">
                                        <div class="flex justify-between items-center">
                                            <h3 class="text-lg font-semibold">چند بار می‌توانم دانلود کنم؟</h3>
                                            <i class="fas fa-chevron-down transition-transform"></i>
                                        </div>
                                    </button>
                                    <div class="faq-answer p-6 border-t border-gray-200 hidden">
                                        <p class="text-gray-700 leading-relaxed">
                                            شما می‌توانید تا 5 بار هر فایل را دانلود کنید. این محدودیت برای حفظ امنیت و جلوگیری از سوء استفاده اعمال می‌شود. در صورت نیاز به دانلود بیشتر، با پشتیبانی تماس بگیرید.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- حساب کاربری -->
                        <div id="account" class="faq-category hidden">
                            <div class="space-y-4">
                                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                                    <button class="w-full p-6 text-right hover:bg-gray-50 transition-colors faq-question">
                                        <div class="flex justify-between items-center">
                                            <h3 class="text-lg font-semibold">چگونه حساب کاربری ایجاد کنم؟</h3>
                                            <i class="fas fa-chevron-down transition-transform"></i>
                                        </div>
                                    </button>
                                    <div class="faq-answer p-6 border-t border-gray-200 hidden">
                                        <p class="text-gray-700 leading-relaxed">
                                            روی دکمه "ثبت‌نام" در بالای صفحه کلیک کنید و فرم را با اطلاعات معتبر تکمیل نمایید. بعد از تأیید ایمیل، حساب شما فعال می‌شود.
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                                    <button class="w-full p-6 text-right hover:bg-gray-50 transition-colors faq-question">
                                        <div class="flex justify-between items-center">
                                            <h3 class="text-lg font-semibold">رمز عبور خود را فراموش کرده‌ام</h3>
                                            <i class="fas fa-chevron-down transition-transform"></i>
                                        </div>
                                    </button>
                                    <div class="faq-answer p-6 border-t border-gray-200 hidden">
                                        <p class="text-gray-700 leading-relaxed">
                                            در صفحه ورود، روی "فراموشی رمز عبور" کلیک کنید و ایمیل خود را وارد کنید. لینک بازیابی برای شما ارسال خواهد شد.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- پشتیبانی -->
                        <div id="support" class="faq-category hidden">
                            <div class="space-y-4">
                                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                                    <button class="w-full p-6 text-right hover:bg-gray-50 transition-colors faq-question">
                                        <div class="flex justify-between items-center">
                                            <h3 class="text-lg font-semibold">چگونه با پشتیبانی تماس بگیرم؟</h3>
                                            <i class="fas fa-chevron-down transition-transform"></i>
                                        </div>
                                    </button>
                                    <div class="faq-answer p-6 border-t border-gray-200 hidden">
                                        <p class="text-gray-700 leading-relaxed">
                                            می‌توانید از طریق سیستم تیکت در پنل کاربری، تلفن 021-12345678 یا ایمیل support@shop.com با ما در ارتباط باشید. پاسخگویی از ساعت 8 صبح تا 20 شب.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- بازگشت وجه -->
                        <div id="refund" class="faq-category hidden">
                            <div class="space-y-4">
                                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                                    <button class="w-full p-6 text-right hover:bg-gray-50 transition-colors faq-question">
                                        <div class="flex justify-between items-center">
                                            <h3 class="text-lg font-semibold">آیا امکان بازگشت وجه وجود دارد؟</h3>
                                            <i class="fas fa-chevron-down transition-transform"></i>
                                        </div>
                                    </button>
                                    <div class="faq-answer p-6 border-t border-gray-200 hidden">
                                        <p class="text-gray-700 leading-relaxed">
                                            با توجه به ماهیت دیجیتال محصولات، بازگشت وجه تنها در مواردی مثل خرابی فایل، عدم تطبیق با توضیحات یا مشکلات فنی امکان‌پذیر است.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- مسائل فنی -->
                        <div id="technical" class="faq-category hidden">
                            <div class="space-y-4">
                                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                                    <button class="w-full p-6 text-right hover:bg-gray-50 transition-colors faq-question">
                                        <div class="flex justify-between items-center">
                                            <h3 class="text-lg font-semibold">فایل دانلودی باز نمی‌شود</h3>
                                            <i class="fas fa-chevron-down transition-transform"></i>
                                        </div>
                                    </button>
                                    <div class="faq-answer p-6 border-t border-gray-200 hidden">
                                        <p class="text-gray-700 leading-relaxed">
                                            ابتدا مطمئن شوید فایل کامل دانلود شده است. سپس نرم‌افزار مناسب برای باز کردن فایل نصب کنید. اگر مشکل ادامه دارد، با پشتیبانی تماس بگیرید.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Support -->
        <section class="py-16 bg-gray-800 text-white">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold mb-4">سوال خود را پیدا نکردید؟</h2>
                <p class="text-gray-300 mb-8">با تیم پشتیبانی ما در ارتباط باشید</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="tel:02112345678" class="bg-blue-600 px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        <i class="fas fa-phone ml-2"></i>تماس تلفنی
                    </a>
                    <a href="mailto:support@shop.com" class="bg-green-600 px-8 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                        <i class="fas fa-envelope ml-2"></i>ارسال ایمیل
                    </a>
                    <a href="dashboard.html#tickets" class="bg-purple-600 px-8 py-3 rounded-lg hover:bg-purple-700 transition-colors font-semibold">
                        <i class="fas fa-ticket-alt ml-2"></i>ثبت تیکت
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/faq.js')}}"></script>
@endsection