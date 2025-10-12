@extends('admin.layouts.partials.master')

@section('content')
    <div id="categories" class="content-section">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">مدیریت دسته‌بندی‌ها</h2>
                <a href="{{ route('admin.category.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus ml-2"></i>دسته‌بندی جدید
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

            @if($errors->any())
                <div class="card border-danger mb-4 shadow-lg">
                    <div class="card-header bg-gradient bg-danger text-white py-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span class="fw-bold fs-6">خطاها</span>
                    </div>
                    <div class="card-body bg-light py-3">
                        <ul class="mb-0 text-danger fs-7">
                            @foreach($errors->all() as $error)
                                <li class="mb-1">
                                    <i class="fas fa-times me-2 small"></i>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-blue-50 rounded-lg p-6 text-center">
                    <i class="fas fa-folder text-blue-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ count($categories) }}</h3>
                    <p class="text-gray-600">کل دسته‌بندی‌ها</p>
                </div>
                <div class="bg-green-50 rounded-lg p-6 text-center">
                    <i class="fas fa-box text-green-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $categories->sum('products_count') }}</h3>
                    <p class="text-gray-600">کل محصولات</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-6 text-center">
                    <i class="fas fa-chart-line text-purple-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $categories->avg('products_count') ? round($categories->avg('products_count'), 1) : 0 }}</h3>
                    <p class="text-gray-600">میانگین محصولات</p>
                </div>
            </div>

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($categories as $category)
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-14 h-14 rounded-lg bg-{{$category->color}}-100 flex items-center justify-center">
                                    <i class="{{$category->icon}} text-{{$category->color}}-600 text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg text-gray-800">{{ $category->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $category->slug }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-3 mb-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">تعداد محصولات:</span>
                                <span class="font-bold text-gray-800">{{ $category->products_count }} محصول</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-4">
                            <i class="far fa-calendar-alt"></i>
                            <span>ایجاد: {{ \Morilog\Jalali\Jalalian::forge($category->created_at)->format('Y/m/d') }}</span>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('admin.category.edit', $category->id) }}" 
                               class="flex-1 text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-edit ml-1"></i>ویرایش
                            </a>
                            <form action="{{ route('admin.category.destroy', $category->id) }}" 
                                  method="POST" 
                                  class="flex-1"
                                >
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition">
                                    <i class="fas fa-trash ml-1"></i>حذف
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-16 text-gray-500">
                        <i class="fas fa-inbox text-6xl mb-4 text-gray-300"></i>
                        <p class="text-xl mb-4">هیچ دسته‌بندی‌ای یافت نشد</p>
                        <a href="{{ route('admin.category.create') }}" 
                           class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-plus ml-2"></i>ایجاد اولین دسته‌بندی
                        </a>
                    </div>
                @endforelse
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
