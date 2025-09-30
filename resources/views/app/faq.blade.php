@extends('app.layouts.master')

@section('title','سوالات متداول')

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

        @foreach ($faqs as $faq)
            @if ($faq->status == "published")
            <section class="pb-12">
                <div class="container mx-auto px-4">
                    <div class="max-w-4xl mx-auto">
                        <div id="faqContent">
                            <div id="purchase" class="faq-category">
                                <div class="space-y-4">
                                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                                        <button class="w-full p-6 text-right hover:bg-gray-50 transition-colors faq-question">
                                            <div class="flex justify-between items-center">
                                                <h3 class="text-lg font-semibold">{{ $faq->question }}</h3>
                                                <i class="fas fa-chevron-down transition-transform"></i>
                                            </div>
                                        </button>
                                        <div class="faq-answer p-6 border-t border-gray-200 hidden">
                                            <p class="text-gray-700 leading-relaxed">
                                                {{ $faq->answer }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            @endif
        @endforeach
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