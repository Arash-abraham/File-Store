<?php $__env->startSection('content'); ?>
    <div id="products" class="content-section">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">مدیریت محصولات</h2>
                <a href="<?php echo e(route('admin.product.create')); ?>">
                    <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors" onclick="showAddProductModal()">
                        <i class="fas fa-plus ml-2"></i>افزودن محصول
                    </button>
                </a>
            </div>
            
            <!-- Success Alert -->
            <?php if(session('success')): ?>
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
                                <?php echo e(session('success')); ?>

                            </li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Search and Filter Form -->
            <form action="" method="GET" class="flex flex-col md:flex-row gap-4 mb-6">
                <!-- Search Input -->
                <div class="flex-1 relative">
                    <input type="text" name="search" id="productSearch" placeholder="جستجوی محصولات..." 
                           class="w-full px-4 py-3 pl-12 pr-4 border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 text-right bg-white">
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
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>">
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-4"><?php echo e($product->id); ?></td>
                                <td class="p-4">
                                    <?php if($product->image_urls && count($product->image_urls) > 0): ?>
                                        <img src="<?php echo e(asset($product->image_urls[0])); ?>" 
                                             class="w-16 h-16 rounded-lg object-cover" 
                                             alt="تصویر محصول <?php echo e($product->title); ?>">
                                    <?php else: ?>
                                        <span class="text-gray-500">بدون تصویر</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 font-semibold"><?php echo e($product->title); ?></td>
                                <td class="p-4"><?php echo e($product->category->name); ?></td>
                                <td class="p-4"><?php echo e(number_format($product->original_price)); ?> تومان</td>
                                <?php if($product->status == 'active'): ?>
                                    <td class="p-4">
                                        <span class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">فعال</span>
                                    </td>
                                <?php elseif($product->status == 'inactive'): ?>
                                    <td class="p-4">
                                        <span class="bg-yellow-100 text-yellow-800 text-sm px-3 py-1 rounded-full">پیش‌نویس</span>
                                    </td>
                                <?php else: ?>
                                    <td class="p-4">
                                        <span class="bg-red-100 text-red-800 text-sm px-3 py-1 rounded-full">غیرفعال</span>
                                    </td>
                                <?php endif; ?>
                                <?php if($product->availability): ?>
                                    <td class="p-4">
                                        <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">دردسترس</span>
                                    </td>
                                <?php else: ?>
                                    <td class="p-4">
                                        <span class="bg-red-100 text-red-800 text-sm px-3 py-1 rounded-full">ناموجود</span>
                                    </td>
                                <?php endif; ?>
                                <td class="p-4">
                                    <div class="flex gap-2">
                                        <a href="<?php echo e(route('admin.product.edit', $product->id)); ?>">
                                            <button class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </a>
                                        <form action="<?php echo e(route('admin.product.destroy', $product->id)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-800" 
                                                    onclick="return confirm('آیا مطمئن هستید؟')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="p-4 text-center text-gray-500">محصولی یافت نشد.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php $__env->startSection('js'); ?>
        <script>
            function closeSuccessAlert() {
                document.getElementById('successAlert').style.display = 'none';
            }
        </script>
    <?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.partials.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/admin/layouts/sections/product/products.blade.php ENDPATH**/ ?>