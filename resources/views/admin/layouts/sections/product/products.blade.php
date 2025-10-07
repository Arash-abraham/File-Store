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
            
        <!-- Search and Filter Form -->
        <form action="" method="GET" class="flex flex-col md:flex-row gap-4 mb-6">
            <!-- Search Input -->
            <div class="flex-1 relative">
                <input type="text" name="search" id="productSearch" placeholder="جستجوی محصولات..." 
                       class="w-full px-4 py-3 pl-12 pr-4 border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 text-right bg-white">
                
                <!-- Search Icon on left side -->
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"></path>
                    </svg>
                </div>
            </div>
            <!-- Category Filter -->
            <div class="w-full md:w-1/4">
                <select name="category_id" id="categoryFilter" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">همه دسته‌بندی‌ها</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Button -->
            <div class="w-full md:w-auto">
                <button type="submit" 
                        class="w-full md:w-auto px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                    فیلتر
                </button>
            </div>
        </form>
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
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4">1</td>

                            <td class="p-4">
                                <img src="https://images.pexels.com/photos/4348401/pexels-photo-4348401.jpeg?auto=compress&cs=tinysrgb&w=100" 
                                    class="w-16 h-16 rounded-lg object-cover">
                            </td>
                            <td class="p-4 font-semibold">Adobe Photoshop 2024</td>
                            <td class="p-4">نرم‌افزار</td>
                            <td class="p-4">2,500,000 تومان</td>
                            <td class="p-4">
                                <span class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">فعال</span>
                            </td>
                            <td class="p-4">
                                <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">در دسترس</span>
                            </td>
                            <td class="p-4">
                                <div class="flex gap-2">
                                    <button class="text-blue-600 hover:text-blue-800" onclick="editProduct(1)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800" onclick="deleteProduct(1)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>

@endsection