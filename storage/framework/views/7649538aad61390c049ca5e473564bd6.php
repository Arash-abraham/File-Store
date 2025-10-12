<?php $__env->startSection('title','محصولات'); ?>

<?php $__env->startSection('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/products.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div id="main-content">

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
                        <li id="breadcrumbCategory" class="text-gray-500">محصولات</li>
                    </ol>
                </nav>
            </div>
        </section>

        <!-- Category Header -->
        <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
            <div class="container mx-auto px-4 text-center">
                <h1 id="categoryTitle" class="text-4xl font-bold mb-4">محصولات</h1>
                <p id="categoryDescription" class="text-xl opacity-90">بهترین محصولات کاربردی و حرفه‌ای</p>
            </div>
        </section>

        <!-- Filters & Products -->
        <section class="py-12">
            <div class="container mx-auto px-4">
                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Sidebar Filters -->
                    <?php if($products->count() != 0): ?>
                        <div class="lg:w-1/4">
                            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                                <h3 class="text-lg font-bold mb-6">فیلترها</h3>
                                
                                <form id="filter-form" method="GET" action="<?php echo e(route('products')); ?>">
                                    <!-- فیلد مخفی برای حفظ category -->
                                    <?php if($selectedCategory): ?>
                                        <input type="hidden" name="category" value="<?php echo e($selectedCategory); ?>">
                                    <?php endif; ?>
                                    
                                    <!-- Availability Filter -->
                                    <div class="mb-6">
                                        <h4 class="font-semibold mb-3">وضعیت موجودی</h4>
                                        <div class="space-y-2">
                                            <label class="flex items-center">
                                                <input type="radio" name="availability" value="available" 
                                                    <?php echo e(request('availability') === 'available' ? 'checked' : ''); ?>

                                                    class="ml-2 text-blue-600 focus:ring-blue-500">
                                                <span>فقط موجودها</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="availability" value="unavailable"
                                                    <?php echo e(request('availability') === 'unavailable' ? 'checked' : ''); ?>

                                                    class="ml-2 text-blue-600 focus:ring-blue-500">
                                                <span>ناموجود</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="availability" value="all"
                                                    <?php echo e(!request('availability') || request('availability') === 'all' ? 'checked' : ''); ?>

                                                    class="ml-2 text-blue-600 focus:ring-blue-500">
                                                <span>همه</span>
                                            </label>
                                        </div>
                                    </div>
                    
                                    <div class="mb-6">
                                        <h4 class="font-semibold mb-3">بازه قیمتی (تومان)</h4>
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <input type="number" name="price_min" 
                                                value="<?php echo e(request('price_min')); ?>" 
                                                placeholder="حداقل"
                                                min="0"
                                                class="flex-1 min-w-[120px] p-2 border rounded-lg">
                                            <span class="text-gray-500">تا</span>
                                            <input type="number" name="price_max" 
                                                value="<?php echo e(request('price_max')); ?>"
                                                placeholder="حداکثر"
                                                min="0" 
                                                class="flex-1 min-w-[120px] p-2 border rounded-lg">
                                        </div>
                                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                                            اعمال فیلتر قیمت
                                        </button>
                                    </div>
                                    
                                    <!-- Category Filter - فقط وقتی که category در URL نیست -->
                                    <?php if(!$selectedCategory): ?>
                                        <div class="mb-6">
                                            <h4 class="font-semibold mb-3">دسته بندی</h4>
                                            <div class="space-y-2 max-h-60 overflow-y-auto">
                                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <label class="flex items-center">
                                                    <input type="checkbox" name="categories[]" 
                                                        value="<?php echo e($category->id); ?>"
                                                        <?php echo e(in_array($category->id, (array)request('categories', [])) ? 'checked' : ''); ?>

                                                        class="category-filter ml-2 text-blue-600 focus:ring-blue-500">
                                                    <span><?php echo e($category->name); ?></span>
                                                </label>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Clear Filters -->
                                    <a href="<?php echo e(route('products')); ?>" 
                                    class="block w-full text-center border border-gray-300 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                                        پاک کردن فیلترها
                                    </a>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- Products Grid -->
                    <div class="lg:w-3/4">                        
                        <!-- Products -->
                        <div id="productsContainer" class="container mx-auto py-10 px-4">
                            <?php if($products->count() == 0): ?>
                                <div class="fixed inset-0 flex items-center justify-center bg-white z-50">
                                    <div class="text-center">
                                        <i class="fas fa-search text-6xl text-gray-400 mb-6"></i>
                                        <p class="text-2xl font-bold text-gray-700 mb-4">محصولی یافت نشد</p>
                                        <p class="text-lg text-gray-500 mb-8">لطفاً دسته بندی خود را تغییر دهید</p>
                                        <a href="<?php echo e(route('products')); ?>" 
                                        class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors text-lg">
                                            مشاهده همه محصولات
                                        </a>
                                    </div>
                            </div>
                            <?php else: ?>
                                <div id="productsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
                                            <div class="relative overflow-hidden">
                                                <a href="<?php echo e(route('show-product', $product->id)); ?>">
                                                    <img src="<?php echo e(asset($product->image_urls[0])); ?>" alt="<?php echo e($product->title); ?>" 
                                                        class="w-full h-48 object-cover">
                                                    <?php if(!$product->availability): ?>
                                                        <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">ناموجود</span>
                                                    <?php endif; ?>
                                                    </a>
                                                <div class="p-6">
                                                    <a href="<?php echo e(route('show-product', $product->id)); ?>">
                                                        <h3 class="font-semibold text-lg mb-2 text-gray-800 line-clamp-2"><?php echo e($product->title); ?></h3>    
                                                    </a>                                    
                                                    <div class="flex items-center mb-3">
                                                        <span class="text-gray-500 text-sm mr-2"><?php echo e($product->category->name); ?></span>
                                                    </div>
                                                    
                                                    <div class="flex items-center justify-between mb-4">
                                                        <div>
                                                            <span class="text-xl font-bold text-green-600"><?php echo e(number_format($product->original_price)); ?> تومان</span>                        
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex gap-2">
                                                        <?php if($product->availability): ?>
                                                            <form action="<?php echo e(route('cart.add')); ?>" method="POST" class="flex-1">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                                                                <button type="submit" 
                                                                        class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                                                                    <i class="fas fa-shopping-cart"></i>
                                                                    افزودن به سبد
                                                                </button>
                                                            </form>
                                                            <a href="<?php echo e(route('show-product', $product->id)); ?>">
                                                                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            </a>
                                                        <?php else: ?> 
                                                            <button class="w-full flex-1 bg-gray-400 text-white py-2 rounded-lg cursor-not-allowed flex items-center justify-center gap-1">
                                                                <i class="fas fa-shopping-cart"></i> افزودن به سبد
                                                            </button>
                                                            <a href="<?php echo e(route('show-product', $product->id)); ?>">
                                                                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('js/products.js')); ?>"></script>
    <script src="<?php echo e(asset('js/modal.js')); ?>"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('app.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/app/products.blade.php ENDPATH**/ ?>