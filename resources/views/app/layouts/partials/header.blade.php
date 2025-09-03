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
                <div class="flex items-center gap-4">
                    <a href="{{route('login')}}" class="hover:text-blue-600 transition-colors">ورود / ثبت‌نام</a>
                    <a href="dashboard.html" class="hover:text-blue-600 transition-colors">پنل کاربری</a>
                    <a href="admin.html" class="hover:text-purple-600 transition-colors">
                        <i class="fas fa-cogs ml-1"></i>پنل مدیریت
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main Header -->
        <div class="py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center">
                    <img src="https://images.pexels.com/photos/1181533/pexels-photo-1181533.jpeg?auto=compress&cs=tinysrgb&w=100" 
                         alt="لوگو فروشگاه" class="h-12 w-12 rounded-lg ml-3">
                    <h1 class="text-2xl font-bold text-gray-800">فروشگاه آنلاین</h1>
                </div>
                
                <!-- Search Bar -->
                <div class="flex-1 max-w-2xl mx-8">
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="جستجوی محصولات..." 
                               class="w-full px-4 py-3 pr-12 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:outline-none transition-colors">
                        <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-500 transition-colors">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Cart & User Actions -->
                <div class="flex items-center gap-4"> 
                    <button id="cart-toggle" class="relative p-3 text-gray-600 hover:text-blue-600 transition-colors">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span id="cart-count" class="absolute -top-1 -right-1 bg-blue-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">0</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="border-t border-gray-200 py-4">
            <ul class="flex justify-center gap-8">
                <li>
                    <a href="{{ route('home') }}" class="nav-link text-black hover:text-blue-600 transition-colors">خانه</a>
                </li>
                <li>
                    <a href="{{ route('category') }}" class="nav-link text-black hover:text-blue-600 transition-colors">دسته‌بندی</a>
                </li>   
                <li>
                    <a href="{{ route('products') }}" class="nav-link text-black hover:text-blue-600 transition-colors">محصولات</a>
                </li>           
                <li>
                    <a href="{{ route('faq') }}" class="nav-link text-black hover:text-blue-600 transition-colors">سوالات متداول</a>
                </li>         
            </ul>
        </nav>
    </div>
</header>