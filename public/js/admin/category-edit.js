// صبر کن تا DOM کاملاً لود شود
document.addEventListener('DOMContentLoaded', function() {
    // گرفتن مقادیر از data attributes
    const form = document.getElementById('addCategoryForm');
    const currentIcon = form.getAttribute('data-current-icon');
    const currentColor = form.getAttribute('data-current-color');
    
    console.log('آیکون از دیتابیس:', currentIcon);
    console.log('رنگ از دیتابیس:', currentColor);

    // تابع برای انتخاب آیکون
    function selectIcon(iconElement) {
        console.log('آیکون کلیک شد:', iconElement.getAttribute('data-icon'));
        
        // حذف انتخاب از همه آیکون‌ها
        document.querySelectorAll('.icon-option').forEach(opt => {
            opt.classList.remove('border-blue-500', 'bg-blue-50');
            opt.classList.add('border-gray-200');
            
            const icon = opt.querySelector('i');
            icon.classList.remove('text-blue-600');
            icon.classList.add('text-gray-600');
        });
        
        // انتخاب آیکون جدید
        iconElement.classList.remove('border-gray-200');
        iconElement.classList.add('border-blue-500', 'bg-blue-50');
        
        const currentIcon = iconElement.querySelector('i');
        currentIcon.classList.remove('text-gray-600');
        currentIcon.classList.add('text-blue-600');
        
        document.getElementById('selectedIcon').value = iconElement.getAttribute('data-icon');
        
        // آپدیت پیش‌نمایش با رنگ فعلی
        const currentColor = document.getElementById('selectedColor').value;
        document.getElementById('previewIcon').className = iconElement.getAttribute('data-icon') + ` text-${currentColor}-600 text-2xl ml-3`;
    }

    // تابع برای انتخاب رنگ
    function selectColor(colorElement) {
        document.querySelectorAll('.color-option').forEach(opt => {
            opt.classList.remove('border-4');
            opt.classList.add('border-2');
        });
        
        colorElement.classList.remove('border-2');
        colorElement.classList.add('border-4');
        
        const color = colorElement.getAttribute('data-color');
        document.getElementById('selectedColor').value = color;
        
        const previewIcon = document.getElementById('previewIcon');
        const colorClasses = ['text-blue-600', 'text-green-600', 'text-purple-600', 'text-orange-600', 'text-red-600', 'text-yellow-600', 'text-indigo-600', 'text-pink-600'];
        colorClasses.forEach(cls => previewIcon.classList.remove(cls));
        previewIcon.classList.add(`text-${color}-600`);
    }

    // پیدا کردن و انتخاب آیکون بر اساس مقدار دیتابیس
    function selectIconByValue(iconValue) {
        const iconElement = document.querySelector(`.icon-option[data-icon="${iconValue}"]`);
        if (iconElement) {
            selectIcon(iconElement);
        } else {
            console.log('آیکون پیدا نشد:', iconValue);
            // اگر آیکون پیدا نشد، اولین آیکون رو انتخاب کن
            const firstIcon = document.querySelector('.icon-option');
            if (firstIcon) selectIcon(firstIcon);
        }
    }

    // پیدا کردن و انتخاب رنگ بر اساس مقدار دیتابیس
    function selectColorByValue(colorValue) {
        const colorElement = document.querySelector(`.color-option[data-color="${colorValue}"]`);
        if (colorElement) {
            selectColor(colorElement);
        } else {
            console.log('رنگ پیدا نشد:', colorValue);
            // اگر رنگ پیدا نشد، اولین رنگ رو انتخاب کن
            const firstColor = document.querySelector('.color-option');
            if (firstColor) selectColor(firstColor);
        }
    }

    // مدیریت انتخاب آیکون‌ها
    document.querySelectorAll('.icon-option').forEach(option => {
        option.addEventListener('click', function() {
            selectIcon(this);
        });
    });

    // مدیریت انتخاب رنگ‌ها
    document.querySelectorAll('.color-option').forEach(option => {
        option.addEventListener('click', function() {
            selectColor(this);
        });
    });

    // مدیریت نام دسته‌بندی
    const nameInput = document.querySelector('input[name="name"]');
    if (nameInput) {
        document.getElementById('previewName').textContent = nameInput.value.trim() || 'نام دسته‌بندی';
        nameInput.addEventListener('input', function() {
            document.getElementById('previewName').textContent = this.value.trim() || 'نام دسته‌بندی';
        });
    }

    // تنظیم اولیه انتخاب‌ها بر اساس دیتابیس
    selectIconByValue(currentIcon);
    selectColorByValue(currentColor);
});