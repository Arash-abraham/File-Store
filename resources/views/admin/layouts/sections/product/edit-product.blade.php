@extends('admin.layouts.partials.master')

@section('content')
    <div id="editProductPage" class="bg-white rounded-xl p-8 max-w-6xl w-full mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold">ویرایش محصول: {{ $product->title }}</h3>
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

        <form enctype="multipart/form-data" id="editProductForm" action="{{ route('admin.product.update', $product->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">عنوان محصول *</label>
                    <input type="text" name="title" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('title', $product->title) }}"
                        placeholder="مثال: Adobe Photoshop 2024">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">دسته‌بندی *</label>
                    <select name="category" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">همه دسته‌بندی‌ها</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                    </select>
                </div>
            </div>

            <!-- Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">قیمت اصلی (تومان) *</label>
                    <input type="number" name="originalPrice" required min="0" id="originalPriceInput"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('originalPrice', $product->original_price) }}"
                        placeholder="مثال: 3000000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت محصول</label>
                    <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>فعال</option>
                        <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>غیرفعال</option>
                        <option value="draft" {{ $product->status == 'draft' ? 'selected' : '' }}>پیش‌نویس</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت موجودی</label>
                    <select name="availability" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="true" {{ $product->availability ? 'selected' : '' }}>در دسترس</option>
                        <option value="false" {{ !$product->availability ? 'selected' : '' }}>ناموجود</option>
                    </select>
                </div>
            </div>

            <!-- Tags -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">برچسب‌ها</label>
                <select name="tag" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">همه تگ‌ها</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" {{ $product->tag_id == $tag->id ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                </select>
            </div>

            <!-- Images -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">تصاویر محصول</label>
                
                <!-- Existing Images -->
                <div id="existingImages" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    @foreach ($product->image_urls ?? [] as $index => $imageUrl)
                        <div class="border border-gray-300 rounded-lg p-2 relative existing-image-container" data-index="{{ $index + 1 }}">
                            <img src="{{ asset($imageUrl) }}" alt="تصویر محصول" class="w-full h-24 object-cover rounded">
                            <button type="button" onclick="removeExistingImage({{ $index + 1 }})" class="absolute top-1 right-1 text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                            <input type="hidden" name="existingImages[]" value="{{ $imageUrl }}" id="existingImage{{ $index + 1 }}">
                        </div>
                    @endforeach
                </div>

                <!-- Step 1: Ask for number of additional images -->
                <div id="imageCountStep" class="mb-4">
                    <label class="block text-sm text-gray-600 mb-2">تعداد تصاویر جدید مورد نظر:</label>
                    <select id="imageCountSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" onchange="initImageUpload()">
                        <option value="0">لطفا تعداد را انتخاب کنید</option>
                    </select>
                </div>

                <!-- Step 2: Upload containers -->
                <div id="uploadContainers" class="space-y-4 hidden">
                    <!-- Upload containers will be generated here -->
                </div>

                <!-- Hidden inputs for storing new image data -->
                <div id="hiddenImageInputs"></div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات محصول</label>
                <textarea name="description" rows="4" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="توضیحات کاملی از محصول...">{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- Actions -->
            <div class="flex gap-4 pt-4 border-t">
                <button type="submit" 
                        class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    <i class="fas fa-save ml-1"></i>ذخیره تغییرات
                </button>
                <button type="button" onclick="goBack()" 
                        class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    لغو
                </button>
            </div>
        </form>
    </div>

    <script>
        let maxImages = 0;
        let currentUploaded = 0;
        let existingImageCount = {{ count($product->image_urls ?? []) }};
        const maxTotalImages = 6; // حداکثر تعداد کل تصاویر

        // مقداردهی اولیه به گزینه‌های select بر اساس تعداد تصاویر موجود
        document.addEventListener('DOMContentLoaded', function() {
            updateImageCountOptions();
        });

        function updateImageCountOptions() {
            const select = document.getElementById('imageCountSelect');
            select.innerHTML = '<option value="0">لطفا تعداد را انتخاب کنید</option>';

            const remainingSlots = maxTotalImages - existingImageCount;
            if (remainingSlots > 0) {
                for (let i = 1; i <= remainingSlots; i++) {
                    const option = document.createElement('option');
                    option.value = i;
                    option.text = `${i} تصویر`;
                    select.appendChild(option);
                }
            } else {
                select.disabled = true;
                select.title = 'ظرفیت تصاویر پر شده است.';
            }
        }

        function initImageUpload() {
            const countSelect = document.getElementById('imageCountSelect');
            const selectedCount = parseInt(countSelect.value);

            if (selectedCount === 0) {
                document.getElementById('uploadContainers').classList.add('hidden');
                document.getElementById('uploadContainers').innerHTML = '';
                document.getElementById('hiddenImageInputs').innerHTML = '';
                maxImages = 0;
                currentUploaded = 0;
                return;
            }

            maxImages = selectedCount;
            currentUploaded = 0;

            const uploadContainers = document.getElementById('uploadContainers');
            uploadContainers.classList.remove('hidden');

            uploadContainers.innerHTML = '';
            for (let i = 1; i <= maxImages; i++) {
                uploadContainers.innerHTML += createUploadContainer(i);
            }

            document.getElementById('hiddenImageInputs').innerHTML = '';

            const completionMsg = document.querySelector('.completion-message');
            if (completionMsg) {
                completionMsg.remove();
            }
        }

        function createUploadContainer(index) {
            return `
            <div class="border border-gray-300 rounded-lg p-4 upload-container" data-index="${index}">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">تصویر جدید ${index}</span>
                    <span class="text-xs text-gray-500">(اختیاری)</span>
                </div>
                <div class="upload-area-${index}">
                    <div class="flex items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors" onclick="document.getElementById('imageUpload${index}').click()">
                        <div class="text-center">
                            <svg class="w-6 h-6 mx-auto mb-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <p class="text-xs text-gray-500">برای آپلود کلیک کنید</p>
                        </div>
                    </div>
                    <input id="imageUpload${index}" type="file" accept="image/*" class="hidden" onchange="handleImageUpload(${index}, this)" />
                </div>
                <div id="previewContainer${index}" class="mt-2 hidden">
                    <div class="flex items-center gap-3">
                        <img id="previewImage${index}" src="" alt="پیش‌نمایش" class="w-16 h-16 object-cover rounded border">
                        <button type="button" onclick="removeUploadedImage(${index})" class="text-red-500 hover:text-red-700 text-sm">
                            حذف
                        </button>
                    </div>
                </div>
            </div>
            `;
        }

        function handleImageUpload(index, input) {
            const file = input.files[0];
            if (!file) return;



            const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('فرمت فایل باید JPG, PNG یا WEBP باشد');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById(`previewImage${index}`);
                const previewContainer = document.getElementById(`previewContainer${index}`);
                const uploadArea = document.querySelector(`.upload-area-${index}`);

                previewImg.src = e.target.result;
                previewContainer.classList.remove('hidden');
                uploadArea.classList.add('hidden');

                storeImageData(index, e.target.result);

                currentUploaded++;
                checkUploadCompletion();
            };
            reader.readAsDataURL(file);
        }

        function storeImageData(index, imageData) {
            const hiddenInputs = document.getElementById('hiddenImageInputs');

            const existingInput = document.getElementById(`hiddenImage${index}`);
            if (existingInput) {
                existingInput.remove();
            }

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = `productImages[]`;
            hiddenInput.id = `hiddenImage${index}`;
            hiddenInput.value = imageData;
            hiddenInputs.appendChild(hiddenInput);
        }

        function removeUploadedImage(index) {
            const input = document.getElementById(`imageUpload${index}`);
            const previewContainer = document.getElementById(`previewContainer${index}`);
            const uploadArea = document.querySelector(`.upload-area-${index}`);
            const hiddenInput = document.getElementById(`hiddenImage${index}`);

            input.value = '';
            previewContainer.classList.add('hidden');
            uploadArea.classList.remove('hidden');

            if (hiddenInput) {
                hiddenInput.remove();
            }

            if (document.getElementById(`previewImage${index}`).src) {
                currentUploaded--;
            }

            const completionMsg = document.querySelector('.completion-message');
            if (completionMsg) {
                completionMsg.remove();
            }

            const uploadContainers = document.querySelectorAll('.upload-container');
            uploadContainers.forEach(container => {
                const idx = container.dataset.index;
                const area = document.querySelector(`.upload-area-${idx}`);
                if (area) {
                    area.style.opacity = '1';
                    area.style.pointerEvents = 'auto';
                }
            });

            // به‌روزرسانی گزینه‌ها پس از حذف تصویر آپلود شده
            existingImageCount = {{ count($product->image_urls ?? []) }} - (document.querySelectorAll('.existing-image-container').length - existingImageCount);
            updateImageCountOptions();
        }

        function removeExistingImage(index) {
            const container = document.querySelector(`.existing-image-container[data-index="${index}"]`);
            const hiddenInput = document.getElementById(`existingImage${index}`);
            if (container) {
                container.remove();
                existingImageCount--;
            }
            if (hiddenInput) {
                hiddenInput.remove();
            }

            // به‌روزرسانی گزینه‌ها پس از حذف تصویر موجود
            updateImageCountOptions();
        }

        function checkUploadCompletion() {
            if (currentUploaded >= maxImages) {
                const uploadContainers = document.querySelectorAll('.upload-container');
                uploadContainers.forEach(container => {
                    const index = container.dataset.index;
                    const uploadArea = document.querySelector(`.upload-area-${index}`);
                    if (uploadArea) {
                        uploadArea.style.opacity = '0.5';
                        uploadArea.style.pointerEvents = 'none';
                    }
                });

                const completionMsg = document.createElement('div');
                completionMsg.className = 'bg-green-50 border border-green-200 rounded-lg p-3 mt-4';
                completionMsg.innerHTML = `
                    <div class="flex items-center text-green-700">
                        <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>همه تصاویر جدید با موفقیت آپلود شدند</span>
                    </div>
                `;

                const existingMsg = document.querySelector('.completion-message');
                if (!existingMsg) {
                    completionMsg.classList.add('completion-message');
                    document.getElementById('uploadContainers').appendChild(completionMsg);
                }
            }
        }

        function goBack() {
            window.history.back();
        }
    </script>

    @section('js')
        <script src="{{ asset('js/admin/category.js') }}"></script>
    @endsection
@endsection