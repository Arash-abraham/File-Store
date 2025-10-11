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
<?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/layouts/main/orders-section.blade.php ENDPATH**/ ?>