<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پرداخت ناموفق</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;200;300;400;500;600;700;800;900&display=swap');
        body {
            font-family: 'Vazirmatn', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="container mx-auto max-w-md">
        <div class="bg-white rounded-2xl shadow-sm p-8 text-center">
            <div class="mb-4">
                <i class="fas fa-exclamation-circle text-4xl text-red-500"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-3">پرداخت ناموفق</h1>
            <p class="text-gray-600 mb-6">
                @if (session('error'))
                    {{ session('error') }}
                @else
                    متأسفانه پرداخت شما تکمیل نشد. لطفاً دوباره تلاش کنید.
                @endif
            </p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('checkout.show') }}" class="bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold">
                    بازگشت به سبد خرید
                </a>
                <a href="{{ route('home') }}" class="bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-semibold">
                    صفحه اصلی
                </a>
            </div>
        </div>
    </div>
</body>
</html>