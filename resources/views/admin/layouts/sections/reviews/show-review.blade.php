@extends('admin.layouts.partials.master')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">جزئیات نظر</h1>
            <a href="{{ route('admin.review.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                <i class="fas fa-arrow-right me-2"></i>بازگشت
            </a>
        </div>

        <!-- Review Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header Info -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">{{ $review->product->title }}</h2>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="fas fa-star text-yellow-300 text-xl"></i>
                                @else
                                    <i class="far fa-star text-white text-xl"></i>
                                @endif
                            @endfor
                            <span class="mr-3 text-lg">({{ $review->rating }} از 5)</span>
                        </div>
                    </div>
                    <div class="text-left">
                        @if($review->status == 'pending')
                            <span class="px-4 py-2 bg-yellow-400 text-yellow-900 rounded-full text-sm font-semibold">
                                <i class="fas fa-clock me-1"></i>در انتظار تایید
                            </span>
                        @elseif($review->status == 'approved')
                            <span class="px-4 py-2 bg-green-400 text-green-900 rounded-full text-sm font-semibold">
                                <i class="fas fa-check me-1"></i>تایید شده
                            </span>
                        @else
                            <span class="px-4 py-2 bg-red-400 text-red-900 rounded-full text-sm font-semibold">
                                <i class="fas fa-times me-1"></i>رد شده
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- User Info -->
            <div class="p-6 bg-gray-50 border-b">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">نام کاربر</p>
                        <p class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-user text-blue-500 me-2"></i>
                            {{ $review->user->name ?? 'کاربر حذف شده' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">ایمیل</p>
                        <p class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-envelope text-blue-500 me-2"></i>
                            {{ $review->user->email ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">تاریخ ثبت نظر</p>
                        <p class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-calendar text-blue-500 me-2"></i>
                            {{ \Morilog\Jalali\Jalalian::forge($review->created_at)->format('Y/m/d H:i') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">شماره تلفن</p>
                        <p class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-phone text-blue-500 me-2"></i>
                            {{ $review->user->phone ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Review Content -->
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-comment-dots text-blue-500 me-2"></i>
                    متن نظر
                </h3>
                <div class="bg-gray-50 rounded-lg p-6 text-gray-700 leading-relaxed text-lg">
                    {{ $review->body }}
                </div>
            </div>

            <!-- Stats -->
            <div class="p-6 bg-gray-50 border-t">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white rounded-lg p-4 text-center">
                        <i class="fas fa-thumbs-up text-green-500 text-2xl mb-2"></i>
                        <p class="text-2xl font-bold text-gray-800">{{ $review->helpful_count }}</p>
                        <p class="text-sm text-gray-600">مفید بوده</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 text-center">
                        <i class="fas fa-flag text-red-500 text-2xl mb-2"></i>
                        <p class="text-2xl font-bold text-gray-800">{{ $review->reports_count }}</p>
                        <p class="text-sm text-gray-600">گزارش شده</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="p-6 bg-white border-t flex justify-center gap-4">
                @if($review->status == 'pending' || $review->status == 'rejected')
                    <form action="{{ route('admin.review.updateStatus', $review->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" 
                                class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center"
                                >
                            <i class="fas fa-check me-2"></i>
                            تایید نظر
                        </button>
                    </form>
                @endif

                @if($review->status == 'pending' || $review->status == 'approved')
                    <form action="{{ route('admin.review.updateStatus', $review->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" 
                                class="px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition flex items-center"
                                >
                            <i class="fas fa-times me-2"></i>
                            رد نظر
                        </button>
                    </form>
                @endif

                <form action="{{ route('admin.review.destroy', $review->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center"
                            >
                        <i class="fas fa-trash me-2"></i>
                        حذف نظر
                    </button>
                </form>
            </div>
        </div>

        <!-- Product Link -->
        <div class="mt-6 text-center">
            <a href="{{ route('show-product', ['id' => $review->product->id]) }}" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
               target="_blank">
                <i class="fas fa-external-link-alt me-2"></i>
                مشاهده محصول در سایت
            </a>
        </div>
    </div>
</div>
@endsection