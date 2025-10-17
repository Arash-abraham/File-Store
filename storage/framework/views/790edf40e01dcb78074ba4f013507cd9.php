<div id="wallet" class="content-section hidden">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <?php if(session('error')): ?>
            <div class="mt-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle ml-2"></i>
                    <?php echo e(session('error')); ?>

                </div>
            </div>
        <?php endif; ?>
        
        <!-- Wallet Balance -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">موجودی کیف پول</h2>
            
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-blue-100 mb-2">موجودی فعلی</p>
                        <p class="text-3xl font-bold"><?php echo e($wallet->formatted_balance); ?></p>
                    </div>
                    <i class="fas fa-wallet text-4xl opacity-50"></i>
                </div>
            </div>
            
            <div class="space-y-4">
                <a href="<?php echo e(route('wallet.deposit')); ?>" 
                   class="block w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold text-center">
                    <i class="fas fa-plus ml-2"></i>
                    شارژ کیف پول
                </a>
                <p></p>
                <a href="<?php echo e(route('dashboard')); ?>">
                    <button class="w-full border border-gray-300 py-3 rounded-lg hover:bg-gray-50 transition-colors">
                        بازگشت به داشبورد
                    </button>
                </a>
            </div>

            <!-- اطلاعات سریع -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="font-semibold text-gray-800 mb-3">اطلاعات حساب</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="text-gray-600">تعداد تراکنش‌ها:</div>
                    <div class="text-gray-800 font-medium"><?php echo e($wallet->transactions()->count()); ?></div>
                    
                    <div class="text-gray-600">آخرین تراکنش:</div>
                    <div class="text-gray-800 font-medium">
                        <?php if($walletTransactions->count() > 0): ?>
                            <?php echo e(\Morilog\Jalali\Jalalian::forge($walletTransactions->first()->created_at)->format('Y/m/d')); ?>

                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <h3 class="text-xl font-bold text-gray-800 mb-6">آخرین تراکنش‌ها</h3>
            
            <?php if($walletTransactions->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $walletTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex justify-between items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center ml-3 
                                    <?php if(in_array($transaction->type, ['deposit', 'refund'])): ?> bg-green-100 text-green-600
                                    <?php else: ?> bg-red-100 text-red-600 <?php endif; ?>">
                                    <?php if(in_array($transaction->type, ['deposit', 'refund'])): ?>
                                        <i class="fas fa-plus"></i>
                                    <?php else: ?>
                                        <i class="fas fa-minus"></i>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800"><?php echo e($transaction->type_label); ?></p>
                                    <p class="text-sm text-gray-500">
                                        <?php echo e(\Morilog\Jalali\Jalalian::forge($transaction->created_at)->format('Y/m/d H:i')); ?>

                                    </p>
                                    <?php if($transaction->ref_id): ?>
                                        <p class="text-xs text-gray-400">کد پیگیری: <?php echo e($transaction->ref_id); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="text-left">
                                <p class="<?php if(in_array($transaction->type, ['deposit', 'refund'])): ?> text-green-600 <?php else: ?> text-red-600 <?php endif; ?> font-semibold">
                                    <?php echo e($transaction->formatted_amount); ?>

                                </p>
                                <span class="text-xs px-2 py-1 rounded-full 
                                    <?php if($transaction->status == 'completed'): ?> bg-green-100 text-green-800
                                    <?php elseif($transaction->status == 'pending'): ?> bg-yellow-100 text-yellow-800
                                    <?php else: ?> bg-red-100 text-red-800 <?php endif; ?>">
                                    <?php echo e($transaction->status_label); ?>

                                </span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-receipt text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-gray-500">هنوز تراکنشی ثبت نشده است</p>
                    <p class="text-sm text-gray-400 mt-1">پس از اولین تراکنش، تاریخچه اینجا نمایش داده می‌شود</p>
                </div>
            <?php endif; ?>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="font-semibold text-gray-800 mb-3">راهنمای کیف پول</h4>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li class="flex items-center">
                        <i class="fas fa-plus text-green-500 ml-2 text-xs"></i>
                        افزایش موجودی از طریق درگاه پرداخت
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-shopping-cart text-blue-500 ml-2 text-xs"></i>
                        پرداخت سریع خریدها با موجودی کیف پول
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-history text-purple-500 ml-2 text-xs"></i>
                        مشاهده تاریخچه کامل تراکنش‌ها در همین بخش
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/layouts/main/wallet-section.blade.php ENDPATH**/ ?>