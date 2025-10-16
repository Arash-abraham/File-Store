<div id="orders" class="content-section hidden">
    <div class="bg-white rounded-2xl shadow-xl p-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-800">محصولات خریداری‌شده من</h2>
            <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-blue-500 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-bag text-white"></i>
            </div>
        </div>
        
        <div class="space-y-6">
            <?php $__empty_1 = true; $__currentLoopData = $purchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php if($payment->status == 'completed' && $payment->order): ?>
                <div class="bg-gradient-to-r from-white to-green-50 border border-green-100 rounded-2xl p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Header -->
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
                        <div class="flex items-center space-x-4 space-x-reverse mb-4 lg:mb-0">
                            <div class="relative">
                                <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-check text-white text-lg"></i>
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white"></div>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-lg">خرید #<?php echo e($payment->id); ?></h3>
                                <p class="text-sm text-gray-500 flex items-center mt-1">
                                    <i class="fas fa-calendar ml-1 text-gray-400"></i>
                                    <?php echo e($payment->created_at->format('Y/m/d - H:i')); ?>

                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <span class="px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg">
                                <i class="fas fa-check ml-1"></i>
                                تکمیل شده
                            </span>
                            
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-800">
                                    <?php echo e(number_format($payment->amount ?? $payment->order->total_amount ?? 0)); ?> تومان
                                </p>
                                <p class="text-xs text-gray-500">مبلغ کل</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Products Grid -->
                    <div class="border-t border-green-100 pt-6">
                        <h4 class="font-bold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-cubes ml-2 text-blue-500"></i>
                            محصولات خریداری شده
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
                            <?php $__empty_2 = true; $__currentLoopData = $payment->order->items ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                <div class="bg-white rounded-xl p-4 border border-gray-200 hover:border-green-300 transition-all duration-300 shadow-sm hover:shadow-md">
                                    <a href="<?php echo e(route('show-product', $item->product->id)); ?>">
                                        <div class="mb-3">
                                            <?php if($item->product && $item->product->image_urls && count($item->product->image_urls) > 0): ?>
                                                <img src="<?php echo e(asset($item->product->image_urls[0])); ?>" alt="<?php echo e($item->product->title); ?>" 
                                                    class="w-full h-28 object-cover rounded-lg mx-auto shadow-sm">
                                            <?php else: ?>
                                                <div class="w-full h-28 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mx-auto">
                                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Product Info -->
                                        <h5 class="font-semibold text-gray-800 mb-2 text-center line-clamp-2 leading-6" style="height: 3rem;">
                                            <?php echo e($item->product->title ?? 'محصول'); ?>

                                        </h5>
                                        <p>قیمت اصلی : <?php echo e(number_format($item->product->original_price)); ?> تومان</p>

                                    </a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                <div class="col-span-full text-center py-8">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-exclamation text-gray-400 text-xl"></i>
                                    </div>
                                    <p class="text-gray-500">هیچ محصولی یافت نشد</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="w-28 h-28 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <i class="fas fa-shopping-cart text-gray-400 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-3">هنوز خریدی نداشته‌اید</h3>
                    <p class="text-gray-500 text-lg mb-8 max-w-md mx-auto">با اولین خرید خود، تجربه‌ای لذت‌بخش از خرید آنلاین داشته باشید</p>
                    <a href="<?php echo e(route('products')); ?>" class="bg-gradient-to-r from-green-500 to-blue-600 hover:from-green-600 hover:to-blue-700 text-white px-8 py-4 rounded-2xl font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-lg inline-flex items-center">
                        <i class="fas fa-shopping-bag ml-3 text-lg"></i>
                        شروع خرید از فروشگاه
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/layouts/main/orders-section.blade.php ENDPATH**/ ?>