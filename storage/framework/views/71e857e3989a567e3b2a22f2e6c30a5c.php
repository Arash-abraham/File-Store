<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <?php echo $__env->make('layouts.head-tag', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>

<body class="font-sans bg-gray-50">
    <?php echo $__env->make('layouts.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <?php echo $__env->make('layouts.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <!-- Dashboard Section -->
                <?php echo $__env->make('layouts.main.dashboard-section', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <!-- Orders Section -->
                <?php echo $__env->make('layouts.main.orders-section', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <!-- Downloads Section -->
                <?php echo $__env->make('layouts.main.downloads-section', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


                <!-- Tickets Section -->
                <?php echo $__env->make('layouts.main.tickets-section', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


                <!-- Wallet Section -->
                <?php echo $__env->make('layouts.main.wallet-section', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


                <!-- Profile Section -->
                <?php echo $__env->make('layouts.main.profile-section', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


                <!-- Password Section -->
                <?php echo $__env->make('layouts.main.password-section', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            </div>
        </div>
    </div>

    <!-- New Ticket Modal -->
    <?php echo $__env->make('layouts.main.new-ticket-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script src="<?php echo e(asset('js/dashboard.js')); ?>"></script>
</body>
</html><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/dashboard.blade.php ENDPATH**/ ?>