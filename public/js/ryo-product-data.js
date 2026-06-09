(function () {
    const dataEl = document.getElementById('product-data');
    if (!dataEl) return;

    let data = {};
    try {
        data = JSON.parse(dataEl.textContent || '{}');
    } catch (error) {
        console.error('Failed to parse product data JSON:', error);
    }

    window.VARIANTS = data.variants || [];
    window.CHECKOUT_URL = data.checkoutUrl || '';
    window.productImages = data.productImages || { all: [], byColor: {} };
})();
