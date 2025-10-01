@extends('admin.index')

@section('content')
    <div id="categories" class="content-section">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">مدیریت دسته‌بندی‌ها</h2>
                <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors" onclick="showAddCategoryModal()">
                    <i class="fas fa-plus ml-2"></i>افزودن دسته‌بندی
                </button>
            </div>
            
            <!-- Categories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-laptop-code text-blue-600 text-2xl ml-3"></i>
                            <div>
                                <h3 class="font-semibold">نرم‌افزارها</h3>
                                <p class="text-sm text-gray-500">1 محصول</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button class="text-blue-600 hover:text-blue-800" onclick="editCategory(1)" title="ویرایش">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-800" onclick="deleteCategory(1)" title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm">انواع نرم‌افزارهای کاربردی و حرفه‌ای</p>

                </div>
                
            </div>
        </div>
    </div>
    
    @include('admin.layouts.sections.category.add-category')
    
@endsection