@extends('app.layouts.master')

@section('title', 'نتایج جستجو')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/products.css')}}">
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .active-filters {
            background-color: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .filter-badge {
            background-color: #3b82f6;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            margin-left: 0.5rem;
        }
    </style>
@endsection

@section('content')
    @if (session('success'))       
        <x-add-to-cart></x-add-to-cart>
    @endif
    @if($errors->any())
        <x-error></x-error>
    @endif

    <div id="main-content">
        <!-- Cart Modal (همان کد قبلی) -->
        <div id="cart-modal" class="fixed w-80 bg-white text-gray-800 rounded-xl shadow-2xl p-0 hidden z-50 border border-gray-200">
            <!-- محتوای مودال سبد خرید -->
        </div>

        <!-- Breadcrumb -->
        <section class="bg-white py-4">
            <div class="container mx-auto px-4">
                <nav class="text-sm text-gray-600">
                    <ol class="list-none p-0 inline-flex">
                        <li class="flex items-center">
                            <a href="{{route('home')}}" class="hover:text-blue-600">خانه</a>
                            <i class="fas fa-chevron-left mx-2"></i>
                        </li>
                        <li class="text-gray-500">نتایج جستجو</li>
                    </ol>
                </nav>
            </div>
        </section>

        <!-- Search Header -->
        <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl font-bold mb-4">
                    @if(request('q'))
                        نتایج جستجو برای "{{ request('q') }}"
                    @else
                        همه محصولات
                    @endif
                </h1>
                <p class="text-xl opacity-90">
                    @if($products->total() > 0)
                        {{ $products->total() }} محصول یافت شد
                    @else
                        محصولی یافت نشد
                    @endif
                </p>
            </div>
        </section>

        <!-- Active Filters -->
        @if(request()->anyFilled(['q', 'category', 'price', 'availability']))
        <section class="bg-white py-4 border-b">
            <div class="container mx-auto px-4">
                <div class="active-filters">
                    <h3 class="font-semibold text-gray-700 mb-3">فیلترهای فعال:</h3>
                    <div class="flex flex-wrap gap-2">
                        @if(request('q'))
                            <span class="filter-badge">
                                جستجو: {{ request('q') }}
                                <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="mr-2 hover:text-red-200">×</a>
                            </span>
                        @endif
                        
                        @if(request('category'))
                            @php
                                $categoryName = $categories->where('id', request('category'))->first()->name ?? 'دسته‌بندی';
                            @endphp
                            <span class="filter-badge">
                                دسته‌بندی: {{ $categoryName }}
                                <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="mr-2 hover:text-red-200">×</a>
                            </span>
                        @endif
                        
                        @if(request('price'))
                            @php
                                $priceLabels = [
                                    '0-50000' => 'زیر ۵۰,۰۰۰ تومان',
                                    '50000-100000' => '۵۰,۰۰۰ تا ۱۰۰,۰۰۰ تومان',
                                    '100000-200000' => '۱۰۰,۰۰۰ تا ۲۰۰,۰۰۰ تومان',
                                    '200000-500000' => '۲۰۰,۰۰۰ تا ۵۰۰,۰۰۰ تومان',
                                    '500000-1000000' => '۵۰۰,۰۰۰ تا ۱,۰۰۰,۰۰۰ تومان',
                                    '1000000' => 'بالای ۱,۰۰۰,۰۰۰ تومان'
                                ];
                            @endphp
                            <span class="filter-badge">
                                قیمت: {{ $priceLabels[request('price')] ?? request('price') }}
                                <a href="{{ request()->fullUrlWithQuery(['price' => null]) }}" class="mr-2 hover:text-red-200">×</a>
                            </span>
                        @endif
                        
                        @if(request('availability') !== null)
                            <span class="filter-badge">
                                موجودی: {{ request('availability') ? 'موجود' : 'ناموجود' }}
                                <a href="{{ request()->fullUrlWithQuery(['availability' => null]) }}" class="mr-2 hover:text-red-200">×</a>
                            </span>
                        @endif
                        
                        <a href="{{ route('search') }}" class="text-red-500 hover:text-red-700 text-sm flex items-center gap-1">
                            <i class="fas fa-times"></i>
                            حذف همه فیلترها
                        </a>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <section class="py-12">
            <div class="container mx-auto px-4">
                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Sidebar Filters -->
                    <div class="lg:w-1/4">
                        <div class="bg-white rounded-xl shadow-lg p-6 sticky top-4">
                            <h3 class="text-lg font-bold text-gray-800 mb-6">فیلترها</h3>
                            
                            <!-- Search Form -->
                            <form action="{{ route('search') }}" method="GET" class="mb-6">
                                <input type="text" name="q" value="{{ request('q') }}" 
                                       placeholder="جستجوی محصولات..." 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                
                                <!-- Category Filter -->
                                <div class="mt-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">دسته‌بندی</label>
                                    <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">همه دسته‌بندی‌ها</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Price Filter -->
                                <div class="mt-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">محدوده قیمت</label>
                                    <select name="price" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">همه قیمت‌ها</option>
                                        <option value="0-50000" {{ request('price') == '0-50000' ? 'selected' : '' }}>زیر ۵۰,۰۰۰ تومان</option>
                                        <option value="50000-100000" {{ request('price') == '50000-100000' ? 'selected' : '' }}>۵۰,۰۰۰ تا ۱۰۰,۰۰۰ تومان</option>
                                        <option value="100000-200000" {{ request('price') == '100000-200000' ? 'selected' : '' }}>۱۰۰,۰۰۰ تا ۲۰۰,۰۰۰ تومان</option>
                                        <option value="200000-500000" {{ request('price') == '200000-500000' ? 'selected' : '' }}>۲۰۰,۰۰۰ تا ۵۰۰,۰۰۰ تومان</option>
                                        <option value="500000-1000000" {{ request('price') == '500000-1000000' ? 'selected' : '' }}>۵۰۰,۰۰۰ تا ۱,۰۰۰,۰۰۰ تومان</option>
                                        <option value="1000000" {{ request('price') == '1000000' ? 'selected' : '' }}>بالای ۱,۰۰۰,۰۰۰ تومان</option>
                                    </select>
                                </div>
                                
                                <!-- Availability Filter -->
                                <div class="mt-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">وضعیت موجودی</label>
                                    <select name="availability" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">همه محصولات</option>
                                        <option value="1" {{ request('availability') == '1' ? 'selected' : '' }}>فقط موجود</option>
                                        <option value="0" {{ request('availability') == '0' ? 'selected' : '' }}>فقط ناموجود</option>
                                    </select>
                                </div>
                                
                                <!-- Sort By -->
                                <div class="mt-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">مرتب‌سازی</label>
                                    <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>جدیدترین</option>
                                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>قدیمی‌ترین</option>
                                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>ارزان‌ترین</option>
                                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>گران‌ترین</option>
                                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>پربازدیدترین</option>
                                    </select>
                                </div>
                                
                                <div class="mt-6 flex gap-2">
                                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors">
                                        اعمال فیلترها
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Products -->
                    <div class="lg:w-3/4">
                        @if($products->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach ($products as $product)
                                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
                                        <div class="relative overflow-hidden">
                                            <a href="{{ route('show-product', $product->id) }}">
                                                <img src="{{ asset($product->image_urls[0] ?? 'images/placeholder.jpg') }}" alt="{{ $product->title }}" 
                                                    class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                                @if (!$product->availability)
                                                    <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">ناموجود</span>
                                                @endif
                                            </a>
                                        </div>
                                        <div class="p-6">
                                            <a href="{{ route('show-product', $product->id) }}">
                                                <h3 class="font-semibold text-lg mb-2 text-gray-800 line-clamp-2">{{ $product->title }}</h3>    
                                            </a>                                    
                                            <div class="flex items-center mb-3">
                                                <span class="text-gray-500 text-sm">{{ $product->category->name }}</span>
                                            </div>
                                            
                                            <div class="flex items-center justify-between mb-4">
                                                <span class="text-xl font-bold text-green-600">{{ number_format($product->original_price) }} تومان</span>
                                            </div>
                                            
                                            <div class="flex gap-2">
                                                @if ($product->availability)
                                                    <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                        <button type="submit" 
                                                                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                                                            <i class="fas fa-shopping-cart"></i>
                                                            افزودن به سبد
                                                        </button>
                                                    </form>
                                                @else 
                                                    <button class="w-full flex-1 bg-gray-400 text-white py-2 rounded-lg cursor-not-allowed flex items-center justify-center gap-1">
                                                        <i class="fas fa-shopping-cart"></i> افزودن به سبد
                                                    </button>
                                                @endif
                                                <a href="{{ route('show-product', $product->id) }}">
                                                    <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Pagination -->
                            <div class="mt-8">
                                {{ $products->appends(request()->query())->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-search text-6xl text-gray-400 mb-4"></i>
                                <h3 class="text-2xl font-bold text-gray-600 mb-2">محصولی یافت نشد</h3>
                                <p class="text-gray-500 mb-6">لطفاً فیلترهای جستجو را تغییر دهید یا عبارت دیگری را امتحان کنید</p>
                                <a href="{{ route('product') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
                                    مشاهده همه محصولات
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/products.js')}}"></script>
    <script src="{{asset('js/modal.js')}}"></script>
@endsection