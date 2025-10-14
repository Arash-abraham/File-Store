<div id="dashboard" class="content-section">
    <div class="bg-white rounded-3xl shadow-2xl p-8 mb-8 border border-gray-100 min-h-[200px]">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between h-full">
            <!-- Text Content -->
            <div class="flex-1">
                <div class="flex items-center space-x-3 space-x-reverse mb-4">
                    <div class="w-3 h-8 bg-gradient-to-t from-blue-500 to-purple-600 rounded-full"></div>
                    <h2 class="text-2xl font-bold text-gray-800">سلام <?php echo e(auth()->user()->name); ?> عزیز</h2>
                </div>
                <p class="text-gray-600 text-lg mb-6">به پنل کاربری خود خوش آمدید.</p>
                
            </div>
            
            <!-- Graphic Element -->
            <div class="hidden md:block flex-shrink-0">
                <div class="relative">
                    <div class="w-32 h-32 bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-600">کاربر فعال</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Date & Time -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex gap-4 text-sm text-gray-500">
                <div class="flex items-center">
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <?php echo e(jdate()->format('Y/m/d')); ?>

                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <?php echo e(jdate()->format('H:i')); ?>

                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/layouts/main/dashboard-section.blade.php ENDPATH**/ ?>