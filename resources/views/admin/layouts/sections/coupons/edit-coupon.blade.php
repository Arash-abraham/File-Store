@extends('admin.layouts.partials.master')

@section('title', 'ویرایش کد تخفیف')

@section('content')
<div class="content-section">
    @if(session('success'))
        <div id="successAlert" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">موفقیت!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
            <button onclick="closeSuccessAlert()" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">ویرایش کد تخفیف</h2>
            <a href="{{ route('admin.coupon.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                <i class="fas fa-arrow-right"></i>
                بازگشت به لیست
            </a>
        </div>

        <form action="{{ route('admin.coupon.update', $coupon->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- کد تخفیف -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">کد تخفیف *</label>
                    <input type="text" name="code" id="code" value="{{ old('code', $coupon->code) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('code') border-red-500 @enderror"
                           placeholder="مثال: SUMMER2024" required>
                    @error('code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-xs mt-1">فقط حروف بزرگ انگلیسی و اعداد مجاز هستند</p>
                </div>

                <!-- نوع تخفیف -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">نوع تخفیف *</label>
                    <select name="type" id="type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror" required>
                        <option value="">انتخاب کنید</option>
                        <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>درصدی</option>
                        <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>مبلغ ثابت</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- مقدار تخفیف -->
                <div>
                    <label for="value" class="block text-sm font-medium text-gray-700 mb-2">مقدار تخفیف *</label>
                    <input type="number" name="value" id="value" value="{{ old('value', $coupon->value) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('value') border-red-500 @enderror"
                           placeholder="مثال: 20" min="1" required>
                    @error('value')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حداکثر تخفیف -->
                <div id="maxDiscountField">
                    <label for="max_discount" class="block text-sm font-medium text-gray-700 mb-2">حداکثر تخفیف (تومان)</label>
                    <input type="number" name="max_discount" id="max_discount" value="{{ old('max_discount', $coupon->max_discount) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('max_discount') border-red-500 @enderror"
                           placeholder="مثال: 50000" min="0">
                    @error('max_discount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-xs mt-1">فقط برای تخفیف درصدی</p>
                </div>

                <!-- حداقل سفارش -->
                <div>
                    <label for="min_order" class="block text-sm font-medium text-gray-700 mb-2">حداقل مبلغ سفارش (تومان)</label>
                    <input type="number" name="min_order" id="min_order" value="{{ old('min_order', $coupon->min_order) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('min_order') border-red-500 @enderror"
                           placeholder="مثال: 100000" min="0">
                    @error('min_order')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- تاریخ شروع -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">تاریخ شروع</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $coupon->start_date ? $coupon->start_date->format('Y-m-d') : '') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror">
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- تاریخ پایان -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">تاریخ پایان</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $coupon->end_date ? $coupon->end_date->format('Y-m-d') : '') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-500 @enderror">
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- وضعیت -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">وضعیت *</label>
                    <select name="status" id="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror" required>
                        <option value="">انتخاب کنید</option>
                        <option value="active" {{ old('status', $coupon->status) == 'active' ? 'selected' : '' }}>فعال</option>
                        <option value="inactive" {{ old('status', $coupon->status) == 'inactive' ? 'selected' : '' }}>غیرفعال</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- اطلاعات اضافی -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">اطلاعات کد تخفیف</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">تاریخ ایجاد:</span>
                        <span class="font-medium">{{ $coupon->created_at->format('Y/m/d H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">آخرین ویرایش:</span>
                        <span class="font-medium">{{ $coupon->updated_at->format('Y/m/d H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">وضعیت فعلی:</span>
                        <span class="font-medium {{ $coupon->status == 'active' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $coupon->status_name }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- دکمه‌ها -->
            <div class="flex gap-4 pt-6 border-t">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    بروزرسانی کد تخفیف
                </button>
                <a href="{{ route('admin.coupon.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg transition-colors font-semibold">
                    انصراف
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function closeSuccessAlert() {
        document.getElementById('successAlert').style.display = 'none';
    }

    // Auto hide success message after 5 seconds
    setTimeout(function() {
        const alert = document.getElementById('successAlert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 5000);

    // اعتبارسنجی مقدار تخفیف
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const valueInput = document.getElementById('value');
        const maxDiscountField = document.getElementById('maxDiscountField');

        function validateDiscountValue() {
            if (typeSelect.value === 'percentage' && valueInput.value > 100) {
                valueInput.setCustomValidity('درصد تخفیف نمی‌تواند بیشتر از 100 باشد');
            } else {
                valueInput.setCustomValidity('');
            }
        }

        function toggleMaxDiscountField() {
            if (typeSelect.value === 'percentage') {
                maxDiscountField.style.display = 'block';
            } else {
                maxDiscountField.style.display = 'none';
                document.getElementById('max_discount').value = '';
            }
        }

        typeSelect.addEventListener('change', function() {
            validateDiscountValue();
            toggleMaxDiscountField();
        });

        valueInput.addEventListener('input', validateDiscountValue);

        // اجرای اولیه
        validateDiscountValue();
        toggleMaxDiscountField();
    });
</script>
@endsection