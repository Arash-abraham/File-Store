// Authentication JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const loginTab = document.getElementById('loginTab');
    const registerTab = document.getElementById('registerTab');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const loginFormElement = document.getElementById('loginFormElement');
    const registerFormElement = document.getElementById('registerFormElement');
    const loadingOverlay = document.getElementById('loadingOverlay');

    // Tab switching functionality
    function switchToLogin() {
        loginTab.classList.add('bg-blue-600', 'text-white');
        loginTab.classList.remove('text-gray-600', 'bg-gray-100');
        registerTab.classList.remove('bg-blue-600', 'text-white');
        registerTab.classList.add('text-gray-600', 'bg-gray-100', 'hover:bg-gray-200');
        
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
    }

    function switchToRegister() {
        registerTab.classList.add('bg-blue-600', 'text-white');
        registerTab.classList.remove('text-gray-600', 'bg-gray-100', 'hover:bg-gray-200');
        loginTab.classList.remove('bg-blue-600', 'text-white');
        loginTab.classList.add('text-gray-600', 'bg-gray-100');
        
        registerForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
    }

    // Event listeners for tabs
    loginTab.addEventListener('click', switchToLogin);
    registerTab.addEventListener('click', switchToRegister);

    // Password toggle functionality
    window.togglePassword = function(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.nextElementSibling.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    };

    // Password strength checker
    function checkPasswordStrength(password) {
        let score = 0;
        let feedback = 'ضعیف';
        let color = 'bg-red-500';
        
        if (password.length >= 8) score += 25;
        if (/[a-z]/.test(password)) score += 25;
        if (/[A-Z]/.test(password)) score += 25;
        if (/[0-9]/.test(password)) score += 25;
        
        if (score >= 75) {
            feedback = 'قوی';
            color = 'bg-green-500';
        } else if (score >= 50) {
            feedback = 'متوسط';
            color = 'bg-yellow-500';
        }
        
        return { score, feedback, color };
    }

    // Password strength indicator
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

    // Form validation
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function validatePhone(phone) {
        const re = /^09\d{9}$/;
        return re.test(phone);
    }

    function validatePassword(password) {
        return password.length >= 8 && /[a-zA-Z]/.test(password) && /[0-9]/.test(password);
    }

    // Show message
    function showMessage(message, type = 'info') {
        const messageContainer = document.getElementById('messageContainer');
        const messageEl = document.createElement('div');
        messageEl.className = `px-6 py-4 rounded-lg text-white font-semibold mb-4 transition-all ${
            type === 'success' ? 'bg-green-600' : 
            type === 'error' ? 'bg-red-600' : 'bg-blue-600'
        }`;
        messageEl.textContent = message;
        
        messageContainer.appendChild(messageEl);
        
        setTimeout(() => {
            messageEl.remove();
        }, 5000);
    }

    // Show loading
    function showLoading() {
        loadingOverlay.classList.remove('hidden');
    }

    // Hide loading
    function hideLoading() {
        loadingOverlay.classList.add('hidden');
    }

    // Login form submission
    // loginFormElement.addEventListener('submit', function(e) {
    //     e.preventDefault();
        
    //     const email = document.getElementById('loginEmail').value;
    //     const password = document.getElementById('loginPassword').value;
        
    //     // Validation
    //     if (!validateEmail(email)) {
    //         showMessage('لطفاً ایمیل معتبر وارد کنید', 'error');
    //         return;
    //     }
        
    //     if (!password) {
    //         showMessage('لطفاً رمز عبور را وارد کنید', 'error');
    //         return;
    //     }
        
    //     // Show loading
    //     showLoading();
        
    //     // Simulate API call
    //     setTimeout(() => {
    //         hideLoading();
            
    //         // Simulate successful login
    //         const userData = {
    //             name: 'احمد محمدی',
    //             email: email,
    //             loginTime: new Date().toISOString()
    //         };
            
    //         localStorage.setItem('user', JSON.stringify(userData));
    //         localStorage.setItem('isLoggedIn', 'true');
            
    //         showMessage('با موفقیت وارد شدید', 'success');
            
    //         setTimeout(() => {
    //             window.location.href = 'dashboard.html';
    //         }, 1500);
            
    //     }, 2000);
    // });

    // Register form submission
    registerFormElement.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const firstName = document.getElementById('firstName').value;
        const lastName = document.getElementById('lastName').value;
        const email = document.getElementById('registerEmail').value;
        const phone = document.getElementById('phone').value;
        const password = document.getElementById('registerPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const agreeTerms = document.querySelector('input[type="checkbox"][required]').checked;
        
        // Validation
        if (!firstName || !lastName) {
            showMessage('لطفاً نام و نام خانوادگی را وارد کنید', 'error');
            return;
        }
        
        if (!validateEmail(email)) {
            showMessage('لطفاً ایمیل معتبر وارد کنید', 'error');
            return;
        }
        
        if (!validatePhone(phone)) {
            showMessage('لطفاً شماره تماس معتبر وارد کنید (09123456789)', 'error');
            return;
        }
        
        if (!validatePassword(password)) {
            showMessage('رمز عبور باید حداقل 8 کاراکتر و شامل حروف و اعداد باشد', 'error');
            return;
        }
        
        if (password !== confirmPassword) {
            showMessage('رمز عبور و تکرار آن مطابقت ندارند', 'error');
            return;
        }
        
        if (!agreeTerms) {
            showMessage('لطفاً شرایط و قوانین را بپذیرید', 'error');
            return;
        }
        
        // Show loading
        showLoading();
        
        // Simulate API call
        setTimeout(() => {
            hideLoading();
            
            // Simulate successful registration
            const userData = {
                name: `${firstName} ${lastName}`,
                email: email,
                phone: phone,
                registerTime: new Date().toISOString()
            };
            
            localStorage.setItem('user', JSON.stringify(userData));
            localStorage.setItem('isLoggedIn', 'true');
            
            showMessage('حساب کاربری با موفقیت ایجاد شد', 'success');
            
            setTimeout(() => {
                window.location.href = 'dashboard.html';
            }, 1500);
            
        }, 2000);
    });

    // Real-time validation feedback
    const inputs = document.querySelectorAll('input[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.type === 'email' && this.value && !validateEmail(this.value)) {
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300');
            } else if (this.type === 'tel' && this.value && !validatePhone(this.value)) {
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

    // Password match validation
    const confirmPasswordInput = document.getElementById('confirmPassword');
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            const password = document.getElementById('registerPassword').value;
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

    // Social login placeholders
    document.querySelectorAll('button[type="button"]').forEach(button => {
        if (button.textContent.includes('Google') || button.textContent.includes('SMS')) {
            button.addEventListener('click', function() {
                showMessage('این قابلیت به زودی فعال خواهد شد', 'info');
            });
        }
    });

    // // Check if user is already logged in
    // if (localStorage.getItem('isLoggedIn') === 'true') {
    //     showMessage('شما قبلاً وارد شده‌اید', 'info');
    //     setTimeout(() => {
    //         window.location.href = 'dashboard.html';
    //     }, 2000);
    // }

    // Handle forgot password
    document.querySelector('a[href="#"]').addEventListener('click', function(e) {
        e.preventDefault();
        
        const email = document.getElementById('loginEmail').value;
        if (!email) {
            showMessage('لطفاً ابتدا ایمیل خود را وارد کنید', 'error');
            document.getElementById('loginEmail').focus();
            return;
        }
        
        if (!validateEmail(email)) {
            showMessage('لطفاً ایمیل معتبر وارد کنید', 'error');
            return;
        }
        
        showLoading();
        
        setTimeout(() => {
            hideLoading();
            showMessage('لینک بازیابی رمز عبور به ایمیل شما ارسال شد', 'success');
        }, 1500);
    });
});