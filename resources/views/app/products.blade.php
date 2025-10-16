@extends('app.layouts.master')

@section('title','محصولات')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/products.css')}}">
@endsection

@section('content')
    @if (session('add_to_cart'))       
        <x-add-to-cart></x-add-to-cart>
    @endif
    @if($errors->any())
        <x-error></x-adderror>
    @endif

    <div id="main-content">

        <div id="cart-modal" class="fixed w-80 bg-white text-gray-800 rounded-xl shadow-2xl p-0 hidden z-50 border border-gray-200">
            <div class="flex justify-between items-center p-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-t-xl">
                <h2 class="text-lg font-bold">سبد خرید</h2>
                <button id="close-cart" class="text-white hover:bg-white/20 p-1 rounded-full transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div id="cart-content" class="max-h-64 overflow-y-auto p-4 space-y-3">
                @if ($cartItems->isEmpty())
                    <p class="text-center text-gray-500 text-sm">سبد خرید خالی است</p>
                @else
                    @foreach ($cartItems as $item)
                        <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                            <div class="flex items-center space-x-3 space-x-reverse">
                                <img src="{{ asset($item->product->image_urls[0] ?? 'images/placeholder.jpg') }}" 
                                    alt="{{ $item->product->title }}" class="w-12 h-12 object-cover rounded-lg shadow-sm">
                                <div>
                                    <h3 class="text-sm font-semibold">{{ $item->product->title }}</h3>
                                    <p class="text-xs text-purple-600 font-medium">{{ number_format($item->unit_price) }} تومان</p>
                                </div>
                            </div>
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-full transition-colors">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                @endif
            </div>
            
            <div class="p-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600">جمع کل:</span>
                    <span class="text-lg font-bold text-purple-700">{{ number_format($total) }} تومان</span>
                </div>
                @if (!$cartItems->isEmpty())
                    <a href="{{ route('checkout.show') }}" 
                    class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white py-3 rounded-lg font-semibold transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-credit-card"></i>
                        تسویه حساب
                    </a>
                @endif
            </div>
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
                    @if($products->count() != 0)
                        <div class="lg:w-1/4">
                            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                                <h3 class="text-lg font-bold mb-6">فیلترها</h3>
                                
                                <form id="filter-form" method="GET" action="{{ route('products') }}">
                                    <!-- فیلد مخفی برای حفظ category -->
                                    @if($selectedCategory)
                                        <input type="hidden" name="category" value="{{ $selectedCategory }}">
                                    @endif
                                    
                                    <!-- Availability Filter -->
                                    <div class="mb-6">
                                        <h4 class="font-semibold mb-3">وضعیت موجودی</h4>
                                        <div class="space-y-2">
                                            <label class="flex items-center">
                                                <input type="radio" name="availability" value="available" 
                                                    {{ request('availability') === 'available' ? 'checked' : '' }}
                                                    class="ml-2 text-blue-600 focus:ring-blue-500">
                                                <span>فقط موجودها</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="availability" value="unavailable"
                                                    {{ request('availability') === 'unavailable' ? 'checked' : '' }}
                                                    class="ml-2 text-blue-600 focus:ring-blue-500">
                                                <span>ناموجود</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="availability" value="all"
                                                    {{ !request('availability') || request('availability') === 'all' ? 'checked' : '' }}
                                                    class="ml-2 text-blue-600 focus:ring-blue-500">
                                                <span>همه</span>
                                            </label>
                                        </div>
                                    </div>
                    
                                    <div class="mb-6">
                                        <h4 class="font-semibold mb-3">بازه قیمتی (تومان)</h4>
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <input type="number" name="price_min" 
                                                value="{{ request('price_min') }}" 
                                                placeholder="حداقل"
                                                min="0"
                                                class="flex-1 min-w-[120px] p-2 border rounded-lg">
                                            <span class="text-gray-500">تا</span>
                                            <input type="number" name="price_max" 
                                                value="{{ request('price_max') }}"
                                                placeholder="حداکثر"
                                                min="0" 
                                                class="flex-1 min-w-[120px] p-2 border rounded-lg">
                                        </div>
                                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                                            اعمال فیلتر قیمت
                                        </button>
                                    </div>
                                    
                                    <!-- Category Filter - فقط وقتی که category در URL نیست -->
                                    @if(!$selectedCategory)
                                        <div class="mb-6">
                                            <h4 class="font-semibold mb-3">دسته بندی</h4>
                                            <div class="space-y-2 max-h-60 overflow-y-auto">
                                                @foreach($categories as $category)
                                                <label class="flex items-center">
                                                    <input type="checkbox" name="categories[]" 
                                                        value="{{ $category->id }}"
                                                        {{ in_array($category->id, (array)request('categories', [])) ? 'checked' : '' }}
                                                        class="category-filter ml-2 text-blue-600 focus:ring-blue-500">
                                                    <span>{{ $category->name }}</span>
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Clear Filters -->
                                    <a href="{{ route('products') }}" 
                                    class="block w-full text-center border border-gray-300 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                                        پاک کردن فیلترها
                                    </a>
                                </form>
                            </div>
                        </div>
                    @endif
                    <!-- Products Grid -->
                    <div class="lg:w-3/4">                        
                        <!-- Products -->
                        <div id="productsContainer" class="container mx-auto py-10 px-4">
                            @if($products->count() == 0)
                                <div class="fixed inset-0 flex items-center justify-center bg-white z-50">
                                    <div class="text-center">
                                        <i class="fas fa-search text-6xl text-gray-400 mb-6"></i>
                                        <p class="text-2xl font-bold text-gray-700 mb-4">محصولی یافت نشد</p>
                                        <p class="text-lg text-gray-500 mb-8">لطفاً دسته بندی خود را تغییر دهید</p>
                                        <a href="{{ route('products') }}" 
                                        class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors text-lg">
                                            مشاهده همه محصولات
                                        </a>
                                    </div>
                            </div>
                            @else
                                <div id="productsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach ($products as $product)
                                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
                                            <div class="relative overflow-hidden">
                                                <a href="{{ route('show-product', $product->id) }}">
                                                    <img src="{{ asset($product->image_urls[0]) }}" alt="{{ $product->title }}" 
                                                        class="w-full h-48 object-cover">
                                                    @if (!$product->availability)
                                                        <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">ناموجود</span>
                                                    @endif
                                                    </a>
                                                <div class="p-6">
                                                    <a href="{{ route('show-product', $product->id) }}">
                                                        <h3 class="font-semibold text-lg mb-2 text-gray-800 line-clamp-2">{{ $product->title }}</h3>    
                                                    </a>                                    
                                                    <div class="flex items-center mb-3">
                                                        <span class="text-gray-500 text-sm mr-2">{{ $product->category->name }}</span>
                                                    </div>
                                                    
                                                    <div class="flex items-center justify-between mb-4">
                                                        <div>
                                                            <span class="text-xl font-bold text-green-600">{{ number_format($product->original_price) }} تومان</span>                        
                                                        </div>
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
                                                            <a href="{{ route('show-product', $product->id) }}">
                                                                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            </a>
                                                        @else 
                                                            <button class="w-full flex-1 bg-gray-400 text-white py-2 rounded-lg cursor-not-allowed flex items-center justify-center gap-1">
                                                                <i class="fas fa-shopping-cart"></i> افزودن به سبد
                                                            </button>
                                                            <a href="{{ route('show-product', $product->id) }}">
                                                                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        
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