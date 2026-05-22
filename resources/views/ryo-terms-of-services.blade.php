<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RYO - Terms of Service</title>
  <meta name="description" content="RYO shipping policy, return policy, privacy policy, and terms of service.">
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=cairo:300,400,500,600,700,800&display=swap" rel="stylesheet" />
  <link href="{{ asset('css/website.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon/favicon.png') }}">
    <link href="{{ asset('css/website-terms-of-services.css') }}" rel="stylesheet">
    <script src="{{ asset('js/tailwind.js') }}"></script>
  <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>

  {{-- Navbar --}}
  @include('partials.nav-bar2')

  {{-- Mobile Menu --}}
  @include('partials.mobile-menu')

  {{-- Cart Drawer --}}
  @include('partials.cart-drawer')


  <!-- ══════════════════════════════════════════
     PAGE HERO
══════════════════════════════════════════ -->
  <section style="
    position:relative;
    padding:70px 40px 120px;
    background:#F8F6F2;
    overflow:hidden;
    border-bottom:1px solid #EDEDEB;
">

    <!-- Background Accent -->
    <div style="
        position:absolute;
        top:-180px;
        right:-120px;
        width:520px;
        height:520px;
        border-radius:50%;
        background:rgba(10,10,10,0.025);
        pointer-events:none;
    "></div>

    <div style="
        position:relative;
        max-width:1400px;
        margin:0 auto;
    ">

      <!-- Breadcrumb -->
      <div style="
            display:flex;
            align-items:center;
            gap:10px;
            margin-bottom:40px;
        ">

        <a href="{{ route('home') }}" style="
                    font-family:'Space Grotesk',sans-serif;
                    font-size:10px;
                    letter-spacing:.18em;
                    text-transform:uppercase;
                    color:#9C9A96;
                    text-decoration:none;
                    transition:color .3s ease;
               " onmouseover="this.style.color='#0A0A0A'" onmouseout="this.style.color='#9C9A96'">

          Home

        </a>

        <span style="
                color:#D5D3CF;
                font-size:10px;
            ">
          /
        </span>

        <span style="
                font-family:'Space Grotesk',sans-serif;
                font-size:10px;
                letter-spacing:.18em;
                text-transform:uppercase;
                color:#0A0A0A;
            ">
          Policies
        </span>

      </div>

      <!-- Main Grid -->
      <div style="
            display:grid;
            grid-template-columns:1.1fr 0.9fr;
            gap:120px;
            align-items:end;
        ">

        <!-- Left -->
        <div>

          <p style="
                    font-family:'Space Grotesk',sans-serif;
                    font-size:10px;
                    letter-spacing:.28em;
                    text-transform:uppercase;
                    color:#9C9A96;
                    margin-bottom:28px;
                ">
            Legal & Information
          </p>

          <h1 style="
                    font-family:'Cormorant Garamond',serif;
                    font-size:clamp(64px,8vw,130px);
                    font-weight:300;
                    letter-spacing:-.05em;
                    line-height:.88;
                    color:#0A0A0A;
                    margin:0;
                ">

            Our<br>

            <span style="
                        font-style:italic;
                        font-weight:400;
                    ">
              Policies
            </span>

          </h1>

        </div>

        <!-- Right -->
        <div>

          <div style="
                    width:72px;
                    height:1px;
                    background:#0A0A0A;
                    margin-bottom:32px;
                "></div>

          <p style="
                    font-family:'DM Sans',sans-serif;
                    font-size:15px;
                    font-weight:300;
                    color:#5A5A57;
                    line-height:2;
                    max-width:460px;
                    margin-bottom:40px;
                ">

            Everything you need to know about shipping,
            returns, privacy, and terms. Clear policies
            designed to create a smooth and transparent experience.

          </p>

          <!-- Mini Info -->
          <div style="
                    display:flex;
                    flex-wrap:wrap;
                    gap:48px;
                ">

            <div>
              <p style="
                            font-family:'Space Grotesk',sans-serif;
                            font-size:10px;
                            letter-spacing:.16em;
                            text-transform:uppercase;
                            color:#9C9A96;
                            margin-bottom:10px;
                        ">
                Updated
              </p>

              <p style="
                            font-family:'DM Sans',sans-serif;
                            font-size:14px;
                            color:#0A0A0A;
                        ">
                May 2026
              </p>
            </div>

            <div>
              <p style="
                            font-family:'Space Grotesk',sans-serif;
                            font-size:10px;
                            letter-spacing:.16em;
                            text-transform:uppercase;
                            color:#9C9A96;
                            margin-bottom:10px;
                        ">
                Support
              </p>

              <p style="
                            font-family:'DM Sans',sans-serif;
                            font-size:14px;
                            color:#0A0A0A;
                        ">
                info.ryo.brand@gmail.com
              </p>
            </div>

          </div>

        </div>

      </div>

    </div>

  </section>

<!-- ══════════════════════════════════════════
     MAIN LAYOUT
══════════════════════════════════════════ -->
<section style="
    background:#FAF8F5;
">


<div style="max-width:1200px;margin:0 auto;gap:64px;padding:0 40px 120px;" id="policyLayout">



  <!-- CONTENT -->
  <main style="min-width:0;">

    <!-- ── TERMS ── -->
    <div class="policy-section reveal" id="terms">

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


  </main>

</div>


</section>


  {{-- Newslater --}}
  @include('partials.newslater')


  {{-- Footer --}}
  @include('partials.footer')




<script src="{{ asset('js/website-terms-of-services.js') }}"></script>
  @if (Route::has('login'))
    <div class="h-14.5 hidden lg:block"></div>
  @endif


</body>

</html>
