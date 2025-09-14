<div id="products" class="content-section hidden">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">مدیریت محصولات</h2>
            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors" onclick="showAddProductModal()">
                <i class="fas fa-plus ml-2"></i>افزودن محصول
            </button>
        </div>
        
        <!-- Search and Filter -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1">
                <input type="text" id="productSearch" placeholder="جستجوی محصولات..." 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
            </div>
            <select class="border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:outline-none">
                <option>همه دسته‌ها</option>
                <option>نرم‌افزارها</option>
                <option>دوره‌های آموزشی</option>
                <option>کتاب‌های الکترونیکی</option>
                <option>قالب‌ها</option>
            </select>
            <select class="border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:outline-none">
                <option>همه وضعیت‌ها</option>
                <option>فعال</option>
                <option>غیرفعال</option>
                <option>پیش‌نویس</option>
            </select>
        </div>
        
        <!-- Products Table -->
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-right p-4">تصویر</th>
                        <th class="text-right p-4">نام محصول</th>
                        <th class="text-right p-4">نوع</th>
                        <th class="text-right p-4">قیمت</th>
                        <th class="text-right p-4">قیمت با تخفیف</th>
                        <th class="text-right p-4">وضعیت</th>
                        <th class="text-right p-4">وضعیت فروش</th>
                        <th class="text-right p-4">عملیات</th>
                    </tr>
                </thead>
                <tbody id="productsTableBody">
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4">
                            <img src="https://images.pexels.com/photos/4348401/pexels-photo-4348401.jpeg?auto=compress&cs=tinysrgb&w=100" 
                                 class="w-16 h-16 rounded-lg object-cover">
                        </td>
                        <td class="p-4 font-semibold">Adobe Photoshop 2024</td>
                        <td class="p-4">نرم‌افزار</td>
                        <td class="p-4">3,000,000 تومان</td>
                        <td class="p-4">2,500,000 تومان</td>
                        <td class="p-4">
                            <span class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">فعال</span>
                        </td>
                        <td class="p-4">
                            <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">در دسترس</span>
                        </td>
                        <td class="p-4">
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800" onclick="editProduct(1)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800" onclick="deleteProduct(1)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            <nav class="flex items-center space-x-2">
                <button class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    قبلی
                </button>
                <button class="px-3 py-2 bg-blue-600 text-white rounded-lg">1</button>
                <button class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">2</button>
                <button class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    بعدی
                </button>
            </nav>
        </div>
    </div>
</div>