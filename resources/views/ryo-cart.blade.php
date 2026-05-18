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


</head>

<body>

    <!-- TOAST -->
    <div id="toast"
        style="position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(80px);background:#0A0A0A;color:white;padding:12px 20px;border-radius:40px;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.08em;display:flex;align-items:center;gap:12px;z-index:500;opacity:0;transition:all 0.25s ease;pointer-events:none;">
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
            <a href="{{ route('home') }}"
                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;text-decoration:none;">Home</a>
            <span style="color:#D5D3CF;font-size:11px;">/</span>
            <span
                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:#0A0A0A;">Your
                Bag</span>
        </div>
        <div
            style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:0;flex-wrap:wrap;gap:12px;">
            <div>
                <h1
                    style="font-family:'Cormorant Garamond',serif;font-size:clamp(40px,5vw,72px);font-weight:300;letter-spacing:-.02em;line-height:.95;">
                    Your Bag</h1>
            </div>
            <a href="{{ route('all-products') }}" class="continue-link">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5">
                    <path d="M19 12H5M12 5l-7 7 7 7" />
                </svg>
                Continue Shopping
            </a>
        </div>
    </div>

    <!-- FREE SHIPPING PROGRESS BAR -->
    <div style="padding:20px 40px 0;max-width:1440px;margin:0 auto;" id="freeShippingBar">
        <div style="background:#F2EEE8;padding:14px 20px;display:flex;align-items:center;gap:16px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"
                style="flex-shrink:0;">
                <path d="M5 12h14" />
                <path d="M12 5l7 7-7 7" />
            </svg>
            <div style="flex:1;">
                <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:#3D3D3A;margin-bottom:6px;"
                    id="shippingMsg">
                    Add items to unlock free shipping!
                </p>
                <div class="progress-track">
                    <div class="progress-fill" id="progressFill" style="width:0%;"></div>
                </div>
            </div>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"
                style="flex-shrink:0;">
                <rect x="1" y="3" width="15" height="13" />
                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                <circle cx="5.5" cy="18.5" r="2.5" />
                <circle cx="18.5" cy="18.5" r="2.5" />
            </svg>
        </div>
    </div>

    <!-- MAIN CART LAYOUT -->
    <div style="max-width:1440px;margin:0 auto;padding:32px 40px 160px;display:grid;grid-template-columns:1fr 380px;gap:64px;align-items:start;"
        id="cartLayout">

        <!-- LEFT: CART ITEMS -->
        <div>
            <div style="display:grid;grid-template-columns:120px 1fr auto;gap:28px;padding-bottom:16px;border-bottom:2px solid #0A0A0A;"
                class="hidden md:grid">
                <span
                    style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.18em;text-transform:uppercase;color:#9C9A96;">Product</span>
                <span
                    style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.18em;text-transform:uppercase;color:#9C9A96;"></span>
                <span
                    style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.18em;text-transform:uppercase;color:#9C9A96;text-align:right;">Total</span>
            </div>

            <div id="cartPageItemsContainer">
                <!-- Cart items will be dynamically loaded here -->
            </div>


            <!-- EMPTY STATE -->
            <div class="empty-state" id="emptyState" style="display:none; text-align:center; padding:60px 20px;">
                <div
                    style="width:64px;height:64px;border:1px solid #EDEDEB;display:flex;align-items:center;justify-content:center;margin:0 auto 28px auto;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9C9A96"
                        stroke-width="1.5">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 01-8 0" />
                    </svg>
                </div>
                <p
                    style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;margin-bottom:16px;">
                    Your bag is empty</p>
                <h2
                    style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,4vw,44px);font-weight:300;margin-bottom:16px;line-height:1.1;">
                    Nothing here <em>yet</em></h2>
                <p
                    style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#9C9A96;margin-bottom:40px;max-width:360px;line-height:1.7;margin-left:auto;margin-right:auto;">
                    Looks like you haven't added anything to your bag. Explore our latest collection and find something
                    you love.</p>
                <a href="{{ route('all-products') }}"
                    style="display:inline-flex;align-items:center;gap:10px;background:#0A0A0A;color:#F8F6F2;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.14em;text-transform:uppercase;padding:14px 32px;text-decoration:none;">
                    Shop Collection
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <!-- YOU MAY ALSO LIKE (upsell) -->
            <div id="upsellSection"
                style="padding-top:48px;margin-top:16px;border-top:1px solid #EDEDEB; display:block;">
                <p
                    style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:#9C9A96;margin-bottom:20px;">
                    Complete Your Look</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;" id="upsellGrid">
                    <div class="upsell-card" data-upsell-name="Utility Coach Jacket" data-upsell-price="5500"
                        data-upsell-img="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=300&q=80">
                        <div class="upsell-img"><img
                                src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=300&q=80"
                                alt="Coach Jacket"></div>
                        <div style="flex:1;min-width:0;">
                            <p
                                style="font-family:'DM Sans',sans-serif;font-size:12px;font-weight:400;margin-bottom:3px;">
                                Utility Coach Jacket</p>
                            <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;">Olive / Black</p>
                            <p style="font-family:'DM Sans',sans-serif;font-size:12px;">EGP 5,500</p>
                        </div>
                        <button class="upsell-add" onclick="event.stopPropagation(); addUpsellItem(this)"><svg
                                width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg></button>
                    </div>
                    <div class="upsell-card" data-upsell-name="Washed Crewneck Sweat" data-upsell-price="3400"
                        data-upsell-img="https://images.unsplash.com/photo-1594938298603-f4d8c9a3a9a4?w=300&q=80">
                        <div class="upsell-img"><img
                                src="https://images.unsplash.com/photo-1594938298603-f4d8c9a3a9a4?w=300&q=80"
                                alt="Crewneck"></div>
                        <div style="flex:1;min-width:0;">
                            <p
                                style="font-family:'DM Sans',sans-serif;font-size:12px;font-weight:400;margin-bottom:3px;">
                                Washed Crewneck Sweat</p>
                            <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;">Faded Grey</p>
                            <p style="font-family:'DM Sans',sans-serif;font-size:12px;">EGP 3,400</p>
                        </div>
                        <button class="upsell-add" onclick="event.stopPropagation(); addUpsellItem(this)"><svg
                                width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: ORDER SUMMARY -->
        <div style="position:sticky;top:88px;" class="reveal">
            <div style="border:1px solid #EDEDEB;background:#F2EEE8;padding:32px;">
                <p
                    style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid #D5D3CF;">
                    Order Summary</p>
                <div style="display:flex;justify-content:space-between;margin-bottom:14px;">
                    <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;"
                        id="itemCountLabel">0 items</span>
                    <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;"
                        id="subtotalDisplay">EGP 0</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:14px;">
                    <span
                        style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">Shipping</span>
                    <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;"
                        id="shippingDisplay">—</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:14px; display:none;"
                    id="discountRow">
                    <span
                        style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">Discount</span>
                    <span style="font-family:'DM Sans',sans-serif;font-size:13px;" id="discountDisplay">— EGP 0</span>
                </div>
                <div style="height:1px;background:#D5D3CF;margin:16px 0;"></div>
                <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:28px;">
                    <span
                        style="font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.15em;text-transform:uppercase;">Total</span>
                    <div style="text-align:right;">
                        <p style="font-family:'Cormorant Garamond',serif;font-size:32px;font-weight:300;"
                            id="totalDisplay">EGP 0</p>
                        <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;">VAT included</p>
                    </div>
                </div>
                <div style="margin-bottom:20px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;cursor:pointer;"
                        onclick="togglePromo()">
                        <div style="display:flex;align-items:center;gap:6px;"><svg width="13" height="13"
                                viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z" />
                                <line x1="7" y1="7" x2="7.01" y2="7" />
                            </svg><span
                                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:#9C9A96;">Promo
                                Code</span></div>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#9C9A96"
                            stroke-width="1.5" id="promoChevron" style="transition:transform .3s ease;">
                            <path d="M6 9l6 6 6-6" />
                        </svg>
                    </div>
                    <div id="promoWrap" style="display:none;">
                        <div class="promo-wrap"><input class="promo-input" type="text"
                                placeholder="Enter promo code" id="promoCode"><button class="promo-btn"
                                onclick="applyPromo()">Apply</button></div>
                        <p id="promoMsg" style="font-family:'DM Sans',sans-serif;font-size:11px;margin-top:8px;"></p>
                    </div>
                </div>
                <a href="{{ route('checkout') }}" class="checkout-btn" id="checkoutBtn"><svg width="16"
                        height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>Secure Checkout</a>
                <div style="display:flex;align-items:center;gap:12px;margin:16px 0;">
                    <div style="flex:1;height:1px;background:#D5D3CF;"></div><span
                        style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;">or</span>
                    <div style="flex:1;height:1px;background:#D5D3CF;"></div>
                </div>
                <a href="{{ route('checkout') }}" id="guestCheckoutBtn"
                    style="display:flex;align-items:center;justify-content:center;gap:10px;border:1px solid #D5D3CF;color:#0A0A0A;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.12em;text-transform:uppercase;padding:14px;text-decoration:none;">Continue
                    as Guest</a>
                <div style="margin-top:20px;padding-top:16px;border-top:1px solid #D5D3CF;">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;"><svg width="13"
                            height="13" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg><span style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;">SSL
                            Encrypted · Secure Payment</span></div>
                    <div style="display:flex;align-items:center;gap:8px;"><svg width="13" height="13"
                            viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5">
                            <polyline points="17 1 21 5 17 9" />
                            <path d="M3 11V9a4 4 0 014-4h14" />
                            <polyline points="7 23 3 19 7 15" />
                            <path d="M21 13v2a4 4 0 01-4 4H3" />
                        </svg><span style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;">Free returns
                            within 14 days</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- MOBILE STICKY CHECKOUT BAR -->
    <div class="mobile-summary-sticky md:hidden" id="mobileStickyBar" style="display:none;">
        <div>
            <p style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.14em;text-transform:uppercase;color:#9C9A96;margin-bottom:2px;"
                id="mobileItemCount">0 items</p>
            <p style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:300;" id="mobileTotalDisplay">
                EGP 0</p>
        </div>
        <a href="{{ route('checkout') }}" id="mobileCheckoutBtn"
            style="background:#0A0A0A;color:#F8F6F2;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.12em;text-transform:uppercase;padding:14px 24px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;">Checkout<svg
                width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.5">
                <path d="M5 12h14M12 5l7 7-7 7" />
            </svg></a>
    </div>

    @include('partials.newslater')
    @include('partials.footer')

    <script>
        const FREE_SHIPPING_THRESHOLD = 1500;
        const SHOP_URL = @json(route('all-products'));
        let discount = 0;
        let currentPromoCode = '';
    </script>
    <script src="{{ asset('js/cart.js') }}"></script>

</body>

</html>
