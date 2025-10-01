document.querySelectorAll('.icon-option').forEach(option => {
    option.addEventListener('click', function() {
        document.querySelectorAll('.icon-option').forEach(opt => {
            opt.classList.remove('border-blue-500', 'bg-blue-50');
            opt.classList.add('border-gray-200');
        });
        
        this.classList.remove('border-gray-200');
        this.classList.add('border-blue-500', 'bg-blue-50');
        
        document.getElementById('selectedIcon').value = this.getAttribute('data-icon');
        
        document.getElementById('previewIcon').className = this.getAttribute('data-icon') + ' text-blue-600 text-2xl ml-3';
    });
});

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