<div id="persianDatePicker" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
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
