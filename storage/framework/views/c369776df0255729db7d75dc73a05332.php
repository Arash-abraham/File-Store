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
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
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
        
        .discount-badge {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div id="confetti-container"></div>
    
    <div class="container mx-auto max-w-5xl">
        <div class="success-animation">
            <div class="order-card p-6 md:p-8 text-center"> 
                
                <div class="mb-4">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-green-50 to-blue-50 mb-3 pulse">
                        <i class="fas fa-check-circle text-4xl success-icon"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-check-circle text-green-500 text-lg ml-2"></i>
                        <span class="text-green-700 font-semibold">پرداخت شما با موفقیت انجام شد!</span>
                    </div>
                </div>
                
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">پرداخت موفقیت‌آمیز بود</h1>
                
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 mb-6 border border-blue-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-right">
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 text-sm">شماره سفارش:</span>
                                <span class="font-bold text-gray-800">#<?php echo e($order->id); ?></span>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 text-sm">مبلغ پرداختی:</span>
                                <span class="font-bold text-green-600"><?php echo e(number_format($order->remaining_amount)); ?> تومان</span>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 text-sm">تاریخ پرداخت:</span>
                                <span class="font-bold text-gray-800"><?php echo e(verta($order->created_at)->format('Y/m/d')); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 bg-white rounded-lg p-3 shadow-sm">
                        <div class="space-y-1">                            
                            <h3 class="font-semibold text-gray-700 mb-2 text-right text-sm">جزئیات مالی:</h3>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 text-sm">مبلغ کل: <?php echo e(number_format($order->total_amount)); ?> تومان</span>
                            </div>

                            <?php if($order->discount_amount > 0): ?>
                                <div class="flex justify-between items-center">
                                    <span class="text-red-600 text-sm">تخفیف: <?php echo e(number_format($order->discount_amount)); ?> تومان</span>
                                </div>
                            <?php endif; ?>

                            <?php if($order->paid_from_wallet > 0): ?>
                                <div class="flex justify-between items-center">
                                    <span class="text-blue-600 text-sm">پرداخت از کیف پول: <?php echo e(number_format($order->paid_from_wallet)); ?> تومان</span>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                    
                    <?php if($order->discount_amount > 0): ?>
                    <div class="mt-3 flex justify-center">
                        <div class="discount-badge px-3 py-1 rounded-full font-semibold text-xs shadow-lg">
                            <i class="fas fa-tag ml-1"></i>
                            <?php echo e(number_format(($order->discount_amount / $order->total_amount) * 100, 1)); ?>% تخفیف
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- دکمه‌های اقدام -->
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="<?php echo e(route('home')); ?>" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold text-base inline-flex items-center justify-center">
                        <i class="fas fa-home ml-2"></i>
                        بازگشت به صفحه اصلی
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function createConfetti() {
            const container = document.getElementById('confetti-container');
            const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#f9ca24', '#6c5ce7', '#a29bfe'];
            
            for (let i = 0; i < 40; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.top = '-10px';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.width = Math.random() * 8 + 4 + 'px';
                confetti.style.height = confetti.style.width;
                confetti.style.opacity = Math.random() * 0.5 + 0.5;
                
                container.appendChild(confetti);
                
                // انیمیشن سقوط
                const animation = confetti.animate([
                    { transform: 'translateY(0) rotate(0deg)', opacity: 1 },
                    { transform: `translateY(${window.innerHeight}px) rotate(${Math.random() * 360}deg)`, opacity: 0 }
                ], {
                    duration: Math.random() * 2000 + 1500,
                    easing: 'cubic-bezier(0.1, 0.8, 0.3, 1)'
                });
                
                animation.onfinish = () => confetti.remove();
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            createConfetti();
            
            const hasDiscount = <?php echo e($order->discount_amount > 0 ? 'true' : 'false'); ?>;
            
            if (hasDiscount) {
                setTimeout(() => {
                    createConfetti();
                }, 1000);
            }
        });
    </script>
</body>
</html><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/app/payment-success.blade.php ENDPATH**/ ?>