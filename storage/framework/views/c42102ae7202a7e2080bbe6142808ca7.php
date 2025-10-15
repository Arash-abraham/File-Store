<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سبد خرید و پرداخت</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> <!-- اضافه کردن Axios -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;200;300;400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Vazirmatn', sans-serif;
        }

        .cart-item {
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .empty-cart-icon {
            font-size: 4rem;
            color: #d1d5db;
        }

        .btn-orange {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            transition: all 0.3s ease;
        }

        .btn-orange:hover {
            background: linear-gradient(135deg, #d97706, #b45309);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        }
        .spinner {
            border: 2px solid #f3f3f3;
            border-radius: 50%;
            border-top: 2px solid #3498db;
            width: 20px;
            height: 20px;
            -webkit-animation: spin 1s linear infinite;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-left: 8px;
        }

        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .btn-loading {
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="container mx-auto p-4 max-w-4xl">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">سبد خرید شما</h1>
            <p class="text-gray-600">مدیریت و بررسی محصولات انتخابی</p>
        </div>

        <?php if(session('success')): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle ml-2 text-green-500"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>
        <?php if(session('applied')): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle ml-2 text-green-500"></i>
                <span><?php echo e(session('applied')); ?></span>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-exclamation-circle ml-2 text-red-500"></i>
                <span><?php echo e(session('error')); ?></span>
            </div>
        <?php endif; ?>

        <?php if($cartItems->isEmpty()): ?>
            <div class="bg-white rounded-2xl shadow-sm p-8 text-center">
                <div class="empty-cart-icon mb-4">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">سبد خرید شما خالی است</h3>
                <p class="text-gray-500 mb-6">می‌توانید با مراجعه به صفحه محصولات، محصولات مورد نظر خود را اضافه کنید</p>
                <a href="<?php echo e(route('home')); ?>" class="btn-primary text-white px-6 py-3 rounded-lg inline-flex items-center">
                    <i class="fas fa-store ml-2"></i>
                    بازگشت به فروشگاه
                </a>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6">
                <div class="divide-y divide-gray-100">
                    <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="cart-item p-6 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="relative">
                                    <img src="<?php echo e(asset($item->product->image_urls[0] ?? 'images/placeholder.jpg')); ?>" 
                                         alt="<?php echo e($item->product->title); ?>" 
                                         class="w-20 h-20 object-cover rounded-xl shadow-sm">
                                    <span class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center">
                                        <?php echo e($item->quantity); ?>

                                    </span>
                                </div>
                                <div class="mr-4">
                                    <h3 class="text-lg font-semibold text-gray-800"><?php echo e($item->product->title); ?></h3>
                                    <p class="text-gray-600 mt-1"><?php echo e(number_format($item->unit_price)); ?> تومان</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4">
                                <form action="<?php echo e(route('cart.remove', $item->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition-colors">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                
                                <p class="text-gray-800 font-bold text-lg"><?php echo e(number_format($item->subtotal)); ?> تومان</p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    <form action="<?php echo e(route('cart.apply-coupon')); ?>" method="POST" class="flex gap-2">
                        <?php echo csrf_field(); ?>
                        <div class="flex-1 relative">
                            <input type="text" value="<?php echo e($item->unit_price); ?>" name="price" hidden>
                            <input type="text" name="coupon_code" id="coupon_code" 
                                   value="<?php echo e(old('coupon_code', $appliedCoupon ?? '')); ?>"
                                   placeholder="کد تخفیف خود را وارد کنید" 
                                   class="w-full p-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['coupon_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   <?php echo e(isset($appliedCoupon) && $appliedCoupon ? 'readonly' : ''); ?>>
                            <i class="fas fa-ticket-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <?php if(session('applied')): ?>
                            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                                <p>اعمال شد</p>                                
                            </div>
                        <?php else: ?> 
                            <button type="submit" class="btn-orange px-6 py-3 rounded-lg font-semibold flex items-center">
                                <i class="fas fa-gift ml-2"></i>
                                اعمال تخفیف
                            </button>
                        <?php endif; ?>

                    </form>
                    
                    <?php $__errorArgs = ['coupon_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="mt-2 text-red-500 text-sm flex items-center">
                            <i class="fas fa-exclamation-triangle ml-2"></i>
                            <?php echo e($message); ?>

                        </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="px-6 py-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-gray-200 rounded-lg shadow-sm">
                    <div class="max-w-md ml-auto space-y-4">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700 font-medium">جمع کل:</span>
                            <span class="text-xl font-bold text-gray-900"><?php echo e(number_format($total)); ?> تومان</span>
                        </div>
                        
                        <?php if($discount > 0): ?>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-700 font-medium">تخفیف:</span>
                                <span class="text-lg font-bold text-green-600"><?php echo e(number_format($discount)); ?> تومان</span>
                            </div>
                            
                            <div class="flex justify-between items-center pt-4 border-t border-gray-300 border-dashed">
                                <span class="text-gray-800 font-semibold text-lg">مبلغ قابل پرداخت:</span>
                                <span class="text-xl font-bold text-blue-700"><?php echo e(number_format($total - $discount)); ?> تومان</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <div class="text-center">
                <form id="paymentForm" action="<?php echo e(route('checkout.process')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="session_token" value="<?php echo e(session()->getId()); ?>">
                    <input type="hidden" name="payment_gateway" value="zarinpal">
                    <button type="submit" id="paymentButton" class="btn-success text-white px-8 py-4 rounded-xl font-bold text-lg inline-flex items-center shadow-lg">
                        <i class="fas fa-credit-card ml-2"></i>
                        <span id="buttonText">پرداخت و تکمیل سفارش</span>
                        <div id="buttonSpinner" class="spinner mr-2" style="display: none;"></div>
                    </button>
                </form>
                
                <a href="<?php echo e(route('home')); ?>" class="mt-6 inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                    <i class="fas fa-arrow-right ml-2"></i>
                    بازگشت به صفحه اصلی
                </a>
            </div>
        <?php endif; ?>
    </div>
    <script>
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const paymentButton = document.getElementById('paymentButton');
            const buttonText = document.getElementById('buttonText');
            const buttonSpinner = document.getElementById('buttonSpinner');
            
            paymentButton.classList.add('btn-loading');
            paymentButton.disabled = true;
            buttonText.textContent = 'در حال انتقال به درگاه...';
            buttonSpinner.style.display = 'inline-block';
            
            console.log('Sending checkout request...', {
                session_token: document.querySelector('input[name="session_token"]').value,
                payment_gateway: document.querySelector('input[name="payment_gateway"]').value
            });
            
            axios.post(this.action, new FormData(this))
                .then(response => {
                    console.log('Checkout response:', response.data);
                    
                    if (response.data.success) {
                        window.location.href = response.data.data.payment_url;
                    } else {
                        resetButton();
                        alert('خطا: ' + (response.data.message || 'خطای ناشناخته'));
                    }
                })
                .catch(error => {
                    console.error('Checkout error:', error);
                    console.error('Error response:', error.response?.data);
                    
                    resetButton();
                    alert('خطا در پردازش پرداخت: ' + (error.response?.data?.message || 'لطفاً دوباره تلاش کنید'));
                });
                
            function resetButton() {
                paymentButton.classList.remove('btn-loading');
                paymentButton.disabled = false;
                buttonText.textContent = 'پرداخت و تکمیل سفارش';
                buttonSpinner.style.display = 'none';
            }
        });
    </script>
</body>
</html><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/app/checkout.blade.php ENDPATH**/ ?>