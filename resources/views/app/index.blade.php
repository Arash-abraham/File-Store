@extends('app.layouts.master')

@section('title','خانه')

@section('content')
    <div id="main-content">
        <!-- Cart Modal and Blur Overlay -->
        <div id="blur-overlay" class="hidden"></div>
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

        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-5xl font-bold mb-6">فروشگاه دیجیتال برتر</h2>
                <p class="text-xl mb-8 opacity-90">بهترین نرم‌افزارها، دوره‌های آموزشی و محصولات دیجیتال را از ما بخرید</p>
                <a href="{{route('products')}}">
                    <button class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                        مشاهده محصولات
                    </button>
                </a>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-2">10,000+</h3>
                        <p class="text-gray-600">مشتری راضی</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-box text-green-600 text-2xl"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-2">5,000+</h3>
                        <p class="text-gray-600">محصول متنوع</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-yellow-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-award text-yellow-600 text-2xl"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-2">98%</h3>
                        <p class="text-gray-600">رضایت کاربران</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-purple-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-headset text-purple-600 text-2xl"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-2">24/7</h3>
                        <p class="text-gray-600">پشتیبانی آنلاین</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Popular Products -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">محصولات پرفروش</h2>
                    <p class="text-gray-600 text-lg">بهترین و پربازدیدترین محصولات ما</p>
                </div>
                
                <div id="productsGrid" class=" grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
                        @foreach ($products as $product)
                            <div class="relative overflow-hidden">
                                <a href="{{route('show-product',$product->id)}}">
                                    <img src="{{$product->image_urls[0]}}">
                                </a>
                                <div class="p-6">
                                    <a href="{{route('show-product',$product->id)}}">
                                        <h3 class="font-semibold text-lg mb-2 text-gray-800 line-clamp-2">{{ $product->title }}</h3>    
                                    </a>                                    
                                    <div class="flex items-center mb-3">
                                        <span class="text-gray-500 text-sm mr-2">{{$product->category->name}}</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <span class="text-xl font-bold text-green-600">{{$product->original_price}} تومان</span>                        
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-2 mb-4">
                                        <button onclick="addToCart(1)" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-shopping-cart ml-1"></i>
                                            افزودن به سبد
                                        </button>
                                        <a href="{{route('show-product',$product->id)}}">
                                            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach ($categories as $category)
                        <div class="bg-gradient-to-br from-{{$category->color}}-500 to-{{$category->color}}-600 rounded-xl p-6 text-white text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                            <i class="fas {{$category->icon}} text-5xl mb-4 group-hover:text-{{$category->color}}-100 transition-colors duration-300"></i>
                            <h3 class="text-xl font-bold mb-2 group-hover:text-white">دوره‌های آموزشی</h3>
                            <p class="opacity-90 group-hover:opacity-100 transition-opacity duration-300">آموزش‌های تخصصی</p>
                            <button class="mt-4 bg-white bg-opacity-20 text-white px-4 py-2 rounded-full hover:bg-opacity-30 transition-all duration-200">مشاهده</button>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Newsletter -->
        @guest
            <section class="py-16 bg-gray-800 text-white">
                <div class="container mx-auto px-4 text-center">
                    <h2 class="text-3xl font-bold mb-4">در خبرنامه ما عضو شوید</h2>
                    <p class="text-gray-300 mb-8">از آخرین محصولات و تخفیف‌ها باخبر شوید</p>
                    <div class="max-w-md mx-auto flex gap-4">
                        <a class="flex-1 px-4 py-3" href="{{route('login')}}">
                            <button class="w-full bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold py-2">
                                ثبت نام
                            </button>
                        </a>    
                    </div>
                </div>
            </section>
        @else
            <section class="py-16 bg-gray-800 text-white">
                <div class="container mx-auto px-4 text-center">
                    <h2 class="text-3xl font-bold mb-4">در خبرنامه ما عضو شوید</h2>
                    <p class="text-gray-300 mb-8">از آخرین محصولات و تخفیف‌ها باخبر شوید</p>
                    <div class="max-w-md mx-auto flex-1 px-4 py-3">
                        شما عضوی از خبرنامه ما هستید
                    </div>
                </div>
            </section>
        @endguest
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/main.js') }}"></script>
@endsection