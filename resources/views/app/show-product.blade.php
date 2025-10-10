@extends('app.layouts.master')

@section('title', $product->title)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
@endsection

@section('content')
    <!-- Breadcrumb -->
    <section class="bg-white py-4">
        <div class="container mx-auto px-4">
            <nav class="text-sm text-gray-600">
                <ol class="list-none p-0 inline-flex">
                    <li class="flex items-center">
                        <a href="{{ route('home') }}" class="hover:text-blue-600">خانه</a>
                        <i class="fas fa-chevron-left mx-2"></i>
                    </li>
                    <li class="flex items-center">
                        <a href="" class="hover:text-blue-600">{{ $product->category->name }}</a>
                        <i class="fas fa-chevron-left mx-2"></i>
                    </li>
                    <li class="text-gray-500">{{ $product->title }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Product Image -->
                <div>
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-center mb-6">
                            <img id="productImage" src="{{ asset($product->image_urls[0] ?? 'images/placeholder.jpg') }}" 
                                 alt="{{ $product->title }}" class="max-w-full h-80 object-contain rounded-lg">
                        </div>
                        <div class="flex gap-3 justify-center flex-wrap">
                            @foreach ($product->image_urls ?? [] as $index => $image)
                                <img src="{{ asset($image) }}" 
                                     data-index="{{ $index }}"
                                     class="w-16 h-16 rounded-lg cursor-pointer border-2 transition-all duration-200 thumbnail 
                                            {{ $index === 0 ? 'border-blue-500 scale-105' : 'border-gray-200 hover:border-blue-400' }}"
                                     onclick="changeImage(this)">
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div>
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <div class="flex items-center gap-2 mb-4">
                            @if ($product->availability)
                                <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">موجود</span>
                            @else 
                                <span class="bg-red-100 text-red-800 text-xs px-3 py-1 rounded-full">ناموجود</span>
                            @endif                            
                        </div>
                        
                        <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->title }}</h1>
                        
                        <div class="flex items-center gap-4 mb-6">
                            <div class="flex items-center text-yellow-500">
                                <span class="text-gray-600 mr-2">126 نظر</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center gap-4">
                                <span class="text-3xl font-bold text-green-600">{{ number_format($product->original_price) }} تومان</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-800 mb-3">ویژگی‌های کلیدی:</h3>
                            <ul class="space-y-2 text-gray-600">
                                @foreach ($product->key_features ?? [] as $item)
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 ml-2"></i>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <form action="{{ route('cart.add') }}" method="POST" class="flex gap-4 mb-6">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="number" name="quantity" value="1" min="1" class="w-20 p-2 border rounded">
                            <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-xl hover:bg-blue-700 transition-colors font-semibold {{ !$product->availability ? 'opacity-50 cursor-not-allowed' : '' }}" 
                                    {{ !$product->availability ? 'disabled' : '' }}>
                                <i class="fas fa-shopping-cart ml-2"></i>افزودن به سبد خرید
                            </button>
                        </form>

                        @if (session('success'))
                            <div class="text-green-600 mb-4">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="text-red-600 mb-4">{{ session('error') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tabs Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="bg-white rounded-xl shadow-lg">
                <div class="border-b">
                    <nav class="flex">
                        <button class="tab-button active px-8 py-4 font-semibold border-b-2 border-blue-500 text-blue-600" onclick="showTab('description')">
                            توضیحات
                        </button>
                        <button class="tab-button px-8 py-4 font-semibold text-gray-600 hover:text-blue-600 transition-colors" onclick="showTab('files')">
                            فایل‌های مرتبط
                        </button>
                        <button class="tab-button px-8 py-4 font-semibold text-gray-600 hover:text-blue-600 transition-colors" onclick="showTab('reviews')">
                            نظرات (126)
                        </button>
                    </nav>
                </div>

                <div class="p-8">
                    <div id="description" class="tab-content">
                        <h3 class="text-2xl font-bold mb-4">درباره {{ $product->title }}</h3>
                        <div class="prose max-w-none text-gray-700 leading-relaxed">
                            <p class="mb-4">{{ $product->description }}</p>
                        </div>
                    </div>

                    <div id="files" class="tab-content hidden">
                        <h3 class="text-2xl font-bold mb-6">فایل‌های قابل دانلود</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center ml-4">
                                        <i class="fas fa-file-archive text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold">{{ $product->title }}_Setup.zip</h4>
                                        <p class="text-sm text-gray-500">نسخه کامل نرم‌افزار - 2.8 GB</p>
                                    </div>
                                </div>
                                <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    دانلود
                                </button>
                            </div>
                            <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center ml-4">
                                        <i class="fas fa-key text-green-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold">License_Key.txt</h4>
                                        <p class="text-sm text-gray-500">کلید فعالسازی - 1 KB</p>
                                    </div>
                                </div>
                                <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    دانلود
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="reviews" class="tab-content hidden">
                        <!-- محتوای نظرات -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">محصولات مرتبط</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- محتوای محصولات مرتبط -->
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        function changeImage(clickedImage) {
            const mainImage = document.getElementById('productImage');
            mainImage.src = clickedImage.src;
            const thumbnails = document.querySelectorAll('.thumbnail');
            thumbnails.forEach(thumb => {
                thumb.classList.remove('border-blue-500', 'scale-105');
                thumb.classList.add('border-gray-200');
            });
            clickedImage.classList.remove('border-gray-200');
            clickedImage.classList.add('border-blue-500', 'scale-105');
            mainImage.style.opacity = '0.7';
            setTimeout(() => mainImage.style.opacity = '1', 150);
        }

        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-b-2', 'border-blue-500', 'text-blue-600');
                button.classList.add('text-gray-600');
            });
            document.getElementById(tabName).classList.remove('hidden');
            event.target.classList.add('active', 'border-b-2', 'border-blue-500', 'text-blue-600');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const firstThumbnail = document.querySelector('.thumbnail');
            if (firstThumbnail) {
                firstThumbnail.classList.add('border-blue-500', 'scale-105');
            }
        });
    </script>
@endsection