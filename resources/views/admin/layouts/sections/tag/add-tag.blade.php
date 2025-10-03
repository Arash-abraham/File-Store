@extends('admin.layouts.partials.master')

@section('content')
    <div class="bg-white rounded-xl p-6 w-full shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">افزودن برچسب</h3>
        </div>
        @if($errors->any())
            <div class="card border-danger mb-4" id="errorAlert">
                <div class="card-header bg-danger text-white py-2 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <span class="fw-bold">خطا در ارسال فرم</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white" onclick="closeErrorAlert()" aria-label="Close"></button>
                </div>
                <div class="card-body text-danger py-3">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <form id="addCategoryForm" class="space-y-6" method="POST" action="{{route('admin.tag.store')}}">
            @csrf
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نام برچسب *</label>
                    <input type="text" name="name" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="مثال: نرم‌افزارها">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">شناسه (Slug) *</label>
                    <input type="text" name="slug" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="software" id="categorySlug">
                    <p class="text-xs text-gray-500 mt-1">فقط حروف انگلیسی، اعداد و خط تیره</p>
                </div>
            </div>


            <!-- Actions -->
            <div class="flex gap-4 pt-4 border-t border-gray-200">
                <button type="submit" 
                        class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold shadow-md">
                    <i class="fas fa-plus ml-1"></i>افزودن برچسب
                </button>
                <a href="{{ route('admin.tag.index') }}">
                    <button type="button" 
                        class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        بازگشت
                    </button>
                </a>
            </div>
        </form>
    </div>
    @section('js')
        <script src="{{asset('js/admin/category.js')}}"></script>
    @endsection
@endsection