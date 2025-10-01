// صبر کن تا DOM کاملاً لود شود
document.addEventListener('DOMContentLoaded', function() {
    // مدیریت انتخاب آیکون‌ها
    document.querySelectorAll('.icon-option').forEach(option => {
        option.addEventListener('click', function() {
            console.log('آیکون کلیک شد:', this.getAttribute('data-icon'));
            
            // حذف انتخاب از همه آیکون‌ها
            document.querySelectorAll('.icon-option').forEach(opt => {
                opt.classList.remove('border-blue-500', 'bg-blue-50');
                opt.classList.add('border-gray-200');
                
                // رنگ آیکون‌ها رو هم به خاکستری برگردون
                const icon = opt.querySelector('i');
                icon.classList.remove('text-blue-600');
                icon.classList.add('text-gray-600');
            });
            
            // انتخاب آیکون جدید
            this.classList.remove('border-gray-200');
            this.classList.add('border-blue-500', 'bg-blue-50');
            
            // رنگ آیکون انتخاب شده رو آبی کن
            const currentIcon = this.querySelector('i');
            currentIcon.classList.remove('text-gray-600');
            currentIcon.classList.add('text-blue-600');
            
            document.getElementById('selectedIcon').value = this.getAttribute('data-icon');
            
            document.getElementById('previewIcon').className = this.getAttribute('data-icon') + ' text-blue-600 text-2xl ml-3';
        });
    });

    // بقیه کدها...
    document.querySelectorAll('.color-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.color-option').forEach(opt => {
                opt.classList.remove('border-4');
                opt.classList.add('border-2');
            });
            
            this.classList.remove('border-2');
            this.classList.add('border-4');
            
            const color = this.getAttribute('data-color');
            document.getElementById('selectedColor').value = color;
            
            const previewIcon = document.getElementById('previewIcon');
            const colorClasses = ['text-blue-600', 'text-green-600', 'text-purple-600', 'text-orange-600', 'text-red-600', 'text-yellow-600', 'text-indigo-600', 'text-pink-600'];
            colorClasses.forEach(cls => previewIcon.classList.remove(cls));
            previewIcon.classList.add(`text-${color}-600`);
        });
    });

    document.querySelector('input[name="name"]').addEventListener('input', function() {
        document.getElementById('previewName').textContent = this.value || 'نام دسته‌بندی';
    });

    document.querySelector('textarea[name="description"]').addEventListener('input', function() {
        document.getElementById('previewDescription').textContent = this.value || 'توضیحات دسته‌بندی...';
    });
});