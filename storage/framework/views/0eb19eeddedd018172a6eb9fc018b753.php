<div class="lg:w-1/4">
    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
        <nav class="space-y-2">
            <a href="#" class="sidebar-item active flex items-center p-3 rounded-lg transition-colors" data-section="dashboard">
                <i class="fas fa-tachometer-alt w-5 ml-3"></i>
                <span>داشبورد</span>
            </a>
            <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="orders">
                <i class="fas fa-shopping-bag w-5 ml-3"></i>
                <span>سفارش‌های من</span>
            </a>
            <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="downloads">
                <i class="fas fa-download w-5 ml-3"></i>
                <span>دانلودها</span>
            </a>
            <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="tickets">
                <i class="fas fa-ticket-alt w-5 ml-3"></i>
                <span>تیکت‌های پشتیبانی</span>
            </a>
            <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="wallet">
                <i class="fas fa-wallet w-5 ml-3"></i>
                <span>کیف پول</span>
            </a>
            <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="profile">
                <i class="fas fa-user w-5 ml-3"></i>
                <span>ویرایش پروفایل</span>
            </a>
            <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="password">
                <i class="fas fa-key w-5 ml-3"></i>
                <span>تغییر رمز عبور</span>
            </a>
            <hr class="my-4">
            <form method="POST" action="<?php echo e(route('logout')); ?>" class="flex items-center p-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                <?php echo csrf_field(); ?>
                
                <button>
                    <i class="fas fa-sign-out-alt w-5 ml-3"></i>
                    خروج
                </button>
            </form>
            
        </nav>
    </div>
</div>
<?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/layouts/partials/sidebar.blade.php ENDPATH**/ ?>