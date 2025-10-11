<?php $__env->startSection('title', $product->title); ?>

<?php $__env->startSection('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/products.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div id="cart-modal" class="fixed w-80 bg-white text-gray-800 rounded-xl shadow-2xl p-0 hidden z-50 border border-gray-200">
        <div class="flex justify-between items-center p-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-t-xl">
            <h2 class="text-lg font-bold">سبد خرید</h2>
            <button id="close-cart" class="text-white hover:bg-white/20 p-1 rounded-full transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div id="cart-content" class="max-h-64 overflow-y-auto p-4 space-y-3">
            <?php if($cartItems->isEmpty()): ?>
                <p class="text-center text-gray-500 text-sm">سبد خرید خالی است</p>
            <?php else: ?>
                <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <img src="<?php echo e(asset($item->product->image_urls[0] ?? 'images/placeholder.jpg')); ?>" 
                                alt="<?php echo e($item->product->title); ?>" class="w-12 h-12 object-cover rounded-lg shadow-sm">
                            <div>
                                <h3 class="text-sm font-semibold"><?php echo e($item->product->title); ?></h3>
                                <p class="text-xs text-purple-600 font-medium"><?php echo e(number_format($item->unit_price)); ?> تومان</p>
                            </div>
                        </div>
                        <form action="<?php echo e(route('cart.remove', $item->id)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-full transition-colors">
                                <i class="fas fa-trash-alt text-sm"></i>
                            </button>
                        </form>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
        
        <div class="p-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm text-gray-600">جمع کل:</span>
                <span class="text-lg font-bold text-purple-700"><?php echo e(number_format($total)); ?> تومان</span>
            </div>
            <?php if(!$cartItems->isEmpty()): ?>
                <a href="<?php echo e(route('checkout.show')); ?>" 
                class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white py-3 rounded-lg font-semibold transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                    <i class="fas fa-credit-card"></i>
                    تسویه حساب
                </a>
            <?php endif; ?>
        </div>
    </div>     

    <!-- Breadcrumb -->
    <section class="bg-white py-4">
        <div class="container mx-auto px-4">
            <nav class="text-sm text-gray-600">
                <ol class="list-none p-0 inline-flex">
                    <li class="flex items-center">
                        <a href="<?php echo e(route('home')); ?>" class="hover:text-blue-600">خانه</a>
                        <i class="fas fa-chevron-left mx-2"></i>
                    </li>
                    <li class="flex items-center">
                        <a href="<?php echo e(route('productsWithCategory', ['category' => $product->category->id])); ?>" class="hover:text-blue-600"><?php echo e($product->category->name); ?></a>
                        <i class="fas fa-chevron-left mx-2"></i>
                    </li>
                    <li class="text-gray-500"><?php echo e($product->title); ?></li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Product Image -->
                <div>
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-center mb-6">
                            <img id="productImage" src="<?php echo e(asset($product->image_urls[0] ?? 'images/placeholder.jpg')); ?>" 
                                 alt="<?php echo e($product->title); ?>" class="max-w-full h-80 object-contain rounded-lg">
                        </div>
                        <div class="flex gap-3 justify-center flex-wrap">
                            <?php $__currentLoopData = $product->image_urls ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <img src="<?php echo e(asset($image)); ?>" 
                                     data-index="<?php echo e($index); ?>"
                                     class="w-16 h-16 rounded-lg cursor-pointer border-2 transition-all duration-200 thumbnail 
                                            <?php echo e($index === 0 ? 'border-blue-500 scale-105' : 'border-gray-200 hover:border-blue-400'); ?>"
                                     onclick="changeImage(this)">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div>
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <div class="flex items-center gap-2 mb-4">
                            <?php if($product->availability): ?>
                                <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">موجود</span>
                            <?php else: ?> 
                                <span class="bg-red-100 text-red-800 text-xs px-3 py-1 rounded-full">ناموجود</span>
                            <?php endif; ?>                            
                        </div>
                        
                        <h1 class="text-3xl font-bold text-gray-800 mb-4"><?php echo e($product->title); ?></h1>
                        
                        <div class="flex items-center gap-4 mb-6">
                            <div class="flex items-center text-yellow-500">
                                <span class="text-gray-600 mr-2">126 نظر</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center gap-4">
                                <span class="text-3xl font-bold text-green-600"><?php echo e(number_format($product->original_price)); ?> تومان</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-800 mb-3">ویژگی‌های کلیدی:</h3>
                            <ul class="space-y-2 text-gray-600">
                                <?php $__currentLoopData = $product->key_features ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 ml-2"></i><?php echo e($item); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>

                        <form action="<?php echo e(route('cart.add')); ?>" method="POST" class="flex gap-4 mb-6">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                            <input type="number" name="quantity" value="1" min="1" class="w-20 p-2 border rounded">
                            <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-xl hover:bg-blue-700 transition-colors font-semibold <?php echo e(!$product->availability ? 'opacity-50 cursor-not-allowed' : ''); ?>" 
                                    <?php echo e(!$product->availability ? 'disabled' : ''); ?>>
                                <i class="fas fa-shopping-cart ml-2"></i>افزودن به سبد خرید
                            </button>
                        </form>

                        <?php if(session('success')): ?>
                            <div class="text-green-600 mb-4"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>
                        <?php if(session('error')): ?>
                            <div class="text-red-600 mb-4"><?php echo e(session('error')); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tabs Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="bg-white rounded-xl shadow-lg">
                <div class="border-b">
                    <nav class="flex">
                        <button class="tab-button active px-8 py-4 font-semibold border-b-2 border-blue-500 text-blue-600" onclick="showTab('description')">
                            توضیحات
                        </button>
                        <button class="tab-button px-8 py-4 font-semibold text-gray-600 hover:text-blue-600 transition-colors" onclick="showTab('files')">
                            فایل‌های مرتبط
                        </button>
                        <button class="tab-button px-8 py-4 font-semibold text-gray-600 hover:text-blue-600 transition-colors" onclick="showTab('reviews')">
                            نظرات (126)
                        </button>
                    </nav>
                </div>

                <div class="p-8">
                    <div id="description" class="tab-content">
                        <h3 class="text-2xl font-bold mb-4">درباره <?php echo e($product->title); ?></h3>
                        <div class="prose max-w-none text-gray-700 leading-relaxed">
                            <p class="mb-4"><?php echo e($product->description); ?></p>
                        </div>
                    </div>

                    <div id="files" class="tab-content hidden">
                        <h3 class="text-2xl font-bold mb-6">فایل‌های قابل دانلود</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center ml-4">
                                        <i class="fas fa-file-archive text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold"><?php echo e($product->title); ?>_Setup.zip</h4>
                                        <p class="text-sm text-gray-500">نسخه کامل نرم‌افزار - 2.8 GB</p>
                                    </div>
                                </div>
                                <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    دانلود
                                </button>
                            </div>
                            <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center ml-4">
                                        <i class="fas fa-key text-green-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold">License_Key.txt</h4>
                                        <p class="text-sm text-gray-500">کلید فعالسازی - 1 KB</p>
                                    </div>
                                </div>
                                <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    دانلود
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="reviews" class="tab-content hidden">
                        <!-- محتوای نظرات -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">محصولات مرتبط</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- محتوای محصولات مرتبط -->
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        function changeImage(clickedImage) {
            const mainImage = document.getElementById('productImage');
            mainImage.src = clickedImage.src;
            const thumbnails = document.querySelectorAll('.thumbnail');
            thumbnails.forEach(thumb => {
                thumb.classList.remove('border-blue-500', 'scale-105');
                thumb.classList.add('border-gray-200');
            });
            clickedImage.classList.remove('border-gray-200');
            clickedImage.classList.add('border-blue-500', 'scale-105');
            mainImage.style.opacity = '0.7';
            setTimeout(() => mainImage.style.opacity = '1', 150);
        }

        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-b-2', 'border-blue-500', 'text-blue-600');
                button.classList.add('text-gray-600');
            });
            document.getElementById(tabName).classList.remove('hidden');
            event.target.classList.add('active', 'border-b-2', 'border-blue-500', 'text-blue-600');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const firstThumbnail = document.querySelector('.thumbnail');
            if (firstThumbnail) {
                firstThumbnail.classList.add('border-blue-500', 'scale-105');
            }
        });
        
    </script>
    <script src="<?php echo e(asset('js/modal.js')); ?>"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('app.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/app/show-product.blade.php ENDPATH**/ ?>