<div id="profile" class="content-section hidden">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">ویرایش پروفایل</h2>
        

        <form action="<?php echo e(route('profile.update')); ?>" method="POST" class="space-y-6">
            <?php echo csrf_field(); ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نام کاربری</label>
                    <input type="text" name="name" value="<?php echo e(old('name', auth()->user()->name)); ?>" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                           required>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ایمیل</label>
                <input type="email" name="email" value="<?php echo e(old('email', auth()->user()->email)); ?>" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                       required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">شماره تماس</label>
                <input type="tel" name="phone" value="<?php echo e(old('phone', auth()->user()->phone)); ?>" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                       placeholder="09123456789">
            </div>
            
            
            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    ذخیره تغییرات
                </button>
            </div>
        </form>
    </div>
</div><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/layouts/main/profile-section.blade.php ENDPATH**/ ?>