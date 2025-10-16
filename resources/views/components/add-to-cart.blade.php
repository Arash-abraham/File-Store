<!-- پاپ‌آپ سبد خرید -->
<div id="cart-popup" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="bg-black bg-opacity-50 absolute inset-0" onclick="hidePopup()"></div>
    
    <div class="bg-white rounded-2xl shadow-2xl w-11/12 max-w-md mx-auto relative z-10">
        <!-- هدر -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3 space-x-reverse">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-green-600 text-lg"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">موفقیت آمیز</h3>
            </div>
            <button onclick="hidePopup()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- محتوا -->
        <div class="p-6">
            <div class="text-center">
                <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shopping-cart text-green-600 text-3xl"></i>
                </div>
                <p class="text-gray-600 mb-6">{{ session('add_to_cart') }}</p>
                
            </div>
        </div>
    </div>
</div>

<script>
function showPopup() {
    document.getElementById('cart-popup').classList.remove('hidden');
}

function hidePopup() {
    document.getElementById('cart-popup').classList.add('hidden');
}


</script>