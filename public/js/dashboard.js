// Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Check if user is logged in
    if (localStorage.getItem('isLoggedIn') !== 'true') {
        window.location.href = 'auth.html';
        return;
    }

    // Load user data
    const userData = JSON.parse(localStorage.getItem('user') || '{}');
    
    // Sidebar navigation
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    const contentSections = document.querySelectorAll('.content-section');

    function showSection(sectionId) {
        // Hide all sections
        contentSections.forEach(section => {
            section.classList.add('hidden');
        });
        
        // Remove active class from all sidebar items
        sidebarItems.forEach(item => {
            item.classList.remove('active', 'bg-blue-100', 'text-blue-600');
            item.classList.add('text-gray-600', 'hover:bg-gray-100');
        });
        
        // Show selected section
        const targetSection = document.getElementById(sectionId);
        if (targetSection) {
            targetSection.classList.remove('hidden');
        }
        
        // Add active class to clicked sidebar item
        const activeItem = document.querySelector(`[data-section="${sectionId}"]`);
        if (activeItem) {
            activeItem.classList.add('active', 'bg-blue-100', 'text-blue-600');
            activeItem.classList.remove('text-gray-600', 'hover:bg-gray-100');
        }
    }

    // Sidebar click handlers
    sidebarItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const sectionId = this.getAttribute('data-section');
            if (sectionId) {
                showSection(sectionId);
                
                // Update URL hash
                window.location.hash = sectionId;
            }
        });
    });

    // Handle URL hash on load
    const hash = window.location.hash.substring(1);
    if (hash && document.getElementById(hash)) {
        showSection(hash);
    } else {
        showSection('dashboard');
    }

    // Handle browser back/forward
    window.addEventListener('hashchange', function() {
        const hash = window.location.hash.substring(1);
        if (hash && document.getElementById(hash)) {
            showSection(hash);
        } else {
            showSection('dashboard');
        }
    });

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

    // New ticket modal
    window.showNewTicketModal = function() {
        document.getElementById('newTicketModal').classList.remove('hidden');
    };

    window.hideNewTicketModal = function() {
        document.getElementById('newTicketModal').classList.add('hidden');
    };

    // Close modal on backdrop click
    document.getElementById('newTicketModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideNewTicketModal();
        }
    });


    // Add new ticket to list
    function addNewTicketToList(subject, category, message) {
        const ticketsList = document.querySelector('#tickets .space-y-4');
        const newTicketId = Date.now();
        const currentDate = new Date().toLocaleDateString('fa-IR');
        const currentTime = new Date().toLocaleTimeString('fa-IR', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        
        ticketsList.insertAdjacentHTML('afterbegin', ticketHTML);
    }

    // Handle profile form submission
    const profileForm = document.querySelector('#profile form');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Simulate API call
            showNotification('اطلاعات پروفایل با موفقیت بروزرسانی شد', 'success');
            
            // Update localStorage with new data
            const updatedUser = {
                ...userData,
                name: `${this.querySelector('input[type="text"]').value} ${this.querySelectorAll('input[type="text"]')[1].value}`,
                email: this.querySelector('input[type="email"]').value,
                phone: this.querySelector('input[type="tel"]').value
            };
            
            localStorage.setItem('user', JSON.stringify(updatedUser));
        });
    }


    // Handle wallet charge button
    const chargeWalletBtn = document.querySelector('.bg-green-600');
    if (chargeWalletBtn) {
        chargeWalletBtn.addEventListener('click', function() {
            // Simulate wallet charge process
            const amount = prompt('مبلغ شارژ را وارد کنید (تومان):');
            if (amount && parseInt(amount) > 0) {
                showNotification(`درخواست شارژ ${parseInt(amount).toLocaleString('fa-IR')} تومان ثبت شد`, 'success');
            }
        });
    }

    // Download functionality for files
    const downloadButtons = document.querySelectorAll('.bg-blue-600[onclick*="دانلود"], button:contains("دانلود فایل‌ها")');
    downloadButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Check download limits
            const remainingDownloads = Math.floor(Math.random() * 3) + 1;
            
            if (remainingDownloads > 0) {
                showNotification('دانلود شروع شد...', 'success');
                
                // Simulate download progress
                let progress = 0;
                const interval = setInterval(() => {
                    progress += Math.random() * 20;
                    if (progress >= 100) {
                        clearInterval(interval);
                        showNotification('فایل با موفقیت دانلود شد', 'success');
                    }
                }, 500);
            } else {
                showNotification('حد مجاز دانلود به پایان رسیده است', 'error');
            }
        });
    });

    // Show notification function
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 left-1/2 transform -translate-x-1/2 z-50 px-6 py-3 rounded-lg text-white font-semibold transition-all ${
            type === 'success' ? 'bg-green-600' : 
            type === 'error' ? 'bg-red-600' : 'bg-blue-600'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 4000);
    }

    // Statistics animation
    function animateNumbers() {
        const numberElements = document.querySelectorAll('.text-2xl.font-bold');
        
        numberElements.forEach(el => {
            const target = parseInt(el.textContent.replace(/,/g, ''));
            if (!isNaN(target)) {
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    el.textContent = Math.floor(current).toLocaleString('fa-IR');
                }, 30);
            }
        });
    }

    // Animate numbers when dashboard is visible
    const dashboardSection = document.getElementById('dashboard');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateNumbers();
                observer.unobserve(entry.target);
            }
        });
    });
    
    observer.observe(dashboardSection);

    // Handle logout
    const logoutLink = document.querySelector('a[href="auth.html"]');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('آیا مطمئن هستید که می‌خواهید خروج کنید؟')) {
                localStorage.removeItem('user');
                localStorage.removeItem('isLoggedIn');
                showNotification('با موفقیت خارج شدید', 'success');
                
                setTimeout(() => {
                    window.location.href = 'auth.html';
                }, 1500);
            }
        });
    }

    // Auto-save form data
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                const formId = form.closest('.content-section').id;
                const formData = {};
                
                inputs.forEach(inp => {
                    if (inp.name && inp.value) {
                        formData[inp.name] = inp.value;
                    }
                });
                
                localStorage.setItem(`form-${formId}`, JSON.stringify(formData));
            });
        });
    });

    // Load saved form data
    forms.forEach(form => {
        const formId = form.closest('.content-section').id;
        const savedData = localStorage.getItem(`form-${formId}`);
        
        if (savedData) {
            const data = JSON.parse(savedData);
            Object.keys(data).forEach(key => {
                const input = form.querySelector(`[name="${key}"]`);
                if (input && !input.value) {
                    input.value = data[key];
                }
            });
        }
    });

    // Real-time search in orders and downloads
    const searchInputs = document.querySelectorAll('input[type="search"], input[placeholder*="جستجو"]');
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const section = this.closest('.content-section');
            const items = section.querySelectorAll('.border.border-gray-200');
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(query)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Initialize tooltips for buttons
    const buttons = document.querySelectorAll('button[title]');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            // Tooltip implementation would go here
        });
    });

    console.log('Dashboard initialized successfully');
});