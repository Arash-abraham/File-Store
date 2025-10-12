@extends('admin.layouts.partials.master')

@section('content')
    <div class="content-section">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-plus-circle text-blue-600 ml-2"></i>
                    ایجاد منو جدید
                </h2>
                <a href="{{ route('admin.menu.index') }}" class="text-gray-600 hover:text-gray-800 transition">
                    <i class="fas fa-arrow-right ml-2"></i>بازگشت به لیست
                </a>
            </div>

            <!-- Errors -->
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.menu.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Title -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-heading ml-1 text-blue-600"></i>
                        عنوان منو
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           value="{{ old('title') }}"
                           placeholder="مثال: صفحه اصلی"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror"
                           required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- URL -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-link ml-1 text-blue-600"></i>
                        آدرس URL
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="url" 
                           value="{{ old('url') }}"
                           placeholder="مثال: / یا /products یا https://example.com"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('url') border-red-500 @enderror"
                           required>
                    @error('url')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">می‌توانید از / برای صفحه اصلی، /products برای محصولات و یا لینک کامل استفاده کنید</p>
                </div>

                <!-- Icon -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-icons ml-1 text-blue-600"></i>
                        آیکون (Font Awesome) - اختیاری
                    </label>
                    <input type="text" 
                           name="icon" 
                           value="{{ old('icon') }}"
                           placeholder="مثال: fas fa-home"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('icon') border-red-500 @enderror">
                    @error('icon')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">
                        برای انتخاب آیکون از <a href="https://fontawesome.com/icons" target="_blank" class="text-blue-600 hover:underline">Font Awesome</a> استفاده کنید
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Sort Order -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-sort-numeric-up ml-1 text-blue-600"></i>
                            ترتیب نمایش
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="sort_order" 
                               value="{{ old('sort_order', 1) }}"
                               min="1"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sort_order') border-red-500 @enderror"
                               required>
                        @error('sort_order')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Target -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-window-restore ml-1 text-blue-600"></i>
                            نحوه باز شدن
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="target" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('target') border-red-500 @enderror"
                                required>
                            <option value="_self" {{ old('target') == '_self' ? 'selected' : '' }}>همان صفحه</option>
                            <option value="_blank" {{ old('target') == '_blank' ? 'selected' : '' }}>صفحه جدید</option>
                        </select>
                        @error('target')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-toggle-on ml-1 text-blue-600"></i>
                            وضعیت
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="status" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror"
                                required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>فعال</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غیرفعال</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-align-right ml-1 text-blue-600"></i>
                        توضیحات - اختیاری
                    </label>
                    <textarea name="description" 
                              rows="3"
                              placeholder="توضیحات کوتاهی درباره این منو"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-4">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 text-white py-4 rounded-lg hover:bg-blue-700 transition-colors font-semibold text-lg">
                        <i class="fas fa-save ml-2"></i>
                        ذخیره منو
                    </button>
                    <a href="{{ route('admin.menu.index') }}" 
                       class="px-8 py-4 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                        <i class="fas fa-times ml-2"></i>
                        انصراف
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

