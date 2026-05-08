<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RYO - Shop</title>
  <meta name="description" content="Shop RYO's full collection of premium oversized streetwear. New arrivals, best sellers, tees, bottoms, outerwear.">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="{{ asset('css/website.css') }}" rel="stylesheet">

  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=DM+Sans:wght@300;400;500&family=Space+Grotesk:wght@400;500&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'ryo-black': '#0A0A0A', 'ryo-white': '#F8F6F2',
            'ryo-gray-100': '#EDEDEB', 'ryo-gray-200': '#D5D3CF',
            'ryo-gray-400': '#9C9A96', 'ryo-gray-700': '#3D3D3A',
          },
          fontFamily: {
            display: ['Cormorant Garamond', 'serif'],
            body: ['DM Sans', 'sans-serif'],
            label: ['Space Grotesk', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    html { scroll-behavior:smooth; }
    body { font-family:'DM Sans',sans-serif; background:#F8F6F2; color:#0A0A0A; overflow-x:hidden; }
    ::-webkit-scrollbar { width:4px; }
    ::-webkit-scrollbar-track { background:#F8F6F2; }
    ::-webkit-scrollbar-thumb { background:#9C9A96; }

    /* NAV */
    .nav-link { font-family:'Space Grotesk',sans-serif; font-size:11px; letter-spacing:0.12em; text-transform:uppercase; color:#0A0A0A; text-decoration:none; position:relative; padding-bottom:2px; }
    .nav-link::after { content:''; position:absolute; bottom:0; left:0; width:0; height:1px; background:#0A0A0A; transition:width 0.3s cubic-bezier(0.25,0.46,0.45,0.94); }
    .nav-link:hover::after { width:100%; }
    .nav-link.active::after { width:100%; }

    /* FILTER */
    .filter-btn {
      font-family:'Space Grotesk',sans-serif; font-size:10px; letter-spacing:0.14em; text-transform:uppercase;
      padding:9px 18px; border:1px solid #D5D3CF; background:transparent; cursor:pointer;
      color:#9C9A96; transition:all 0.25s ease;
    }
    .filter-btn:hover { border-color:#0A0A0A; color:#0A0A0A; }
    .filter-btn.active { background:#0A0A0A; color:#F8F6F2; border-color:#0A0A0A; }

    /* SORT SELECT */
    .sort-select {
      font-family:'Space Grotesk',sans-serif; font-size:10px; letter-spacing:0.12em; text-transform:uppercase;
      border:1px solid #D5D3CF; background:#F8F6F2; color:#0A0A0A;
      padding:9px 32px 9px 14px; outline:none; cursor:pointer; appearance:none;
      background-image:url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%230A0A0A' stroke-width='1.2' stroke-linecap='round'/%3E%3C/svg%3E");
      background-repeat:no-repeat; background-position:right 12px center;
    }

    /* PRODUCT CARD */
    .product-card { position:relative; cursor:pointer; }
    .product-img-wrap { overflow:hidden; position:relative; background:#EDEDEB; }
    .product-img-wrap img { width:100%; height:100%; object-fit:cover; display:block; transition:transform 0.65s cubic-bezier(0.25,0.46,0.45,0.94); }
    .product-card:hover .product-img-wrap img { transform:scale(1.04); }
    .hover-img { position:absolute; inset:0; opacity:0; transition:opacity 0.5s cubic-bezier(0.25,0.46,0.45,0.94); }
    .product-card:hover .hover-img { opacity:1; }
    .product-badge { position:absolute; top:10px; left:10px; font-family:'Space Grotesk',sans-serif; font-size:9px; letter-spacing:0.15em; text-transform:uppercase; padding:4px 9px; z-index:2; }
    .badge-new { background:#0A0A0A; color:#F8F6F2; }
    .badge-sale { background:#3D3D3A; color:#F8F6F2; }
    .badge-sold { background:#EDEDEB; color:#9C9A96; }
    .product-quick-add { position:absolute; bottom:0; left:0; right:0; background:rgba(10,10,10,0.88); color:#F8F6F2; font-family:'Space Grotesk',sans-serif; font-size:10px; letter-spacing:0.14em; text-transform:uppercase; text-align:center; padding:13px; opacity:0; transform:translateY(8px); transition:all 0.3s cubic-bezier(0.25,0.46,0.45,0.94); cursor:pointer; border:none; width:100%; }
    .product-card:hover .product-quick-add { opacity:1; transform:translateY(0); }
    .wishlist-btn { position:absolute; top:10px; right:10px; width:30px; height:30px; background:rgba(248,246,242,0.85); border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; z-index:2; opacity:0; transition:opacity 0.3s ease; }
    .product-card:hover .wishlist-btn { opacity:1; }
    .product-meta { padding:12px 0 0; }
    .product-name { font-family:'DM Sans',sans-serif; font-size:13px; font-weight:400; color:#0A0A0A; margin-bottom:3px; }
    .product-color { font-family:'DM Sans',sans-serif; font-size:12px; color:#9C9A96; margin-bottom:3px; }
    .product-price { font-family:'DM Sans',sans-serif; font-size:13px; font-weight:300; color:#9C9A96; }
    .sale-price { color:#0A0A0A; margin-right:8px; }
    .original-price { text-decoration:line-through; }

    /* SIDEBAR FILTER */
    .sidebar-section { border-bottom:1px solid #EDEDEB; padding:20px 0; }
    .sidebar-title { font-family:'Space Grotesk',sans-serif; font-size:10px; letter-spacing:0.18em; text-transform:uppercase; color:#0A0A0A; margin-bottom:16px; display:flex; align-items:center; justify-content:space-between; cursor:pointer; }
    .filter-option { display:flex; align-items:center; gap:10px; margin-bottom:10px; cursor:pointer; }
    .filter-option input[type="checkbox"] { width:14px; height:14px; accent-color:#0A0A0A; cursor:pointer; }
    .filter-option label { font-family:'DM Sans',sans-serif; font-size:13px; font-weight:300; color:#3D3D3A; cursor:pointer; transition:color 0.2s ease; flex:1; }
    .filter-option:hover label { color:#0A0A0A; }
    .filter-option .count { font-family:'DM Sans',sans-serif; font-size:11px; color:#9C9A96; }
    .color-swatch { width:20px; height:20px; border-radius:50%; border:1px solid #D5D3CF; cursor:pointer; transition:all 0.2s ease; flex-shrink:0; }
    .color-swatch.selected { outline:2px solid #0A0A0A; outline-offset:2px; }
    .color-swatch:hover { outline:2px solid #9C9A96; outline-offset:2px; }
    .price-range { width:100%; accent-color:#0A0A0A; cursor:pointer; }

    /* MOBILE FILTER PANEL */
    .filter-panel { position:fixed; inset:0; background:#F8F6F2; z-index:100; transform:translateX(-100%); transition:transform 0.45s cubic-bezier(0.25,0.46,0.45,0.94); overflow-y:auto; }
    .filter-panel.open { transform:translateX(0); }
    .filter-overlay { position:fixed; inset:0; background:rgba(10,10,10,0.4); z-index:99; opacity:0; pointer-events:none; transition:opacity 0.3s ease; }
    .filter-overlay.open { opacity:1; pointer-events:all; }

    /* CART DRAWER */
    .cart-drawer { position:fixed; top:0; right:0; width:min(420px,100vw); height:100%; background:#F8F6F2; z-index:200; transform:translateX(100%); transition:transform 0.45s cubic-bezier(0.25,0.46,0.45,0.94); display:flex; flex-direction:column; border-left:1px solid #D5D3CF; }
    .cart-drawer.open { transform:translateX(0); }
    .cart-overlay { position:fixed; inset:0; background:rgba(10,10,10,0.4); z-index:199; opacity:0; pointer-events:none; transition:opacity 0.4s ease; }
    .cart-overlay.open { opacity:1; pointer-events:all; }

    /* MOBILE MENU */
    .mobile-menu { position:fixed; inset:0; background:#0A0A0A; z-index:100; transform:translateX(-100%); transition:transform 0.5s cubic-bezier(0.25,0.46,0.45,0.94); display:flex; flex-direction:column; padding:32px; }
    .mobile-menu.open { transform:translateX(0); }
    .mobile-nav-link { font-family:'Cormorant Garamond',serif; font-size:40px; font-weight:300; color:#F8F6F2; text-decoration:none; line-height:1.2; border-bottom:1px solid #3D3D3A; padding:20px 0; display:block; transition:color 0.3s ease; }
    .mobile-nav-link:hover { color:#9C9A96; }

    /* REVEAL */
    .reveal { opacity:0; transform:translateY(24px); transition:opacity 0.7s cubic-bezier(0.25,0.46,0.45,0.94), transform 0.7s cubic-bezier(0.25,0.46,0.45,0.94); }
    .reveal.visible { opacity:1; transform:translateY(0); }

    /* PRODUCT HIDDEN for filter */
    .product-item.hidden { display:none; }

    /* GRID TOGGLE */
    .grid-btn { background:none; border:none; cursor:pointer; padding:6px; color:#9C9A96; transition:color 0.2s ease; }
    .grid-btn.active, .grid-btn:hover { color:#0A0A0A; }

    /* FOOTER */
    .footer-link { font-family:'DM Sans',sans-serif; font-size:13px; font-weight:300; color:#9C9A96; text-decoration:none; transition:color 0.3s ease; }
    .footer-link:hover { color:#F8F6F2; }

    /* BREADCRUMB */
    .breadcrumb-item { font-family:'Space Grotesk',sans-serif; font-size:10px; letter-spacing:0.12em; text-transform:uppercase; color:#9C9A96; text-decoration:none; transition:color 0.2s ease; }
    .breadcrumb-item:hover { color:#0A0A0A; }
    .breadcrumb-item.current { color:#0A0A0A; }
  </style>
</head>
<body>


 {{-- Navbar --}}
 @include('partials.nav-bar2')

 {{-- Mobile Menu --}}
 @include('partials.mobile-menu')

 {{-- Cart Drawer --}}
 @include('partials.cart-drawer')




<!-- ═══════════════════════════════════════════
     SHOP HEADER
═══════════════════════════════════════════ -->
<div style="padding:48px 40px 32px;max-width:1440px;margin:0 auto;border-bottom:1px solid #EDEDEB;">
  <!-- Breadcrumb -->
  <div style="display:flex;align-items:center;gap:8px;margin-bottom:24px;">
    <a href="index.html" class="breadcrumb-item">Home</a>
    <span style="color:#D5D3CF;font-size:12px;">/</span>
    <span class="breadcrumb-item current">Shop</span>
  </div>

  <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:16px;">
    <div>
      <h1 style="font-family:'Cormorant Garamond',serif;font-size:clamp(36px,5vw,64px);font-weight:300;letter-spacing:-0.01em;line-height:1;margin-bottom:8px;">All Products</h1>
      <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9C9A96;font-weight:300;">48 products</p>
    </div>

    <!-- Top Filter Bar (desktop) -->
    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;" class="hidden md:flex">
      <button class="filter-btn active" onclick="setCategory(this,'all')" data-cat="all">All</button>
      <button class="filter-btn" onclick="setCategory(this,'new')" data-cat="new">New Arrivals</button>
      <button class="filter-btn" onclick="setCategory(this,'tops')" data-cat="tops">Tops</button>
      <button class="filter-btn" onclick="setCategory(this,'bottoms')" data-cat="bottoms">Bottoms</button>
      <button class="filter-btn" onclick="setCategory(this,'outerwear')" data-cat="outerwear">Outerwear</button>
      <button class="filter-btn" onclick="setCategory(this,'sale')" data-cat="sale">Sale</button>
    </div>
  </div>
</div>


<!-- ═══════════════════════════════════════════
     MAIN SHOP LAYOUT
═══════════════════════════════════════════ -->
<div style="max-width:1440px;margin:0 auto;display:flex;gap:0;">



  <!-- MAIN PRODUCT AREA -->
  <main style="flex:1;padding:0 40px 80px;min-width:0;">

    <!-- Controls Bar -->
    <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 0;border-bottom:1px solid #EDEDEB;margin-bottom:32px;">
      <!-- Mobile: Filter button -->

      <span style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;" class="hidden md:block">Showing 1–48 of 48 products</span>

      <div style="display:flex;align-items:center;gap:16px;">
        <!-- Grid toggle -->
        <div style="display:flex;align-items:center;gap:4px;" class="hidden md:flex">
          <button class="grid-btn active" onclick="setGrid(4,this)" aria-label="4-column grid">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"><rect x="0" y="0" width="3" height="3"/><rect x="4.5" y="0" width="3" height="3"/><rect x="9" y="0" width="3" height="3"/><rect x="13.5" y="0" width="2.5" height="3"/><rect x="0" y="4.5" width="3" height="3"/><rect x="4.5" y="4.5" width="3" height="3"/><rect x="9" y="4.5" width="3" height="3"/><rect x="13.5" y="4.5" width="2.5" height="3"/><rect x="0" y="9" width="3" height="3"/><rect x="4.5" y="9" width="3" height="3"/><rect x="9" y="9" width="3" height="3"/><rect x="13.5" y="9" width="2.5" height="3"/></svg>
          </button>
          <button class="grid-btn" onclick="setGrid(3,this)" aria-label="3-column grid">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"><rect x="0" y="0" width="4" height="4"/><rect x="6" y="0" width="4" height="4"/><rect x="12" y="0" width="4" height="4"/><rect x="0" y="6" width="4" height="4"/><rect x="6" y="6" width="4" height="4"/><rect x="12" y="6" width="4" height="4"/><rect x="0" y="12" width="4" height="4"/><rect x="6" y="12" width="4" height="4"/><rect x="12" y="12" width="4" height="4"/></svg>
          </button>
          <button class="grid-btn" onclick="setGrid(2,this)" aria-label="2-column grid">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"><rect x="0" y="0" width="6.5" height="6.5"/><rect x="9.5" y="0" width="6.5" height="6.5"/><rect x="0" y="9.5" width="6.5" height="6.5"/><rect x="9.5" y="9.5" width="6.5" height="6.5"/></svg>
          </button>
        </div>
        <!-- Sort -->
        <select class="sort-select" id="sortSelect" onchange="sortProducts(this.value)">
          <option value="featured">Featured</option>
          <option value="newest">Newest First</option>
          <option value="price-asc">Price: Low to High</option>
          <option value="price-desc">Price: High to Low</option>
          <option value="bestselling">Best Selling</option>
        </select>
      </div>
    </div>

    <!-- PRODUCT GRID -->
    <div id="productGrid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:32px 20px;">

      <!-- Product 1 -->
      <div class="product-card product-item reveal" data-cat="tops new" data-price="2200">
        <div class="product-img-wrap" style="aspect-ratio:3/4;">
          <img src="https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=500&q=80" alt="Oversized Essential Tee">
          <img class="hover-img" src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&q=80" alt="Oversized Essential Tee alt">
          <span class="product-badge badge-new">New</span>
          <button class="wishlist-btn" aria-label="Wishlist"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></button>
          <button class="product-quick-add" onclick="addToCart(event,'Oversized Essential Tee')">Quick Add</button>
        </div>
        <div class="product-meta">
          <p class="product-name"><a href="product.html" style="text-decoration:none;color:inherit;">Oversized Essential Tee</a></p>
          <p class="product-color">Stone / Black / Cream</p>
          <p class="product-price">EGP 2,200</p>
        </div>
      </div>

      <!-- Product 2 -->
      <div class="product-card product-item reveal" style="transition-delay:0.05s;" data-cat="bottoms new" data-price="3800">
        <div class="product-img-wrap" style="aspect-ratio:3/4;">
          <img src="https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=500&q=80" alt="Wide Leg Cargo">
          <img class="hover-img" src="https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=500&q=80" alt="Cargo alt">
          <span class="product-badge badge-new">New</span>
          <button class="wishlist-btn" aria-label="Wishlist"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></button>
          <button class="product-quick-add" onclick="addToCart(event,'Wide Leg Cargo Pant')">Quick Add</button>
        </div>
        <div class="product-meta">
          <p class="product-name"><a href="product.html" style="text-decoration:none;color:inherit;">Wide Leg Cargo Pant</a></p>
          <p class="product-color">Washed Black / Stone</p>
          <p class="product-price">EGP 3,800</p>
        </div>
      </div>

      <!-- Product 3 -->
      <div class="product-card product-item reveal" style="transition-delay:0.1s;" data-cat="outerwear new" data-price="5500">
        <div class="product-img-wrap" style="aspect-ratio:3/4;">
          <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=500&q=80" alt="Utility Coach Jacket">
          <img class="hover-img" src="https://images.unsplash.com/photo-1544441893-675973e31985?w=500&q=80" alt="Jacket alt">
          <span class="product-badge badge-new">New</span>
          <button class="wishlist-btn" aria-label="Wishlist"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></button>
          <button class="product-quick-add" onclick="addToCart(event,'Utility Coach Jacket')">Quick Add</button>
        </div>
        <div class="product-meta">
          <p class="product-name"><a href="product.html" style="text-decoration:none;color:inherit;">Utility Coach Jacket</a></p>
          <p class="product-color">Olive / Black</p>
          <p class="product-price">EGP 5,500</p>
        </div>
      </div>

      <!-- Product 4 -->
      <div class="product-card product-item reveal" style="transition-delay:0.15s;" data-cat="tops new" data-price="4200">
        <div class="product-img-wrap" style="aspect-ratio:3/4;">
          <img src="https://images.unsplash.com/photo-1509631179647-0177331693ae?w=500&q=80" alt="Relaxed Heavy Hoodie">
          <button class="wishlist-btn" aria-label="Wishlist"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></button>
          <button class="product-quick-add" onclick="addToCart(event,'Relaxed Heavy Hoodie')">Quick Add</button>
        </div>
        <div class="product-meta">
          <p class="product-name"><a href="product.html" style="text-decoration:none;color:inherit;">Relaxed Heavy Hoodie</a></p>
          <p class="product-color">Charcoal / Cream</p>
          <p class="product-price">EGP 4,200</p>
        </div>
      </div>

      <!-- Product 5 -->
      <div class="product-card product-item reveal" style="transition-delay:0.05s;" data-cat="tops" data-price="3400">
        <div class="product-img-wrap" style="aspect-ratio:3/4;">
          <img src="https://images.unsplash.com/photo-1594938298603-f4d8c9a3a9a4?w=500&q=80" alt="Washed Crewneck">
          <span class="product-badge badge-sale">Best Seller</span>
          <button class="wishlist-btn" aria-label="Wishlist"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></button>
          <button class="product-quick-add" onclick="addToCart(event,'Washed Crewneck Sweat')">Quick Add</button>
        </div>
        <div class="product-meta">
          <p class="product-name"><a href="product.html" style="text-decoration:none;color:inherit;">Washed Crewneck Sweat</a></p>
          <p class="product-color">Faded Grey / Black</p>
          <p class="product-price">EGP 3,400</p>
        </div>
      </div>

      <!-- Product 6 -->
      <div class="product-card product-item reveal" style="transition-delay:0.1s;" data-cat="bottoms" data-price="2900">
        <div class="product-img-wrap" style="aspect-ratio:3/4;">
          <img src="https://images.unsplash.com/photo-1542272604-787c3835535d?w=500&q=80" alt="Slim Tapered Chino">
          <button class="wishlist-btn" aria-label="Wishlist"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></button>
          <button class="product-quick-add" onclick="addToCart(event,'Slim Tapered Chino')">Quick Add</button>
        </div>
        <div class="product-meta">
          <p class="product-name"><a href="product.html" style="text-decoration:none;color:inherit;">Slim Tapered Chino</a></p>
          <p class="product-color">Stone / Navy / Black</p>
          <p class="product-price">EGP 2,900</p>
        </div>
      </div>

      <!-- Product 7 -->
      <div class="product-card product-item reveal" style="transition-delay:0.15s;" data-cat="tops sale" data-price="1800">
        <div class="product-img-wrap" style="aspect-ratio:3/4;">
          <img src="https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=500&q=80" alt="Linen Shirt">
          <span class="product-badge badge-sale">Sale</span>
          <button class="wishlist-btn" aria-label="Wishlist"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></button>
          <button class="product-quick-add" onclick="addToCart(event,'Relaxed Linen Shirt')">Quick Add</button>
        </div>
        <div class="product-meta">
          <p class="product-name"><a href="product.html" style="text-decoration:none;color:inherit;">Relaxed Linen Shirt</a></p>
          <p class="product-color">Cream / White</p>
          <p class="product-price"><span class="sale-price">EGP 1,800</span><span class="original-price">EGP 2,600</span></p>
        </div>
      </div>

      <!-- Product 8 -->
      <div class="product-card product-item reveal" style="transition-delay:0.2s;" data-cat="outerwear" data-price="6200">
        <div class="product-img-wrap" style="aspect-ratio:3/4;">
          <img src="https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=500&q=80" alt="Structured Bomber">
          <span class="product-badge badge-sale">Best Seller</span>
          <button class="wishlist-btn" aria-label="Wishlist"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></button>
          <button class="product-quick-add" onclick="addToCart(event,'Structured Bomber')">Quick Add</button>
        </div>
        <div class="product-meta">
          <p class="product-name"><a href="product.html" style="text-decoration:none;color:inherit;">Structured Bomber</a></p>
          <p class="product-color">Black / Vintage Brown</p>
          <p class="product-price">EGP 6,200</p>
        </div>
      </div>

      <!-- Product 9 -->
      <div class="product-card product-item reveal" data-cat="bottoms sale" data-price="2100">
        <div class="product-img-wrap" style="aspect-ratio:3/4;">
          <img src="https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=500&q=80" alt="Track Pant">
          <span class="product-badge badge-sale">Sale</span>
          <button class="wishlist-btn" aria-label="Wishlist"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></button>
          <button class="product-quick-add" onclick="addToCart(event,'Premium Track Pant')">Quick Add</button>
        </div>
        <div class="product-meta">
          <p class="product-name"><a href="product.html" style="text-decoration:none;color:inherit;">Premium Track Pant</a></p>
          <p class="product-color">Black / Stone</p>
          <p class="product-price"><span class="sale-price">EGP 2,100</span><span class="original-price">EGP 3,000</span></p>
        </div>
      </div>

      <!-- Product 10 -->
      <div class="product-card product-item reveal" style="transition-delay:0.05s;" data-cat="outerwear" data-price="7800">
        <div class="product-img-wrap" style="aspect-ratio:3/4;">
          <img src="https://images.unsplash.com/photo-1551537482-f2075a1d41f2?w=500&q=80" alt="Overcoat">
          <button class="wishlist-btn" aria-label="Wishlist"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></button>
          <button class="product-quick-add" onclick="addToCart(event,'Minimal Wool Overcoat')">Quick Add</button>
        </div>
        <div class="product-meta">
          <p class="product-name"><a href="product.html" style="text-decoration:none;color:inherit;">Minimal Wool Overcoat</a></p>
          <p class="product-color">Camel / Charcoal</p>
          <p class="product-price">EGP 7,800</p>
        </div>
      </div>

      <!-- Product 11 -->
      <div class="product-card product-item reveal" style="transition-delay:0.1s;" data-cat="tops" data-price="1900">
        <div class="product-img-wrap" style="aspect-ratio:3/4;">
          <img src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?w=500&q=80" alt="Muscle Tee">
          <button class="wishlist-btn" aria-label="Wishlist"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></button>
          <button class="product-quick-add" onclick="addToCart(event,'Raw Edge Muscle Tee')">Quick Add</button>
        </div>
        <div class="product-meta">
          <p class="product-name"><a href="product.html" style="text-decoration:none;color:inherit;">Raw Edge Muscle Tee</a></p>
          <p class="product-color">White / Black</p>
          <p class="product-price">EGP 1,900</p>
        </div>
      </div>

      <!-- Product 12 -->
      <div class="product-card product-item reveal" style="transition-delay:0.15s;" data-cat="tops" data-price="2500">
        <div class="product-img-wrap" style="aspect-ratio:3/4;">
          <img src="https://images.unsplash.com/photo-1578681994506-b8f463449011?w=500&q=80" alt="Quarter Zip">
          <button class="wishlist-btn" aria-label="Wishlist"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></button>
          <button class="product-quick-add" onclick="addToCart(event,'Quarter Zip Fleece')">Quick Add</button>
        </div>
        <div class="product-meta">
          <p class="product-name"><a href="product.html" style="text-decoration:none;color:inherit;">Quarter Zip Fleece</a></p>
          <p class="product-color">Cream / Washed Grey</p>
          <p class="product-price">EGP 2,500</p>
        </div>
      </div>

    </div><!-- end grid -->

    <!-- LOAD MORE -->
    <div style="text-align:center;padding-top:64px;">
      <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-bottom:16px;">Showing 12 of 48 products</p>
      <div style="width:200px;height:1px;background:#EDEDEB;margin:0 auto 20px;position:relative;">
        <div style="width:33%;height:1px;background:#0A0A0A;position:absolute;left:0;top:0;"></div>
      </div>
      <button onclick="loadMore()" style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.18em;text-transform:uppercase;background:none;border:1px solid #D5D3CF;padding:12px 32px;cursor:pointer;color:#0A0A0A;transition:all 0.3s ease;" onmouseover="this.style.borderColor='#0A0A0A'" onmouseout="this.style.borderColor='#D5D3CF'">
        Load More Products
      </button>
    </div>

  </main>
</div><!-- end layout -->


<!-- ═══════════════════════════════════════════
     ADDED TO CART TOAST
═══════════════════════════════════════════ -->
<div id="toast" style="position:fixed;bottom:32px;left:50%;transform:translateX(-50%) translateY(80px);background:#0A0A0A;color:#F8F6F2;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:0.12em;text-transform:uppercase;padding:14px 28px;z-index:300;opacity:0;transition:all 0.4s cubic-bezier(0.25,0.46,0.45,0.94);white-space:nowrap;display:flex;align-items:center;gap:12px;">
  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="20 6 9 17 4 12"/></svg>
  <span id="toastMsg">Added to bag</span>
  <button onclick="toggleCart()" style="background:none;border:none;cursor:pointer;color:#9C9A96;font-size:10px;letter-spacing:0.1em;text-transform:uppercase;margin-left:8px;">View Bag</button>
</div>



  {{-- Newslater --}}
  @include('partials.newslater')

 {{-- Footer --}}
 @include('partials.footer')




<script>
  // ── CART ──
  function toggleCart() {
    document.getElementById('cartDrawer').classList.toggle('open');
    document.getElementById('cartOverlay').classList.toggle('open');
    document.body.style.overflow = document.getElementById('cartDrawer').classList.contains('open') ? 'hidden' : '';
  }

  // ── MENU ──
  function toggleMenu() {
    document.getElementById('mobileMenu').classList.toggle('open');
  }

  // ── MOBILE FILTER ──
  function toggleMobileFilter() {
    document.getElementById('filterPanel').classList.toggle('open');
    document.getElementById('filterOverlay').classList.toggle('open');
    document.body.style.overflow = document.getElementById('filterPanel').classList.contains('open') ? 'hidden' : '';
  }

  // ── CATEGORY FILTER ──
  function setCategory(btn, cat) {
    document.querySelectorAll('[data-cat]').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const items = document.querySelectorAll('.product-item');
    items.forEach(item => {
      const cats = item.dataset.cat || '';
      if (cat === 'all' || cats.includes(cat)) {
        item.style.display = '';
      } else {
        item.style.display = 'none';
      }
    });
  }

  // ── SIZE TOGGLE ──
  function toggleSize(btn) {
    btn.classList.toggle('active');
  }

  // ── PRICE RANGE ──
  function updatePrice(val) {
    const formatted = Number(val).toLocaleString('en-EG');
    const el = document.getElementById('price-val');
    const mel = document.getElementById('m-price-val');
    if (el) el.textContent = 'EGP ' + formatted;
    if (mel) mel.textContent = 'EGP ' + formatted;
  }

  // ── GRID COLUMNS ──
  function setGrid(cols, btn) {
    document.querySelectorAll('.grid-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('productGrid').style.gridTemplateColumns = `repeat(${cols},1fr)`;
  }

  // ── SORT ──
  function sortProducts(val) {
    const grid = document.getElementById('productGrid');
    const items = [...grid.querySelectorAll('.product-item')];
    if (val === 'price-asc') {
      items.sort((a, b) => Number(a.dataset.price) - Number(b.dataset.price));
    } else if (val === 'price-desc') {
      items.sort((a, b) => Number(b.dataset.price) - Number(a.dataset.price));
    }
    items.forEach(i => grid.appendChild(i));
  }

  // ── ADD TO CART TOAST ──
  function addToCart(e, name) {
    e.stopPropagation();
    const toast = document.getElementById('toast');
    document.getElementById('toastMsg').textContent = name + ' added to bag';
    toast.style.opacity = '1';
    toast.style.transform = 'translateX(-50%) translateY(0)';
    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateX(-50%) translateY(80px)';
    }, 3000);
  }

  // ── LOAD MORE ──
  function loadMore() {
    alert('Load more products (connect to Laravel pagination)');
  }

  // ── SCROLL REVEAL ──
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
  document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

  // ── RESPONSIVE GRID ──
  function checkGrid() {
    if (window.innerWidth < 640) {
      document.getElementById('productGrid').style.gridTemplateColumns = 'repeat(2,1fr)';
    } else if (window.innerWidth < 900) {
      document.getElementById('productGrid').style.gridTemplateColumns = 'repeat(3,1fr)';
    }
  }
  checkGrid();
  window.addEventListener('resize', checkGrid);
</script>
</body>
</html>
