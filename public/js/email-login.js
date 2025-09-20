document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const alertBox = document.querySelector('.alert-box');
    
    emailInput.addEventListener('blur', function() {
        if (this.value && !this.value.includes('@')) {
            alertBox.classList.remove('hidden');
            setTimeout(() => {
                alertBox.classList.add('hidden');
            }, 3000);
        }
    });
    
    const card = document.querySelector('.card');
    card.addEventListener('mouseenter', function() {
        this.style.boxShadow = '0 15px 40px rgba(0, 0, 0, 0.12)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.08)';
    });
});