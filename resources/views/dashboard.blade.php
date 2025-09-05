<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پنل کاربری - فروشگاه آنلاین</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Vazirmatn', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="https://images.pexels.com/photos/1181533/pexels-photo-1181533.jpeg?auto=compress&cs=tinysrgb&w=100" 
                         alt="لوگو فروشگاه" class="h-12 w-12 rounded-lg ml-3">
                    <h1 class="text-2xl font-bold text-gray-800">پنل کاربری</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-wallet ml-2"></i>
                        <span>موجودی: <span class="font-semibold text-green-600">250,000 تومان</span></span>
                    </div>
                    <div class="flex items-center">
                        <img src="https://images.pexels.com/photos/771742/pexels-photo-771742.jpeg?auto=compress&cs=tinysrgb&w=100" 
                             class="w-10 h-10 rounded-full ml-3">
                        <div>
                            <div class="text-sm font-semibold">{{ auth()->user()->name }}</div>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <a href="{{route('home')}}" class="text-blue-600 hover:text-blue-800 transition-colors" title="صفحه اصلی">
                            <i class="fas fa-home text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                    <nav class="space-y-2">
                        <a href="#" class="sidebar-item active flex items-center p-3 rounded-lg transition-colors" data-section="dashboard">
                            <i class="fas fa-tachometer-alt w-5 ml-3"></i>
                            <span>داشبورد</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="orders">
                            <i class="fas fa-shopping-bag w-5 ml-3"></i>
                            <span>سفارش‌های من</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="downloads">
                            <i class="fas fa-download w-5 ml-3"></i>
                            <span>دانلودها</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="tickets">
                            <i class="fas fa-ticket-alt w-5 ml-3"></i>
                            <span>تیکت‌های پشتیبانی</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="wallet">
                            <i class="fas fa-wallet w-5 ml-3"></i>
                            <span>کیف پول</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="profile">
                            <i class="fas fa-user w-5 ml-3"></i>
                            <span>ویرایش پروفایل</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="password">
                            <i class="fas fa-key w-5 ml-3"></i>
                            <span>تغییر رمز عبور</span>
                        </a>
                        <hr class="my-4">
                        <form method="POST" action="{{route('logout')}}" class="flex items-center p-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            @csrf
                            {{-- @method('DELETE') --}}
                            <button>
                                <i class="fas fa-sign-out-alt w-5 ml-3"></i>
                                خروج
                            </button>
                        </form>
                        
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <!-- Dashboard Section -->
                <div id="dashboard" class="content-section">
                    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">خوش آمدید احمد محمدی</h2>
                        
                        <!-- Stats Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            <div class="bg-blue-50 rounded-lg p-6 text-center">
                                <i class="fas fa-shopping-bag text-blue-600 text-3xl mb-3"></i>
                                <h3 class="text-2xl font-bold text-gray-800">12</h3>
                                <p class="text-gray-600">کل سفارش‌ها</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-6 text-center">
                                <i class="fas fa-download text-green-600 text-3xl mb-3"></i>
                                <h3 class="text-2xl font-bold text-gray-800">28</h3>
                                <p class="text-gray-600">فایل دانلود شده</p>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-6 text-center">
                                <i class="fas fa-wallet text-purple-600 text-3xl mb-3"></i>
                                <h3 class="text-2xl font-bold text-gray-800">250,000</h3>
                                <p class="text-gray-600">موجودی (تومان)</p>
                            </div>
                            <div class="bg-orange-50 rounded-lg p-6 text-center">
                                <i class="fas fa-ticket-alt text-orange-600 text-3xl mb-3"></i>
                                <h3 class="text-2xl font-bold text-gray-800">3</h3>
                                <p class="text-gray-600">تیکت‌های فعال</p>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-bold mb-4">آخرین فعالیت‌ها</h3>
                            <div class="space-y-4">
                                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                    <i class="fas fa-download text-blue-600 text-lg ml-4"></i>
                                    <div class="flex-1">
                                        <p class="font-semibold">دانلود Adobe Photoshop 2024</p>
                                        <p class="text-sm text-gray-500">2 ساعت پیش</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                    <i class="fas fa-shopping-cart text-green-600 text-lg ml-4"></i>
                                    <div class="flex-1">
                                        <p class="font-semibold">خرید دوره آموزش React</p>
                                        <p class="text-sm text-gray-500">1 روز پیش</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                    <i class="fas fa-ticket-alt text-orange-600 text-lg ml-4"></i>
                                    <div class="flex-1">
                                        <p class="font-semibold">ارسال تیکت پشتیبانی</p>
                                        <p class="text-sm text-gray-500">3 روز پیش</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders Section -->
                <div id="orders" class="content-section hidden">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">سفارش‌های من</h2>
                            <select class="border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none">
                                <option>همه سفارش‌ها</option>
                                <option>تکمیل شده</option>
                                <option>در انتظار پرداخت</option>
                                <option>لغو شده</option>
                            </select>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-center">
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center ml-4">
                                            <i class="fas fa-image text-gray-500 text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-lg">Adobe Photoshop 2024</h3>
                                            <p class="text-gray-600">کد سفارش: #ORD-001</p>
                                            <p class="text-sm text-gray-500">تاریخ: 1403/08/15</p>
                                        </div>
                                    </div>
                                    <div class="text-left">
                                        <span class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">تکمیل شده</span>
                                        <p class="text-xl font-bold text-gray-800 mt-2">2,500,000 تومان</p>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                        دانلود فایل‌ها
                                    </button>
                                    <button class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                                        جزئیات سفارش
                                    </button>
                                </div>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-center">
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center ml-4">
                                            <i class="fas fa-play text-gray-500 text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-lg">دوره کامل آموزش React</h3>
                                            <p class="text-gray-600">کد سفارش: #ORD-002</p>
                                            <p class="text-sm text-gray-500">تاریخ: 1403/08/14</p>
                                        </div>
                                    </div>
                                    <div class="text-left">
                                        <span class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">تکمیل شده</span>
                                        <p class="text-xl font-bold text-gray-800 mt-2">450,000 تومان</p>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                        مشاهده دوره
                                    </button>
                                    <button class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                                        جزئیات سفارش
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-8 flex justify-center">
                            <nav class="flex items-center space-x-2">
                                <button class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    قبلی
                                </button>
                                <button class="px-3 py-2 bg-blue-600 text-white rounded-lg">1</button>
                                <button class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">2</button>
                                <button class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    بعدی
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Downloads Section -->
                <div id="downloads" class="content-section hidden">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">لیست دانلودها</h2>
                        
                        <div class="space-y-4">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center ml-4">
                                            <i class="fas fa-file-archive text-blue-600 text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold">Adobe_Photoshop_2024.zip</h3>
                                            <p class="text-sm text-gray-500">2.8 GB - دانلود شده: 3 بار</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm text-gray-500">باقی مانده: 2 بار</span>
                                        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                            دانلود
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center ml-4">
                                            <i class="fas fa-file-video text-green-600 text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold">React_Course_Complete.zip</h3>
                                            <p class="text-sm text-gray-500">1.5 GB - دانلود شده: 1 بار</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm text-gray-500">باقی مانده: 4 بار</span>
                                        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                            دانلود
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tickets Section -->
                <div id="tickets" class="content-section hidden">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">تیکت‌های پشتیبانی</h2>
                            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors" onclick="showNewTicketModal()">
                                تیکت جدید
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="font-semibold text-lg">مشکل در دانلود فایل</h3>
                                        <p class="text-gray-600">تیکت #12345</p>
                                        <p class="text-sm text-gray-500">ایجاد شده: 1403/08/15 - 14:30</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="bg-yellow-100 text-yellow-800 text-sm px-3 py-1 rounded-full">در انتظار پاسخ</span>
                                        <button class="text-blue-600 hover:text-blue-800 transition-colors">مشاهده</button>
                                    </div>
                                </div>
                                <p class="text-gray-700 bg-gray-50 p-3 rounded-lg">سلام، من نمی‌تونم فایل Adobe Photoshop رو دانلود کنم. لطفاً کمک کنید.</p>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="font-semibold text-lg">درخواست بازگشت وجه</h3>
                                        <p class="text-gray-600">تیکت #12344</p>
                                        <p class="text-sm text-gray-500">ایجاد شده: 1403/08/13 - 10:15</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">بسته شده</span>
                                        <button class="text-blue-600 hover:text-blue-800 transition-colors">مشاهده</button>
                                    </div>
                                </div>
                                <p class="text-gray-700 bg-gray-50 p-3 rounded-lg">محصولی که خریدم با توضیحات مطابقت نداره.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wallet Section -->
                <div id="wallet" class="content-section hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Wallet Balance -->
                        <div class="bg-white rounded-xl shadow-lg p-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6">موجودی کیف پول</h2>
                            
                            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white mb-6">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-blue-100 mb-2">موجودی فعلی</p>
                                        <p class="text-3xl font-bold">250,000 تومان</p>
                                    </div>
                                    <i class="fas fa-wallet text-4xl opacity-50"></i>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <button class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                    شارژ کیف پول
                                </button>
                                <button class="w-full border border-gray-300 py-3 rounded-lg hover:bg-gray-50 transition-colors">
                                    تاریخچه تراکنش‌ها
                                </button>
                            </div>
                        </div>

                        <!-- Recent Transactions -->
                        <div class="bg-white rounded-xl shadow-lg p-8">
                            <h3 class="text-xl font-bold text-gray-800 mb-6">آخرین تراکنش‌ها</h3>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between items-center p-4 border border-gray-200 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center ml-3">
                                            <i class="fas fa-minus text-red-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold">خرید محصول</p>
                                            <p class="text-sm text-gray-500">1403/08/15</p>
                                        </div>
                                    </div>
                                    <p class="text-red-600 font-semibold">-2,500,000 تومان</p>
                                </div>
                                
                                <div class="flex justify-between items-center p-4 border border-gray-200 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center ml-3">
                                            <i class="fas fa-plus text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold">شارژ کیف پول</p>
                                            <p class="text-sm text-gray-500">1403/08/12</p>
                                        </div>
                                    </div>
                                    <p class="text-green-600 font-semibold">+3,000,000 تومان</p>
                                </div>
                                
                                <div class="flex justify-between items-center p-4 border border-gray-200 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center ml-3">
                                            <i class="fas fa-minus text-red-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold">خرید محصول</p>
                                            <p class="text-sm text-gray-500">1403/08/10</p>
                                        </div>
                                    </div>
                                    <p class="text-red-600 font-semibold">-450,000 تومان</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Section -->
                <div id="profile" class="content-section hidden">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">ویرایش پروفایل</h2>
                        
                        <form class="space-y-6">
                            <div class="flex items-center mb-8">
                                <img src="https://images.pexels.com/photos/771742/pexels-photo-771742.jpeg?auto=compress&cs=tinysrgb&w=200" 
                                     class="w-24 h-24 rounded-full ml-6">
                                <div>
                                    <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                        تغییر عکس پروفایل
                                    </button>
                                    <p class="text-sm text-gray-500 mt-2">فرمت‌های قابل قبول: JPG, PNG (حداکثر 2MB)</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">نام</label>
                                    <input type="text" value="احمد" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">نام خانوادگی</label>
                                    <input type="text" value="محمدی" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">ایمیل</label>
                                <input type="email" value="ahmad@example.com" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">شماره تماس</label>
                                <input type="tel" value="09123456789" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ تولد</label>
                                <input type="date" value="1370-05-15" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                            
                            <div class="flex gap-4">
                                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                                    ذخیره تغییرات
                                </button>
                                <button type="button" class="border border-gray-300 px-8 py-3 rounded-lg hover:bg-gray-50 transition-colors">
                                    لغو
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Password Section -->
                <div id="password" class="content-section hidden">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">تغییر رمز عبور</h2>
                        
                        <form class="space-y-6 max-w-md">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">رمز عبور فعلی</label>
                                <div class="relative">
                                    <input type="password" id="currentPassword" class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                    <button type="button" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600" onclick="togglePassword('currentPassword')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">رمز عبور جدید</label>
                                <div class="relative">
                                    <input type="password" id="newPassword" class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                    <button type="button" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600" onclick="togglePassword('newPassword')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="mt-2">
                                    <div class="text-xs text-gray-500 mb-1">
                                        رمز عبور باید حداقل 8 کاراکتر و شامل حروف و اعداد باشد
                                    </div>
                                    <div class="flex">
                                        <div class="flex-1 h-1 bg-gray-200 rounded-full ml-2">
                                            <div class="h-1 bg-red-500 rounded-full transition-all" style="width: 0%"></div>
                                        </div>
                                        <span class="text-xs text-gray-500">ضعیف</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تکرار رمز عبور جدید</label>
                                <div class="relative">
                                    <input type="password" id="confirmNewPassword" class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                    <button type="button" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600" onclick="togglePassword('confirmNewPassword')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex gap-4">
                                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                                    تغییر رمز عبور
                                </button>
                                <button type="button" class="border border-gray-300 px-8 py-3 rounded-lg hover:bg-gray-50 transition-colors">
                                    لغو
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Ticket Modal -->
    <div id="newTicketModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">ایجاد تیکت جدید</h3>
                <button onclick="hideNewTicketModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">موضوع</label>
                    <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">دسته‌بندی</label>
                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option>مشکل فنی</option>
                        <option>مسائل مالی</option>
                        <option>درخواست بازگشت وجه</option>
                        <option>سایر</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">پیام</label>
                    <textarea rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none"></textarea>
                </div>
                
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        ارسال تیکت
                    </button>
                    <button type="button" onclick="hideNewTicketModal()" class="flex-1 border border-gray-300 py-3 rounded-lg hover:bg-gray-50 transition-colors">
                        لغو
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{asset('js/dashboard.js')}}"></script>
</body>
</html>