<div id="categories" class="content-section hidden">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">مدیریت دسته‌بندی‌ها</h2>
            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors" onclick="showAddCategoryModal()">
                <i class="fas fa-plus ml-2"></i>افزودن دسته‌بندی
            </button>
        </div>
        
        <!-- Categories Grid -->
        <div id="categoriesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Categories will be loaded by JavaScript -->
        </div>
    </div>
</div>