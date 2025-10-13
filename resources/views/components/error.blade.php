<!-- پاپ‌آپ خطا -->
<div id="error-popup" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="bg-black bg-opacity-50 absolute inset-0" onclick="hideErrorPopup()"></div>
    
    <div class="bg-white rounded-2xl shadow-2xl w-11/12 max-w-md mx-auto relative z-10">
        <!-- هدر -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3 space-x-reverse">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">خطا</h3>
            </div>
            <button onclick="hideErrorPopup()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- محتوا -->
        <div class="p-6">
            <div class="text-center">

                @foreach($errors->all() as $error)
                    <p id="error-message" class="text-gray-600 mb-6">{{$error}}</p>
                @endforeach
                
                <div class="flex space-x-3 space-x-reverse">
                    <button onclick="hideErrorPopup()" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 px-4 rounded-xl font-medium transition-colors duration-200">
                        متوجه شدم
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showErrorPopup(message) {
    document.getElementById('error-message').textContent = message;
    document.getElementById('error-popup').classList.remove('hidden');
}

function hideErrorPopup() {
    document.getElementById('error-popup').classList.add('hidden');
}


</script>