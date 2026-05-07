<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Policies — RYO</title>
  <meta name="description" content="RYO shipping policy, return policy, privacy policy, and terms of service.">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=DM+Sans:wght@300;400;500&family=Space+Grotesk:wght@400;500&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'ryo-black':'#0A0A0A','ryo-white':'#F8F6F2',
            'ryo-gray-100':'#EDEDEB','ryo-gray-200':'#D5D3CF',
            'ryo-gray-400':'#9C9A96','ryo-gray-700':'#3D3D3A',
          },
          fontFamily: {
            display:['Cormorant Garamond','serif'],
            body:['DM Sans','sans-serif'],
            label:['Space Grotesk','sans-serif'],
          }
        }
      }
    }
  </script>

  
  <style>
    *{margin:0;padding:0;box-sizing:border-box;}
    html{scroll-behavior:smooth;}
    body{font-family:'DM Sans',sans-serif;background:#F8F6F2;color:#0A0A0A;overflow-x:hidden;}
    ::-webkit-scrollbar{width:4px;}
    ::-webkit-scrollbar-track{background:#F8F6F2;}
    ::-webkit-scrollbar-thumb{background:#9C9A96;}

    /* NAV */
    .nav-link{font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:#0A0A0A;text-decoration:none;position:relative;padding-bottom:2px;}
    .nav-link::after{content:'';position:absolute;bottom:0;left:0;width:0;height:1px;background:#0A0A0A;transition:width .3s cubic-bezier(.25,.46,.45,.94);}
    .nav-link:hover::after{width:100%;}

    /* SIDEBAR NAV */
    .policy-nav-link{
      display:block;font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.15em;text-transform:uppercase;
      color:#9C9A96;text-decoration:none;padding:12px 0;border-bottom:1px solid #EDEDEB;
      transition:color .25s ease;position:relative;
    }
    .policy-nav-link::before{
      content:'';position:absolute;left:-20px;top:50%;transform:translateY(-50%);
      width:2px;height:0;background:#0A0A0A;transition:height .25s ease;
    }
    .policy-nav-link:hover{color:#0A0A0A;}
    .policy-nav-link.active{color:#0A0A0A;}
    .policy-nav-link.active::before{height:20px;}

    /* CONTENT */
    .policy-section{padding:80px 0;border-bottom:1px solid #EDEDEB;}
    .policy-section:last-child{border-bottom:none;}
    .policy-section h2{
      font-family:'Cormorant Garamond',serif;font-size:clamp(32px,3.5vw,52px);font-weight:300;
      letter-spacing:-.01em;line-height:1.1;margin-bottom:32px;
    }
    .policy-section h3{
      font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.2em;text-transform:uppercase;
      color:#0A0A0A;margin:32px 0 14px;
    }
    .policy-section p{
      font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#3D3D3A;line-height:1.85;
      margin-bottom:16px;
    }
    .policy-section ul{margin-bottom:20px;padding-left:0;list-style:none;}
    .policy-section li{
      font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#3D3D3A;line-height:1.85;
      padding:6px 0;border-bottom:1px solid #EDEDEB;display:flex;gap:12px;
    }
    .policy-section li::before{content:'—';color:#9C9A96;flex-shrink:0;}
    .policy-section a{color:#0A0A0A;text-decoration:none;border-bottom:1px solid #D5D3CF;padding-bottom:1px;transition:border-color .2s ease;}
    .policy-section a:hover{border-color:#0A0A0A;}

    /* INFO BOX */
    .info-box{
      background:#EDEDEB;padding:24px 28px;margin:24px 0;
      display:flex;gap:16px;
    }
    .info-box p{color:#3D3D3A;margin-bottom:0;}

    /* TABLE */
    .policy-table{width:100%;border-collapse:collapse;margin:20px 0;}
    .policy-table th{
      font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.15em;text-transform:uppercase;
      color:#9C9A96;text-align:left;padding:12px 0;border-bottom:1px solid #0A0A0A;font-weight:400;
    }
    .policy-table td{
      font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#3D3D3A;
      padding:14px 0;border-bottom:1px solid #EDEDEB;vertical-align:top;line-height:1.6;
    }
    .policy-table td:first-child{font-weight:400;color:#0A0A0A;width:35%;}

    /* CART DRAWER */
    .cart-drawer{position:fixed;top:0;right:0;width:min(420px,100vw);height:100%;background:#F8F6F2;z-index:200;transform:translateX(100%);transition:transform .45s cubic-bezier(.25,.46,.45,.94);display:flex;flex-direction:column;border-left:1px solid #D5D3CF;}
    .cart-drawer.open{transform:translateX(0);}
    .cart-overlay{position:fixed;inset:0;background:rgba(10,10,10,.4);z-index:199;opacity:0;pointer-events:none;transition:opacity .4s ease;}
    .cart-overlay.open{opacity:1;pointer-events:all;}

    /* MOBILE MENU */
    .mobile-menu{position:fixed;inset:0;background:#0A0A0A;z-index:100;transform:translateX(-100%);transition:transform .5s cubic-bezier(.25,.46,.45,.94);display:flex;flex-direction:column;padding:32px;}
    .mobile-menu.open{transform:translateX(0);}

    /* REVEAL */
    .reveal{opacity:0;transform:translateY(20px);transition:opacity .7s cubic-bezier(.25,.46,.45,.94),transform .7s cubic-bezier(.25,.46,.45,.94);}
    .reveal.visible{opacity:1;transform:translateY(0);}

    /* FOOTER */
    .footer-link{font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;text-decoration:none;transition:color .3s ease;}
    .footer-link:hover{color:#F8F6F2;}

    /* STICKY SIDEBAR */
    .policy-sidebar{position:sticky;top:80px;align-self:start;}

    /* CONTACT CARD */
    .contact-card{border:1px solid #EDEDEB;padding:28px;margin-top:32px;}
    .contact-card p{font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#3D3D3A;line-height:1.7;margin-bottom:0;}

    /* BADGE */
    .return-badge{
      display:inline-flex;align-items:center;gap:8px;
      font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.12em;text-transform:uppercase;
      padding:8px 16px;border:1px solid #EDEDEB;color:#3D3D3A;margin:4px 4px 4px 0;
    }
  </style>
</head>
<body>

 {{-- Navbar --}}
 @include('partials.nav-bar')

 {{-- Mobile Menu --}}
 @include('partials.mobile-menu')

 {{-- Cart Drawer --}}
 @include('partials.cart-drawer')


<!-- ══════════════════════════════════════════
     PAGE HERO
══════════════════════════════════════════ -->
<div style="padding:80px 40px 60px;border-bottom:1px solid #EDEDEB;background:#F8F6F2;">
  <div style="max-width:1200px;margin:0 auto;">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:24px;">
      <a href="index.html" style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;text-decoration:none;">Home</a>
      <span style="color:#D5D3CF;font-size:11px;">/</span>
      <span style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:#0A0A0A;">Policies</span>
    </div>
    <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;margin-bottom:16px;">Legal & Information</p>
    <h1 style="font-family:'Cormorant Garamond',serif;font-size:clamp(48px,6vw,88px);font-weight:300;letter-spacing:-.02em;line-height:.95;margin-bottom:24px;">
      Our<br><em>Policies</em>
    </h1>
    <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#9C9A96;max-width:480px;line-height:1.8;">Everything you need to know about shipping, returns, your privacy, and our terms. We keep it clear.</p>
  </div>
</div>


<!-- ══════════════════════════════════════════
     MAIN LAYOUT
══════════════════════════════════════════ -->
<div style="max-width:1200px;margin:0 auto;display:grid;grid-template-columns:220px 1fr;gap:64px;padding:0 40px 120px;" id="policyLayout">

  <!-- SIDEBAR -->
  <aside class="policy-sidebar" style="padding-top:64px;">
    <p style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;margin-bottom:20px;">Jump to</p>
    <nav>
      <a href="#shipping" class="policy-nav-link active" onclick="setActive(this)">Shipping Policy</a>
      <a href="#returns" class="policy-nav-link" onclick="setActive(this)">Return Policy</a>
      <a href="#privacy" class="policy-nav-link" onclick="setActive(this)">Privacy Policy</a>
      <a href="#terms" class="policy-nav-link" onclick="setActive(this)">Terms of Service</a>
      <a href="#cookies" class="policy-nav-link" onclick="setActive(this)">Cookie Policy</a>
      <a href="#contact" class="policy-nav-link" onclick="setActive(this)" style="border-bottom:none;">Contact Us</a>
    </nav>

    <div style="margin-top:48px;padding-top:32px;border-top:1px solid #EDEDEB;">
      <p style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;margin-bottom:12px;">Last Updated</p>
      <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#3D3D3A;font-weight:300;">June 1, 2025</p>
    </div>

    <div class="contact-card" style="margin-top:32px;">
      <p style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.18em;text-transform:uppercase;color:#9C9A96;margin-bottom:12px;">Need help?</p>
      <p style="margin-bottom:12px;">Our team is available 7 days a week.</p>
      <a href="mailto:hello@ryo.com" style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:#0A0A0A;text-decoration:none;border-bottom:1px solid #D5D3CF;padding-bottom:1px;">hello@ryo.com</a>
    </div>
  </aside>


  <!-- CONTENT -->
  <main style="min-width:0;">

    <!-- ── SHIPPING ── -->
    <div class="policy-section reveal" id="shipping">
      <div style="display:flex;align-items:center;gap:16px;margin-bottom:12px;">
        <div style="width:48px;height:48px;border:1px solid #EDEDEB;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
        </div>
        <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;">01</p>
      </div>
      <h2>Shipping Policy</h2>

      <div class="info-box">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5" style="flex-shrink:0;margin-top:2px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <p>Orders placed before 2:00 PM on business days are typically processed the same day. Orders placed on weekends or public holidays are processed the next business day.</p>
      </div>

      <h3>Delivery Timeframes</h3>
      <table class="policy-table">
        <thead>
          <tr>
            <th>Method</th>
            <th>Delivery Time</th>
            <th>Cost</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Standard Delivery</td>
            <td>2–4 business days</td>
            <td>Free on orders over EGP 2,000<br><span style="color:#9C9A96;">EGP 60 under EGP 2,000</span></td>
          </tr>
          <tr>
            <td>Express Delivery</td>
            <td>Same day / Next day<br><span style="font-size:12px;color:#9C9A96;">Order before 12:00 PM</span></td>
            <td>EGP 150</td>
          </tr>
          <tr>
            <td>Store Pickup — Cairo</td>
            <td>Ready in 2 hours</td>
            <td>Free</td>
          </tr>
        </tbody>
      </table>

      <h3>Coverage Area</h3>
      <p>We currently deliver across all governorates in Egypt. Remote areas may experience an additional 1–2 business days on delivery. We are continuously expanding our network.</p>

      <h3>Order Tracking</h3>
      <p>Once your order is dispatched, you will receive an SMS and email confirmation with a tracking link. You can also track your order from your <a href="#">account dashboard</a> at any time.</p>

      <h3>Delivery Attempts</h3>
      <ul>
        <li>Our courier will attempt delivery up to 2 times</li>
        <li>If both attempts are unsuccessful, the order will be held for 3 days before being returned</li>
        <li>Re-delivery fees may apply after a failed delivery</li>
        <li>Please ensure your phone number and address are accurate at checkout</li>
      </ul>

      <h3>Damaged in Transit</h3>
      <p>If your order arrives damaged or incomplete, please contact us within 48 hours of delivery with photos of the damaged item and packaging. We will arrange a replacement or full refund with no questions asked.</p>
    </div>


    <!-- ── RETURNS ── -->
    <div class="policy-section reveal" id="returns">
      <div style="display:flex;align-items:center;gap:16px;margin-bottom:12px;">
        <div style="width:48px;height:48px;border:1px solid #EDEDEB;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 014-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 01-4 4H3"/></svg>
        </div>
        <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;">02</p>
      </div>
      <h2>Return Policy</h2>

      <p>We want you to love every RYO piece. If something isn't right, we make returns simple and fair.</p>

      <div style="display:flex;flex-wrap:wrap;gap:8px;margin:20px 0;">
        <div class="return-badge"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="20 6 9 17 4 12"/></svg> 14-Day Returns</div>
        <div class="return-badge"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="20 6 9 17 4 12"/></svg> Free Returns</div>
        <div class="return-badge"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="20 6 9 17 4 12"/></svg> Full Refund</div>
        <div class="return-badge"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="20 6 9 17 4 12"/></svg> Exchange Available</div>
      </div>

      <h3>Return Window</h3>
      <p>You have 14 days from the date of delivery to initiate a return. After this period, we unfortunately cannot accept returns.</p>

      <h3>Eligible Items</h3>
      <ul>
        <li>Items must be unworn, unwashed, and in original condition</li>
        <li>All original tags must be attached and intact</li>
        <li>Items must be returned in original packaging where possible</li>
        <li>Items must not show signs of wear, alterations, or damage caused by the customer</li>
      </ul>

      <h3>Non-Returnable Items</h3>
      <ul>
        <li>Sale items marked as "Final Sale" at time of purchase</li>
        <li>Accessories including hats, belts, and bags for hygiene reasons</li>
        <li>Items with removed or missing tags</li>
        <li>Items that appear to have been worn or washed</li>
      </ul>

      <h3>How to Return</h3>
      <p>Initiating a return is simple:</p>
      <ul>
        <li>Log into your <a href="#">account</a> and select the order you wish to return</li>
        <li>Select the items and reason for return</li>
        <li>We will contact you within 24 hours to arrange pickup (free of charge)</li>
        <li>Once received and inspected, your refund will be processed within 5–7 business days</li>
      </ul>

      <div class="info-box">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5" style="flex-shrink:0;margin-top:2px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <p>Refunds are issued to the original payment method. For cash on delivery orders, refunds are issued via mobile wallet or bank transfer within 3–5 business days after the return is inspected.</p>
      </div>

      <h3>Exchanges</h3>
      <p>Want a different size or colour? We're happy to exchange your item, subject to availability. Simply indicate your preferred size/colour when initiating your return and we will process the exchange at no additional cost.</p>

      <h3>Defective or Wrong Item</h3>
      <p>If you received a defective or incorrect item, please contact us immediately at <a href="mailto:hello@ryo.com">hello@ryo.com</a> with your order number and photos. We will prioritise a replacement at no charge, including shipping both ways.</p>
    </div>


    <!-- ── PRIVACY ── -->
    <div class="policy-section reveal" id="privacy">
      <div style="display:flex;align-items:center;gap:16px;margin-bottom:12px;">
        <div style="width:48px;height:48px;border:1px solid #EDEDEB;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;">03</p>
      </div>
      <h2>Privacy Policy</h2>

      <p>Your privacy is important to us. This policy explains how RYO collects, uses, and protects your personal information when you shop with us or use our website.</p>

      <h3>Information We Collect</h3>
      <p>When you create an account or place an order, we collect:</p>
      <ul>
        <li>Your name, email address, phone number, and delivery address</li>
        <li>Payment information (processed securely — we never store card details)</li>
        <li>Order history and communication preferences</li>
        <li>Device and browsing data when you visit our website (see Cookie Policy)</li>
      </ul>

      <h3>How We Use Your Information</h3>
      <ul>
        <li>To process and fulfil your orders</li>
        <li>To send order confirmations, shipping updates, and receipts</li>
        <li>To personalise your shopping experience</li>
        <li>To send marketing communications (only with your consent)</li>
        <li>To improve our website, products, and customer service</li>
        <li>To comply with legal and regulatory obligations</li>
      </ul>

      <h3>Data Sharing</h3>
      <p>We do not sell your personal data. We only share it with trusted third parties when necessary to provide our services, including:</p>
      <ul>
        <li>Delivery and logistics partners (to fulfil orders)</li>
        <li>Payment processors (to handle transactions securely)</li>
        <li>Analytics platforms (to improve our services — data is anonymised)</li>
        <li>Customer support tools (to assist you when you reach out)</li>
      </ul>

      <h3>Data Security</h3>
      <p>We implement industry-standard security measures including SSL encryption, secure data storage, and access controls to protect your personal information from unauthorised access, disclosure, or misuse.</p>

      <h3>Your Rights</h3>
      <p>You have the right to:</p>
      <ul>
        <li>Access the personal data we hold about you</li>
        <li>Request correction of inaccurate information</li>
        <li>Request deletion of your account and associated data</li>
        <li>Opt out of marketing communications at any time</li>
        <li>Lodge a complaint with the relevant data authority</li>
      </ul>

      <h3>Data Retention</h3>
      <p>We retain your data for as long as your account is active or as required to fulfil legal obligations. You may request deletion of your data at any time by contacting us at <a href="mailto:privacy@ryo.com">privacy@ryo.com</a>.</p>
    </div>


    <!-- ── TERMS ── -->
    <div class="policy-section reveal" id="terms">
      <div style="display:flex;align-items:center;gap:16px;margin-bottom:12px;">
        <div style="width:48px;height:48px;border:1px solid #EDEDEB;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        </div>
        <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;">04</p>
      </div>
      <h2>Terms of Service</h2>

      <p>By accessing or using the RYO website, placing an order, or creating an account, you agree to be bound by these Terms of Service. Please read them carefully.</p>

      <h3>Use of Website</h3>
      <ul>
        <li>You must be at least 18 years of age to use our website or make a purchase</li>
        <li>You agree not to use our website for any unlawful or prohibited purpose</li>
        <li>You are responsible for maintaining the confidentiality of your account credentials</li>
        <li>RYO reserves the right to refuse service or cancel orders at our discretion</li>
      </ul>

      <h3>Product Information</h3>
      <p>We make every effort to display products accurately, including colours, sizing, and descriptions. However, due to differences in screen displays, slight variations may occur. We reserve the right to correct errors in product listings, including pricing errors, without liability.</p>

      <h3>Pricing & Payment</h3>
      <ul>
        <li>All prices are listed in Egyptian Pounds (EGP) and include VAT</li>
        <li>We reserve the right to change prices at any time without notice</li>
        <li>Payment must be made in full at the time of order placement</li>
        <li>We accept Visa, Mastercard, mobile wallets, and cash on delivery</li>
        <li>All transactions are subject to verification and may be declined without reason</li>
      </ul>

      <h3>Intellectual Property</h3>
      <p>All content on the RYO website — including images, text, logos, and design elements — is the property of RYO and protected under applicable copyright and intellectual property law. Reproduction, distribution, or use of any content without our written consent is strictly prohibited.</p>

      <h3>Limitation of Liability</h3>
      <p>RYO will not be held liable for any indirect, incidental, special, or consequential damages arising from the use of our website, products, or services. Our total liability in any situation shall not exceed the amount paid for the specific order in question.</p>

      <h3>Governing Law</h3>
      <p>These terms are governed by and construed in accordance with the laws of the Arab Republic of Egypt. Any disputes arising from these terms shall be subject to the exclusive jurisdiction of Egyptian courts.</p>

      <h3>Modifications to Terms</h3>
      <p>We reserve the right to update these Terms of Service at any time. Material changes will be communicated via email or a notice on our website. Continued use of our services after changes are posted constitutes your acceptance of the revised terms.</p>
    </div>


    <!-- ── COOKIES ── -->
    <div class="policy-section reveal" id="cookies">
      <div style="display:flex;align-items:center;gap:16px;margin-bottom:12px;">
        <div style="width:48px;height:48px;border:1px solid #EDEDEB;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M8.5 8.5a1 1 0 100 2 1 1 0 000-2M15.5 8.5a1 1 0 100 2 1 1 0 000-2M10 14a4 4 0 004 0"/></svg>
        </div>
        <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;">05</p>
      </div>
      <h2>Cookie Policy</h2>

      <p>We use cookies and similar tracking technologies to improve your browsing experience, analyse site traffic, and assist in our marketing efforts.</p>

      <h3>What Are Cookies</h3>
      <p>Cookies are small text files stored on your device when you visit a website. They help websites remember your preferences and behaviour across sessions, enabling a personalised experience.</p>

      <h3>Types of Cookies We Use</h3>
      <table class="policy-table">
        <thead>
          <tr><th>Type</th><th>Purpose</th><th>Duration</th></tr>
        </thead>
        <tbody>
          <tr><td>Essential</td><td>Required for the website to function — cart, login, security</td><td>Session</td></tr>
          <tr><td>Functional</td><td>Remember your preferences like language and region</td><td>1 year</td></tr>
          <tr><td>Analytics</td><td>Help us understand how visitors use our site (anonymised)</td><td>2 years</td></tr>
          <tr><td>Marketing</td><td>Used to show relevant ads based on your interests</td><td>90 days</td></tr>
        </tbody>
      </table>

      <h3>Managing Cookies</h3>
      <p>You can control and delete cookies through your browser settings. Please note that disabling certain cookies may affect the functionality of our website. Most browsers allow you to view, delete, and block cookies from specific websites.</p>

      <h3>Third-Party Cookies</h3>
      <p>We use trusted third-party services including Google Analytics, Meta Pixel, and TikTok Pixel that may set their own cookies. These services have their own privacy policies governing their use of data.</p>
    </div>


    <!-- ── CONTACT ── -->
    <div class="policy-section reveal" id="contact" style="border-bottom:none;padding-bottom:0;">
      <div style="display:flex;align-items:center;gap:16px;margin-bottom:12px;">
        <div style="width:48px;height:48px;border:1px solid #EDEDEB;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
        </div>
        <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;">06</p>
      </div>
      <h2>Contact Us</h2>

      <p>Questions about our policies? We're here to help. Our customer care team is available 7 days a week.</p>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:32px;">

        <div style="border:1px solid #EDEDEB;padding:28px;">
          <div style="width:40px;height:40px;border:1px solid #EDEDEB;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          </div>
          <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;margin-bottom:8px;">Email</p>
          <a href="mailto:hello@ryo.com" style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#0A0A0A;text-decoration:none;border-bottom:1px solid #D5D3CF;padding-bottom:1px;">hello@ryo.com</a>
          <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-top:8px;">Response within 24 hours</p>
        </div>

        <div style="border:1px solid #EDEDEB;padding:28px;">
          <div style="width:40px;height:40px;border:1px solid #EDEDEB;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 11.5 19.79 19.79 0 01.22 3.18 2 2 0 012.18 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8.64a16 16 0 006.29 6.29l1-.58a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
          </div>
          <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;margin-bottom:8px;">WhatsApp</p>
          <a href="#" style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#0A0A0A;text-decoration:none;border-bottom:1px solid #D5D3CF;padding-bottom:1px;">+20 100 xxx xxxx</a>
          <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-top:8px;">Available 9AM – 9PM daily</p>
        </div>

        <div style="border:1px solid #EDEDEB;padding:28px;">
          <div style="width:40px;height:40px;border:1px solid #EDEDEB;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
          </div>
          <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;margin-bottom:8px;">Instagram</p>
          <a href="https://instagram.com" target="_blank" style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#0A0A0A;text-decoration:none;border-bottom:1px solid #D5D3CF;padding-bottom:1px;">@ryo.official</a>
          <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-top:8px;">DM us anytime</p>
        </div>

        <div style="border:1px solid #EDEDEB;padding:28px;">
          <div style="width:40px;height:40px;border:1px solid #EDEDEB;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
          </div>
          <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;margin-bottom:8px;">Store — Cairo</p>
          <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#3D3D3A;line-height:1.6;">123 Fashion Street<br>Zamalek, Cairo</p>
          <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-top:8px;">Sun – Thu, 11AM – 10PM</p>
        </div>
      </div>
    </div>

  </main>
</div><!-- end layout -->


<!-- ══════════════════════════════════════════
     FOOTER
══════════════════════════════════════════ -->
<footer style="background:#0A0A0A;border-top:1px solid #1a1a1a;padding:64px 40px 32px;">
  <div style="max-width:1440px;margin:0 auto;">
    <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:48px;margin-bottom:64px;">
      <div>
        <p style="font-family:'Cormorant Garamond',serif;font-size:32px;font-weight:300;color:#F8F6F2;letter-spacing:.15em;margin-bottom:16px;">RYO</p>
        <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;line-height:1.8;max-width:260px;">Minimal luxury. Premium casual. Modern streetwear for the generation that knows.</p>
      </div>
      <div>
        <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:#F8F6F2;margin-bottom:24px;">Shop</p>
        <nav style="display:flex;flex-direction:column;gap:12px;">
          <a href="shop.html" class="footer-link">New Arrivals</a>
          <a href="shop.html" class="footer-link">Best Sellers</a>
          <a href="shop.html" class="footer-link">Sale</a>
        </nav>
      </div>
      <div>
        <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:#F8F6F2;margin-bottom:24px;">Policies</p>
        <nav style="display:flex;flex-direction:column;gap:12px;">
          <a href="#shipping" class="footer-link">Shipping</a>
          <a href="#returns" class="footer-link">Returns</a>
          <a href="#privacy" class="footer-link">Privacy</a>
          <a href="#terms" class="footer-link">Terms</a>
        </nav>
      </div>
      <div>
        <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:#F8F6F2;margin-bottom:24px;">Account</p>
        <nav style="display:flex;flex-direction:column;gap:12px;">
          <a href="#" class="footer-link">My Orders</a>
          <a href="#" class="footer-link">Saved Items</a>
          <a href="login.html" class="footer-link">Login</a>
        </nav>
      </div>
    </div>
    <div style="display:flex;align-items:center;justify-content:space-between;padding-top:24px;border-top:1px solid #1a1a1a;">
      <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#3D3D3A;">© 2025 RYO. All rights reserved.</p>
      <div style="display:flex;gap:24px;">
        <a href="#privacy" style="font-family:'DM Sans',sans-serif;font-size:12px;color:#3D3D3A;text-decoration:none;">Privacy</a>
        <a href="#terms" style="font-family:'DM Sans',sans-serif;font-size:12px;color:#3D3D3A;text-decoration:none;">Terms</a>
        <a href="#cookies" style="font-family:'DM Sans',sans-serif;font-size:12px;color:#3D3D3A;text-decoration:none;">Cookies</a>
      </div>
    </div>
  </div>
</footer>


<script>
  // ── CART ──
  function toggleCart() {
    document.getElementById('cartDrawer').classList.toggle('open');
    document.getElementById('cartOverlay').classList.toggle('open');
  }

  // ── MENU ──
  function toggleMenu() {
    document.getElementById('mobileMenu').classList.toggle('open');
  }

  // ── ACTIVE SIDEBAR NAV ──
  function setActive(el) {
    document.querySelectorAll('.policy-nav-link').forEach(l => l.classList.remove('active'));
    el.classList.add('active');
  }

  // ── SCROLL SPY ──
  const sections = ['shipping','returns','privacy','terms','cookies','contact'];
  window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(id => {
      const el = document.getElementById(id);
      if (el && el.getBoundingClientRect().top < 120) current = id;
    });
    if (current) {
      document.querySelectorAll('.policy-nav-link').forEach(l => {
        l.classList.remove('active');
        if (l.getAttribute('href') === `#${current}`) l.classList.add('active');
      });
    }
  });

  // ── SCROLL REVEAL ──
  const revObs = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revObs.unobserve(e.target); }});
  }, { threshold: 0.06 });
  document.querySelectorAll('.reveal').forEach(el => revObs.observe(el));

  // ── RESPONSIVE ──
  function checkLayout() {
    const layout = document.getElementById('policyLayout');
    if (window.innerWidth < 900) {
      layout.style.gridTemplateColumns = '1fr';
      layout.style.gap = '0';
      document.querySelector('.policy-sidebar').style.display = 'none';
    } else {
      layout.style.gridTemplateColumns = '220px 1fr';
      layout.style.gap = '64px';
      document.querySelector('.policy-sidebar').style.display = 'block';
    }
  }
  checkLayout();
  window.addEventListener('resize', checkLayout);
</script>
</body>
</html>
