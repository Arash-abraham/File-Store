<div id="product-files" class="content-section hidden">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">مدیریت فایل‌های محصولات</h2>
            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors" onclick="showAddFileModal()">
                <i class="fas fa-plus ml-2"></i>افزودن فایل
            </button>
        </div>

        <!-- Files List -->
        <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center ml-4">
                            <i class="fas fa-file-archive text-blue-600 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">Adobe_Photoshop_2024_Setup.zip</h3>
                            <p class="text-sm text-gray-500">محصول: Adobe Photoshop 2024</p>
                            <p class="text-sm text-gray-500">حجم: 2.8 GB - نوع: zip</p>
                            <p class="text-sm text-gray-500">آدرس: /files/products/photoshop2024/</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="text-blue-600 hover:text-blue-800 px-4 py-2 border border-blue-600 rounded-lg">
                            <i class="fas fa-download ml-1"></i>دانلود
                        </button>
                        <button class="text-green-600 hover:text-green-800" onclick="editFile(1)">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-800" onclick="deleteFile(1)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
