@extends('app.layouts.master')

@section('title', 'خانه')

@section('content')
    <div id="main-content">
        <!-- Cart Modal and Blur Overlay -->
        <div id="blur-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"></div>
        <div id="cart-modal" class="fixed w-80 bg-gray-800 text-white rounded-lg shadow-lg p-4 hidden z-50">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-bold">سبد خرید</h2>
                <button id="close-cart" class="text-gray-400 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="cart-content" class="max-h-64 overflow-y-auto mb-4">
                <!-- محتوای سبد خرید اینجا رندر می‌شود -->
            </div>
            <div id="cart-actions" class="mt-4"></div>
        </div>

        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-5xl font-bold mb-6">فروشگاه دیجیتال برتر</h2>
                <p class="text-xl mb-8 opacity-90">بهترین نرم‌افزارها، دوره‌های آموزشی و محصولات دیجیتال را از ما بخرید</p>
                <a href="{{ route('products') }}">
                    <button class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                        مشاهده محصولات
                    </button>
                </a>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-2">10,000+</h3>
                        <p class="text-gray-600">مشتری راضی</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-box text-green-600 text-2xl"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-2">5,000+</h3>
                        <p class="text-gray-600">محصول متنوع</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-yellow-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-award text-yellow-600 text-2xl"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-2">98%</h3>
                        <p class="text-gray-600">رضایت کاربران</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-purple-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-headset text-purple-600 text-2xl"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-2">24/7</h3>
                        <p class="text-gray-600">پشتیبانی آنلاین</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Popular Products -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">محصولات پرفروش</h2>
                    <p class="text-gray-600 text-lg">بهترین و پربازدیدترین محصولات ما</p>
                </div>
                
                <div id="productsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
                            <div class="relative overflow-hidden">
                                <a href="{{ route('show-product', $product->id) }}">
                                    <img src="{{ asset($product->image_urls[0]) }}" alt="{{ $product->title }}" 
                                         class="w-full h-48 object-cover">
                                </a>
                                <div class="p-6">
                                    <a href="{{ route('show-product', $product->id) }}">
                                        <h3 class="font-semibold text-lg mb-2 text-gray-800 line-clamp-2">{{ $product->title }}</h3>    
                                    </a>                                    
                                    <div class="flex items-center mb-3">
                                        <span class="text-gray-500 text-sm mr-2">{{ $product->category->name }}</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <span class="text-xl font-bold text-green-600">{{ number_format($product->original_price) }} تومان</span>                        
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-2">
                                        <button onclick="addToCart({{ $product->id }})" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-shopping-cart ml-1"></i>
                                            افزودن به سبد
                                        </button>
                                        <a href="{{ route('show-product', $product->id) }}">
                                            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Categories -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach ($categories as $category)
                        <div class="bg-gradient-to-br from-{{$category->color}}-500 to-{{$category->color}}-600 rounded-xl p-6 text-white text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                            <i class="fas {{$category->icon}} text-5xl mb-4 group-hover:text-{{$category->color}}-100 transition-colors duration-300"></i>
                            <h3 class="text-xl font-bold mb-2 group-hover:text-white">{{$category->name}}</h3>
                            <button class="mt-4 bg-white bg-opacity-20 text-white px-4 py-2 rounded-full hover:bg-opacity-30 transition-all duration-200">مشاهده</button>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Newsletter -->
        @guest
            <section class="py-16 bg-gray-800 text-white">
                <div class="container mx-auto px-4 text-center">
                    <h2 class="text-3xl font-bold mb-4">در خبرنامه ما عضو شوید</h2>
                    <p class="text-gray-300 mb-8">از آخرین محصولات و تخفیف‌ها باخبر شوید</p>
                    <div class="max-w-md mx-auto flex gap-4">
                        <a class="flex-1 px-4 py-3" href="{{ route('login') }}">
                            <button class="w-full bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold py-2">
                                ثبت نام
                            </button>
                        </a>    
                    </div>
                </div>
            </section>
        @else
            <section class="py-16 bg-gray-800 text-white">
                <div class="container mx-auto px-4 text-center">
                    <h2 class="text-3xl font-bold mb-4">در خبرنامه ما عضو شوید</h2>
                    <p class="text-gray-300 mb-8">از آخرین محصولات و تخفیف‌ها باخبر شوید</p>
                    <div class="max-w-md mx-auto flex-1 px-4 py-3">
                        شما عضوی از خبرنامه ما هستید
                    </div>
                </div>
            </section>
        @endguest
    </div>
@endsection

@section('scripts')
    <script>
        // متغیرهای اولیه
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const cartContent = document.getElementById('cart-content');
        const cartActions = document.getElementById('cart-actions');
        const cartToggle = document.getElementById('cartToggle');
        const cartModal = document.getElementById('cart-modal');
        const blurOverlay = document.getElementById('blur-overlay');
        const closeCart = document.getElementById('close-cart');

        // فرمت قیمت
        function formatPrice(price) {
            return Number(price).toLocaleString('fa-IR');
        }

        // به‌روزرسانی تعداد سبد
        function updateCartCount() {
            const cartCount = document.getElementById('cartCount');
            if (cartCount) {
                const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                cartCount.textContent = totalItems;
                cartCount.style.display = totalItems > 0 ? 'flex' : 'none';
            }
        }

        // رندر سبد خرید
        function renderCart() {
            if (!cartContent) return;

            cartContent.innerHTML = '';
            if (cart.length === 0) {
                cartContent.innerHTML = '<p class="text-center text-gray-400">سبد خرید خالی است</p>';
                if (cartActions) cartActions.innerHTML = '';
            } else {
                let total = 0;
                cart.forEach(item => {
                    total += item.price * item.quantity;
                    const cartItem = document.createElement('div');
                    cartItem.className = 'flex items-center justify-between py-2 border-b border-gray-700';
                    cartItem.innerHTML = `
                        <div class="flex items-center">
                            <img src="${item.image}" alt="${item.title}" class="w-12 h-12 object-cover rounded mr-2">
                            <div>
                                <h3 class="text-sm font-semibold">${item.title}</h3>
                                <p class="text-xs text-gray-400">${formatPrice(item.price)} تومان × ${item.quantity}</p>
                            </div>
                        </div>
                        <button onclick="removeFromCart(${item.id})" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    `;
                    cartContent.appendChild(cartItem);
                });
                
                if (cartActions) {
                    cartActions.innerHTML = `
                        <div class="mb-2">
                            <input id="couponCode" type="text" placeholder="کد تخفیف" class="w-full p-2 border rounded text-gray-800">
                            <button onclick="applyCoupon()" class="w-full bg-yellow-500 text-white py-2 rounded mt-2">اعمال کد تخفیف</button>
                            <p id="couponMessage" class="text-sm mt-1"></p>
                        </div>
                        <button id="checkout" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-md">تسویه حساب</button>
                    `;
                }
            }
            updateCartCount();
        }

        // حذف آیتم از سبد
        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            localStorage.setItem('cart', JSON.stringify(cart));
            syncCartToApi();
            renderCart();
        }

        // موقعیت مودال سبد
        function positionCartModal() {
            if (cartModal) {
                cartModal.style.top = '80px';
                cartModal.style.left = '20px';
            }
        }

        // toggle مودال سبد
        if (cartToggle) {
            cartToggle.addEventListener('click', () => {
                if (cartModal) cartModal.classList.toggle('show');
                if (blurOverlay) blurOverlay.classList.toggle('show');
                if (cartModal && cartModal.classList.contains('show')) {
                    renderCart();
                    positionCartModal();
                }
            });
        }

        // بستن مودال سبد
        if (closeCart) {
            closeCart.addEventListener('click', () => {
                if (cartModal) cartModal.classList.remove('show');
                if (blurOverlay) blurOverlay.classList.remove('show');
            });
        }

        // بستن مودال با کلیک روی overlay
        if (blurOverlay) {
            blurOverlay.addEventListener('click', () => {
                if (cartModal) cartModal.classList.remove('show');
                if (blurOverlay) blurOverlay.classList.remove('show');
            });
        }

        // اعمال کد تخفیف
        let discount = 0;
        function applyCoupon() {
            const couponCode = document.getElementById('couponCode').value;
            const couponMessage = document.getElementById('couponMessage');
            
            if (couponCode === 'DISCOUNT10') {
                discount = Math.floor(cart.reduce((sum, item) => sum + item.price * item.quantity, 0) * 0.1);
                couponMessage.textContent = 'کد تخفیف 10% اعمال شد!';
                couponMessage.className = 'text-sm text-green-600 mt-1';
            } else {
                discount = 0;
                couponMessage.textContent = 'کد تخفیف نامعتبر است!';
                couponMessage.className = 'text-sm text-red-600 mt-1';
            }
        }

        // همگام‌سازی سبد با API
        async function syncCartToApi() {
            const token = localStorage.getItem('api_token');
            const sessionToken = localStorage.getItem('session_token') || 'default-session';
            
            if (!token) {
                console.log('کاربر لاگین نکرده است');
                return;
            }

            try {
                // ابتدا سبد سرور را خالی کنیم
                await fetch('/api/cart/clear', {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'session-token': sessionToken,
                    }
                });

                // سپس آیتم‌ها را اضافه کنیم
                for (const item of cart) {
                    await fetch('/api/cart/add', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'application/json',
                            'session-token': sessionToken,
                        },
                        body: JSON.stringify({
                            product_id: item.id,
                            quantity: item.quantity
                        })
                    });
                }
            } catch (error) {
                console.error('خطا در همگام‌سازی:', error);
            }
        }

        // افزودن به سبد با API
        async function addToCart(productId) {
            const token = localStorage.getItem('api_token');
            const sessionToken = localStorage.getItem('session_token') || 'default-session';

            try {
                const response = await fetch('/api/cart/add', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'session-token': sessionToken,
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // محصول به سبد سرور اضافه شد، حالا سبد لوکال را آپدیت کن
                    await fetchCartFromServer();
                    
                    // نمایش پیام موفقیت
                    showNotification('محصول به سبد خرید اضافه شد', 'success');
                    
                    // نمایش سبد خرید
                    if (cartModal) {
                        cartModal.classList.add('show');
                        blurOverlay.classList.add('show');
                        renderCart();
                        positionCartModal();
                    }
                } else {
                    showNotification('خطا در افزودن به سبد: ' + data.message, 'error');
                }
            } catch (error) {
                console.error('خطا:', error);
                showNotification('خطا در ارتباط با سرور', 'error');
            }
        }

        // دریافت سبد خرید از سرور
        async function fetchCartFromServer() {
            const token = localStorage.getItem('api_token');
            const sessionToken = localStorage.getItem('session_token') || 'default-session';

            try {
                const response = await fetch('/api/cart', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'session-token': sessionToken,
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    // آپدیت سبد لوکال بر اساس داده‌های سرور
                    cart = data.items || [];
                    localStorage.setItem('cart', JSON.stringify(cart));
                    updateCartCount();
                }
            } catch (error) {
                console.error('خطا در دریافت سبد خرید:', error);
            }
        }

        // نمایش نوتیفیکیشن
        function showNotification(message, type = 'info') {
            // ایجاد المان نوتیفیکیشن
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                'bg-blue-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // حذف خودکار بعد از 3 ثانیه
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // تسویه حساب
        document.addEventListener('click', async (e) => {
            if (e.target.id === 'checkout' && cart.length > 0) {
                const token = localStorage.getItem('api_token');
                const sessionToken = localStorage.getItem('session_token') || 'default-session';

                try {
                    const response = await fetch('/api/checkout', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'application/json',
                            'session-token': sessionToken,
                        },
                        body: JSON.stringify({
                            payment_gateway: 'zarinpal'
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // هدایت به درگاه پرداخت
                        window.location.href = data.data.payment_url;
                    } else {
                        showNotification('خطا در ایجاد سفارش: ' + data.message, 'error');
                    }
                } catch (error) {
                    showNotification('خطا در ارتباط با سرور', 'error');
                }
            }
        });

        // بارگذاری اولیه
        document.addEventListener('DOMContentLoaded', async () => {
            // ابتدا سبد را از سرور بگیر
            await fetchCartFromServer();
            renderCart();
            updateCartCount();
        });

        // قرار دادن توابع در scope全局
        window.removeFromCart = removeFromCart;
        window.addToCart = addToCart;
        window.applyCoupon = applyCoupon;
    </script>
@endsection