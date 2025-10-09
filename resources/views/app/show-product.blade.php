@extends('app.layouts.master')

@section('title','سوالات متداول')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/products.css')}}">
@endsection

@section('content')
    <!-- Breadcrumb -->
    <section class="bg-white py-4">
        <div class="container mx-auto px-4">
            <nav class="text-sm text-gray-600">
                <ol class="list-none p-0 inline-flex">
                    <li class="flex items-center">
                        <a href="{{route('home')}}" class="hover:text-blue-600">خانه</a>
                        <i class="fas fa-chevron-left mx-2"></i>
                    </li>
                    <li class="flex items-center">
                        <a href="category.html?cat=software" class="hover:text-blue-600">{{$product->category->name}}</a>
                        <i class="fas fa-chevron-left mx-2"></i>
                    </li>
                    <li class="text-gray-500">{{$product->title}}</li>
                </ol>
            </nav>
        </div>
    </section>
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Product Image -->
                <div>
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <img id="productImage" src="{{asset($product->image_urls[0])}}" 
                             alt="تصویر محصول" class="w-full rounded-lg">
                        <div class="flex gap-4 mt-6">
                            @foreach ($product->image_urls as $image)
                                <img src="{{asset($image)}}" 
                                class="w-20 h-20 rounded-lg cursor-pointer border-2 border-gray-200 hover:border-blue-500 transition-colors"
                                onclick="changeImage(this.src)">
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div>
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <div class="flex items-center gap-2 mb-4">
                            @if ($product->availability == 1)
                                <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">موجود</span>
                            @else 
                                <span class="bg-red-100 text-red-800 text-xs px-3 py-1 rounded-full">ناموجود</span>
                            @endif                            
                            <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">{{$tag->name}}</span>
                        </div>
                        
                        <h1 class="text-3xl font-bold text-gray-800 mb-4">{{$product->title}}</h1>
                        
                        <div class="flex items-center gap-4 mb-6">
                            <div class="flex items-center text-yellow-500">
                                <span class="text-gray-600 mr-2">126 نظر</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center gap-4">
                                <span class="text-3xl font-bold text-green-600">{{$product->original_price}}تومان</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-800 mb-3">ویژگی‌های کلیدی:</h3>
                            <ul class="space-y-2 text-gray-600">
                                <li class="flex items-center"><i class="fas fa-check text-green-500 ml-2"></i>ویرایش حرفه‌ای تصاویر</li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 ml-2"></i>امکانات هوش مصنوعی</li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 ml-2"></i>پشتیبانی از فرمت‌های مختلف</li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 ml-2"></i>آپدیت رایگان</li>
                            </ul>
                        </div>

                        <div class="flex gap-4 mb-6">
                            <button class="flex-1 bg-blue-600 text-white py-4 rounded-xl hover:bg-blue-700 transition-colors font-semibold">
                                <i class="fas fa-shopping-cart ml-2"></i>افزودن به سبد خرید
                            </button>
                        </div>

                        <div class="border-t pt-6">
                            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                                <div><span class="font-semibold">کد محصول:</span> PS2024-001</div>
                                <div><span class="font-semibold">سازنده:</span> Adobe</div>
                                <div><span class="font-semibold">زبان:</span> فارسی/انگلیسی</div>
                                <div><span class="font-semibold">نوع مجوز:</span> کامل</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tabs Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="bg-white rounded-xl shadow-lg">
                <!-- Tab Headers -->
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

                <!-- Tab Contents -->
                <div class="p-8">
                    <!-- Description Tab -->
                    <div id="description" class="tab-content">
                        <h3 class="text-2xl font-bold mb-4">درباره Adobe Photoshop 2024</h3>
                        <div class="prose max-w-none text-gray-700 leading-relaxed">
                            <p class="mb-4">
                                Adobe Photoshop 2024 جدیدترین نسخه از معروف‌ترین نرم‌افزار ویرایش تصاویر جهان است که با امکانات پیشرفته و قابلیت‌های جدید هوش مصنوعی، تجربه‌ای بی‌نظیر از ویرایش تصاویر ارائه می‌دهد.
                            </p>
                            <p class="mb-4">
                                این نسخه شامل ابزارهای جدید برای حذف پس‌زمینه، تغییر آسمان، ویرایش پرتره و بسیاری از قابلیت‌های دیگر است که کار شما را بسیار ساده‌تر می‌کند.
                            </p>
                            <h4 class="text-xl font-bold mb-3">امکانات جدید:</h4>
                            <ul class="list-disc pr-6 space-y-2">
                                <li>ابزارهای هوش مصنوعی برای ویرایش خودکار</li>
                                <li>فیلترهای جدید Neural</li>
                                <li>بهبود عملکرد و سرعت</li>
                                <li>رابط کاربری بهینه‌شده</li>
                                <li>پشتیبانی از فرمت‌های جدید</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Files Tab -->
                    <div id="files" class="tab-content hidden">
                        <h3 class="text-2xl font-bold mb-6">فایل‌های قابل دانلود</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center ml-4">
                                        <i class="fas fa-file-archive text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold">Adobe_Photoshop_2024_Setup.zip</h4>
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
                            
                            <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center ml-4">
                                        <i class="fas fa-book text-orange-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold">Installation_Guide.pdf</h4>
                                        <p class="text-sm text-gray-500">راهنمای نصب - 5.2 MB</p>
                                    </div>
                                </div>
                                <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    دانلود
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews Tab -->
                    <div id="reviews" class="tab-content hidden">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold">نظرات کاربران</h3>
                            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                                ثبت نظر جدید
                            </button>
                        </div>
                        
                        <!-- Review Summary -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-8">
                            <div class="flex items-center gap-8">
                                <div class="text-center">
                                    <div class="text-4xl font-bold text-gray-800 mb-2">4.8</div>
                                    <div class="flex text-yellow-500 mb-2">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="text-sm text-gray-500">126 نظر</div>
                                </div>
                                <div class="flex-1">
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm w-8">5</span>
                                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                                <div class="bg-yellow-500 h-2 rounded-full" style="width: 85%"></div>
                                            </div>
                                            <span class="text-sm text-gray-500">107</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm w-8">4</span>
                                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                                <div class="bg-yellow-500 h-2 rounded-full" style="width: 10%"></div>
                                            </div>
                                            <span class="text-sm text-gray-500">12</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm w-8">3</span>
                                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                                <div class="bg-yellow-500 h-2 rounded-full" style="width: 4%"></div>
                                            </div>
                                            <span class="text-sm text-gray-500">5</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm w-8">2</span>
                                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                                <div class="bg-yellow-500 h-2 rounded-full" style="width: 1%"></div>
                                            </div>
                                            <span class="text-sm text-gray-500">1</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm w-8">1</span>
                                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                                <div class="bg-yellow-500 h-2 rounded-full" style="width: 1%"></div>
                                            </div>
                                            <span class="text-sm text-gray-500">1</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Reviews List -->
                        <div class="space-y-6">
                            <div class="border-b pb-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <img src="https://images.pexels.com/photos/771742/pexels-photo-771742.jpeg?auto=compress&cs=tinysrgb&w=100" 
                                             class="w-12 h-12 rounded-full ml-3">
                                        <div>
                                            <h4 class="font-semibold">احمد محمدی</h4>
                                            <div class="flex text-yellow-500 text-sm">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-sm text-gray-500">2 روز پیش</span>
                                </div>
                                <p class="text-gray-700 leading-relaxed">
                                    نرم‌افزار فوق‌العاده‌ای است! امکانات جدید هوش مصنوعی واقعاً کار را راحت کرده. کیفیت بسیار بالا و نصب آسان. پیشنهاد می‌کنم.
                                </p>
                                <div class="flex items-center gap-4 mt-4">
                                    <button class="text-sm text-gray-500 hover:text-blue-600 transition-colors">
                                        <i class="fas fa-thumbs-up ml-1"></i>مفید (15)
                                    </button>
                                    <button class="text-sm text-gray-500 hover:text-red-600 transition-colors">
                                        <i class="fas fa-flag ml-1"></i>گزارش
                                    </button>
                                </div>
                            </div>
                            
                            <div class="border-b pb-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <img src="https://images.pexels.com/photos/415829/pexels-photo-415829.jpeg?auto=compress&cs=tinysrgb&w=100" 
                                             class="w-12 h-12 rounded-full ml-3">
                                        <div>
                                            <h4 class="font-semibold">مریم اکبری</h4>
                                            <div class="flex text-yellow-500 text-sm">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="far fa-star"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-sm text-gray-500">5 روز پیش</span>
                                </div>
                                <p class="text-gray-700 leading-relaxed">
                                    خریدم و راضی هستم. فقط سرعت نصب کمی کند بود اما در کل کیفیت خوبی داره. ابزارهای جدید خیلی کاربردی هستند.
                                </p>
                                <div class="flex items-center gap-4 mt-4">
                                    <button class="text-sm text-gray-500 hover:text-blue-600 transition-colors">
                                        <i class="fas fa-thumbs-up ml-1"></i>مفید (8)
                                    </button>
                                    <button class="text-sm text-gray-500 hover:text-red-600 transition-colors">
                                        <i class="fas fa-flag ml-1"></i>گزارش
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-8">
                            <button class="border border-gray-300 px-6 py-3 rounded-lg hover:bg-gray-50 transition-colors">
                                نمایش نظرات بیشتر
                            </button>
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
            <div id="relatedProducts" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <img src="${product.image}" alt="${product.title}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold mb-2 line-clamp-2">${product.title}</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-500 text-sm">
                                ${generateStars(product.rating)}
                            </div>
                            <span class="text-gray-500 text-sm mr-1">(${product.reviews})</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-lg font-bold text-green-600">${formatPrice(product.price)} تومان</span>
                            ${product.originalPrice ? `<br><span class="text-sm text-gray-500 line-through">${formatPrice(product.originalPrice)} تومان</span>` : ''}
                        </div>
                        <button onclick="window.location.href='product.html?id=${product.id}'" 
                                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            مشاهده محصول
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection