const productsData = [
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
    },
    {
        id: 3,
        title: 'کتاب آموزش Python از صفر',
        price: 85000,
        image: 'https://images.pexels.com/photos/1181671/pexels-photo-1181671.jpeg?auto=compress&cs=tinysrgb&w=400'
    }
];

// Cart data (initially with 3 items as per the badge)
let cart = [
    { id: 1, title: 'Adobe Photoshop 2024', price: 2500000, image: '' },
    { id: 2, title: 'دوره کامل آموزش React JS', price: 450000, image: 'https://images.pexels.com/photos/11035380/pexels-photo-11035380.jpeg?auto=compress&cs=tinysrgb&w=400' },
    { id: 3, title: 'کتاب آموزش Python از صفر', price: 85000, image: 'https://images.pexels.com/photos/1181671/pexels-photo-1181671.jpeg?auto=compress&cs=tinysrgb&w=400' }
];

// DOM Elements
const cartToggle = document.getElementById('cart-toggle');
const cartModal = document.getElementById('cart-modal');
const closeCart = document.getElementById('close-cart');
const cartContent = document.getElementById('cart-content');
const cartCount = document.getElementById('cart-count');
const cartActions = document.getElementById('cart-actions');
const blurOverlay = document.getElementById('blur-overlay');

// Update cart count
function updateCartCount() {
    cartCount.textContent = cart.length;
}

// Render cart content
function renderCart() {
    cartContent.innerHTML = '';
    if (cart.length === 0) {
        cartContent.innerHTML = '<p class="text-center text-gray-400">سبد خرید خالی است</p>';
        cartActions.innerHTML = '';
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
        cartActions.innerHTML = '<button id="checkout" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-md">تسویه حساب</button>';
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
    cartModal.style.top = '80px';
    cartModal.style.left = '20px';
}

// Add to cart (for future use with product cards)
window.addToCart = function(productId) {
    const product = productsData.find(p => p.id === productId);
    if (product && !cart.find(item => item.id === productId)) {
        cart.push({
            id: product.id,
            title: product.title,
            price: product.price,
            image: product.image
        });
        renderCart();
        cartModal.classList.add('show');
        blurOverlay.classList.add('show');
        positionCartModal();
    }
};

// Toggle cart modal
cartToggle.addEventListener('click', () => {
    cartModal.classList.toggle('show');
    blurOverlay.classList.toggle('show');
    if (cartModal.classList.contains('show')) {
        renderCart();
        positionCartModal();
    }
});

// Close cart modal
closeCart.addEventListener('click', () => {
    cartModal.classList.remove('show');
    blurOverlay.classList.remove('show');
});

// Close cart modal on overlay click
blurOverlay.addEventListener('click', () => {
    cartModal.classList.remove('show');
    blurOverlay.classList.remove('show');
});

// Checkout
document.addEventListener('click', (e) => {
    if (e.target.id === 'checkout' && cart.length > 0) {
        alert('تسویه حساب انجام شد!');
        cart = [];
        cartModal.classList.remove('show');
        blurOverlay.classList.remove('show');
        renderCart();
    }
});

// Format price in Persian
function formatPrice(price) {
    return new Intl.NumberFormat('fa-IR').format(price);
}

document.addEventListener('DOMContentLoaded', function() {
    // Navigation highlighting
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
    
});

// Initialize cart
renderCart();