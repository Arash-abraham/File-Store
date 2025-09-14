<div id="editCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-8 max-w-2xl w-full mx-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">ویرایش دسته‌بندی</h3>
            <button onclick="hideEditCategoryModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="editCategoryForm" class="space-y-6">
            <input type="hidden" name="categoryId" id="editCategoryId">
            
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نام دسته‌بندی *</label>
                    <input type="text" name="name" required id="editCategoryName"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="مثال: نرم‌افزارها">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">شناسه (Slug) *</label>
                    <input type="text" name="slug" required id="editCategorySlug"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="software">
                    <p class="text-xs text-gray-500 mt-1">فقط حروف انگلیسی، اعداد و خط تیره</p>
                </div>
            </div>

            <!-- Icon Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">انتخاب آیکون</label>
                <div class="grid grid-cols-6 gap-3" id="editIconSelection">
                    <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-laptop-code">
                        <i class="fas fa-laptop-code text-gray-600 text-xl"></i>
                    </div>
                    <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-play-circle">
                        <i class="fas fa-play-circle text-gray-600 text-xl"></i>
                    </div>
                    <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-book">
                        <i class="fas fa-book text-gray-600 text-xl"></i>
                    </div>
                    <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-palette">
                        <i class="fas fa-palette text-gray-600 text-xl"></i>
                    </div>
                    <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-mobile-alt">
                        <i class="fas fa-mobile-alt text-gray-600 text-xl"></i>
                    </div>
                    <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-gamepad">
                        <i class="fas fa-gamepad text-gray-600 text-xl"></i>
                    </div>
                    <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-camera">
                        <i class="fas fa-camera text-gray-600 text-xl"></i>
                    </div>
                    <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-music">
                        <i class="fas fa-music text-gray-600 text-xl"></i>
                    </div>
                    <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-chart-bar">
                        <i class="fas fa-chart-bar text-gray-600 text-xl"></i>
                    </div>
                    <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-tools">
                        <i class="fas fa-tools text-gray-600 text-xl"></i>
                    </div>
                    <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-graduation-cap">
                        <i class="fas fa-graduation-cap text-gray-600 text-xl"></i>
                    </div>
                    <div class="edit-icon-option p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50" data-icon="fas fa-shopping-cart">
                        <i class="fas fa-shopping-cart text-gray-600 text-xl"></i>
                    </div>
                </div>
                <input type="hidden" name="icon" value="" id="editSelectedIcon">
            </div>

            <!-- Color Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">انتخاب رنگ</label>
                <div class="flex gap-3" id="editColorSelection">
                    <div class="edit-color-option w-10 h-10 rounded-full bg-blue-500 border-2 border-gray-300 cursor-pointer" data-color="blue"></div>
                    <div class="edit-color-option w-10 h-10 rounded-full bg-green-500 border-2 border-gray-300 cursor-pointer" data-color="green"></div>
                    <div class="edit-color-option w-10 h-10 rounded-full bg-purple-500 border-2 border-gray-300 cursor-pointer" data-color="purple"></div>
                    <div class="edit-color-option w-10 h-10 rounded-full bg-orange-500 border-2 border-gray-300 cursor-pointer" data-color="orange"></div>
                    <div class="edit-color-option w-10 h-10 rounded-full bg-red-500 border-2 border-gray-300 cursor-pointer" data-color="red"></div>
                    <div class="edit-color-option w-10 h-10 rounded-full bg-yellow-500 border-2 border-gray-300 cursor-pointer" data-color="yellow"></div>
                    <div class="edit-color-option w-10 h-10 rounded-full bg-indigo-500 border-2 border-gray-300 cursor-pointer" data-color="indigo"></div>
                    <div class="edit-color-option w-10 h-10 rounded-full bg-pink-500 border-2 border-gray-300 cursor-pointer" data-color="pink"></div>
                </div>
                <input type="hidden" name="color" value="" id="editSelectedColor">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات دسته‌بندی</label>
                <textarea name="description" rows="3" id="editCategoryDescription"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="توضیحات کوتاهی درباره این دسته‌بندی..."></textarea>
            </div>

            <!-- Preview -->
            <div id="editCategoryPreview" class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold mb-3">پیش‌نمایش:</h4>
                <div class="border border-gray-200 rounded-lg p-6 bg-white max-w-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <i id="editPreviewIcon" class="fas fa-laptop-code text-blue-600 text-2xl ml-3"></i>
                            <div>
                                <h3 id="editPreviewName" class="font-semibold">نام دسته‌بندی</h3>
                                <p class="text-sm text-gray-500" id="editPreviewCount">0 محصول</p>
                            </div>
                        </div>
                    </div>
                    <p id="editPreviewDescription" class="text-gray-600 text-sm">توضیحات دسته‌بندی...</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-4 pt-4 border-t">
                <button type="submit" 
                        class="flex-1 bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                    <i class="fas fa-save ml-1"></i>ذخیره تغییرات
                </button>
                <button type="button" onclick="hideEditCategoryModal()" 
                        class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    لغو
                </button>
            </div>
        </form>
    </div>
</div>
