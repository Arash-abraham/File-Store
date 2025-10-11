<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <?php echo $__env->make('admin.layouts.head-tag', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>
<body class="font-sans bg-gray-50">
    <!-- Header -->
    <?php echo $__env->make('admin.layouts.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <?php echo $__env->make('admin.layouts.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <!-- Main Content -->
            <div class="lg:w-3/4">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>
    
    <!-- Add Menu Modal -->
        

    <!-- Edit Menu Modal -->    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="<?php echo e(asset('js/admin.js')); ?>"></script>
    <script src="<?php echo e(asset('js/ckeditor/ckeditor.js')); ?>"></script>
    <script src="<?php echo e(asset('js/jalalidatepicker/persian-date.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/jalalidatepicker/persian-datepicker.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/date.js')); ?>"></script>
    <?php echo $__env->yieldContent('js'); ?>
    
</body>
</html><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/admin/layouts/partials/master.blade.php ENDPATH**/ ?>