<div class="lg:w-1/4">
    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
        <nav class="space-y-2">
            <a href="#" class="sidebar-item active flex items-center p-3 rounded-lg transition-colors" data-section="dashboard">
                <i class="fas fa-chart-pie w-5 ml-3"></i>
                <span>داشبورد</span>
            </a>
            <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="products">
                <i class="fas fa-box w-5 ml-3"></i>
                <span>مدیریت محصولات</span>
            </a>
            <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="product-files">
                <i class="fas fa-folder-open w-5 ml-3"></i>
                <span>فایل‌های محصولات</span>
            </a>
            <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="categories">
                <i class="fas fa-tags w-5 ml-3"></i>
                <span>دسته‌بندی‌ها</span>
            </a>
            <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="tags">
                <i class="fas fa-tag w-5 ml-3"></i>
                <span>برچسب‌ها</span>
            </a>
            <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="coupons">
                <i class="fas fa-percent w-5 ml-3"></i>
                <span>کدهای تخفیف</span>
            </a>
            <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="comments">
                <i class="fas fa-comments w-5 ml-3"></i>
                <span>نظرات</span>
            </a>
            <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="menus">
                <i class="fas fa-bars w-5 ml-3"></i>
                <span>منوهای سایت</span>
            </a>
            <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="settings">
                <i class="fas fa-cogs w-5 ml-3"></i>
                <span>تنظیمات سایت</span>
            </a>
            <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="payments">
                <i class="fas fa-credit-card w-5 ml-3"></i>
                <span>پرداخت‌ها</span>
            </a>
            <a href="#" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="tickets">
                <i class="fas fa-ticket-alt w-5 ml-3"></i>
                <span>تیکت‌های پشتیبانی</span>
            </a>
            <a href="{{route('admin.faq.index')}}" class="sidebar-item flex items-center p-3 rounded-lg transition-colors" data-section="faq">
                <i class="fas fa-question-circle w-5 ml-3"></i>
                <span>سوالات متداول</span>
            </a>
            <hr class="my-4">
            <form method="POST" action="{{route('logout')}}" class="flex items-center p-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                @csrf
                <button>
                    <i class="fas fa-sign-out-alt w-5 ml-3"></i>    
                    خروج
                </button>
            </form>
        </nav>
    </div>
</div>
