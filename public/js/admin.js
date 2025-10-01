// Admin Panel JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // Persian Date Conversion Functions
    const PersianDate = {
        // Convert Gregorian to Persian
        toJalaali(gy, gm, gd) {
            var g_d_m, jy, j_d_m, gy2, days;
            if (gy <= 1600) {
                jy = 0;
                gy -= 621;
            } else {
                jy = 979;
                gy -= 1600;
            }
            if (gm > 2) {
                gy2 = gy + 1;
                days = 365 * gy + parseInt((gy2 + 3) / 4) - parseInt((gy2 + 99) / 100) + parseInt((gy2 + 399) / 400) - 80 + gd + [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334][gm - 1];
            } else {
                gy2 = gy;
                days = 365 * gy + parseInt((gy2 + 3) / 4) - parseInt((gy2 + 99) / 100) + parseInt((gy2 + 399) / 400) - 80 + gd + [0, 31, 59][gm - 1];
            }
            jy += 33 * parseInt(days / 12053);
            days %= 12053;
            jy += 4 * parseInt(days / 1461);
            days %= 1461;
            if (days > 365) {
                jy += parseInt((days - 1) / 365);
                days = (days - 1) % 365;
            }
            var jm, jd;
            if (days < 186) {
                jm = 1 + parseInt(days / 31);
                jd = 1 + (days % 31);
            } else {
                jm = 7 + parseInt((days - 186) / 30);
                jd = 1 + ((days - 186) % 30);
            }
            return [jy, jm, jd];
        },

        // Convert Persian to Gregorian
        toGregorian(jy, jm, jd) {
            var gy, gm, gd, days, gy2;
            if (jy <= 979) {
                gy = 1600;
                jy -= 979;
            } else {
                gy = 621;
                jy -= 979;
            }
            if (jm < 7) {
                days = (jm - 1) * 31;
            } else {
                days = (jm - 7) * 30 + 186;
            }
            days += (365 * jy) + (parseInt(jy / 33) * 8) + (parseInt(((jy % 33) + 3) / 4)) + 78 + jd;
            if (jy <= 979) {
                gy2 = gy + 1;
            } else {
                gy2 = gy;
            }
            var leap_adj = parseInt((gy2 + 3) / 4) - parseInt((gy2 + 99) / 100) + parseInt((gy2 + 399) / 400) - parseInt((gy + 3) / 4) + parseInt((gy + 99) / 100) - parseInt((gy + 399) / 400);
            if (days > leap_adj) {
                days -= leap_adj;
            }
            gy += 400 * parseInt(days / 146097);
            days %= 146097;
            var leap = true;
            if (days >= 36525) {
                days--;
                gy += 100 * parseInt(days / 36524);
                days %= 36524;
                if (days >= 365) days++;
            }
            gy += 4 * parseInt(days / 1461);
            days %= 1461;
            if (days >= 366) {
                leap = false;
                days--;
                gy += parseInt(days / 365);
                days = days % 365;
            }
            gd = days + 1;
            if ((leap && (gd > 79)) || (!leap && (gd > 78))) {
                var sal_a = [0, 31, ((leap) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                gm = 0;
                gd += (leap) ? -79 : -78;
                while (gm < 13 && gd > sal_a[gm]) gd -= sal_a[gm++];
                if (gm > 12) {
                    gm = 1;
                    gd = 1;
                    gy++;
                }
            } else {
                var sal_a = [0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                gm = 10;
                gd += 10;
                while (gm > 0 && gd <= sal_a[gm]) {
                    gd += sal_a[gm--];
                }
                if (gm == 0) {
                    gm = 12;
                    gy--;
                }
            }
            return [gy, gm, gd];
        },

        // Format Persian date as string
        formatPersian(jy, jm, jd) {
            return jy + '/' + (jm < 10 ? '0' + jm : jm) + '/' + (jd < 10 ? '0' + jd : jd);
        },

        // Parse Persian date string
        parsePersian(dateStr) {
            if (!dateStr || !dateStr.match(/^\d{4}\/\d{1,2}\/\d{1,2}$/)) {
                return null;
            }
            const parts = dateStr.split('/');
            return [parseInt(parts[0]), parseInt(parts[1]), parseInt(parts[2])];
        },

        // Convert Gregorian Date object to Persian string
        gregorianToPersianString(date) {
            if (!date) return '';
            const [jy, jm, jd] = this.toJalaali(date.getFullYear(), date.getMonth() + 1, date.getDate());
            return this.formatPersian(jy, jm, jd);
        },

        // Convert Persian string to Gregorian Date object
        persianStringToGregorian(persianStr) {
            const parsed = this.parsePersian(persianStr);
            if (!parsed) return null;
            const [gy, gm, gd] = this.toGregorian(parsed[0], parsed[1], parsed[2]);
            return new Date(gy, gm - 1, gd);
        },

        // Validate Persian date
        isValidPersian(jy, jm, jd) {
            if (jy < 1200 || jy > 1500) return false;
            if (jm < 1 || jm > 12) return false;
            if (jd < 1) return false;
            
            // Days in each Persian month
            const daysInMonth = jm <= 6 ? 31 : (jm <= 11 ? 30 : 29);
            
            // Check for leap year (simplified)
            if (jm === 12) {
                const isLeap = ((jy - 979) % 33 % 4) === 1;
                if (isLeap && jd > 30) return false;
                if (!isLeap && jd > 29) return false;
            } else if (jd > daysInMonth) {
                return false;
            }
            
            return true;
        }
    };

    // Check if user is admin
    if (localStorage.getItem('isAdmin') !== 'true') {
        // For demo purposes, automatically set admin status
        localStorage.setItem('isAdmin', 'true');
        localStorage.setItem('adminUser', JSON.stringify({
            name: 'سید امیر محمدی',
            role: 'مدیر کل',
            loginTime: new Date().toISOString()
        }));
    }

    // Sample data for admin panel
    const sampleProducts = [
        {
            id: 1,
            name: 'Adobe Photoshop 2024',
            type: 'نرم‌افزار',
            price: 3000000,
            discountPrice: 2500000,
            status: 'فعال',
            saleStatus: 'در دسترس',
            image: 'https://images.pexels.com/photos/4348401/pexels-photo-4348401.jpeg?auto=compress&cs=tinysrgb&w=100',
            category: 'software'
        },
        {
            id: 2,
            name: 'دوره کامل React JS',
            type: 'دوره آموزشی',
            price: 600000,
            discountPrice: 450000,
            status: 'فعال',
            saleStatus: 'در دسترس',
            image: 'https://images.pexels.com/photos/11035380/pexels-photo-11035380.jpeg?auto=compress&cs=tinysrgb&w=100',
            category: 'courses'
        }
    ];

    const sampleFiles = [
        {
            id: 1,
            name: 'Adobe_Photoshop_2024_Setup.zip',
            productId: 1,
            productName: 'Adobe Photoshop 2024',
            path: '/files/products/photoshop2024/',
            size: '2.8 GB',
            type: 'zip'
        },
        {
            id: 2,
            name: 'License_Key.txt',
            productId: 1,
            productName: 'Adobe Photoshop 2024',
            path: '/files/products/photoshop2024/',
            size: '1 KB',
            type: 'txt'
        }
    ];

    // Sample categories data
    const sampleCategories = [
        {
            id: 1,
            name: 'نرم‌افزارها',
            slug: 'software',
            icon: 'fas fa-laptop-code',
            color: 'blue',
            description: 'انواع نرم‌افزارهای کاربردی و حرفه‌ای',
            productCount: 0
        },
        {
            id: 2,
            name: 'دوره‌های آموزشی',
            slug: 'courses',
            icon: 'fas fa-play-circle',
            color: 'green',
            description: 'آموزش‌های تخصصی و دوره‌های مهارتی',
            productCount: 0
        },
        {
            id: 3,
            name: 'کتاب‌های الکترونیکی',
            slug: 'ebooks',
            icon: 'fas fa-book',
            color: 'purple',
            description: 'کتاب‌های PDF و EPUB',
            productCount: 0
        },
        {
            id: 4,
            name: 'قالب‌ها',
            slug: 'templates',
            icon: 'fas fa-palette',
            color: 'orange',
            description: 'قالب‌های وب و گرافیک',
            productCount: 0
        }
    ];

    // Sample tags data
    const sampleTags = [
        {
            id: 1,
            name: 'Adobe',
            slug: 'adobe',
            color: 'blue',
            description: 'محصولات شرکت ادوبی',
            productCount: 0
        },
        {
            id: 2,
            name: 'طراحی گرافیک',
            slug: 'graphic-design',
            color: 'green',
            description: 'ابزارها و منابع طراحی گرافیک',
            productCount: 0
        },
        {
            id: 3,
            name: 'برنامه‌نویسی',
            slug: 'programming',
            color: 'purple',
            description: 'زبان‌ها و ابزارهای برنامه‌نویسی',
            productCount: 0
        },
        {
            id: 4,
            name: 'ویرایش ویدیو',
            slug: 'video-editing',
            color: 'orange',
            description: 'نرم‌افزارهای تدوین ویدیو',
            productCount: 0
        },
        {
            id: 5,
            name: 'طراحی وب',
            slug: 'web-design',
            color: 'indigo',
            description: 'ابزارهای طراحی وب سایت',
            productCount: 0
        }
    ];

    const sampleCoupons = [
        {
            id: 1,
            code: 'SALE20',
            type: 'percentage',
            value: 20,
            maxDiscount: 500000,
            minOrder: 1000000,
            usageLimit: 100,
            usageCount: 45,
            startDate: '2024-01-01',
            endDate: '2024-12-30',
            status: 'active',
            description: 'تخفیف 20 درصدی برای خرید بالای یک میلیون تومان',
            createdAt: '2024-01-01T00:00:00.000Z'
        },
        {
            id: 2,
            code: 'WELCOME100K',
            type: 'fixed',
            value: 100000,
            maxDiscount: null,
            minOrder: 500000,
            usageLimit: 50,
            usageCount: 12,
            startDate: '2024-01-01',
            endDate: '2024-06-30',
            status: 'active',
            description: 'تخفیف 100 هزار تومانی ویژه کاربران جدید',
            createdAt: '2024-01-01T00:00:00.000Z'
        },
        {
            id: 3,
            code: 'SPRING30',
            type: 'percentage',
            value: 30,
            maxDiscount: 1000000,
            minOrder: 2000000,
            usageLimit: 200,
            usageCount: 180,
            startDate: '2024-03-01',
            endDate: '2024-05-31',
            status: 'inactive',
            description: 'تخفیف بهاری 30 درصدی',
            createdAt: '2024-03-01T00:00:00.000Z'
        }
    ];

    // Sample payments data
    const samplePayments = [
        {
            id: 'PAY-1403-001',
            userId: 1,
            userName: 'احمد محمدی',
            userEmail: 'ahmad@example.com',
            amount: 2500000,
            gateway: 'zarinpal',
            gatewayName: 'زرین‌پال',
            status: 'completed',
            transactionId: 'TXN123456789',
            productTitle: 'دوره آموزش React.js',
            date: '1403/07/15',
            time: '14:30',
            createdAt: '2024-10-06T11:30:00.000Z'
        },
        {
            id: 'PAY-1403-002',
            userId: 2,
            userName: 'فاطمه احمدی',
            userEmail: 'fateme@example.com',
            amount: 1200000,
            gateway: 'mellat',
            gatewayName: 'ملت',
            status: 'failed',
            transactionId: 'TXN987654321',
            productTitle: 'کتاب الکترونیکی جاوا اسکریپت',
            date: '1403/07/14',
            time: '09:15',
            createdAt: '2024-10-05T06:15:00.000Z'
        },
        {
            id: 'PAY-1403-003',
            userId: 3,
            userName: 'علی رضایی',
            userEmail: 'ali@example.com',
            amount: 850000,
            gateway: 'parsian',
            gatewayName: 'پارسیان',
            status: 'pending',
            transactionId: 'TXN456789123',
            productTitle: 'قالب وردپرس مدرن',
            date: '1403/07/16',
            time: '16:45',
            createdAt: '2024-10-07T13:45:00.000Z'
        }
    ];

    // Sample support tickets data
    const sampleTickets = [
        {
            id: 1,
            ticketNumber: 'TKT-1001',
            userId: 1,
            userName: 'احمد محمدی',
            userEmail: 'ahmad@example.com',
            subject: 'مشکل در دانلود فایل',
            category: 'technical',
            categoryName: 'فنی',
            priority: 'high',
            priorityName: 'بالا',
            status: 'open',
            statusName: 'باز',
            message: 'سلام، من خریداری کردم ولی نمی‌تونم فایل رو دانلود کنم. لطفاً کمک کنید.',
            response: null,
            assignedTo: null,
            createdAt: '2024-10-07T10:30:00.000Z',
            updatedAt: '2024-10-07T10:30:00.000Z',
            date: '1403/07/16',
            time: '14:00'
        },
        {
            id: 2,
            ticketNumber: 'TKT-1002',
            userId: 2,
            userName: 'مریم حسینی',
            userEmail: 'maryam@example.com',
            subject: 'درخواست بازگشت وجه',
            category: 'financial',
            categoryName: 'مالی',
            priority: 'medium',
            priorityName: 'متوسط',
            status: 'in_progress',
            statusName: 'در حال بررسی',
            message: 'محصولی که خریدم با توضیحات مطابقت نداره. میخوام وجه رو پس بگیرم.',
            response: 'تیکت شما در حال بررسی است و ظرف 24 ساعت پاسخ خواهید گرفت.',
            assignedTo: 'تیم پشتیبانی',
            createdAt: '2024-10-06T14:20:00.000Z',
            updatedAt: '2024-10-06T16:45:00.000Z',
            date: '1403/07/15',
            time: '17:50'
        },
        {
            id: 3,
            ticketNumber: 'TKT-1003',
            userId: 3,
            userName: 'رضا کریمی',
            userEmail: 'reza@example.com',
            subject: 'سوال درباره قیمت‌گذاری',
            category: 'general',
            categoryName: 'عمومی',
            priority: 'low',
            priorityName: 'پایین',
            status: 'closed',
            statusName: 'بسته شده',
            message: 'آیا تخفیف ویژه‌ای برای خرید عمده وجود دارد؟',
            response: 'بله، برای خرید بالای 5 میلیون تومان 15% تخفیف داریم. با شماره 021-12345678 تماس بگیرید.',
            assignedTo: 'مشاور فروش',
            createdAt: '2024-10-05T08:15:00.000Z',
            updatedAt: '2024-10-05T11:30:00.000Z',
            date: '1403/07/14',
            time: '11:45'
        }
    ];

    // Sidebar navigation
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    const contentSections = document.querySelectorAll('.content-section');

    function showSection(sectionId) {
        sidebarItems.forEach(item => {
            item.classList.remove('active', 'bg-blue-100', 'text-blue-600');
            item.classList.add('text-gray-600', 'hover:bg-gray-100');
        });
                
        // // Add active class to clicked sidebar item
        // const activeItem = document.querySelector(`[data-section="${sectionId}"]`);
        // if (activeItem) {
        //     activeItem.classList.add('active', 'bg-blue-100', 'text-blue-600');
        //     activeItem.classList.remove('text-gray-600', 'hover:bg-gray-100');
        // }

        // Load section specific data
        loadSectionData(sectionId);
    }

    

    // Handle URL hash on load
    const hash = window.location.hash.substring(1);
    if (hash && document.getElementById(hash)) {
        showSection(hash);
    } else {
        showSection('dashboard');
    }

    // Load section specific data
    function loadSectionData(sectionId) {
        switch(sectionId) {
            case 'dashboard':
                initializeDashboard();
                break;
            case 'products':
                loadProducts();
                break;
            case 'product-files':
                loadProductFiles();
                break;
            case 'categories':
                loadCategories();
                break;
            case 'tags':
                loadTags();
                break;
            case 'coupons':
                loadCoupons();
                break;
            case 'menus':
                loadStoredMenus();
                loadMenus();
                break;
            case 'payments':
                loadStoredPayments();
                loadPayments();
                break;
            case 'tickets':
                loadStoredTickets();
                loadTickets();
                break;
            case 'faq':
                loadStoredFaqs();
                loadFaqs();
                break;
        }
    }

    // Initialize dashboard with charts
    function initializeDashboard() {
        // Monthly Sales Chart
        const monthlySalesCtx = document.getElementById('monthlySalesChart');
        if (monthlySalesCtx) {
            new Chart(monthlySalesCtx, {
                type: 'line',
                data: {
                    labels: ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور'],
                    datasets: [{
                        label: 'فروش ماهانه',
                        data: [12000000, 15000000, 8000000, 20000000, 18000000, 25000000],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('fa-IR') + ' تومان';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Top Products Chart
        const topProductsCtx = document.getElementById('topProductsChart');
        if (topProductsCtx) {
            new Chart(topProductsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['نرم‌افزارها', 'دوره‌ها', 'کتاب‌ها', 'قالب‌ها'],
                    datasets: [{
                        data: [45, 25, 20, 10],
                        backgroundColor: [
                            'rgb(59, 130, 246)',
                            'rgb(16, 185, 129)',
                            'rgb(245, 158, 11)',
                            'rgb(239, 68, 68)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Animate statistics numbers
        animateNumbers();
    }

    // Animate numbers
    function animateNumbers() {
        const numberElements = document.querySelectorAll('.text-2xl.font-bold');
        
        numberElements.forEach(el => {
            const text = el.textContent.replace(/[,۰-۹]/g, '');
            const target = parseInt(text) || 0;
            
            if (target > 0) {
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

    // Load products
    function loadProducts() {
        const tbody = document.getElementById('productsTableBody');
        if (!tbody) return;

        tbody.innerHTML = '';
        sampleProducts.forEach(product => {
            const row = document.createElement('tr');
            row.className = 'border-b hover:bg-gray-50';
            row.innerHTML = `
                <td class="p-4">
                    <img src="${product.image}" class="w-16 h-16 rounded-lg object-cover">
                </td>
                <td class="p-4 font-semibold">${product.name}</td>
                <td class="p-4">${product.type}</td>
                <td class="p-4">${product.price.toLocaleString('fa-IR')} تومان</td>
                <td class="p-4">${product.discountPrice.toLocaleString('fa-IR')} تومان</td>
                <td class="p-4">
                    <span class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">${product.status}</span>
                </td>
                <td class="p-4">
                    <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">${product.saleStatus}</span>
                </td>
                <td class="p-4">
                    <div class="flex gap-2">
                        <button class="text-blue-600 hover:text-blue-800" onclick="editProduct(${product.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-800" onclick="deleteProduct(${product.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    // Load categories
    function loadCategories() {
        const categoriesGrid = document.getElementById('categoriesGrid');
        if (!categoriesGrid) return;

        // Update product counts
        sampleCategories.forEach(category => {
            category.productCount = sampleProducts.filter(p => p.category === category.slug).length;
        });

        categoriesGrid.innerHTML = '';
        sampleCategories.forEach(category => {
            const categoryCard = document.createElement('div');
            categoryCard.className = 'border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow';
            categoryCard.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <i class="${category.icon} text-${category.color}-600 text-2xl ml-3"></i>
                        <div>
                            <h3 class="font-semibold">${category.name}</h3>
                            <p class="text-sm text-gray-500">${category.productCount} محصول</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="text-blue-600 hover:text-blue-800" onclick="editCategory(${category.id})" title="ویرایش">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-800" onclick="deleteCategory(${category.id})" title="حذف">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <p class="text-gray-600 text-sm">${category.description}</p>
                <div class="mt-3">
                    <span class="inline-block bg-${category.color}-100 text-${category.color}-800 text-xs px-2 py-1 rounded-full">
                        ${category.slug}
                    </span>
                </div>
            `;
            categoriesGrid.appendChild(categoryCard);
        });
    }

    // Load tags
    function loadTags() {
        const tagsTableBody = document.getElementById('tagsTableBody');
        if (!tagsTableBody) return;

        // Update product counts - count tags in product tags array
        sampleTags.forEach(tag => {
            tag.productCount = sampleProducts.filter(product => {
                if (product.tags) {
                    const productTags = product.tags.toLowerCase().split(',').map(t => t.trim());
                    return productTags.includes(tag.slug.toLowerCase()) || productTags.includes(tag.name.toLowerCase());
                }
                return false;
            }).length;
        });

        tagsTableBody.innerHTML = '';
        sampleTags.forEach(tag => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <span class="bg-${tag.color}-100 text-${tag.color}-800 px-3 py-1 rounded-full text-sm font-medium">
                            ${tag.name}
                        </span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <code class="bg-gray-100 px-2 py-1 rounded text-xs">${tag.slug}</code>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="w-6 h-6 rounded-full bg-${tag.color}-500 border-2 border-gray-300"></div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${tag.productCount} محصول
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex gap-2">
                        <button class="text-blue-600 hover:text-blue-800" onclick="editTag(${tag.id})" title="ویرایش">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-800" onclick="deleteTag(${tag.id})" title="حذف">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            tagsTableBody.appendChild(row);
        });
    }

    // Load product files
    function loadProductFiles() {
        // Sample implementation for product files
        console.log('Loading product files...');
    }

    // Load coupons
    function loadCoupons() {
        const couponsTableBody = document.getElementById('couponsTableBody');
        if (!couponsTableBody) return;

        couponsTableBody.innerHTML = '';
        sampleCoupons.forEach(coupon => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            
            // Format dates to Persian
            const startDate = coupon.startDate ? formatPersianDateForDisplay(coupon.startDate) : '-';
            const endDate = coupon.endDate ? formatPersianDateForDisplay(coupon.endDate) : '-';
            
            // Format discount
            const discountText = coupon.type === 'percentage' ? `${coupon.value}%` : `${coupon.value.toLocaleString()} تومان`;
            
            // Format usage
            const usageText = coupon.usageLimit === 0 ? `${coupon.usageCount}/نامحدود` : `${coupon.usageCount}/${coupon.usageLimit}`;
            
            // Status
            const statusClass = coupon.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
            const statusText = coupon.status === 'active' ? 'فعال' : 'غیرفعال';
            
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <code class="bg-gray-100 text-blue-600 px-3 py-1 rounded text-sm font-mono font-bold">${coupon.code}</code>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${coupon.type === 'percentage' ? 'درصدی' : 'مقدار ثابت'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                    ${discountText}
                    ${coupon.maxDiscount && coupon.type === 'percentage' ? `<br><span class="text-xs text-gray-500">حداکثر: ${coupon.maxDiscount.toLocaleString()} تومان</span>` : ''}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${coupon.minOrder ? coupon.minOrder.toLocaleString() + ' تومان' : 'بدون حداقل'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${endDate}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${usageText}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${statusClass}">
                        ${statusText}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex gap-2">
                        <button class="text-blue-600 hover:text-blue-800" onclick="editCoupon(${coupon.id})" title="ویرایش">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-800" onclick="deleteCoupon(${coupon.id})" title="حذف">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            couponsTableBody.appendChild(row);
        });
    }

    // Modal functions
    window.showAddProductModal = function() {
        document.getElementById('addProductModal').classList.remove('hidden');
    };

    window.hideAddProductModal = function() {
        document.getElementById('addProductModal').classList.add('hidden');
    };

    window.showAddFileModal = function() {
        document.getElementById('addFileModal').classList.remove('hidden');
    };

    window.hideAddFileModal = function() {
        document.getElementById('addFileModal').classList.add('hidden');
    };

    window.showAddCategoryModal = function() {
        document.getElementById('addCategoryModal').classList.remove('hidden');
        setupCategoryModal();
    };

    window.hideAddCategoryModal = function() {
        document.getElementById('addCategoryModal').classList.add('hidden');
        resetCategoryForm();
    };

    window.showAddTagModal = function() {
        document.getElementById('addTagModal').classList.remove('hidden');
        setupTagModal();
    };

    window.hideAddTagModal = function() {
        document.getElementById('addTagModal').classList.add('hidden');
        resetTagForm();
    };

    window.showAddCouponModal = function() {
        document.getElementById('addCouponModal').classList.remove('hidden');
        setupCouponModal();
    };

    window.hideAddCouponModal = function() {
        document.getElementById('addCouponModal').classList.add('hidden');
        resetCouponForm();
    };


    // showAddMenuModal moved to menus section

    // Product management functions
    window.editProduct = function(productId) {
        showNotification(`ویرایش محصول شماره ${productId}`, 'info');
    };

    window.deleteProduct = function(productId) {
        if (confirm('آیا از حذف این محصول مطمئن هستید؟')) {
            showNotification(`محصول شماره ${productId} حذف شد`, 'success');
        }
    };

    // File management functions
    window.editFile = function(fileId) {
        showNotification(`ویرایش فایل شماره ${fileId}`, 'info');
    };

    window.deleteFile = function(fileId) {
        if (confirm('آیا از حذف این فایل مطمئن هستید؟')) {
            showNotification(`فایل شماره ${fileId} حذف شد`, 'success');
        }
    };

    // Search functionality for products
    const productSearch = document.getElementById('productSearch');
    if (productSearch) {
        productSearch.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#productsTableBody tr');
            
            rows.forEach(row => {
                const productName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                if (productName.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Auto calculate discount percentage
    const originalPriceInput = document.getElementById('originalPriceInput');
    const discountPriceInput = document.getElementById('discountPriceInput');
    const discountPercentInput = document.getElementById('discountPercent');

    function calculateDiscount() {
        const originalPrice = parseFloat(originalPriceInput.value) || 0;
        const discountPrice = parseFloat(discountPriceInput.value) || 0;
        
        if (originalPrice > 0 && discountPrice > 0 && discountPrice < originalPrice) {
            const discountPercent = Math.round(((originalPrice - discountPrice) / originalPrice) * 100);
            discountPercentInput.value = discountPercent;
        } else {
            discountPercentInput.value = '';
        }
    }

    if (originalPriceInput) originalPriceInput.addEventListener('input', calculateDiscount);
    if (discountPriceInput) discountPriceInput.addEventListener('input', calculateDiscount);

    // Form validation
    function validateProductForm(formData) {
        const errors = [];
        
        if (!formData.get('title')) {
            errors.push('عنوان محصول الزامی است');
        }
        
        if (!formData.get('category')) {
            errors.push('انتخاب دسته‌بندی الزامی است');
        }
        
        if (!formData.get('originalPrice') || parseFloat(formData.get('originalPrice')) <= 0) {
            errors.push('قیمت اصلی باید بزرگتر از صفر باشد');
        }
        
        const originalPrice = parseFloat(formData.get('originalPrice'));
        const discountPrice = parseFloat(formData.get('price'));
        
        if (discountPrice && discountPrice >= originalPrice) {
            errors.push('قیمت با تخفیف باید کمتر از قیمت اصلی باشد');
        }
        
        return errors;
    }

    // Form submissions
    const addProductForm = document.querySelector('#addProductModal form');
    if (addProductForm) {
        addProductForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const errors = validateProductForm(formData);
            
            if (errors.length > 0) {
                showNotification(errors.join('. '), 'error');
                return;
            }
            
            // Create new product object
            const newProduct = {
                id: Date.now(), // Simple ID generation
                title: formData.get('title'),
                price: parseInt(formData.get('price')) || parseInt(formData.get('originalPrice')),
                originalPrice: parseInt(formData.get('originalPrice')),
                discount: parseInt(formData.get('discount')) || 0,
                image: formData.get('image') || 'https://images.pexels.com/photos/196644/pexels-photo-196644.jpeg?auto=compress&cs=tinysrgb&w=400',
                rating: parseFloat(formData.get('rating')),
                reviews: parseInt(formData.get('reviews')) || 0,
                category: formData.get('category'),
                isAvailable: formData.get('availability') === 'true',
                tags: formData.get('tags') ? formData.get('tags').split(',').map(tag => tag.trim()) : [],
                description: formData.get('description') || '',
                status: formData.get('status')
            };
            
            // Add to sample products array
            sampleProducts.push(newProduct);
            
            // Save to localStorage
            localStorage.setItem('adminProducts', JSON.stringify(sampleProducts));
            
            // Show success message
            showNotification('محصول جدید با موفقیت اضافه شد', 'success');
            
            // Reset form and hide modal
            this.reset();
            discountPercentInput.value = '';
            hideAddProductModal();
            
            // Reload products table
            loadProducts();
            
            // Update dashboard stats
            updateDashboardStats();
        });
    }

    const addFileForm = document.querySelector('#addFileModal form');
    if (addFileForm) {
        // Populate product options
        const productSelect = addFileForm.querySelector('select[name="productId"]');
        if (productSelect) {
            function updateProductOptions() {
                productSelect.innerHTML = '<option value="">انتخاب محصول</option>';
                sampleProducts.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = product.title;
                    productSelect.appendChild(option);
                });
            }
            updateProductOptions();
        }

        addFileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const fileName = formData.get('fileName');
            const productId = formData.get('productId');
            const filePath = formData.get('filePath');
            const fileSize = formData.get('fileSize');
            const fileType = formData.get('fileType');
            
            // Validation
            if (!fileName || !productId || !filePath || !fileSize || !fileType) {
                showNotification('لطفاً تمام فیلدها را پر کنید', 'error');
                return;
            }
            
            // Find product name
            const product = sampleProducts.find(p => p.id == productId);
            const productName = product ? product.title : 'محصول نامشخص';
            
            // Create new file object
            const newFile = {
                id: Date.now(),
                name: fileName,
                productId: parseInt(productId),
                productName: productName,
                path: filePath,
                size: fileSize,
                type: fileType
            };
            
            // Add to sample files array
            sampleFiles.push(newFile);
            
            // Save to localStorage
            localStorage.setItem('adminFiles', JSON.stringify(sampleFiles));
            
            showNotification('فایل جدید با موفقیت اضافه شد', 'success');
            
            // Reset form and hide modal
            this.reset();
            hideAddFileModal();
            
            // Reload files if in files section
            if (!document.getElementById('product-files').classList.contains('hidden')) {
                loadProductFiles();
            }
        });
    }

    // Settings form
    const settingsForm = document.querySelector('#settings form');
    if (settingsForm) {
        settingsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            showNotification('تنظیمات با موفقیت ذخیره شد', 'success');
        });
    }

    // Close modals on backdrop click
    document.querySelectorAll('.fixed.inset-0').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    });

    // Update dashboard statistics
    function updateDashboardStats() {
        // Update product count
        const productCountEl = document.querySelector('.bg-blue-50 .text-2xl.font-bold');
        if (productCountEl) {
            productCountEl.textContent = sampleProducts.length.toLocaleString('fa-IR');
        }
        
        // Update revenue (simulation)
        const revenueEl = document.querySelector('.bg-orange-50 .text-2xl.font-bold');
        if (revenueEl) {
            const totalRevenue = sampleProducts.reduce((sum, product) => sum + product.price, 0);
            revenueEl.textContent = (totalRevenue / 1000).toLocaleString('fa-IR');
        }
        
        // Update recent activity
        const activityContainer = document.querySelector('#dashboard .space-y-4');
        if (activityContainer && sampleProducts.length > 0) {
            const latestProduct = sampleProducts[sampleProducts.length - 1];
            const activityHTML = `
                <div class="flex items-center p-4 bg-green-50 rounded-lg">
                    <i class="fas fa-plus text-green-600 text-lg ml-4"></i>
                    <div class="flex-1">
                        <p class="font-semibold">محصول جدید اضافه شد: ${latestProduct.title}</p>
                        <p class="text-sm text-gray-500">همین الان</p>
                    </div>
                </div>
            `;
            activityContainer.insertAdjacentHTML('afterbegin', activityHTML);
        }
    }

    // Load products from localStorage on startup
    function loadStoredProducts() {
        const storedProducts = localStorage.getItem('adminProducts');
        if (storedProducts) {
            try {
                const parsedProducts = JSON.parse(storedProducts);
                // Merge with sample data
                sampleProducts.push(...parsedProducts.filter(p => !sampleProducts.find(sp => sp.id === p.id)));
            } catch (error) {
                console.error('Error loading stored products:', error);
            }
        }
    }

    // Load categories from localStorage on startup
    function loadStoredCategories() {
        const storedCategories = localStorage.getItem('adminCategories');
        if (storedCategories) {
            try {
                const parsedCategories = JSON.parse(storedCategories);
                // Replace sample data with stored data
                sampleCategories.splice(0, sampleCategories.length);
                sampleCategories.push(...parsedCategories);
            } catch (error) {
                console.error('Error loading stored categories:', error);
            }
        }
    }

    // Show notification function
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 left-1/2 transform -translate-x-1/2 z-50 px-6 py-3 rounded-lg text-white font-semibold transition-all transform scale-100 ${
            type === 'success' ? 'bg-green-600' : 
            type === 'error' ? 'bg-red-600' : 'bg-blue-600'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Animation
        setTimeout(() => {
            notification.style.transform = 'translateX(-50%) translateY(-10px)';
        }, 100);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(-50%) translateY(-20px) scale(0)';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 4000);
    }

    // Export to CSV functionality for reports
    function exportToCSV(data, filename) {
        const csvContent = data.map(row => 
            row.map(field => `"${field}"`).join(',')
        ).join('\n');
        
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        
        if (link.download !== undefined) {
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', filename);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

    // Add export buttons functionality
    document.querySelectorAll('.export-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const section = this.dataset.section;
            exportData(section);
        });
    });

    function exportData(section) {
        switch(section) {
            case 'products':
                const productData = [
                    ['نام محصول', 'نوع', 'قیمت', 'وضعیت'],
                    ...sampleProducts.map(p => [p.name, p.type, p.price, p.status])
                ];
                exportToCSV(productData, 'products.csv');
                break;
            default:
                showNotification('عملکرد صادرات برای این بخش پیاده‌سازی نشده', 'info');
        }
    }


    // Start real-time updates
    simulateRealTimeUpdates();

    // Bulk actions functionality
    function initBulkActions() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const bulkActionsDiv = document.getElementById('bulkActions');

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                itemCheckboxes.forEach(cb => cb.checked = this.checked);
                toggleBulkActions();
            });
        }

        itemCheckboxes.forEach(cb => {
            cb.addEventListener('change', toggleBulkActions);
        });

        function toggleBulkActions() {
            const checkedItems = document.querySelectorAll('.item-checkbox:checked');
            if (bulkActionsDiv) {
                bulkActionsDiv.style.display = checkedItems.length > 0 ? 'block' : 'none';
            }
        }
    }

    // Initialize bulk actions
    initBulkActions();

    // Advanced search functionality
    function initAdvancedSearch() {
        const advancedSearchBtn = document.getElementById('advancedSearchBtn');
        const advancedSearchPanel = document.getElementById('advancedSearchPanel');

        if (advancedSearchBtn) {
            advancedSearchBtn.addEventListener('click', function() {
                advancedSearchPanel.classList.toggle('hidden');
            });
        }
    }

    // Initialize advanced search
    initAdvancedSearch();

    // Auto-save functionality for forms
    function initAutoSave() {
        const forms = document.querySelectorAll('form[data-autosave]');
        
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    const formId = form.dataset.autosave;
                    const formData = new FormData(form);
                    const data = {};
                    
                    for (let [key, value] of formData.entries()) {
                        data[key] = value;
                    }
                    
                    localStorage.setItem(`autosave_${formId}`, JSON.stringify(data));
                    
                    // Show auto-save indicator
                    showAutoSaveIndicator();
                });
            });
        });
    }

    function showAutoSaveIndicator() {
        let indicator = document.getElementById('autoSaveIndicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.id = 'autoSaveIndicator';
            indicator.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg text-sm';
            indicator.textContent = 'ذخیره خودکار انجام شد';
            document.body.appendChild(indicator);
        }
        
        indicator.style.display = 'block';
        setTimeout(() => {
            indicator.style.display = 'none';
        }, 2000);
    }

    // Initialize auto-save
    initAutoSave();

    // Load stored data from localStorage
    loadStoredProducts();
    loadStoredCategories();
    
    // Update dropdowns after loading data
    updateProductCategoryDropdowns();
    
    // Load stored tags from localStorage
    loadStoredTags();
    
    // Initialize Persian date picker
    initializePersianDatePicker();


    function updateProductCategoryDropdowns() {
        // Update category dropdown in add product modal
        const categorySelect = document.querySelector('#addProductModal select[name="category"]');
        if (categorySelect) {
            const currentValue = categorySelect.value;
            categorySelect.innerHTML = '<option value="">انتخاب دسته‌بندی</option>';
            
            sampleCategories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.slug;
                option.textContent = category.name;
                if (category.slug === currentValue) {
                    option.selected = true;
                }
                categorySelect.appendChild(option);
            });
        }
    }
    
    // Edit category modal functions
    window.hideEditCategoryModal = function() {
        document.getElementById('editCategoryModal').classList.add('hidden');
    };
    
    function setupEditCategoryModal() {
        const form = document.getElementById('editCategoryForm');
        const nameInput = form.querySelector('input[name="name"]');
        const slugInput = form.querySelector('input[name="slug"]');
        const descriptionInput = form.querySelector('textarea[name="description"]');
        
        // Remove existing event listeners
        nameInput.removeEventListener('input', updateEditCategoryPreview);
        slugInput.removeEventListener('input', updateEditCategoryPreview);
        descriptionInput.removeEventListener('input', updateEditCategoryPreview);
        
        // Add fresh event listeners
        nameInput.addEventListener('input', updateEditCategoryPreview);
        slugInput.addEventListener('input', updateEditCategoryPreview);
        descriptionInput.addEventListener('input', updateEditCategoryPreview);
        
        // Icon selection for edit modal
        document.querySelectorAll('.edit-icon-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove active class from all
                document.querySelectorAll('.edit-icon-option').forEach(opt => {
                    opt.classList.remove('border-blue-500', 'bg-blue-50');
                    opt.classList.add('border-gray-200');
                    opt.querySelector('i').classList.remove('text-blue-600');
                    opt.querySelector('i').classList.add('text-gray-600');
                });
                
                // Add active class to clicked
                this.classList.add('border-blue-500', 'bg-blue-50');
                this.classList.remove('border-gray-200');
                this.querySelector('i').classList.add('text-blue-600');
                this.querySelector('i').classList.remove('text-gray-600');
                
                // Update hidden input
                document.getElementById('editSelectedIcon').value = this.dataset.icon;
                updateEditCategoryPreview();
            });
        });
        
        // Color selection for edit modal
        document.querySelectorAll('.edit-color-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove active class from all
                document.querySelectorAll('.edit-color-option').forEach(opt => {
                    opt.classList.remove('border-4');
                    opt.classList.add('border-2', 'border-gray-300');
                });
                
                // Add active class to clicked
                const color = this.dataset.color;
                this.classList.add('border-4');
                this.classList.remove('border-2', 'border-gray-300');
                this.classList.add(`border-${color}-200`);
                
                // Update hidden input
                document.getElementById('editSelectedColor').value = color;
                updateEditCategoryPreview();
            });
        });
        
        // Form submission - remove existing listener first
        form.removeEventListener('submit', handleEditCategorySubmission);
        form.addEventListener('submit', handleEditCategorySubmission);
    }
    
    function updateEditCategoryPreview() {
        const name = document.getElementById('editCategoryName').value || 'نام دسته‌بندی';
        const description = document.getElementById('editCategoryDescription').value || 'توضیحات دسته‌بندی...';
        const icon = document.getElementById('editSelectedIcon').value;
        const color = document.getElementById('editSelectedColor').value;
        
        document.getElementById('editPreviewName').textContent = name;
        document.getElementById('editPreviewDescription').textContent = description;
        document.getElementById('editPreviewIcon').className = `${icon} text-${color}-600 text-2xl ml-3`;
    }
    
    function handleEditCategorySubmission(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const categoryId = parseInt(formData.get('categoryId'));
        const name = formData.get('name');
        const slug = formData.get('slug');
        const description = formData.get('description');
        const icon = formData.get('icon');
        const color = formData.get('color');
        
        // Validation
        if (!name || !slug) {
            showNotification('نام و شناسه دسته‌بندی الزامی است', 'error');
            return;
        }
        
        // Check for duplicate slug (exclude current category)
        const duplicateCategory = sampleCategories.find(c => c.slug === slug && c.id !== categoryId);
        if (duplicateCategory) {
            showNotification('شناسه دسته‌بندی قبلاً استفاده شده است', 'error');
            return;
        }
        
        // Find and update category
        const categoryIndex = sampleCategories.findIndex(c => c.id === categoryId);
        if (categoryIndex !== -1) {
            const oldSlug = sampleCategories[categoryIndex].slug;
            
            // Update category data
            sampleCategories[categoryIndex] = {
                ...sampleCategories[categoryIndex],
                name: name,
                slug: slug,
                icon: icon,
                color: color,
                description: description || ''
            };
            
            // Update products if slug changed
            if (oldSlug !== slug) {
                sampleProducts.forEach(product => {
                    if (product.category === oldSlug) {
                        product.category = slug;
                    }
                });
                
                // Save updated products to localStorage
                localStorage.setItem('adminProducts', JSON.stringify(sampleProducts.filter(p => p.id > 3)));
            }
            
            // Save to localStorage
            localStorage.setItem('adminCategories', JSON.stringify(sampleCategories));
            
            // Show success message
            showNotification('دسته‌بندی با موفقیت بروزرسانی شد', 'success');
            
            // Hide modal and reload categories
            hideEditCategoryModal();
            loadCategories();
            
            // Update product dropdowns
            updateProductCategoryDropdowns();
        }
    }
    
    // Load tags from localStorage on startup
    function loadStoredTags() {
        const storedTags = localStorage.getItem('adminTags');
        if (storedTags) {
            try {
                const parsedTags = JSON.parse(storedTags);
                // Replace sample data with stored data
                sampleTags.splice(0, sampleTags.length);
                sampleTags.push(...parsedTags);
            } catch (error) {
                console.error('Error loading stored tags:', error);
            }
        }
    }

    // Tag management functions
    function setupTagModal() {
        const form = document.getElementById('addTagForm');
        const nameInput = form.querySelector('input[name="name"]');
        const slugInput = form.querySelector('input[name="slug"]');
        const descriptionInput = form.querySelector('textarea[name="description"]');
        
        // Auto-generate slug from name
        nameInput.addEventListener('input', function() {
            const slug = generateSlug(this.value);
            slugInput.value = slug;
            updateTagPreview();
        });
        
        // Update preview on input changes
        slugInput.addEventListener('input', updateTagPreview);
        descriptionInput.addEventListener('input', updateTagPreview);
        
        // Color selection
        document.querySelectorAll('.tag-color-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove active class from all
                document.querySelectorAll('.tag-color-option').forEach(opt => {
                    opt.classList.remove('border-4');
                    opt.classList.add('border-2', 'border-gray-300');
                });
                
                // Add active class to clicked
                const color = this.dataset.color;
                this.classList.add('border-4');
                this.classList.remove('border-2', 'border-gray-300');
                this.classList.add(`border-${color}-200`);
                
                // Update hidden input
                document.getElementById('selectedTagColor').value = color;
                updateTagPreview();
            });
        });
        
        // Form submission
        form.addEventListener('submit', handleTagSubmission);
    }
    
    function resetTagForm() {
        const form = document.getElementById('addTagForm');
        form.reset();
        
        // Reset color selection
        document.querySelectorAll('.tag-color-option').forEach(opt => {
            opt.classList.remove('border-4');
            opt.classList.add('border-2', 'border-gray-300');
        });
        
        // Set first color as active (blue)
        const firstColor = document.querySelector('.tag-color-option');
        firstColor.classList.add('border-4', 'border-blue-200');
        firstColor.classList.remove('border-2', 'border-gray-300');
        
        // Reset hidden input
        document.getElementById('selectedTagColor').value = 'blue';
        
        updateTagPreview();
    }
    
    function updateTagPreview() {
        const name = document.querySelector('#addTagForm input[name="name"]').value || 'نام برچسب';
        const color = document.getElementById('selectedTagColor').value;
        
        const previewTag = document.getElementById('previewTag');
        previewTag.textContent = name;
        previewTag.className = `bg-${color}-100 text-${color}-800 px-4 py-2 rounded-full text-sm`;
    }
    
    function handleTagSubmission(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const name = formData.get('name');
        const slug = formData.get('slug');
        const description = formData.get('description');
        const color = formData.get('color');
        
        // Validation
        if (!name || !slug) {
            showNotification('نام و شناسه برچسب الزامی است', 'error');
            return;
        }
        
        // Check for duplicate slug
        if (sampleTags.find(t => t.slug === slug)) {
            showNotification('شناسه برچسب قبلاً استفاده شده است', 'error');
            return;
        }
        
        // Create new tag
        const newTag = {
            id: Date.now(),
            name: name,
            slug: slug,
            color: color,
            description: description || '',
            productCount: 0
        };
        
        // Add to tags array
        sampleTags.push(newTag);
        
        // Save to localStorage
        localStorage.setItem('adminTags', JSON.stringify(sampleTags));
        
        // Show success message
        showNotification('برچسب جدید با موفقیت اضافه شد', 'success');
        
        // Hide modal and reload tags
        hideAddTagModal();
        loadTags();
    }

    // Tag management functions
    window.editTag = function(tagId) {
        const tag = sampleTags.find(t => t.id === tagId);
        if (!tag) {
            showNotification('برچسب یافت نشد', 'error');
            return;
        }
        
        // Fill the edit form with current tag data
        document.getElementById('editTagId').value = tag.id;
        document.getElementById('editTagName').value = tag.name;
        document.getElementById('editTagSlug').value = tag.slug;
        document.getElementById('editTagDescription').value = tag.description || '';
        document.getElementById('editSelectedTagColor').value = tag.color;
        document.getElementById('editPreviewCount').textContent = `${tag.productCount} محصول`;
        
        // Set selected color
        document.querySelectorAll('.edit-tag-color-option').forEach(opt => {
            opt.classList.remove('border-4');
            opt.classList.add('border-2', 'border-gray-300');
            
            if (opt.dataset.color === tag.color) {
                opt.classList.add('border-4');
                opt.classList.remove('border-2', 'border-gray-300');
                opt.classList.add(`border-${tag.color}-200`);
            }
        });
        
        // Update preview
        updateEditTagPreview();
        
        // Show modal
        document.getElementById('editTagModal').classList.remove('hidden');
        setupEditTagModal();
    };

    window.deleteTag = function(tagId) {
        if (confirm('آیا از حذف این برچسب مطمئن هستید؟')) {
            const tagIndex = sampleTags.findIndex(t => t.id === tagId);
            if (tagIndex !== -1) {
                const tag = sampleTags[tagIndex];
                
                // Check if tag is used in products
                const tagUsageCount = sampleProducts.filter(product => {
                    if (product.tags) {
                        const productTags = product.tags.toLowerCase().split(',').map(t => t.trim());
                        return productTags.includes(tag.slug.toLowerCase()) || productTags.includes(tag.name.toLowerCase());
                    }
                    return false;
                }).length;
                
                if (tagUsageCount > 0) {
                    if (!confirm(`این برچسب در ${tagUsageCount} محصول استفاده شده است. آیا مطمئن هستید؟`)) {
                        return;
                    }
                }
                
                sampleTags.splice(tagIndex, 1);
                localStorage.setItem('adminTags', JSON.stringify(sampleTags));
                showNotification('برچسب با موفقیت حذف شد', 'success');
                loadTags();
            }
        }
    };
    
    // Edit tag modal functions
    window.hideEditTagModal = function() {
        document.getElementById('editTagModal').classList.add('hidden');
    };
    
    function setupEditTagModal() {
        const form = document.getElementById('editTagForm');
        const nameInput = form.querySelector('input[name="name"]');
        const slugInput = form.querySelector('input[name="slug"]');
        const descriptionInput = form.querySelector('textarea[name="description"]');
        
        // Remove existing event listeners
        nameInput.removeEventListener('input', updateEditTagPreview);
        slugInput.removeEventListener('input', updateEditTagPreview);
        descriptionInput.removeEventListener('input', updateEditTagPreview);
        
        // Add fresh event listeners
        nameInput.addEventListener('input', updateEditTagPreview);
        slugInput.addEventListener('input', updateEditTagPreview);
        descriptionInput.addEventListener('input', updateEditTagPreview);
        
        // Color selection for edit modal
        document.querySelectorAll('.edit-tag-color-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove active class from all
                document.querySelectorAll('.edit-tag-color-option').forEach(opt => {
                    opt.classList.remove('border-4');
                    opt.classList.add('border-2', 'border-gray-300');
                });
                
                // Add active class to clicked
                const color = this.dataset.color;
                this.classList.add('border-4');
                this.classList.remove('border-2', 'border-gray-300');
                this.classList.add(`border-${color}-200`);
                
                // Update hidden input
                document.getElementById('editSelectedTagColor').value = color;
                updateEditTagPreview();
            });
        });
        
        // Form submission - remove existing listener first
        form.removeEventListener('submit', handleEditTagSubmission);
        form.addEventListener('submit', handleEditTagSubmission);
    }
    
    function updateEditTagPreview() {
        const name = document.getElementById('editTagName').value || 'نام برچسب';
        const color = document.getElementById('editSelectedTagColor').value;
        
        const previewTag = document.getElementById('editPreviewTag');
        previewTag.textContent = name;
        previewTag.className = `bg-${color}-100 text-${color}-800 px-4 py-2 rounded-full text-sm`;
    }
    
    function handleEditTagSubmission(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const tagId = parseInt(formData.get('tagId'));
        const name = formData.get('name');
        const slug = formData.get('slug');
        const description = formData.get('description');
        const color = formData.get('color');
        
        // Validation
        if (!name || !slug) {
            showNotification('نام و شناسه برچسب الزامی است', 'error');
            return;
        }
        
        // Check for duplicate slug (exclude current tag)
        const duplicateTag = sampleTags.find(t => t.slug === slug && t.id !== tagId);
        if (duplicateTag) {
            showNotification('شناسه برچسب قبلاً استفاده شده است', 'error');
            return;
        }
        
        // Find and update tag
        const tagIndex = sampleTags.findIndex(t => t.id === tagId);
        if (tagIndex !== -1) {
            const oldSlug = sampleTags[tagIndex].slug;
            const oldName = sampleTags[tagIndex].name;
            
            // Update tag data
            sampleTags[tagIndex] = {
                ...sampleTags[tagIndex],
                name: name,
                slug: slug,
                color: color,
                description: description || ''
            };
            
            // Update products if slug or name changed
            if (oldSlug !== slug || oldName !== name) {
                sampleProducts.forEach(product => {
                    if (product.tags) {
                        let productTags = product.tags.split(',').map(t => t.trim());
                        
                        // Replace old tag name/slug with new ones
                        productTags = productTags.map(tag => {
                            if (tag.toLowerCase() === oldSlug.toLowerCase() || tag.toLowerCase() === oldName.toLowerCase()) {
                                return name;
                            }
                            return tag;
                        });
                        
                        product.tags = productTags.join(', ');
                    }
                });
                
                // Save updated products to localStorage
                localStorage.setItem('adminProducts', JSON.stringify(sampleProducts.filter(p => p.id > 3)));
            }
            
            // Save to localStorage
            localStorage.setItem('adminTags', JSON.stringify(sampleTags));
            
            // Show success message
            showNotification('برچسب با موفقیت بروزرسانی شد', 'success');
            
            // Hide modal and reload tags
            hideEditTagModal();
            loadTags();
        }
    }

    // Load coupons from localStorage on startup
    function loadStoredCoupons() {
        const storedCoupons = localStorage.getItem('adminCoupons');
        if (storedCoupons) {
            try {
                const parsedCoupons = JSON.parse(storedCoupons);
                // Replace sample data with stored data
                sampleCoupons.splice(0, sampleCoupons.length);
                sampleCoupons.push(...parsedCoupons);
            } catch (error) {
                console.error('Error loading stored coupons:', error);
            }
        }
    }

    // Coupon management functions
    function setupCouponModal() {
        const form = document.getElementById('addCouponForm');
        const codeInput = form.querySelector('input[name="code"]');
        const typeSelect = form.querySelector('select[name="type"]');
        const valueInput = form.querySelector('input[name="value"]');
        const maxDiscountInput = form.querySelector('input[name="maxDiscount"]');
        const minOrderInput = form.querySelector('input[name="minOrder"]');
        const startDateInput = document.getElementById('startDatePersian');
        const endDateInput = document.getElementById('endDatePersian');
        
        // Date inputs are now handled by date picker modal
        
        // Auto uppercase code
        codeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            updateCouponPreview();
        });
        
        // Handle discount type change
        typeSelect.addEventListener('change', function() {
            const maxDiscountDiv = document.getElementById('maxDiscountDiv');
            const discountUnit = document.getElementById('discountUnit');
            
            if (this.value === 'percentage') {
                maxDiscountDiv.style.display = 'block';
                discountUnit.textContent = '%';
                valueInput.max = '100';
            } else {
                maxDiscountDiv.style.display = 'none';
                discountUnit.textContent = 'تومان';
                valueInput.removeAttribute('max');
            }
            updateCouponPreview();
        });
        
        // Update preview on input changes
        valueInput.addEventListener('input', updateCouponPreview);
        maxDiscountInput.addEventListener('input', updateCouponPreview);
        minOrderInput.addEventListener('input', updateCouponPreview);
        if (startDateInput) startDateInput.addEventListener('change', updateCouponPreview);
        if (endDateInput) endDateInput.addEventListener('change', updateCouponPreview);
        
        // Form submission
        form.addEventListener('submit', handleCouponSubmission);
        
        // Initialize preview
        updateCouponPreview();
    }
    
    function resetCouponForm() {
        const form = document.getElementById('addCouponForm');
        form.reset();
        
        // Reset discount type display
        const maxDiscountDiv = document.getElementById('maxDiscountDiv');
        const discountUnit = document.getElementById('discountUnit');
        maxDiscountDiv.style.display = 'block';
        discountUnit.textContent = '%';
        
        updateCouponPreview();
    }
    
    function updateCouponPreview() {
        const code = document.querySelector('#addCouponForm input[name="code"]').value || 'SALE20';
        const type = document.querySelector('#addCouponForm select[name="type"]').value;
        const value = document.querySelector('#addCouponForm input[name="value"]').value || '20';
        const maxDiscount = document.querySelector('#addCouponForm input[name="maxDiscount"]').value;
        const minOrder = document.querySelector('#addCouponForm input[name="minOrder"]').value || '0';
        const endDate = document.querySelector('#addCouponForm input[name="endDate"]').value;
        
        document.getElementById('previewCode').textContent = code;
        
        if (type === 'percentage') {
            let discountText = `${value}% تخفیف`;
            if (maxDiscount) {
                discountText += ` (حداکثر ${parseInt(maxDiscount).toLocaleString()} تومان)`;
            }
            document.getElementById('previewDiscount').textContent = discountText;
        } else {
            document.getElementById('previewDiscount').textContent = `${parseInt(value).toLocaleString()} تومان تخفیف`;
        }
        
        document.getElementById('previewDetails').textContent = `حداقل خرید: ${parseInt(minOrder).toLocaleString()} تومان`;
        
        if (endDate) {
            // Convert Persian date to display format
            const persianEndDate = PersianDate.parsePersian(endDate);
            if (persianEndDate) {
                const [jy, jm, jd] = persianEndDate;
                const formattedDate = PersianDate.formatPersian(jy, jm, jd);
                document.getElementById('previewValidity').textContent = `تا ${formattedDate} اعتبار دارد`;
            } else {
                document.getElementById('previewValidity').textContent = 'فرمت تاریخ نامعتبر';
            }
        } else {
            document.getElementById('previewValidity').textContent = 'بدون تاریخ انقضا';
        }
    }
    
    function handleCouponSubmission(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const code = formData.get('code');
        const type = formData.get('type');
        const value = parseInt(formData.get('value'));
        const maxDiscount = formData.get('maxDiscount') ? parseInt(formData.get('maxDiscount')) : null;
        const minOrder = formData.get('minOrder') ? parseInt(formData.get('minOrder')) : 0;
        const usageLimit = formData.get('usageLimit') ? parseInt(formData.get('usageLimit')) : 0;
        
        // Convert Persian dates to Gregorian
        const startDatePersian = formData.get('startDate') || null;
        const endDatePersian = formData.get('endDate') || null;
        
        let startDate = null;
        let endDate = null;
        
        if (startDatePersian) {
            const startDateObj = PersianDate.persianStringToGregorian(startDatePersian);
            if (startDateObj) {
                startDate = startDateObj.toISOString().split('T')[0];
            } else {
                showNotification('فرمت تاریخ شروع نامعتبر است', 'error');
                return;
            }
        }
        
        if (endDatePersian) {
            const endDateObj = PersianDate.persianStringToGregorian(endDatePersian);
            if (endDateObj) {
                endDate = endDateObj.toISOString().split('T')[0];
            } else {
                showNotification('فرمت تاریخ انقضا نامعتبر است', 'error');
                return;
            }
        }
        
        const status = formData.get('status');
        const description = formData.get('description');
        
        // Validation
        if (!code || !type || !value) {
            showNotification('کد، نوع و مقدار تخفیف الزامی است', 'error');
            return;
        }
        
        // Check for duplicate code
        if (sampleCoupons.find(c => c.code === code)) {
            showNotification('این کد تخفیف قبلاً استفاده شده است', 'error');
            return;
        }
        
        // Validate percentage
        if (type === 'percentage' && (value < 1 || value > 100)) {
            showNotification('درصد تخفیف باید بین 1 تا 100 باشد', 'error');
            return;
        }
        
        // Create new coupon
        const newCoupon = {
            id: Date.now(),
            code: code,
            type: type,
            value: value,
            maxDiscount: maxDiscount,
            minOrder: minOrder,
            usageLimit: usageLimit,
            usageCount: 0,
            startDate: startDate,
            endDate: endDate,
            status: status,
            description: description || '',
            createdAt: new Date().toISOString()
        };
        
        // Add to coupons array
        sampleCoupons.push(newCoupon);
        
        // Save to localStorage
        localStorage.setItem('adminCoupons', JSON.stringify(sampleCoupons));
        
        // Show success message
        showNotification('کد تخفیف جدید با موفقیت ایجاد شد', 'success');
        
        // Hide modal and reload coupons
        hideAddCouponModal();
        loadCoupons();
    }

    // Edit coupon functions
    window.editCoupon = function(couponId) {
        console.log('🔍 editCoupon called with ID:', couponId);
        
        const coupon = sampleCoupons.find(c => c.id === couponId);
        if (!coupon) {
            console.log('❌ کوپن پیدا نشد:', couponId);
            showNotification('کد تخفیف یافت نشد', 'error');
            return;
        }
        
        console.log('✅ کوپن پیدا شد:', coupon);
        
        // Check if all required elements exist
        const requiredElements = ['editCouponId', 'editCouponCode', 'editCouponType', 'editDiscountValue', 'editMaxDiscount', 'editMinOrder', 'editUsageLimit', 'editStartDatePersian', 'editEndDatePersian', 'editCouponStatus', 'editCouponDescription'];
        const missingElements = [];
        
        for (const elementId of requiredElements) {
            if (!document.getElementById(elementId)) {
                missingElements.push(elementId);
            }
        }
        
        if (missingElements.length > 0) {
            console.log('❌ عناصر مفقود:', missingElements);
            alert('خطا: برخی عناصر فرم پیدا نشدند: ' + missingElements.join(', '));
            return;
        }
        
        console.log('✅ همه عناصر فرم موجودند');
        
        // Fill the edit form with current coupon data
        document.getElementById('editCouponId').value = coupon.id;
        document.getElementById('editCouponCode').value = coupon.code;
        document.getElementById('editCouponType').value = coupon.type;
        document.getElementById('editDiscountValue').value = coupon.value;
        document.getElementById('editMaxDiscount').value = coupon.maxDiscount || '';
        document.getElementById('editMinOrder').value = coupon.minOrder || '';
        document.getElementById('editUsageLimit').value = coupon.usageLimit || '';
        // Convert Gregorian dates to Persian for display
        const startDatePersian = coupon.startDate ? formatPersianDateForDisplay(coupon.startDate) : '';
        const endDatePersian = coupon.endDate ? formatPersianDateForDisplay(coupon.endDate) : '';
        
        document.getElementById('editStartDatePersian').value = startDatePersian;
        document.getElementById('editEndDatePersian').value = endDatePersian;
        document.getElementById('editCouponStatus').value = coupon.status;
        document.getElementById('editCouponDescription').value = coupon.description || '';
        
        // Update usage stats
        document.getElementById('editUsageCount').textContent = coupon.usageCount;
        const remainingUses = coupon.usageLimit === 0 ? '∞' : (coupon.usageLimit - coupon.usageCount);
        document.getElementById('editRemainingUses').textContent = remainingUses;
        
        // Handle discount type display
        const editMaxDiscountDiv = document.getElementById('editMaxDiscountDiv');
        const editDiscountUnit = document.getElementById('editDiscountUnit');
        
        if (coupon.type === 'percentage') {
            editMaxDiscountDiv.style.display = 'block';
            editDiscountUnit.textContent = '%';
        } else {
            editMaxDiscountDiv.style.display = 'none';
            editDiscountUnit.textContent = 'تومان';
        }
        
        // Update preview
        updateEditCouponPreview();
        
        // Show modal
        const editModal = document.getElementById('editCouponModal');
        if (!editModal) {
            console.log('❌ editCouponModal element پیدا نشد!');
            alert('خطا: Modal ادیت کوپن پیدا نشد!');
            return;
        }
        
        console.log('✅ در حال نمایش editCouponModal...');
        editModal.classList.remove('hidden');
        setupEditCouponModal();
        console.log('✅ editCouponModal نمایش داده شد');
    };

    window.deleteCoupon = function(couponId) {
        if (confirm('آیا از حذف این کد تخفیف مطمئن هستید؟')) {
            const couponIndex = sampleCoupons.findIndex(c => c.id === couponId);
            if (couponIndex !== -1) {
                const coupon = sampleCoupons[couponIndex];
                
                if (coupon.usageCount > 0) {
                    if (!confirm(`این کد تخفیف ${coupon.usageCount} بار استفاده شده است. آیا مطمئن هستید؟`)) {
                        return;
                    }
                }
                
                sampleCoupons.splice(couponIndex, 1);
                localStorage.setItem('adminCoupons', JSON.stringify(sampleCoupons));
                showNotification('کد تخفیف با موفقیت حذف شد', 'success');
                loadCoupons();
            }
        }
    };
    
    // Edit coupon modal functions
    window.hideEditCouponModal = function() {
        document.getElementById('editCouponModal').classList.add('hidden');
    };
    
    function setupEditCouponModal() {
        const form = document.getElementById('editCouponForm');
        const codeInput = form.querySelector('input[name="code"]');
        const typeSelect = form.querySelector('select[name="type"]');
        const valueInput = form.querySelector('input[name="value"]');
        const maxDiscountInput = form.querySelector('input[name="maxDiscount"]');
        const minOrderInput = form.querySelector('input[name="minOrder"]');
        const startDateInput = document.getElementById('editStartDatePersian');
        const endDateInput = document.getElementById('editEndDatePersian');
        
        // Date inputs are now handled by date picker modal
        
        // Remove existing event listeners - only for existing inputs
        
        // Auto uppercase code
        codeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            updateEditCouponPreview();
        });
        
        // Handle discount type change
        typeSelect.addEventListener('change', handleEditTypeChange);
        
        // Update preview on input changes
        valueInput.addEventListener('input', updateEditCouponPreview);
        maxDiscountInput.addEventListener('input', updateEditCouponPreview);
        minOrderInput.addEventListener('input', updateEditCouponPreview);
        if (startDateInput) startDateInput.addEventListener('input', updateEditCouponPreview);
        if (endDateInput) endDateInput.addEventListener('input', updateEditCouponPreview);
        
        // Form submission
        form.removeEventListener('submit', handleEditCouponSubmission);
        form.addEventListener('submit', handleEditCouponSubmission);
    }
    
    function handleEditTypeChange() {
        const editMaxDiscountDiv = document.getElementById('editMaxDiscountDiv');
        const editDiscountUnit = document.getElementById('editDiscountUnit');
        const editDiscountValue = document.getElementById('editDiscountValue');
        
        if (this.value === 'percentage') {
            editMaxDiscountDiv.style.display = 'block';
            editDiscountUnit.textContent = '%';
            editDiscountValue.max = '100';
        } else {
            editMaxDiscountDiv.style.display = 'none';
            editDiscountUnit.textContent = 'تومان';
            editDiscountValue.removeAttribute('max');
        }
        updateEditCouponPreview();
    }
    
    function updateEditCouponPreview() {
        try {
            const code = document.getElementById('editCouponCode').value || 'SALE20';
            const type = document.getElementById('editCouponType').value;
            const value = document.getElementById('editDiscountValue').value || '20';
            const maxDiscount = document.getElementById('editMaxDiscount').value;
            const minOrder = document.getElementById('editMinOrder').value || '0';
            const endDateElement = document.getElementById('editEndDatePersian');
            const endDate = endDateElement ? endDateElement.value : '';
        
        document.getElementById('editPreviewCode').textContent = code;
        
        if (type === 'percentage') {
            let discountText = `${value}% تخفیف`;
            if (maxDiscount) {
                discountText += ` (حداکثر ${parseInt(maxDiscount).toLocaleString()} تومان)`;
            }
            document.getElementById('editPreviewDiscount').textContent = discountText;
        } else {
            document.getElementById('editPreviewDiscount').textContent = `${parseInt(value).toLocaleString()} تومان تخفیف`;
        }
        
        document.getElementById('editPreviewDetails').textContent = `حداقل خرید: ${parseInt(minOrder).toLocaleString()} تومان`;
        
        if (endDate) {
            // Convert Persian date to display format
            const persianEndDate = PersianDate.parsePersian(endDate);
            if (persianEndDate) {
                const [jy, jm, jd] = persianEndDate;
                const formattedDate = PersianDate.formatPersian(jy, jm, jd);
                document.getElementById('editPreviewValidity').textContent = `تا ${formattedDate} اعتبار دارد`;
            } else {
                document.getElementById('editPreviewValidity').textContent = 'فرمت تاریخ نامعتبر';
            }
        } else {
            document.getElementById('editPreviewValidity').textContent = 'بدون تاریخ انقضا';
        }
        
        } catch (error) {
            console.error('خطا در updateEditCouponPreview:', error);
            // Don't break the flow, just log the error
        }
    }
    
    function handleEditCouponSubmission(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const couponId = parseInt(formData.get('couponId'));
        const code = formData.get('code');
        const type = formData.get('type');
        const value = parseInt(formData.get('value'));
        const maxDiscount = formData.get('maxDiscount') ? parseInt(formData.get('maxDiscount')) : null;
        const minOrder = formData.get('minOrder') ? parseInt(formData.get('minOrder')) : 0;
        const usageLimit = formData.get('usageLimit') ? parseInt(formData.get('usageLimit')) : 0;
        
        // Convert Persian dates to Gregorian
        const startDatePersian = formData.get('startDate') || null;
        const endDatePersian = formData.get('endDate') || null;
        
        let startDate = null;
        let endDate = null;
        
        if (startDatePersian) {
            const startDateObj = PersianDate.persianStringToGregorian(startDatePersian);
            if (startDateObj) {
                startDate = startDateObj.toISOString().split('T')[0];
            } else {
                showNotification('فرمت تاریخ شروع نامعتبر است', 'error');
                return;
            }
        }
        
        if (endDatePersian) {
            const endDateObj = PersianDate.persianStringToGregorian(endDatePersian);
            if (endDateObj) {
                endDate = endDateObj.toISOString().split('T')[0];
            } else {
                showNotification('فرمت تاریخ انقضا نامعتبر است', 'error');
                return;
            }
        }
        
        const status = formData.get('status');
        const description = formData.get('description');
        
        // Validation
        if (!code || !type || !value) {
            showNotification('کد، نوع و مقدار تخفیف الزامی است', 'error');
            return;
        }
        
        // Check for duplicate code (exclude current coupon)
        const duplicateCoupon = sampleCoupons.find(c => c.code === code && c.id !== couponId);
        if (duplicateCoupon) {
            showNotification('این کد تخفیف قبلاً استفاده شده است', 'error');
            return;
        }
        
        // Validate percentage
        if (type === 'percentage' && (value < 1 || value > 100)) {
            showNotification('درصد تخفیف باید بین 1 تا 100 باشد', 'error');
            return;
        }
        
        // Find and update coupon
        const couponIndex = sampleCoupons.findIndex(c => c.id === couponId);
        if (couponIndex !== -1) {
            // Update coupon data (preserve usage count)
            sampleCoupons[couponIndex] = {
                ...sampleCoupons[couponIndex],
                code: code,
                type: type,
                value: value,
                maxDiscount: maxDiscount,
                minOrder: minOrder,
                usageLimit: usageLimit,
                startDate: startDate,
                endDate: endDate,
                status: status,
                description: description || '',
                updatedAt: new Date().toISOString()
            };
            
            // Save to localStorage
            localStorage.setItem('adminCoupons', JSON.stringify(sampleCoupons));
            
            // Show success message
            showNotification('کد تخفیف با موفقیت بروزرسانی شد', 'success');
            
            // Hide modal and reload coupons
            hideEditCouponModal();
            loadCoupons();
        }
    }
    
    // Load stored coupons
    loadStoredCoupons();



    function formatPersianDateForDisplay(dateString) {
        if (!dateString) return '-';
        
        try {
            const date = new Date(dateString);
            return PersianDate.gregorianToPersianString(date);
        } catch (e) {
            return dateString;
        }
    }

    // Persian Date Picker Functions
    let currentDatePickerInput = null;
    let currentYear = 1403;
    let currentMonth = 1;
    
    function initializePersianDatePicker() {
        console.log('Initializing Persian Date Picker...');
        
        // Create modal if it doesn't exist
        let modal = document.getElementById('persianDatePicker');
        if (!modal) {
            console.log('Creating Persian Date Picker Modal...');
            createPersianDatePickerModal();
            modal = document.getElementById('persianDatePicker');
        }
        
        // Initialize with current Persian date
        const today = new Date();
        const [jy, jm, jd] = PersianDate.toJalaali(today.getFullYear(), today.getMonth() + 1, today.getDate());
        currentYear = jy;
        currentMonth = jm;
        
        console.log('Current Persian date:', `${jy}/${jm}/${jd}`);
        
        // Populate year select
        populateYearSelect();
        
        console.log('Persian Date Picker initialized successfully');
    }

    function createPersianDatePickerModal() {
        const modalHTML = `
        <div id="persianDatePicker" style="
            display: none; 
            position: fixed; 
            top: 0; left: 0; right: 0; bottom: 0; 
            z-index: 99999; 
            background: rgba(0,0,0,0.7); 
            justify-content: center; 
            align-items: center;
            backdrop-filter: blur(4px);
        ">
            <div style="
                background: white; 
                border-radius: 12px; 
                padding: 24px; 
                width: 320px; 
                max-width: 90vw; 
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                margin: 16px;
            ">
                <!-- Header -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin: 0;">انتخاب تاریخ</h3>
                    <button onclick="closeDatePicker()" style="color: #9ca3af; background: none; border: none; font-size: 20px; cursor: pointer;">
                        ✕
                    </button>
                </div>
                
                <!-- Month/Year Navigation -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <button onclick="changeMonth(-1)" style="
                        padding: 8px; 
                        border-radius: 8px; 
                        background: #dbeafe; 
                        color: #2563eb; 
                        border: none; 
                        cursor: pointer;
                    ">‹</button>
                    
                    <div style="display: flex; gap: 8px;">
                        <select id="monthSelect" onchange="updateCalendar()" style="
                            padding: 4px 8px; 
                            border: 1px solid #d1d5db; 
                            border-radius: 6px; 
                            font-size: 14px;
                        ">
                            <option value="1">فروردین</option>
                            <option value="2">اردیبهشت</option>
                            <option value="3">خرداد</option>
                            <option value="4">تیر</option>
                            <option value="5">مرداد</option>
                            <option value="6">شهریور</option>
                            <option value="7">مهر</option>
                            <option value="8">آبان</option>
                            <option value="9">آذر</option>
                            <option value="10">دی</option>
                            <option value="11">بهمن</option>
                            <option value="12">اسفند</option>
                        </select>
                        
                        <select id="yearSelect" onchange="updateCalendar()" style="
                            padding: 4px 8px; 
                            border: 1px solid #d1d5db; 
                            border-radius: 6px; 
                            font-size: 14px;
                        ">
                        </select>
                    </div>
                    
                    <button onclick="changeMonth(1)" style="
                        padding: 8px; 
                        border-radius: 8px; 
                        background: #dbeafe; 
                        color: #2563eb; 
                        border: none; 
                        cursor: pointer;
                    ">›</button>
                </div>
                
                <!-- Weekdays -->
                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; margin-bottom: 8px;">
                    <div style="text-align: center; font-size: 12px; font-weight: bold; color: #6b7280; padding: 8px;">ش</div>
                    <div style="text-align: center; font-size: 12px; font-weight: bold; color: #6b7280; padding: 8px;">ی</div>
                    <div style="text-align: center; font-size: 12px; font-weight: bold; color: #6b7280; padding: 8px;">د</div>
                    <div style="text-align: center; font-size: 12px; font-weight: bold; color: #6b7280; padding: 8px;">س</div>
                    <div style="text-align: center; font-size: 12px; font-weight: bold; color: #6b7280; padding: 8px;">چ</div>
                    <div style="text-align: center; font-size: 12px; font-weight: bold; color: #6b7280; padding: 8px;">پ</div>
                    <div style="text-align: center; font-size: 12px; font-weight: bold; color: #ef4444; padding: 8px;">ج</div>
                </div>
                
                <!-- Calendar Days -->
                <div id="calendarDays" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; margin-bottom: 16px;">
                </div>
                
                <!-- Action Buttons -->
                <div style="display: flex; gap: 8px;">
                    <button onclick="selectToday()" style="
                        flex: 1; 
                        padding: 8px 16px; 
                        background: #10b981; 
                        color: white; 
                        border: none; 
                        border-radius: 8px; 
                        font-size: 14px; 
                        font-weight: 500; 
                        cursor: pointer;
                    ">امروز</button>
                    <button onclick="clearDate()" style="
                        flex: 1; 
                        padding: 8px 16px; 
                        background: #6b7280; 
                        color: white; 
                        border: none; 
                        border-radius: 8px; 
                        font-size: 14px; 
                        font-weight: 500; 
                        cursor: pointer;
                    ">پاک کردن</button>
                </div>
            </div>
        </div>
        `;
        
        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        console.log('Persian Date Picker Modal created and added to body');
    }
    
    function populateYearSelect() {
        const yearSelect = document.getElementById('yearSelect');
        if (!yearSelect) {
            console.error('yearSelect element not found!');
            return;
        }
        
        console.log('Populating year select with years 1390-1410');
        yearSelect.innerHTML = '';
        
        for (let year = 1390; year <= 1410; year++) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            if (year === currentYear) {
                option.selected = true;
            }
            yearSelect.appendChild(option);
        }
        
        console.log(`Year select populated with ${yearSelect.children.length} options`);
    }
    
    // Force show modal function for debugging
    window.forceShowModal = function() {
        const modal = document.getElementById('persianDatePicker');
        if (modal) {
            modal.style.display = 'flex';
            populateYearSelect();
            updateCalendar();
            alert('✅ Modal به زور نمایش داده شد!');
        } else {
            alert('❌ Modal element پیدا نشد!');
        }
    };

    window.openDatePicker = function(inputId) {
        try {
            currentDatePickerInput = inputId;
            
            const modal = document.getElementById('persianDatePicker');
            if (!modal) {
                alert('❌ Modal پیدا نشد!');
                return;
            }
            
            // Show modal with simple display flex
            modal.style.display = 'flex';
            
            // Initialize if needed
            const yearSelect = document.getElementById('yearSelect');
            const monthSelect = document.getElementById('monthSelect');
            
            if (yearSelect && yearSelect.children.length === 0) {
                populateYearSelect();
            }
            
            // Set current date
            const input = document.getElementById(inputId);
            if (input && input.value) {
                const parsed = PersianDate.parsePersian(input.value);
                if (parsed) {
                    currentYear = parsed[0];
                    currentMonth = parsed[1];
                }
            }
            
            if (yearSelect && monthSelect) {
                yearSelect.value = currentYear;
                monthSelect.value = currentMonth;
                updateCalendar();
            }
            
        } catch (error) {
            console.error('خطا:', error);
            alert('خطا در باز کردن تاریخ‌گزار: ' + error.message);
        }
    };
    
    window.closeDatePicker = function() {
        const modal = document.getElementById('persianDatePicker');
        if (modal) {
            modal.style.display = 'none';
        }
        currentDatePickerInput = null;
    };
    
    window.changeMonth = function(direction) {
        currentMonth += direction;
        if (currentMonth < 1) {
            currentMonth = 12;
            currentYear--;
        } else if (currentMonth > 12) {
            currentMonth = 1;
            currentYear++;
        }
        
        document.getElementById('yearSelect').value = currentYear;
        document.getElementById('monthSelect').value = currentMonth;
        updateCalendar();
    };
    
    window.updateCalendar = function() {
        try {
            const yearSelect = document.getElementById('yearSelect');
            const monthSelect = document.getElementById('monthSelect');
            const calendarDays = document.getElementById('calendarDays');
            
            if (!yearSelect || !monthSelect || !calendarDays) {
                console.error('Calendar elements not found!');
                return;
            }
            
            currentYear = parseInt(yearSelect.value);
            currentMonth = parseInt(monthSelect.value);
            
            console.log(`Updating calendar for ${currentYear}/${currentMonth}`);
            calendarDays.innerHTML = '';
        
        // Get days in current month
        let daysInMonth;
        if (currentMonth <= 6) {
            daysInMonth = 31;
        } else if (currentMonth <= 11) {
            daysInMonth = 30;
        } else {
            // Check if leap year for month 12 (Esfand)
            const isLeap = ((currentYear - 979) % 33 % 4) === 1;
            daysInMonth = isLeap ? 30 : 29;
        }
        
        // Get first day of month (day of week)
        const firstDay = PersianDate.toGregorian(currentYear, currentMonth, 1);
        const firstDayObj = new Date(firstDay[0], firstDay[1] - 1, firstDay[2]);
        let startDay = firstDayObj.getDay(); // 0 = Sunday
        
        // Convert to Persian week (Saturday = 0)
        startDay = (startDay + 1) % 7;
        
        // Add empty cells for previous month
        for (let i = 0; i < startDay; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'calendar-day other-month';
            calendarDays.appendChild(emptyDay);
        }
        
        // Add days of current month
        const today = new Date();
        const [todayYear, todayMonth, todayDay] = PersianDate.toJalaali(today.getFullYear(), today.getMonth() + 1, today.getDate());
        
        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';
            dayElement.textContent = day;
            
            // Base styling
            dayElement.style.cssText = `
                width: 32px; 
                height: 32px; 
                display: flex; 
                align-items: center; 
                justify-content: center; 
                font-size: 14px; 
                cursor: pointer; 
                border-radius: 8px; 
                transition: all 0.2s;
            `;
            
            // Mark today
            if (currentYear === todayYear && currentMonth === todayMonth && day === todayDay) {
                dayElement.classList.add('today');
                dayElement.style.background = '#dcfce7';
                dayElement.style.color = '#16a34a';
                dayElement.style.fontWeight = 'bold';
            }
            
            // Mark selected date
            if (currentDatePickerInput) {
                const input = document.getElementById(currentDatePickerInput);
                if (input && input.value) {
                    const parsed = PersianDate.parsePersian(input.value);
                    if (parsed && parsed[0] === currentYear && parsed[1] === currentMonth && parsed[2] === day) {
                        dayElement.classList.add('selected');
                        dayElement.style.background = '#3b82f6';
                        dayElement.style.color = 'white';
                        dayElement.style.fontWeight = 'bold';
                    }
                }
            }
            
            // Hover effect
            dayElement.addEventListener('mouseenter', function() {
                if (!this.classList.contains('selected') && !this.classList.contains('today')) {
                    this.style.background = '#dbeafe';
                    this.style.color = '#2563eb';
                }
            });
            
            dayElement.addEventListener('mouseleave', function() {
                if (!this.classList.contains('selected') && !this.classList.contains('today')) {
                    this.style.background = '';
                    this.style.color = '';
                }
            });
            
            dayElement.onclick = () => selectDate(day);
            calendarDays.appendChild(dayElement);
        }
        
        // Fill remaining cells
        const totalCells = calendarDays.children.length;
        const remainingCells = 42 - totalCells; // 6 rows × 7 days
        for (let i = 0; i < remainingCells && i < 14; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'calendar-day other-month';
            calendarDays.appendChild(emptyDay);
        }
        
        console.log(`Calendar updated with ${daysInMonth} days for ${currentYear}/${currentMonth}`);
        
        } catch (error) {
            console.error('Error in updateCalendar:', error);
        }
    };
    
    function selectDate(day) {
        if (!currentDatePickerInput) return;
        
        const selectedDate = PersianDate.formatPersian(currentYear, currentMonth, day);
        const input = document.getElementById(currentDatePickerInput);
        input.value = selectedDate;
        
        // Trigger change event
        input.dispatchEvent(new Event('change'));
        
        // Update preview if it's a coupon modal
        if (currentDatePickerInput.includes('startDate') || currentDatePickerInput.includes('endDate')) {
            if (currentDatePickerInput.includes('edit')) {
                updateEditCouponPreview();
            } else {
                updateCouponPreview();
            }
        }
        
        closeDatePicker();
    }
    
    window.selectToday = function() {
        const today = new Date();
        const [jy, jm, jd] = PersianDate.toJalaali(today.getFullYear(), today.getMonth() + 1, today.getDate());
        selectDate(jd);
        
        // Update calendar to current month
        currentYear = jy;
        currentMonth = jm;
        document.getElementById('yearSelect').value = currentYear;
        document.getElementById('monthSelect').value = currentMonth;
        updateCalendar();
    };
    
    window.clearDate = function() {
        if (!currentDatePickerInput) return;
        
        const input = document.getElementById(currentDatePickerInput);
        input.value = '';
        
        // Trigger change event
        input.dispatchEvent(new Event('change'));
        
        // Update preview if it's a coupon modal
        if (currentDatePickerInput.includes('startDate') || currentDatePickerInput.includes('endDate')) {
            if (currentDatePickerInput.includes('edit')) {
                updateEditCouponPreview();
            } else {
                updateCouponPreview();
            }
        }
        
        closeDatePicker();
    };



    // ==========================================
    // Menu Management Functions
    // ==========================================

    // Load menus from localStorage or use sample data
    function loadStoredMenus() {
        console.log('📥 loadStoredMenus فراخوانی شد');
        const stored = localStorage.getItem('adminMenus');
        if (stored) {
            const parsed = JSON.parse(stored);
            sampleMenus.length = 0;
            sampleMenus.push(...parsed);
            console.log('✅ منوها از localStorage بارگذاری شد:', sampleMenus.length);
        } else {
            localStorage.setItem('adminMenus', JSON.stringify(sampleMenus));
            console.log('✅ منوهای پیش‌فرض در localStorage ذخیره شد');
        }
    }

    // Load menus into table
    function loadMenus() {
        console.log('🔄 loadMenus فراخوانی شد');
        console.log('📊 sampleMenus:', sampleMenus);
        
        const menusTableBody = document.getElementById('menusTableBody');
        const currentMenuPreview = document.getElementById('currentMenuPreview');
        
        console.log('🔍 menusTableBody:', menusTableBody);
        console.log('🔍 currentMenuPreview:', currentMenuPreview);
        
        if (!menusTableBody || !currentMenuPreview) {
            console.log('❌ یکی از element ها پیدا نشد!');
            return;
        }

        // Sort menus by order
        const sortedMenus = [...sampleMenus].sort((a, b) => a.order - b.order);

        // Clear existing content
        menusTableBody.innerHTML = '';
        currentMenuPreview.innerHTML = '';
        
        console.log('✅ محتوای قدیمی پاک شد');

        // Populate table
        sortedMenus.forEach(menu => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            
            const statusClass = menu.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
            const statusText = menu.status === 'active' ? 'فعال' : 'غیرفعال';
            
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div class="flex items-center">
                        <button onclick="moveMenuUp(${menu.id})" class="text-blue-600 hover:text-blue-800 mr-2" title="انتقال به بالا">
                            <i class="fas fa-arrow-up"></i>
                        </button>
                        <span class="bg-gray-100 px-2 py-1 rounded text-xs font-semibold">${menu.order}</span>
                        <button onclick="moveMenuDown(${menu.id})" class="text-blue-600 hover:text-blue-800 ml-2" title="انتقال به پایین">
                            <i class="fas fa-arrow-down"></i>
                        </button>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        ${menu.icon ? `<i class="${menu.icon} ml-2 text-blue-600"></i>` : ''}
                        <span class="text-sm font-medium text-gray-900">${menu.title}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <code class="bg-gray-100 px-2 py-1 rounded text-xs">${menu.url}</code>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${menu.icon ? `<i class="${menu.icon}"></i>` : '<span class="text-gray-400">-</span>'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${statusClass}">
                        ${statusText}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex gap-2">
                        <button class="text-blue-600 hover:text-blue-800" onclick="editMenu(${menu.id})" title="ویرایش">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-800" onclick="deleteMenu(${menu.id})" title="حذف">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            
            menusTableBody.appendChild(row);
        });
        
        console.log(`✅ ${sortedMenus.length} منو به جدول اضافه شد`);

        // Populate preview
        const activeMenus = sortedMenus.filter(menu => menu.status === 'active');
        activeMenus.forEach(menu => {
            const menuPreview = document.createElement('div');
            menuPreview.className = 'inline-flex items-center px-3 py-2 text-gray-700 hover:text-blue-600 transition-colors border rounded-lg';
            menuPreview.innerHTML = `
                ${menu.icon ? `<i class="${menu.icon} ml-2"></i>` : ''}
                <span>${menu.title}</span>
            `;
            currentMenuPreview.appendChild(menuPreview);
        });
        
        console.log(`✅ ${activeMenus.length} منوی فعال به پیش‌نمایش اضافه شد`);
        console.log('🎯 loadMenus کامل شد');
    }

    // Show add menu modal
    window.showAddMenuModal = function() {
        console.log('🎯 showAddMenuModal فراخوانی شد');
        const modal = document.getElementById('addMenuModal');
        if (!modal) {
            alert('❌ addMenuModal پیدا نشد!');
            return;
        }
        console.log('✅ Modal پیدا شد، در حال نمایش...');
        modal.classList.remove('hidden');
        setupAddMenuModal();
        console.log('✅ showAddMenuModal کامل شد');
    };

    // Hide add menu modal
    window.hideAddMenuModal = function() {
        document.getElementById('addMenuModal').classList.add('hidden');
        document.getElementById('addMenuForm').reset();
        updateMenuPreview();
    };

    // Setup add menu modal
    function setupAddMenuModal() {
        const form = document.getElementById('addMenuForm');
        const titleInput = form.querySelector('input[name="title"]');
        const urlInput = form.querySelector('input[name="url"]');
        const iconInput = document.getElementById('menuIconInput');
        const orderInput = form.querySelector('input[name="order"]');
        
        // Set next order number
        const maxOrder = Math.max(...sampleMenus.map(m => m.order), 0);
        orderInput.value = maxOrder + 1;
        
        // Icon preview
        iconInput.addEventListener('input', function() {
            updateMenuIconPreview('menuIconPreview', this.value);
            updateMenuPreview();
        });
        
        // Live preview updates
        titleInput.addEventListener('input', updateMenuPreview);
        urlInput.addEventListener('input', updateMenuPreview);
        
        // Form submission
        form.removeEventListener('submit', handleAddMenuSubmission);
        form.addEventListener('submit', handleAddMenuSubmission);
        
        // Initialize preview
        updateMenuPreview();
    }

    // Handle add menu form submission
    function handleAddMenuSubmission(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const title = formData.get('title').trim();
        const url = formData.get('url').trim();
        const icon = formData.get('icon').trim();
        const order = parseInt(formData.get('order')) || 1;
        const target = formData.get('target');
        const status = formData.get('status');
        const description = formData.get('description').trim();
        
        // Validation
        if (!title || !url) {
            showNotification('لطفاً تمام فیلدهای الزامی را پر کنید', 'error');
            return;
        }
        
        // Create new menu
        const newMenu = {
            id: Date.now(),
            title,
            url,
            icon: icon || null,
            order,
            target,
            status,
            description,
            createdAt: new Date().toISOString()
        };
        
        // Add to array
        sampleMenus.push(newMenu);
        
        // Save to localStorage
        localStorage.setItem('adminMenus', JSON.stringify(sampleMenus));
        
        // Reload menus
        loadMenus();
        
        // Hide modal and show success message
        hideAddMenuModal();
        showNotification('آیتم منو با موفقیت اضافه شد', 'success');
    }

    // Edit menu
    window.editMenu = function(menuId) {
        const menu = sampleMenus.find(m => m.id === menuId);
        if (!menu) {
            showNotification('آیتم منو یافت نشد', 'error');
            return;
        }
        
        // Fill edit form
        document.getElementById('editMenuId').value = menu.id;
        document.getElementById('editMenuTitle').value = menu.title;
        document.getElementById('editMenuUrl').value = menu.url;
        document.getElementById('editMenuIconInput').value = menu.icon || '';
        document.getElementById('editMenuOrder').value = menu.order;
        document.getElementById('editMenuTarget').value = menu.target;
        document.getElementById('editMenuStatus').value = menu.status;
        document.getElementById('editMenuDescription').value = menu.description || '';
        
        // Update icon preview
        updateMenuIconPreview('editMenuIconPreview', menu.icon || '');
        updateEditMenuPreview();
        
        // Show modal
        document.getElementById('editMenuModal').classList.remove('hidden');
        setupEditMenuModal();
    };

    // Setup edit menu modal
    function setupEditMenuModal() {
        const form = document.getElementById('editMenuForm');
        const titleInput = document.getElementById('editMenuTitle');
        const urlInput = document.getElementById('editMenuUrl');
        const iconInput = document.getElementById('editMenuIconInput');
        
        // Icon preview
        iconInput.addEventListener('input', function() {
            updateMenuIconPreview('editMenuIconPreview', this.value);
            updateEditMenuPreview();
        });
        
        // Live preview updates
        titleInput.addEventListener('input', updateEditMenuPreview);
        urlInput.addEventListener('input', updateEditMenuPreview);
        
        // Form submission
        form.removeEventListener('submit', handleEditMenuSubmission);
        form.addEventListener('submit', handleEditMenuSubmission);
    }

    // Handle edit menu form submission
    function handleEditMenuSubmission(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const menuId = parseInt(formData.get('menuId'));
        const title = formData.get('title').trim();
        const url = formData.get('url').trim();
        const icon = formData.get('icon').trim();
        const order = parseInt(formData.get('order')) || 1;
        const target = formData.get('target');
        const status = formData.get('status');
        const description = formData.get('description').trim();
        
        // Validation
        if (!title || !url) {
            showNotification('لطفاً تمام فیلدهای الزامی را پر کنید', 'error');
            return;
        }
        
        // Find and update menu
        const menuIndex = sampleMenus.findIndex(m => m.id === menuId);
        if (menuIndex === -1) {
            showNotification('آیتم منو یافت نشد', 'error');
            return;
        }
        
        // Update menu
        sampleMenus[menuIndex] = {
            ...sampleMenus[menuIndex],
            title,
            url,
            icon: icon || null,
            order,
            target,
            status,
            description,
            updatedAt: new Date().toISOString()
        };
        
        // Save to localStorage
        localStorage.setItem('adminMenus', JSON.stringify(sampleMenus));
        
        // Reload menus
        loadMenus();
        
        // Hide modal and show success message
        hideEditMenuModal();
        showNotification('آیتم منو با موفقیت ویرایش شد', 'success');
    }

    // Hide edit menu modal
    window.hideEditMenuModal = function() {
        document.getElementById('editMenuModal').classList.add('hidden');
    };

    // Delete menu
    window.deleteMenu = function(menuId) {
        if (confirm('آیا از حذف این آیتم منو مطمئن هستید؟')) {
            const menuIndex = sampleMenus.findIndex(m => m.id === menuId);
            if (menuIndex !== -1) {
                sampleMenus.splice(menuIndex, 1);
                localStorage.setItem('adminMenus', JSON.stringify(sampleMenus));
                showNotification('آیتم منو با موفقیت حذف شد', 'success');
                loadMenus();
            }
        }
    };

    // Move menu up
    window.moveMenuUp = function(menuId) {
        const menu = sampleMenus.find(m => m.id === menuId);
        if (!menu || menu.order <= 1) return;
        
        // Find menu with order-1
        const upperMenu = sampleMenus.find(m => m.order === menu.order - 1);
        if (upperMenu) {
            // Swap orders
            upperMenu.order = menu.order;
            menu.order = menu.order - 1;
            
            // Save and reload
            localStorage.setItem('adminMenus', JSON.stringify(sampleMenus));
            loadMenus();
        }
    };

    // Move menu down
    window.moveMenuDown = function(menuId) {
        const maxOrder = Math.max(...sampleMenus.map(m => m.order));
        const menu = sampleMenus.find(m => m.id === menuId);
        if (!menu || menu.order >= maxOrder) return;
        
        // Find menu with order+1
        const lowerMenu = sampleMenus.find(m => m.order === menu.order + 1);
        if (lowerMenu) {
            // Swap orders
            lowerMenu.order = menu.order;
            menu.order = menu.order + 1;
            
            // Save and reload
            localStorage.setItem('adminMenus', JSON.stringify(sampleMenus));
            loadMenus();
        }
    };

    // Update menu icon preview
    function updateMenuIconPreview(previewId, iconClass) {
        const preview = document.getElementById(previewId);
        if (preview) {
            preview.innerHTML = iconClass ? `<i class="${iconClass}"></i>` : '<i class="fas fa-question"></i>';
        }
    }

    // Update add menu preview
    function updateMenuPreview() {
        const form = document.getElementById('addMenuForm');
        const title = form.querySelector('input[name="title"]').value || 'نام منو';
        const icon = document.getElementById('menuIconInput').value;
        const preview = document.getElementById('menuPreview');
        
        if (preview) {
            preview.innerHTML = `
                <div class="inline-flex items-center px-3 py-2 text-gray-700 hover:text-blue-600 transition-colors">
                    ${icon ? `<i class="${icon} w-5 ml-2"></i>` : '<i class="fas fa-question w-5 ml-2"></i>'}
                    <span>${title}</span>
                </div>
            `;
        }
    }

    // Update edit menu preview
    function updateEditMenuPreview() {
        const title = document.getElementById('editMenuTitle').value || 'نام منو';
        const icon = document.getElementById('editMenuIconInput').value;
        const preview = document.getElementById('editMenuPreview');
        
        if (preview) {
            preview.innerHTML = `
                <div class="inline-flex items-center px-3 py-2 text-gray-700 hover:text-blue-600 transition-colors">
                    ${icon ? `<i class="${icon} w-5 ml-2"></i>` : '<i class="fas fa-question w-5 ml-2"></i>'}
                    <span>${title}</span>
                </div>
            `;
        }
    }

    // Initialize all modules on load - make data globally accessible
    window.sampleMenus = sampleMenus;
    window.samplePayments = samplePayments;
    window.sampleTickets = sampleTickets;
    window.sampleFaqs = sampleFaqs;
    
    loadStoredMenus();
    loadStoredPayments();
    loadStoredTickets();
    loadStoredFaqs();
    
    // Load stored data functions
    function loadStoredPayments() {
        const stored = localStorage.getItem('adminPayments');
        if (stored) {
            const parsed = JSON.parse(stored);
            samplePayments.length = 0;
            samplePayments.push(...parsed);
        } else {
            localStorage.setItem('adminPayments', JSON.stringify(samplePayments));
        }
    }

    function loadStoredTickets() {
        const stored = localStorage.getItem('adminTickets');
        if (stored) {
            const parsed = JSON.parse(stored);
            sampleTickets.length = 0;
            sampleTickets.push(...parsed);
        } else {
            localStorage.setItem('adminTickets', JSON.stringify(sampleTickets));
        }
    }

    function loadStoredFaqs() {
        const stored = localStorage.getItem('adminFaqs');
        if (stored) {
            const parsed = JSON.parse(stored);
            sampleFaqs.length = 0;
            sampleFaqs.push(...parsed);
        } else {
            localStorage.setItem('adminFaqs', JSON.stringify(sampleFaqs));
        }
    }

    // Load functions for each module
    function loadPayments() {
        const paymentsTableBody = document.getElementById('paymentsTableBody');
        if (!paymentsTableBody) return;

        paymentsTableBody.innerHTML = '';
        samplePayments.forEach(payment => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            
            const statusClasses = {
                'completed': 'bg-green-100 text-green-800',
                'failed': 'bg-red-100 text-red-800',
                'pending': 'bg-yellow-100 text-yellow-800'
            };
            
            const statusNames = {
                'completed': 'موفق',
                'failed': 'ناموفق',
                'pending': 'در انتظار'
            };
            
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-blue-600 font-bold">${payment.id}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div>
                        <div class="text-sm font-medium text-gray-900">${payment.userName}</div>
                        <div class="text-sm text-gray-500">${payment.userEmail}</div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                    ${payment.amount.toLocaleString()} تومان
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${payment.gatewayName}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${statusClasses[payment.status]}">
                        ${statusNames[payment.status]}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${payment.date}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex gap-2">
                        <button class="text-blue-600 hover:text-blue-800" onclick="viewPaymentDetails('${payment.id}')" title="جزئیات">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </td>
            `;
            
            paymentsTableBody.appendChild(row);
        });

        updatePaymentStats();
    }

    function updatePaymentStats() {
        const completed = samplePayments.filter(p => p.status === 'completed').length;
        const failed = samplePayments.filter(p => p.status === 'failed').length;
        const pending = samplePayments.filter(p => p.status === 'pending').length;
        const totalRevenue = samplePayments
            .filter(p => p.status === 'completed')
            .reduce((sum, p) => sum + p.amount, 0);

        const successEl = document.getElementById('successfulPayments');
        const failedEl = document.getElementById('failedPayments');
        const pendingEl = document.getElementById('pendingPayments');
        const revenueEl = document.getElementById('totalRevenue');

        if (successEl) successEl.textContent = completed;
        if (failedEl) failedEl.textContent = failed;
        if (pendingEl) pendingEl.textContent = pending;
        if (revenueEl) revenueEl.textContent = totalRevenue.toLocaleString();
    }

    window.viewPaymentDetails = function(paymentId) {
        const payment = samplePayments.find(p => p.id === paymentId);
        if (payment) {
            const statusNames = { 'completed': 'موفق', 'failed': 'ناموفق', 'pending': 'در انتظار' };
            alert(`💳 جزئیات پرداخت ${paymentId}\n\n👤 کاربر: ${payment.userName}\n💰 مبلغ: ${payment.amount.toLocaleString()} تومان\n📦 محصول: ${payment.productTitle}\n🏦 درگاه: ${payment.gatewayName}\n📊 وضعیت: ${statusNames[payment.status] || payment.status}\n🔢 شناسه تراکنش: ${payment.transactionId}`);
        }
    };

    window.exportPaymentsToCSV = function() {
        showNotification('💾 دانلود فایل CSV در حال توسعه است', 'info');
    };

    window.showPaymentStatsModal = function() {
        showNotification('📊 آمار تفصیلی پرداخت‌ها در حال توسعه است', 'info');
    };

    // Tickets functions
    function loadTickets() {
        const ticketsTableBody = document.getElementById('ticketsTableBody');
        if (!ticketsTableBody) return;

        ticketsTableBody.innerHTML = '';
        sampleTickets.forEach(ticket => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            
            const statusClasses = {
                'open': 'bg-yellow-100 text-yellow-800',
                'in_progress': 'bg-blue-100 text-blue-800',
                'closed': 'bg-green-100 text-green-800'
            };
            
            const priorityClasses = {
                'low': 'bg-gray-100 text-gray-800',
                'medium': 'bg-yellow-100 text-yellow-800',
                'high': 'bg-red-100 text-red-800'
            };
            
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-blue-600 font-bold">${ticket.ticketNumber}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div>
                        <div class="text-sm font-medium text-gray-900">${ticket.userName}</div>
                        <div class="text-sm text-gray-500">${ticket.userEmail}</div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${ticket.subject}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${ticket.categoryName}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${priorityClasses[ticket.priority]}">
                        ${ticket.priorityName}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${statusClasses[ticket.status]}">
                        ${ticket.statusName}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${ticket.date}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex gap-2">
                        <button class="text-blue-600 hover:text-blue-800" onclick="viewTicket(${ticket.id})" title="مشاهده">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="text-green-600 hover:text-green-800" onclick="replyToTicket(${ticket.id})" title="پاسخ">
                            <i class="fas fa-reply"></i>
                        </button>
                    </div>
                </td>
            `;
            
            ticketsTableBody.appendChild(row);
        });

        updateTicketStats();
    }

    function updateTicketStats() {
        const total = sampleTickets.length;
        const open = sampleTickets.filter(t => t.status === 'open').length;
        const urgent = sampleTickets.filter(t => t.priority === 'high').length;
        const closed = sampleTickets.filter(t => t.status === 'closed').length;

        const totalEl = document.getElementById('totalTickets');
        const openEl = document.getElementById('openTickets');
        const urgentEl = document.getElementById('urgentTickets');
        const closedEl = document.getElementById('closedTickets');

        if (totalEl) totalEl.textContent = total;
        if (openEl) openEl.textContent = open;
        if (urgentEl) urgentEl.textContent = urgent;
        if (closedEl) closedEl.textContent = closed;
    }

    window.showAddTicketModal = function() {
        showNotification('📝 فرم ایجاد تیکت جدید در حال توسعه است', 'info');
    };

    window.viewTicket = function(ticketId) {
        const ticket = sampleTickets.find(t => t.id === ticketId);
        if (ticket) {
            const responseText = ticket.response ? `\n\n📝 پاسخ:\n${ticket.response}` : '\n\n⏳ هنوز پاسخی ارسال نشده است.';
            alert(`🎫 تیکت ${ticket.ticketNumber}\n\n👤 کاربر: ${ticket.userName}\n📋 موضوع: ${ticket.subject}\n🏷️ دسته‌بندی: ${ticket.categoryName}\n⚡ اولویت: ${ticket.priorityName}\n📊 وضعیت: ${ticket.statusName}\n\n💬 پیام:\n${ticket.message}${responseText}`);
        }
    };

    window.replyToTicket = function(ticketId) {
        const ticket = sampleTickets.find(t => t.id === ticketId);
        if (ticket) {
            const response = prompt(`📝 پاسخ به تیکت ${ticket.ticketNumber}:\n\n📋 موضوع: ${ticket.subject}\n💬 پیام کاربر: ${ticket.message}\n\n✍️ پاسخ شما:`);
            if (response && response.trim()) {
                ticket.response = response.trim();
                ticket.status = 'in_progress';
                ticket.statusName = 'در حال بررسی';
                ticket.assignedTo = 'شما';
                ticket.updatedAt = new Date().toISOString();
                
                localStorage.setItem('adminTickets', JSON.stringify(sampleTickets));
                loadTickets();
                showNotification('✅ پاسخ با موفقیت ارسال شد', 'success');
            }
        }
    };

    // FAQ functions
    function loadFaqs() {
        const faqAccordion = document.getElementById('faqAccordion');
        if (!faqAccordion) return;

        const sortedFaqs = [...sampleFaqs].sort((a, b) => a.order - b.order);
        faqAccordion.innerHTML = '';
        
        sortedFaqs.forEach(faq => {
            const faqItem = document.createElement('div');
            faqItem.className = 'border border-gray-200 rounded-lg';
            
            const statusClass = faq.status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
            
            faqItem.innerHTML = `
                <div class="flex items-center justify-between p-4 bg-gray-50">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">${faq.question}</h4>
                        <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${statusClass}">
                                ${faq.statusName}
                            </span>
                            <span>دسته: ${faq.category}</span>
                            <span>👁️ ${faq.views}</span>
                            <span class="text-green-600">👍 ${faq.helpful}</span>
                            <span class="text-red-600">👎 ${faq.notHelpful}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="toggleFaqAnswer(${faq.id})" class="text-blue-600 hover:text-blue-800" title="نمایش/مخفی کردن پاسخ">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <button onclick="editFaq(${faq.id})" class="text-green-600 hover:text-green-800" title="ویرایش">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteFaq(${faq.id})" class="text-red-600 hover:text-red-800" title="حذف">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div id="faqAnswer${faq.id}" class="p-4 border-t border-gray-200 hidden">
                    <p class="text-gray-700 leading-relaxed">${faq.answer}</p>
                </div>
            `;
            
            faqAccordion.appendChild(faqItem);
        });

        updateFaqStats();
    }

    function updateFaqStats() {
        const total = sampleFaqs.length;
        const published = sampleFaqs.filter(f => f.status === 'published').length;
        const draft = sampleFaqs.filter(f => f.status === 'draft').length;

        const totalEl = document.getElementById('totalFaqs');
        const publishedEl = document.getElementById('publishedFaqs');
        const draftEl = document.getElementById('draftFaqs');

        if (totalEl) totalEl.textContent = total;
        if (publishedEl) publishedEl.textContent = published;
        if (draftEl) draftEl.textContent = draft;
    }

    window.showAddFaqModal = function() {
        showNotification('📝 فرم افزودن سوال متداول در حال توسعه است', 'info');
    };

    window.toggleFaqAnswer = function(faqId) {
        const answerDiv = document.getElementById(`faqAnswer${faqId}`);
        if (answerDiv) {
            answerDiv.classList.toggle('hidden');
            const icon = document.querySelector(`button[onclick="toggleFaqAnswer(${faqId})"] i`);
            if (icon) {
                icon.classList.toggle('fa-chevron-down');
                icon.classList.toggle('fa-chevron-up');
            }
        }
    };

    window.editFaq = function(faqId) {
        const faq = sampleFaqs.find(f => f.id === faqId);
        if (faq) {
            const newQuestion = prompt('✏️ سوال جدید:', faq.question);
            if (newQuestion && newQuestion.trim()) {
                const newAnswer = prompt('✍️ پاسخ جدید:', faq.answer);
                if (newAnswer && newAnswer.trim()) {
                    faq.question = newQuestion.trim();
                    faq.answer = newAnswer.trim();
                    faq.updatedAt = new Date().toISOString();
                    
                    localStorage.setItem('adminFaqs', JSON.stringify(sampleFaqs));
                    loadFaqs();
                    showNotification('✅ سوال متداول با موفقیت ویرایش شد', 'success');
                }
            }
        }
    };

    window.deleteFaq = function(faqId) {
        if (confirm('🗑️ آیا از حذف این سوال متداول مطمئن هستید؟')) {
            const faqIndex = sampleFaqs.findIndex(f => f.id === faqId);
            if (faqIndex !== -1) {
                sampleFaqs.splice(faqIndex, 1);
                localStorage.setItem('adminFaqs', JSON.stringify(sampleFaqs));
                loadFaqs();
                showNotification('✅ سوال متداول با موفقیت حذف شد', 'success');
            }
        }
    };

    console.log('All modules initialized:', {
        menus: sampleMenus.length,
        payments: samplePayments.length, 
        tickets: sampleTickets.length,
        faqs: sampleFaqs.length
    });

    console.log('Admin panel initialized successfully');
});