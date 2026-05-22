(function () {
    const STORAGE_KEY = 'ryo_cart';

    function loadCart() {
        try {
            const data = localStorage.getItem(STORAGE_KEY);
            if (!data) return [];
            const cart = JSON.parse(data);
            return Array.isArray(cart) ? cart : [];
        } catch (error) {
            return [];
        }
    }

    function formatEGP(value) {
        return 'EGP ' + Math.round(value).toLocaleString('en-EG');
    }

    function updateCartBadge(cart) {
        const totalItems = Array.isArray(cart)
            ? cart.reduce((sum, item) => sum + (Number(item.quantity) || 0), 0)
            : 0;
        const badge = document.getElementById('cartBadge');
        if (!badge) return;
        if (totalItems > 0) {
            badge.style.display = 'flex';
            badge.textContent = totalItems;
        } else {
            badge.style.display = 'none';
        }
    }

    function escapeHtml(value) {
        if (!value) return '';
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function updateCartDrawer(cart) {
        const container = document.getElementById('cartItemsContainer');
        const subtotalEl = document.getElementById('cartDrawerSubtotal');
        const countEl = document.getElementById('cartDrawerCount');

        if (!container) return;

        if (!Array.isArray(cart) || cart.length === 0) {
            container.innerHTML = `
                <div style="text-align:center;padding:48px 24px;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.2">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 01-8 0" />
                    </svg>
                    <p style="font-family:'DM Sans',sans-serif;font-size:14px;color:#9C9A96;margin-top:16px;">Your bag is empty</p>
                </div>
            `;
            if (subtotalEl) subtotalEl.textContent = 'EGP 0';
            if (countEl) countEl.textContent = '0';
            return;
        }

        let subtotal = 0;
        let count = 0;

        container.innerHTML = cart.map((item, index) => {
            const quantity = Number(item.quantity) || 1;
            const price = Number(item.price) || 0;
            const itemTotal = quantity * price;
            subtotal += itemTotal;
            count += quantity;

            return `
                <div style="display:flex;gap:16px;padding-bottom:20px;margin-bottom:20px;border-bottom:1px solid #EDEDEB;">
                    <div style="width:80px;height:100px;overflow:hidden;border-radius:4px;background:#EDEDEB;flex-shrink:0;">
                        <img src="${escapeHtml(item.imageUrl || '')}" alt="${escapeHtml(item.productName)}" style="width:100%;height:100%;object-fit:cover;" onerror="this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22130%22 height=%22173%22><rect width=%22100%25%22 height=%22100%25%22 fill=%22%23ddd%22/><text x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%23666%22>No Image</text></svg>'">
                    </div>
                    <div style="flex:1;">
                        <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:500;margin-bottom:6px;color:#0A0A0A;">${escapeHtml(item.productName)}</p>
                        <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;margin-bottom:4px;">${escapeHtml(item.colorName || 'Default')}</p>
                        <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;margin-bottom:12px;">Size: ${escapeHtml(item.sizeName || 'OS')}</p>
                        <div style="display:flex;align-items:center;justify-content:space-between;">
                            <div style="display:flex;align-items:center;gap:12px;border:1px solid #EDEDEB;padding:4px 12px;">
                                <button onclick="window.cart.changeQty(${index}, -1)" style="background:none;border:none;cursor:pointer;font-size:14px;color:#9C9A96;">−</button>
                                <span style="font-size:12px;font-family:'DM Sans',sans-serif;">${quantity}</span>
                                <button onclick="window.cart.changeQty(${index}, 1)" style="background:none;border:none;cursor:pointer;font-size:14px;">+</button>
                            </div>
                            <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:500;">${formatEGP(itemTotal)}</span>
                        </div>
                        <button onclick="window.cart.removeItem(${index})" style="background:none;border:none;cursor:pointer;font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:0.1em;text-transform:uppercase;color:#C0392B;margin-top:12px;padding:0;">Remove</button>
                    </div>
                </div>
            `;
        }).join('');

        if (subtotalEl) subtotalEl.textContent = formatEGP(subtotal);
        if (countEl) countEl.textContent = String(count);
    }

    function saveCart(cart) {
        if (!Array.isArray(cart)) return;
        localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
        const normalized = cart.map((item) => ({
            ...item,
            price: Number(item.price) || 0,
            quantity: Math.max(1, Number(item.quantity) || 1),
        }));
        updateCartBadge(normalized);
        updateCartDrawer(normalized);
    }

    function toggleCart() {
        document.getElementById('cartDrawer')?.classList.toggle('open');
        document.getElementById('cartOverlay')?.classList.toggle('open');
    }

    function getCart() {
        return loadCart().map((item) => ({
            ...item,
            price: Number(item.price) || 0,
            quantity: Math.max(1, Number(item.quantity) || 1),
        }));
    }

    function changeQty(index, delta) {
        const cart = getCart();
        if (!cart[index]) return;
        cart[index].quantity = Math.max(1, cart[index].quantity + delta);
        saveCart(cart);
    }

    function removeItem(index) {
        const cart = getCart();
        if (!cart[index]) return;
        cart.splice(index, 1);
        saveCart(cart);
    }

    function updateDrawer() {
        const cart = getCart();
        updateCartBadge(cart);
        updateCartDrawer(cart);
    }

    function init() {
        updateDrawer();
        window.addEventListener('storage', updateDrawer);
        window.toggleCart = toggleCart;
        window.toggleMenu = window.toggleMenu || function () {
            document.getElementById('mobileMenu')?.classList.toggle('open');
        };
        window.cart = window.cart || {};
        window.cart.toggle = toggleCart;
        window.cart.changeQty = window.cart.changeQty || changeQty;
        window.cart.removeItem = window.cart.removeItem || removeItem;
        window.cart.saveItems = window.cart.saveItems || saveCart;
        window.cart.getItems = window.cart.getItems || getCart;
        window.cart.updateCartDrawer = window.cart.updateCartDrawer || updateDrawer;
        window.cart.updateBadge = window.cart.updateBadge || updateCartBadge;
    }

    document.addEventListener('DOMContentLoaded', init);
})();
