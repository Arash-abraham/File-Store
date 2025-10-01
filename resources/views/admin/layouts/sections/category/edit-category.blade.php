@extends('admin.index')

@section('content')
    <div class="bg-white rounded-xl p-6 w-full shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">تغییر در دسته بندی</h3>
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
        <form data-current-icon="{{ $category->icon }}" data-current-color="{{ $category->color }}" id="addCategoryForm" class="space-y-6" method="POST" action="{{route('admin.category.update',$category->id)}}">
            @method('PUT')
            @csrf
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نام دسته‌بندی *</label>
                    <input type="text" name="name" required value="{{ $category->name }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="مثال: نرم‌افزارها">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">شناسه (Slug) *</label>
                    <input type="text" name="slug" required value="{{ $category->slug }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="software" id="categorySlug">
                    <p class="text-xs text-gray-500 mt-1">فقط حروف انگلیسی، اعداد و خط تیره</p>
                </div>
            </div>

            <!-- Icon Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">انتخاب آیکون</label>
                <div class="grid grid-cols-6 gap-3" id="iconSelection">
                    <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all" data-icon="fas fa-play-circle">
                        <i class="fas fa-laptop-code text-gray-600 text-xl"></i>
                    </div>
                    <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all" data-icon="fas fa-play-circle">
                        <i class="fas fa-play-circle text-gray-600 text-xl"></i>
                    </div>
                    <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all" data-icon="fas fa-book">
                        <i class="fas fa-book text-gray-600 text-xl"></i>
                    </div>
                    <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all" data-icon="fas fa-palette">
                        <i class="fas fa-palette text-gray-600 text-xl"></i>
                    </div>
                    <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all" data-icon="fas fa-mobile-alt">
                        <i class="fas fa-mobile-alt text-gray-600 text-xl"></i>
                    </div>
                    <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all" data-icon="fas fa-gamepad">
                        <i class="fas fa-gamepad text-gray-600 text-xl"></i>
                    </div>
                    <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all" data-icon="fas fa-camera">
                        <i class="fas fa-camera text-gray-600 text-xl"></i>
                    </div>
                    <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all" data-icon="fas fa-music">
                        <i class="fas fa-music text-gray-600 text-xl"></i>
                    </div>
                    <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all" data-icon="fas fa-chart-bar">
                        <i class="fas fa-chart-bar text-gray-600 text-xl"></i>
                    </div>
                    <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all" data-icon="fas fa-tools">
                        <i class="fas fa-tools text-gray-600 text-xl"></i>
                    </div>
                    <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all" data-icon="fas fa-graduation-cap">
                        <i class="fas fa-graduation-cap text-gray-600 text-xl"></i>
                    </div>
                    <div class="icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all" data-icon="fas fa-shopping-cart">
                        <i class="fas fa-shopping-cart text-gray-600 text-xl"></i>
                    </div>
                </div>
                <input type="hidden" name="icon" value="fas fa-laptop-code" id="selectedIcon">
            </div>

            <!-- Color Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">انتخاب رنگ</label>
                <div class="flex gap-3 flex-wrap" id="colorSelection">
                    <div class="color-option w-10 h-10 rounded-full bg-blue-500 border-4 border-blue-200 cursor-pointer transition-all" data-color="blue"></div>
                    <div class="color-option w-10 h-10 rounded-full bg-green-500 border-2 border-gray-300 cursor-pointer transition-all" data-color="green"></div>
                    <div class="color-option w-10 h-10 rounded-full bg-purple-500 border-2 border-gray-300 cursor-pointer transition-all" data-color="purple"></div>
                    <div class="color-option w-10 h-10 rounded-full bg-orange-500 border-2 border-gray-300 cursor-pointer transition-all" data-color="orange"></div>
                    <div class="color-option w-10 h-10 rounded-full bg-red-500 border-2 border-gray-300 cursor-pointer transition-all" data-color="red"></div>
                    <div class="color-option w-10 h-10 rounded-full bg-yellow-500 border-2 border-gray-300 cursor-pointer transition-all" data-color="yellow"></div>
                    <div class="color-option w-10 h-10 rounded-full bg-indigo-500 border-2 border-gray-300 cursor-pointer transition-all" data-color="indigo"></div>
                    <div class="color-option w-10 h-10 rounded-full bg-pink-500 border-2 border-gray-300 cursor-pointer transition-all" data-color="pink"></div>
                </div>
                <input type="hidden" name="color" value="blue" id="selectedColor">
            </div>


            <!-- Preview -->
            <div id="categoryPreview" class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <h4 class="font-semibold mb-3 text-gray-700">پیش‌نمایش:</h4>
                <div class="border border-gray-200 rounded-lg p-6 bg-white">
                    <div class="flex items-center">
                        <i id="previewIcon" class="{{$category->icon}} text-{{$category->color}}-600 text-2xl ml-3"></i>
                        <div>
                            <h3 id="previewName" class="font-semibold text-gray-800"></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-4 pt-4 border-t border-gray-200">
                <button type="submit" 
                        class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold shadow-md">
                    <i class="fas fa-plus ml-1"></i>ثبت تغییرات
                </button>
                <a href="{{ route('admin.category.index') }}">
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