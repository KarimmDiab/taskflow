<!-- CART OVERLAY + DRAWER -->
<div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>
<div class="cart-drawer" id="cartDrawer">
  <div style="display:flex;align-items:center;justify-content:space-between;padding:24px;border-bottom:1px solid #EDEDEB;">
    <span style="font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.15em;text-transform:uppercase;">Your Bag</span>
    <button onclick="toggleCart()" style="background:none;border:none;cursor:pointer;padding:4px;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
  </div>
  <div style="flex:1;overflow-y:auto;padding:24px;display:flex;align-items:center;justify-content:center;">
    <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9C9A96;text-align:center;">Your bag is empty</p>
  </div>
  <div style="padding:24px;border-top:1px solid #EDEDEB;">
    <a href="shop.html" style="display:flex;align-items:center;justify-content:center;gap:10px;background:#0A0A0A;color:#F8F6F2;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.12em;text-transform:uppercase;padding:14px;text-decoration:none;">Shop Now</a>
  </div>
</div>
