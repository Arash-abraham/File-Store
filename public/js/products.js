document.addEventListener('DOMContentLoaded', function() {
    // Cart data
    let cart = [
        {
            id: 1,
            title: 'Adobe Photoshop 2024',
            price: 2500000,
            image: 'https://images.pexels.com/photos/4348401/pexels-photo-4348401.jpeg?auto=compress&cs=tinysrgb&w=400'
        },
        {
            id: 2,
            title: 'دوره کامل آموزش React JS',
            price: 450000,
            image: 'https://images.pexels.com/photos/11035380/pexels-photo-11035380.jpeg?auto=compress&cs=tinysrgb&w=400'
        }
    ];

    // DOM Elements
    const cartToggle = document.getElementById('cart-toggle');
    const cartModal = document.getElementById('cart-modal');
    const closeCart = document.getElementById('close-cart');
    const cartContent = document.getElementById('cart-content');
    const cartCount = document.getElementById('cart-count');
    const cartActions = document.getElementById('cart-actions');
    const blurOverlay = document.getElementById('blur-overlay');
    const availableOnly = document.getElementById('available-only');
    const unavailableOnly = document.getElementById('unavailable-only');
    const priceMin = document.getElementById('price-min');
    const priceMax = document.getElementById('price-max');
    const applyPriceFilter = document.getElementById('apply-price-filter');
    const authorFilters = document.querySelectorAll('.author-filter');
    const clearFilters = document.getElementById('clear-filters');
    const productItems = document.querySelectorAll('.product-item');

    // Filter state
    let filters = {
        availableOnly: false,
        unavailableOnly: false,
        priceMin: null,
        priceMax: null,
        authors: []
    };

    // Update cart count
    function updateCartCount() {
        if (cartCount) {
            cartCount.textContent = cart.length;
        }
    }

    // Render cart content
    function renderCart() {
        if (!cartContent) return;
        
        cartContent.innerHTML = '';
        if (cart.length === 0) {
            cartContent.innerHTML = '<p class="text-center text-gray-400">سبد خرید خالی است</p>';
            if (cartActions) cartActions.innerHTML = '';
        } else {
            cart.forEach(item => {
                const cartItem = document.createElement('div');
                cartItem.className = 'flex items-center justify-between py-2 border-b border-gray-700';
                cartItem.innerHTML = `
                    <div class="flex items-center">
                        <img src="${item.image}" alt="${item.title}" class="w-12 h-12 object-cover rounded mr-2">
                        <div>
                            <h3 class="text-sm font-semibold">${item.title}</h3>
                            <p class="text-xs text-gray-400">${formatPrice(item.price)} تومان</p>
                        </div>
                    </div>
                    <button onclick="removeFromCart(${item.id})" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                `;
                cartContent.appendChild(cartItem);
            });
            if (cartActions) {
                cartActions.innerHTML = '<button id="checkout" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-md">تسویه حساب</button>';
            }
        }
        updateCartCount();
    }

    // Remove item from cart
    window.removeFromCart = function(productId) {
        cart = cart.filter(item => item.id !== productId);
        renderCart();
    };

    // Position cart modal
    function positionCartModal() {
        if (cartModal) {
            cartModal.style.top = '80px';
            cartModal.style.left = '20px';
        }
    }

    // Add to cart
    window.addToCart = function(productId) {
        // Find product by ID - in a real app, you would fetch this from your data
        let product;
        if (productId === 1) {
            product = {
                id: 1,
                title: 'Adobe Photoshop 2024',
                price: 2500000,
                image: 'https://images.pexels.com/photos/4348401/pexels-photo-4348401.jpeg?auto=compress&cs=tinysrgb&w=400'
            };
        } else if (productId === 2) {
            product = {
                id: 2,
                title: 'دوره کامل آموزش React JS',
                price: 450000,
                image: 'https://images.pexels.com/photos/11035380/pexels-photo-11035380.jpeg?auto=compress&cs=tinysrgb&w=400'
            };
        }
        
        if (product) {
            cart.push({
                id: product.id,
                title: product.title,
                price: product.price,
                image: product.image
            });
            renderCart();
            if (cartModal) cartModal.classList.add('show');
            if (blurOverlay) blurOverlay.classList.add('show');
            positionCartModal();
        }
    };

    // Toggle cart modal
    if (cartToggle) {
        cartToggle.addEventListener('click', () => {
            if (cartModal) cartModal.classList.toggle('show');
            if (blurOverlay) blurOverlay.classList.toggle('show');
            if (cartModal && cartModal.classList.contains('show')) {
                renderCart();
                positionCartModal();
            }
        });
    }

    // Close cart modal
    if (closeCart) {
        closeCart.addEventListener('click', () => {
            if (cartModal) cartModal.classList.remove('show');
            if (blurOverlay) blurOverlay.classList.remove('show');
        });
    }

    // Close cart modal on overlay click
    if (blurOverlay) {
        blurOverlay.addEventListener('click', () => {
            if (cartModal) cartModal.classList.remove('show');
            if (blurOverlay) blurOverlay.classList.remove('show');
        });
    }

    // Checkout
    document.addEventListener('click', (e) => {
        if (e.target.id === 'checkout' && cart.length > 0) {
            alert('تسویه حساب انجام شد!');
            cart = [];
            if (cartModal) cartModal.classList.remove('show');
            if (blurOverlay) blurOverlay.classList.remove('show');
            renderCart();
        }
    });

    // Format price in Persian
    function formatPrice(price) {
        return new Intl.NumberFormat('fa-IR').format(price);
    }

    // Apply filters and render products
    function applyFilters() {
        let visibleCount = 0;
        
        productItems.forEach(item => {
            let show = true;
            const available = item.getAttribute('data-available') === 'true';
            const price = parseInt(item.getAttribute('data-price'));
            const author = item.getAttribute('data-author');
            
            // Filter by availability
            if (filters.availableOnly && !available) {
                show = false;
            }
            if (filters.unavailableOnly && available) {
                show = false;
            }
            
            // Filter by price range
            if (show && filters.priceMin !== null && price < filters.priceMin) {
                show = false;
            }
            if (show && filters.priceMax !== null && price > filters.priceMax) {
                show = false;
            }
            
            // Filter by author
            if (show && filters.authors.length > 0 && !filters.authors.includes(author)) {
                show = false;
            }
            
            // Show or hide the product
            if (show) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Update results count
        const resultsCount = document.getElementById('resultsCount');
        if (resultsCount) {
            resultsCount.textContent = `نمایش 1-${visibleCount} از ${visibleCount} محصول`;
        }
    }

    // Event listeners for filters
    if (availableOnly) {
        availableOnly.addEventListener('change', () => {
            filters.availableOnly = availableOnly.checked;
            if (filters.availableOnly) filters.unavailableOnly = false;
            if (unavailableOnly) unavailableOnly.checked = false;
            applyFilters();
        });
    }

    if (unavailableOnly) {
        unavailableOnly.addEventListener('change', () => {
            filters.unavailableOnly = unavailableOnly.checked;
            if (filters.unavailableOnly) filters.availableOnly = false;
            if (availableOnly) availableOnly.checked = false;
            applyFilters();
        });
    }

    if (applyPriceFilter) {
        applyPriceFilter.addEventListener('click', () => {
            filters.priceMin = priceMin.value ? parseInt(priceMin.value) : null;
            filters.priceMax = priceMax.value ? parseInt(priceMax.value) : null;
            applyFilters();
        });
    }

    authorFilters.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            filters.authors = Array.from(authorFilters)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            applyFilters();
        });
    });

    if (clearFilters) {
        clearFilters.addEventListener('click', () => {
            filters = {
                availableOnly: false,
                unavailableOnly: false,
                priceMin: null,
                priceMax: null,
                authors: []
            };
            if (availableOnly) availableOnly.checked = false;
            if (unavailableOnly) unavailableOnly.checked = false;
            if (priceMin) priceMin.value = '';
            if (priceMax) priceMax.value = '';
            authorFilters.forEach(checkbox => checkbox.checked = false);
            applyFilters();
        });
    }
    let currentPath = window.location.pathname.replace(/^\/|\/$/g, '').split('/').pop();
    if (!currentPath) {
        currentPath = 'home';
    }
    console.log('Current Path:', currentPath);

    const navLinks = document.querySelectorAll('.nav-link');
    console.log('Nav Links:', navLinks);

    if (navLinks.length === 0) {
        console.log('No nav links found! Check your HTML or selector.');
        return;
    }

    navLinks.forEach(link => {
        let linkPath = link.getAttribute('href').replace(/^\/|\/$/g, '').split('/').pop();
        if (!linkPath) {
            linkPath = 'home';
        }
        console.log('Link Path:', linkPath, 'Link Text:', link.textContent);

        if (currentPath === linkPath) {
            link.classList.add('text-blue-600', 'font-semibold');
            link.classList.remove('text-black', 'hover:text-blue-600');
        } else {
            link.classList.add('text-black', 'hover:text-blue-600');
            link.classList.remove('text-blue-600', 'font-semibold');
        }
    });
    // Initialize the page
    renderCart();
});