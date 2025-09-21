document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('smsForm');
    const alertBox = document.getElementById('alertBox');
    const phoneInput = document.getElementById('phone');
    
    // تنظیمات اولیه برای نمایش صحیح لیبل
    const phoneLabel = document.querySelector('.input-label');
    
    // بررسی مقدار اولیه در صورت وجود
    if (phoneInput.value !== '') {
        phoneLabel.style.top = '0';
        phoneLabel.style.fontSize = '14px';
        phoneLabel.style.color = '#3b82f6';
        phoneLabel.style.background = 'linear-gradient(to bottom, white 50%, #f8fafc 50%)';
        phoneLabel.style.zIndex = '3';
    }
    
    // افزودن رویداد برای تغییرات آینده
    phoneInput.addEventListener('input', function() {
        if (this.value !== '') {
            phoneLabel.style.top = '0';
            phoneLabel.style.fontSize = '14px';
            phoneLabel.style.color = '#3b82f6';
            phoneLabel.style.background = 'linear-gradient(to bottom, white 50%, #f8fafc 50%)';
            phoneLabel.style.zIndex = '3';
        } else {
            phoneLabel.style.top = '50%';
            phoneLabel.style.fontSize = '16px';
            phoneLabel.style.color = '#64748b';
            phoneLabel.style.background = 'transparent';
            phoneLabel.style.zIndex = '1';
        }
    });
    
    // اعتبارسنجی هنگام ارسال فرم
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // اعتبارسنجی شماره تلفن (شروع با 09 و 11 رقمی)
        const phoneRegex = /^09\d{9}$/;
        
        if (!phoneRegex.test(phoneInput.value)) {
            // نمایش خطا
            alertBox.classList.remove('hidden');
            

            // جلوگیری از ارسال فرم
            return false;
        }
        
        this.submit();
    });
    
    const card = document.querySelector('.card');
    card.addEventListener('mouseenter', function() {
        this.style.boxShadow = '0 15px 40px rgba(0, 0, 0, 0.12)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.08)';
    });
});