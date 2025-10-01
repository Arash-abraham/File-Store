@extends('admin.index')

@section('content')
    <div id="categories" class="content-section">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">مدیریت دسته‌بندی‌ها</h2>
                <a href="{{route('admin.category.create')}}">
                    <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors" onclick="showAddCategoryModal()">
                        <i class="fas fa-plus ml-2"></i>افزودن دسته‌بندی
                    </button>
                </a>
            </div>
            
            @foreach ($categories as $category)
                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <i class="{{$category->icon}} text-{{$category->color}}-600 text-2xl ml-3"></i>
                            <div>
                                <h3 class="font-semibold">{{ $category->name }}</h3>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button class="text-blue-600 hover:text-blue-800" title="ویرایش">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-800" title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
        
@endsection
