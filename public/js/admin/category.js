// توابع رو بیرون از DOMContentLoaded تعریف کن
function selectIcon(iconElement) {
    console.log('آیکون کلیک شد:', iconElement.getAttribute('data-icon'));
    
    document.querySelectorAll('.icon-option').forEach(opt => {
        opt.classList.remove('border-blue-500', 'bg-blue-50');
        opt.classList.add('border-gray-200');
        
        const icon = opt.querySelector('i');
        icon.classList.remove('text-blue-600');
        icon.classList.add('text-gray-600');
    });
    
    iconElement.classList.remove('border-gray-200');
    iconElement.classList.add('border-blue-500', 'bg-blue-50');
    
    const currentIcon = iconElement.querySelector('i');
    currentIcon.classList.remove('text-gray-600');
    currentIcon.classList.add('text-blue-600');
    
    document.getElementById('selectedIcon').value = iconElement.getAttribute('data-icon');
    
    const currentColor = document.getElementById('selectedColor').value;
    document.getElementById('previewIcon').className = iconElement.getAttribute('data-icon') + ` text-${currentColor}-600 text-2xl ml-3`;
}

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

function selectIconByValue(iconValue) {
    const iconElement = document.querySelector(`.icon-option[data-icon="${iconValue}"]`);
    if (iconElement) {
        selectIcon(iconElement);
    } else {
        console.log('آیکون پیدا نشد:', iconValue);
        const firstIcon = document.querySelector('.icon-option');
        if (firstIcon) selectIcon(firstIcon);
    }
}

function selectColorByValue(colorValue) {
    const colorElement = document.querySelector(`.color-option[data-color="${colorValue}"]`);
    if (colorElement) {
        selectColor(colorElement);
    } else {
        console.log('رنگ پیدا نشد:', colorValue);
        const firstColor = document.querySelector('.color-option');
        if (firstColor) selectColor(firstColor);
    }
}

function closeErrorAlert() {
    document.getElementById('errorAlert').style.display = 'none';
}

function closeSuccessAlert() {
    document.getElementById('successAlert').style.display = 'none';
}

// حالا داخل DOMContentLoaded فقط event listenerها رو اضافه کن
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addCategoryForm');
    const currentIcon = form.getAttribute('data-current-icon');
    const currentColor = form.getAttribute('data-current-color');
    
    console.log('آیکون از دیتابیس:', currentIcon);
    console.log('رنگ از دیتابیس:', currentColor);

    document.querySelectorAll('.icon-option').forEach(option => {
        option.addEventListener('click', function() {
            selectIcon(this);
        });
    });

    document.querySelectorAll('.color-option').forEach(option => {
        option.addEventListener('click', function() {
            selectColor(this);
        });
    });

    const nameInput = document.querySelector('input[name="name"]');
    if (nameInput) {
        document.getElementById('previewName').textContent = nameInput.value.trim() || 'نام دسته‌بندی';
        nameInput.addEventListener('input', function() {
            document.getElementById('previewName').textContent = this.value.trim() || 'نام دسته‌بندی';
        });
    }

    selectIconByValue(currentIcon);
    selectColorByValue(currentColor);
});