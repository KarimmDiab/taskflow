// cart.js - Global cart management that persists across pages

// Global cart object
window.cart = {
    // Initialize cart from localStorage
    init: () => {
        const savedCart = localStorage.getItem('ryo_cart');
        if (savedCart) {
            window.cart.items = JSON.parse(savedCart);
        } else {
            window.cart.items = [];
        }
        window.cart.updateBadge();
    },

    items: [],

    addItem: (item) => {
        // Check if item already exists
        const existingIndex = window.cart.items.findIndex(i =>
            i.variantId === item.variantId
        );

        if (existingIndex !== -1) {
            // Update quantity
            const newQty = window.cart.items[existingIndex].quantity + item.quantity;
            const maxStock = item.maxStock || 999;
            if (newQty <= maxStock) {
                window.cart.items[existingIndex].quantity = newQty;
            } else {
                window.cart.items[existingIndex].quantity = maxStock;
            }
        } else {
            window.cart.items.push(item);
        }

        window.cart.save();
        window.cart.updateBadge();
        return true;
    },

    removeItem: (index) => {
        window.cart.items.splice(index, 1);
        window.cart.save();
        window.cart.updateBadge();
    },

    updateQuantity: (index, quantity) => {
        if (index >= 0 && index < window.cart.items.length) {
            if (quantity <= 0) {
                window.cart.removeItem(index);
            } else {
                window.cart.items[index].quantity = quantity;
                window.cart.save();
                window.cart.updateBadge();
            }
        }
    },

    getTotalItems: () => {
        return window.cart.items.reduce((sum, item) => sum + item.quantity, 0);
    },

    getSubtotal: () => {
        return window.cart.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    },

    save: () => {
        localStorage.setItem('ryo_cart', JSON.stringify(window.cart.items));
        window.cart.updateBadge();
    },

    updateBadge: () => {
        const totalItems = window.cart.getTotalItems();
        const cartBadge = document.getElementById('cartBadge');
        if (cartBadge) {
            if (totalItems > 0) {
                cartBadge.style.display = 'flex';
                cartBadge.textContent = totalItems;
            } else {
                cartBadge.style.display = 'none';
            }
        }
    },

    updateCartDrawer: () => {
        const cartItemsContainer = document.getElementById('cartItemsContainer');
        const cartDrawerSubtotal = document.getElementById('cartDrawerSubtotal');
        const cartDrawerCount = document.getElementById('cartDrawerCount');

        if (!cartItemsContainer) return;

        if (window.cart.items.length === 0) {
            cartItemsContainer.innerHTML = `
                <div style="text-align: center; padding: 48px 24px;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.2">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 01-8 0" />
                    </svg>
                    <p style="font-family: 'DM Sans', sans-serif; font-size: 14px; color: #9C9A96; margin-top: 16px;">Your bag is empty</p>
                </div>
            `;
            if (cartDrawerSubtotal) cartDrawerSubtotal.textContent = 'EGP 0';
            if (cartDrawerCount) cartDrawerCount.textContent = '0';
            return;
        }

        let subtotal = 0;
        let itemCount = 0;

        cartItemsContainer.innerHTML = window.cart.items.map((item, idx) => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            itemCount += item.quantity;

            return `
                <div class="flex gap-4 pb-6 mb-6" style="border-bottom: 1px solid #EDEDEB;">
                    <div style="width: 80px; height: 100px; background: #EDEDEB; flex-shrink: 0; overflow: hidden;">
                        <img src="${item.imageUrl}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div style="flex: 1;">
                        <p style="font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500; margin-bottom: 4px;">${item.productName}</p>
                        <p style="font-family: 'DM Sans', sans-serif; font-size: 12px; color: #9C9A96; margin-bottom: 2px;">Size: ${item.sizeName}</p>
                        <p style="font-family: 'DM Sans', sans-serif; font-size: 12px; color: #9C9A96; margin-bottom: 16px;">${item.colorName}</p>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 12px; border: 1px solid #EDEDEB; padding: 6px 14px;">
                                <button onclick="window.cart.updateQuantityInDrawer(${idx}, -1)" style="background: none; border: none; cursor: pointer; font-size: 16px; color: #9C9A96;">−</button>
                                <span style="font-size: 13px; font-family: 'DM Sans', sans-serif;">${item.quantity}</span>
                                <button onclick="window.cart.updateQuantityInDrawer(${idx}, 1)" style="background: none; border: none; cursor: pointer; font-size: 16px;">+</button>
                            </div>
                            <span style="font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500;">${window.cart.formatEGP(itemTotal)}</span>
                        </div>
                        <button onclick="window.cart.removeItemFromDrawer(${idx})" style="background: none; border: none; cursor: pointer; font-family: 'Space Grotesk', sans-serif; font-size: 9px; letter-spacing: 0.1em; text-transform: uppercase; color: #C0392B; margin-top: 12px; padding: 0;">Remove</button>
                    </div>
                </div>
            `;
        }).join('');

        if (cartDrawerSubtotal) cartDrawerSubtotal.textContent = window.cart.formatEGP(subtotal);
        if (cartDrawerCount) cartDrawerCount.textContent = itemCount;
    },

    updateQuantityInDrawer: (index, delta) => {
        if (index >= 0 && index < window.cart.items.length) {
            const newQuantity = window.cart.items[index].quantity + delta;
            if (newQuantity < 1) {
                window.cart.removeItem(index);
            } else {
                window.cart.items[index].quantity = newQuantity;
                window.cart.save();
            }
            window.cart.updateCartDrawer();
        }
    },

    removeItemFromDrawer: (index) => {
        window.cart.removeItem(index);
        window.cart.updateCartDrawer();
    },

    formatEGP: (value) => {
        return 'EGP ' + Math.round(value).toLocaleString('en-EG');
    },

    toggle: () => {
        const drawer = document.getElementById('cartDrawer');
        const overlay = document.getElementById('cartOverlay');
        drawer?.classList.toggle('open');
        overlay?.classList.toggle('open');
        document.body.style.overflow = drawer?.classList.contains('open') ? 'hidden' : '';
    }
};

// Auto-initialize cart when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.cart.init();
    window.cart.updateCartDrawer();
});
