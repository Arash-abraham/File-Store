@extends('admin.layouts.partials.master')

@section('content')
    <div id="products" class="content-section">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">مدیریت محصولات</h2>
                <a href="{{ route('admin.product.create') }}">
                    <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors" onclick="showAddProductModal()">
                        <i class="fas fa-plus ml-2"></i>افزودن محصول
                    </button>
                </a>
            </div>
            
            <!-- Success Alert -->
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
            <!-- Products Table -->
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-right p-4">آیدی</th>
                            <th class="text-right p-4">تصویر</th>
                            <th class="text-right p-4">نام محصول</th>
                            <th class="text-right p-4">نوع</th>
                            <th class="text-right p-4">قیمت</th>
                            <th class="text-right p-4">وضعیت</th>
                            <th class="text-right p-4">وضعیت فروش</th>
                            <th class="text-right p-4">عملیات</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody">
                        @forelse ($products as $product)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-4">{{ $product->id }}</td>
                                <td class="p-4">
                                    @if ($product->image_urls && count($product->image_urls) > 0)
                                        <img src="{{ asset($product->image_urls[0]) }}" 
                                             class="w-16 h-16 rounded-lg object-cover" 
                                             alt="تصویر محصول {{ $product->title }}">
                                    @else
                                        <span class="text-gray-500">بدون تصویر</span>
                                    @endif
                                </td>
                                <td class="p-4 font-semibold">{{ $product->title }}</td>
                                <td class="p-4">{{ $product->category->name }}</td>
                                <td class="p-4">{{ number_format($product->original_price) }} تومان</td>
                                @if ($product->status == 'active')
                                    <td class="p-4">
                                        <span class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">فعال</span>
                                    </td>
                                @elseif($product->status == 'inactive')
                                    <td class="p-4">
                                        <span class="bg-yellow-100 text-yellow-800 text-sm px-3 py-1 rounded-full">پیش‌نویس</span>
                                    </td>
                                @else
                                    <td class="p-4">
                                        <span class="bg-red-100 text-red-800 text-sm px-3 py-1 rounded-full">غیرفعال</span>
                                    </td>
                                @endif
                                @if ($product->availability)
                                    <td class="p-4">
                                        <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">دردسترس</span>
                                    </td>
                                @else
                                    <td class="p-4">
                                        <span class="bg-red-100 text-red-800 text-sm px-3 py-1 rounded-full">ناموجود</span>
                                    </td>
                                @endif
                                <td class="p-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.product.edit', $product->id) }}">
                                            <button class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </a>
                                        <form action="{{ route('admin.product.destroy', $product->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" 
                                                    onclick="return confirm('آیا مطمئن هستید؟')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-4 text-center text-gray-500">محصولی یافت نشد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @section('js')
        <script>
            function closeSuccessAlert() {
                document.getElementById('successAlert').style.display = 'none';
            }
        </script>
    @endsection
@endsection