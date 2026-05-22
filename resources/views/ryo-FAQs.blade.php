<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RYO - Frequently Asked Questions</title>
  <meta name="description" content="Find answers to common questions about RYO — shipping, returns, sizing, materials, orders, and more. Fast, transparent support.">
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

    /* FAQ ACCORDION STYLES (refined) */
    .faq-category{
      margin-bottom:48px;
    }
    .faq-category h3{
      font-family:'Space Grotesk',sans-serif;
      font-size:11px;
      letter-spacing:.2em;
      text-transform:uppercase;
      color:#9C9A96;
      margin-bottom:28px;
      padding-bottom:8px;
      border-bottom:1px solid #EDEDEB;
      display:inline-block;
    }
    .faq-item{
      border-bottom:1px solid #EDEDEB;
      padding:24px 0;
      cursor:pointer;
      transition:background 0.2s ease;
    }
    .faq-question{
      font-family:'DM Sans',sans-serif;
      font-size:16px;
      font-weight:500;
      letter-spacing:-0.2px;
      color:#0A0A0A;
      display:flex;
      justify-content:space-between;
      align-items:center;
    }
    .faq-answer{
      font-family:'DM Sans',sans-serif;
      font-size:14px;
      font-weight:300;
      color:#5A5A57;
      line-height:1.75;
      margin-top:16px;
      display:none;
      padding-right:24px;
    }
    .faq-item.open .faq-answer{
      display:block;
    }
    .faq-icon{
      font-size:22px;
      font-weight:300;
      transition:transform 0.25s ease;
      color:#9C9A96;
    }
    .faq-item.open .faq-icon{
      transform:rotate(45deg);
      color:#0A0A0A;
    }
    .contact-prompt{
      background:#EDEDEB;
      padding:48px 40px;
      text-align:center;
      margin-top:60px;
    }
    @media (max-width: 768px) {
      .faq-hero-grid{grid-template-columns:1fr !important; gap:48px !important;}
      .faq-two-col{grid-template-columns:1fr !important; gap:48px !important;}
      .faq-question{font-size:15px;}
    }
  </style>
</head>

<body>

  {{-- Navbar --}}
  @include('partials.nav-bar2')

  {{-- Mobile Menu --}}
  @include('partials.mobile-menu')

  {{-- Cart Drawer --}}
  @include('partials.cart-drawer')


  <!-- ══════════════════════════════════════════
       FAQ HERO SECTION (identical layout to policies)
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
        <span style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:#0A0A0A;">FAQ</span>
      </div>

      <!-- Main Grid -->
      <div class="faq-hero-grid" style="display:grid;grid-template-columns:1.1fr 0.9fr;gap:120px;align-items:end;">
        <!-- Left -->
        <div>
          <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.28em;text-transform:uppercase;color:#9C9A96;margin-bottom:28px;">
            Knowledge base
          </p>
          <h1 style="font-family:'Cormorant Garamond',serif;font-size:clamp(64px,8vw,130px);font-weight:300;letter-spacing:-.05em;line-height:.88;color:#0A0A0A;margin:0;">
            Your<br>
            <span style="font-style:italic;font-weight:400;">Questions</span>
          </h1>
        </div>

        <!-- Right -->
        <div>
          <div style="width:72px;height:1px;background:#0A0A0A;margin-bottom:32px;"></div>
          <p style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:300;color:#5A5A57;line-height:2;max-width:460px;margin-bottom:40px;">
            Everything you need to know about ordering, shipping, returns, sizing, and care. Can't find an answer? Our team is just a message away.
          </p>
          <div style="display:flex;flex-wrap:wrap;gap:48px;">
            <div>
              <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.16em;text-transform:uppercase;color:#9C9A96;margin-bottom:10px;">Still need help?</p>
              <p style="font-family:'DM Sans',sans-serif;font-size:14px;color:#0A0A0A;"><a href="{{ route('contact-us') }}" style="color:#0A0A0A;border-bottom:1px solid #D5D3CF;">Contact support →</a></p>
            </div>
            <div>
              <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.16em;text-transform:uppercase;color:#9C9A96;margin-bottom:10px;">Average response</p>
              <p style="font-family:'DM Sans',sans-serif;font-size:14px;color:#0A0A0A;">Within 12 hours</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- ══════════════════════════════════════════
       FAQ MAIN CONTENT (two column layout: categories + accordions)
══════════════════════════════════════════ -->
  <section style="background:#FAF8F5;padding:80px 40px 100px;">
    <div style="max-width:1200px;margin:0 auto;">
      <div class="faq-two-col" style="display:grid;grid-template-columns:280px 1fr;gap:80px;">

        <!-- LEFT: Category sidebar (sticky) -->
        <div style="position:sticky;top:80px;align-self:start;" class="reveal">
          <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.16em;text-transform:uppercase;color:#9C9A96;margin-bottom:24px;">Browse by topic</p>
          <ul style="list-style:none;padding:0;">
            <li style="margin-bottom:16px;"><a href="#orders" class="category-link" style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9C9A96;text-decoration:none;transition:color 0.2s;">Orders & Payment</a></li>
            <li style="margin-bottom:16px;"><a href="#shipping" class="category-link" style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9C9A96;text-decoration:none;transition:color 0.2s;">Shipping & Delivery</a></li>
            <li style="margin-bottom:16px;"><a href="#returns" class="category-link" style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9C9A96;text-decoration:none;transition:color 0.2s;">Returns & Exchanges</a></li>
            <li style="margin-bottom:16px;"><a href="#sizing" class="category-link" style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9C9A96;text-decoration:none;transition:color 0.2s;">Sizing & Fit</a></li>
            <li style="margin-bottom:16px;"><a href="#care" class="category-link" style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9C9A96;text-decoration:none;transition:color 0.2s;">Product Care</a></li>
            <li style="margin-bottom:16px;"><a href="#sustainability" class="category-link" style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9C9A96;text-decoration:none;transition:color 0.2s;">Sustainability</a></li>
          </ul>
        </div>

        <!-- RIGHT: FAQ accordions grouped by category -->
        <div>
          <!-- ORDERS & PAYMENT -->
          <div id="orders" class="faq-category reveal">
            <h3>Orders & Payment</h3>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>How do I place an order?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Simply browse our collection, select your size and quantity, and click "Add to Cart". When you're ready, proceed to checkout, enter your shipping details, choose a payment method, and confirm your order. You'll receive a confirmation email shortly after.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>What payment methods do you accept?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">We accept Visa, Mastercard, American Express, mobile wallets (Apple Pay, Google Pay), and Cash on Delivery for domestic orders within Egypt. All payments are processed securely.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>Can I cancel or modify my order after placing it?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Orders can be modified or canceled within 1 hour of placement. Please contact our support team immediately with your order number. Once processed for shipping, changes are no longer possible.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>Is my payment information secure?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Absolutely. We use SSL encryption and PCI-compliant payment gateways. Your credit card details are never stored on our servers.</div>
            </div>
          </div>

          <!-- SHIPPING & DELIVERY -->
          <div id="shipping" class="faq-category reveal">
            <h3>Shipping & Delivery</h3>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>How long does shipping take?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Domestic orders (Egypt): 2–4 business days. International shipping: 7–12 business days. Please note that customs clearance may cause delays for international orders.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>How much does shipping cost?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Shipping costs are calculated at checkout based on your location and order value. Domestic shipping starts at 50 EGP. Free domestic shipping on orders over 2,500 EGP. International rates vary by region.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>Can I track my order?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Yes. Once your order is dispatched, you'll receive an email with a tracking number and a link to monitor your shipment's progress.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>Do you ship internationally?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Yes, we ship worldwide. Please note that international orders may be subject to import duties and taxes, which are the responsibility of the customer.</div>
            </div>
          </div>

          <!-- RETURNS & EXCHANGES -->
          <div id="returns" class="faq-category reveal">
            <h3>Returns & Exchanges</h3>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>What is your return policy?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">We accept returns within 14 days of delivery. Items must be unworn, unwashed, with all original tags attached. For hygiene reasons, we cannot accept returns on underwear, swimwear, or earrings.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>How do I initiate a return?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Contact our support team at returns@ryo.com with your order number and the item(s) you wish to return. We'll provide you with a return shipping label and instructions.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>How long does it take to receive a refund?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Once we receive and inspect your return, refunds are processed within 5–7 business days. The credit will appear on your original payment method.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>Can I exchange an item?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Yes. Follow the same return process and indicate that you'd like an exchange. If the desired size or color is available, we'll ship it to you at no additional shipping cost.</div>
            </div>
          </div>

          <!-- SIZING & FIT -->
          <div id="sizing" class="faq-category reveal">
            <h3>Sizing & Fit</h3>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>How do I find my size?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Each product page includes a detailed size guide with measurements in centimeters and inches. We recommend comparing the guide to a similar garment you already own.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>Do your clothes run true to size?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Most RYO pieces are designed with a relaxed, architectural fit. We recommend checking the specific product measurements, as some styles are intentionally oversized. Customer reviews often include fit notes.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>What if I'm between sizes?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">If you're between sizes, we suggest sizing up for a more comfortable, effortless silhouette. Many of our designs feature adjustable elements like ties or elastic waistbands.</div>
            </div>
          </div>

          <!-- PRODUCT CARE -->
          <div id="care" class="faq-category reveal">
            <h3>Product Care</h3>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>How should I wash my RYO garments?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Each garment includes a care label. As a general rule, we recommend machine washing cold on a gentle cycle with similar colors, and air drying away from direct sunlight. Avoid bleach and fabric softeners.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>Do you offer repair services?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Yes, we offer complimentary minor repairs for RYO products purchased within the last two years. Contact our support team to arrange a repair.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>How can I remove wrinkles from linen pieces?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Linen is naturally textured. We embrace its relaxed character. If you prefer a crisper look, steam the garment on a low setting or iron while slightly damp.</div>
            </div>
          </div>

          <!-- SUSTAINABILITY -->
          <div id="sustainability" class="faq-category reveal">
            <h3>Sustainability</h3>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>What materials do you use?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">We prioritize natural, renewable fibers: organic cotton, linen, TENCEL™ Lyocell, and recycled polyesters. All materials are Oeko-Tex certified or equivalent.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>Are your products ethically made?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Absolutely. We partner with small, family-owned workshops in Egypt that provide fair wages, safe conditions, and no child labor. Our entire supply chain is audited annually.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>Do you use sustainable packaging?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Yes. All orders are shipped in 100% recycled and recyclable cardboard boxes or compostable mailers. We avoid plastic polybags whenever possible.</div>
            </div>
          </div>

          <!-- CONTACT PROMPT -->
          <div class="contact-prompt reveal" style="margin-top:48px;">
            <p style="font-family:'Cormorant Garamond',serif;font-size:26px;font-weight:300;font-style:italic;margin-bottom:16px;">Still have questions?</p>
            <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#3D3D3A;margin-bottom:24px;">Our support team is ready to assist you with any unanswered inquiries.</p>
            <a href="{{ route('contact-us') }}" style="display:inline-block;background:#0A0A0A;color:#F8F6F2;font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;text-transform:uppercase;padding:14px 32px;text-decoration:none;transition:background 0.3s;">Contact us →</a>
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
    // ── CART & MENU (standard) ──
    function toggleCart() {
      document.getElementById('cartDrawer')?.classList.toggle('open');
      document.getElementById('cartOverlay')?.classList.toggle('open');
    }
    function toggleMenu() {
      document.getElementById('mobileMenu')?.classList.toggle('open');
    }

    // ── FAQ ACCORDION ──
    function toggleFaq(element) {
      element.classList.toggle('open');
    }

    // ── SCROLL REVEAL ──
    const revObs = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revObs.unobserve(e.target); } });
    }, { threshold: 0.08 });
    document.querySelectorAll('.reveal').forEach(el => revObs.observe(el));

    // ── SIDEBAR HIGHLIGHT ON SCROLL (category links) ──
    const sections = ['orders', 'shipping', 'returns', 'sizing', 'care', 'sustainability'];
    const categoryLinks = document.querySelectorAll('.category-link');

    window.addEventListener('scroll', () => {
      let current = '';
      sections.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
          const rect = el.getBoundingClientRect();
          if (rect.top < 150 && rect.bottom > 100) current = id;
        }
      });
      categoryLinks.forEach(link => {
        const href = link.getAttribute('href').substring(1);
        if (href === current) {
          link.style.color = '#0A0A0A';
          link.style.fontWeight = '500';
        } else {
          link.style.color = '#9C9A96';
          link.style.fontWeight = '400';
        }
      });
    });

    // Smooth scroll for category links
    categoryLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        const targetId = link.getAttribute('href').substring(1);
        const target = document.getElementById(targetId);
        if (target) {
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    });

    // ── RESPONSIVE CHECK ──
    function checkLayout() {
      // no structural changes needed for faq page, but keep for consistency
    }
    checkLayout();
    window.addEventListener('resize', checkLayout);
  </script>

  @if (Route::has('login'))
    <div class="h-14.5 hidden lg:block"></div>
  @endif

</body>

</html>
