document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filter-form');
    
    const checkInputs = filterForm.querySelectorAll('input[type="checkbox"], input[type="radio"]');
    checkInputs.forEach(input => {
        input.addEventListener('change', function() {
            filterForm.submit();
        });
    });
    
    const priceInputs = filterForm.querySelectorAll('input[name="price_min"], input[name="price_max"]');
    priceInputs.forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                filterForm.submit();
            }
        });
    });
    
    const priceButton = filterForm.querySelector('button[name="apply_price"]');
    if (priceButton) {
        priceButton.addEventListener('click', function(e) {
            e.preventDefault(); 
            filterForm.submit();
        });
    }
});