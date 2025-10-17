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
                    
                <?php if(session('success')): ?>
                    <div class="mt-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative">
                        <button type="button" 
                                class="close-btn absolute left-3 top-3 w-6 h-6 flex items-center justify-center text-green-600 hover:text-green-800 hover:bg-green-200 rounded-full transition-colors"
                                onclick="this.parentElement.style.display='none'">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                        <div class="flex items-center pr-8">
                            <i class="fas fa-check-circle ml-2"></i>
                            <?php echo e(session('success')); ?>

                            <?php if(session('ref_id')): ?>
                                <span class="mr-4">کد پیگیری: <strong><?php echo e(session('ref_id')); ?></strong></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
    
                <?php if($errors->any()): ?>
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg relative">
                        <span class="close-btn" onclick="this.parentElement.style.display='none'">&times;</span>
                        <ul class="list-disc list-inside">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
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