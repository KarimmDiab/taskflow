    const FREE_SHIPPING_THRESHOLD = 2000;
    let cartSubtotal = 0;

    function formatEGP(value) {
      return `EGP ${Math.round(value).toLocaleString('en-EG')}`;
    }

    function escapeHtml(str) {
      return String(str || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
    }

    function loadCheckoutCart() {
      try {
        if (!window.localStorage) return [];
        const parsed = JSON.parse(window.localStorage.getItem('ryo_cart') || '[]');
        if (!Array.isArray(parsed)) return [];

        return parsed
          .map(item => ({
            productName: item.productName || 'RYO Product',
            colorName: item.colorName || 'Default',
            sizeName: item.sizeName || 'OS',
            price: Number(item.price) || 0,
            quantity: Math.max(1, Number.parseInt(item.quantity, 10) || 1),
            imageUrl: item.imageUrl || '',
          }))
          .filter(item => item.quantity > 0);
      } catch (error) {
        if (window.localStorage) window.localStorage.setItem('ryo_cart', '[]');
        return [];
      }
    }

    function renderOrderSummary() {
      const cart = loadCheckoutCart();
      const itemsWrap = document.getElementById('orderItems');
      const placeOrderBtn = document.getElementById('placeOrderBtn');

      cartSubtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
      shippingCost = cart.length > 0 && cartSubtotal < FREE_SHIPPING_THRESHOLD ? 60 : 0;

      if (!itemsWrap) return;

      if (cart.length === 0) {
        itemsWrap.innerHTML = `
          <div style="text-align:center;padding:36px 16px;border:1px solid #D5D3CF;background:rgba(248,246,242,.55);">
            <p style="font-family:'Cormorant Garamond',serif;font-size:26px;font-weight:300;margin-bottom:8px;">Your bag is empty</p>
            <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-bottom:20px;">Add items to see your active order summary.</p>
            <a href="{{ route('all-products') }}" style="display:inline-flex;align-items:center;justify-content:center;background:#0A0A0A;color:#F8F6F2;font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.12em;text-transform:uppercase;padding:12px 20px;text-decoration:none;">Continue Shopping</a>
          </div>`;
        if (placeOrderBtn) {
          placeOrderBtn.disabled = true;
          placeOrderBtn.style.opacity = '.45';
          placeOrderBtn.style.cursor = 'not-allowed';
        }
      } else {
        itemsWrap.innerHTML = cart.map(item => `
          <div class="summary-item">
            <div class="summary-img">
              <img src="${escapeHtml(item.imageUrl)}" alt="${escapeHtml(item.productName)}" onerror="this.src='data:image/svg+xml;charset=UTF-8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2264%22 height=%2280%22%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22%23EDEDEB%22/%3E%3C/svg%3E'">
              <div class="item-qty-badge">${item.quantity}</div>
            </div>
            <div style="flex:1;">
              <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:400;margin-bottom:3px;">${escapeHtml(item.productName)}</p>
              <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-bottom:2px;">Size: ${escapeHtml(item.sizeName)} · ${escapeHtml(item.colorName)}</p>
              <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;">RYO Collection</p>
            </div>
            <span style="font-family:'DM Sans',sans-serif;font-size:13px;flex-shrink:0;">${formatEGP(item.price * item.quantity)}</span>
          </div>
        `).join('');
        if (placeOrderBtn) {
          placeOrderBtn.disabled = false;
          placeOrderBtn.style.opacity = '';
          placeOrderBtn.style.cursor = '';
        }
      }

      document.getElementById('subtotalDisplay').textContent = formatEGP(cartSubtotal);
      document.getElementById('shippingDisplay').textContent = shippingCost > 0 ? formatEGP(shippingCost) : 'Free';
      const standardShippingPrice = document.getElementById('std-price');
      if (standardShippingPrice) standardShippingPrice.textContent = shippingCost > 0 ? formatEGP(shippingCost) : 'Free';
      updateTotal();
    }
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'ryo-black': '#0A0A0A', 'ryo-white': '#F8F6F2',
            'ryo-gray-100': '#EDEDEB', 'ryo-gray-200': '#D5D3CF',
            'ryo-gray-400': '#9C9A96', 'ryo-gray-700': '#3D3D3A',
          },
          fontFamily: {
            display: ['Cormorant Garamond', 'serif'],
            body: ['DM Sans', 'sans-serif'],
            label: ['Space Grotesk', 'sans-serif'],
          }
        }
      }
    }
