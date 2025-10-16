<div id="password" class="content-section hidden">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">تغییر رمز عبور</h2>
        

        
        <form action="{{ route('password.update') }}" method="POST" class="space-y-6 max-w-md">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">رمز عبور فعلی</label>
                <div class="relative">
                    <input type="password" name="current_password" id="currentPassword" 
                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                           required>
                    <button type="button" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600" onclick="togglePassword('currentPassword')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">رمز عبور جدید</label>
                <div class="relative">
                    <input type="password" name="new_password" id="newPassword" 
                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                           required minlength="8">
                    <button type="button" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600" onclick="togglePassword('newPassword')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="mt-2">
                    <div class="text-xs text-gray-500 mb-1">
                        رمز عبور باید حداقل 8 کاراکتر و شامل حروف و اعداد باشد
                    </div>
                    <div class="flex">
                        <div class="flex-1 h-1 bg-gray-200 rounded-full ml-2">
                            <div class="h-1 bg-red-500 rounded-full transition-all" style="width: 0%"></div>
                        </div>
                        <span class="text-xs text-gray-500">ضعیف</span>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">تکرار رمز عبور جدید</label>
                <div class="relative">
                    <input type="password" name="new_password_confirmation" id="confirmNewPassword" 
                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                           required minlength="8">
                    <button type="button" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600" onclick="togglePassword('confirmNewPassword')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    تغییر رمز عبور
                </button>
                <button type="button" onclick="document.querySelector('#password form').reset()" 
                        class="border border-gray-300 px-8 py-3 rounded-lg hover:bg-gray-50 transition-colors">
                    لغو
                </button>
            </div>
        </form>
    </div>
</div>