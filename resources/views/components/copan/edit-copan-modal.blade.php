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