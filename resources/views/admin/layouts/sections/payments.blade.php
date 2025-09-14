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