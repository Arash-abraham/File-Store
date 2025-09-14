<div id="addProductModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-8 max-w-4xl w-full mx-4 max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold">افزودن محصول جدید</h3>
            <button onclick="hideAddProductModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form id="addProductForm" class="space-y-6">
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">عنوان محصول *</label>
                    <input type="text" name="title" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="مثال: Adobe Photoshop 2024">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">دسته‌بندی *</label>
                    <select name="category" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">انتخاب دسته‌بندی</option>
                        <option value="software">نرم‌افزار</option>
                        <option value="courses">دوره آموزشی</option>
                        <option value="ebooks">کتاب الکترونیکی</option>
                        <option value="templates">قالب</option>
                    </select>
                </div>
            </div>

            <!-- Pricing -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">قیمت اصلی (تومان) *</label>
                    <input type="number" name="originalPrice" required min="0" id="originalPriceInput"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="مثال: 3000000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">قیمت با تخفیف (تومان)</label>
                    <input type="number" name="price" min="0" id="discountPriceInput"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="مثال: 2500000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">درصد تخفیف</label>
                    <input type="number" name="discount" min="0" max="100" readonly id="discountPercent"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100"
                           placeholder="محاسبه خودکار">
                </div>
            </div>

            <!-- Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت محصول</label>
                    <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="active">فعال</option>
                        <option value="inactive">غیرفعال</option>
                        <option value="draft">پیش‌نویس</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت موجودی</label>
                    <select name="availability" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="true">در دسترس</option>
                        <option value="false">ناموجود</option>
                    </select>
                </div>
            </div>

            <!-- Rating -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">امتیاز (1-5)</label>
                    <select name="rating" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="5">5 ستاره</option>
                        <option value="4.8">4.8 ستاره</option>
                        <option value="4.5" selected>4.5 ستاره</option>
                        <option value="4">4 ستاره</option>
                        <option value="3.5">3.5 ستاره</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">تعداد نظرات</label>
                    <input type="number" name="reviews" min="0" value="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Tags -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">برچسب‌ها (با کاما جدا کنید)</label>
                <input type="text" name="tags" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="مثال: Adobe, طراحی گرافیک, ویرایش تصویر">
            </div>

            <!-- Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">تصویر محصول</label>
                <input type="url" name="image" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="https://example.com/image.jpg">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات محصول</label>
                <textarea name="description" rows="4" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="توضیحات کاملی از محصول..."></textarea>
            </div>

            <!-- Actions -->
            <div class="flex gap-4 pt-4 border-t">
                <button type="submit" 
                        class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    <i class="fas fa-plus ml-1"></i>افزودن محصول
                </button>
                <button type="button" onclick="hideAddProductModal()" 
                        class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    لغو
                </button>
            </div>
        </form>
    </div>
</div>