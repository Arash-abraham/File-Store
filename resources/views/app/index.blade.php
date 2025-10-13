@extends('app.layouts.master')

@section('title', 'خانه')

@section('content')
    @if (session('success'))       
        <x-add-to-cart></x-add-to-cart>
    @endif
    @if($errors->any())
        <x-error></x-adderror>
    @endif

    <div id="main-content">
        <!-- Cart Modal and Blur Overlay -->
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
        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
            <div class="container mx-auto px-4 text-center">
                @foreach ($setting as $item)
                <h2 class="text-5xl font-bold mb-6">فروشگاه {{$item->site_title}}</h2>
                <p class="text-xl mb-8 opacity-90">{{$item->site_description}}</p>
                @endforeach
                <a href="{{ route('products') }}">
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
                
                <div id="productsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
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
            </div>
        </section>

        <!-- Categories -->
        @php
            
        @endphp
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">دسته‌بندی محصولات</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach ($categories as $category)
                        <a href="{{ route('productsWithCategory', ['category' => $category->id]) }}" 
                           class="bg-gradient-to-br from-{{$category->color}}-500 to-{{$category->color}}-600 rounded-xl p-6 text-white text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group cursor-pointer">
                            <i class="fas {{$category->icon}} text-5xl mb-4 group-hover:text-{{$category->color}}-100 transition-colors duration-300"></i>
                            <h3 class="text-xl font-bold mb-2 group-hover:text-white">{{$category->name}}</h3>
                            <span class="text-sm opacity-90">{{ $category->products_count }} محصول</span>
                            <div class="mt-4 bg-white bg-opacity-20 text-white px-4 py-2 rounded-full inline-block group-hover:bg-opacity-30 transition-all duration-200">
                                مشاهده محصولات
                            </div>
                        </a>
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
                        <a class="flex-1 px-4 py-3" href="{{ route('login') }}">
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
    <script src="{{asset('js/modal.js')}}"></script>
@endsection