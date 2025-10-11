<div id="newTicketModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">ایجاد تیکت جدید</h3>
            <button onclick="hideNewTicketModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">موضوع</label>
                <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">دسته‌بندی</label>
                <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <option>مشکل فنی</option>
                    <option>مسائل مالی</option>
                    <option>درخواست بازگشت وجه</option>
                    <option>سایر</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">پیام</label>
                <textarea rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none"></textarea>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    ارسال تیکت
                </button>
                <button type="button" onclick="hideNewTicketModal()" class="flex-1 border border-gray-300 py-3 rounded-lg hover:bg-gray-50 transition-colors">
                    لغو
                </button>
            </div>
        </form>
    </div>
</div><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/layouts/main/new-ticket-modal.blade.php ENDPATH**/ ?>