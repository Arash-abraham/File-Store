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
                        <div class="relative overflow-hidden">
                            <img src="https://images.pexels.com/photos/4348401/pexels-photo-4348401.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=400" alt="Adobe Photoshop 2024" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            
                    <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                        17% تخفیف
                    </span>
                
                        </div>
                        
                        <div class="p-6">
                            <h3 class="font-semibold text-lg mb-2 text-gray-800 line-clamp-2">Adobe Photoshop 2024</h3>
                            
                            <div class="flex items-center mb-3">
                                <span class="text-gray-500 text-sm mr-2">(126)</span>
                            </div>
                            
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <span class="text-xl font-bold text-green-600">۲٬۵۰۰٬۰۰۰ تومان</span>
                                    
                    <span class="text-sm text-gray-500 line-through">۳٬۰۰۰٬۰۰۰ تومان</span>
                
                                </div>
                            </div>
                            
                            <div class="flex gap-2 mb-4">
                                <button onclick="addToCart(1)" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-shopping-cart ml-1"></i>
                                    افزودن به سبد
                                </button>
                                <button onclick="viewProduct(1)" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
                        <div class="relative overflow-hidden">
                            <img src="https://images.pexels.com/photos/4348401/pexels-photo-4348401.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=400" alt="Adobe Photoshop 2024" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            
                    <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                        17% تخفیف
                    </span>
                
                        </div>
                        
                        <div class="p-6">
                            <h3 class="font-semibold text-lg mb-2 text-gray-800 line-clamp-2">Adobe Photoshop 2024</h3>
                            
                            <div class="flex items-center mb-3">
                                <span class="text-gray-500 text-sm mr-2">(126)</span>
                            </div>
                            
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <span class="text-xl font-bold text-green-600">۲٬۵۰۰٬۰۰۰ تومان</span>
                                    
                    <span class="text-sm text-gray-500 line-through">۳٬۰۰۰٬۰۰۰ تومان</span>
                
                                </div>
                            </div>
                            
                            <div class="flex gap-2 mb-4">
                                <button onclick="addToCart(1)" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-shopping-cart ml-1"></i>
                                    افزودن به سبد
                                </button>
                                <button onclick="viewProduct(1)" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
                        <div class="relative overflow-hidden">
                            <img src="https://images.pexels.com/photos/4348401/pexels-photo-4348401.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=400" alt="Adobe Photoshop 2024" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            
                    <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                        17% تخفیف
                    </span>
                
                        </div>
                        
                        <div class="p-6">
                            <h3 class="font-semibold text-lg mb-2 text-gray-800 line-clamp-2">Adobe Photoshop 2024</h3>
                            
                            <div class="flex items-center mb-3">
                                <span class="text-gray-500 text-sm mr-2">(126)</span>
                            </div>
                            
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <span class="text-xl font-bold text-green-600">۲٬۵۰۰٬۰۰۰ تومان</span>
                                    
                    <span class="text-sm text-gray-500 line-through">۳٬۰۰۰٬۰۰۰ تومان</span>
                
                                </div>
                            </div>
                            
                            <div class="flex gap-2 mb-4">
                                <button onclick="addToCart(1)" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-shopping-cart ml-1"></i>
                                    افزودن به سبد
                                </button>
                                <button onclick="viewProduct(1)" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
                        <div class="relative overflow-hidden">
                            <img src="https://images.pexels.com/photos/4348401/pexels-photo-4348401.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=400" alt="Adobe Photoshop 2024" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            
                    <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                        17% تخفیف
                    </span>
                
                        </div>
                        
                        <div class="p-6">
                            <h3 class="font-semibold text-lg mb-2 text-gray-800 line-clamp-2">Adobe Photoshop 2024</h3>
                            
                            <div class="flex items-center mb-3">
                                <span class="text-gray-500 text-sm mr-2">(126)</span>
                            </div>
                            
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <span class="text-xl font-bold text-green-600">۲٬۵۰۰٬۰۰۰ تومان</span>
                                    
                    <span class="text-sm text-gray-500 line-through">۳٬۰۰۰٬۰۰۰ تومان</span>
                
                                </div>
                            </div>
                            
                            <div class="flex gap-2 mb-4">
                                <button onclick="addToCart(1)" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-shopping-cart ml-1"></i>
                                    افزودن به سبد
                                </button>
                                <button onclick="viewProduct(1)" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
                        <div class="relative overflow-hidden">
                            <img src="https://images.pexels.com/photos/4348401/pexels-photo-4348401.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=400" alt="Adobe Photoshop 2024" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            
                    <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                        17% تخفیف
                    </span>
                
                        </div>
                        
                        <div class="p-6">
                            <h3 class="font-semibold text-lg mb-2 text-gray-800 line-clamp-2">Adobe Photoshop 2024</h3>
                            
                            <div class="flex items-center mb-3">
                                <span class="text-gray-500 text-sm mr-2">(126)</span>
                            </div>
                            
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <span class="text-xl font-bold text-green-600">۲٬۵۰۰٬۰۰۰ تومان</span>
                                    
                    <span class="text-sm text-gray-500 line-through">۳٬۰۰۰٬۰۰۰ تومان</span>
                
                                </div>
                            </div>
                            
                            <div class="flex gap-2 mb-4">
                                <button onclick="addToCart(1)" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-shopping-cart ml-1"></i>
                                    افزودن به سبد
                                </button>
                                <button onclick="viewProduct(1)" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>

        <!-- Categories -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">محبوب‌ترین دسته‌بندی‌ها</h2>
                    <p class="text-gray-600 text-lg">انواع مختلف محصولات دیجیتال</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-8 text-white text-center hover:transform hover:scale-105 transition-all cursor-pointer">
                        <i class="fas fa-laptop-code text-4xl mb-4"></i>
                        <h3 class="text-xl font-bold mb-2">نرم‌افزارها</h3>
                        <p class="opacity-90">انواع نرم‌افزارهای کاربردی</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-8 text-white text-center hover:transform hover:scale-105 transition-all cursor-pointer">
                        <i class="fas fa-play-circle text-4xl mb-4"></i>
                        <h3 class="text-xl font-bold mb-2">دوره‌های آموزشی</h3>
                        <p class="opacity-90">آموزش‌های تخصصی</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-8 text-white text-center hover:transform hover:scale-105 transition-all cursor-pointer">
                        <i class="fas fa-book text-4xl mb-4"></i>
                        <h3 class="text-xl font-bold mb-2">کتاب‌های الکترونیکی</h3>
                        <p class="opacity-90">کتاب‌های PDF و EPUB</p>
                    </div>
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-8 text-white text-center hover:transform hover:scale-105 transition-all cursor-pointer">
                        <i class="fas fa-palette text-4xl mb-4"></i>
                        <h3 class="text-xl font-bold mb-2">قالب‌ها</h3>
                        <p class="opacity-90">قالب‌های وب و گرافیک</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Newsletter -->
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
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/main.js') }}"></script>
@endsection