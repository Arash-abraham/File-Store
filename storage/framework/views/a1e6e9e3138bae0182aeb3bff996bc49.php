<?php $__env->startSection('title', $product->title); ?>

<?php $__env->startSection('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/products.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>       
        <?php if (isset($component)) { $__componentOriginal433d0650de850ca88957850e1fcee89b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal433d0650de850ca88957850e1fcee89b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.add-to-cart','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('add-to-cart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal433d0650de850ca88957850e1fcee89b)): ?>
<?php $attributes = $__attributesOriginal433d0650de850ca88957850e1fcee89b; ?>
<?php unset($__attributesOriginal433d0650de850ca88957850e1fcee89b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal433d0650de850ca88957850e1fcee89b)): ?>
<?php $component = $__componentOriginal433d0650de850ca88957850e1fcee89b; ?>
<?php unset($__componentOriginal433d0650de850ca88957850e1fcee89b); ?>
<?php endif; ?>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <?php if (isset($component)) { $__componentOriginal26e98e8e5cc4164d9d54ab94efc26e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26e98e8e5cc4164d9d54ab94efc26e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.error','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal26e98e8e5cc4164d9d54ab94efc26e46)): ?>
<?php $attributes = $__attributesOriginal26e98e8e5cc4164d9d54ab94efc26e46; ?>
<?php unset($__attributesOriginal26e98e8e5cc4164d9d54ab94efc26e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal26e98e8e5cc4164d9d54ab94efc26e46)): ?>
<?php $component = $__componentOriginal26e98e8e5cc4164d9d54ab94efc26e46; ?>
<?php unset($__componentOriginal26e98e8e5cc4164d9d54ab94efc26e46); ?>
<?php endif; ?>
    <?php endif; ?>

    <div id="cart-modal" class="fixed w-80 bg-white text-gray-800 rounded-xl shadow-2xl p-0 hidden z-50 border border-gray-200">
        <div class="flex justify-between items-center p-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-t-xl">
            <h2 class="text-lg font-bold">سبد خرید</h2>
            <button id="close-cart" class="text-white hover:bg-white/20 p-1 rounded-full transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div id="cart-content" class="max-h-64 overflow-y-auto p-4 space-y-3">
            <?php if($cartItems->isEmpty()): ?>
                <p class="text-center text-gray-500 text-sm">سبد خرید خالی است</p>
            <?php else: ?>
                <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <img src="<?php echo e(asset($item->product->image_urls[0] ?? 'images/placeholder.jpg')); ?>" 
                                alt="<?php echo e($item->product->title); ?>" class="w-12 h-12 object-cover rounded-lg shadow-sm">
                            <div>
                                <h3 class="text-sm font-semibold"><?php echo e($item->product->title); ?></h3>
                                <p class="text-xs text-purple-600 font-medium"><?php echo e(number_format($item->unit_price)); ?> تومان</p>
                            </div>
                        </div>
                        <form action="<?php echo e(route('cart.remove', $item->id)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-full transition-colors">
                                <i class="fas fa-trash-alt text-sm"></i>
                            </button>
                        </form>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
        
        <div class="p-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm text-gray-600">جمع کل:</span>
                <span class="text-lg font-bold text-purple-700"><?php echo e(number_format($total)); ?> تومان</span>
            </div>
            <?php if(!$cartItems->isEmpty()): ?>
                <a href="<?php echo e(route('checkout.show')); ?>" 
                class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white py-3 rounded-lg font-semibold transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                    <i class="fas fa-credit-card"></i>
                    تسویه حساب
                </a>
            <?php endif; ?>
        </div>
    </div>     

    <!-- Breadcrumb -->
    <section class="bg-white py-4">
        <div class="container mx-auto px-4">
            <nav class="text-sm text-gray-600">
                <ol class="list-none p-0 inline-flex">
                    <li class="flex items-center">
                        <a href="<?php echo e(route('home')); ?>" class="hover:text-blue-600">خانه</a>
                        <i class="fas fa-chevron-left mx-2"></i>
                    </li>
                    <li class="flex items-center">
                        <a href="<?php echo e(route('productsWithCategory', ['category' => $product->category->id])); ?>" class="hover:text-blue-600"><?php echo e($product->category->name); ?></a>
                        <i class="fas fa-chevron-left mx-2"></i>
                    </li>
                    <li class="text-gray-500"><?php echo e($product->title); ?></li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Product Image -->
                <div>
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-center mb-6">
                            <img id="productImage" src="<?php echo e(asset($product->image_urls[0] ?? 'images/placeholder.jpg')); ?>" 
                                 alt="<?php echo e($product->title); ?>" class="max-w-full h-80 object-contain rounded-lg">
                        </div>
                        <div class="flex gap-3 justify-center flex-wrap">
                            <?php $__currentLoopData = $product->image_urls ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <img src="<?php echo e(asset($image)); ?>" 
                                     data-index="<?php echo e($index); ?>"
                                     class="w-16 h-16 rounded-lg cursor-pointer border-2 transition-all duration-200 thumbnail 
                                            <?php echo e($index === 0 ? 'border-blue-500 scale-105' : 'border-gray-200 hover:border-blue-400'); ?>"
                                     onclick="changeImage(this)">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div>
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <div class="flex items-center gap-2 mb-4">
                            <?php if($product->availability): ?>
                                <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">موجود</span>
                            <?php else: ?> 
                                <span class="bg-red-100 text-red-800 text-xs px-3 py-1 rounded-full">ناموجود</span>
                            <?php endif; ?>                            
                        </div>
                        
                        <h1 class="text-3xl font-bold text-gray-800 mb-4"><?php echo e($product->title); ?></h1>
                    

                        <div class="mb-6">
                            <div class="flex items-center gap-4">
                                <span class="text-3xl font-bold text-green-600"><?php echo e(number_format($product->original_price)); ?> تومان</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-800 mb-3">ویژگی‌های کلیدی:</h3>
                            <ul class="space-y-2 text-gray-600">
                                <?php $__currentLoopData = $product->key_features ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 ml-2"></i><?php echo e($item); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>

                        <form action="<?php echo e(route('cart.add')); ?>" method="POST" class="flex gap-4 mb-6">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                            <input type="number" name="quantity" value="1" min="1" class="w-20 p-2 border rounded">
                            <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-xl hover:bg-blue-700 transition-colors font-semibold <?php echo e(!$product->availability ? 'opacity-50 cursor-not-allowed' : ''); ?>" 
                                    <?php echo e(!$product->availability ? 'disabled' : ''); ?>>
                                <i class="fas fa-shopping-cart ml-2"></i>افزودن به سبد خرید
                            </button>
                        </form>

                        <?php if(session('success')): ?>
                            <div class="text-green-600 mb-4"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>
                        <?php if(session('error')): ?>
                            <div class="text-red-600 mb-4"><?php echo e(session('error')); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tabs Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="bg-white rounded-xl shadow-lg">
                <div class="border-b">
                    <nav class="flex">
                        <button class="tab-button active px-8 py-4 font-semibold border-b-2 border-blue-500 text-blue-600" onclick="showTab('description')">
                            توضیحات
                        </button>
                        <button class="tab-button px-8 py-4 font-semibold text-gray-600 hover:text-blue-600 transition-colors" onclick="showTab('files')">
                            فایل‌های مرتبط
                        </button>
                        <button class="tab-button px-8 py-4 font-semibold text-gray-600 hover:text-blue-600 transition-colors" onclick="showTab('reviews')">
                            نظرات
                        </button>
                    </nav>
                </div>

                <div class="p-8">
                    <div id="description" class="tab-content">
                        <h3 class="text-2xl font-bold mb-4">درباره <?php echo e($product->title); ?></h3>
                        <div class="prose max-w-none text-gray-700 leading-relaxed">
                            <p class="mb-4"><?php echo e($product->description); ?></p>
                        </div>
                    </div>

                    <div id="files" class="tab-content hidden">
                        <h3 class="text-2xl font-bold mb-6">فایل‌های قابل دانلود</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center ml-4">
                                        <i class="fas fa-file-archive text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold"><?php echo e($product->title); ?>_Setup.zip</h4>
                                        <p class="text-sm text-gray-500">نسخه کامل نرم‌افزار - 2.8 GB</p>
                                    </div>
                                </div>
                                <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    دانلود
                                </button>
                            </div>
                            <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center ml-4">
                                        <i class="fas fa-key text-green-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold">License_Key.txt</h4>
                                        <p class="text-sm text-gray-500">کلید فعالسازی - 1 KB</p>
                                    </div>
                                </div>
                                <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    دانلود
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="reviews" class="tab-content hidden">
                        <h3 class="text-2xl font-bold mb-6">نظرات کاربران</h3>
                        
                        <!-- Submit Review Form (Only for logged-in users) -->
                        <?php if(auth()->guard()->check()): ?>
                            <div class="bg-blue-50 rounded-lg p-6 mb-8">
                                <h4 class="text-xl font-bold mb-4">ثبت نظر شما</h4>

                                <form action="<?php echo e(route('review.store')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">

                                    <div class="mb-4">
                                        <label class="block text-gray-700 font-semibold mb-2">نظر شما:</label>
                                        <textarea name="body" rows="5" 
                                                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                                placeholder="نظر خود را درباره این محصول بنویسید... (حداقل 10 کاراکتر)"
                                                required><?php echo e(old('body')); ?></textarea>
                                        <?php $__errorArgs = ['body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                                        <i class="fas fa-paper-plane ml-2"></i>ارسال نظر
                                    </button>
                                </form>
                            </div>
                        <?php else: ?>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8 text-center">
                                <i class="fas fa-info-circle text-yellow-600 text-3xl mb-3"></i>
                                <p class="text-gray-700 mb-4">برای ثبت نظر ابتدا باید وارد حساب کاربری خود شوید</p>
                                <a href="<?php echo e(route('login')); ?>" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                                    ورود به حساب کاربری
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- Reviews List -->
                        <div class="space-y-6">
                            <?php $__empty_1 = true; $__currentLoopData = $product->approvedReviews()->latest()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="border rounded-lg p-6 hover:shadow-md transition">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h5 class="font-bold text-gray-800"><?php echo e($review->user->name ?? 'کاربر'); ?></h5>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-sm text-gray-500">
                                                    <?php echo e(\Morilog\Jalali\Jalalian::forge($review->created_at)->format('Y/m/d')); ?>

                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <p class="text-gray-700 leading-relaxed mb-4"><?php echo e($review->body); ?></p>
                                    
                                    <?php if(auth()->guard()->check()): ?>
                                        <div class="flex gap-4 text-sm">
                                            <form action="<?php echo e(route('review.helpful', $review->id)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="text-gray-600 hover:text-green-600 transition">
                                                    <i class="far fa-thumbs-up ml-1"></i>
                                                    مفید بود (<?php echo e($review->helpful_count); ?>)
                                                </button>
                                            </form>
                                            <form action="<?php echo e(route('review.report', $review->id)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="text-gray-600 hover:text-red-600 transition"
                                                        >
                                                    <i class="far fa-flag ml-1"></i>گزارش
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-12 text-gray-500">
                                    <i class="fas fa-comments text-5xl mb-4"></i>
                                    <p>هنوز نظری ثبت نشده است. اولین نفر باشید!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">محصولات مرتبط</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                        <div class="relative overflow-hidden">
                            <img src="<?php echo e(asset($relatedProduct->image_urls[0] ?? 'images/placeholder.jpg')); ?>" 
                                alt="<?php echo e($relatedProduct->title); ?>"
                                class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute top-3 left-3">
                                <?php if($relatedProduct->availability): ?>
                                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">موجود</span>
                                <?php else: ?>
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">ناموجود</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-bold text-gray-800 mb-2 line-clamp-2 h-12">
                                <a href="<?php echo e(route('show-product', $relatedProduct->id)); ?>" 
                                class="hover:text-blue-600 transition-colors">
                                    <?php echo e($relatedProduct->title); ?>

                                </a>
                            </h3>
                            
                            <div class="flex items-center justify-between mt-3">
                                <span class="text-lg font-bold text-green-600">
                                    <?php echo e(number_format($relatedProduct->original_price)); ?> تومان
                                </span>
                            </div>
                            
                            <div class="mt-4">
                                <a href="<?php echo e(route('show-product', $relatedProduct->id)); ?>" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center gap-2">
                                    <i class="fas fa-eye text-xs"></i>
                                    مشاهده محصول
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                <?php if($relatedProducts->isEmpty()): ?>
                    <div class="col-span-full text-center py-8">
                        <i class="fas fa-box-open text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500 text-lg">محصول مرتبطی یافت نشد</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        function changeImage(clickedImage) {
            const mainImage = document.getElementById('productImage');
            mainImage.src = clickedImage.src;
            const thumbnails = document.querySelectorAll('.thumbnail');
            thumbnails.forEach(thumb => {
                thumb.classList.remove('border-blue-500', 'scale-105');
                thumb.classList.add('border-gray-200');
            });
            clickedImage.classList.remove('border-gray-200');
            clickedImage.classList.add('border-blue-500', 'scale-105');
            mainImage.style.opacity = '0.7';
            setTimeout(() => mainImage.style.opacity = '1', 150);
        }

        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-b-2', 'border-blue-500', 'text-blue-600');
                button.classList.add('text-gray-600');
            });
            const activeTab = document.getElementById(tabName);
            if (activeTab) {
                activeTab.classList.remove('hidden');
                const activeButton = document.querySelector(`button[onclick="showTab('${tabName}')"]`);
                if (activeButton) {
                    activeButton.classList.add('active', 'border-b-2', 'border-blue-500', 'text-blue-600');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const firstThumbnail = document.querySelector('.thumbnail');
            if (firstThumbnail) {
                firstThumbnail.classList.add('border-blue-500', 'scale-105');
            }

            // چک کردن sessionStorage یا URL برای فعال کردن تب
            const savedTab = sessionStorage.getItem('activeTab') || window.location.hash.replace('#', '');
            const tabToShow = (savedTab === 'reviews' || savedTab === 'description' || savedTab === 'files') ? savedTab : 'description';
            showTab(tabToShow);

            // اضافه کردن #reviews به فرم ثبت نظر
            const reviewForm = document.querySelector('form[action="<?php echo e(route('review.store')); ?>"]');
            if (reviewForm) {
                reviewForm.addEventListener('submit', function() {
                    sessionStorage.setItem('activeTab', 'reviews'); // ذخیره تب فعال
                    window.location.hash = 'reviews'; // اضافه کردن #reviews به URL
                });
            }
        });
    </script>
    <script src="<?php echo e(asset('js/modal.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('app.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/app/show-product.blade.php ENDPATH**/ ?>