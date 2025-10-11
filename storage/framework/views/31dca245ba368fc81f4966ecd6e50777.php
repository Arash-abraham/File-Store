<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <?php echo $__env->make('app.layouts.head-tag', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>
<body class="font-sans bg-gray-50">
    <?php echo $__env->make('app.layouts.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <!-- Main Content -->
    <div id="main-content">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <?php echo $__env->make('app.layouts.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/app/layouts/master.blade.php ENDPATH**/ ?>