<div id="tickets" class="content-section hidden">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">تیکت‌های پشتیبانی</h2>
            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors" onclick="showNewTicketModal()">
                تیکت جدید
            </button>
        </div>
        
        <div class="space-y-4">
            <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border border-gray-200 rounded-lg p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-semibold text-lg"><?php echo e($ticket->subject); ?></h3>
                            <p class="text-gray-600">تیکت #<?php echo e($ticket->id); ?></p>
                            <p class="text-sm text-gray-500"><?php echo e(\Morilog\Jalali\Jalalian::forge($ticket->created_at)->format('Y/m/d H:i')); ?></p>
                        </div>
                        <div class="flex items-center gap-3">
                            <?php if($ticket->status == 'open'): ?>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    باز
                                </span>
                            <?php elseif($ticket->status == 'in_progress'): ?>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    درحال بررسی
                                </span>
                            <?php else: ?>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    بسته شد
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    سوال : <p class="text-gray-700 bg-gray-50 p-3 rounded-lg"><?php echo e($ticket->message); ?></p>

                    <br>

                    پاسخ : <p class="text-gray-700 bg-gray-50 p-3 rounded-lg">
                        <?php if($ticket->response): ?>
                            <?php echo e($ticket->response); ?>

                        <?php else: ?>
                            هنوز پاسخی ثبت نشده است
                        <?php endif; ?>
                    </p>

                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/layouts/main/tickets-section.blade.php ENDPATH**/ ?>