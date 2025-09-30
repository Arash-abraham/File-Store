<div id="addFaqModal" class="modal-overlay">
    <div class="bg-white rounded-xl p-8 max-w-2xl w-full mx-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">افزودن پاسخ برای سوالات متداول</h3>
            <button onclick="hideFaqModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.faq.create') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">سوال</label>
                <input type="text" name="question" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">پاسخ</label>
                    <textarea name="answer" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
            </div>
        
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    ثبت
                </button>
                <button type="button" onclick="hideFaqModal()" class="flex-1 border border-gray-300 py-3 rounded-lg hover:bg-gray-50 transition-colors">
                    لغو
                </button>
            </div>
        </form>
    </div>
</div>