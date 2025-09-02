@extends('app.layouts.master')

@section('title','محصولات')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/products.css')}}">
@endsection

@section('content')
    <div id="main-content">
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

        <!-- Breadcrumb -->
        <section class="bg-white py-4">
            <div class="container mx-auto px-4">
                <nav class="text-sm text-gray-600">
                    <ol class="list-none p-0 inline-flex">
                        <li class="flex items-center">
                            <a href="index.html" class="hover:text-blue-600">خانه</a>
                            <i class="fas fa-chevron-left mx-2"></i>
                        </li>
                        <li id="breadcrumbCategory" class="text-gray-500">محصولات</li>
                    </ol>
                </nav>
            </div>
        </section>

        <!-- Category Header -->
        <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
            <div class="container mx-auto px-4 text-center">
                <h1 id="categoryTitle" class="text-4xl font-bold mb-4">محصولات</h1>
                <p id="categoryDescription" class="text-xl opacity-90">بهترین محصولات کاربردی و حرفه‌ای</p>
            </div>
        </section>

        <!-- Filters & Products -->
        <section class="py-12">
            <div class="container mx-auto px-4">
                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Sidebar Filters -->
                    <div class="lg:w-1/4">
                        <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                            <h3 class="text-lg font-bold mb-6">فیلترها</h3>
                            <!-- Availability Filter -->
                            <div class="mb-6">
                                <h4 class="font-semibold mb-3">وضعیت موجودی</h4>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" id="available-only" class="ml-2 text-blue-600 focus:ring-blue-500">
                                        <span>فقط موجودها</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" id="unavailable-only" class="ml-2 text-blue-600 focus:ring-blue-500">
                                        <span>ناموجود</span>
                                    </label>
                                </div>
                            </div>
                            <!-- Price Filter -->
                            <div class="mb-6">
                                <h4 class="font-semibold mb-3">بازه قیمتی (تومان)</h4>
                                <div class="flex flex-wrap items-center gap-2">
                                    <input type="number" id="price-min" placeholder="حداقل" class="flex-1 min-w-[120px] p-2 border rounded-lg focus:border-blue-500 focus:outline-none">
                                    <input type="number" id="price-max" placeholder="حداکثر" class="flex-1 min-w-[120px] p-2 border rounded-lg focus:border-blue-500 focus:outline-none">
                                </div>
                                <button id="apply-price-filter" class="mt-2 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">اعمال فیلتر قیمت</button>
                            </div>
                            <!-- Author Filter -->
                            <div class="mb-6">
                                <h4 class="font-semibold mb-3">سازنده / نویسنده</h4>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" class="author-filter ml-2 text-blue-600 focus:ring-blue-500" value="Adobe">
                                        <span>Adobe</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" class="author-filter ml-2 text-blue-600 focus:ring-blue-500" value="Microsoft">
                                        <span>Microsoft</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" class="author-filter ml-2 text-blue-600 focus:ring-blue-500" value="Autodesk">
                                        <span>Autodesk</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" class="author-filter ml-2 text-blue-600 focus:ring-blue-500" value="JetBrains">
                                        <span>JetBrains</span>
                                    </label>
                                </div>
                            </div>
                            <!-- Clear Filters -->
                            <button id="clear-filters" class="w-full border border-gray-300 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                                پاک کردن فیلترها
                            </button>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="lg:w-3/4">
                        <!-- Sort & View Options -->
                        <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
                            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                                <div class="text-gray-600">
                                    <span id="resultsCount">نمایش 1-3 از 3 محصول</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-gray-600">مرتب‌سازی:</span>
                                    <select id="sortSelect" class="border border-gray-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:outline-none">
                                        <option value="popular">پربازدیدترین</option>
                                        <option value="price-low">ارزان‌ترین</option>
                                        <option value="price-high">گران‌ترین</option>
                                        <option value="newest">جدیدترین</option>
                                        <option value="rating">بهترین امتیاز</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Products -->
                        <div id="productsContainer" class="container mx-auto py-10 px-4">
                            <div id="productsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <!-- محصول 1 - موجود -->
                                <div class="product-item bg-white rounded-xl shadow-md overflow-hidden" data-available="true" data-price="2500000" data-author="Adobe">
                                    <div class="relative group">
                                        <img src="https://images.pexels.com/photos/4348401/pexels-photo-4348401.jpeg?auto=compress&cs=tinysrgb&w=400" alt="Adobe Photoshop 2024" class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                        <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">17% تخفیف</span>
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-semibold text-lg mb-2 text-gray-800 line-clamp-2">Adobe Photoshop 2024</h3>
                                        <div class="flex items-center mb-2 text-sm text-gray-500">
                                            <span>(126)</span>
                                        </div>
                                        <div class="flex items-center justify-between mb-3">
                                            <div>
                                                <span class="text-xl font-bold text-green-600">۲٬۵۰۰٬۰۰۰ تومان</span>
                                                <span class="ml-2 text-sm text-gray-500 line-through">۳٬۰۰۰٬۰۰۰ تومان</span>
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <button onclick="addToCart(1)" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-1">
                                                <i class="fas fa-shopping-cart"></i> افزودن به سبد
                                            </button>
                                            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                    
                                <!-- محصول 2 - موجود -->
                                <div class="product-item bg-white rounded-xl shadow-md overflow-hidden" data-available="true" data-price="450000" data-author="Microsoft">
                                    <div class="relative group">
                                        <img src="https://images.pexels.com/photos/11035380/pexels-photo-11035380.jpeg?auto=compress&cs=tinysrgb&w=400" alt="دوره کامل آموزش React JS" class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                        <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">10% تخفیف</span>
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-semibold text-lg mb-2 text-gray-800 line-clamp-2">دوره کامل آموزش React JS</h3>
                                        <div class="flex items-center mb-2 text-sm text-gray-500">
                                            <span>(85)</span>
                                        </div>
                                        <div class="flex items-center justify-between mb-3">
                                            <div>
                                                <span class="text-xl font-bold text-green-600">۴۵۰٬۰۰۰ تومان</span>
                                                <span class="ml-2 text-sm text-gray-500 line-through">۵۰۰٬۰۰۰ تومان</span>
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <button onclick="addToCart(2)" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-1">
                                                <i class="fas fa-shopping-cart"></i> افزودن به سبد
                                            </button>
                                            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- محصول 3 - ناموجود -->
                                <div class="product-item bg-white rounded-xl shadow-md overflow-hidden" data-available="false" data-price="3000000" data-author="Autodesk">
                                    <div class="relative group">
                                        <img src="https://images.pexels.com/photos/4348401/pexels-photo-4348401.jpeg?auto=compress&cs=tinysrgb&w=400" alt="AutoCAD 2024" class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                        <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">ناموجود</span>
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-semibold text-lg mb-2 text-gray-800 line-clamp-2">AutoCAD 2024</h3>
                                        <div class="flex items-center mb-2 text-sm text-gray-500">
                                            <span>(50)</span>
                                        </div>
                                        <div class="flex items-center justify-between mb-3">
                                            <div>
                                                <span class="text-xl font-bold text-green-600">۳٬۰۰۰٬۰۰۰ تومان</span>
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <button class="flex-1 bg-gray-400 text-white py-2 rounded-lg cursor-not-allowed flex items-center justify-center gap-1">
                                                <i class="fas fa-shopping-cart"></i> افزودن به سبد
                                            </button>
                                            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-8 flex justify-center">
                            <nav class="flex items-center space-x-2">
                                <button class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50" disabled>
                                    قبلی
                                </button>
                                <button class="px-3 py-2 bg-blue-600 text-white rounded-lg">1</button>
                                <button class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">2</button>
                                <button class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">3</button>
                                <span class="px-3 py-2">...</span>
                                <button class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">13</button>
                                <button class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    بعدی
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/products.js')}}"></script>
@endsection