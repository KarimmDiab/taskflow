  <!-- ═══════════════════════════════════════════
     CART OVERLAY + DRAWER
═══════════════════════════════════════════ -->
<div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>


<div class="cart-drawer" id="cartDrawer">
  <div class="flex items-center justify-between p-6 border-b border-ryo-gray-100">
    <span
      style="font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:0.15em;text-transform:uppercase;">Your
      Bag (2)</span>
    <button onclick="toggleCart()" style="background:none;border:none;cursor:pointer;padding:4px;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M18 6L6 18M6 6l12 12" />
      </svg>
    </button>
  </div>


  
  <div style="flex:1;overflow-y:auto;padding:24px;">
    <!-- Cart Item -->
    <div class="flex gap-4 pb-6 mb-6" style="border-bottom:1px solid #EDEDEB;">
      <div style="width:80px;height:100px;background:#EDEDEB;flex-shrink:0;overflow:hidden;">
        <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=200&q=80"
          style="width:100%;height:100%;object-fit:cover;">
      </div>
      <div style="flex:1;">
        <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:400;margin-bottom:4px;">Oversized
          Essential Tee</p>
        <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-bottom:2px;">Size: L</p>
        <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-bottom:16px;">Stone</p>
        <div style="display:flex;align-items:center;justify-content:space-between;">
          <div style="display:flex;align-items:center;gap:12px;border:1px solid #EDEDEB;padding:6px 14px;">
            <button
              style="background:none;border:none;cursor:pointer;font-size:16px;line-height:1;color:#9C9A96;">−</button>
            <span style="font-size:13px;font-family:'DM Sans',sans-serif;">1</span>
            <button style="background:none;border:none;cursor:pointer;font-size:16px;line-height:1;">+</button>
          </div>
          <span style="font-family:'DM Sans',sans-serif;font-size:13px;">EGP 2,200</span>
        </div>
      </div>
    </div>
    <!-- Cart Item 2 -->
    <div class="flex gap-4 pb-6">
      <div style="width:80px;height:100px;background:#EDEDEB;flex-shrink:0;overflow:hidden;">
        <img src="https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=200&q=80"
          style="width:100%;height:100%;object-fit:cover;">
      </div>
      <div style="flex:1;">
        <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:400;margin-bottom:4px;">Wide Leg Cargo
          Pant</p>
        <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-bottom:2px;">Size: 32</p>
        <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-bottom:16px;">Washed Black</p>
        <div style="display:flex;align-items:center;justify-content:space-between;">
          <div style="display:flex;align-items:center;gap:12px;border:1px solid #EDEDEB;padding:6px 14px;">
            <button
              style="background:none;border:none;cursor:pointer;font-size:16px;line-height:1;color:#9C9A96;">−</button>
            <span style="font-size:13px;font-family:'DM Sans',sans-serif;">1</span>
            <button style="background:none;border:none;cursor:pointer;font-size:16px;line-height:1;">+</button>
          </div>
          <span style="font-family:'DM Sans',sans-serif;font-size:13px;">EGP 3,800</span>
        </div>
      </div>
    </div>
  </div>

  <div style="padding:24px;border-top:1px solid #EDEDEB;">
    <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
      <span style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9C9A96;">Subtotal</span>
      <span style="font-family:'DM Sans',sans-serif;font-size:13px;">EGP 6,000</span>
    </div>
    <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;margin-bottom:20px;">Shipping calculated
      at checkout</p>
    <a href="checkout.html" class="btn-primary"
      style="width:100%;justify-content:center;text-decoration:none;display:flex;">
      Checkout
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M5 12h14M12 5l7 7-7 7" />
      </svg>
    </a>
    <button onclick="toggleCart()"
      style="width:100%;background:none;border:none;cursor:pointer;margin-top:12px;font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.15em;text-transform:uppercase;color:#9C9A96;padding:10px;">Continue
      Shopping</button>
  </div>
</div>
