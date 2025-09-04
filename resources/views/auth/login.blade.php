<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود / ثبت‌نام - فروشگاه آنلاین</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Vazirmatn', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-center">
                <img src="https://images.pexels.com/photos/1181533/pexels-photo-1181533.jpeg?auto=compress&cs=tinysrgb&w=100" 
                     alt="لوگو فروشگاه" class="h-12 w-12 rounded-lg ml-3">
                <h1 class="text-2xl font-bold text-gray-800">فروشگاه آنلاین</h1>
            </div>
        </div>
    </header>

    <!-- Auth Forms -->
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-md mx-auto">
            <!-- Tab Switcher -->
            <div class="bg-white rounded-t-xl shadow-lg">
                <div class="flex">
                    <button id="loginTab" class="flex-1 py-4 text-center font-semibold bg-blue-600 text-white rounded-tr-xl transition-colors">
                        ورود
                    </button>
                    <button id="registerTab" class="flex-1 py-4 text-center font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-tl-xl transition-colors">
                        ثبت‌نام
                    </button>
                </div>
            </div>

            <!-- Login Form -->
            <div id="loginForm" class="bg-white rounded-b-xl shadow-lg p-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">خوش آمدید</h2>
                    <p class="text-gray-600">برای ادامه وارد حساب کاربری خود شوید</p>
                </div>

                <form id="loginFormElement" class="space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                            <ul class="list-disc pr-4">
                                @foreach ($errors->all() as $error)
                                    <li class="text-sm font-medium">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
        
                    <div>
                        <label for="loginEmail" class="block text-sm font-medium text-gray-700 mb-2">ایمیل</label>
                        <div class="relative">
                            <input type="email" id="loginEmail" name="email" required
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            <i class="fas fa-envelope absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <div>
                        <label for="loginPassword" class="block text-sm font-medium text-gray-700 mb-2">رمز عبور</label>
                        <div class="relative">
                            <input type="password" id="loginPassword" name="password" required
                                   class="w-full px-4 py-3 pr-12 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            <i class="fas fa-lock absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <button type="button" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600" onclick="togglePassword('loginPassword')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" class="ml-2 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="text-sm text-gray-700">مرا به خاطر بسپار</span>
                        </label>
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            فراموشی رمز عبور؟
                        </a>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        ورود به حساب
                    </button>

                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">یا</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fab fa-google text-red-500 ml-2"></i>
                            <span class="text-sm">Google</span>
                        </button>
                        <button type="button" class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-mobile-alt text-green-500 ml-2"></i>
                            <span class="text-sm">SMS</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Register Form -->
            <div id="registerForm" class="bg-white rounded-b-xl shadow-lg p-8 hidden">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">عضویت در فروشگاه</h2>
                    <p class="text-gray-600">حساب کاربری خود را ایجاد کنید</p>
                </div>

                <form id="registerFormElement" class="space-y-6" method="POST" action="{{ route('register') }}">
                    @csrf
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                            <ul class="list-disc pr-4">
                                @foreach ($errors->all() as $error)
                                    <li class="text-sm font-medium">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">نام</label>
                            <input type="text" id="name" name="name" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        </div>
                        {{-- <div>
                            <label for="lastName" class="block text-sm font-medium text-gray-700 mb-2">نام خانوادگی</label>
                            <input type="text" id="lastName" name="lastName" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        </div> --}}
                    </div>

                    <div>
                        <label for="registerEmail" class="block text-sm font-medium text-gray-700 mb-2">ایمیل</label>
                        <div class="relative">
                            <input type="email" id="registerEmail" name="email" required
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            <i class="fas fa-envelope absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    {{-- <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">شماره تماس</label>
                        <div class="relative">
                            <input type="tel" id="phone" name="phone" required
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                   placeholder="09123456789">
                            <i class="fas fa-phone absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div> --}}

                    <div>
                        <label for="registerPassword" class="block text-sm font-medium text-gray-700 mb-2">رمز عبور</label>
                        <div class="relative">
                            <input type="password" id="registerPassword" name="password" required
                                   class="w-full px-4 py-3 pr-12 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            <i class="fas fa-lock absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <button type="button" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600" onclick="togglePassword('registerPassword')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="mt-2">
                            <div class="text-xs text-gray-500">
                                رمز عبور باید حداقل 8 کاراکتر و شامل حروف و اعداد باشد
                            </div>
                            <div class="flex mt-1">
                                <div class="flex-1 h-1 bg-gray-200 rounded-full ml-1">
                                    <div id="passwordStrength" class="h-1 bg-red-500 rounded-full transition-all" style="width: 0%"></div>
                                </div>
                                <span id="passwordStrengthText" class="text-xs text-gray-500">ضعیف</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">تکرار رمز عبور</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                   class="w-full px-4 py-3 pr-12 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            <i class="fas fa-lock absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <button type="button" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" required class="ml-2 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="text-sm text-gray-700">
                                با <a href="#" class="text-blue-600 hover:text-blue-800">شرایط و قوانین</a> موافقم
                            </span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" class="ml-2 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="text-sm text-gray-700">مایل به دریافت ایمیل‌های تبلیغاتی هستم</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        ایجاد حساب کاربری
                    </button>
                </form>
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-8">
                <a href="index.html" class="text-blue-600 hover:text-blue-800 transition-colors">
                    <i class="fas fa-arrow-right ml-2"></i>بازگشت به صفحه اصلی
                </a>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg p-8 text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-700">در حال پردازش...</p>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <div id="messageContainer" class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50"></div>

    <script src="{{asset('js/auth.js')}}"></script>
</body>
</html>