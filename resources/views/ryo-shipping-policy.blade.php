<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Policies — RYO</title>
  <meta name="description" content="RYO shipping policy, return policy, privacy policy, and terms of service.">
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=cairo:300,400,500,600,700,800&display=swap" rel="stylesheet" />
  <link href="{{ asset('css/website.css') }}" rel="stylesheet">


  <script src="https://cdn.tailwindcss.com"></script>



  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'ryo-black': '#0A0A0A',
            'ryo-white': '#F8F6F2',
            'ryo-gray-100': '#EDEDEB',
            'ryo-gray-200': '#D5D3CF',
            'ryo-gray-400': '#9C9A96',
            'ryo-gray-700': '#3D3D3A',
            'ryo-silver': '#C8C6C2',
            'ryo-cream': '#F2EEE8',
          },
          fontFamily: {
            display: ['Cormorant Garamond', 'serif'],
            body: ['DM Sans', 'sans-serif'],
            label: ['Space Grotesk', 'sans-serif'],
          },
          transitionTimingFunction: {
            'luxury': 'cubic-bezier(0.25, 0.46, 0.45, 0.94)',
          },
          transitionDuration: {
            '400': '400ms',
            '600': '600ms',
            '800': '800ms',
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
  @include('partials.nav-bar2')

  {{-- Mobile Menu --}}
  @include('partials.mobile-menu')

  {{-- Cart Drawer --}}
  @include('partials.cart-drawer2')


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
                support@ryo.com
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

    <!-- ── SHIPPING ── -->
    <div class="policy-section reveal" id="shipping">

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

  </main>

</div>


</section>


  {{-- Newslater --}}
  @include('partials.newslater')


  {{-- Footer --}}
  @include('partials.footer')



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
    const sections = ['shipping', 'returns', 'privacy', 'terms', 'cookies', 'contact'];
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
      entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revObs.unobserve(e.target); } });
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


  @if (Route::has('login'))
    <div class="h-14.5 hidden lg:block"></div>
  @endif


</body>

</html>