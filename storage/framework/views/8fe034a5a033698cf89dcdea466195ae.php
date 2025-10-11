<?php $__env->startSection('content'); ?>
    <div id="addProductPage" class="bg-white rounded-xl p-8 max-w-6xl w-full mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold">افزودن محصول جدید</h3>
        </div>
        <?php if($errors->any()): ?>
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
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
        <form enctype="multipart/form-data" id="addProductForm" action="<?php echo e(route('admin.product.store')); ?>" method="POST" class="space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('POST'); ?>
            
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">عنوان محصول *</label>
                    <input type="text" name="title" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="مثال: Adobe Photoshop 2024">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">دسته‌بندی *</label>
                    <select name="category" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">همه دسته‌بندی‌ها</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>">
                                    <?php echo e($category->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <!-- Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">قیمت اصلی (تومان) *</label>
                    <input type="number" name="originalPrice" required min="0" id="originalPriceInput"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="مثال: 3000000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت محصول</label>
                    <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="active">فعال</option>
                        <option value="inactive">غیرفعال</option>
                        <option value="draft">پیش‌نویس</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت موجودی</label>
                    <select name="availability" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="true">در دسترس</option>
                        <option value="false">ناموجود</option>
                    </select>
                </div>
            </div>

            <!-- Tags -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">برچسب‌ها</label>
                <select name="tag" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">همه تگ ها</option>
                        <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tag->id); ?>">
                                <?php echo e($tag->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Key Features Section -->
            <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">ویژگی‌های کلیدی محصول</h3>
                </div>
                
                <!-- Step 1: Ask for number of key features -->
                <div id="keyFeaturesCountStep" class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">تعداد ویژگی‌های کلیدی مورد نظر:</label>
                    <select id="keyFeaturesCountSelect" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="0">لطفا تعداد ویژگی‌ها را انتخاب کنید</option>
                        <option value="1">1 ویژگی</option>
                        <option value="2">2 ویژگی</option>
                        <option value="3">3 ویژگی</option>
                        <option value="4">4 ویژگی</option>
                        <option value="5">5 ویژگی</option>
                        <option value="6">6 ویژگی</option>
                        <option value="7">7 ویژگی</option>
                        <option value="8">8 ویژگی</option>
                        <option value="9">9 ویژگی</option>
                        <option value="10">10 ویژگی</option>
                    </select>
                </div>
                
                <!-- Step 2: Key features input containers -->
                <div id="keyFeaturesContainers" class="space-y-4 hidden">
                    <!-- Key feature input fields will be generated here -->
                </div>
            </div>

            <!-- Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">تصاویر محصول</label>
                
                <!-- Step 1: Ask for number of images -->
                <div id="imageCountStep" class="mb-4">
                    <label class="block text-sm text-gray-600 mb-2">تعداد تصاویر مورد نظر را انتخاب کنید:</label>
                    <select id="imageCountSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" onchange="initImageUpload()">
                        <option value="0">لطفا تعداد را انتخاب کنید</option>
                        <option value="1">1 تصویر</option>
                        <option value="2">2 تصویر</option>
                        <option value="3">3 تصویر</option>
                        <option value="4">4 تصویر</option>
                        <option value="5">5 تصویر</option>
                        <option value="6">6 تصویر</option>
                    </select>
                </div>
            
                <!-- Step 2: Upload containers -->
                <div id="uploadContainers" class="space-y-4 hidden">
                    <!-- Upload containers will be generated here -->
                </div>
            
                <!-- Hidden inputs for storing image data -->
                <div id="hiddenImageInputs"></div>
            </div>
            
            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات محصول</label>
                <textarea name="description" rows="4" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="توضیحات کاملی از محصول..."></textarea>
            </div>

            <!-- Actions -->
            <div class="flex gap-4 pt-4 border-t">
                <button type="submit" 
                        class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    <i class="fas fa-plus ml-1"></i>افزودن محصول
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
        
        // Key Features Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const keyFeaturesCountSelect = document.getElementById('keyFeaturesCountSelect');
            
            keyFeaturesCountSelect.addEventListener('change', function() {
                const selectedCount = parseInt(this.value);
                generateKeyFeaturesInputs(selectedCount);
            });
        });
        
        function generateKeyFeaturesInputs(count) {
            const container = document.getElementById('keyFeaturesContainers');
            
            if (count === 0) {
                container.classList.add('hidden');
                container.innerHTML = '';
                return;
            }
            
            container.classList.remove('hidden');
            container.innerHTML = '';
            
            for (let i = 1; i <= count; i++) {
                const featureDiv = document.createElement('div');
                featureDiv.className = 'bg-white p-4 rounded-lg border border-gray-200';
                featureDiv.innerHTML = `
                    <label class="block text-sm font-medium text-gray-700 mb-2">ویژگی کلیدی ${i}</label>
                    <input type="text" 
                           name="key_features[]" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="ویژگی ${i} را وارد کنید (مثال: قابلیت نصب روی ویندوز و مک)">
                `;
                container.appendChild(featureDiv);
            }
        }
        
        function initImageUpload() {
            const countSelect = document.getElementById('imageCountSelect');
            const selectedCount = parseInt(countSelect.value);
            
            if (selectedCount === 0) {
                // Hide upload containers if "لطفا تعداد را انتخاب کنید" is selected
                document.getElementById('uploadContainers').classList.add('hidden');
                document.getElementById('uploadContainers').innerHTML = '';
                document.getElementById('hiddenImageInputs').innerHTML = '';
                maxImages = 0;
                currentUploaded = 0;
                return;
            }
            
            maxImages = selectedCount;
            currentUploaded = 0;
            
            // Show upload containers
            const uploadContainers = document.getElementById('uploadContainers');
            uploadContainers.classList.remove('hidden');
            
            // Generate upload containers
            uploadContainers.innerHTML = '';
            for (let i = 1; i <= maxImages; i++) {
                uploadContainers.innerHTML += createUploadContainer(i);
            }
            
            // Clear hidden inputs
            document.getElementById('hiddenImageInputs').innerHTML = '';
            
            // Remove completion message if exists
            const completionMsg = document.querySelector('.completion-message');
            if (completionMsg) {
                completionMsg.remove();
            }
        }
        
        function createUploadContainer(index) {
            return `
            <div class="border border-gray-300 rounded-lg p-4 upload-container" data-index="${index}">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">تصویر ${index}</span>
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
        
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById(`previewImage${index}`);
                const previewContainer = document.getElementById(`previewContainer${index}`);
                const uploadArea = document.querySelector(`.upload-area-${index}`);
                
                previewImg.src = e.target.result;
                previewContainer.classList.remove('hidden');
                uploadArea.classList.add('hidden');
                
                // Store image data
                storeImageData(index, e.target.result);
                
                // Update counter
                currentUploaded++;
                checkUploadCompletion();
            };
            reader.readAsDataURL(file);
        }
        
        function storeImageData(index, imageData) {
            const hiddenInputs = document.getElementById('hiddenImageInputs');
            
            // Remove existing input for this index
            const existingInput = document.getElementById(`hiddenImage${index}`);
            if (existingInput) {
                existingInput.remove();
            }
            
            // Create new hidden input
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
            
            // Update counter
            if (document.getElementById(`previewImage${index}`).src) {
                currentUploaded--;
            }
            
            // Remove completion message and enable upload areas
            const completionMsg = document.querySelector('.completion-message');
            if (completionMsg) {
                completionMsg.remove();
            }
            
            // Re-enable all upload areas
            const uploadContainers = document.querySelectorAll('.upload-container');
            uploadContainers.forEach(container => {
                const idx = container.dataset.index;
                const area = document.querySelector(`.upload-area-${idx}`);
                if (area) {
                    area.style.opacity = '1';
                    area.style.pointerEvents = 'auto';
                }
            });
        }
        
        function checkUploadCompletion() {
            if (currentUploaded >= maxImages) {
                // Hide all upload areas
                const uploadContainers = document.querySelectorAll('.upload-container');
                uploadContainers.forEach(container => {
                    const index = container.dataset.index;
                    const uploadArea = document.querySelector(`.upload-area-${index}`);
                    if (uploadArea) {
                        uploadArea.style.opacity = '0.5';
                        uploadArea.style.pointerEvents = 'none';
                    }
                });
                
                // Show completion message
                const completionMsg = document.createElement('div');
                completionMsg.className = 'bg-green-50 border border-green-200 rounded-lg p-3 mt-4';
                completionMsg.innerHTML = `
                    <div class="flex items-center text-green-700">
                        <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>همه تصاویر با موفقیت آپلود شدند</span>
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

        function closeErrorAlert() {
            document.getElementById('errorAlert').style.display = 'none';
        }
    </script>
    <?php $__env->startSection('js'); ?>
        <script src="<?php echo e(asset('js/admin/category.js')); ?>"></script>
    <?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.partials.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/admin/layouts/sections/product/add-product.blade.php ENDPATH**/ ?>