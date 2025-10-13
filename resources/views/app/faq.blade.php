@extends('app.layouts.master')

@section('title','سوالات متداول')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/products.css')}}">
@endsection

@section('content')
    @if (session('success'))       
        <x-add-to-cart></x-add-to-cart>
    @endif
    @if($errors->any())
        <x-error></x-adderror>
    @endif

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
                        <div x-data="{ open: false }" class="bg-white rounded-xl shadow-lg overflow-hidden mb-4">
                            <button 
                                @click="open = !open" 
                                class="w-full p-6 text-right hover:bg-gray-50 transition-colors"
                            >
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-semibold">{{ $faq->question }}</h3>
                                    <i 
                                        class="fas transition-transform" 
                                        :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"
                                    ></i>
                                </div>
                            </button>
                            <div x-show="open" x-collapse class="p-6 border-t border-gray-200">
                                <p class="text-gray-700 leading-relaxed">
                                    {{ $faq->answer }}
                                </p>
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
                    @foreach ($setting as $item)
                        <a href="tel:{{$item->phone}}" class="bg-blue-600 px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                            <i class="fas fa-phone ml-2"></i>تماس تلفنی
                        </a>
                        <a href="mailto:{{$item->email}}" class="bg-green-600 px-8 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                            <i class="fas fa-envelope ml-2"></i>ارسال ایمیل
                        </a>
                    @endforeach

                    <a href="{{route('dashboard')}}#tickets" class="bg-purple-600 px-8 py-3 rounded-lg hover:bg-purple-700 transition-colors font-semibold">
                        <i class="fas fa-ticket-alt ml-2"></i>ثبت تیکت
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/faq.js')}}"></script>
    <script src="{{asset('js/modal.js')}}"></script>

@endsection