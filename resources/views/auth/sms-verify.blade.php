<!DOCTYPE html>
<html lang="fa" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تایید کد ورود - SMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/opt-verify.css')}}">
</head>
<body>
    <div class="fixed top-0 left-0 w-full h-1/2 bg-gradient-to-br from-blue-400 to-purple-600 opacity-10 z-0"></div>
    
    <div class="w-full max-w-md z-10">
      <div class="card p-8">
        <div class="flex justify-center mb-6">
          <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center shadow-md">
            <i class="ri-mail-open-line text-3xl text-blue-600"></i>
          </div>
        </div>

        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2 text-center">تأیید هویت دو مرحله‌ای</h1>
        <p class="text-gray-600 text-sm md:text-base mb-6 text-center">کد تأیید برای شماره <strong>{{ $phone }}</strong> پیامک شد. لطفاً آن را وارد کنید.</p>

        <!-- نمایش خطاها -->
        @if ($errors->any())
          <div dir="rtl" class="mb-4 rounded-xl bg-red-50 p-4 text-red-700 text-sm flex items-start shadow-md">
            <i dir="rtl" class="ri-error-warning-line ml-2 mt-0.5"></i>
            <span dir="rtl">{{ $errors->first() }}</span>
          </div>
        @endif
        
        @if (session('success'))
          <div dir="rtl" class="mb-4 rounded-xl bg-green-50 p-4 text-green-700 text-sm flex items-start shadow-md">
            <i dir="rtl" class="ri-checkbox-circle-line ml-2 mt-0.5"></i>
            <span dir="rtl">{{ session('success') }}</span>
          </div>
        @endif

        <form action="{{ route('sms.verify-code') }}" method="post" class="space-y-6">
          @csrf
          <!-- فیلد مخفی برای شماره تلفن -->
          <input type="hidden" name="phone_number" value="{{ $phone }}">
          
          <div>
              <label for="otp" class="block text-sm font-medium text-gray-700 mb-3">کد تأیید ۶ رقمی</label>
              <div class="flex justify-start gap-3" dir="ltr">
                  <input type="text" maxlength="1" class="code-input w-full h-14 rounded-xl text-center focus:outline-none focus:ring-0" onkeyup="moveToNext(this, 0)">
                  <input type="text" maxlength="1" class="code-input w-full h-14 rounded-xl text-center focus:outline-none focus:ring-0" onkeyup="moveToNext(this, 1)">
                  <input type="text" maxlength="1" class="code-input w-full h-14 rounded-xl text-center focus:outline-none focus:ring-0" onkeyup="moveToNext(this, 2)">
                  <input type="text" maxlength="1" class="code-input w-full h-14 rounded-xl text-center focus:outline-none focus:ring-0" onkeyup="moveToNext(this, 3)">
                  <input type="text" maxlength="1" class="code-input w-full h-14 rounded-xl text-center focus:outline-none focus:ring-0" onkeyup="moveToNext(this, 4)">
                  <input type="text" maxlength="1" class="code-input w-full h-14 rounded-xl text-center focus:outline-none focus:ring-0" onkeyup="moveToNext(this, 5)">
              </div>
              <input type="hidden" id="fullCode" name="code" value="">
          </div>  
          
          <button type="submit" class="btn-primary w-full rounded-xl py-3.5 text-white font-medium flex items-center justify-center gap-2">
              <i class="ri-login-box-line"></i>
              تأیید و ادامه
          </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col gap-3">
          <a href="{{ route('home') }}" class="btn-secondary rounded-xl bg-gray-100 px-4 py-3 text-gray-700 font-medium text-center flex items-center justify-center gap-2">
            <i class="ri-logout-box-r-line"></i>
            بازگشت به خانه
          </a>
          <a href="{{ route('login.sms') }}" class="btn-secondary rounded-xl bg-blue-50 px-4 py-3 text-blue-600 font-medium text-center flex items-center justify-center gap-2">
            <i class="ri-restart-line"></i>
            ارسال مجدد کد
          </a>
        </div>

        <div class="mt-6 text-center">
          <p class="text-xs text-gray-500">
            <i class="ri-time-line align-middle ml-1"></i>
            کد تا <span class="timer" id="countdown">۵:۰۰</span> دقیقه دیگر معتبر است
          </p>
        </div>
      </div>
    </div>

    <script src="{{asset('js/opt-verify.js')}}"></script>
</body>
</html>