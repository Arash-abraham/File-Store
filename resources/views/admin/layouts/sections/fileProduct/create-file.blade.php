@extends('admin.layouts.partials.master')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">افزودن فایل‌های محصول</h2>
        
        <form action="{{ route('admin.file-product.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- انتخاب محصول -->
            <div>
                <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">محصول</label>
                <select name="product_id" id="product_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">انتخاب محصول</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->title }}</option>
                    @endforeach
                </select>
                @error('product_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- تعداد فایل‌ها -->
            <div>
                <label for="file_count" class="block text-sm font-medium text-gray-700 mb-2">تعداد فایل‌هایی که می‌خواهید اضافه کنید</label>
                <select name="file_count" id="file_count" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    onchange="generateFileFields()">
                    <option value="">انتخاب تعداد</option>
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}">{{ $i }} فایل</option>
                    @endfor
                </select>
            </div>

            <!-- بخش فایل‌ها -->
            <div id="file_fields" class="space-y-4">
                <!-- فیلدهای فایل به صورت داینامیک ایجاد می‌شوند -->
            </div>

            <!-- دکمه ارسال -->
            <div class="flex justify-end">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                    ذخیره فایل‌ها
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function generateFileFields() {
    const fileCount = document.getElementById('file_count').value;
    const fileFields = document.getElementById('file_fields');
    fileFields.innerHTML = '';

    for (let i = 1; i <= fileCount; i++) {
        const fileField = `
            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                <h4 class="font-medium text-gray-700 mb-3">فایل ${i}</h4>
                
                <!-- نام فایل -->
                <div class="mb-3">
                    <label for="name_${i}" class="block text-sm font-medium text-gray-700 mb-1">نام نمایشی</label>
                    <input type="text" name="names[]" id="name_${i}" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="مثال: راهنمای نصب" required>
                </div>

                <!-- آپلود فایل -->
                <div class="mb-3">
                    <label for="file_${i}" class="block text-sm font-medium text-gray-700 mb-1">فایل</label>
                    <input type="file" name="files[]" id="file_${i}" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        accept=".pdf,.zip,.rar" required>
                    <p class="text-xs text-gray-500 mt-1">فرمت‌های مجاز: PDF, ZIP, RAR (حداکثر حجم: 25MB)</p>
                </div>

                <!-- نوع فایل -->
                <div class="mb-3">
                    <label for="type_${i}" class="block text-sm font-medium text-gray-700 mb-1">نوع فایل</label>
                    <select name="types[]" id="type_${i}" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="pdf">PDF</option>
                        <option value="zip">ZIP</option>
                        <option value="rar">RAR</option>
                    </select>
                </div>

                <!-- سایز فایل -->
                <div class="mb-3">
                    <label for="size_label_${i}" class="block text-sm font-medium text-gray-700 mb-1">برچسب سایز</label>
                    <input type="text" name="size_labels[]" id="size_label_${i}" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="مثال: 2.5 مگابایت">
                </div>

                <!-- ترتیب نمایش -->
                <div>
                    <label for="sort_order_${i}" class="block text-sm font-medium text-gray-700 mb-1">ترتیب نمایش</label>
                    <input type="number" name="sort_orders[]" id="sort_order_${i}" value="${i}" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        min="1" max="100">
                </div>
            </div>
        `;
        fileFields.innerHTML += fileField;
    }
}

// اعتبارسنجی فایل‌ها قبل از ارسال
document.querySelector('form').addEventListener('submit', function(e) {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    let isValid = true;

    fileInputs.forEach(input => {
        if (input.files.length > 0) {
            const file = input.files[0];
            const maxSize = 25 * 1024 * 1024; // 25MB
            const allowedTypes = ['application/pdf', 'application/zip', 'application/x-rar-compressed'];

            if (file.size > maxSize) {
                alert(`حجم فایل "${file.name}" نباید بیشتر از 25 مگابایت باشد`);
                isValid = false;
            }

            if (!allowedTypes.includes(file.type)) {
                alert(`فرمت فایل "${file.name}" مجاز نیست. فقط PDF, ZIP, RAR مجاز هستند`);
                isValid = false;
            }
        }
    });

    if (!isValid) {
        e.preventDefault();
    }
});
</script>
@endsection