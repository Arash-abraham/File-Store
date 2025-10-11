<header class="bg-white shadow-lg sticky top-0 z-40">
    <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <img src="https://images.pexels.com/photos/1181533/pexels-photo-1181533.jpeg?auto=compress&cs=tinysrgb&w=100" 
                     alt="لوگو فروشگاه" class="h-12 w-12 rounded-lg ml-3">
                <h1 class="text-2xl font-bold text-gray-800">پنل مدیریت</h1>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-crown ml-2 text-yellow-500"></i>
                    <span>مدیر: <span class="font-semibold text-purple-600"><?php echo e(auth()->user()->name); ?></span></span>
                </div>

                <a href="<?php echo e(route('home')); ?>" class="text-blue-600 hover:text-blue-800 transition-colors">
                    <i class="fas fa-home text-xl"></i>
                </a>
            </div>
        </div>
    </div>
</header><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/admin/layouts/partials/header.blade.php ENDPATH**/ ?>