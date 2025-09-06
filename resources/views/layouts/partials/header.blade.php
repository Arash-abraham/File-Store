<!-- Header -->
<header class="bg-white shadow-lg sticky top-0 z-40">
    <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <img src="https://images.pexels.com/photos/1181533/pexels-photo-1181533.jpeg?auto=compress&cs=tinysrgb&w=100" 
                     alt="لوگو فروشگاه" class="h-12 w-12 rounded-lg ml-3">
                <h1 class="text-2xl font-bold text-gray-800">پنل کاربری</h1>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-wallet ml-2"></i>
                    <span>موجودی: <span class="font-semibold text-green-600">250,000 تومان</span></span>
                </div>
                <div class="flex items-center">
                    <img src="https://images.pexels.com/photos/771742/pexels-photo-771742.jpeg?auto=compress&cs=tinysrgb&w=100" 
                         class="w-10 h-10 rounded-full ml-3">
                    <div>
                        <div class="text-sm font-semibold">{{ auth()->user()->name }}</div>
                    </div>
                </div>
                <div class="flex gap-4">
                    <a href="{{route('home')}}" class="text-blue-600 hover:text-blue-800 transition-colors" title="صفحه اصلی">
                        <i class="fas fa-home text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>