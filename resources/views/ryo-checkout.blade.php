<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RYO - Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300&family=DM+Sans:wght@300;400;500&family=Space+Grotesk:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/ryo-check-out.css') }}">
    <script src="{{ asset('js/website-ryo-checkout-head.js') }}"></script>


</head>

<body>

    <!-- ══════════════════════════════════════════
     SUCCESS OVERLAY
══════════════════════════════════════════ -->
    <div class="success-overlay" id="successOverlay">
        <div
            style="width:64px;height:64px;border:1px solid #0A0A0A;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:32px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="1.5">
                <polyline points="20 6 9 17 4 12" />
            </svg>
        </div>
        <p
            style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.22em;text-transform:uppercase;color:#9C9A96;margin-bottom:16px;">
            Order Confirmed</p>
        <h2
            style="font-family:'Cormorant Garamond',serif;font-size:clamp(36px,5vw,60px);font-weight:300;margin-bottom:16px;line-height:1.1;">
            Thank you,<br><em>your order is placed.</em></h2>
        <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#9C9A96;margin-bottom:8px;">
            Order
            #RYO-2025-00841</p>
        <p
            style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;margin-bottom:48px;max-width:400px;line-height:1.7;">
            A confirmation has been sent to your email. Your order will be delivered within 2–4 business days.</p>
        <div style="display:flex;gap:16px;flex-wrap:wrap;justify-content:center;">
            <a href="{{ route('all-products') }}"
                style="display:inline-flex;align-items:center;gap:10px;background:#0A0A0A;color:#F8F6F2;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.12em;text-transform:uppercase;padding:14px 28px;text-decoration:none;">Continue
                Shopping</a>
            <a href="#"
                style="display:inline-flex;align-items:center;gap:10px;background:transparent;color:#0A0A0A;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.12em;text-transform:uppercase;padding:13px 28px;text-decoration:none;border:1px solid #0A0A0A;">Track
                Order</a>
        </div>
    </div>


    <!-- ══════════════════════════════════════════
     HEADER / NAV
══════════════════════════════════════════ -->
    <header
        style="height:64px;display:flex;align-items:center;justify-content:space-between;padding:0 40px;border-bottom:1px solid #EDEDEB;background:#F8F6F2;position:sticky;top:0;z-index:50;">
        <a href="{{ route('cart') }}"
            style="display:flex;align-items:center;gap:8px;font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;text-decoration:none;transition:color .2s ease;"
            onmouseover="this.style.color='#0A0A0A'" onmouseout="this.style.color='#9C9A96'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.5">
                <path d="M19 12H5M12 5l-7 7 7 7" />
            </svg>
            Back to Cart
        </a>
        <!-- LOGO -->
        <a href="{{ route('home') }}" class="nav-logo"
            style="
        position:absolute;
        left:50%;
        transform:translateX(-50%);
        display:flex;
        align-items:center;
        justify-content:center;
   ">

            <img src="{{ asset('images/logos/black_logo.png') }}" class="nav-logo" alt="RYO"
                style="height:70px;width:auto;display:block;">

        </a>
        <div style="display:flex;align-items:center;gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
            </svg>
            <span
                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:#9C9A96;">Secure
                Checkout</span>
        </div>
    </header>


    <!-- ══════════════════════════════════════════
     STEP INDICATOR
══════════════════════════════════════════ -->
    <div style="border-bottom:1px solid #EDEDEB;padding:20px 40px;">
        <div style="max-width:640px;margin:0 auto; text-align: center;">
            <div class="step-indicator" style="justify-content: center;">
                <div class="step done" id="step1">
                    <div class="step-num">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    </div>
                    <span class="step-label">Cart</span>
                </div>
                <div class="step-line"></div>
                <div class="step active" id="step2">
                    <div class="step-num">2</div>
                    <span class="step-label">Checkout</span>
                </div>

            </div>
        </div>
    </div>


    <!-- ══════════════════════════════════════════
     MAIN CHECKOUT LAYOUT
══════════════════════════════════════════ -->
    <div style="max-width:1200px;margin:0 auto;display:grid;grid-template-columns:1fr 420px;gap:0;min-height:calc(100vh - 130px);"
        id="checkoutLayout">

        <!-- LEFT: FORM -->
        <div style="padding:48px 56px 80px 40px;border-right:1px solid #EDEDEB;">

            <!-- STEP 1: Contact & Shipping Info -->
            <div id="formStep1">
                <h2
                    style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,3vw,40px);font-weight:300;margin-bottom:32px;letter-spacing:-.01em;">
                    Contact Information</h2>



                <!-- Contact -->
                <div class="form-panel reveal">
                    <p class="section-heading">01 — Contact</p>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div class="form-group">
                            <label class="form-label" for="firstName">First Name</label>
                            <input class="form-input" type="text" id="firstName" placeholder="Ahmed"
                                autocomplete="given-name">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="lastName">Last Name</label>
                            <input class="form-input" type="text" id="lastName" placeholder="Mohamed"
                                autocomplete="family-name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input class="form-input" type="email" id="email" placeholder="ahmed@example.com"
                            autocomplete="email">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" for="phone">Phone Number</label>
                        <div style="display:flex;gap:0;">
                            <div
                                style="border:1px solid #D5D3CF;border-right:none;padding:13px 14px;background:#EDEDEB;display:flex;align-items:center;gap:6px;flex-shrink:0;">
                                <span
                                    style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#3D3D3A;">🇪🇬
                                    +20</span>
                            </div>
                            <input class="form-input" type="tel" id="phone" placeholder="010 xxxx xxxx"
                                autocomplete="tel" style="border-left:none;">
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="form-panel reveal" style="transition-delay:.1s;">
                    <p class="section-heading">02 — Shipping Address</p>
                    <div class="form-group">
                        <label class="form-label" for="address1">Address Line 1</label>
                        <input class="form-input" type="text" id="address1"
                            placeholder="Street, Building, Apartment" autocomplete="address-line1">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="address2">Address Line 2 <span
                                style="font-size:10px;color:#9C9A96;">(Optional)</span></label>
                        <input class="form-input" type="text" id="address2" placeholder="Floor, Landmark"
                            autocomplete="address-line2">
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div class="form-group">
                            <label class="form-label" for="city">City</label>
                            <select class="form-select" id="city" onchange="updateShippingByCity()">
                                <option value="" disabled selected>Select city</option>
                                @foreach ($govs as $gov)
                                    {{-- مهم جداً: data-price يجب أن يحمل قيمة تكلفة الشحن --}}
                                    <option value="{{ $gov->id }}" data-price="{{ $gov->shipping_cost }}"
                                        data-days="{{ $gov->estimated_days }}">
                                        {{ $gov->city_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="district">District / Area</label>
                            <input class="form-input" type="text" id="district"
                                placeholder="Nasr City, Maadi...">
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" for="notes">Order Notes <span
                                style="font-size:10px;color:#9C9A96;">(Optional)</span></label>
                        <textarea class="form-input" id="notes" rows="3" placeholder="Special delivery instructions..."
                            style="resize:vertical;"></textarea>
                    </div>
                </div>

                <!-- Shipping Method -->
                <div class="form-panel reveal" id="shippingPanel" style="transition-delay:.2s;">
                    <p class="section-heading">03 — Shipping Method</p>

                    <div class="shipping-opt active" onclick="selectShipping(this,'standard')">
                        <div style="display:flex;align-items:center;gap:14px;">
                            <div class="pay-radio" id="ship-radio-std">
                                <div class="pay-radio-dot" style="opacity:1;"></div>
                            </div>
                            <div>
                                <p
                                    style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:400;margin-bottom:2px;">
                                    Cash On Delivery
                                </p>
                                <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;"
                                    id="estimated-days-text">
                                    — <!-- سيتم تحديثه عبر JavaScript -->
                                </p>
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#0A0A0A;" id="std-price">
                                Select city
                            </p>
                            <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;">
                                Shipping cost depends on city
                            </p>
                        </div>
                    </div>
                </div>



                <!-- Submit -->
                <button class="submit-btn" id="placeOrderBtn" onclick="placeOrder()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                    Place Order — EGP <span id="totalInBtn">0</span>
                </button>

                <p
                    style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;text-align:center;margin-top:16px;line-height:1.7;">
                    By placing your order you agree to our
                    <a href="{{ route('terms-of-services') }}"
                        style="color:#0A0A0A;text-decoration:none;border-bottom:1px solid #D5D3CF;">Terms of
                        Service</a>,
                    <a href="{{ route('privacy-policy') }}"
                        style="color:#0A0A0A;text-decoration:none;border-bottom:1px solid #D5D3CF;">Privacy
                        Policy</a>,
                    and <a href="{{ route('refund-policy') }}"
                        style="color:#0A0A0A;text-decoration:none;border-bottom:1px solid #D5D3CF;">Return
                        Policy</a>.
                </p>
            </div>

        </div>


        <!-- RIGHT: ORDER SUMMARY -->
        <div
            style="background:#F2EEE8;padding:40px 40px 40px 40px;border-left:1px solid #EDEDEB;position:sticky;top:130px;align-self:start;">

            <p
                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid #D5D3CF;">
                Order Summary</p>

            <!-- Items -->
            <div id="orderItems">
                {{-- سيتم تعبئة العناصر ديناميكياً عبر JavaScript --}}
            </div>

            <div style="height:1px;background:#D5D3CF;margin:20px 0;"></div>

            <!-- Promo Code -->
            <div style="margin-bottom:20px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;cursor:pointer;"
                    onclick="togglePromo()">
                    <span
                        style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:#9C9A96;">Promo
                        Code</span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#9C9A96"
                        stroke-width="1.5" id="promoChevron" style="transition:transform .3s ease;">
                        <path d="M6 9l6 6 6-6" />
                    </svg>
                </div>
                <div id="promoWrap" style="display:none;">
                    <div class="promo-wrap">
                        <input class="promo-input" type="text" placeholder="Enter code" id="promoCode">
                        <button class="promo-btn" onclick="applyPromo()">Apply</button>
                    </div>
                    <p id="promoMsg" style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;"></p>
                </div>
            </div>

            <!-- Price Breakdown -->
            <div>
                <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                    <span
                        style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">Subtotal</span>
                    <span style="font-family:'DM Sans',sans-serif;font-size:13px;" id="subtotalDisplay">EGP 0</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                    <span
                        style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">Shipping</span>
                    <span style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9C9A96;"
                        id="shippingDisplay">Free</span>
                </div>
                <div style="display:none;justify-content:space-between;margin-bottom:10px;" id="discountRow">
                    <span
                        style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">Discount</span>
                    <span style="font-family:'DM Sans',sans-serif;font-size:13px;color:#0A0A0A;"
                        id="discountDisplay">EGP 0</span>
                </div>

                <div style="height:1px;background:#D5D3CF;margin:16px 0;"></div>
                <div style="display:flex;justify-content:space-between;align-items:baseline;">
                    <span
                        style="font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.15em;text-transform:uppercase;">Total</span>
                    <div style="text-align:right;">
                        <p style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:300;"
                            id="grandTotal" class="total-price">EGP 0
                        </p>
                    </div>
                </div>
            </div>

            <!-- Policies -->
            <div style="margin-top:28px;padding-top:20px;border-top:1px solid #D5D3CF;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9C9A96"
                        stroke-width="1.5">
                        <path d="M5 12h14" />
                        <path d="M12 5l7 7-7 7" />
                    </svg>
                    <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;font-weight:300;">Free
                        returns within
                        7 days</p>
                </div>

                <div style="display:flex;align-items:center;gap:10px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9C9A96"
                        stroke-width="1.5">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;font-weight:300;"
                        id="estimated-days-text2">Delivered
                        in 2–4
                        business days</p>
                </div>
            </div>

        </div>
    </div><!-- end checkout layout -->

    <script>
        window.govs = @json($govs);
    </script>


    <script src="{{ asset('js/website-ryo-checkout.js') }}"></script>
</body>

</html>
