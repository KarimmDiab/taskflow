<style>
    .cart-overlay {
        position: fixed;
        inset: 0;
        z-index: 199;
        background: rgba(10, 10, 10, 0.42);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.35s ease;
    }

    .cart-overlay.open {
        opacity: 1;
        pointer-events: all;
    }

    .cart-drawer {
        box-shadow: -24px 0 60px rgba(10, 10, 10, 0.14);
    }

    .cart-drawer__items::-webkit-scrollbar {
        width: 6px;
    }

    .cart-drawer__items::-webkit-scrollbar-thumb {
        background: #D5D3CF;
        border-radius: 999px;
    }

    .cart-drawer__close:hover {
        background: #EDEDEB;
        color: #0A0A0A;
    }

    .cart-drawer__checkout:hover {
        background: #242424 !important;
        transform: translateY(-1px);
    }

    .cart-drawer__continue:hover {
        color: #0A0A0A !important;
    }
</style>

<div class="cart-overlay" id="cartOverlay" onclick="window.cart.toggle()" aria-hidden="true"></div>

<div class="cart-drawer" id="cartDrawer" role="dialog" aria-modal="true" aria-label="Shopping bag">
    <div style="padding:22px 24px 18px;border-bottom:1px solid #EDEDEB;background:#FBF9F6;">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;">
            <div>
                <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.18em;text-transform:uppercase;color:#9C9A96;margin:0 0 5px;">
                    Shopping Bag
                </p>
                <h2 style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:300;line-height:1;margin:0;color:#0A0A0A;">
                    Your Bag <span style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9C9A96;">(<span id="cartDrawerCount">0</span>)</span>
                </h2>
            </div>
            <button class="cart-drawer__close" onclick="window.cart.toggle()" aria-label="Close cart"
                style="width:40px;height:40px;border:1px solid #EDEDEB;background:#F8F6F2;color:#3D3D3A;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s ease;">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M18 6L6 18M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0;margin-top:20px;border:1px solid #EDEDEB;background:#F8F6F2;">
            <div style="padding:10px 8px;text-align:center;border-right:1px solid #EDEDEB;">
                <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#3D3D3A;margin:0;">Secure checkout</p>
            </div>
            <div style="padding:10px 8px;text-align:center;border-right:1px solid #EDEDEB;">
                <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#3D3D3A;margin:0;">Fast delivery</p>
            </div>
            <div style="padding:10px 8px;text-align:center;">
                <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#3D3D3A;margin:0;">14-day returns</p>
            </div>
        </div>
    </div>

    <div id="cartItemsContainer" class="cart-drawer__items" style="flex:1;overflow-y:auto;padding:24px;background:#F8F6F2;">
        <!-- Cart items will be dynamically inserted here -->
    </div>

    <div style="padding:22px 24px 24px;border-top:1px solid #EDEDEB;background:#FBF9F6;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
            <span style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.16em;text-transform:uppercase;color:#9C9A96;">Subtotal</span>
            <span id="cartDrawerSubtotal" style="font-family:'DM Sans',sans-serif;font-size:18px;font-weight:500;color:#0A0A0A;">EGP 0</span>
        </div>
        <p style="font-family:'DM Sans',sans-serif;font-size:11px;line-height:1.6;color:#9C9A96;margin:0 0 18px;">Shipping, discounts, and taxes are calculated at checkout.</p>
        <a href="{{ route('checkout') }}" class="cart-drawer__checkout btn-primary"
            style="width:100%;justify-content:center;text-decoration:none;display:flex;background:#0A0A0A;color:#F8F6F2;padding:15px 18px;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:0.14em;text-transform:uppercase;align-items:center;gap:8px;transition:all .2s ease;">
            Checkout
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M5 12h14M12 5l7 7-7 7" />
            </svg>
        </a>
        <button class="cart-drawer__continue" onclick="window.cart.toggle()"
            style="width:100%;background:none;border:none;cursor:pointer;margin-top:12px;font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.15em;text-transform:uppercase;color:#9C9A96;padding:10px;transition:color .2s ease;">
            Continue Shopping
        </button>
    </div>
</div>
