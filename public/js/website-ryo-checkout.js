    // ── PAYMENT METHOD ──
    function selectPayment(type) {
      const options = { cod: 'pay-cod' };
      Object.values(options).forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('active');
        el.querySelector('.pay-radio-dot').style.opacity = '0';
      });
      const selected = document.getElementById(options[type]);
      if (selected) {
        selected.classList.add('active');
        selected.querySelector('.pay-radio-dot').style.opacity = '1';
      }

      const cardFields = document.getElementById('cardFields');
      const walletFields = document.getElementById('walletFields');
      if (cardFields) cardFields.style.display = type === 'card' ? 'block' : 'none';
      if (walletFields) walletFields.style.display = type === 'wallet' ? 'block' : 'none';
    }

    // ── SHIPPING METHOD ──
    let shippingCost = 0;
    function selectShipping(el, type, cost) {
      document.querySelectorAll('.shipping-opt').forEach(o => {
        o.classList.remove('active');
        o.querySelector('.pay-radio-dot').style.opacity = '0';
      });
      el.classList.add('active');
      el.querySelector('.pay-radio-dot').style.opacity = '1';
      shippingCost = cost;
      document.getElementById('shippingDisplay').textContent = cost > 0 ? `EGP ${cost}` : 'Free';
      updateTotal();
    }

    // ── PROMO ──
    let discount = 0;
    function togglePromo() {
      const wrap = document.getElementById('promoWrap');
      const chevron = document.getElementById('promoChevron');
      const isOpen = wrap.style.display !== 'none';
      wrap.style.display = isOpen ? 'none' : 'block';
      chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
    }
    function applyPromo() {
      const code = document.getElementById('promoCode').value.toUpperCase().trim();
      const msg = document.getElementById('promoMsg');
      if (code === 'RYO10') {
        discount = Math.round(cartSubtotal * 0.1);
        msg.textContent = '✓ RYO10 applied — 10% off';
        msg.style.color = '#0A0A0A';
        document.getElementById('discountRow').style.display = 'flex';
        document.getElementById('discountDisplay').textContent = `− ${formatEGP(discount)}`;
      } else if (code === '') {
        msg.textContent = 'Please enter a promo code.';
        msg.style.color = '#9C9A96';
      } else {
        msg.textContent = 'Invalid code. Try RYO10 for 10% off.';
        msg.style.color = '#c0392b';
        discount = 0;
        document.getElementById('discountRow').style.display = 'none';
      }
      updateTotal();
    }

    // ── TOTAL ──
    function updateTotal() {
      const total = Math.max(0, cartSubtotal + shippingCost - discount);
      document.getElementById('grandTotal').textContent = formatEGP(total);
      document.getElementById('totalInBtn').textContent = total.toLocaleString('en-EG');
    }

    // ── EXPIRY FORMAT ──
    function formatExpiry(input) {
      let val = input.value.replace(/\D/g, '');
      if (val.length > 2) val = val.slice(0, 2) + ' / ' + val.slice(2, 4);
      input.value = val;
    }

    // ── PLACE ORDER ──
    function placeOrder() {
      if (loadCheckoutCart().length === 0) return;
      const btn = document.getElementById('placeOrderBtn');
      btn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="animation:spin 1s linear infinite"><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" opacity=".3"/><path d="M21 12a9 9 0 00-9-9"/></svg> Processing...';
      btn.style.background = '#3D3D3A';
      btn.disabled = true;

      setTimeout(() => {
        document.getElementById('successOverlay').classList.add('show');
      }, 1800);
    }

    // ── SCROLL REVEAL ──
    const revObs = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revObs.unobserve(e.target); } });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(el => revObs.observe(el));

    // ── RESPONSIVE ──
    function checkLayout() {
      const layout = document.getElementById('checkoutLayout');
      if (window.innerWidth < 900) {
        layout.style.gridTemplateColumns = '1fr';
      } else {
        layout.style.gridTemplateColumns = '1fr 420px';
      }
    }
    renderOrderSummary();
    selectPayment('cod');
    checkLayout();
    window.addEventListener('storage', (event) => {
      if (event.key === 'ryo_cart') renderOrderSummary();
    });
    window.addEventListener('resize', checkLayout);

    // ── SPIN ANIMATION ──
    const style = document.createElement('style');
    style.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
    document.head.appendChild(style);
