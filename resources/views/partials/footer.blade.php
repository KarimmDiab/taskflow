  <!-- ═══════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════ -->
<footer style="background:#0A0A0A;border-top:1px solid #1a1a1a;padding:64px 40px 32px;">
  <div style="max-width:1440px;margin:0 auto;">
    <!-- Footer Top Grid -->
    <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:48px;margin-bottom:64px;">

      <!-- Brand Column -->
      <div>
        <p
          style="font-family:'Cormorant Garamond',serif;font-size:32px;font-weight:300;color:#F8F6F2;letter-spacing:0.15em;margin-bottom:16px;">
          <img src="{{ asset('images/logos/white_logo.png') }}" class="nav-logo" alt="RYO"
            style="height:180px;width:auto;display:block;">
        </p>
        <p
          style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;line-height:1.8;margin-bottom:24px;max-width:260px;">
          Minimal luxury. Premium casual. Modern streetwear for the generation that knows.</p>
        <!-- Social -->
        <div style="display:flex;gap:16px;">
          <a href="https://www.facebook.com/RYObrand.official" target="_blank" style="color:#3D3D3A;transition:color 0.3s ease;"
            onmouseover="this.style.color='#F8F6F2'" onmouseout="this.style.color='#3D3D3A'">

            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">

              <path
                d="M22 12a10 10 0 10-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.23.19 2.23.19v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.77l-.44 2.89h-2.33v6.99A10 10 0 0022 12z" />

            </svg>

          </a>
          <a href="https://www.instagram.com/ryobrandofficial" target="_blank"
            style="color:#3D3D3A;transition:color 0.3s ease;" onmouseover="this.style.color='#F8F6F2'"
            onmouseout="this.style.color='#3D3D3A'">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <rect x="2" y="2" width="20" height="20" rx="5" />
              <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" />
              <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
            </svg>
          </a>
          <a href="https://www.tiktok.com/@ryobrand.official" target="_blank"
            style="color:#3D3D3A;transition:color 0.3s ease;" onmouseover="this.style.color='#F8F6F2'"
            onmouseout="this.style.color='#3D3D3A'">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V9.19a8.17 8.17 0 004.79 1.54V7.28a4.85 4.85 0 01-1.02-.59z" />
            </svg>
          </a>
        </div>
      </div>

      <!-- Shop Links -->
      <div>
        <p
          style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.18em;text-transform:uppercase;color:#F8F6F2;margin-bottom:24px;">
          Shop</p>
        <nav style="display:flex;flex-direction:column;gap:12px;">
          <a href="shop.html" class="footer-link">New Arrivals</a>
          <a href="#" class="footer-link">Best Sellers</a>
          <a href="#" class="footer-link">Tops & Tees</a>
          <a href="#" class="footer-link">Bottoms</a>
          <a href="#" class="footer-link">Outerwear</a>
          <a href="#" class="footer-link">Accessories</a>
          <a href="#" class="footer-link">Sale</a>
        </nav>
      </div>

      <!-- Info Links -->
      <div>
        <p
          style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.18em;text-transform:uppercase;color:#F8F6F2;margin-bottom:24px;">
          Info</p>
        <nav style="display:flex;flex-direction:column;gap:12px;">
          <a href="#" class="footer-link">About RYO</a>
          <a href="#" class="footer-link">Sustainability</a>
          <a href="#" class="footer-link">Size Guide</a>
          <a href="#" class="footer-link">FAQs</a>
          <a href="#" class="footer-link">Contact Us</a>
        </nav>
      </div>

      <!-- Account Links -->
      <div>
        <p
          style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.18em;text-transform:uppercase;color:#F8F6F2;margin-bottom:24px;">
          Account</p>
        <nav style="display:flex;flex-direction:column;gap:12px;">
          <a href="#" class="footer-link">My Orders</a>
          <a href="#" class="footer-link">Track Order</a>
          <a href="#" class="footer-link">Returns</a>
          <a href="login.html" class="footer-link">Login</a>
          <a href="register.html" class="footer-link">Register</a>
        </nav>
      </div>
    </div>

    <!-- Footer Bottom -->
    <div
      style="display:flex;align-items:center;justify-content:space-between;padding-top:24px;border-top:1px solid #1a1a1a;">
      <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#3D3D3A;">© 2026 RYO. All rights reserved.</p>
      <div style="display:flex;gap:24px;">
        <a href={{ route('privacy-policy') }} target="_blank"
          style="font-family:'DM Sans',sans-serif;font-size:12px;color:#3D3D3A;text-decoration:none;transition:color 0.3s ease;"
          onmouseover="this.style.color='#9C9A96'" onmouseout="this.style.color='#3D3D3A'">Privacy Policy</a>
        <a href={{ route('terms-of-services') }} target="_blank"
          style="font-family:'DM Sans',sans-serif;font-size:12px;color:#3D3D3A;text-decoration:none;transition:color 0.3s ease;"
          onmouseover="this.style.color='#9C9A96'" onmouseout="this.style.color='#3D3D3A'">Terms of Service</a>
        <a href={{ route('Shipping-Policy') }} target="_blank"
          style="font-family:'DM Sans',sans-serif;font-size:12px;color:#3D3D3A;text-decoration:none;transition:color 0.3s ease;"
          onmouseover="this.style.color='#9C9A96'" onmouseout="this.style.color='#3D3D3A'">Shipping Policy</a>
      </div>
    </div>
  </div>
</footer>
