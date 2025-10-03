<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تأیید ایمیل</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Optional: smooth font rendering */
    body { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
  </style>
  <meta name="description" content="برای دسترسی به داشبورد، لطفاً ایمیل خود را از طریق لینک ارسال شده تأیید کنید.">
</head>
<body class="min-h-screen bg-gray-50">
  <div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
      <div class="bg-white rounded-2xl shadow-lg p-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3 text-center">تأیید ایمیل</h1>
        <p class="text-gray-700 text-sm md:text-base leading-7 text-center">
          برای دسترسی به حساب کاربری و داشبورد، لازم است ایمیل خود را تأیید کنید.
          یک لینک تأیید به ایمیل شما ارسال شده است. لطفاً ایمیل خود را بررسی کرده و روی لینک تأیید کلیک کنید.
        </p>

        <!-- Alert area (backend can render a status message here) -->
        <!-- <div class="mt-4 rounded-md bg-green-50 p-4 text-green-700 text-sm">لینک تأیید دوباره ارسال شد.</div> -->
        <!-- <div class="mt-4 rounded-md bg-red-50 p-4 text-red-700 text-sm">ارسال لینک با خطا مواجه شد.</div> -->

        <div class="mt-8 flex flex-col gap-3">
          <form method="POST" action="{{route('logout')}}" class="flex items-center p-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
              @csrf
              <button class="w-full inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-2.5 text-white font-medium shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                  ارسال دوباره
              </button>
          </form>

        <div class="mt-6 text-center">
          <p class="text-xs text-gray-500">اگر ایمیلی دریافت نکردید، پوشه اسپم (Spam) را نیز بررسی کنید.</p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
