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