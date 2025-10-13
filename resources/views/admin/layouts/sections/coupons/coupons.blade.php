@extends('admin.layouts.partials.master')

@section('content')
    <div id="coupons" class="content-section">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">مدیریت کدهای تخفیف</h2>
                <a href="{{ route('admin.coupon.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus ml-2"></i>کد تخفیف جدید
                </a>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="card border-success mb-4 shadow-lg" id="successAlert">
                    <div class="card-header bg-gradient bg-success text-white py-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <span class="fw-bold fs-6">عملیات موفق</span>
                        </div>
                        <button type="button" class="btn-close btn-close-white" onclick="closeSuccessAlert()" aria-label="Close"></button>
                    </div>
                    <div class="card-body bg-light py-3">
                        <ul class="mb-0 text-success fs-7">
                            <li class="mb-1">
                                <i class="fas fa-check me-2 small"></i>
                                {{ session('success') }}
                            </li>
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-blue-50 rounded-lg p-6 text-center">
                    <i class="fas fa-ticket-alt text-blue-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ count($coupons) }}</h3>
                    <p class="text-gray-600">کل کدها</p>
                </div>
                <div class="bg-green-50 rounded-lg p-6 text-center">
                    <i class="fas fa-check-circle text-green-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $active_count }}</h3>
                    <p class="text-gray-600">فعال</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-6 text-center">
                    <i class="fas fa-times-circle text-gray-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $inactive_count }}</h3>
                    <p class="text-gray-600">غیرفعال</p>
                </div>
                <div class="bg-red-50 rounded-lg p-6 text-center">
                    <i class="fas fa-clock text-red-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $expired_count }}</h3>
                    <p class="text-gray-600">منقضی شده</p>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3 text-right text-gray-600 font-semibold border-b">ردیف</th>
                            <th class="p-3 text-right text-gray-600 font-semibold border-b">کد تخفیف</th>
                            <th class="p-3 text-right text-gray-600 font-semibold border-b">نوع</th>
                            <th class="p-3 text-right text-gray-600 font-semibold border-b">مقدار</th>
                            <th class="p-3 text-right text-gray-600 font-semibold border-b">حداقل خرید</th>
                            <th class="p-3 text-right text-gray-600 font-semibold border-b">تاریخ پایان</th>
                            <th class="p-3 text-right text-gray-600 font-semibold border-b">وضعیت</th>
                            <th class="p-3 text-center text-gray-600 font-semibold border-b">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $index => $coupon)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3 border-b">{{ $index + 1 }}</td>
                            <td class="p-3 border-b">
                                <span class="font-bold text-blue-600 text-lg">{{ $coupon->code }}</span>
                            </td>
                            <td class="p-3 border-b">
                                @if($coupon->type === 'percentage')
                                    <span class="text-purple-600">
                                        <i class="fas fa-percent ml-1"></i>درصدی
                                    </span>
                                @else
                                    <span class="text-green-600">
                                        <i class="fas fa-coins ml-1"></i>مبلغ ثابت
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 border-b">
                                <span class="font-semibold text-lg">{{ $coupon->formatted_discount }}</span>
                                @if($coupon->type === 'percentage' && $coupon->max_discount)
                                    <br>
                                    <span class="text-sm text-gray-500">حداکثر: {{ number_format($coupon->max_discount) }} تومان</span>
                                @endif
                            </td>
                            <td class="p-3 border-b">
                                {{ $coupon->min_order ? number_format($coupon->min_order) . ' تومان' : '-' }}
                            </td>
                            <td class="p-3 border-b">
                                @if($coupon->end_date)
                                    {{ \Morilog\Jalali\Jalalian::forge($coupon->end_date)->format('Y/m/d') }}
                                    @if($coupon->end_date->isPast())
                                        <br><span class="text-red-500 text-sm">(منقضی شده)</span>
                                    @endif
                                @else
                                    <span class="text-gray-500">نامحدود</span>
                                @endif
                            </td>
                            <td class="p-3 border-b">
                                @if($coupon->status === 'active')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                        <i class="fas fa-check ml-1"></i>فعال
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">
                                        <i class="fas fa-times ml-1"></i>غیرفعال
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 border-b">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.coupon.edit', $coupon->id) }}" 
                                       class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition"
                                       title="ویرایش">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <a href="{{ route('admin.coupon.toggle-status', $coupon->id) }}" 
                                       class="p-2 {{ $coupon->status === 'active' ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-600' }} rounded-lg hover:opacity-80 transition"
                                       title="{{ $coupon->status === 'active' ? 'غیرفعال کردن' : 'فعال کردن' }}">
                                        <i class="fas fa-power-off"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.coupon.destroy', $coupon->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition"
                                                title="حذف"
                                                onclick="return confirm('آیا از حذف این کد تخفیف اطمینان دارید؟')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3"></i>
                                <p>هیچ کد تخفیفی یافت نشد</p>
                                <a href="{{ route('admin.coupon.create') }}" class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                    ایجاد اولین کد تخفیف
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
    </script>
@endsection

