@extends('app.layouts.master')

@section('title','درباره ما')

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
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-700 py-16 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">درباره ما</h1>
                <p class="text-xl opacity-90">داستان شکل‌گیری و مسیر رشد فایل استور</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Introduction -->
                <div class="bg-white rounded-xl shadow-md p-8 mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">آغاز یک داستان موفق</h2>
                    <p class="text-gray-600 text-lg leading-relaxed mb-6 text-justify">
                        در سال 1399، آرش و امیرحسین با یک ایده ساده اما قدرتمند تصمیم گرفتند فروشگاه آنلاین خود را در قالب فروش پی‌دی‌اف کتاب راه‌اندازی کنند. آن‌ها باور داشتند که دانش باید به راحتی در دسترس همه قرار گیرد و کتاب‌های دیجیتال می‌توانند این امکان را فراهم کنند.
                    </p>
                    <div class="flex justify-center">
                        <div class="w-24 h-1 bg-blue-500 rounded-full"></div>
                    </div>
                </div>

                <!-- Founders Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="text-center mb-6">

                            <h3 class="text-xl font-bold text-gray-800">آرش</h3>
                            <p class="text-gray-600">مؤسس و مدیر فنی</p>
                        </div>
                        <p class="text-gray-600 text-justify">
                            آرش با تخصص در زمینه فناوری اطلاعات و برنامه‌نویسی، مسئولیت توسعه پلتفرم و اطمینان از عملکرد بهینه سایت را بر عهده دارد.
                        </p>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="text-center mb-6">
                            <h3 class="text-xl font-bold text-gray-800">امیرحسین</h3>
                            <p class="text-gray-600">مؤسس و مدیر محتوا</p>
                        </div>
                        <p class="text-gray-600 text-justify">
                            امیرحسین با دانش گسترده در حوزه برنامه‌نویسی و بازاریابی، مسئولیت انتخاب کتاب‌ها و ارتباط با ناشران و نویسندگان را بر عهده دارد.
                        </p>
                    </div>
                </div>

                <!-- Joint Responsibilities -->
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl shadow-md p-8 mb-12 border border-blue-100">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">مسئولیت‌های مشترک</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-start">
                            <div class="bg-blue-100 p-2 rounded-lg mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 mb-2">توسعه استراتژی کسب‌وکار</h3>
                                <p class="text-gray-600 text-sm">هر دو مؤسس در تدوین و اجرای استراتژی‌های کلان کسب‌وکار نقش فعالی دارند</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-purple-100 p-2 rounded-lg mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 mb-2">مدیریت تیم</h3>
                                <p class="text-gray-600 text-sm">رهبری و هدایت تیم‌های فنی و محتوایی به صورت مشترک انجام می‌شود</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-green-100 p-2 rounded-lg mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 mb-2">برنامه‌ریزی رشد</h3>
                                <p class="text-gray-600 text-sm">تعیین اهداف کوتاه‌مدت و بلندمدت و نظارت بر تحقق آن‌ها</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-yellow-100 p-2 rounded-lg mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 mb-2">نوآوری و توسعه</h3>
                                <p class="text-gray-600 text-sm">هر دو در شناسایی فرصت‌های جدید و توسعه خدمات نوین مشارکت دارند</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-xl shadow-md p-8 mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">مسیر رشد ما</h2>
                    
                    <div class="relative">
                        <!-- Timeline Line -->
                        <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-blue-200"></div>
                        
                        <!-- Timeline Items -->
                        <div class="space-y-12">
                            <!-- Item 1 -->
                            <div class="flex flex-col md:flex-row items-center">
                                <div class="md:w-1/2 md:pr-8 mb-4 md:mb-0 md:text-right">
                                    <h3 class="text-xl font-bold text-gray-800">شروع فعالیت</h3>
                                    <p class="text-gray-600 mt-2">سال 1399</p>
                                    <p class="text-gray-600 mt-2">آغاز فعالیت با مجموعه‌ای کوچک از کتاب‌های پی‌دی‌اف</p>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-blue-500 z-10"></div>
                                <div class="md:w-1/2 md:pl-8 mt-4 md:mt-0">
                                    <!-- Empty for alignment -->
                                </div>
                            </div>
                            
                            <!-- Item 2 -->
                            <div class="flex flex-col md:flex-row items-center">
                                <div class="md:w-1/2 md:pr-8 mb-4 md:mb-0 md:text-right">
                                    <!-- Empty for alignment -->
                                </div>
                                <div class="w-8 h-8 rounded-full bg-blue-500 z-10"></div>
                                <div class="md:w-1/2 md:pl-8 mt-4 md:mt-0">
                                    <h3 class="text-xl font-bold text-gray-800">افزایش محبوبیت</h3>
                                    <p class="text-gray-600 mt-2">سال 1400</p>
                                    <p class="text-gray-600 mt-2">رشد چشمگیر کاربران و اضافه شدن کتاب‌های جدید به مجموعه</p>
                                </div>
                            </div>
                            
                            <!-- Item 3 -->
                            <div class="flex flex-col md:flex-row items-center">
                                <div class="md:w-1/2 md:pr-8 mb-4 md:mb-0 md:text-right">
                                    <h3 class="text-xl font-bold text-gray-800">توسعه پلتفرم</h3>
                                    <p class="text-gray-600 mt-2">سال 1401</p>
                                    <p class="text-gray-600 mt-2">بهبود رابط کاربری و اضافه شدن قابلیت‌های جدید</p>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-blue-500 z-10"></div>
                                <div class="md:w-1/2 md:pl-8 mt-4 md:mt-0">
                                    <!-- Empty for alignment -->
                                </div>
                            </div>
                            
                            <!-- Item 4 -->
                            <div class="flex flex-col md:flex-row items-center">
                                <div class="md:w-1/2 md:pr-8 mb-4 md:mb-0 md:text-right">
                                    <!-- Empty for alignment -->
                                </div>
                                <div class="w-8 h-8 rounded-full bg-blue-500 z-10"></div>
                                <div class="md:w-1/2 md:pl-8 mt-4 md:mt-0">
                                    <h3 class="text-xl font-bold text-gray-800">دستاوردهای جدید</h3>
                                    <p class="text-gray-600 mt-2">سال 1402</p>
                                    <p class="text-gray-600 mt-2">همکاری با ناشران معتبر و ارائه بیش از 1000 عنوان کتاب</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mission & Vision -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="text-center mb-4">
                            <div class="w-16 h-16 bg-green-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">ماموریت ما</h3>
                        </div>
                        <p class="text-gray-600 text-justify">
                            ما باور داریم که دسترسی به دانش باید برای همه آسان و مقرون به صرفه باشد. ماموریت ما ارائه کتاب‌های دیجیتال با کیفیت بالا با قیمت مناسب و ایجاد تجربه‌ای لذت‌بخش برای مطالعه است.
                        </p>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="text-center mb-4">
                            <div class="w-16 h-16 bg-yellow-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">چشم‌انداز ما</h3>
                        </div>
                        <p class="text-gray-600 text-justify">
                            چشم‌انداز ما تبدیل شدن به بزرگ‌ترین پلتفرم فروش کتاب‌های دیجیتال در ایران و ارائه خدمات نوآورانه برای بهبود تجربه مطالعه دیجیتال است.
                        </p>
                    </div>
                </div>

                <!-- Stats -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl shadow-md p-8 text-white text-center">
                    <h2 class="text-3xl font-bold mb-8">دستاوردهای ما در یک نگاه</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <div class="text-3xl md:text-4xl font-bold mb-2">3+</div>
                            <div class="text-lg">سال تجربه</div>
                        </div>
                        <div>
                            <div class="text-3xl md:text-4xl font-bold mb-2">1000+</div>
                            <div class="text-lg">عنوان کتاب</div>
                        </div>
                        <div>
                            <div class="text-3xl md:text-4xl font-bold mb-2">50k+</div>
                            <div class="text-lg">کاربر راضی</div>
                        </div>
                        <div>
                            <div class="text-3xl md:text-4xl font-bold mb-2">98%</div>
                            <div class="text-lg">رضایت‌مندی</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{asset('js/faq.js')}}"></script>
    <script src="{{asset('js/modal.js')}}"></script>
@endsection