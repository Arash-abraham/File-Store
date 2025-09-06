<div id="dashboard" class="content-section">
    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">خوش آمدید {{ auth()->user()->name }}</h2>
        
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