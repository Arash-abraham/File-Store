@extends('admin.layouts.partials.master')

@section('content')
    <div id="settings" class="content-section">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">تنظیمات سایت</h2>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            <form action="{{ route('admin.web-setting.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Logo and Icon -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Logo Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">لوگوی سایت</label>
                        <div class="flex items-center gap-4">
                            <img id="logo-preview" 
                                 src="{{ isset($settings->logo_path) && $settings->logo_path ? asset($settings->logo_path) : 'https://images.pexels.com/photos/1181533/pexels-photo-1181533.jpeg?auto=compress&cs=tinysrgb&w=100' }}" 
                                 class="w-16 h-16 rounded-lg object-cover">
                            <div>
                                <input type="file" 
                                       id="logo" 
                                       name="logo" 
                                       accept="image/*" 
                                       class="hidden"
                                       onchange="previewImage(this, 'logo-preview')">
                                <button type="button" 
                                        onclick="document.getElementById('logo').click()" 
                                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    تغییر لوگو
                                </button>

                            </div>
                        </div>
                        @error('logo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Icon Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">آیکون سایت</label>
                        <div class="flex items-center gap-4">
                            <img id="icon-preview" 
                                 src="{{ isset($settings->icon_path) && $settings->icon_path ? asset($settings->icon_path) : '/vite.svg' }}" 
                                 class="w-8 h-8">
                            <div>
                                <input type="file" 
                                       id="icon" 
                                       name="icon" 
                                       accept="image/*" 
                                       class="hidden"
                                       onchange="previewImage(this, 'icon-preview')">
                                <button type="button" 
                                        onclick="document.getElementById('icon').click()" 
                                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    تغییر آیکون
                                </button>
                            </div>
                        </div>
                        @error('icon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Site Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="site_title" class="block text-sm font-medium text-gray-700 mb-2">عنوان سایت</label>
                        <input type="text" 
                               id="site_title" 
                               name="site_title" 
                               value="{{ old('site_title', $settings->site_title ?? '') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('site_title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="site_description" class="block text-sm font-medium text-gray-700 mb-2">توضیحات سایت</label>
                        <textarea id="site_description" 
                                  name="site_description" 
                                  rows="4" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('site_description', $settings->site_description ?? '') }}</textarea>
                        @error('site_description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">شماره تماس</label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $settings->phone ?? '') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">ایمیل</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $settings->email ?? '') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">آدرس</label>
                    <input type="text" 
                           id="address" 
                           name="address" 
                           value="{{ old('address', $settings->address ?? '') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    ذخیره تغییرات
                </button>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                
                reader.readAsDataURL(file);
            }
        }

        function removeImage(type) {
            const previewId = type + '-preview';
            const preview = document.getElementById(previewId);
            const input = document.getElementById(type);
            
            // Reset file input
            input.value = '';

        }

    </script>
@endsection