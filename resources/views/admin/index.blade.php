<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    @include('admin.layouts.head-tag')
</head>
<body class="font-sans bg-gray-50">
    <!-- Header -->
    @include('admin.layouts.partials.header')

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            @include('admin.layouts.partials.sidebar')
            <!-- Main Content -->
            <div class="lg:w-3/4">
                <!-- Dashboard Section -->
                @include('admin.layouts.sections.dashborad')

                <!-- Products Management Section -->
                @include('admin.layouts.sections.products')

                <!-- Product Files Management Section -->
                @include('admin.layouts.sections.file-management')

                <!-- Categories Management Section -->
                @include('admin.layouts.sections.category')

                <!-- Tags Management Section -->
                @include('admin.layouts.sections.tags')

                <!-- Coupons Management Section -->
                @include('admin.layouts.sections.coupons')

                <!-- Comments Management Section -->
                @include('admin.layouts.sections.comments')

                <!-- Site Menus Section -->
                {{-- TODO --}}
                
                <!-- Payments Section -->
                @include('admin.layouts.sections.payments')

                <!-- Support Tickets Section -->
                @include('admin.layouts.sections.tickets')


                <!-- FAQ Section -->
                @include('admin.layouts.sections.faq')


                <!-- Site Settings Section -->
                @include('admin.layouts.sections.setting')


                <!-- Other sections will be added in the next part due to length -->
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <x-add-product-modal></x-add-product-modal>

    <!-- Add File Modal -->
    <x-add-file-modal></x-add-file-modal>

    <!-- Add Category Modal -->
    <x-category.add-category></x-category.add-category>

    <!-- Edit Category Modal -->
    <x-category.edit-category></x-category.edit-category>

    <!-- Add Tag Modal -->
    <x-tag.add-tag-modal></x-tag.add-tag-modal>

    <!-- Edit Tag Modal -->
    <x-tag.edit-tag-modal></x-tag.edit-tag-modal>

    <!-- Add Coupon Modal -->
    <x-copan.add-copan-modal></x-copan.add-copan-modal>

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