<!-- ═══════════════════════════════════════════
     NAVBAR
═══════════════════════════════════════════ -->
<header
  style="position:sticky;top:0;z-index:50;padding:0 40px;height:64px;display:flex;align-items:center;justify-content:space-between;background:rgba(248,246,242,.96);backdrop-filter:blur(12px);border-bottom:1px solid #EDEDEB;">
  <!-- LEFT NAV -->
  <nav style="display:flex;gap:32px;align-items:center;" class="hidden md:flex">
    <a href={{ route('home') }} class="nav-link">HOME</a>
    <a href={{ route('all-products') }} class="nav-link">SHOP</a>
    <a href="#" class="nav-link">COLLECTIONS</a>
  </nav>
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
  <!-- RIGHT NAV -->
  <div style="display:flex;align-items:center;gap:24px;">
    <nav style="display:flex;gap:32px;" class="hidden md:flex">
      <a href="#" class="nav-link">NEW IN</a>
      <a href="#" class="nav-link">About</a>
      <a href="#" class="nav-link">CONTACT US</a>
    </nav>
    <div style="display:flex;align-items:center;gap:16px;">

      <!-- Account -->
      @if (Route::has('login'))
        <nav class="flex items-center justify-end gap-4">

          <div class="relative group">

            <!-- User Icon -->
            <button class="nav-link flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">

                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </button>

            <!-- Dropdown -->
            <div class="absolute right-0 top-full pt-2 w-48 bg-white shadow-lg rounded-lg hidden group-hover:block z-50">
              @auth

                <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">
                  Dashboard
                </a>

                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">
                  Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                  @csrf

                  <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                    Logout
                  </button>
                </form>

              @else

                <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-gray-100">
                  Log in
                </a>

                @if (Route::has('register'))
                  <a href="{{ route('register') }}" class="block px-4 py-2 hover:bg-gray-100">
                    Register
                  </a>
                @endif

              @endauth

            </div>

          </div>

        </nav>
      @endif
      <!-- Cart -->
      <button onclick="toggleCart()" class="nav-icon"
        style="background:none;border:none;cursor:pointer;color:black;transition:color 0.4s ease;padding:4px;position:relative;"
        aria-label="Cart">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
          <line x1="3" y1="6" x2="21" y2="6" />
          <path d="M16 10a4 4 0 01-8 0" />
        </svg>
        <span
          style="position:absolute;top:-2px;right:-4px;width:16px;height:16px;border-radius:50%;background:black;color:white;font-size:9px;font-family:'Space Grotesk',sans-serif;display:flex;align-items:center;justify-content:center;font-weight:500;"
          id="cartCount">2</span>
      </button>
      <!-- Hamburger (mobile) -->
      <button onclick="toggleMenu()" class="nav-icon md:hidden"
        style="background:none;border:none;cursor:pointer;color:black;transition:color 0.4s ease;padding:4px;"
        aria-label="Menu">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <line x1="3" y1="6" x2="21" y2="6" />
          <line x1="3" y1="12" x2="21" y2="12" />
          <line x1="3" y1="18" x2="21" y2="18" />
        </svg>
      </button>
    </div>
  </div>
</header>