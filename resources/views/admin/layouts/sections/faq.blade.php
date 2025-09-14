<div id="faq" class="content-section hidden">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">مدیریت سوالات متداول</h2>
            <button onclick="showAddFaqModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors font-semibold">
                <i class="fas fa-plus ml-1"></i>افزودن سوال جدید
            </button>
        </div>
        
        <!-- FAQ Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-blue-50 rounded-lg p-6 text-center">
                <i class="fas fa-question-circle text-blue-600 text-3xl mb-3"></i>
                <h3 class="text-2xl font-bold text-gray-800" id="totalFaqs">0</h3>
                <p class="text-gray-600">کل سوالات</p>
            </div>
            <div class="bg-green-50 rounded-lg p-6 text-center">
                <i class="fas fa-eye text-green-600 text-3xl mb-3"></i>
                <h3 class="text-2xl font-bold text-gray-800" id="publishedFaqs">0</h3>
                <p class="text-gray-600">منتشر شده</p>
            </div>
            <div class="bg-yellow-50 rounded-lg p-6 text-center">
                <i class="fas fa-edit text-yellow-600 text-3xl mb-3"></i>
                <h3 class="text-2xl font-bold text-gray-800" id="draftFaqs">0</h3>
                <p class="text-gray-600">پیش‌نویس</p>
            </div>
        </div>
        
        <!-- FAQ Items -->
        <div id="faqAccordion" class="space-y-4">
            <!-- FAQ items will be populated by JavaScript -->
        </div>
    </div>
</div>