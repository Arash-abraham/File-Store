<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پرداخت موفق</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;200;300;400;500;600;700;800;900&display=swap');
        
        body {
            font-family: 'Vazirmatn', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .success-animation {
            animation: successScale 0.6s ease-in-out;
        }
        
        @keyframes successScale {
            0% { transform: scale(0.8); opacity: 0; }
            70% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #f0f;
            opacity: 0.7;
            border-radius: 50%;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        .order-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        
        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }
        
        .success-icon {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 4px 8px rgba(79, 172, 254, 0.3));
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div id="confetti-container"></div>
    
    <div class="container mx-auto max-w-2xl">
        <div class="success-animation">
            <div class="order-card p-8 md:p-10 text-center">
                <!-- آیکون موفقیت -->
                <div class="mb-6">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-green-50 to-blue-50 mb-4 pulse">
                        <i class="fas fa-check-circle text-5xl success-icon"></i>
                    </div>
                </div>
                
                <!-- پیام موفقیت -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-6 mb-8">
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-check-circle text-green-500 text-xl ml-2"></i>
                        <span class="text-green-700 font-semibold text-lg">پرداخت شما با موفقیت انجام شد!</span>
                    </div>
                </div>
                
                <!-- عنوان اصلی -->
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">پرداخت موفقیت‌آمیز بود</h1>
                
                <!-- کارت اطلاعات سفارش -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 mb-8 border border-blue-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-right">
                        <div class="bg-white rounded-xl p-4 shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-500">شماره سفارش:</span>
                                <span class="font-bold text-gray-800">#<?php echo e($order->id); ?></span>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-xl p-4 shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-500">مبلغ پرداختی:</span>
                                <span class="font-bold text-green-600 text-lg"><?php echo e(number_format($order->total_amount)); ?> تومان</span>
                            </div>
                        </div>
                    </div>
                    

                </div>
                
                <!-- دکمه‌های اقدام -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo e(route('home')); ?>" class="btn-primary text-white px-8 py-4 rounded-xl font-semibold text-lg inline-flex items-center justify-center">
                        <i class="fas fa-home ml-2"></i>
                        بازگشت به صفحه اصلی
                    </a>
                </div>
                
                <!-- پیام پایانی -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-gray-500 text-sm">در صورت وجود هرگونه سوال، با پشتیبانی تماس بگیرید</p>
                    <div class="flex justify-center mt-3 space-x-4 space-x-reverse">
                        <a href="#" class="text-blue-500 hover:text-blue-700 transition-colors">
                            <i class="fab fa-telegram-plane text-xl"></i>
                        </a>
                        <a href="#" class="text-blue-500 hover:text-blue-700 transition-colors">
                            <i class="fas fa-phone-alt text-xl"></i>
                        </a>
                        <a href="#" class="text-blue-500 hover:text-blue-700 transition-colors">
                            <i class="fas fa-envelope text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ایجاد افکت کانفی
        function createConfetti() {
            const container = document.getElementById('confetti-container');
            const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#f9ca24', '#6c5ce7', '#a29bfe'];
            
            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.top = '-10px';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.width = Math.random() * 10 + 5 + 'px';
                confetti.style.height = confetti.style.width;
                confetti.style.opacity = Math.random() * 0.5 + 0.5;
                
                container.appendChild(confetti);
                
                // انیمیشن سقوط
                const animation = confetti.animate([
                    { transform: 'translateY(0) rotate(0deg)', opacity: 1 },
                    { transform: `translateY(${window.innerHeight}px) rotate(${Math.random() * 360}deg)`, opacity: 0 }
                ], {
                    duration: Math.random() * 3000 + 2000,
                    easing: 'cubic-bezier(0.1, 0.8, 0.3, 1)'
                });
                
                // حذف المان پس از پایان انیمیشن
                animation.onfinish = () => confetti.remove();
            }
        }
        
        // اجرای افکت کانفی هنگام لود صفحه
        document.addEventListener('DOMContentLoaded', function() {
            createConfetti();
            
            // تکرار افکت هر 3 ثانیه برای 3 بار
            let count = 0;
            const interval = setInterval(() => {
                createConfetti();
                count++;
                if (count >= 3) clearInterval(interval);
            }, 3000);
        });
    </script>
</body>
</html><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/app/payment-success.blade.php ENDPATH**/ ?>