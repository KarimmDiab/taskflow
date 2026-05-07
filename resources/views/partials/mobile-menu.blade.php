  <!-- ═══════════════════════════════════════════
     MOBILE MENU
═══════════════════════════════════════════ -->
<div class="mobile-menu" id="mobileMenu">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:40px;">

      <a href="{{ route('home') }}" style="display:block;width:120px;">

        <img src="{{ asset('images/logos/white_logo.png') }}" alt="RYsssO"
          style="width:100%;height:100%;object-fit:contain;">

      </a>

      <button onclick="toggleMenu()" style="background:none;border:none;cursor:pointer;color:#F8F6F2;">

        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">

          <path d="M18 6L6 18M6 6l12 12" />

        </svg>

      </button>

    </div>
    <nav style="flex:1;">
      <a href="shop.html" class="mobile-nav-link" style="display:block;transition-delay:0.1s;">Shop</a>
      <a href="#" class="mobile-nav-link" style="display:block;transition-delay:0.15s;">Collections</a>
      <a href="#" class="mobile-nav-link" style="display:block;transition-delay:0.2s;">New Drops</a>
      <a href="#" class="mobile-nav-link" style="display:block;transition-delay:0.25s;">Sale</a>
      <a href="#" class="mobile-nav-link" style="display:block;transition-delay:0.3s;">About</a>
    </nav>
    <div style="display:flex;gap:24px;padding-top:32px;border-top:1px solid #3D3D3A;">
      <a href="login.html"
        style="font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:0.15em;text-transform:uppercase;color:#9C9A96;text-decoration:none;">Account</a>
      <a href="#"
        style="font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:0.15em;text-transform:uppercase;color:#9C9A96;text-decoration:none;">Saved
        Items</a>
    </div>
  </div>
