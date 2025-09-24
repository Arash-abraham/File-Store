document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('smsForm');
    const alertBox = document.getElementById('alertBox');
    const phoneInput = document.getElementById('phone');
    
    const phoneLabel = document.querySelector('.input-label');
    
    if (phoneInput.value !== '') {
        phoneLabel.style.top = '0';
        phoneLabel.style.fontSize = '14px';
        phoneLabel.style.color = '#3b82f6';
        phoneLabel.style.background = 'linear-gradient(to bottom, white 50%, #f8fafc 50%)';
        phoneLabel.style.zIndex = '3';
    }
    
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
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const phoneRegex = /^09\d{9}$/;
        
        if (!phoneRegex.test(phoneInput.value)) {
            alertBox.classList.remove('hidden');
            
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