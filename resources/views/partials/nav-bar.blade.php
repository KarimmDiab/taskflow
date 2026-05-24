  <!-- ═══════════════════════════════════════════
     NAVBAR
═══════════════════════════════════════════ -->
  <header id="navbar"
      style="position:fixed;top:0;left:0;right:0;z-index:50;padding:0 40px;height:64px;display:flex;align-items:center;justify-content:space-between;transition:all 0.4s cubic-bezier(0.25,0.46,0.45,0.94);border-bottom:1px solid transparent;"
      class="">

      <!-- LOGO -->
      <div style="display:flex;align-items:center;gap:16px;">
          <a href="{{ route('home') }}" class="nav-logo" style="display:flex;align-items:center;">
              <img src="{{ asset('images/logos/white_logo.png') }}" class="logo-img" alt="RYO"
                  style="height:48px;width:auto;display:block;transition:filter .25s ease;">
          </a>
      </div>

      <!-- CENTER NAV -->
      <nav class="center-nav hidden md:flex"
          style="position:absolute;left:50%;transform:translateX(-50%);display:flex;gap:32px;align-items:center;">
          <a href={{ route('home') }} class="nav-link">HOME</a>
          <a href={{ route('all-products') }} class="nav-link">SHOP</a>
          <a href="{{ route('collections') }}" class="nav-link">COLLECTIONS</a>
          <a href="{{ route('about-us') }}" class="nav-link">About</a>
          <a href="{{ route('contact-us') }}" class="nav-link">CONTACT US</a>
      </nav>

      <!-- RIGHT NAV -->
      <div style="display:flex;align-items:center;gap:24px;">

          <div style="display:flex;align-items:center;gap:16px;">

              <!-- Account -->
              @if (Route::has('login'))
                  <nav class="flex items-center justify-end gap-4">

                      <div class="relative group">

                          <!-- User Icon -->
                          <button class="nav-link flex items-center">
                              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                  stroke-width="1.5" stroke="currentColor" class="w-6 h-6">

                                  <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                              </svg>
                          </button>

                          <!-- Dropdown -->
                          <div
                              class="absolute right-0 top-full pt-2 w-48 bg-white shadow-lg rounded-lg hidden group-hover:block z-50" style="color: black;font-weight: bold;">
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
              <button onclick="window.cart.toggle()" class="nav-icon"
                  style="background:none;border:none;cursor:pointer;color:inherit;transition:color 0.4s ease;padding:4px;position:relative;"
                  aria-label="Cart">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                      stroke-width="1.5">
                      <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                      <line x1="3" y1="6" x2="21" y2="6" />
                      <path d="M16 10a4 4 0 01-8 0" />
                  </svg>
                  <span id="cartBadge"
                      style="position:absolute;top:-2px;right:-4px;width:16px;height:16px;border-radius:50%;background:black;color:white;font-size:9px;font-family:'Space Grotesk',sans-serif;display:none;align-items:center;justify-content:center;font-weight:bolder;">
                      0
                  </span>
              </button>
              <!-- Hamburger (mobile) -->
              <button onclick="toggleMenu()" class="nav-icon md:hidden"
                  style="background:none;border:none;cursor:pointer;color:inherit;transition:color 0.4s ease;padding:4px;"
                  aria-label="Menu">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                      stroke-width="1.5">
                      <line x1="3" y1="6" x2="21" y2="6" />
                      <line x1="3" y1="12" x2="21" y2="12" />
                      <line x1="3" y1="18" x2="21" y2="18" />
                  </svg>
              </button>
          </div>
      </div>

      <style>
          #navbar {
              background: transparent;
              color: #F8F6F2;
          }

          #navbar .nav-link {
              color: inherit;
              text-decoration: none;
          }

          #navbar .nav-icon {
              color: inherit;
          }

          #navbar.scrolled {
              background: #ffffff;
              color: #111827;
              border-bottom-color: rgba(0, 0, 0, 0.08);
              box-shadow: 0 12px 40px rgba(0, 0, 0, 0.06);
          }

          #navbar.scrolled .nav-link,
          #navbar.scrolled .nav-icon {
              color: black;
          }

          #navbar.scrolled .logo-img {
              filter: invert(1) grayscale(1) contrast(1.1);
          }

          @media (max-width: 767px) {
              .center-nav {
                  position: static;
                  transform: none;
              }
          }
      </style>

      <script>
          (function() {
              const nav = document.getElementById('navbar');
              const onScroll = () => {
                  if (window.scrollY > 20) nav.classList.add('scrolled');
                  else nav.classList.remove('scrolled');
              };
              document.addEventListener('scroll', onScroll, {
                  passive: true
              });
              onScroll();
          })();
      </script>

  </header>
