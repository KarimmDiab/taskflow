<div class="cart-drawer" id="cartDrawer">
    <div class="flex items-center justify-between p-6 border-b border-ryo-gray-100">
        <span style="font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:0.15em;text-transform:uppercase;">
            Your Bag (<span id="cartDrawerCount">0</span>)
        </span>
        <button onclick="window.cart.toggle()" style="background:none;border:none;cursor:pointer;padding:4px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M18 6L6 18M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div id="cartItemsContainer" style="flex:1;overflow-y:auto;padding:24px;">
        <!-- Cart items will be dynamically inserted here -->
    </div>

    <div style="padding:24px;border-top:1px solid #EDEDEB;">
        <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
            <span style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9C9A96;">Subtotal</span>
            <span id="cartDrawerSubtotal" style="font-family:'DM Sans',sans-serif;font-size:13px;">EGP 0</span>
        </div>
        <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;margin-bottom:20px;">Shipping calculated at checkout</p>
        <a href="{{ route('checkout') }}" class="btn-primary" style="width:100%;justify-content:center;text-decoration:none;display:flex;background:#0A0A0A;color:white;padding:14px;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:0.12em;text-transform:uppercase;align-items:center;gap:8px;">
            Checkout
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M5 12h14M12 5l7 7-7 7" />
            </svg>
        </a>
        <button onclick="window.cart.toggle()" style="width:100%;background:none;border:none;cursor:pointer;margin-top:12px;font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.15em;text-transform:uppercase;color:#9C9A96;padding:10px;">
            Continue Shopping
        </button>
    </div>
</div>
