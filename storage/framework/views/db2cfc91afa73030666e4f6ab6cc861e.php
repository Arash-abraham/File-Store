<!-- Header -->
<header class="bg-white shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <!-- Top Bar -->
        <div class="border-b border-gray-200 py-2">
            <div class="flex justify-between items-center text-sm text-gray-600">
                <div class="flex items-center gap-4">
                    <span><i class="fas fa-phone ml-1"></i>پشتیبانی: 021-12345678</span>
                    <span><i class="fas fa-envelope ml-1"></i>info@shop.com</span>
                </div>
                <?php if(auth()->guard()->guest()): ?>
                    <div class="flex items-center gap-4">
                        <a href="<?php echo e(route('login')); ?>" class="hover:text-blue-600 transition-colors">ورود / ثبت‌نام</a>
                    </div>
                <?php else: ?>
                    <div class="flex items-center gap-4">
                        <a href="<?php echo e(route('dashboard')); ?>" class="hover:text-blue-600 transition-colors"><?php echo e(auth()->user()->name); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Main Header -->
        <div class="py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center">
                    <?php $__currentLoopData = $setting; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <h1 class="text-2xl font-bold text-gray-800"><?php echo e($item->site_title); ?></h1>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <!-- Search Bar -->
                <div class="flex-1 max-w-2xl mx-8">
                    <form action="<?php echo e(route('search')); ?>" method="GET" id="searchForm">
                        <div class="relative">
                            <input type="text" name="q" id="searchInput" placeholder="جستجوی محصولات، نویسندگان، ناشران..." 
                                value="<?php echo e(request('q')); ?>"
                                class="w-full px-4 py-3 pr-12 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:outline-none transition-colors">
                            <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-500 transition-colors">
                                <i class="fas fa-search"></i>
                            </button>
                            
                            <!-- Advanced Filters Button -->
                            <button type="button" id="filtersToggle" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-500 transition-colors">
                                <i class="fas fa-sliders-h"></i>
                            </button>
                        </div>
                        
                        <!-- Advanced Filters Panel -->
                        <div id="filtersPanel" class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 hidden">
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-lg font-bold text-gray-800">فیلترهای پیشرفته</h3>
                                    <button type="button" id="closeFilters" class="text-gray-400 hover:text-gray-600 transition-colors">
                                        <i class="fas fa-times text-xl"></i>
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                    <!-- Category Filter -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3">دسته‌بندی</label>
                                        <select name="category" id="categoryFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">همه دسته‌بندی‌ها</option>
                                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($category->id); ?>" <?php echo e(request('category') == $category->id ? 'selected' : ''); ?>>
                                                    <?php echo e($category->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    
                                    <!-- Price Range -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3">محدوده قیمت</label>
                                        <select name="price" id="priceFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">همه قیمت‌ها</option>
                                            <option value="0-50000" <?php echo e(request('price') == '0-50000' ? 'selected' : ''); ?>>زیر ۵۰,۰۰۰ تومان</option>
                                            <option value="50000-100000" <?php echo e(request('price') == '50000-100000' ? 'selected' : ''); ?>>۵۰,۰۰۰ تا ۱۰۰,۰۰۰ تومان</option>
                                            <option value="100000-200000" <?php echo e(request('price') == '100000-200000' ? 'selected' : ''); ?>>۱۰۰,۰۰۰ تا ۲۰۰,۰۰۰ تومان</option>
                                            <option value="200000-500000" <?php echo e(request('price') == '200000-500000' ? 'selected' : ''); ?>>۲۰۰,۰۰۰ تا ۵۰۰,۰۰۰ تومان</option>
                                            <option value="500000-1000000" <?php echo e(request('price') == '500000-1000000' ? 'selected' : ''); ?>>۵۰۰,۰۰۰ تا ۱,۰۰۰,۰۰۰ تومان</option>
                                            <option value="1000000" <?php echo e(request('price') == '1000000' ? 'selected' : ''); ?>>بالای ۱,۰۰۰,۰۰۰ تومان</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Availability -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3">وضعیت موجودی</label>
                                        <select name="availability" id="availabilityFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">همه محصولات</option>
                                            <option value="1" <?php echo e(request('availability') == '1' ? 'selected' : ''); ?>>فقط موجود</option>
                                            <option value="0" <?php echo e(request('availability') == '0' ? 'selected' : ''); ?>>فقط ناموجود</option>
                                        </select>
                                    </div>
                                    
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200">
                                    <button type="button" id="resetFilters" class="text-gray-600 hover:text-gray-800 transition-colors flex items-center gap-2">
                                        <i class="fas fa-redo"></i>
                                        بازنشانی فیلترها
                                    </button>
                                    <div class="flex gap-3">
                                        <button type="button" id="applyFilters" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                                            اعمال فیلترها
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                
                <!-- Cart & User Actions -->
                <div class="flex items-center gap-4"> 
                    <button id="cart-toggle" class="relative p-3 text-gray-600 hover:text-blue-600 transition-colors">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span id="cart-count" class="absolute -top-1 -right-1 bg-blue-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center"><?php echo e($count); ?></span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="border-t border-gray-200 py-4">
            <ul class="flex justify-center gap-8 items-center">
                <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <a href="<?php echo e(asset($menu->url)); ?>" 
                        class="nav-link text-black hover:text-blue-600 transition-colors">
                            <?php echo e($menu->title); ?>

                        </a>
                    </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </ul>
            <a href=""></a>
        </nav>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filtersToggle = document.getElementById('filtersToggle');
            const filtersPanel = document.getElementById('filtersPanel');
            const closeFilters = document.getElementById('closeFilters');
            const applyFilters = document.getElementById('applyFilters');
            const resetFilters = document.getElementById('resetFilters');
            const searchForm = document.getElementById('searchForm');
        
            // Toggle filters panel
            filtersToggle.addEventListener('click', function() {
                filtersPanel.classList.toggle('hidden');
            });
        
            // Close filters panel
            closeFilters.addEventListener('click', function() {
                filtersPanel.classList.add('hidden');
            });
        
            // Apply filters and submit form
            applyFilters.addEventListener('click', function() {
                filtersPanel.classList.add('hidden');
                searchForm.submit();
            });
        
            // Reset filters
            resetFilters.addEventListener('click', function() {
                document.getElementById('categoryFilter').value = '';
                document.getElementById('priceFilter').value = '';
                document.getElementById('availabilityFilter').value = '';
                document.getElementById('sortFilter').value = 'newest';
                document.getElementById('searchInput').value = '';
                searchForm.submit();
            });
        
            // Close filters when clicking outside
            document.addEventListener('click', function(event) {
                if (!filtersPanel.contains(event.target) && !filtersToggle.contains(event.target)) {
                    filtersPanel.classList.add('hidden');
                }
            });
        
            // Auto-submit on Enter key in search input
            document.getElementById('searchInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchForm.submit();
                }
            });
        });
        </script>
</header>

<?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/app/layouts/partials/header.blade.php ENDPATH**/ ?>