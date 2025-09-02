@extends('app.layouts.master')

@section('title','دسته بندی')

@section('content')
    <div id="main-content">
        <!-- Cart Modal and Blur Overlay -->
        <div id="blur-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>
        <div id="cart-modal" class="w-80 bg-gray-800 text-white rounded-xl shadow-2xl p-6 hidden z-50 transition-all duration-300 transform scale-95 hover:scale-100">
            <div class="flex justify-between items-center mb-4 border-b border-gray-700 pb-2">
                <h2 class="text-xl font-bold text-gray-100">سبد خرید</h2>
                <button id="close-cart" class="text-gray-400 hover:text-white transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="cart-content" class="max-h-64 overflow-y-auto mb-6 space-y-4">
                <!-- محتوای سبد خرید اینجا رندر می‌شود -->
            </div>
            <div id="cart-actions" class="mt-6"></div>
        </div>

        <!-- Breadcrumb -->
        <section class="bg-gray-50 py-4 shadow-sm">
            <div class="container mx-auto px-4">
                <nav class="text-sm text-gray-600">
                    <ol class="list-none p-0 inline-flex items-center space-x-2">
                        <li class="flex items-center">
                            <a href="index.html" class="hover:text-blue-600 font-medium transition-colors duration-200">خانه</a>
                            <i class="fas fa-chevron-left mx-2 text-gray-400"></i>
                        </li>
                        <li id="breadcrumbCategory" class="font-medium text-gray-700">دسته بندی ها</li>
                    </ol>
                </nav>
            </div>
        </section>

        <!-- Category Header -->
        <section class="bg-gradient-to-r from-blue-500 via-purple-500 to-indigo-600 text-white py-20 relative overflow-hidden">
            <div class="container mx-auto px-4 text-center relative z-10">
                <h1 id="categoryTitle" class="text-5xl font-extrabold mb-4 drop-shadow-lg">دسته بندی ها</h1>
                <p id="categoryDescription" class="text-xl opacity-90 max-w-2xl mx-auto drop-shadow">بهترین دسته بندی‌های کاربردی و حرفه‌ای</p>
            </div>
            <div class="absolute inset-0 bg-black bg-opacity-20 blur-md"></div>
        </section>

        <!-- Filters & Products -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-300 transform group">
                        <i class="fas fa-laptop-code text-5xl mb-4 group-hover:text-blue-100 transition-colors duration-300"></i>
                        <h3 class="text-xl font-bold mb-2 group-hover:text-white">دسته بندی ها</h3>
                        <p class="opacity-90 group-hover:opacity-100 transition-opacity duration-300">انواع دسته بندی‌های کاربردی</p>
                        <button class="mt-4 bg-white bg-opacity-20 text-white px-4 py-2 rounded-full hover:bg-opacity-30 transition-all duration-200">مشاهده</button>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                        <i class="fas fa-play-circle text-5xl mb-4 group-hover:text-green-100 transition-colors duration-300"></i>
                        <h3 class="text-xl font-bold mb-2 group-hover:text-white">دوره‌های آموزشی</h3>
                        <p class="opacity-90 group-hover:opacity-100 transition-opacity duration-300">آموزش‌های تخصصی</p>
                        <button class="mt-4 bg-white bg-opacity-20 text-white px-4 py-2 rounded-full hover:bg-opacity-30 transition-all duration-200">مشاهده</button>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                        <i class="fas fa-book text-5xl mb-4 group-hover:text-purple-100 transition-colors duration-300"></i>
                        <h3 class="text-xl font-bold mb-2 group-hover:text-white">کتاب‌های الکترونیکی</h3>
                        <p class="opacity-90 group-hover:opacity-100 transition-opacity duration-300">کتاب‌های PDF و EPUB</p>
                        <button class="mt-4 bg-white bg-opacity-20 text-white px-4 py-2 rounded-full hover:bg-opacity-30 transition-all duration-200">مشاهده</button>
                    </div>
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                        <i class="fas fa-palette text-5xl mb-4 group-hover:text-orange-100 transition-colors duration-300"></i>
                        <h3 class="text-xl font-bold mb-2 group-hover:text-white">قالب‌ها</h3>
                        <p class="opacity-90 group-hover:opacity-100 transition-opacity duration-300">قالب‌های وب و گرافیک</p>
                        <button class="mt-4 bg-white bg-opacity-20 text-white px-4 py-2 rounded-full hover:bg-opacity-30 transition-all duration-200">مشاهده</button>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/category.js') }}"></script>
@endsection