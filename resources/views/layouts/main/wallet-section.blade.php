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