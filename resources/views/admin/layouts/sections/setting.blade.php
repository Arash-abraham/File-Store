@extends('admin.layouts.partials.master')

@section('content')

    <div id="settings" class="content-section">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">تنظیمات سایت</h2>
            
            <form class="space-y-6">
                <!-- Logo and Icon -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">لوگوی سایت</label>
                        <div class="flex items-center gap-4">
                            <img src="https://images.pexels.com/photos/1181533/pexels-photo-1181533.jpeg?auto=compress&cs=tinysrgb&w=100" 
                                class="w-16 h-16 rounded-lg">
                            <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                تغییر لوگو
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">آیکون سایت</label>
                        <div class="flex items-center gap-4">
                            <img src="/vite.svg" class="w-8 h-8">
                            <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                تغییر آیکون
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Site Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">عنوان سایت</label>
                        <input type="text" value="فروشگاه آنلاین" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات سایت</label>
                        <textarea rows="4" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">بهترین و معتبرترین فروشگاه محصولات دیجیتال در ایران</textarea>
                    </div>
                </div>


                <!-- Contact Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">شماره تماس</label>
                        <input type="text" value="021-12345678" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ایمیل</label>
                        <input type="email" value="info@shop.com" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">آدرس</label>
                    <input type="text" value="تهران، خیابان آزادی" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    ذخیره تغییرات
                </button>
            </form>
        </div>
    </div>

@endsection