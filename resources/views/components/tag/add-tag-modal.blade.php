<div id="addTagModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-8 max-w-lg w-full mx-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">افزودن برچسب جدید</h3>
            <button onclick="hideAddTagModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="addTagForm" class="space-y-6">
            <!-- Basic Info -->
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نام برچسب *</label>
                    <input type="text" name="name" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="مثال: Adobe">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">شناسه (Slug) *</label>
                    <input type="text" name="slug" required id="tagSlug"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="adobe">
                    <p class="text-xs text-gray-500 mt-1">فقط حروف انگلیسی، اعداد و خط تیره</p>
                </div>
            </div>

            <!-- Color Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">انتخاب رنگ</label>
                <div class="flex gap-3 flex-wrap" id="tagColorSelection">
                    <div class="tag-color-option w-12 h-12 rounded-full bg-blue-500 border-4 border-blue-200 cursor-pointer flex items-center justify-center text-white font-bold" data-color="blue">A</div>
                    <div class="tag-color-option w-12 h-12 rounded-full bg-green-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="green">A</div>
                    <div class="tag-color-option w-12 h-12 rounded-full bg-purple-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="purple">A</div>
                    <div class="tag-color-option w-12 h-12 rounded-full bg-orange-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="orange">A</div>
                    <div class="tag-color-option w-12 h-12 rounded-full bg-red-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="red">A</div>
                    <div class="tag-color-option w-12 h-12 rounded-full bg-yellow-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-yellow-800 font-bold" data-color="yellow">A</div>
                    <div class="tag-color-option w-12 h-12 rounded-full bg-indigo-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="indigo">A</div>
                    <div class="tag-color-option w-12 h-12 rounded-full bg-pink-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="pink">A</div>
                    <div class="tag-color-option w-12 h-12 rounded-full bg-gray-500 border-2 border-gray-300 cursor-pointer flex items-center justify-center text-white font-bold" data-color="gray">A</div>
                </div>
                <input type="hidden" name="color" value="blue" id="selectedTagColor">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات برچسب (اختیاری)</label>
                <textarea name="description" rows="2" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="توضیحات کوتاهی درباره این برچسب..."></textarea>
            </div>

            <!-- Preview -->
            <div id="tagPreview" class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold mb-3">پیش‌نمایش:</h4>
                <div class="flex gap-2">
                    <span id="previewTag" class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm">نام برچسب</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-4 pt-4 border-t">
                <button type="submit" 
                        class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    <i class="fas fa-plus ml-1"></i>افزودن برچسب
                </button>
                <button type="button" onclick="hideAddTagModal()" 
                        class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    لغو
                </button>
            </div>
        </form>
    </div>
</div>