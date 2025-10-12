@extends('admin.layouts.partials.master')

@section('content')
    <div id="reviews" class="content-section">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">مدیریت نظرات محصولات</h2>
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

            <!-- Review Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-blue-50 rounded-lg p-6 text-center">
                    <i class="fas fa-comments text-blue-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ count($reviews) }}</h3>
                    <p class="text-gray-600">کل نظرات</p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-6 text-center">
                    <i class="fas fa-clock text-yellow-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $pending_count }}</h3>
                    <p class="text-gray-600">در انتظار تایید</p>
                </div>
                <div class="bg-green-50 rounded-lg p-6 text-center">
                    <i class="fas fa-check-circle text-green-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $approved_count }}</h3>
                    <p class="text-gray-600">تایید شده</p>
                </div>
                <div class="bg-red-50 rounded-lg p-6 text-center">
                    <i class="fas fa-times-circle text-red-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $rejected_count }}</h3>
                    <p class="text-gray-600">رد شده</p>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex gap-3 mb-6">
                <a href="{{ route('admin.review.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-list me-2"></i>همه نظرات
                </a>
                <a href="{{ route('admin.review.filter', 'pending') }}" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                    <i class="fas fa-clock me-2"></i>در انتظار تایید
                </a>
                <a href="{{ route('admin.review.filter', 'approved') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-check me-2"></i>تایید شده
                </a>
                <a href="{{ route('admin.review.filter', 'rejected') }}" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-times me-2"></i>رد شده
                </a>
            </div>

            <!-- Reviews Table -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse" id="reviewsTable">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3 text-right text-gray-600 font-semibold border-b">ردیف</th>
                            <th class="p-3 text-right text-gray-600 font-semibold border-b">کاربر</th>
                            <th class="p-3 text-right text-gray-600 font-semibold border-b">محصول</th>
                            <th class="p-3 text-right text-gray-600 font-semibold border-b">نظر</th>
                            <th class="p-3 text-right text-gray-600 font-semibold border-b">وضعیت</th>
                            <th class="p-3 text-right text-gray-600 font-semibold border-b">تاریخ</th>
                            <th class="p-3 text-center text-gray-600 font-semibold border-b">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $index => $review)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3 border-b">{{ $index + 1 }}</td>
                            <td class="p-3 border-b">
                                <div>
                                    <div class="font-semibold">{{ $review->user->name ?? 'کاربر حذف شده' }}</div>
                                    <div class="text-sm text-gray-500">{{ $review->user->email ?? '-' }}</div>
                                </div>
                            </td>
                            <td class="p-3 border-b">
                                <div class="font-semibold">{{ $review->product->title }}</div>
                            </td>
                            <td class="p-3 border-b">
                                <div class="max-w-xs truncate" title="{{ $review->body }}">
                                    {{ Str::limit($review->body, 50) }}
                                </div>
                            </td>
                            <td class="p-3 border-b">
                                @if($review->status == 'pending')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                                        <i class="fas fa-clock me-1"></i>درانتظار
                                    </span>
                                @elseif($review->status == 'approved')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                        <i class="fas fa-check me-1"></i>تاییدشده
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">
                                        <i class="fas fa-times me-1"></i>ردشده
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 border-b text-sm text-gray-600">
                                {{ \Morilog\Jalali\Jalalian::forge($review->created_at)->format('Y/m/d H:i') }}
                            </td>
                            <td class="p-3 border-b">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.review.show', $review->id) }}" 
                                       class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition"
                                       title="مشاهده جزئیات">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($review->status == 'pending')
                                        <form action="{{ route('admin.review.updateStatus', $review->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" 
                                                    class="p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition"
                                                    title="تایید نظر"
                                                    >
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.review.updateStatus', $review->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" 
                                                    class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition"
                                                    title="رد نظر"
                                                    >
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('admin.review.destroy', $review->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition"
                                                title="حذف نظر"
                                            >
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
                                <p>هیچ نظری یافت نشد</p>
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