<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RYO - Contact Us</title>
  <meta name="description" content="Get in touch with RYO. Customer support, boutique visits, press inquiries, and general questions.">
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

    /* CONTACT CARD STYLES */
    .contact-card-modern{
      background:#FFFFFF;
      border:1px solid #EDEDEB;
      transition:all 0.3s ease;
    }
    .contact-card-modern:hover{
      border-color:#D5D3CF;
      transform:translateY(-4px);
    }
    .contact-icon{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      width:48px;
      height:48px;
      background:#0A0A0A;
      color:#F8F6F2;
      font-family:'Space Grotesk',sans-serif;
      font-weight:400;
      font-size:20px;
      transition:all 0.2s ease;
    }
    .form-input, .form-textarea{
      width:100%;
      background:transparent;
      border:none;
      border-bottom:1px solid #D5D3CF;
      padding:14px 0 10px;
      font-family:'DM Sans',sans-serif;
      font-size:14px;
      font-weight:300;
      color:#0A0A0A;
      transition:border-color 0.2s ease;
      outline:none;
    }
    .form-input:focus, .form-textarea:focus{
      border-bottom-color:#0A0A0A;
    }
    .form-input::placeholder, .form-textarea::placeholder{
      color:#9C9A96;
      font-weight:300;
    }
    .submit-btn{
      background:#0A0A0A;
      color:#F8F6F2;
      font-family:'Space Grotesk',sans-serif;
      font-size:10px;
      letter-spacing:.2em;
      text-transform:uppercase;
      padding:16px 32px;
      border:none;
      cursor:pointer;
      transition:background 0.3s ease;
    }
    .submit-btn:hover{
      background:#3D3D3A;
    }
    .faq-item{
      border-bottom:1px solid #EDEDEB;
      padding:20px 0;
      cursor:pointer;
    }
    .faq-question{
      font-family:'DM Sans',sans-serif;
      font-size:14px;
      font-weight:500;
      letter-spacing:-0.2px;
      color:#0A0A0A;
      display:flex;
      justify-content:space-between;
      align-items:center;
    }
    .faq-answer{
      font-family:'DM Sans',sans-serif;
      font-size:13px;
      font-weight:300;
      color:#5A5A57;
      line-height:1.7;
      margin-top:12px;
      display:none;
    }
    .faq-item.open .faq-answer{
      display:block;
    }
    .faq-icon{
      font-size:20px;
      font-weight:300;
      transition:transform 0.2s ease;
    }
    .faq-item.open .faq-icon{
      transform:rotate(45deg);
    }
    .store-card{
      background:#FFFFFF;
      border:1px solid #EDEDEB;
      padding:28px;
    }
    @media (max-width: 768px) {
      .contact-hero-grid{grid-template-columns:1fr !important; gap:48px !important;}
      .contact-two-col{grid-template-columns:1fr !important; gap:48px !important;}
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
       CONTACT HERO SECTION (same styling as policies page)
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
        <span style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:#0A0A0A;">Contact</span>
      </div>

      <!-- Main Grid -->
      <div class="contact-hero-grid" style="display:grid;grid-template-columns:1.1fr 0.9fr;gap:120px;align-items:end;">
        <!-- Left -->
        <div>
          <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.28em;text-transform:uppercase;color:#9C9A96;margin-bottom:28px;">
            Reach out
          </p>
          <h1 style="font-family:'Cormorant Garamond',serif;font-size:clamp(64px,8vw,130px);font-weight:300;letter-spacing:-.05em;line-height:.88;color:#0A0A0A;margin:0;">
            Let's<br>
            <span style="font-style:italic;font-weight:400;">Connect</span>
          </h1>
        </div>

        <!-- Right -->
        <div>
          <div style="width:72px;height:1px;background:#0A0A0A;margin-bottom:32px;"></div>
          <p style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:300;color:#5A5A57;line-height:2;max-width:460px;margin-bottom:40px;">
            Whether you have a question about an order, need styling advice, or simply want to say hello — our team is here to assist with elegance and efficiency.
          </p>
          <div style="display:flex;flex-wrap:wrap;gap:48px;">
            <div>
              <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.16em;text-transform:uppercase;color:#9C9A96;margin-bottom:10px;">Response Time</p>
              <p style="font-family:'DM Sans',sans-serif;font-size:14px;color:#0A0A0A;">Within 24 hours</p>
            </div>
            <div>
              <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.16em;text-transform:uppercase;color:#9C9A96;margin-bottom:10px;">Live Chat</p>
              <p style="font-family:'DM Sans',sans-serif;font-size:14px;color:#0A0A0A;">Mon–Fri, 10AM–6PM</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- ══════════════════════════════════════════
       CONTACT INFORMATION CARDS
══════════════════════════════════════════ -->
  <section style="background:#FAF8F5;padding:80px 40px 0;">
    <div style="max-width:1200px;margin:0 auto;">
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:40px;" class="reveal">

        <!-- Email Card -->
        <div class="contact-card-modern" style="padding:36px 28px;">
          <div class="contact-icon" style="margin-bottom:24px;">✉</div>
          <h3 style="font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.2em;text-transform:uppercase;margin-bottom:12px;">Email Us</h3>
          <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#3D3D3A;margin-bottom:8px;">General inquiries:</p>
          <a href="mailto:hello@ryo.com" style="font-family:'DM Sans',sans-serif;font-size:16px;font-weight:400;color:#0A0A0A;text-decoration:none;border-bottom:1px solid #D5D3CF;">hello@ryo.com</a>
          <p style="margin-top:16px;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">For press & collaborations: <a href="mailto:press@ryo.com" style="color:#0A0A0A;border-bottom:1px solid #D5D3CF;">press@ryo.com</a></p>
        </div>

        <!-- Phone / WhatsApp Card -->
        <div class="contact-card-modern" style="padding:36px 28px;">
          <div class="contact-icon" style="margin-bottom:24px;">📞</div>
          <h3 style="font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.2em;text-transform:uppercase;margin-bottom:12px;">Call or WhatsApp</h3>
          <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#3D3D3A;margin-bottom:8px;">Customer support:</p>
          <a href="tel:+201234567890" style="font-family:'DM Sans',sans-serif;font-size:16px;font-weight:400;color:#0A0A0A;text-decoration:none;border-bottom:1px solid #D5D3CF;">+20 123 456 7890</a>
          <p style="margin-top:16px;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">Mon–Fri, 10AM – 6PM (GMT+2)</p>
        </div>

        <!-- Boutique / Showroom Card -->
        <div class="contact-card-modern" style="padding:36px 28px;">
          <div class="contact-icon" style="margin-bottom:24px;">📍</div>
          <h3 style="font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.2em;text-transform:uppercase;margin-bottom:12px;">Visit Us</h3>
          <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#3D3D3A;margin-bottom:4px;">RYO Atelier – Zamalek</p>
          <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#5A5A57;">12 Brazil Street, Cairo, Egypt</p>
          <p style="margin-top:12px;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">By appointment only</p>
        </div>
      </div>
    </div>
  </section>


  <!-- ══════════════════════════════════════════
       CONTACT FORM + FAQ (TWO COLUMN LAYOUT)
══════════════════════════════════════════ -->
  <section style="background:#FAF8F5;padding:80px 40px 100px;">
    <div style="max-width:1200px;margin:0 auto;">
      <div class="contact-two-col" style="display:grid;grid-template-columns:1fr 1fr;gap:80px;">

        <!-- LEFT: Contact Form -->
        <div class="reveal">
          <div style="margin-bottom:32px;">
            <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;margin-bottom:12px;">Send a message</p>
            <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,4vw,44px);font-weight:300;letter-spacing:-.02em;line-height:1.2;">We’d love to hear from you</h2>
          </div>

          <form id="contactForm" action="#" method="POST">
            @csrf
            <div style="margin-bottom:36px;">
              <input type="text" name="full_name" class="form-input" placeholder="Full name" required>
            </div>
            <div style="margin-bottom:36px;">
              <input type="email" name="email" class="form-input" placeholder="Email address" required>
            </div>
            <div style="margin-bottom:36px;">
              <input type="tel" name="phone" class="form-input" placeholder="Phone number (optional)">
            </div>
            <div style="margin-bottom:48px;">
              <textarea name="message" rows="4" class="form-textarea" placeholder="How can we assist you?" required></textarea>
            </div>
            <button type="submit" class="submit-btn">Send message →</button>
            <p style="font-family:'DM Sans',sans-serif;font-size:11px;font-weight:300;color:#9C9A96;margin-top:20px;">We'll get back to you within 24 hours.</p>
          </form>
        </div>

        <!-- RIGHT: FAQ Section (accordion style) -->
        <div class="reveal">
          <div style="margin-bottom:32px;">
            <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;margin-bottom:12px;">Common questions</p>
            <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,4vw,44px);font-weight:300;letter-spacing:-.02em;line-height:1.2;">Quick answers</h2>
          </div>

          <div class="faq-list">
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>How long does shipping take?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Domestic orders (Egypt): 2–4 business days. International shipping: 7–12 business days. You’ll receive a tracking link once shipped.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>What is your return policy?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">We accept returns within 14 days of delivery. Items must be unworn, unwashed, with original tags attached. For more details, see our Returns section.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>Can I modify or cancel my order?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Orders can be modified or canceled within 1 hour of placement. Please contact our support team immediately with your order number.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>Do you offer international shipping?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Yes, we ship worldwide. Shipping costs and duties are calculated at checkout based on destination.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>How can I track my order?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Once your order is dispatched, you’ll receive an email with tracking details and a link to monitor delivery status.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- ══════════════════════════════════════════
       STORE LOCATION / MAP SECTION
══════════════════════════════════════════ -->
  <section style="background:#F8F6F2;padding:40px 40px 100px;">
    <div style="max-width:1200px;margin:0 auto;">
      <div class="reveal" style="display:grid;grid-template-columns:1fr 1.2fr;gap:60px;align-items:center;">
        <!-- Left Text -->
        <div>
          <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.28em;text-transform:uppercase;color:#9C9A96;margin-bottom:16px;">Visit our atelier</p>
          <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(32px,4vw,48px);font-weight:300;letter-spacing:-.02em;line-height:1.2;margin-bottom:20px;">Experience RYO in person</h2>
          <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#3D3D3A;line-height:1.8;margin-bottom:28px;">Our Zamalek boutique is a minimalist sanctuary where you can discover our latest collections, receive personal styling consultations, and enjoy a curated selection of limited pieces.</p>
          <div class="store-card" style="padding:0;background:transparent;border:none;">
            <div style="display:flex;gap:24px;flex-wrap:wrap;">
              <div>
                <p style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.16em;text-transform:uppercase;color:#9C9A96;">Address</p>
                <p style="font-family:'DM Sans',sans-serif;font-size:14px;color:#0A0A0A;margin-top:6px;">12 Brazil Street, Zamalek, Cairo</p>
              </div>
              <div>
                <p style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.16em;text-transform:uppercase;color:#9C9A96;">Hours</p>
                <p style="font-family:'DM Sans',sans-serif;font-size:14px;color:#0A0A0A;margin-top:6px;">Tuesday – Saturday, 11AM – 7PM</p>
              </div>
            </div>
            <a href="#" style="display:inline-block;margin-top:28px;font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:#0A0A0A;border-bottom:1px solid #0A0A0A;padding-bottom:4px;text-decoration:none;">Book an appointment →</a>
          </div>
        </div>
        <!-- Right Map (placeholder with same design system) -->
        <div style="background:#EDEDEB;height:320px;display:flex;align-items:center;justify-content:center;border:1px solid #D5D3CF;">
          <div style="text-align:center;">
            <span style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;color:#9C9A96;">Interactive map</span>
            <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#3D3D3A;margin-top:12px;">12 Brazil Street, Zamalek</p>
            <div style="margin-top:16px;width:40px;height:40px;background:#0A0A0A;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;color:white;font-size:18px;">📍</div>
          </div>
        </div>
      </div>
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
      entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revObs.unobserve(e.target); } });
    }, { threshold: 0.08 });
    document.querySelectorAll('.reveal').forEach(el => revObs.observe(el));

    // ── FAQ ACCORDION ──
    function toggleFaq(element) {
      element.classList.toggle('open');
    }

    // ── FORM SUBMIT (prevent default + console simulation) ──
    const contactForm = document.getElementById('contactForm');
    if(contactForm) {
      contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Thank you for reaching out. Our team will respond within 24 hours.');
        contactForm.reset();
      });
    }

    // ── RESPONSIVE (no sidebar needed but keep consistency) ──
    function checkLayout() {
      // nothing critical for contact page, but keep pattern
    }
    checkLayout();
    window.addEventListener('resize', checkLayout);
  </script>

  @if (Route::has('login'))
    <div class="h-14.5 hidden lg:block"></div>
  @endif

</body>

</html>
