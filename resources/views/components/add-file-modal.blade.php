
<div id="addFileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-8 max-w-2xl w-full mx-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">افزودن فایل محصول</h3>
            <button onclick="hideAddFileModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="addFileForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">نام فایل</label>
                <input type="text" name="fileName" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">محصول مربوطه</label>
                <select name="productId" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">انتخاب محصول</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">آدرس فایل</label>
                    <input type="text" name="filePath" required
                           placeholder="/files/products/..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">حجم فایل</label>
                    <input type="text" name="fileSize" required
                           placeholder="2.8 GB" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">نوع فایل</label>
                <select name="fileType" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">انتخاب نوع</option>
                    <option value="zip">ZIP</option>
                    <option value="rar">RAR</option>
                    <option value="pdf">PDF</option>
                    <option value="mp4">MP4</option>
                </select>
            </div>
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    افزودن فایل
                </button>
                <button type="button" onclick="hideAddFileModal()" class="flex-1 border border-gray-300 py-3 rounded-lg hover:bg-gray-50 transition-colors">
                    لغو
                </button>
            </div>
        </form>
    </div>
</div>