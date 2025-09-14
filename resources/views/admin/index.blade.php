<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پنل مدیریت - فروشگاه آنلاین</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <style>


            /* Persian Date Picker Styles */
            .calendar-day {
                @apply w-8 h-8 flex items-center justify-center text-sm cursor-pointer rounded-lg transition-all;
            }
            
            .calendar-day:hover {
                @apply bg-blue-100 text-blue-600;
            }
            
            .calendar-day.selected {
                @apply bg-blue-500 text-white font-bold;
            }
            
            .calendar-day.today {
                @apply bg-green-100 text-green-700 font-bold;
            }
            
            .calendar-day.other-month {
                @apply text-gray-400 cursor-not-allowed;
            }
            
            .calendar-day.disabled {
                @apply text-gray-300 cursor-not-allowed;
            }
            
            /* Persian Date Picker Animation */
            #persianDatePicker {
                backdrop-filter: blur(4px);
            }
            
            #persianDatePicker .bg-white {
                animation: fadeInUp 0.3s ease;
            }
            
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>    
</head>
<body class="font-sans bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="https://images.pexels.com/photos/1181533/pexels-photo-1181533.jpeg?auto=compress&cs=tinysrgb&w=100" 
                         alt="لوگو فروشگاه" class="h-12 w-12 rounded-lg ml-3">
                    <h1 class="text-2xl font-bold text-gray-800">پنل مدیریت</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-crown ml-2 text-yellow-500"></i>
                        <span>مدیر: <span class="font-semibold text-purple-600">{{ auth()->user()->name }}</span></span>
                    </div>
                    <div class="flex items-center">
                        <img src="https://images.pexels.com/photos/771742/pexels-photo-771742.jpeg?auto=compress&cs=tinysrgb&w=100" 
                             class="w-10 h-10 rounded-full ml-3">
                    </div>
                    <a href="{{route('home')}}" class="text-blue-600 hover:text-blue-800 transition-colors">
                        <i class="fas fa-home text-xl"></i>
                    </a>
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
                            <i class="fas fa-chart-pie w-5 ml-3"></i>
                            <span>داشبورد</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="products">
                            <i class="fas fa-box w-5 ml-3"></i>
                            <span>مدیریت محصولات</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="product-files">
                            <i class="fas fa-folder-open w-5 ml-3"></i>
                            <span>فایل‌های محصولات</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="categories">
                            <i class="fas fa-tags w-5 ml-3"></i>
                            <span>دسته‌بندی‌ها</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="tags">
                            <i class="fas fa-tag w-5 ml-3"></i>
                            <span>برچسب‌ها</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="coupons">
                            <i class="fas fa-percent w-5 ml-3"></i>
                            <span>کدهای تخفیف</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="comments">
                            <i class="fas fa-comments w-5 ml-3"></i>
                            <span>نظرات</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="menus">
                            <i class="fas fa-bars w-5 ml-3"></i>
                            <span>منوهای سایت</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="settings">
                            <i class="fas fa-cogs w-5 ml-3"></i>
                            <span>تنظیمات سایت</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="payments">
                            <i class="fas fa-credit-card w-5 ml-3"></i>
                            <span>پرداخت‌ها</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="tickets">
                            <i class="fas fa-ticket-alt w-5 ml-3"></i>
                            <span>تیکت‌های پشتیبانی</span>
                        </a>
                        <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="faq">
                            <i class="fas fa-question-circle w-5 ml-3"></i>
                            <span>سوالات متداول</span>
                        </a>
                        <hr class="my-4">
                        <a href="auth.html" class="flex items-center p-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <i class="fas fa-sign-out-alt w-5 ml-3"></i>
                            <span>خروج</span>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <!-- Dashboard Section -->
                <div id="dashboard" class="content-section">
                    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">داشبورد مدیریت</h2>
                        
                        <!-- Stats Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            <div class="bg-blue-50 rounded-lg p-6 text-center">
                                <i class="fas fa-box text-blue-600 text-3xl mb-3"></i>
                                <h3 class="text-2xl font-bold text-gray-800">127</h3>
                                <p class="text-gray-600">کل محصولات</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-6 text-center">
                                <i class="fas fa-shopping-bag text-green-600 text-3xl mb-3"></i>
                                <h3 class="text-2xl font-bold text-gray-800">1,543</h3>
                                <p class="text-gray-600">کل فروش‌ها</p>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-6 text-center">
                                <i class="fas fa-users text-purple-600 text-3xl mb-3"></i>
                                <h3 class="text-2xl font-bold text-gray-800">12,847</h3>
                                <p class="text-gray-600">کاربران</p>
                            </div>
                            <div class="bg-orange-50 rounded-lg p-6 text-center">
                                <i class="fas fa-dollar-sign text-orange-600 text-3xl mb-3"></i>
                                <h3 class="text-2xl font-bold text-gray-800">۴۵,۶۷۸,۹۰۰</h3>
                                <p class="text-gray-600">درآمد (تومان)</p>
                            </div>
                        </div>

                        <!-- Charts Section -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="bg-white border rounded-xl p-6">
                                <h3 class="text-lg font-bold mb-4">نمودار فروش ماهانه</h3>
                                <canvas id="monthlySalesChart"></canvas>
                            </div>
                            <div class="bg-white border rounded-xl p-6">
                                <h3 class="text-lg font-bold mb-4">محصولات پرفروش</h3>
                                <canvas id="topProductsChart"></canvas>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="border-t pt-6 mt-8">
                            <h3 class="text-lg font-bold mb-4">آخرین فعالیت‌ها</h3>
                            <div class="space-y-4">
                                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                    <i class="fas fa-plus text-green-600 text-lg ml-4"></i>
                                    <div class="flex-1">
                                        <p class="font-semibold">محصول جدید اضافه شد: Adobe Illustrator 2024</p>
                                        <p class="text-sm text-gray-500">5 دقیقه پیش</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                    <i class="fas fa-shopping-cart text-blue-600 text-lg ml-4"></i>
                                    <div class="flex-1">
                                        <p class="font-semibold">سفارش جدید: #ORD-1547 - 2,500,000 تومان</p>
                                        <p class="text-sm text-gray-500">12 دقیقه پیش</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                    <i class="fas fa-ticket-alt text-orange-600 text-lg ml-4"></i>
                                    <div class="flex-1">
                                        <p class="font-semibold">تیکت جدید: مشکل در دانلود فایل</p>
                                        <p class="text-sm text-gray-500">25 دقیقه پیش</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Management Section -->
                <div id="products" class="content-section hidden">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">مدیریت محصولات</h2>
                            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors" onclick="showAddProductModal()">
                                <i class="fas fa-plus ml-2"></i>افزودن محصول
                            </button>
                        </div>
                        
                        <!-- Search and Filter -->
                        <div class="flex flex-col md:flex-row gap-4 mb-6">
                            <div class="flex-1">
                                <input type="text" id="productSearch" placeholder="جستجوی محصولات..." 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                            </div>
                            <select class="border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:outline-none">
                                <option>همه دسته‌ها</option>
                                <option>نرم‌افزارها</option>
                                <option>دوره‌های آموزشی</option>
                                <option>کتاب‌های الکترونیکی</option>
                                <option>قالب‌ها</option>
                            </select>
                            <select class="border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:outline-none">
                                <option>همه وضعیت‌ها</option>
                                <option>فعال</option>
                                <option>غیرفعال</option>
                                <option>پیش‌نویس</option>
                            </select>
                        </div>
                        
                        <!-- Products Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-right p-4">تصویر</th>
                                        <th class="text-right p-4">نام محصول</th>
                                        <th class="text-right p-4">نوع</th>
                                        <th class="text-right p-4">قیمت</th>
                                        <th class="text-right p-4">قیمت با تخفیف</th>
                                        <th class="text-right p-4">وضعیت</th>
                                        <th class="text-right p-4">وضعیت فروش</th>
                                        <th class="text-right p-4">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody id="productsTableBody">
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-4">
                                            <img src="https://images.pexels.com/photos/4348401/pexels-photo-4348401.jpeg?auto=compress&cs=tinysrgb&w=100" 
                                                 class="w-16 h-16 rounded-lg object-cover">
                                        </td>
                                        <td class="p-4 font-semibold">Adobe Photoshop 2024</td>
                                        <td class="p-4">نرم‌افزار</td>
                                        <td class="p-4">3,000,000 تومان</td>
                                        <td class="p-4">2,500,000 تومان</td>
                                        <td class="p-4">
                                            <span class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">فعال</span>
                                        </td>
                                        <td class="p-4">
                                            <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">در دسترس</span>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex gap-2">
                                                <button class="text-blue-600 hover:text-blue-800" onclick="editProduct(1)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="text-red-600 hover:text-red-800" onclick="deleteProduct(1)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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

                <!-- Product Files Management Section -->
                <div id="product-files" class="content-section hidden">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">مدیریت فایل‌های محصولات</h2>
                            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors" onclick="showAddFileModal()">
                                <i class="fas fa-plus ml-2"></i>افزودن فایل
                            </button>
                        </div>

                        <!-- Files List -->
                        <div class="space-y-4">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center ml-4">
                                            <i class="fas fa-file-archive text-blue-600 text-2xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-lg">Adobe_Photoshop_2024_Setup.zip</h3>
                                            <p class="text-sm text-gray-500">محصول: Adobe Photoshop 2024</p>
                                            <p class="text-sm text-gray-500">حجم: 2.8 GB - نوع: zip</p>
                                            <p class="text-sm text-gray-500">آدرس: /files/products/photoshop2024/</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button class="text-blue-600 hover:text-blue-800 px-4 py-2 border border-blue-600 rounded-lg">
                                            <i class="fas fa-download ml-1"></i>دانلود
                                        </button>
                                        <button class="text-green-600 hover:text-green-800" onclick="editFile(1)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800" onclick="deleteFile(1)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categories Management Section -->
                <div id="categories" class="content-section hidden">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">مدیریت دسته‌بندی‌ها</h2>
                            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors" onclick="showAddCategoryModal()">
                                <i class="fas fa-plus ml-2"></i>افزودن دسته‌بندی
                            </button>
                        </div>
                        
                        <!-- Categories Grid -->
                        <div id="categoriesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Categories will be loaded by JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Tags Management Section -->
                <div id="tags" class="content-section hidden">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">مدیریت برچسب‌ها</h2>
                            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors" onclick="showAddTagModal()">
                                <i class="fas fa-plus ml-2"></i>افزودن برچسب
                            </button>
                        </div>
                        
                        <!-- Tags Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نام برچسب</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">شناسه</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رنگ</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تعداد محصولات</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody id="tagsTableBody" class="bg-white divide-y divide-gray-200">
                                    <!-- Tags will be loaded by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Coupons Management Section -->
                <div id="coupons" class="content-section hidden">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">مدیریت کدهای تخفیف</h2>
                            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors" onclick="showAddCouponModal()">
                                <i class="fas fa-plus ml-2"></i>ایجاد کد تخفیف
                            </button>
                        </div>
                        
                        <!-- Coupons Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">کد تخفیف</th>
                                        <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">نوع</th>
                                        <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">مقدار</th>
                                        <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">حداقل خرید</th>
                                        <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">تاریخ انقضا</th>
                                        <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">استفاده</th>
                                        <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">وضعیت</th>
                                        <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody id="couponsTableBody" class="bg-white divide-y divide-gray-200">
                                    <!-- Coupons will be loaded by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Comments Management Section -->
                <div id="comments" class="content-section hidden">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">مدیریت نظرات</h2>
                            <div class="flex gap-3">
                                <select class="border border-gray-300 rounded-lg px-4 py-2">
                                    <option>همه نظرات</option>
                                    <option>تایید شده</option>
                                    <option>در انتظار تایید</option>
                                    <option>رد شده</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Comments List -->
                        <div class="space-y-4">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-center">
                                        <img src="https://images.pexels.com/photos/771742/pexels-photo-771742.jpeg?auto=compress&cs=tinysrgb&w=100" 
                                             class="w-12 h-12 rounded-full ml-3">
                                        <div>
                                            <h4 class="font-semibold">احمد محمدی</h4>
                                            <p class="text-sm text-gray-500">محصول: Adobe Photoshop 2024</p>
                                            <div class="flex text-yellow-500 text-sm mt-1">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button class="bg-green-100 text-green-800 px-3 py-1 rounded text-sm">تایید</button>
                                        <button class="bg-red-100 text-red-800 px-3 py-1 rounded text-sm">رد</button>
                                        <button class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <p class="text-gray-700">نرم‌افزار فوق‌العاده‌ای است! امکانات جدید هوش مصنوعی واقعاً کار را راحت کرده.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Site Menus Section -->
                <div id="menus" class="content-section hidden">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">مدیریت منوهای سایت</h2>
                            <div class="flex gap-2">
                                <button onclick="
                                    console.log('🔍 تست عناصر منو...');
                                    console.log('menusTableBody:', document.getElementById('menusTableBody'));
                                    console.log('currentMenuPreview:', document.getElementById('currentMenuPreview'));
                                    console.log('addMenuModal:', document.getElementById('addMenuModal'));
                                    console.log('sampleMenus:', window.sampleMenus || 'تعریف نشده');
                                " class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded-lg transition-colors text-sm font-semibold">
                                    🔍 تست
                                </button>
                                <button onclick="console.log('🔄 تست بارگذاری منوها...'); loadStoredMenus(); loadMenus();" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg transition-colors text-sm font-semibold">
                                    🔄 بارگذاری
                                </button>
                                <button onclick="
                                    console.log('🗑️ ریست منوها...');
                                    localStorage.removeItem('adminMenus');
                                    location.reload();
                                " class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg transition-colors text-sm font-semibold">
                                    🗑️ ریست
                                </button>
                                <button onclick="
                                    console.log('🎯 دکمه افزودن منو کلیک شد');
                                    const modal = document.getElementById('addMenuModal');
                                    if (modal) {
                                        modal.classList.remove('hidden');
                                        console.log('✅ Modal نمایش داده شد');
                                    } else {
                                        alert('❌ Modal پیدا نشد!');
                                    }
                                " class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors font-semibold">
                                    <i class="fas fa-plus ml-1"></i>افزودن آیتم منو
                                </button>
                            </div>
                        </div>
                        
                        <!-- Current Menus Display -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">منوی فعلی سایت</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div id="currentMenuPreview" class="flex flex-wrap gap-2">
                                    <!-- Menu preview will be populated by JavaScript -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- Menu Items Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ترتیب</th>
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نام منو</th>
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">لینک</th>
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">آیکون</th>
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">وضعیت</th>
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody id="menusTableBody" class="bg-white divide-y divide-gray-200">
                                    <!-- Menu items will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Payments Section -->
                <div id="payments" class="content-section hidden">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">مدیریت پرداخت‌ها</h2>
                            <div class="flex gap-2">
                                <button onclick="exportPaymentsToCSV()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg transition-colors font-semibold">
                                    <i class="fas fa-download ml-1"></i>دانلود CSV
                                </button>
                                <button onclick="showPaymentStatsModal()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg transition-colors font-semibold">
                                    <i class="fas fa-chart-bar ml-1"></i>آمار تفصیلی
                                </button>
                            </div>
                        </div>
                        
                        <!-- Payment Stats -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                            <div class="bg-green-50 rounded-lg p-6 text-center">
                                <i class="fas fa-check-circle text-green-600 text-3xl mb-3"></i>
                                <h3 class="text-2xl font-bold text-gray-800" id="successfulPayments">0</h3>
                                <p class="text-gray-600">پرداخت موفق</p>
                            </div>
                            <div class="bg-red-50 rounded-lg p-6 text-center">
                                <i class="fas fa-times-circle text-red-600 text-3xl mb-3"></i>
                                <h3 class="text-2xl font-bold text-gray-800" id="failedPayments">0</h3>
                                <p class="text-gray-600">پرداخت ناموفق</p>
                            </div>
                            <div class="bg-yellow-50 rounded-lg p-6 text-center">
                                <i class="fas fa-clock text-yellow-600 text-3xl mb-3"></i>
                                <h3 class="text-2xl font-bold text-gray-800" id="pendingPayments">0</h3>
                                <p class="text-gray-600">در انتظار</p>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-6 text-center">
                                <i class="fas fa-money-bill-wave text-blue-600 text-3xl mb-3"></i>
                                <h3 class="text-2xl font-bold text-gray-800" id="totalRevenue">0</h3>
                                <p class="text-gray-600">کل درآمد (تومان)</p>
                            </div>
                        </div>
                        
                        <!-- Payments Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">شناسه پرداخت</th>
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">کاربر</th>
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">مبلغ</th>
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">درگاه</th>
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">وضعیت</th>
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاریخ</th>
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody id="paymentsTableBody" class="bg-white divide-y divide-gray-200">
                                    <!-- Payments will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Support Tickets Section -->
                <div id="tickets" class="content-section hidden">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">مدیریت تیکت‌های پشتیبانی</h2>
                            <div class="flex gap-2">
                                <button onclick="showAddTicketModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors font-semibold">
                                    <i class="fas fa-plus ml-1"></i>ایجاد تیکت جدید
                                </button>
                            </div>
                        </div>
                        
                        <!-- Ticket Stats -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                            <div class="bg-blue-50 rounded-lg p-6 text-center">
                                <i class="fas fa-ticket-alt text-blue-600 text-3xl mb-3"></i>
                                <h3 class="text-2xl font-bold text-gray-800" id="totalTickets">0</h3>
                                <p class="text-gray-600">کل تیکت‌ها</p>
                            </div>
                            <div class="bg-yellow-50 rounded-lg p-6 text-center">
                                <i class="fas fa-clock text-yellow-600 text-3xl mb-3"></i>
                                <h3 class="text-2xl font-bold text-gray-800" id="openTickets">0</h3>
                                <p class="text-gray-600">باز</p>
                            </div>
                            <div class="bg-red-50 rounded-lg p-6 text-center">
                                <i class="fas fa-exclamation-triangle text-red-600 text-3xl mb-3"></i>
                                <h3 class="text-2xl font-bold text-gray-800" id="urgentTickets">0</h3>
                                <p class="text-gray-600">فوری</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-6 text-center">
                                <i class="fas fa-check-circle text-green-600 text-3xl mb-3"></i>
                                <h3 class="text-2xl font-bold text-gray-800" id="closedTickets">0</h3>
                                <p class="text-gray-600">بسته شده</p>
                            </div>
                        </div>
                        
                        <!-- Tickets Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">شناسه</th>
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">کاربر</th>
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">موضوع</th>
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">دسته‌بندی</th>
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اولویت</th>
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">وضعیت</th>
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاریخ ایجاد</th>
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody id="ticketsTableBody" class="bg-white divide-y divide-gray-200">
                                    <!-- Tickets will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div id="faq" class="content-section hidden">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">مدیریت سوالات متداول</h2>
                            <button onclick="showAddFaqModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors font-semibold">
                                <i class="fas fa-plus ml-1"></i>افزودن سوال جدید
                            </button>
                        </div>
                        
                        <!-- FAQ Stats -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="bg-blue-50 rounded-lg p-6 text-center">
                                <i class="fas fa-question-circle text-blue-600 text-3xl mb-3"></i>
                                <h3 class="text-2xl font-bold text-gray-800" id="totalFaqs">0</h3>
                                <p class="text-gray-600">کل سوالات</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-6 text-center">
                                <i class="fas fa-eye text-green-600 text-3xl mb-3"></i>
                                <h3 class="text-2xl font-bold text-gray-800" id="publishedFaqs">0</h3>
                                <p class="text-gray-600">منتشر شده</p>
                            </div>
                            <div class="bg-yellow-50 rounded-lg p-6 text-center">
                                <i class="fas fa-edit text-yellow-600 text-3xl mb-3"></i>
                                <h3 class="text-2xl font-bold text-gray-800" id="draftFaqs">0</h3>
                                <p class="text-gray-600">پیش‌نویس</p>
                            </div>
                        </div>
                        
                        <!-- FAQ Items -->
                        <div id="faqAccordion" class="space-y-4">
                            <!-- FAQ items will be populated by JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Site Settings Section -->
                <div id="settings" class="content-section hidden">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">تنظیمات سایت</h2>
                        
                        <form class="space-y-6">
                            <!-- Logo and Icon -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">لوگوی سایت</label>
                                    <div class="flex items-center gap-4">
                                        <img src="https://images.pexels.com/photos/1181533/pexels-photo-1181533.jpeg?auto=compress&cs=tinysrgb&w=100" 
                                             class="w-16 h-16 rounded-lg">
                                        <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                            تغییر لوگو
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">آیکون سایت</label>
                                    <div class="flex items-center gap-4">
                                        <img src="/vite.svg" class="w-8 h-8">
                                        <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                            تغییر آیکون
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Site Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">عنوان سایت</label>
                                    <input type="text" value="فروشگاه آنلاین" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">شعار سایت</label>
                                    <input type="text" value="بهترین فروشگاه دیجیتال" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات سایت</label>
                                <textarea rows="4" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">بهترین و معتبرترین فروشگاه محصولات دیجیتال در ایران</textarea>
                            </div>

                            <!-- Contact Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">شماره تماس</label>
                                    <input type="text" value="021-12345678" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">ایمیل</label>
                                    <input type="email" value="info@shop.com" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">آدرس</label>
                                <input type="text" value="تهران، خیابان آزادی" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                                ذخیره تغییرات
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Other sections will be added in the next part due to length -->
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div id="addProductModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-8 max-w-4xl w-full mx-4 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">افزودن محصول جدید</h3>
                <button onclick="hideAddProductModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <form id="addProductForm" class="space-y-6">
                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">عنوان محصول *</label>
                        <input type="text" name="title" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="مثال: Adobe Photoshop 2024">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">دسته‌بندی *</label>
                        <select name="category" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">انتخاب دسته‌بندی</option>
                            <option value="software">نرم‌افزار</option>
                            <option value="courses">دوره آموزشی</option>
                            <option value="ebooks">کتاب الکترونیکی</option>
                            <option value="templates">قالب</option>
                        </select>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">قیمت اصلی (تومان) *</label>
                        <input type="number" name="originalPrice" required min="0" id="originalPriceInput"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="مثال: 3000000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">قیمت با تخفیف (تومان)</label>
                        <input type="number" name="price" min="0" id="discountPriceInput"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="مثال: 2500000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">درصد تخفیف</label>
                        <input type="number" name="discount" min="0" max="100" readonly id="discountPercent"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100"
                               placeholder="محاسبه خودکار">
                    </div>
                </div>

                <!-- Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت محصول</label>
                        <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="active">فعال</option>
                            <option value="inactive">غیرفعال</option>
                            <option value="draft">پیش‌نویس</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت موجودی</label>
                        <select name="availability" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="true">در دسترس</option>
                            <option value="false">ناموجود</option>
                        </select>
                    </div>
                </div>

                <!-- Rating -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">امتیاز (1-5)</label>
                        <select name="rating" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="5">5 ستاره</option>
                            <option value="4.8">4.8 ستاره</option>
                            <option value="4.5" selected>4.5 ستاره</option>
                            <option value="4">4 ستاره</option>
                            <option value="3.5">3.5 ستاره</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">تعداد نظرات</label>
                        <input type="number" name="reviews" min="0" value="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Tags -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">برچسب‌ها (با کاما جدا کنید)</label>
                    <input type="text" name="tags" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="مثال: Adobe, طراحی گرافیک, ویرایش تصویر">
                </div>

                <!-- Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">تصویر محصول</label>
                    <input type="url" name="image" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="https://example.com/image.jpg">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات محصول</label>
                    <textarea name="description" rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="توضیحات کاملی از محصول..."></textarea>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-4 border-t">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        <i class="fas fa-plus ml-1"></i>افزودن محصول
                    </button>
                    <button type="button" onclick="hideAddProductModal()" 
                            class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        لغو
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add File Modal -->
    <div id="addFileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-8 max-w-2xl w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">افزودن فایل محصول</h3>
                <button onclick="hideAddFileModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="addFileForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نام فایل</label>
                    <input type="text" name="fileName" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">محصول مربوطه</label>
                    <select name="productId" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">انتخاب محصول</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">آدرس فایل</label>
                        <input type="text" name="filePath" required
                               placeholder="/files/products/..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">حجم فایل</label>
                        <input type="text" name="fileSize" required
                               placeholder="2.8 GB" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نوع فایل</label>
                    <select name="fileType" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">انتخاب نوع</option>
                        <option value="zip">ZIP</option>
                        <option value="rar">RAR</option>
                        <option value="pdf">PDF</option>
                        <option value="mp4">MP4</option>
                    </select>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        افزودن فایل
                    </button>
                    <button type="button" onclick="hideAddFileModal()" class="flex-1 border border-gray-300 py-3 rounded-lg hover:bg-gray-50 transition-colors">
                        لغو
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div id="addCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-8 max-w-2xl w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">افزودن دسته‌بندی جدید</h3>
                <button onclick="hideAddCategoryModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="addCategoryForm" class="space-y-6">
                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نام دسته‌بندی *</label>
                        <input type="text" name="name" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="مثال: نرم‌افزارها">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">شناسه (Slug) *</label>
                        <input type="text" name="slug" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="software" id="categorySlug">
                        <p class="text-xs text-gray-500 mt-1">فقط حروف انگلیسی، اعداد و خط تیره</p>
                    </div>
                </div>

                <!-- Icon Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">انتخاب آیکون</label>
                    <div class="grid grid-cols-6 gap-3" id="iconSelection">
                        <div class="icon-option p-3 border-2 border-blue-500 rounded-lg text-center cursor-pointer bg-blue-50" data-icon="fas fa-laptop-code">
                            <i class="fas fa-laptop-code text-blue-600 text-xl"></i>
                        </div>
                        <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-play-circle">
                            <i class="fas fa-play-circle text-gray-600 text-xl"></i>
                        </div>
                        <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-book">
                            <i class="fas fa-book text-gray-600 text-xl"></i>
                        </div>
                        <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-palette">
                            <i class="fas fa-palette text-gray-600 text-xl"></i>
                        </div>
                        <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-mobile-alt">
                            <i class="fas fa-mobile-alt text-gray-600 text-xl"></i>
                        </div>
                        <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-gamepad">
                            <i class="fas fa-gamepad text-gray-600 text-xl"></i>
                        </div>
                        <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-camera">
                            <i class="fas fa-camera text-gray-600 text-xl"></i>
                        </div>
                        <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-music">
                            <i class="fas fa-music text-gray-600 text-xl"></i>
                        </div>
                        <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-chart-bar">
                            <i class="fas fa-chart-bar text-gray-600 text-xl"></i>
                        </div>
                        <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-tools">
                            <i class="fas fa-tools text-gray-600 text-xl"></i>
                        </div>
                        <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-graduation-cap">
                            <i class="fas fa-graduation-cap text-gray-600 text-xl"></i>
                        </div>
                        <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-shopping-cart">
                            <i class="fas fa-shopping-cart text-gray-600 text-xl"></i>
                        </div>
                    </div>
                    <input type="hidden" name="icon" value="fas fa-laptop-code" id="selectedIcon">
                </div>

                <!-- Color Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">انتخاب رنگ</label>
                    <div class="flex gap-3" id="colorSelection">
                        <div class="color-option w-10 h-10 rounded-full bg-blue-500 border-4 border-blue-200 cursor-pointer" data-color="blue"></div>
                        <div class="color-option w-10 h-10 rounded-full bg-green-500 border-2 border-gray-300 cursor-pointer" data-color="green"></div>
                        <div class="color-option w-10 h-10 rounded-full bg-purple-500 border-2 border-gray-300 cursor-pointer" data-color="purple"></div>
                        <div class="color-option w-10 h-10 rounded-full bg-orange-500 border-2 border-gray-300 cursor-pointer" data-color="orange"></div>
                        <div class="color-option w-10 h-10 rounded-full bg-red-500 border-2 border-gray-300 cursor-pointer" data-color="red"></div>
                        <div class="color-option w-10 h-10 rounded-full bg-yellow-500 border-2 border-gray-300 cursor-pointer" data-color="yellow"></div>
                        <div class="color-option w-10 h-10 rounded-full bg-indigo-500 border-2 border-gray-300 cursor-pointer" data-color="indigo"></div>
                        <div class="color-option w-10 h-10 rounded-full bg-pink-500 border-2 border-gray-300 cursor-pointer" data-color="pink"></div>
                    </div>
                    <input type="hidden" name="color" value="blue" id="selectedColor">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات دسته‌بندی</label>
                    <textarea name="description" rows="3" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="توضیحات کوتاهی درباره این دسته‌بندی..."></textarea>
                </div>

                <!-- Preview -->
                <div id="categoryPreview" class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold mb-3">پیش‌نمایش:</h4>
                    <div class="border border-gray-200 rounded-lg p-6 bg-white max-w-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <i id="previewIcon" class="fas fa-laptop-code text-blue-600 text-2xl ml-3"></i>
                                <div>
                                    <h3 id="previewName" class="font-semibold">نرم‌افزارها</h3>
                                    <p class="text-sm text-gray-500">0 محصول</p>
                                </div>
                            </div>
                        </div>
                        <p id="previewDescription" class="text-gray-600 text-sm">توضیحات دسته‌بندی...</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-4 border-t">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        <i class="fas fa-plus ml-1"></i>افزودن دسته‌بندی
                    </button>
                    <button type="button" onclick="hideAddCategoryModal()" 
                            class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        لغو
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-8 max-w-2xl w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">ویرایش دسته‌بندی</h3>
                <button onclick="hideEditCategoryModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="editCategoryForm" class="space-y-6">
                <input type="hidden" name="categoryId" id="editCategoryId">
                
                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نام دسته‌بندی *</label>
                        <input type="text" name="name" required id="editCategoryName"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="مثال: نرم‌افزارها">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">شناسه (Slug) *</label>
                        <input type="text" name="slug" required id="editCategorySlug"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="software">
                        <p class="text-xs text-gray-500 mt-1">فقط حروف انگلیسی، اعداد و خط تیره</p>
                    </div>
                </div>

                <!-- Icon Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">انتخاب آیکون</label>
                    <div class="grid grid-cols-6 gap-3" id="editIconSelection">
                        <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-laptop-code">
                            <i class="fas fa-laptop-code text-gray-600 text-xl"></i>
                        </div>
                        <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-play-circle">
                            <i class="fas fa-play-circle text-gray-600 text-xl"></i>
                        </div>
                        <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-book">
                            <i class="fas fa-book text-gray-600 text-xl"></i>
                        </div>
                        <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-palette">
                            <i class="fas fa-palette text-gray-600 text-xl"></i>
                        </div>
                        <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-mobile-alt">
                            <i class="fas fa-mobile-alt text-gray-600 text-xl"></i>
                        </div>
                        <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-gamepad">
                            <i class="fas fa-gamepad text-gray-600 text-xl"></i>
                        </div>
                        <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-camera">
                            <i class="fas fa-camera text-gray-600 text-xl"></i>
                        </div>
                        <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-music">
                            <i class="fas fa-music text-gray-600 text-xl"></i>
                        </div>
                        <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-chart-bar">
                            <i class="fas fa-chart-bar text-gray-600 text-xl"></i>
                        </div>
                        <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-tools">
                            <i class="fas fa-tools text-gray-600 text-xl"></i>
                        </div>
                        <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-graduation-cap">
                            <i class="fas fa-graduation-cap text-gray-600 text-xl"></i>
                        </div>
                        <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-shopping-cart">
                            <i class="fas fa-shopping-cart text-gray-600 text-xl"></i>
                        </div>
                    </div>
                    <input type="hidden" name="icon" value="" id="editSelectedIcon">
                </div>

                <!-- Color Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">انتخاب رنگ</label>
                    <div class="flex gap-3" id="editColorSelection">
                        <div class="edit-color-option w-10 h-10 rounded-full bg-blue-500 border-2 border-gray-300 cursor-pointer" data-color="blue"></div>
                        <div class="edit-color-option w-10 h-10 rounded-full bg-green-500 border-2 border-gray-300 cursor-pointer" data-color="green"></div>
                        <div class="edit-color-option w-10 h-10 rounded-full bg-purple-500 border-2 border-gray-300 cursor-pointer" data-color="purple"></div>
                        <div class="edit-color-option w-10 h-10 rounded-full bg-orange-500 border-2 border-gray-300 cursor-pointer" data-color="orange"></div>
                        <div class="edit-color-option w-10 h-10 rounded-full bg-red-500 border-2 border-gray-300 cursor-pointer" data-color="red"></div>
                        <div class="edit-color-option w-10 h-10 rounded-full bg-yellow-500 border-2 border-gray-300 cursor-pointer" data-color="yellow"></div>
                        <div class="edit-color-option w-10 h-10 rounded-full bg-indigo-500 border-2 border-gray-300 cursor-pointer" data-color="indigo"></div>
                        <div class="edit-color-option w-10 h-10 rounded-full bg-pink-500 border-2 border-gray-300 cursor-pointer" data-color="pink"></div>
                    </div>
                    <input type="hidden" name="color" value="" id="editSelectedColor">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات دسته‌بندی</label>
                    <textarea name="description" rows="3" id="editCategoryDescription"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="توضیحات کوتاهی درباره این دسته‌بندی..."></textarea>
                </div>

                <!-- Preview -->
                <div id="editCategoryPreview" class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold mb-3">پیش‌نمایش:</h4>
                    <div class="border border-gray-200 rounded-lg p-6 bg-white max-w-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <i id="editPreviewIcon" class="fas fa-laptop-code text-blue-600 text-2xl ml-3"></i>
                                <div>
                                    <h3 id="editPreviewName" class="font-semibold">نام دسته‌بندی</h3>
                                    <p class="text-sm text-gray-500" id="editPreviewCount">0 محصول</p>
                                </div>
                            </div>
                        </div>
                        <p id="editPreviewDescription" class="text-gray-600 text-sm">توضیحات دسته‌بندی...</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-4 border-t">
                    <button type="submit" 
                            class="flex-1 bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                        <i class="fas fa-save ml-1"></i>ذخیره تغییرات
                    </button>
                    <button type="button" onclick="hideEditCategoryModal()" 
                            class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        لغو
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Tag Modal -->
    <div id="addTagModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-8 max-w-lg w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">افزودن برچسب جدید</h3>
                <button onclick="hideAddTagModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="addTagForm" class="space-y-6">
                <!-- Basic Info -->
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نام برچسب *</label>
                        <input type="text" name="name" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="مثال: Adobe">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">شناسه (Slug) *</label>
                        <input type="text" name="slug" required id="tagSlug"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="adobe">
                        <p class="text-xs text-gray-500 mt-1">فقط حروف انگلیسی، اعداد و خط تیره</p>
                    </div>
                </div>

                <!-- Color Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">انتخاب رنگ</label>
                    <div class="flex gap-3 flex-wrap" id="tagColorSelection">
                        <div class="tag-color-option w-12 h-12 rounded-full bg-blue-500 border-4 border-blue-200 cursor-pointer flex items-center justify-center text-white font-bold" data-color="blue">A</div>
                        <div class="tag-color-option w-12 h-12 rounded-full bg-green-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="green">A</div>
                        <div class="tag-color-option w-12 h-12 rounded-full bg-purple-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="purple">A</div>
                        <div class="tag-color-option w-12 h-12 rounded-full bg-orange-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="orange">A</div>
                        <div class="tag-color-option w-12 h-12 rounded-full bg-red-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="red">A</div>
                        <div class="tag-color-option w-12 h-12 rounded-full bg-yellow-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-yellow-800 font-bold" data-color="yellow">A</div>
                        <div class="tag-color-option w-12 h-12 rounded-full bg-indigo-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="indigo">A</div>
                        <div class="tag-color-option w-12 h-12 rounded-full bg-pink-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="pink">A</div>
                        <div class="tag-color-option w-12 h-12 rounded-full bg-gray-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="gray">A</div>
                    </div>
                    <input type="hidden" name="color" value="blue" id="selectedTagColor">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات برچسب (اختیاری)</label>
                    <textarea name="description" rows="2" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="توضیحات کوتاهی درباره این برچسب..."></textarea>
                </div>

                <!-- Preview -->
                <div id="tagPreview" class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold mb-3">پیش‌نمایش:</h4>
                    <div class="flex gap-2">
                        <span id="previewTag" class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm">نام برچسب</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-4 border-t">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        <i class="fas fa-plus ml-1"></i>افزودن برچسب
                    </button>
                    <button type="button" onclick="hideAddTagModal()" 
                            class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        لغو
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Tag Modal -->
    <div id="editTagModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-8 max-w-lg w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">ویرایش برچسب</h3>
                <button onclick="hideEditTagModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="editTagForm" class="space-y-6">
                <input type="hidden" name="tagId" id="editTagId">
                
                <!-- Basic Info -->
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نام برچسب *</label>
                        <input type="text" name="name" required id="editTagName"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="مثال: Adobe">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">شناسه (Slug) *</label>
                        <input type="text" name="slug" required id="editTagSlug"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="adobe">
                        <p class="text-xs text-gray-500 mt-1">فقط حروف انگلیسی، اعداد و خط تیره</p>
                    </div>
                </div>

                <!-- Color Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">انتخاب رنگ</label>
                    <div class="flex gap-3 flex-wrap" id="editTagColorSelection">
                        <div class="edit-tag-color-option w-12 h-12 rounded-full bg-blue-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="blue">A</div>
                        <div class="edit-tag-color-option w-12 h-12 rounded-full bg-green-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="green">A</div>
                        <div class="edit-tag-color-option w-12 h-12 rounded-full bg-purple-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="purple">A</div>
                        <div class="edit-tag-color-option w-12 h-12 rounded-full bg-orange-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="orange">A</div>
                        <div class="edit-tag-color-option w-12 h-12 rounded-full bg-red-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="red">A</div>
                        <div class="edit-tag-color-option w-12 h-12 rounded-full bg-yellow-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-yellow-800 font-bold" data-color="yellow">A</div>
                        <div class="edit-tag-color-option w-12 h-12 rounded-full bg-indigo-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="indigo">A</div>
                        <div class="edit-tag-color-option w-12 h-12 rounded-full bg-pink-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="pink">A</div>
                        <div class="edit-tag-color-option w-12 h-12 rounded-full bg-gray-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="gray">A</div>
                    </div>
                    <input type="hidden" name="color" value="" id="editSelectedTagColor">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات برچسب (اختیاری)</label>
                    <textarea name="description" rows="2" id="editTagDescription"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="توضیحات کوتاهی درباره این برچسب..."></textarea>
                </div>

                <!-- Preview -->
                <div id="editTagPreview" class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold mb-3">پیش‌نمایش:</h4>
                    <div class="flex gap-2">
                        <span id="editPreviewTag" class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm">نام برچسب</span>
                        <span class="text-sm text-gray-500" id="editPreviewCount">0 محصول</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-4 border-t">
                    <button type="submit" 
                            class="flex-1 bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                        <i class="fas fa-save ml-1"></i>ذخیره تغییرات
                    </button>
                    <button type="button" onclick="hideEditTagModal()" 
                            class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        لغو
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Coupon Modal -->
    <div id="addCouponModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-8 max-w-4xl w-full mx-4 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">ایجاد کد تخفیف جدید</h3>
                <button onclick="hideAddCouponModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <form id="addCouponForm" class="space-y-6">
                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">کد تخفیف *</label>
                        <input type="text" name="code" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase font-mono"
                               placeholder="SALE20"
                               style="text-transform: uppercase;">
                        <p class="text-xs text-gray-500 mt-1">فقط حروف انگلیسی و اعداد</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نوع تخفیف *</label>
                        <select name="type" required id="couponType"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="percentage">درصدی (%)</option>
                            <option value="fixed">مقدار ثابت (تومان)</option>
                        </select>
                    </div>
                </div>

                <!-- Discount Value -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">مقدار تخفیف *</label>
                        <div class="relative">
                            <input type="number" name="value" required min="1" id="discountValue"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="20">
                            <span id="discountUnit" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">%</span>
                        </div>
                    </div>
                    <div id="maxDiscountDiv">
                        <label class="block text-sm font-medium text-gray-700 mb-2">حداکثر تخفیف (تومان)</label>
                        <input type="number" name="maxDiscount" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="500000">
                        <p class="text-xs text-gray-500 mt-1">برای تخفیف درصدی</p>
                    </div>
                </div>

                <!-- Min Order & Usage -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">حداقل مبلغ خرید (تومان)</label>
                        <input type="number" name="minOrder" min="0" value="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="1000000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">تعداد استفاده مجاز</label>
                        <input type="number" name="usageLimit" min="1" value="100"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="100">
                        <p class="text-xs text-gray-500 mt-1">0 = نامحدود</p>
                    </div>
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt text-green-500 ml-1"></i>تاریخ شروع
                        </label>
                        <div class="relative">
                            <input type="text" name="startDate" id="startDatePersian" readonly
                                   class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white cursor-pointer"
                                   placeholder="انتخاب تاریخ شروع">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" onclick="alert('آیکون کلیک شد!'); console.log('openDatePicker called'); openDatePicker('startDatePersian');">
                                <i class="fas fa-calendar-alt text-blue-500 hover:text-blue-600"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-times text-red-500 ml-1"></i>تاریخ انقضا
                        </label>
                        <div class="relative">
                            <input type="text" name="endDate" id="endDatePersian" readonly
                                   class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white cursor-pointer"
                                   placeholder="انتخاب تاریخ انقضا">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" onclick="openDatePicker('endDatePersian')">
                                <i class="fas fa-calendar-times text-red-500 hover:text-red-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status & Description -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت</label>
                        <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="active">فعال</option>
                            <option value="inactive">غیرفعال</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                        <textarea name="description" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="توضیحات کد تخفیف..."></textarea>
                    </div>
                </div>

                <!-- Preview -->
                <div id="couponPreview" class="bg-gray-50 rounded-lg p-6">
                    <h4 class="font-semibold mb-4">پیش‌نمایش کد تخفیف:</h4>
                    <div class="bg-white border-2 border-dashed border-blue-300 rounded-lg p-6 max-w-md">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600 font-mono mb-2" id="previewCode">SALE20</div>
                            <div class="text-lg font-semibold text-gray-700 mb-2" id="previewDiscount">20% تخفیف</div>
                            <div class="text-sm text-gray-500" id="previewDetails">حداقل خرید: 0 تومان</div>
                            <div class="text-xs text-gray-400 mt-2" id="previewValidity">بدون تاریخ انقضا</div>
                        </div>
                    </div>
                </div>



                <!-- Actions -->
                <div class="flex gap-4 pt-4 border-t">
                    <div class="grid grid-cols-3 gap-2 mb-3">
                        <button type="button" onclick="forceShowModal();" 
                                class="bg-red-600 text-white py-2 px-3 rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">
                            🧪 تست تاریخ‌گزار
                        </button>
                        <button type="button" onclick="console.log('🧪 تست ادیت کوپن شروع شد...'); editCoupon(1);" 
                                class="bg-orange-600 text-white py-2 px-3 rounded-lg hover:bg-orange-700 transition-colors text-sm font-semibold">
                            🛠️ تست ادیت کوپن
                        </button>
                        <button type="button" onclick="
                            console.log('🎯 تست دکمه جدول...');
                            const editButtons = document.querySelectorAll('button[title=ویرایش]');
                            if (editButtons.length > 0) {
                                editButtons[0].click();
                                console.log('✅ کلیک شد!');
                            } else {
                                alert('❌ دکمه ادیت پیدا نشد!');
                            }
                        " class="bg-purple-600 text-white py-2 px-3 rounded-lg hover:bg-purple-700 transition-colors text-sm font-semibold">
                            🎯 تست جدول
                        </button>
                    </div>
                    
                    <button type="submit" 
                            class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        <i class="fas fa-plus ml-1"></i>ایجاد کد تخفیف
                    </button>
                    <button type="button" onclick="hideAddCouponModal()" 
                            class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        لغو
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Coupon Modal -->
    <div id="editCouponModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-8 max-w-4xl w-full mx-4 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">ویرایش کد تخفیف</h3>
                <button onclick="hideEditCouponModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <form id="editCouponForm" class="space-y-6">
                <input type="hidden" name="couponId" id="editCouponId">
                
                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">کد تخفیف *</label>
                        <input type="text" name="code" required id="editCouponCode"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase font-mono"
                               placeholder="SALE20"
                               style="text-transform: uppercase;">
                        <p class="text-xs text-gray-500 mt-1">فقط حروف انگلیسی و اعداد</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نوع تخفیف *</label>
                        <select name="type" required id="editCouponType"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="percentage">درصدی (%)</option>
                            <option value="fixed">مقدار ثابت (تومان)</option>
                        </select>
                    </div>
                </div>

                <!-- Discount Value -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">مقدار تخفیف *</label>
                        <div class="relative">
                            <input type="number" name="value" required min="1" id="editDiscountValue"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="20">
                            <span id="editDiscountUnit" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">%</span>
                        </div>
                    </div>
                    <div id="editMaxDiscountDiv">
                        <label class="block text-sm font-medium text-gray-700 mb-2">حداکثر تخفیف (تومان)</label>
                        <input type="number" name="maxDiscount" min="0" id="editMaxDiscount"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="500000">
                        <p class="text-xs text-gray-500 mt-1">برای تخفیف درصدی</p>
                    </div>
                </div>

                <!-- Min Order & Usage -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">حداقل مبلغ خرید (تومان)</label>
                        <input type="number" name="minOrder" min="0" id="editMinOrder"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="1000000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">تعداد استفاده مجاز</label>
                        <input type="number" name="usageLimit" min="1" id="editUsageLimit"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="100">
                        <p class="text-xs text-gray-500 mt-1">0 = نامحدود</p>
                    </div>
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt text-green-500 ml-1"></i>تاریخ شروع
                        </label>
                        <div class="relative">
                            <input type="text" name="startDate" id="editStartDatePersian" readonly
                                   class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white cursor-pointer"
                                   placeholder="انتخاب تاریخ شروع">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" onclick="openDatePicker('editStartDatePersian')">
                                <i class="fas fa-calendar-alt text-blue-500 hover:text-blue-600"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-times text-red-500 ml-1"></i>تاریخ انقضا
                        </label>
                        <div class="relative">
                            <input type="text" name="endDate" id="editEndDatePersian" readonly
                                   class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white cursor-pointer"
                                   placeholder="انتخاب تاریخ انقضا">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" onclick="openDatePicker('editEndDatePersian')">
                                <i class="fas fa-calendar-times text-red-500 hover:text-red-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status & Description -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت</label>
                        <select name="status" id="editCouponStatus" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="active">فعال</option>
                            <option value="inactive">غیرفعال</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                        <textarea name="description" rows="3" id="editCouponDescription"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="توضیحات کد تخفیف..."></textarea>
                    </div>
                </div>

                <!-- Usage Stats (Read-only) -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold mb-2">آمار استفاده:</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>تعداد استفاده: <span id="editUsageCount" class="font-semibold">0</span></div>
                        <div>باقیمانده: <span id="editRemainingUses" class="font-semibold">∞</span></div>
                    </div>
                </div>

                <!-- Preview -->
                <div id="editCouponPreview" class="bg-gray-50 rounded-lg p-6">
                    <h4 class="font-semibold mb-4">پیش‌نمایش کد تخفیف:</h4>
                    <div class="bg-white border-2 border-dashed border-blue-300 rounded-lg p-6 max-w-md">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600 font-mono mb-2" id="editPreviewCode">SALE20</div>
                            <div class="text-lg font-semibold text-gray-700 mb-2" id="editPreviewDiscount">20% تخفیف</div>
                            <div class="text-sm text-gray-500" id="editPreviewDetails">حداقل خرید: 0 تومان</div>
                            <div class="text-xs text-gray-400 mt-2" id="editPreviewValidity">بدون تاریخ انقضا</div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-4 border-t">
                    <button type="submit" 
                            class="flex-1 bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                        <i class="fas fa-save ml-1"></i>ذخیره تغییرات
                    </button>
                    <button type="button" onclick="hideEditCouponModal()" 
                            class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        لغو
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Persian Date Picker Modal -->
    <div id="persianDatePicker" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden" style="z-index: 99999; backdrop-filter: blur(4px); position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5);">
        <div class="bg-white rounded-lg shadow-xl p-6 w-80 max-w-sm mx-4">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">انتخاب تاریخ</h3>
                <button onclick="closeDatePicker()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Year and Month Navigation -->
            <div class="flex items-center justify-between mb-4">
                <button onclick="changeMonth(-1)" class="p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <div class="flex items-center gap-2">
                    <select id="monthSelect" onchange="updateCalendar()" class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
                        <option value="1">فروردین</option>
                        <option value="2">اردیبهشت</option>
                        <option value="3">خرداد</option>
                        <option value="4">تیر</option>
                        <option value="5">مرداد</option>
                        <option value="6">شهریور</option>
                        <option value="7">مهر</option>
                        <option value="8">آبان</option>
                        <option value="9">آذر</option>
                        <option value="10">دی</option>
                        <option value="11">بهمن</option>
                        <option value="12">اسفند</option>
                    </select>
                    
                    <select id="yearSelect" onchange="updateCalendar()" class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
                        <!-- Years will be populated by JS -->
                    </select>
                </div>
                
                <button onclick="changeMonth(1)" class="p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            
            <!-- Weekdays -->
            <div class="grid grid-cols-7 gap-1 mb-2">
                <div class="text-center text-xs font-bold text-gray-600 p-2">ش</div>
                <div class="text-center text-xs font-bold text-gray-600 p-2">ی</div>
                <div class="text-center text-xs font-bold text-gray-600 p-2">د</div>
                <div class="text-center text-xs font-bold text-gray-600 p-2">س</div>
                <div class="text-center text-xs font-bold text-gray-600 p-2">چ</div>
                <div class="text-center text-xs font-bold text-gray-600 p-2">پ</div>
                <div class="text-center text-xs font-bold text-red-500 p-2">ج</div>
            </div>
            
            <!-- Calendar Days -->
            <div id="calendarDays" class="grid grid-cols-7 gap-1 mb-4">
                <!-- Days will be populated by JS -->
            </div>
            
            <!-- Today and Clear buttons -->
            <div class="flex gap-2">
                <button onclick="selectToday()" class="flex-1 py-2 px-4 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm font-medium">
                    امروز
                </button>
                <button onclick="clearDate()" class="flex-1 py-2 px-4 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-sm font-medium">
                    پاک کردن
                </button>
            </div>
        </div>
    </div>
    
    <!-- Add Menu Modal -->
    <div id="addMenuModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-8 max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">افزودن آیتم منو</h3>
                <button onclick="hideAddMenuModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <form id="addMenuForm" class="space-y-6">
                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نام منو *</label>
                        <input type="text" name="title" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="مثال: خانه">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">لینک *</label>
                        <input type="text" name="url" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="مثال: index.html">
                    </div>
                </div>

                <!-- Icon & Order -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">آیکون (اختیاری)</label>
                        <div class="flex gap-2">
                            <input type="text" name="icon" id="menuIconInput"
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="fas fa-home">
                            <div id="menuIconPreview" class="w-12 h-12 border border-gray-300 rounded-lg flex items-center justify-center text-gray-400">
                                <i class="fas fa-question"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">کلاس Font Awesome (مثال: fas fa-home)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ترتیب نمایش</label>
                        <input type="number" name="order" min="1" value="1"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="1">
                    </div>
                </div>

                <!-- Target & Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نحوه باز شدن</label>
                        <select name="target" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="_self">همین صفحه</option>
                            <option value="_blank">تب جدید</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت</label>
                        <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="active">فعال</option>
                            <option value="inactive">غیرفعال</option>
                        </select>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات (اختیاری)</label>
                    <textarea name="description" rows="3" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="توضیحات مربوط به آیتم منو..."></textarea>
                </div>

                <!-- Live Preview -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-700 mb-3">پیش‌نمایش</h4>
                    <div id="menuPreview" class="bg-gray-50 rounded-lg p-4 border">
                        <div class="inline-flex items-center px-3 py-2 text-gray-700 hover:text-blue-600 transition-colors">
                            <i class="fas fa-question w-5 ml-2"></i>
                            <span>نام منو</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-4 border-t">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        <i class="fas fa-plus ml-1"></i>افزودن آیتم منو
                    </button>
                    <button type="button" onclick="hideAddMenuModal()" 
                            class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        لغو
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Menu Modal -->
    <div id="editMenuModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-8 max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">ویرایش آیتم منو</h3>
                <button onclick="hideEditMenuModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <form id="editMenuForm" class="space-y-6">
                <input type="hidden" name="menuId" id="editMenuId">
                
                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نام منو *</label>
                        <input type="text" name="title" required id="editMenuTitle"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="مثال: خانه">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">لینک *</label>
                        <input type="text" name="url" required id="editMenuUrl"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="مثال: index.html">
                    </div>
                </div>

                <!-- Icon & Order -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">آیکون (اختیاری)</label>
                        <div class="flex gap-2">
                            <input type="text" name="icon" id="editMenuIconInput"
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="fas fa-home">
                            <div id="editMenuIconPreview" class="w-12 h-12 border border-gray-300 rounded-lg flex items-center justify-center text-gray-400">
                                <i class="fas fa-question"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">کلاس Font Awesome (مثال: fas fa-home)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ترتیب نمایش</label>
                        <input type="number" name="order" min="1" value="1" id="editMenuOrder"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="1">
                    </div>
                </div>

                <!-- Target & Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نحوه باز شدن</label>
                        <select name="target" id="editMenuTarget" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="_self">همین صفحه</option>
                            <option value="_blank">تب جدید</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت</label>
                        <select name="status" id="editMenuStatus" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="active">فعال</option>
                            <option value="inactive">غیرفعال</option>
                        </select>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات (اختیاری)</label>
                    <textarea name="description" rows="3" id="editMenuDescription"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="توضیحات مربوط به آیتم منو..."></textarea>
                </div>

                <!-- Live Preview -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-700 mb-3">پیش‌نمایش</h4>
                    <div id="editMenuPreview" class="bg-gray-50 rounded-lg p-4 border">
                        <div class="inline-flex items-center px-3 py-2 text-gray-700 hover:text-blue-600 transition-colors">
                            <i class="fas fa-question w-5 ml-2"></i>
                            <span>نام منو</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-4 border-t">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        <i class="fas fa-save ml-1"></i>ذخیره تغییرات
                    </button>
                    <button type="button" onclick="hideEditMenuModal()" 
                            class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        لغو
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="{{asset('js/admin.js')}}"></script>
</body>
</html>