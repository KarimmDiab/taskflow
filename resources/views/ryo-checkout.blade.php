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
  <script>
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
  </script>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: #F8F6F2;
      color: #0A0A0A;
      overflow-x: hidden;
    }

    ::-webkit-scrollbar {
      width: 4px;
    }

    ::-webkit-scrollbar-track {
      background: #F8F6F2;
    }

    ::-webkit-scrollbar-thumb {
      background: #9C9A96;
    }

    /* ── FORM FIELDS ── */
    .form-group {
      position: relative;
      margin-bottom: 20px;
    }

    .form-label {
      display: block;
      font-family: 'Space Grotesk', sans-serif;
      font-size: 10px;
      letter-spacing: .15em;
      text-transform: uppercase;
      color: #9C9A96;
      margin-bottom: 8px;
      transition: color .2s ease;
    }

    .form-input {
      width: 100%;
      border: 1px solid #D5D3CF;
      background: #F8F6F2;
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      font-weight: 300;
      color: #0A0A0A;
      padding: 13px 16px;
      outline: none;
      transition: border-color .25s ease, box-shadow .25s ease;
      appearance: none;
      -webkit-appearance: none;
    }

    .form-input:focus {
      border-color: #0A0A0A;
      box-shadow: 0 0 0 3px rgba(10, 10, 10, .06);
    }

    .form-input::placeholder {
      color: #9C9A96;
      font-weight: 300;
    }

    .form-input.error {
      border-color: #c0392b;
    }

    .form-select {
      width: 100%;
      border: 1px solid #D5D3CF;
      background: #F8F6F2;
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      font-weight: 300;
      color: #0A0A0A;
      padding: 13px 40px 13px 16px;
      outline: none;
      cursor: pointer;
      appearance: none;
      -webkit-appearance: none;
      transition: border-color .25s ease;
      background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%230A0A0A' stroke-width='1.2' stroke-linecap='round' fill='none'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 16px center;
    }

    .form-select:focus {
      border-color: #0A0A0A;
    }

    /* ── STEPS ── */
    .step-indicator {
      display: flex;
      align-items: center;
      gap: 0;
    }

    .step {
      display: flex;
      align-items: center;
      gap: 8px;
      position: relative;
    }

    .step-num {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Space Grotesk', sans-serif;
      font-size: 11px;
      font-weight: 500;
      border: 1px solid #D5D3CF;
      color: #9C9A96;
      background: #F8F6F2;
      transition: all .3s ease;
      flex-shrink: 0;
    }

    .step.active .step-num {
      background: #0A0A0A;
      color: #F8F6F2;
      border-color: #0A0A0A;
    }

    .step.done .step-num {
      background: #0A0A0A;
      color: #F8F6F2;
      border-color: #0A0A0A;
    }

    .step-label {
      font-family: 'Space Grotesk', sans-serif;
      font-size: 10px;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: #9C9A96;
      transition: color .3s ease;
    }

    .step.active .step-label {
      color: #0A0A0A;
    }

    .step.done .step-label {
      color: #0A0A0A;
    }

    .step-line {
      width: 48px;
      height: 1px;
      background: #D5D3CF;
      flex-shrink: 0;
    }

    /* ── PAYMENT METHOD ── */
    .pay-option {
      border: 1px solid #D5D3CF;
      padding: 16px 20px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 10px;
      transition: border-color .25s ease, background .25s ease;
      position: relative;
    }

    .pay-option:hover {
      border-color: #9C9A96;
    }

    .pay-option.active {
      border-color: #0A0A0A;
      background: rgba(10, 10, 10, .02);
    }

    .pay-radio {
      width: 18px;
      height: 18px;
      border-radius: 50%;
      border: 1px solid #D5D3CF;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      transition: border-color .2s ease;
    }

    .pay-option.active .pay-radio {
      border-color: #0A0A0A;
    }

    .pay-radio-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #0A0A0A;
      opacity: 0;
      transition: opacity .2s ease;
    }

    .pay-option.active .pay-radio-dot {
      opacity: 1;
    }

    /* ── ORDER SUMMARY ── */
    .summary-item {
      display: flex;
      align-items: flex-start;
      gap: 14px;
      margin-bottom: 20px;
    }

    .summary-img {
      width: 64px;
      height: 80px;
      overflow: hidden;
      background: #EDEDEB;
      position: relative;
      flex-shrink: 0;
    }

    .summary-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .item-qty-badge {
      position: absolute;
      top: -6px;
      right: -6px;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      background: #0A0A0A;
      color: #F8F6F2;
      font-size: 10px;
      font-family: 'Space Grotesk', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* ── PROMO CODE ── */
    .promo-wrap {
      display: flex;
      gap: 0;
      border: 1px solid #D5D3CF;
      overflow: hidden;
      margin-bottom: 20px;
    }

    .promo-input {
      flex: 1;
      border: none;
      background: transparent;
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
      font-weight: 300;
      padding: 12px 16px;
      outline: none;
      color: #0A0A0A;
    }

    .promo-input::placeholder {
      color: #9C9A96;
    }

    .promo-btn {
      background: #0A0A0A;
      color: #F8F6F2;
      border: none;
      cursor: pointer;
      font-family: 'Space Grotesk', sans-serif;
      font-size: 10px;
      letter-spacing: .12em;
      text-transform: uppercase;
      padding: 12px 20px;
      transition: background .2s ease;
    }

    .promo-btn:hover {
      background: #3D3D3A;
    }

    /* ── SUBMIT BTN ── */
    .submit-btn {
      width: 100%;
      background: #0A0A0A;
      color: #F8F6F2;
      border: none;
      cursor: pointer;
      font-family: 'Space Grotesk', sans-serif;
      font-size: 12px;
      letter-spacing: .16em;
      text-transform: uppercase;
      padding: 17px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      transition: all .3s cubic-bezier(.25, .46, .45, .94);
    }

    .submit-btn:hover {
      background: #3D3D3A;
    }

    .submit-btn:active {
      transform: scale(.99);
    }

    /* ── SECTION HEADING ── */
    .section-heading {
      font-family: 'Space Grotesk', sans-serif;
      font-size: 11px;
      letter-spacing: .18em;
      text-transform: uppercase;
      color: #0A0A0A;
      margin-bottom: 20px;
      padding-bottom: 14px;
      border-bottom: 1px solid #EDEDEB;
    }

    /* ── CHECKOUT FORM PANEL ── */
    .form-panel {
      background: #F8F6F2;
      padding: 32px;
      border: 1px solid #EDEDEB;
      margin-bottom: 20px;
    }

    /* ── SUCCESS OVERLAY ── */
    .success-overlay {
      position: fixed;
      inset: 0;
      background: #F8F6F2;
      z-index: 500;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      opacity: 0;
      pointer-events: none;
      transition: opacity .5s ease;
      padding: 40px;
      text-align: center;
    }

    .success-overlay.show {
      opacity: 1;
      pointer-events: all;
    }

    /* ── SHIPPING OPTION ── */
    .shipping-opt {
      border: 1px solid #D5D3CF;
      padding: 16px 20px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 10px;
      transition: border-color .25s ease;
    }

    .shipping-opt:hover,
    .shipping-opt.active {
      border-color: #0A0A0A;
    }

    /* ── REVEAL ── */
    .reveal {
      opacity: 0;
      transform: translateY(20px);
      transition: opacity .6s cubic-bezier(.25, .46, .45, .94), transform .6s cubic-bezier(.25, .46, .45, .94);
    }

    .reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* CARD FIELD */
    .card-field-wrap {
      border: 1px solid #D5D3CF;
      padding: 13px 16px;
      background: #F8F6F2;
      cursor: text;
    }

    .card-field-fake {
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      font-weight: 300;
      color: #9C9A96;
    }
  </style>
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
    <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#9C9A96;margin-bottom:8px;">Order
      #RYO-2025-00841</p>
    <p
      style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;margin-bottom:48px;max-width:400px;line-height:1.7;">
      A confirmation has been sent to your email. Your order will be delivered within 2–4 business days.</p>
    <div style="display:flex;gap:16px;flex-wrap:wrap;justify-content:center;">
      <a href="index.html"
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
    <a href="cart.html"
      style="display:flex;align-items:center;gap:8px;font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;text-decoration:none;transition:color .2s ease;"
      onmouseover="this.style.color='#0A0A0A'" onmouseout="this.style.color='#9C9A96'">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M19 12H5M12 5l-7 7 7 7" />
      </svg>
      Back to Cart
    </a>
    <!-- LOGO -->
    <a href="{{ route('home') }}" class="nav-logo" style="
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
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
  <div
    style="max-width:1200px;margin:0 auto;display:grid;grid-template-columns:1fr 420px;gap:0;min-height:calc(100vh - 130px);"
    id="checkoutLayout">

    <!-- LEFT: FORM -->
    <div style="padding:48px 56px 80px 40px;border-right:1px solid #EDEDEB;">

      <!-- STEP 1: Contact & Shipping Info -->
      <div id="formStep1">
        <h2
          style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,3vw,40px);font-weight:300;margin-bottom:32px;letter-spacing:-.01em;">
          Contact Information</h2>

        <!-- Guest / Login toggle -->
        <div style="display:flex;align-items:center;gap:24px;margin-bottom:28px;padding:16px 20px;background:#EDEDEB;">
          <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#3D3D3A;">Already have an
            account?</span>
          <a href="login.html"
            style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:#0A0A0A;text-decoration:none;border-bottom:1px solid #0A0A0A;padding-bottom:1px;">Login
            for faster checkout</a>
        </div>

        <!-- Contact -->
        <div class="form-panel reveal">
          <p class="section-heading">01 — Contact</p>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="form-group">
              <label class="form-label" for="firstName">First Name</label>
              <input class="form-input" type="text" id="firstName" placeholder="Ahmed" autocomplete="given-name">
            </div>
            <div class="form-group">
              <label class="form-label" for="lastName">Last Name</label>
              <input class="form-input" type="text" id="lastName" placeholder="Mohamed" autocomplete="family-name">
            </div>
          </div>
          <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <input class="form-input" type="email" id="email" placeholder="ahmed@example.com" autocomplete="email">
          </div>
          <div class="form-group" style="margin-bottom:0;">
            <label class="form-label" for="phone">Phone Number</label>
            <div style="display:flex;gap:0;">
              <div
                style="border:1px solid #D5D3CF;border-right:none;padding:13px 14px;background:#EDEDEB;display:flex;align-items:center;gap:6px;flex-shrink:0;">
                <span style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#3D3D3A;">🇪🇬
                  +20</span>
              </div>
              <input class="form-input" type="tel" id="phone" placeholder="010 xxxx xxxx" autocomplete="tel"
                style="border-left:none;">
            </div>
          </div>
        </div>

        <!-- Shipping Address -->
        <div class="form-panel reveal" style="transition-delay:.1s;">
          <p class="section-heading">02 — Shipping Address</p>
          <div class="form-group">
            <label class="form-label" for="address1">Address Line 1</label>
            <input class="form-input" type="text" id="address1" placeholder="Street, Building, Apartment"
              autocomplete="address-line1">
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
              <select class="form-select" id="city">
                <option value="" disabled selected>Select city</option>
                <option>Cairo</option>
                <option>Giza</option>
                <option>Alexandria</option>
                <option>Luxor</option>
                <option>Aswan</option>
                <option>Mansoura</option>
                <option>Tanta</option>
                <option>Ismailia</option>
                <option>Port Said</option>
                <option>Suez</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label" for="district">District / Area</label>
              <input class="form-input" type="text" id="district" placeholder="Nasr City, Maadi...">
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

          <div class="shipping-opt active" onclick="selectShipping(this,'standard',0)">
            <div style="display:flex;align-items:center;gap:14px;">
              <div class="pay-radio" id="ship-radio-std">
                <div class="pay-radio-dot" style="opacity:1;"></div>
              </div>
              <div>
                <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:400;margin-bottom:2px;">Standard
                  Delivery</p>
                <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;">2–4 business days</p>
              </div>
            </div>
            <div style="text-align:right;">
              <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#0A0A0A;" id="std-price">Free</p>
              <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;">On orders over EGP 2,000</p>
            </div>
          </div>

          <div class="shipping-opt" onclick="selectShipping(this,'express',150)">
            <div style="display:flex;align-items:center;gap:14px;">
              <div class="pay-radio" id="ship-radio-exp">
                <div class="pay-radio-dot" style="opacity:0;"></div>
              </div>
              <div>
                <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:400;margin-bottom:2px;">Express
                  Delivery</p>
                <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;">Same day / Next day</p>
              </div>
            </div>
            <div style="text-align:right;">
              <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#0A0A0A;">EGP 150</p>
            </div>
          </div>

          <div class="shipping-opt" style="margin-bottom:0;" onclick="selectShipping(this,'pickup',0)">
            <div style="display:flex;align-items:center;gap:14px;">
              <div class="pay-radio" id="ship-radio-pick">
                <div class="pay-radio-dot" style="opacity:0;"></div>
              </div>
              <div>
                <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:400;margin-bottom:2px;">Store
                  Pickup</p>
                <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;">Ready in 2 hours · Cairo only
                </p>
              </div>
            </div>
            <div style="text-align:right;">
              <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#0A0A0A;">Free</p>
            </div>
          </div>
        </div>

        <!-- Payment Method -->
        <div class="form-panel reveal" style="transition-delay:.3s;">
          <p class="section-heading">04 — Payment</p>

          <!-- Payment Options -->
          <div class="pay-option active" id="pay-card" onclick="selectPayment('card')">
            <div class="pay-radio">
              <div class="pay-radio-dot" style="opacity:1;"></div>
            </div>
            <div style="flex:1;">
              <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:400;">Credit / Debit Card</p>
            </div>
            <div style="display:flex;gap:6px;align-items:center;">
              <div
                style="background:#1a1f71;color:#F8F6F2;font-size:9px;font-family:'Space Grotesk',sans-serif;font-weight:500;padding:3px 7px;letter-spacing:.05em;">
                VISA</div>
              <div style="background:#eb001b;border-radius:50%;width:22px;height:22px;"></div>
              <div style="background:#f79e1b;border-radius:50%;width:22px;height:22px;margin-left:-12px;"></div>
            </div>
          </div>

          <div class="pay-option" id="pay-wallet" onclick="selectPayment('wallet')">
            <div class="pay-radio">
              <div class="pay-radio-dot"></div>
            </div>
            <div style="flex:1;">
              <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:400;">Mobile Wallet</p>
              <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;">Vodafone Cash · Orange Money ·
                Etisalat</p>
            </div>
          </div>

          <div class="pay-option" id="pay-cod" onclick="selectPayment('cod')">
            <div class="pay-radio">
              <div class="pay-radio-dot"></div>
            </div>
            <div style="flex:1;">
              <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:400;">Cash on Delivery</p>
              <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;">+EGP 30 handling fee</p>
            </div>
          </div>

          <!-- Card Fields (shown when card selected) -->
          <div id="cardFields" style="margin-top:20px;display:block;">
            <div class="form-group">
              <label class="form-label">Card Number</label>
              <div class="card-field-wrap" style="display:flex;align-items:center;justify-content:space-between;">
                <span class="card-field-fake">•••• •••• •••• ••••</span>
                <div style="display:flex;gap:6px;">
                  <div
                    style="background:#1a1f71;color:#F8F6F2;font-size:8px;font-family:'Space Grotesk',sans-serif;font-weight:500;padding:2px 5px;">
                    VISA</div>
                </div>
              </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
              <div class="form-group">
                <label class="form-label">Expiry Date</label>
                <input class="form-input" type="text" placeholder="MM / YY" maxlength="7" id="expiry"
                  oninput="formatExpiry(this)">
              </div>
              <div class="form-group">
                <label class="form-label">CVV / CVC</label>
                <input class="form-input" type="text" placeholder="•••" maxlength="4" id="cvv"
                  style="letter-spacing:.2em;">
              </div>
            </div>
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Cardholder Name</label>
              <input class="form-input" type="text" placeholder="Name as on card" id="cardName" autocomplete="cc-name">
            </div>
          </div>

          <!-- Wallet Fields -->
          <div id="walletFields" style="margin-top:20px;display:none;">
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Mobile Number</label>
              <input class="form-input" type="tel" placeholder="010 xxxx xxxx">
            </div>
          </div>

          <!-- Trust note -->
          <div style="display:flex;align-items:center;gap:8px;margin-top:20px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
            </svg>
            <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;font-weight:300;">Your payment is
              encrypted and secure. We never store your card details.</p>
          </div>
        </div>

        <!-- Submit -->
        <button class="submit-btn" id="placeOrderBtn" onclick="placeOrder()">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
          </svg>
          Place Order — EGP <span id="totalInBtn">6,350</span>
        </button>

        <p
          style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;text-align:center;margin-top:16px;line-height:1.7;">
          By placing your order you agree to our
          <a href="policy.html" style="color:#0A0A0A;text-decoration:none;border-bottom:1px solid #D5D3CF;">Terms of
            Service</a>,
          <a href="policy.html" style="color:#0A0A0A;text-decoration:none;border-bottom:1px solid #D5D3CF;">Privacy
            Policy</a>,
          and <a href="policy.html" style="color:#0A0A0A;text-decoration:none;border-bottom:1px solid #D5D3CF;">Return
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
        <div class="summary-item">
          <div class="summary-img">
            <img src="https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=200&q=80" alt="Tee">
            <div class="item-qty-badge">1</div>
          </div>
          <div style="flex:1;">
            <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:400;margin-bottom:3px;">Oversized
              Essential Tee</p>
            <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-bottom:2px;">Size: L · Stone
            </p>
            <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;">SS25 Collection</p>
          </div>
          <span style="font-family:'DM Sans',sans-serif;font-size:13px;flex-shrink:0;">EGP 2,200</span>
        </div>

        <div class="summary-item">
          <div class="summary-img">
            <img src="https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=200&q=80" alt="Cargo">
            <div class="item-qty-badge">1</div>
          </div>
          <div style="flex:1;">
            <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:400;margin-bottom:3px;">Wide Leg Cargo
              Pant</p>
            <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-bottom:2px;">Size: 32 ·
              Washed Black</p>
            <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;">SS25 Collection</p>
          </div>
          <span style="font-family:'DM Sans',sans-serif;font-size:13px;flex-shrink:0;">EGP 3,800</span>
        </div>
      </div>

      <div style="height:1px;background:#D5D3CF;margin:20px 0;"></div>

      <!-- Promo Code -->
      <div style="margin-bottom:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;cursor:pointer;"
          onclick="togglePromo()">
          <span
            style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:#9C9A96;">Promo
            Code</span>
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"
            id="promoChevron" style="transition:transform .3s ease;">
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
          <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">Subtotal</span>
          <span style="font-family:'DM Sans',sans-serif;font-size:13px;">EGP 6,000</span>
        </div>
        <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
          <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">Shipping</span>
          <span style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9C9A96;" id="shippingDisplay">Free</span>
        </div>
        <div style="display:flex;justify-content:space-between;margin-bottom:10px;" id="discountRow"
          style="display:none;">
          <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">Discount</span>
          <span style="font-family:'DM Sans',sans-serif;font-size:13px;color:#0A0A0A;" id="discountDisplay">— EGP
            600</span>
        </div>
        <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
          <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">VAT (14%)</span>
          <span style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9C9A96;">Included</span>
        </div>
        <div style="height:1px;background:#D5D3CF;margin:16px 0;"></div>
        <div style="display:flex;justify-content:space-between;align-items:baseline;">
          <span
            style="font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.15em;text-transform:uppercase;">Total</span>
          <div style="text-align:right;">
            <p style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:300;" id="grandTotal">EGP 6,000
            </p>
            <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;">VAT included</p>
          </div>
        </div>
      </div>

      <!-- Policies -->
      <div style="margin-top:28px;padding-top:20px;border-top:1px solid #D5D3CF;">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5">
            <path d="M5 12h14" />
            <path d="M12 5l7 7-7 7" />
          </svg>
          <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;font-weight:300;">Free returns within
            14 days</p>
        </div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
          </svg>
          <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;font-weight:300;">SSL secured ·
            Encrypted payment</p>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5">
            <circle cx="12" cy="12" r="10" />
            <polyline points="12 6 12 12 16 14" />
          </svg>
          <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;font-weight:300;">Delivered in 2–4
            business days</p>
        </div>
      </div>

    </div>
  </div><!-- end checkout layout -->


  <script>
    // ── PAYMENT METHOD ──
    function selectPayment(type) {
      const options = { card: 'pay-card', wallet: 'pay-wallet', cod: 'pay-cod' };
      Object.values(options).forEach(id => {
        const el = document.getElementById(id);
        el.classList.remove('active');
        el.querySelector('.pay-radio-dot').style.opacity = '0';
      });
      document.getElementById(options[type]).classList.add('active');
      document.getElementById(options[type]).querySelector('.pay-radio-dot').style.opacity = '1';

      document.getElementById('cardFields').style.display = type === 'card' ? 'block' : 'none';
      document.getElementById('walletFields').style.display = type === 'wallet' ? 'block' : 'none';
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
        discount = 600;
        msg.textContent = '✓ RYO10 applied — 10% off';
        msg.style.color = '#0A0A0A';
        document.getElementById('discountRow').style.display = 'flex';
        document.getElementById('discountDisplay').textContent = `− EGP ${discount.toLocaleString()}`;
      } else if (code === '') {
        msg.textContent = 'Please enter a promo code.';
        msg.style.color = '#9C9A96';
      } else {
        msg.textContent = 'Invalid code. Try RYO10 for 10% off.';
        msg.style.color = '#c0392b';
        discount = 0;
      }
      updateTotal();
    }

    // ── TOTAL ──
    function updateTotal() {
      const base = 6000;
      const total = base + shippingCost - discount;
      document.getElementById('grandTotal').textContent = `EGP ${total.toLocaleString('en-EG')}`;
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
    checkLayout();
    window.addEventListener('resize', checkLayout);

    // ── SPIN ANIMATION ──
    const style = document.createElement('style');
    style.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
    document.head.appendChild(style);
  </script>
</body>

</html>