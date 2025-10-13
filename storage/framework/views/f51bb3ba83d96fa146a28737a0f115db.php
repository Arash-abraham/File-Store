<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <?php $__currentLoopData = $setting; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <h3 class="text-xl font-bold mb-4"><?php echo e($item->site_title); ?></h3>
                    <p class="text-gray-300 mb-4"><?php echo e($item->site_description); ?></p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div class="flex gap-4">
                    <a href="#" class="text-gray-300 hover:text-white transition-colors"><i class="fab fa-telegram"></i></a>
                    <a href="#" class="text-gray-300 hover:text-white transition-colors"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-300 hover:text-white transition-colors"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">دسترسی سریع</h4>
                <ul class="space-y-2">
                    <?php $__currentLoopData = $setting; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><a href="<?php echo e(route('about')); ?>" class="text-gray-300 hover:text-white transition-colors">درباره ما</a></li>
                        <li><a href="tel:<?php echo e($item->phone); ?>" class="text-gray-300 hover:text-white transition-colors">تماس با ما</a></li>
                        <li><a href="<?php echo e(route('faq')); ?>" class="text-gray-300 hover:text-white transition-colors">سوالات متداول</a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">دسته‌بندی‌ها</h4>
                <ul class="space-y-2">
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a href="<?php echo e(route('productsWithCategory', ['category' => $category->id])); ?>" class="text-gray-300 hover:text-white transition-colors">
                            <?php echo e($category->name); ?>

                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">اطلاعات تماس</h4>
                <?php $__currentLoopData = $setting; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <ul class="space-y-2 text-gray-300">
                        <li><i class="fas fa-map-marker-alt ml-2"></i><?php echo e($item->address); ?></li>
                        <li><i class="fas fa-phone ml-2"></i><?php echo e($item->phone); ?></li>
                        <li><i class="fas fa-envelope ml-2"></i><?php echo e($item->email); ?></li>
                    </ul>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; 2025  فایل استور. تمامی حقوق محفوظ است.</p>
        </div>
    </div>
</footer><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/app/layouts/partials/footer.blade.php ENDPATH**/ ?>