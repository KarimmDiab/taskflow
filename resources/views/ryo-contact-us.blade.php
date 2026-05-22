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
  <link href="{{ asset('css/website-contact-us.css') }}" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="{{ asset('js/tailwind.js') }}"></script>

</head>

<body>

  {{-- Navbar --}}
  @include('partials.nav-bar2')

  {{-- Mobile Menu --}}
  @include('partials.mobile-menu')

  {{-- Cart Drawer --}}
  @include('partials.cart-drawer')


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
              <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.16em;text-transform:uppercase;color:#9C9A96;margin-bottom:10px;">Customer Care</p>
              <p style="font-family:'DM Sans',sans-serif;font-size:14px;color:#0A0A0A;">Available 24 Hours • Every Day</p>
            </div>
            <div>
              <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.16em;text-transform:uppercase;color:#9C9A96;margin-bottom:10px;">Live Support</p>
              <p style="font-family:'DM Sans',sans-serif;font-size:14px;color:#0A0A0A;">Always Here For You</p>
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
          <a href="mailto:hello@ryo.com" style="font-family:'DM Sans',sans-serif;font-size:16px;font-weight:400;color:#0A0A0A;text-decoration:none;border-bottom:1px solid #D5D3CF;">info.ryo.brand@gmail.com</a>
          <p style="margin-top:16px;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">For press & collaborations: <a href="mailto:press@ryo.com" style="color:#0A0A0A;border-bottom:1px solid #D5D3CF;">info.ryo.brand@gmail.com</a></p>
        </div>

        <!-- Phone / WhatsApp Card -->
        <div class="contact-card-modern" style="padding:36px 28px;">
          <div class="contact-icon" style="margin-bottom:24px;">📞</div>
          <h3 style="font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.2em;text-transform:uppercase;margin-bottom:12px;">Call or WhatsApp</h3>
          <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#3D3D3A;margin-bottom:8px;">Customer support:</p>
          <a href="tel:+201234567890" style="font-family:'DM Sans',sans-serif;font-size:16px;font-weight:400;color:#0A0A0A;text-decoration:none;border-bottom:1px solid #D5D3CF;">+20 155 805 6772</a>
          <p style="margin-top:16px;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">Available 24 Hours • Every Day</p>
        </div>

        <!-- Boutique / Showroom Card -->
        <div class="contact-card-modern" style="padding:36px 28px;">
          <div class="contact-icon" style="margin-bottom:24px;">🌐</div>
          <h3 style="font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.2em;text-transform:uppercase;margin-bottom:12px;">ONLINE EXPERIENCE</h3>
          <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#3D3D3A;margin-bottom:4px;">RYO is an online destination for refined essentials designed with simplicity, comfort, and timeless presence in mind.</p>
          <p style="margin-top:12px;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;"> Alexandria, Egypt</p>
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
              <div class="faq-answer">Orders within Egypt are delivered within 3–5 business days for most governorates. Border and remote areas may take up to 5–7 business days.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>What is your return policy?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">We accept returns within 5 days of delivery. Items must be unworn, unwashed, with original tags attached. For more details, see our Returns section.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>Can I modify or cancel my order?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Orders can be modified or canceled within 2 hours of placement. Once processing begins, changes may no longer be possible.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>Do you offer international shipping?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">We currently offer domestic shipping across Egypt only. International shipping is not available at this time.</div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
              <div class="faq-question">
                <span>How can I track my order?</span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-answer">Once your order is dispatched, tracking details will be shared via email. You can also contact our support team anytime for delivery updates.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>




  {{-- Newsletter --}}
  @include('partials.newslater')

  {{-- Footer --}}
  @include('partials.footer')


  <script src="{{ asset('js/website-contact-us.js') }}"></script>

  @if (Route::has('login'))
    <div class="h-14.5 hidden lg:block"></div>
  @endif

</body>

</html>
