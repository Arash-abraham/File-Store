console.log('Script loaded');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded triggered');
    const loginTab = document.getElementById('loginTab');
    const registerTab = document.getElementById('registerTab');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const loginFormElement = document.getElementById('loginFormElement');
    const registerFormElement = document.getElementById('registerFormElement');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const messageContainer = document.getElementById('messageContainer');

    if (!loginTab || !registerTab || !loginForm || !registerForm) {
        console.error('One or more elements not found:', { loginTab, registerTab, loginForm, registerForm });
        return;
    }

    // Tab switching functionality
    function switchToLogin() {
        loginTab.classList.add('bg-blue-600', 'text-white');
        loginTab.classList.remove('text-gray-600', 'bg-gray-100');
        registerTab.classList.remove('bg-blue-600', 'text-white');
        registerTab.classList.add('text-gray-600', 'bg-gray-100', 'hover:bg-gray-200');
        
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
        localStorage.setItem('activeTab', 'login');
        
        messageContainer.innerHTML = '';
        if (window.serverErrors && window.activeTab === 'login') {
            showServerErrors();
        }
    }

    function switchToRegister() {
        registerTab.classList.add('bg-blue-600', 'text-white');
        registerTab.classList.remove('text-gray-600', 'bg-gray-100', 'hover:bg-gray-200');
        loginTab.classList.remove('bg-blue-600', 'text-white');
        loginTab.classList.add('text-gray-600', 'bg-gray-100');
        
        registerForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
        localStorage.setItem('activeTab', 'register');
        
        messageContainer.innerHTML = '';
        if (window.serverErrors && window.activeTab === 'register') {
            showServerErrors();
        }
    }

    function showServerErrors() {
        if (window.serverErrors && window.serverErrors.length > 0) {
            window.serverErrors.forEach(error => {
                const messageEl = document.createElement('div');
                messageEl.className = 'px-6 py-4 rounded-lg text-white font-semibold mb-4 transition-all bg-red-600';
                messageEl.textContent = error;
                messageContainer.appendChild(messageEl);
                
                setTimeout(() => messageEl.remove(), 5000);
            });
        }
    }

    if (window.activeTab === 'register' || localStorage.getItem('activeTab') === 'register') {
        switchToRegister();
    } else {
        switchToLogin();
    }

    if (window.serverErrors && window.serverErrors.length > 0) {
        showServerErrors();
    }

    loginTab.addEventListener('click', switchToLogin);
    registerTab.addEventListener('click', switchToRegister);

    window.togglePassword = function(inputId) {
        const input = document.getElementById(inputId);
        const button = input?.parentElement?.querySelector('button');
        const icon = button?.querySelector('i');
        
        if (input && icon) {
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        } else {
            console.error('Toggle password failed:', { input, button, icon });
        }
    };

    const registerPassword = document.getElementById('registerPassword');
    if (registerPassword) {
        registerPassword.addEventListener('input', function() {
            const strength = checkPasswordStrength(this.value);
            const strengthBar = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('passwordStrengthText');
            
            if (strengthBar && strengthText) {
                strengthBar.style.width = strength.score + '%';
                strengthBar.className = `h-1 rounded-full transition-all ${strength.color}`;
                strengthText.textContent = strength.feedback;
            }
        });
    }

    function checkPasswordStrength(password) {
        let score = 0, feedback = 'ضعیف', color = 'bg-red-500';
        if (password.length >= 8) score += 25;
        if (/[a-z]/.test(password)) score += 25;
        if (/[A-Z]/.test(password)) score += 25;
        if (/[0-9]/.test(password)) score += 25;
        if (score >= 75) { feedback = 'قوی'; color = 'bg-green-500'; }
        else if (score >= 50) { feedback = 'متوسط'; color = 'bg-yellow-500'; }
        return { score, feedback, color };
    }

    function validateEmail(email) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email); }
    function validatePassword(password) { return password.length >= 8 && /[a-zA-Z]/.test(password) && /[0-9]/.test(password); }

    function showMessage(message, type = 'info') {
        if (messageContainer) {
            const messageEl = document.createElement('div');
            messageEl.className = `px-6 py-4 rounded-lg text-white font-semibold mb-4 transition-all ${
                type === 'success' ? 'bg-green-600' : type === 'error' ? 'bg-red-600' : 'bg-blue-600'
            }`;
            messageEl.textContent = message;
            messageContainer.appendChild(messageEl);
            setTimeout(() => messageEl.remove(), 5000);
        }
    }

    function showLoading() { if (loadingOverlay) loadingOverlay.classList.remove('hidden'); }
    function hideLoading() { if (loadingOverlay) loadingOverlay.classList.add('hidden'); }

    if (registerFormElement) {
        registerFormElement.addEventListener('submit', function(e) {
            const name = document.getElementById('name')?.value;
            const email = document.getElementById('registerEmail')?.value;
            const password = document.getElementById('registerPassword')?.value;
            const confirmPassword = document.getElementById('password_confirmation')?.value;
            const agreeTerms = document.querySelector('input[type="checkbox"][required]')?.checked;

            if (!name) { showMessage('لطفاً نام را وارد کنید', 'error'); e.preventDefault(); return; }
            if (!validateEmail(email)) { showMessage('لطفاً ایمیل معتبر وارد کنید', 'error'); e.preventDefault(); return; }
            if (!validatePassword(password)) { showMessage('رمز عبور باید حداقل 8 کاراکتر و شامل حروف و اعداد باشد', 'error'); e.preventDefault(); return; }
            if (password !== confirmPassword) { showMessage('رمز عبور و تکرار آن مطابقت ندارند', 'error'); e.preventDefault(); return; }
            if (!agreeTerms) { showMessage('لطفاً شرایط و قوانین را بپذیرید', 'error'); e.preventDefault(); return; }
            showLoading();
        });
    }

    const inputs = document.querySelectorAll('input[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.type === 'email' && this.value && !validateEmail(this.value)) {
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300');
            } else if (this.value) {
                this.classList.remove('border-red-500');
                this.classList.add('border-green-500');
            } else {
                this.classList.remove('border-red-500', 'border-green-500');
                this.classList.add('border-gray-300');
            }
        });
    });

    const confirmPasswordInput = document.getElementById('password_confirmation');
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            const password = document.getElementById('registerPassword')?.value;
            if (this.value && password !== this.value) {
                this.classList.add('border-red-500');
                this.classList.remove('border-green-500');
            } else if (this.value && password === this.value) {
                this.classList.remove('border-red-500');
                this.classList.add('border-green-500');
            } else {
                this.classList.remove('border-red-500', 'border-green-500');
                this.classList.add('border-gray-300');
            }
        });
    }

    document.querySelectorAll('button[type="button"]').forEach(button => {
        if (button.textContent.includes('Google') || button.textContent.includes('SMS')) {
            button.addEventListener('click', function() {
                showMessage('این قابلیت به زودی فعال خواهد شد', 'info');
            });
        }
    });
});