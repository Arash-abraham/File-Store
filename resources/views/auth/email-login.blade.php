<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود با ایمیل</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- اضافه کردن Font Awesome برای آیکون -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* انیمیشن ورود فرم */
        @keyframes slideIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .animate-slide-in {
            animation: slideIn 0.5s ease-out;
        }
        /* پس‌زمینه با گرادیانت */
        body {
            background: linear-gradient(135deg, #f0f4f8 0%, #d9e4f5 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 animate-slide-in transform transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-center mb-4">
                <i class="fas fa-envelope text-blue-500 text-2xl mr-2"></i>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-0">ورود با ایمیل</h1>
            </div>
            <p class="text-gray-600 text-sm md:text-base mb-6 text-center">لطفاً ایمیل خود را وارد کنید تا کد ورود برایتان ارسال شود.</p>
            <!-- Alert area (backend should render conditionally) -->
            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 p-3 text-green-700 text-sm text-center">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-4 rounded-md bg-red-50 p-3 text-red-700 text-sm text-center">{{ $errors->first() }}</div>
            @endif
            <form action="{{ route('otp.send') }}" method="post" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">ایمیل</label>
                    <div class="relative">
                        <input id="email" name="email" type="email" required autocomplete="email"
                               class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500 transition-all duration-200"
                               placeholder="example@email.com">
                        <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                <button type="submit"
                        class="w-full inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-2.5 text-white font-semibold shadow-md hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                    <i class="fas fa-paper-plane mr-2"></i> ارسال کد
                </button>
            </div>
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">بدون نیاز به رمز عبور. کد یکبارمصرف به ایمیل شما ارسال می‌شود.</p>
            </div>
        </div>
    </div>
</body>
</html>