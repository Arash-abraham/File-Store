<div id="downloads" class="content-section hidden">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">لیست دانلودها</h2>
        
        <?php if($downloadableFiles->count() > 0): ?>
            <div class="space-y-4">
                <?php $__currentLoopData = $downloadableFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center ml-4 
                                    <?php if($file->type === 'pdf'): ?> bg-red-100 text-red-600
                                    <?php elseif($file->type === 'zip'): ?> bg-blue-100 text-blue-600
                                    <?php elseif($file->type === 'rar'): ?> bg-purple-100 text-purple-600
                                    <?php else: ?> bg-gray-100 text-gray-600 <?php endif; ?>">
                                    <?php if($file->type === 'pdf'): ?>
                                        <i class="fas fa-file-pdf text-xl"></i>
                                    <?php elseif($file->type === 'zip' || $file->type === 'rar'): ?>
                                        <i class="fas fa-file-archive text-xl"></i>
                                    <?php else: ?>
                                        <i class="fas fa-file text-xl"></i>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800"><?php echo e($file->name); ?></h3>
                                    <div class="flex items-center gap-4 mt-1">
                                        <span class="text-sm text-gray-500">
                                            <?php if($file->size_label): ?>
                                                <?php echo e($file->size_label); ?>

                                            <?php else: ?>
                                                <?php echo e($file->formatted_size); ?>

                                            <?php endif; ?>
                                        </span>
                                        <span class="text-xs px-2 py-1 rounded-full 
                                            <?php if($file->type === 'pdf'): ?> bg-red-100 text-red-800
                                            <?php elseif($file->type === 'zip'): ?> bg-blue-100 text-blue-800
                                            <?php elseif($file->type === 'rar'): ?> bg-purple-100 text-purple-800
                                            <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                            <?php echo e($file->type_label); ?>

                                        </span>
                                        <span class="text-xs text-gray-500">
                                            محصول: <?php echo e($file->product->title ?? 'نامشخص'); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="<?php echo e(route('product-files.download', $file->id)); ?>" 
                                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                                    <i class="fas fa-download"></i>
                                    دانلود
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-download text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">فایلی برای دانلود وجود ندارد</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    شما هنوز هیچ محصولی خریداری نکرده‌اید یا محصولات خریداری شده فایل دانلودی ندارند.
                </p>
                <a href="<?php echo e(route('products')); ?>" 
                   class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    مشاهده محصولات
                </a>
            </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/layouts/main/downloads-section.blade.php ENDPATH**/ ?>