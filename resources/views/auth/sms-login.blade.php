<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود با پیامک - فایل استور</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/sms-login.css')}}">
</head>
<body>
    <div class="w-full max-w-md">
        <div class="card p-8 animate-slide-in">
            <div class="icon-wrapper">
                <i class="fas fa-sms text-blue-500 text-3xl"></i>
            </div>
            
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 text-center mb-2">ورود با پیامک</h1>
            <p class="text-gray-600 text-sm md:text-base mb-6 text-center">لطفاً شماره تلفن خود را وارد کنید تا کد ورود برایتان ارسال شود.</p>
            
            <!-- Alert area -->
            <div id="alertBox" class="alert-box bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 hidden">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle ml-2"></i>
                    <span class="text-sm font-medium">لطفاً یک شماره تلفن معتبر وارد کنید (مثال: 09123456789)</span>
                </div>
            </div>
            
            <form id="smsForm" class="space-y-5">
                <div class="input-container">
                    <input id="phone" name="phone" type="tel" required autocomplete="tel" 
                           class="input-field" placeholder="" dir="ltr">
                    <i class="input-icon fas fa-phone"></i>
                    <label for="phone" class="input-label">شماره تلفن</label>
                </div>
                
                <button type="submit"
                        class="btn-primary w-full rounded-xl py-3.5 text-white font-semibold flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane"></i> ارسال کد ورود
                </button>
            </form>
            
            <div class="divider">یا وارد شوید با</div>
            
            <div class="alternative-login">
                <a href="{{route('login.email')}}" class="social-btn">
                    <i class="fab fa-google"></i>
                </a>
            </div>

            <div class="mt-8 text-center text-xs text-gray-500">
                <p class="mt-2"><a href="{{route('login')}}" class="text-blue-500 hover:text-blue-700 transition-colors">ورود با رمز عبور</a></p>
            </div>

            <div class="mt-8 text-center text-xs text-gray-500">
                <p class="mt-2">حساب کاربری ندارید؟ <a href="{{route('login')}}" class="text-blue-500 hover:text-blue-700 transition-colors">ثبت نام کنید</a></p>
            </div>
        </div>
    </div>

    <script src="{{asset('js/sms-login.js')}}"></script>
</body>
</html>