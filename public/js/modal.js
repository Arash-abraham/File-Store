const cartToggle = document.getElementById('cart-toggle');
const cartModal = document.getElementById('cart-modal');
const closeCart = document.getElementById('close-cart');
const cartContent = document.getElementById('cart-content');
const cartCount = document.getElementById('cart-count');
const cartActions = document.getElementById('cart-actions');
const blurOverlay = document.getElementById('blur-overlay');
    // Toggle cart modal
cartToggle.addEventListener('click', () => {
    cartModal.classList.toggle('show');
    blurOverlay.classList.toggle('show');
    if (cartModal.classList.contains('show')) {
        renderCart();
        positionCartModal();
    }
});
// Position cart modal
function positionCartModal() {
    cartModal.style.top = '80px';
    cartModal.style.left = '20px';
}
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
function formatPrice(price) {
    return new Intl.NumberFormat('fa-IR').format(price);
}