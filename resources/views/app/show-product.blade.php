@extends('app.layouts.master')

@section('title', $product->title)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
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
                        <a href="{{ route('home') }}" class="hover:text-blue-600">خانه</a>
                        <i class="fas fa-chevron-left mx-2"></i>
                    </li>
                    <li class="flex items-center">
                        <a href="{{ route('productsWithCategory', ['category' => $product->category->id]) }}" class="hover:text-blue-600">{{ $product->category->name }}</a>
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
                            نظرات
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

                        @php
                        $hasPurchased = auth()->check() && 
                                        \App\Models\Payment::where('user_id', auth()->id())
                                            ->whereHas('order.items', function($query) use ($product) {
                                                $query->where('product_id', $product->id);
                                            })
                                            ->where('status', 'completed')
                                            ->exists();
                    @endphp
                    
                    @if($hasPurchased)
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
                                    <a href="" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                        دانلود
                                    </a>
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
                                    <a href="" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                        دانلود
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- کاربر نخریده -->
                        <div id="files" class="tab-content hidden">
                            <div class="text-center py-12">
                                @auth
                                    <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <i class="fas fa-lock text-yellow-600 text-3xl"></i>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-800 mb-4">دسترسی محدود</h3>
                                    <p class="text-gray-600 mb-6 max-w-md mx-auto">
                                        برای دسترسی به فایل‌های دانلودی، باید این محصول را خریداری کرده باشید.
                                    </p>
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 max-w-md mx-auto">
                                        <h4 class="font-semibold text-blue-800 mb-3">شما این محصول را خریداری نکرده‌اید</h4>
                                        <p class="text-blue-700 text-sm mb-4">
                                            پس از خرید محصول، فایل‌های دانلودی در این بخش در دسترس شما قرار خواهند گرفت.
                                        </p>
                                        <form action="{{ route('cart.add') }}" method="POST" class="flex gap-2">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" 
                                                    class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold {{ !$product->availability ? 'opacity-50 cursor-not-allowed' : '' }}" 
                                                    {{ !$product->availability ? 'disabled' : '' }}>
                                                <i class="fas fa-shopping-cart ml-2"></i>خرید محصول
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <i class="fas fa-user-lock text-yellow-600 text-3xl"></i>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-800 mb-4">لطفاً وارد شوید</h3>
                                    <p class="text-gray-600 mb-6 max-w-md mx-auto">
                                        برای مشاهده وضعیت خرید و دسترسی به فایل‌ها، باید وارد حساب کاربری خود شوید.
                                    </p>
                                    <div class="flex gap-3 justify-center">
                                        <a href="{{ route('login') }}" class="border border-blue-600 text-blue-600 px-6 py-3 rounded-lg hover:bg-blue-50 transition-colors">
                                            ورود
                                        </a>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    @endif

                    <div id="reviews" class="tab-content hidden">
                        <h3 class="text-2xl font-bold mb-6">نظرات کاربران</h3>
                        
                        @auth
                            <div class="bg-blue-50 rounded-lg p-6 mb-8">
                                <h4 class="text-xl font-bold mb-4">ثبت نظر شما</h4>

                                <form action="{{ route('review.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <div class="mb-4">
                                        <label class="block text-gray-700 font-semibold mb-2">نظر شما:</label>
                                        <textarea name="body" rows="5" 
                                                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                                placeholder="نظر خود را درباره این محصول بنویسید... (حداقل 10 کاراکتر)"
                                                required>{{ old('body') }}</textarea>
                                        @error('body')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                                        <i class="fas fa-paper-plane ml-2"></i>ارسال نظر
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8 text-center">
                                <i class="fas fa-info-circle text-yellow-600 text-3xl mb-3"></i>
                                <p class="text-gray-700 mb-4">برای ثبت نظر ابتدا باید وارد حساب کاربری خود شوید</p>
                                <a href="{{ route('login') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                                    ورود به حساب کاربری
                                </a>
                            </div>
                        @endauth

                        <!-- Reviews List -->
                        <div class="space-y-6">
                            @forelse($product->approvedReviews()->latest()->get() as $review)
                                <div class="border rounded-lg p-6 hover:shadow-md transition">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h5 class="font-bold text-gray-800">{{ $review->user->name ?? 'کاربر' }}</h5>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-sm text-gray-500">
                                                    {{ \Morilog\Jalali\Jalalian::forge($review->created_at)->format('Y/m/d') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <p class="text-gray-700 leading-relaxed mb-4">{{ $review->body }}</p>
                                    
                                    @auth
                                        <div class="flex gap-4 text-sm">
                                            <form action="{{ route('review.helpful', $review->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-gray-600 hover:text-green-600 transition">
                                                    <i class="far fa-thumbs-up ml-1"></i>
                                                    مفید بود ({{ $review->helpful_count }})
                                                </button>
                                            </form>
                                            <form action="{{ route('review.report', $review->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-gray-600 hover:text-red-600 transition"
                                                        >
                                                    <i class="far fa-flag ml-1"></i>گزارش
                                                </button>
                                            </form>
                                        </div>
                                    @endauth
                                </div>
                            @empty
                                <div class="text-center py-12 text-gray-500">
                                    <i class="fas fa-comments text-5xl mb-4"></i>
                                    <p>هنوز نظری ثبت نشده است. اولین نفر باشید!</p>
                                </div>
                            @endforelse
                        </div>
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
                @foreach($relatedProducts as $relatedProduct)
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                        <div class="relative overflow-hidden">
                            <img src="{{ asset($relatedProduct->image_urls[0] ?? 'images/placeholder.jpg') }}" 
                                alt="{{ $relatedProduct->title }}"
                                class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute top-3 left-3">
                                @if($relatedProduct->availability)
                                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">موجود</span>
                                @else
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">ناموجود</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-bold text-gray-800 mb-2 line-clamp-2 h-12">
                                <a href="{{ route('show-product', $relatedProduct->id) }}" 
                                class="hover:text-blue-600 transition-colors">
                                    {{ $relatedProduct->title }}
                                </a>
                            </h3>
                            
                            <div class="flex items-center justify-between mt-3">
                                <span class="text-lg font-bold text-green-600">
                                    {{ number_format($relatedProduct->original_price) }} تومان
                                </span>
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('show-product', $relatedProduct->id) }}" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center gap-2">
                                    <i class="fas fa-eye text-xs"></i>
                                    مشاهده محصول
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                @if($relatedProducts->isEmpty())
                    <div class="col-span-full text-center py-8">
                        <i class="fas fa-box-open text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500 text-lg">محصول مرتبطی یافت نشد</p>
                    </div>
                @endif
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
            const activeTab = document.getElementById(tabName);
            if (activeTab) {
                activeTab.classList.remove('hidden');
                const activeButton = document.querySelector(`button[onclick="showTab('${tabName}')"]`);
                if (activeButton) {
                    activeButton.classList.add('active', 'border-b-2', 'border-blue-500', 'text-blue-600');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const firstThumbnail = document.querySelector('.thumbnail');
            if (firstThumbnail) {
                firstThumbnail.classList.add('border-blue-500', 'scale-105');
            }

            // چک کردن sessionStorage یا URL برای فعال کردن تب
            const savedTab = sessionStorage.getItem('activeTab') || window.location.hash.replace('#', '');
            const tabToShow = (savedTab === 'reviews' || savedTab === 'description' || savedTab === 'files') ? savedTab : 'description';
            showTab(tabToShow);

            // اضافه کردن #reviews به فرم ثبت نظر
            const reviewForm = document.querySelector('form[action="{{ route('review.store') }}"]');
            if (reviewForm) {
                reviewForm.addEventListener('submit', function() {
                    sessionStorage.setItem('activeTab', 'reviews'); // ذخیره تب فعال
                    window.location.hash = 'reviews'; // اضافه کردن #reviews به URL
                });
            }
        });
    </script>
    <script src="{{ asset('js/modal.js') }}"></script>
@endsection