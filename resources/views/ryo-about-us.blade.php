<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RYO - About Us</title>
  <meta name="description" content="Discover the story behind RYO — minimalist luxury, timeless design, and Egyptian craftsmanship. Learn about our philosophy, materials, and commitment to slow fashion.">
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=cairo:300,400,500,600,700,800&display=swap" rel="stylesheet" />
  <link href="{{ asset('css/website.css') }}" rel="stylesheet">
  <link rel="icon" type="image/png" href="{{ asset('images/favicon/favicon.png') }}">

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

    /* VALUE CARD */
    .value-card{
      background:#FFFFFF;
      border:1px solid #EDEDEB;
      padding:36px 28px;
      transition:all 0.3s ease;
    }
    .value-card:hover{
      border-color:#D5D3CF;
      transform:translateY(-4px);
    }
    .milestone-dot{
      width:6px;
      height:6px;
      background:#0A0A0A;
      border-radius:50%;
      margin-right:20px;
      flex-shrink:0;
      margin-top:10px;
    }
    .team-member{
      text-align:center;
      transition:opacity 0.3s ease;
    }
    .team-member:hover .member-img{
      opacity:0.92;
    }
    .member-img{
      width:100%;
      aspect-ratio:1/1;
      background:#EDEDEB;
      margin-bottom:20px;
      transition:opacity 0.3s ease;
    }
    @media (max-width: 768px) {
      .about-hero-grid{grid-template-columns:1fr !important; gap:48px !important;}
      .about-two-col{grid-template-columns:1fr !important; gap:48px !important;}
      .milestone-item{flex-direction:column; gap:8px;}
      .milestone-dot{margin-top:0;}
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
       ABOUT HERO SECTION (same elegance as policies)
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
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:40px;">
        <a href="{{ route('home') }}" style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:#9C9A96;text-decoration:none;transition:color .3s ease;" onmouseover="this.style.color='#0A0A0A'" onmouseout="this.style.color='#9C9A96'">Home</a>
        <span style="color:#D5D3CF;font-size:10px;">/</span>
        <span style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:#0A0A0A;">About</span>
      </div>

      <!-- Main Grid -->
      <div class="about-hero-grid" style="display:grid;grid-template-columns:1.1fr 0.9fr;gap:120px;align-items:end;">
        <!-- Left -->
        <div>
          <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.28em;text-transform:uppercase;color:#9C9A96;margin-bottom:28px;">
            The story
          </p>
          <h1 style="font-family:'Cormorant Garamond',serif;font-size:clamp(64px,8vw,130px);font-weight:300;letter-spacing:-.05em;line-height:.88;color:#0A0A0A;margin:0;">
            RYO<br>
            <span style="font-style:italic;font-weight:400;">Essentials</span>
          </h1>
        </div>

        <!-- Right -->
        <div>
          <div style="width:72px;height:1px;background:#0A0A0A;margin-bottom:32px;"></div>
          <p style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:300;color:#5A5A57;line-height:2;max-width:460px;margin-bottom:40px;">
            Born from a desire to create timeless pieces that transcend seasons and trends. RYO is a celebration of slow fashion, honest materials, and Egyptian craftsmanship.
          </p>
          <div style="display:flex;flex-wrap:wrap;gap:48px;">
            <div>
              <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.16em;text-transform:uppercase;color:#9C9A96;margin-bottom:10px;">Founded</p>
              <p style="font-family:'DM Sans',sans-serif;font-size:14px;color:#0A0A0A;">2020</p>
            </div>
            <div>
              <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.16em;text-transform:uppercase;color:#9C9A96;margin-bottom:10px;">Origin</p>
              <p style="font-family:'DM Sans',sans-serif;font-size:14px;color:#0A0A0A;">Cairo, Egypt</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- ══════════════════════════════════════════
       PHILOSOPHY SECTION (two column with image placeholder)
══════════════════════════════════════════ -->
  <section style="background:#FAF8F5;padding:100px 40px;">
    <div style="max-width:1200px;margin:0 auto;">
      <div class="about-two-col" style="display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center;">
        <!-- Left: Text -->
        <div class="reveal">
          <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.28em;text-transform:uppercase;color:#9C9A96;margin-bottom:20px;">Our philosophy</p>
          <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(36px,5vw,58px);font-weight:300;letter-spacing:-.02em;line-height:1.15;margin-bottom:28px;">Silence speaks volumes</h2>
          <p style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:300;color:#3D3D3A;line-height:1.85;margin-bottom:24px;">In a world saturated with noise, RYO chooses restraint. Each garment is stripped to its essential form — no unnecessary embellishments, no fleeting trends. What remains is pure architecture for the body.</p>
          <p style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:300;color:#3D3D3A;line-height:1.85;margin-bottom:32px;">We believe that true luxury lies in the invisible details: the hand of the fabric, the precision of a seam, the way a garment moves with you. Our collections are designed to be lived in, cherished, and passed down.</p>
          <div style="width:48px;height:1px;background:#0A0A0A;"></div>
        </div>
        <!-- Right: Image Placeholder (same aesthetic as map in contact) -->
        <div class="reveal" style="background:#EDEDEB;height:460px;display:flex;align-items:center;justify-content:center;border:1px solid #D5D3CF;">
          <div style="text-align:center;">
            <span style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;color:#9C9A96;">Atelier detail</span>
            <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#3D3D3A;margin-top:12px;">Natural light • Egyptian linen</p>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- ══════════════════════════════════════════
       VALUES SECTION (3 cards)
══════════════════════════════════════════ -->
  <section style="background:#F8F6F2;padding:80px 40px;">
    <div style="max-width:1200px;margin:0 auto;">
      <div style="text-align:center;margin-bottom:64px;" class="reveal">
        <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.28em;text-transform:uppercase;color:#9C9A96;">What we stand for</p>
        <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(36px,4.5vw,52px);font-weight:300;letter-spacing:-.02em;margin-top:16px;">Core values</h2>
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:40px;">
        <div class="value-card reveal">
          <div style="font-size:32px;margin-bottom:24px;">🕊️</div>
          <h3 style="font-family:'Space Grotesk',sans-serif;font-size:12px;letter-spacing:.2em;text-transform:uppercase;margin-bottom:16px;">Slow & deliberate</h3>
          <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#5A5A57;line-height:1.7;">We produce in small, considered batches. Each piece is crafted with intention, never mass-produced. Quality over quantity, always.</p>
        </div>
        <div class="value-card reveal">
          <div style="font-size:32px;margin-bottom:24px;">🌿</div>
          <h3 style="font-family:'Space Grotesk',sans-serif;font-size:12px;letter-spacing:.2em;text-transform:uppercase;margin-bottom:16px;">Ethical craft</h3>
          <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#5A5A57;line-height:1.7;">We partner with local artisans who receive fair wages and work in safe conditions. Our supply chain is transparent and traceable.</p>
        </div>
        <div class="value-card reveal">
          <div style="font-size:32px;margin-bottom:24px;">♻️</div>
          <h3 style="font-family:'Space Grotesk',sans-serif;font-size:12px;letter-spacing:.2em;text-transform:uppercase;margin-bottom:16px;">Responsible materials</h3>
          <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#5A5A57;line-height:1.7;">From organic cotton to deadstock fabrics, we prioritize materials that respect both the wearer and the planet.</p>
        </div>
      </div>
    </div>
  </section>


  <!-- ══════════════════════════════════════════
       JOURNEY / TIMELINE SECTION
══════════════════════════════════════════ -->
  <section style="background:#FAF8F5;padding:100px 40px;">
    <div style="max-width:1000px;margin:0 auto;">
      <div style="text-align:center;margin-bottom:64px;" class="reveal">
        <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.28em;text-transform:uppercase;color:#9C9A96;">The journey</p>
        <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(36px,4.5vw,52px);font-weight:300;letter-spacing:-.02em;margin-top:16px;">Milestones</h2>
      </div>
      <div>
        <div class="milestone-item reveal" style="display:flex;margin-bottom:48px;">
          <div class="milestone-dot"></div>
          <div>
            <p style="font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.16em;color:#9C9A96;margin-bottom:8px;">2020</p>
            <h3 style="font-family:'DM Sans',sans-serif;font-size:18px;font-weight:500;margin-bottom:12px;">The founding</h3>
            <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#5A5A57;line-height:1.7;">RYO is established in Cairo with a capsule collection of 8 essential pieces, crafted in collaboration with local artisans.</p>
          </div>
        </div>
        <div class="milestone-item reveal" style="display:flex;margin-bottom:48px;">
          <div class="milestone-dot"></div>
          <div>
            <p style="font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.16em;color:#9C9A96;margin-bottom:8px;">2022</p>
            <h3 style="font-family:'DM Sans',sans-serif;font-size:18px;font-weight:500;margin-bottom:12px;">First flagship space</h3>
            <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#5A5A57;line-height:1.7;">We open our first boutique in Zamalek — a serene space that embodies the RYO ethos of quiet luxury.</p>
          </div>
        </div>
        <div class="milestone-item reveal" style="display:flex;margin-bottom:48px;">
          <div class="milestone-dot"></div>
          <div>
            <p style="font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.16em;color:#9C9A96;margin-bottom:8px;">2024</p>
            <h3 style="font-family:'DM Sans',sans-serif;font-size:18px;font-weight:500;margin-bottom:12px;">Global shipping launch</h3>
            <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#5A5A57;line-height:1.7;">RYO begins shipping worldwide, bringing Egyptian minimalism to customers across Europe, the Middle East, and North America.</p>
          </div>
        </div>
        <div class="milestone-item reveal" style="display:flex;">
          <div class="milestone-dot"></div>
          <div>
            <p style="font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.16em;color:#9C9A96;margin-bottom:8px;">2025</p>
            <h3 style="font-family:'DM Sans',sans-serif;font-size:18px;font-weight:500;margin-bottom:12px;">Sustainability pledge</h3>
            <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#5A5A57;line-height:1.7;">We commit to using 90% sustainable fabrics and achieve zero-waste pattern cutting across our entire production.</p>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- ══════════════════════════════════════════
       CRAFTSMANSHIP SPOTLIGHT
══════════════════════════════════════════ -->
  <section style="background:#F8F6F2;padding:80px 40px 100px;">
    <div style="max-width:1200px;margin:0 auto;">
      <div class="about-two-col" style="display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center;direction:ltr;">
        <!-- Left: Image placeholder -->
        <div class="reveal" style="background:#EDEDEB;height:420px;display:flex;align-items:center;justify-content:center;border:1px solid #D5D3CF;order:1;">
          <div style="text-align:center;">
            <span style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;color:#9C9A96;">Handcrafted in Cairo</span>
            <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#3D3D3A;margin-top:12px;">Generational expertise</p>
          </div>
        </div>
        <!-- Right: Text -->
        <div class="reveal" style="order:2;">
          <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.28em;text-transform:uppercase;color:#9C9A96;margin-bottom:20px;">Behind the seams</p>
          <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(32px,4vw,48px);font-weight:300;letter-spacing:-.02em;line-height:1.2;margin-bottom:28px;">Masters of their craft</h2>
          <p style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:300;color:#3D3D3A;line-height:1.85;margin-bottom:24px;">Every RYO garment is brought to life by a small team of artisans in our Cairo atelier. Many have spent decades perfecting their techniques — from pattern making to hand-finishing.</p>
          <p style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:300;color:#3D3D3A;line-height:1.85;margin-bottom:24px;">We honor their skill by allowing each piece the time it deserves. No rush, no shortcuts. Just honest, meticulous work.</p>
          <div style="width:48px;height:1px;background:#0A0A0A;"></div>
        </div>
      </div>
    </div>
  </section>


  <!-- ══════════════════════════════════════════
       TEAM / FOUNDER MESSAGE (simple & elegant)
══════════════════════════════════════════ -->
  <section style="background:#FAF8F5;padding:80px 40px 120px;">
    <div style="max-width:900px;margin:0 auto;text-align:center;" class="reveal">
      <div style="width:80px;height:80px;background:#EDEDEB;border-radius:50%;margin:0 auto 28px;display:flex;align-items:center;justify-content:center;border:1px solid #D5D3CF;">
        <span style="font-family:'Cormorant Garamond',serif;font-size:32px;font-style:italic;">RY</span>
      </div>
      <p style="font-family:'Cormorant Garamond',serif;font-size:clamp(22px,3vw,30px);font-weight:300;font-style:italic;color:#0A0A0A;line-height:1.5;margin-bottom:28px;">“We design for those who appreciate the quiet power of simplicity. Clothes should never compete with the wearer — they should support, elevate, and fade into the background of a life well lived.”</p>
      <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;">— Rana Youssef, Founder & Creative Director</p>
    </div>
  </section>


  {{-- Newsletter --}}
  @include('partials.newslater')

  {{-- Footer --}}
  @include('partials.footer')


  <script>
    // ── CART & MENU (same as policy page) ──
    function toggleCart() {
      document.getElementById('cartDrawer')?.classList.toggle('open');
      document.getElementById('cartOverlay')?.classList.toggle('open');
    }
    function toggleMenu() {
      document.getElementById('mobileMenu')?.classList.toggle('open');
    }

    // ── SCROLL REVEAL ──
    const revObs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.classList.add('visible');
          revObs.unobserve(e.target);
        }
      });
    }, { threshold: 0.08 });
    document.querySelectorAll('.reveal').forEach(el => revObs.observe(el));

    // ── RESPONSIVE (no sidebar needed but maintain consistency) ──
    function checkLayout() {
      // nothing critical for about page
    }
    checkLayout();
    window.addEventListener('resize', checkLayout);
  </script>

  @if (Route::has('login'))
    <div class="h-14.5 hidden lg:block"></div>
  @endif

</body>

</html>
