@extends('admin.layouts.partials.master')

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
                                <a href="{{ route('admin.category.edit',$category->id) }}">
                                    <button class="text-green-600 hover:text-green-800" title="ویرایش">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </a>
                                <form action="{{ route('admin.category.destroy', $category->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:text-red-800" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>    
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
    @section('js')
        <script src="{{asset('js/admin/category.js')}}"></script>
    @endsection
@endsection
