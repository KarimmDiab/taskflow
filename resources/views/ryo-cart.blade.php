<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RYO - Your Bag</title>
    <meta name="description" content="Review your RYO bag and proceed to checkout.">
    <link href="{{ asset('css/website.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/website-cart.css') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=DM+Sans:wght@300;400;500&family=Space+Grotesk:wght@400;500&display=swap"
        rel="stylesheet">
    <script src="{{ asset('js/tailwind.js') }}"></script>

    <style>
        .cart-item {
            display: grid;
            grid-template-columns: 120px 1fr auto;
            gap: 28px;
            padding: 28px 0;
            border-bottom: 1px solid #EDEDEB;
            transition: opacity 0.35s ease, transform 0.35s ease;
        }
        .cart-item.removing {
            opacity: 0;
            transform: translateX(-20px);
        }
        .item-img {
            width: 120px;
            aspect-ratio: 3/4;
            overflow: hidden;
            background: #EDEDEB;
        }
        .item-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .chip {
            display: inline-block;
            background: #EDEDEB;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 9px;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: 5px 10px;
            margin-right: 8px;
            margin-bottom: 6px;
            color: #3D3D3A;
        }
        .qty-control {
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid #D5D3CF;
            padding: 6px 14px;
        }
        .qty-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #9C9A96;
            transition: color 0.2s;
        }
        .qty-btn:hover {
            color: #0A0A0A;
        }
        .qty-num {
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            min-width: 20px;
            text-align: center;
        }
        .remove-btn {
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 9px;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: #9C9A96;
            transition: color 0.2s;
            padding: 0;
        }
        .remove-btn:hover {
            color: #C0392B;
        }
        .progress-track {
            background: #E0DCD5;
            height: 3px;
            border-radius: 3px;
            overflow: hidden;
        }
        .progress-fill {
            background: #0A0A0A;
            height: 100%;
            width: 0%;
            transition: width 0.4s ease;
        }
        .continue-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 10px;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: #0A0A0A;
            text-decoration: none;
            border-bottom: 1px solid #D5D3CF;
            padding-bottom: 4px;
        }
        .checkout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            background: #0A0A0A;
            color: #F8F6F2;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 11px;
            letter-spacing: .12em;
            text-transform: uppercase;
            padding: 16px;
            text-decoration: none;
            transition: background 0.3s;
        }
        .checkout-btn:hover {
            background: #3D3D3A;
        }
        .promo-wrap {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }
        .promo-input {
            flex: 1;
            background: transparent;
            border: 1px solid #D5D3CF;
            padding: 10px 12px;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            outline: none;
        }
        .promo-btn {
            background: transparent;
            border: 1px solid #0A0A0A;
            padding: 10px 16px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 9px;
            letter-spacing: .12em;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s;
        }
        .promo-btn:hover {
            background: #0A0A0A;
            color: white;
        }
        .mobile-summary-sticky {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 16px 20px;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 300;
            border-top: 1px solid #EDEDEB;
        }
        .upsell-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border: 1px solid #EDEDEB;
            cursor: pointer;
            transition: all 0.2s;
        }
        .upsell-card:hover {
            border-color: #D5D3CF;
            background: #FBF9F6;
        }
        .upsell-img {
            width: 56px;
            height: 56px;
            overflow: hidden;
            flex-shrink: 0;
            background: #EDEDEB;
        }
        .upsell-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .upsell-add {
            background: transparent;
            border: 1px solid #D5D3CF;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .upsell-add:hover {
            border-color: #0A0A0A;
        }
        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        @media (max-width: 768px) {
            .cart-item {
                grid-template-columns: 90px 1fr;
                gap: 16px;
            }
            .cart-item > div:last-child {
                display: none;
            }
            .item-img {
                width: 90px;
            }
        }
    </style>
</head>

<body>

    <!-- TOAST -->
    <div id="toast" style="position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(80px);background:#0A0A0A;color:white;padding:12px 20px;border-radius:40px;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.08em;display:flex;align-items:center;gap:12px;z-index:500;opacity:0;transition:all 0.25s ease;pointer-events:none;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <polyline points="20 6 9 17 4 12" />
        </svg>
        <span id="toastMsg">Item added to bag</span>
    </div>

    {{-- Navbar --}}
    @include('partials.nav-bar2')



    <!-- PAGE HEADER -->
    <div style="padding:40px 40px 0;max-width:1440px;margin:0 auto;">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;">
            <a href="{{ route('home') }}" style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;text-decoration:none;">Home</a>
            <span style="color:#D5D3CF;font-size:11px;">/</span>
            <span style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:#0A0A0A;">Your Bag</span>
        </div>
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:0;flex-wrap:wrap;gap:12px;">
            <div>
                <h1 style="font-family:'Cormorant Garamond',serif;font-size:clamp(40px,5vw,72px);font-weight:300;letter-spacing:-.02em;line-height:.95;">Your Bag</h1>
            </div>
            <a href="{{ route('all-products') }}" class="continue-link">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M19 12H5M12 5l-7 7 7 7" />
                </svg>
                Continue Shopping
            </a>
        </div>
    </div>

    <!-- FREE SHIPPING PROGRESS BAR -->
    <div style="padding:20px 40px 0;max-width:1440px;margin:0 auto;" id="freeShippingBar">
        <div style="background:#F2EEE8;padding:14px 20px;display:flex;align-items:center;gap:16px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5" style="flex-shrink:0;">
                <path d="M5 12h14" />
                <path d="M12 5l7 7-7 7" />
            </svg>
            <div style="flex:1;">
                <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:#3D3D3A;margin-bottom:6px;" id="shippingMsg">
                    Add items to unlock free shipping!
                </p>
                <div class="progress-track">
                    <div class="progress-fill" id="progressFill" style="width:0%;"></div>
                </div>
            </div>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5" style="flex-shrink:0;">
                <rect x="1" y="3" width="15" height="13" />
                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                <circle cx="5.5" cy="18.5" r="2.5" />
                <circle cx="18.5" cy="18.5" r="2.5" />
            </svg>
        </div>
    </div>

    <!-- MAIN CART LAYOUT -->
    <div style="max-width:1440px;margin:0 auto;padding:32px 40px 160px;display:grid;grid-template-columns:1fr 380px;gap:64px;align-items:start;" id="cartLayout">

        <!-- LEFT: CART ITEMS -->
        <div>
            <div style="display:grid;grid-template-columns:120px 1fr auto;gap:28px;padding-bottom:16px;border-bottom:2px solid #0A0A0A;" class="hidden md:grid">
                <span style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.18em;text-transform:uppercase;color:#9C9A96;">Product</span>
                <span style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.18em;text-transform:uppercase;color:#9C9A96;"></span>
                <span style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.18em;text-transform:uppercase;color:#9C9A96;text-align:right;">Total</span>
            </div>

            <div id="cartItemsContainer">
                <!-- Cart items will be dynamically loaded here -->
            </div>


            <!-- EMPTY STATE -->
            <div class="empty-state" id="emptyState" style="display:none; text-align:center; padding:60px 20px;">
                <div style="width:64px;height:64px;border:1px solid #EDEDEB;display:flex;align-items:center;justify-content:center;margin:0 auto 28px auto;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 01-8 0" />
                    </svg>
                </div>
                <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;margin-bottom:16px;">Your bag is empty</p>
                <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,4vw,44px);font-weight:300;margin-bottom:16px;line-height:1.1;">Nothing here <em>yet</em></h2>
                <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#9C9A96;margin-bottom:40px;max-width:360px;line-height:1.7;margin-left:auto;margin-right:auto;">Looks like you haven't added anything to your bag. Explore our latest collection and find something you love.</p>
                <a href="{{ route('all-products') }}" style="display:inline-flex;align-items:center;gap:10px;background:#0A0A0A;color:#F8F6F2;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.14em;text-transform:uppercase;padding:14px 32px;text-decoration:none;">
                    Shop Collection
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <!-- YOU MAY ALSO LIKE (upsell) -->
            <div id="upsellSection" style="padding-top:48px;margin-top:16px;border-top:1px solid #EDEDEB; display:block;">
                <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:#9C9A96;margin-bottom:20px;">Complete Your Look</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;" id="upsellGrid">
                    <div class="upsell-card" data-upsell-name="Utility Coach Jacket" data-upsell-price="5500" data-upsell-img="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=300&q=80">
                        <div class="upsell-img"><img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=300&q=80" alt="Coach Jacket"></div>
                        <div style="flex:1;min-width:0;"><p style="font-family:'DM Sans',sans-serif;font-size:12px;font-weight:400;margin-bottom:3px;">Utility Coach Jacket</p><p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;">Olive / Black</p><p style="font-family:'DM Sans',sans-serif;font-size:12px;">EGP 5,500</p></div>
                        <button class="upsell-add" onclick="event.stopPropagation(); addUpsellItem(this)"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
                    </div>
                    <div class="upsell-card" data-upsell-name="Washed Crewneck Sweat" data-upsell-price="3400" data-upsell-img="https://images.unsplash.com/photo-1594938298603-f4d8c9a3a9a4?w=300&q=80">
                        <div class="upsell-img"><img src="https://images.unsplash.com/photo-1594938298603-f4d8c9a3a9a4?w=300&q=80" alt="Crewneck"></div>
                        <div style="flex:1;min-width:0;"><p style="font-family:'DM Sans',sans-serif;font-size:12px;font-weight:400;margin-bottom:3px;">Washed Crewneck Sweat</p><p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;">Faded Grey</p><p style="font-family:'DM Sans',sans-serif;font-size:12px;">EGP 3,400</p></div>
                        <button class="upsell-add" onclick="event.stopPropagation(); addUpsellItem(this)"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: ORDER SUMMARY -->
        <div style="position:sticky;top:88px;" class="reveal">
            <div style="border:1px solid #EDEDEB;background:#F2EEE8;padding:32px;">
                <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid #D5D3CF;">Order Summary</p>
                <div style="display:flex;justify-content:space-between;margin-bottom:14px;">
                    <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;" id="itemCountLabel">0 items</span>
                    <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;" id="subtotalDisplay">EGP 0</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:14px;">
                    <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">Shipping</span>
                    <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;" id="shippingDisplay">—</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:14px; display:none;" id="discountRow">
                    <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">Discount</span>
                    <span style="font-family:'DM Sans',sans-serif;font-size:13px;" id="discountDisplay">— EGP 0</span>
                </div>
                <div style="height:1px;background:#D5D3CF;margin:16px 0;"></div>
                <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:28px;">
                    <span style="font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.15em;text-transform:uppercase;">Total</span>
                    <div style="text-align:right;">
                        <p style="font-family:'Cormorant Garamond',serif;font-size:32px;font-weight:300;" id="totalDisplay">EGP 0</p>
                        <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;">VAT included</p>
                    </div>
                </div>
                <div style="margin-bottom:20px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;cursor:pointer;" onclick="togglePromo()">
                        <div style="display:flex;align-items:center;gap:6px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg><span style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:#9C9A96;">Promo Code</span></div>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5" id="promoChevron" style="transition:transform .3s ease;"><path d="M6 9l6 6 6-6"/></svg>
                    </div>
                    <div id="promoWrap" style="display:none;">
                        <div class="promo-wrap"><input class="promo-input" type="text" placeholder="Enter promo code" id="promoCode"><button class="promo-btn" onclick="applyPromo()">Apply</button></div>
                        <p id="promoMsg" style="font-family:'DM Sans',sans-serif;font-size:11px;margin-top:8px;"></p>
                    </div>
                </div>
                <a href="{{ route('checkout') }}" class="checkout-btn" id="checkoutBtn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Secure Checkout</a>
                <div style="display:flex;align-items:center;gap:12px;margin:16px 0;"><div style="flex:1;height:1px;background:#D5D3CF;"></div><span style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;">or</span><div style="flex:1;height:1px;background:#D5D3CF;"></div></div>
                <a href="{{ route('checkout') }}" style="display:flex;align-items:center;justify-content:center;gap:10px;border:1px solid #D5D3CF;color:#0A0A0A;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.12em;text-transform:uppercase;padding:14px;text-decoration:none;">Continue as Guest</a>
                <div style="margin-top:20px;padding-top:16px;border-top:1px solid #D5D3CF;"><div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg><span style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;">SSL Encrypted · Secure Payment</span></div><div style="display:flex;align-items:center;gap:8px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 014-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 01-4 4H3"/></svg><span style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;">Free returns within 14 days</span></div></div>
            </div>
        </div>
    </div>

    <!-- MOBILE STICKY CHECKOUT BAR -->
    <div class="mobile-summary-sticky md:hidden" id="mobileStickyBar" style="display:none;">
        <div><p style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.14em;text-transform:uppercase;color:#9C9A96;margin-bottom:2px;" id="mobileItemCount">0 items</p><p style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:300;" id="mobileTotalDisplay">EGP 0</p></div>
        <a href="{{ route('checkout') }}" style="background:#0A0A0A;color:#F8F6F2;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.12em;text-transform:uppercase;padding:14px 24px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;">Checkout<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
    </div>

    @include('partials.newslater')
    @include('partials.footer')



<script>
    // ═══════════════════════════════════════════════════════════════
    // CART CORE FUNCTIONS
    // ═══════════════════════════════════════════════════════════════

    const FREE_SHIPPING_THRESHOLD = 1500;
    let discount = 0;
    let currentPromoCode = '';

    // Load cart from localStorage
    function loadCart() {
        const savedCart = localStorage.getItem('ryo_cart');
        if (savedCart) {
            return JSON.parse(savedCart);
        }
        return [];
    }

    // Save cart to localStorage
    function saveCart(cart) {
        localStorage.setItem('ryo_cart', JSON.stringify(cart));
        updateCartBadge(cart);
    }

    // Update cart badge in navbar
    function updateCartBadge(cart) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        const cartBadge = document.getElementById('cartBadge');
        if (cartBadge) {
            if (totalItems > 0) {
                cartBadge.style.display = 'flex';
                cartBadge.textContent = totalItems;
            } else {
                cartBadge.style.display = 'none';
            }
        }
    }

    // Format EGP
    function formatEGP(value) {
        return 'EGP ' + Math.round(value).toLocaleString('en-EG');
    }

    // Helper function to escape HTML to prevent XSS
    function escapeHtml(str) {
        if (!str) return '';
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    // Show toast message
    function showToastMessage(msg, isError = false) {
        const toast = document.getElementById('toast');
        const toastMsg = document.getElementById('toastMsg');
        if (toastMsg) toastMsg.textContent = msg;
        if (toast) {
            toast.style.background = isError ? '#C0392B' : '#0A0A0A';
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(-50%) translateY(0)';
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(-50%) translateY(80px)';
                setTimeout(() => {
                    toast.style.background = '#0A0A0A';
                }, 500);
            }, 3000);
        }
    }

    // Update cart item quantity
    function updateCartItemQuantity(index, delta) {
        const cart = loadCart();
        if (!cart[index]) return;

        const newQty = cart[index].quantity + delta;
        if (newQty < 1) {
            if (confirm('Remove this item from your bag?')) {
                cart.splice(index, 1);
                showToastMessage('Item removed from bag');
            } else {
                return;
            }
        } else if (newQty > 10) {
            showToastMessage('Maximum 10 items per product', true);
            return;
        } else {
            cart[index].quantity = newQty;
        }

        saveCart(cart);
        renderCart();
        updateSummary(cart);
    }

    // Remove cart item
    function removeCartItem(index) {
        if (confirm('Remove this item from your bag?')) {
            const cart = loadCart();
            cart.splice(index, 1);
            saveCart(cart);
            renderCart();
            updateSummary(cart);
            showToastMessage('Item removed from bag');
        }
    }

    // Save for later function
    function saveForLater(index) {
        const cart = loadCart();
        const item = cart[index];
        if (item) {
            showToastMessage(item.productName + ' saved for later');
            removeCartItem(index);
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // RENDER CART FUNCTION (ENHANCED DESIGN)
    // ═══════════════════════════════════════════════════════════════

    function renderCart() {
        const cart = loadCart();
        const container = document.getElementById('cartItemsContainer');
        const emptyState = document.getElementById('emptyState');
        const upsellSection = document.getElementById('upsellSection');
        const freeShippingBar = document.getElementById('freeShippingBar');
        const mobileStickyBar = document.getElementById('mobileStickyBar');
        const columnHeaders = document.querySelector('.md\\:grid');

        if (!container) {
            console.error('cartItemsContainer NOT FOUND!');
            return;
        }

        // Style the container
        container.style.display = 'block';
        container.style.maxWidth = '100%';
        container.style.margin = '0 auto';

        if (!cart || cart.length === 0) {
            container.innerHTML = `
                <div class="empty-cart-animation" style="text-align: center; padding: 80px 40px; background: #FBF9F6; border-radius: 16px; margin: 20px 0;">
                    <div style="width: 80px; height: 80px; margin: 0 auto 24px; background: #EDEDEB; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.2">
                            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <path d="M16 10a4 4 0 01-8 0" />
                        </svg>
                    </div>
                    <p style="font-family: 'Cormorant Garamond', serif; font-size: 28px; font-weight: 300; color: #0A0A0A; margin-bottom: 12px;">Your bag is empty</p>
                    <p style="font-family: 'DM Sans', sans-serif; font-size: 14px; color: #9C9A96; margin-bottom: 32px;">Add something special to your bag</p>
                    <a href="{{ route('all-products') }}" style="display: inline-flex; align-items: center; gap: 10px; background: #0A0A0A; color: white; font-family: 'Space Grotesk', sans-serif; font-size: 11px; letter-spacing: .14em; text-transform: uppercase; padding: 14px 32px; text-decoration: none; transition: background 0.3s;" onmouseover="this.style.background='#3D3D3A'" onmouseout="this.style.background='#0A0A0A'">
                        Continue Shopping
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            `;
            if (emptyState) emptyState.style.display = 'flex';
            if (upsellSection) upsellSection.style.display = 'none';
            if (freeShippingBar) freeShippingBar.style.display = 'none';
            if (mobileStickyBar) mobileStickyBar.style.display = 'none';
            if (columnHeaders) columnHeaders.style.display = 'none';
            updateSummary(cart);
            return;
        }

        if (emptyState) emptyState.style.display = 'none';
        if (upsellSection) upsellSection.style.display = 'block';
        if (freeShippingBar) freeShippingBar.style.display = 'block';
        if (mobileStickyBar) mobileStickyBar.style.display = 'flex';
        if (columnHeaders) columnHeaders.style.display = 'grid';

        // Build enhanced HTML
        let htmlString = '';

        cart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            const productUrl = `/product/${item.variantId || item.sku || index}`;

            htmlString += `
                <div class="cart-item-enhanced" data-index="${index}" style="display: flex; gap: 24px; padding: 28px 0; border-bottom: 1px solid #EDEDEB; transition: all 0.3s ease; animation: fadeInUp 0.5s ease;">
                    <!-- Product Image -->
                    <div style="width: 130px; flex-shrink: 0;">
                        <a href="${productUrl}" style="display: block; overflow: hidden; border-radius: 12px; transition: transform 0.3s;">
                            <img src="${item.imageUrl}" alt="${item.productName}" style="width: 100%; aspect-ratio: 3/4; object-fit: cover; transition: transform 0.3s;" onerror="this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22130%22 height=%22173%22><rect width=%22100%25%22 height=%22100%25%22 fill=%22%23ddd%22/><text x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%23666%22>No Image</text></svg>'">
                        </a>
                    </div>

                    <!-- Product Details -->
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                            <div>
                                <p style="font-family: 'Space Grotesk', sans-serif; font-size: 10px; letter-spacing: .15em; text-transform: uppercase; color: #9C9A96; margin-bottom: 6px;">RYO · SS25</p>
                                <a href="${productUrl}" style="font-family: 'Cormorant Garamond', serif; font-size: 20px; font-weight: 400; color: #0A0A0A; text-decoration: none; display: block; margin-bottom: 8px; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">${escapeHtml(item.productName)}</a>
                            </div>
                            <p style="font-family: 'DM Sans', sans-serif; font-size: 18px; font-weight: 500; color: #0A0A0A; margin: 0;" class="md:hidden">${formatEGP(itemTotal)}</p>
                        </div>

                        <!-- Variants -->
                        <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px;">
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: #EDEDEB; font-family: 'Space Grotesk', sans-serif; font-size: 9px; letter-spacing: .1em; text-transform: uppercase; padding: 6px 12px; color: #3D3D3A; border-radius: 20px;">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                                ${escapeHtml(item.colorName || 'Default')}
                            </span>
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: #EDEDEB; font-family: 'Space Grotesk', sans-serif; font-size: 9px; letter-spacing: .1em; text-transform: uppercase; padding: 6px 12px; color: #3D3D3A; border-radius: 20px;">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                Size: ${escapeHtml(item.sizeName || 'OS')}dsadsa
                            </span>
                        </div>

                        <!-- Actions -->
                        <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 8px; border: 1px solid #D5D3CF; padding: 6px 14px; background: white; border-radius: 40px;">
                                <button onclick="updateCartItemQuantity(${index}, -1)" style="background: none; border: none; cursor: pointer; font-size: 18px; color: #9C9A96; padding: 4px 8px; transition: color 0.2s;" onmouseover="this.style.color='#0A0A0A'" onmouseout="this.style.color='#9C9A96'">−</button>
                                <span style="font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 500; min-width: 30px; text-align: center;">${item.quantity}</span>
                                <button onclick="updateCartItemQuantity(${index}, 1)" style="background: none; border: none; cursor: pointer; font-size: 18px; color: #9C9A96; padding: 4px 8px; transition: color 0.2s;" onmouseover="this.style.color='#0A0A0A'" onmouseout="this.style.color='#9C9A96'">+</button>
                            </div>
                            <button onclick="removeCartItem(${index})" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px; font-family: 'Space Grotesk', sans-serif; font-size: 10px; letter-spacing: .14em; text-transform: uppercase; color: #9C9A96; padding: 8px 12px; transition: all 0.2s; border-radius: 40px;" onmouseover="this.style.color='#C0392B'; this.style.background='#FFF5F5'" onmouseout="this.style.color='#9C9A96'; this.style.background='transparent'">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
                                </svg>
                                Remove
                            </button>
                            <button onclick="saveForLater(${index})" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px; font-family: 'Space Grotesk', sans-serif; font-size: 10px; letter-spacing: .14em; text-transform: uppercase; color: #9C9A96; padding: 8px 12px; transition: all 0.2s; border-radius: 40px;" onmouseover="this.style.color='#0A0A0A'; this.style.background='#EDEDEB'" onmouseout="this.style.color='#9C9A96'; this.style.background='transparent'">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
                                </svg>
                                Save for Later
                            </button>
                        </div>
                    </div>

                    <!-- Desktop Price -->
                    <div style="text-align: right; min-width: 120px;" class="hidden md:block">
                        <p style="font-family: 'Cormorant Garamond', serif; font-size: 24px; font-weight: 500; color: #0A0A0A; margin: 0;">${formatEGP(itemTotal)}</p>
                        <p style="font-family: 'DM Sans', sans-serif; font-size: 12px; color: #9C9A96; margin: 4px 0 0;">${formatEGP(item.price)} each</p>
                    </div>
                </div>
            `;
        });

        container.innerHTML = htmlString;

        // Add animation styles if not already present
        if (!document.getElementById('cart-animation-styles')) {
            const style = document.createElement('style');
            style.id = 'cart-animation-styles';
            style.textContent = `
                @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                .cart-item-enhanced {
                    animation: fadeInUp 0.5s ease forwards;
                }
                .cart-item-enhanced:hover {
                    background: #FBF9F6;
                    margin: 0 -10px;
                    padding-left: 10px;
                    padding-right: 10px;
                    border-radius: 16px;
                    transition: all 0.3s ease;
                }
            `;
            document.head.appendChild(style);
        }

        updateSummary(cart);
    }

    // ═══════════════════════════════════════════════════════════════
    // UPDATE SUMMARY FUNCTION
    // ═══════════════════════════════════════════════════════════════

    function updateSummary(cart) {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const itemCount = cart.reduce((sum, item) => sum + item.quantity, 0);
        const shipping = subtotal >= FREE_SHIPPING_THRESHOLD ? 0 : 60;
        const total = subtotal + shipping - discount;

        const subtotalEl = document.getElementById('subtotalDisplay');
        const shippingEl = document.getElementById('shippingDisplay');
        const totalEl = document.getElementById('totalDisplay');
        const itemCountLabel = document.getElementById('itemCountLabel');

        if (subtotalEl) subtotalEl.textContent = formatEGP(subtotal);
        if (shippingEl) shippingEl.textContent = shipping === 0 ? 'Free' : 'EGP 60';
        if (totalEl) totalEl.textContent = formatEGP(total);
        if (itemCountLabel) itemCountLabel.textContent = itemCount + ' item' + (itemCount !== 1 ? 's' : '');

        // Mobile bar
        const mobileTotal = document.getElementById('mobileTotalDisplay');
        const mobileCount = document.getElementById('mobileItemCount');
        if (mobileTotal) mobileTotal.textContent = formatEGP(total);
        if (mobileCount) mobileCount.textContent = itemCount + ' item' + (itemCount !== 1 ? 's' : '');

        // Free shipping bar
        const remaining = Math.max(0, FREE_SHIPPING_THRESHOLD - subtotal);
        const pct = Math.min(100, (subtotal / FREE_SHIPPING_THRESHOLD) * 100);
        const progressFill = document.getElementById('progressFill');
        const shippingMsg = document.getElementById('shippingMsg');
        if (progressFill) progressFill.style.width = pct + '%';
        if (shippingMsg) {
            if (remaining === 0) {
                shippingMsg.innerHTML = '<strong>🎉 You\'ve unlocked free shipping!</strong>';
            } else {
                shippingMsg.innerHTML = 'You\'re <strong>EGP ' + remaining.toLocaleString('en-EG') + '</strong> away from free shipping!';
            }
        }

        // Discount row
        const discountRow = document.getElementById('discountRow');
        const discountDisplay = document.getElementById('discountDisplay');
        if (discount > 0) {
            if (discountRow) discountRow.style.display = 'flex';
            if (discountDisplay) discountDisplay.textContent = '− ' + formatEGP(discount);
        } else {
            if (discountRow) discountRow.style.display = 'none';
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // PROMO FUNCTIONS
    // ═══════════════════════════════════════════════════════════════

    function togglePromo() {
        const wrap = document.getElementById('promoWrap');
        const chevron = document.getElementById('promoChevron');
        const isOpen = wrap && wrap.style.display !== 'none';
        if (wrap) wrap.style.display = isOpen ? 'none' : 'block';
        if (chevron) chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
    }

    function applyPromo() {
        const promoInput = document.getElementById('promoCode');
        const msg = document.getElementById('promoMsg');
        if (!promoInput) return;

        const code = promoInput.value.toUpperCase().trim();
        const cart = loadCart();
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

        if (code === 'RYO10') {
            discount = Math.round(subtotal * 0.1);
            currentPromoCode = 'RYO10';
            if (msg) { msg.textContent = '✓ RYO10 applied — 10% off your order'; msg.style.color = '#0A0A0A'; }
            showToastMessage('Promo code applied! 10% off');
        } else if (code === 'FREESHIP') {
            discount = 60;
            currentPromoCode = 'FREESHIP';
            if (msg) { msg.textContent = '✓ FREESHIP applied — free shipping discount'; msg.style.color = '#0A0A0A'; }
            showToastMessage('Promo code applied! Free shipping');
        } else if (code === '') {
            if (msg) { msg.textContent = 'Please enter a promo code.'; msg.style.color = '#9C9A96'; }
            return;
        } else {
            if (msg) { msg.textContent = 'Invalid code. Try RYO10 or FREESHIP.'; msg.style.color = '#c0392b'; }
            showToastMessage('Invalid promo code', true);
            discount = 0;
            currentPromoCode = '';
        }
        updateSummary(cart);
    }

    // ═══════════════════════════════════════════════════════════════
    // UPSELL FUNCTIONS
    // ═══════════════════════════════════════════════════════════════

    function addUpsellItem(btnElement) {
        const card = btnElement.closest('.upsell-card');
        if (!card) return;

        const name = card.dataset.upsellName;
        const price = parseInt(card.dataset.upsellPrice);
        const img = card.dataset.upsellImg;

        const cart = loadCart();
        const existingIndex = cart.findIndex(item => item.productName === name);

        if (existingIndex !== -1) {
            cart[existingIndex].quantity += 1;
            showToastMessage(name + ' quantity updated');
        } else {
            cart.push({
                variantId: Date.now(),
                productName: name,
                colorName: 'Default',
                sizeName: 'One Size',
                price: price,
                quantity: 1,
                imageUrl: img
            });
            showToastMessage(name + ' added to bag');
        }

        saveCart(cart);
        renderCart();
        updateSummary(cart);

        // Button feedback
        btnElement.innerHTML = '✓';
        btnElement.style.background = '#2C5F2D';
        btnElement.style.borderColor = '#2C5F2D';
        setTimeout(() => {
            btnElement.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>';
            btnElement.style.background = '';
            btnElement.style.borderColor = '#D5D3CF';
        }, 2000);
    }

    // ═══════════════════════════════════════════════════════════════
    // RESPONSIVE LAYOUT
    // ═══════════════════════════════════════════════════════════════

    function checkLayout() {
        const layout = document.getElementById('cartLayout');
        if (layout) {
            if (window.innerWidth < 900) {
                layout.style.gridTemplateColumns = '1fr';
                layout.style.gap = '0';
            } else {
                layout.style.gridTemplateColumns = '1fr 380px';
                layout.style.gap = '64px';
            }
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // DEBUG FUNCTIONS (Optional)
    // ═══════════════════════════════════════════════════════════════

    function debugCart() {
        const cart = loadCart();
        console.log('Cart contents:', cart);
        console.log('Cart length:', cart.length);
        const rawCart = localStorage.getItem('ryo_cart');
        console.log('Raw localStorage data:', rawCart);
        return cart;
    }

    function addTestItem() {
        const testItem = {
            variantId: 999,
            productName: 'Oversized Essential Tee - Test',
            colorName: 'Stone',
            sizeName: 'L',
            price: 1000,
            quantity: 2,
            imageUrl: 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=300&q=80'
        };
        const cart = loadCart();
        cart.push(testItem);
        saveCart(cart);
        renderCart();
        updateSummary(cart);
        console.log('Test item added to cart, cart now has', cart.length, 'items');
    }

    // ═══════════════════════════════════════════════════════════════
    // INITIALIZATION
    // ═══════════════════════════════════════════════════════════════

    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded - initializing cart page');

        renderCart();
        checkLayout();

        window.addEventListener('resize', checkLayout);

        // Scroll reveal for existing elements
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    revealObserver.unobserve(e.target);
                }
            });
        }, { threshold: 0.08 });
        document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));
    });

    // Make functions globally available
    window.updateCartItemQuantity = updateCartItemQuantity;
    window.removeCartItem = removeCartItem;
    window.saveForLater = saveForLater;
    window.renderCart = renderCart;
    window.togglePromo = togglePromo;
    window.applyPromo = applyPromo;
    window.addUpsellItem = addUpsellItem;
    window.showToastMessage = showToastMessage;
    window.debugCart = debugCart;
    window.addTestItem = addTestItem;
</script>
</body>

</html>
