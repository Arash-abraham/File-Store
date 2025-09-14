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
