<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RYO - Product</title>
  <meta name="description" content="The RYO Oversized Essential Tee. Premium 350gsm cotton. Dropped shoulders, relaxed silhouette. SS25 Collection.">
  <script src="https://cdn.tailwindcss.com"></script>
      <link rel="icon" type="image/png" href="{{ asset('images/favicon/favicon.png') }}">

  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=DM+Sans:wght@300;400;500&family=Space+Grotesk:wght@400;500&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'ryo-black':'#0A0A0A','ryo-white':'#F8F6F2',
            'ryo-gray-100':'#EDEDEB','ryo-gray-200':'#D5D3CF',
            'ryo-gray-400':'#9C9A96','ryo-gray-700':'#3D3D3A',
          },
          fontFamily: {
            display:['Cormorant Garamond','serif'],
            body:['DM Sans','sans-serif'],
            label:['Space Grotesk','sans-serif'],
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

    /* GALLERY */
    .gallery-thumb{
      width:72px;height:90px;overflow:hidden;cursor:pointer;border:1px solid transparent;
      transition:border-color .25s ease;flex-shrink:0;
    }
    .gallery-thumb.active{border-color:#0A0A0A;}
    .gallery-thumb img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s ease;}
    .gallery-thumb:hover img{transform:scale(1.05);}
    .main-img-wrap{
      position:relative;overflow:hidden;background:#EDEDEB;
      height:calc(100vh - 64px);max-height:800px;min-height:560px;
    }
    .main-img-wrap img{
      width:100%;height:100%;object-fit:cover;display:block;
      transition:opacity .45s cubic-bezier(.25,.46,.45,.94);
    }
    .main-img-wrap img.fade-out{opacity:0;}

    /* ZOOM LENS */
    .zoom-badge{
      position:absolute;bottom:20px;right:20px;
      background:rgba(248,246,242,.85);backdrop-filter:blur(8px);
      font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.15em;text-transform:uppercase;
      padding:7px 14px;color:#3D3D3A;display:flex;align-items:center;gap:6px;
      opacity:0;transition:opacity .3s ease;pointer-events:none;
    }
    .main-img-wrap:hover .zoom-badge{opacity:1;}

    /* SIZE BTN */
    .size-btn{
      min-width:52px;height:44px;border:1px solid #D5D3CF;background:transparent;
      font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.08em;text-transform:uppercase;
      cursor:pointer;color:#3D3D3A;transition:all .25s ease;padding:0 12px;
    }
    .size-btn:hover{border-color:#0A0A0A;color:#0A0A0A;}
    .size-btn.active{background:#0A0A0A;color:#F8F6F2;border-color:#0A0A0A;}
    .size-btn.sold-out{color:#D5D3CF;cursor:not-allowed;border-color:#EDEDEB;}
    .size-btn.sold-out:hover{border-color:#EDEDEB;color:#D5D3CF;}

    /* COLOR SWATCH */
    .color-swatch-opt{
      width:28px;height:28px;border-radius:50%;border:1px solid #D5D3CF;
      cursor:pointer;transition:all .2s ease;position:relative;
    }
    .color-swatch-opt.active{outline:2px solid #0A0A0A;outline-offset:3px;}
    .color-swatch-opt:hover{outline:2px solid #9C9A96;outline-offset:3px;}
    .color-swatch-opt[title]:hover::after{
      content:attr(title);position:absolute;bottom:-28px;left:50%;transform:translateX(-50%);
      font-family:'DM Sans',sans-serif;font-size:10px;color:#0A0A0A;white-space:nowrap;
      background:#F8F6F2;padding:2px 6px;border:1px solid #EDEDEB;
    }

    /* QTY */
    .qty-btn{
      width:40px;height:40px;background:none;border:none;cursor:pointer;
      font-size:18px;color:#3D3D3A;transition:color .2s ease;display:flex;align-items:center;justify-content:center;
    }
    .qty-btn:hover{color:#0A0A0A;}

    /* ADD TO CART */
    .atc-btn{
      flex:1;background:#0A0A0A;color:#F8F6F2;border:none;cursor:pointer;
      font-family:'Space Grotesk',sans-serif;font-size:12px;letter-spacing:.14em;text-transform:uppercase;
      height:52px;display:flex;align-items:center;justify-content:center;gap:12px;
      transition:all .3s cubic-bezier(.25,.46,.45,.94);
    }
    .atc-btn:hover{background:#3D3D3A;}
    .atc-btn:active{transform:scale(.98);}
    .wishlist-btn-lg{
      width:52px;height:52px;border:1px solid #D5D3CF;background:transparent;cursor:pointer;
      display:flex;align-items:center;justify-content:center;transition:all .3s ease;flex-shrink:0;
    }
    .wishlist-btn-lg:hover{border-color:#0A0A0A;}
    .wishlist-btn-lg.active svg{fill:#0A0A0A;}

    /* ACCORDION */
    .accordion-item{border-bottom:1px solid #EDEDEB;}
    .accordion-header{
      display:flex;align-items:center;justify-content:space-between;
      padding:18px 0;cursor:pointer;
      font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;
    }
    .accordion-icon{transition:transform .35s cubic-bezier(.25,.46,.45,.94);}
    .accordion-item.open .accordion-icon{transform:rotate(45deg);}
    .accordion-body{
      max-height:0;overflow:hidden;transition:max-height .4s cubic-bezier(.25,.46,.45,.94);
    }
    .accordion-item.open .accordion-body{max-height:400px;}
    .accordion-content{padding:0 0 20px;}
    .accordion-content p,.accordion-content li{
      font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#3D3D3A;line-height:1.8;
    }
    .accordion-content ul{padding-left:0;list-style:none;}
    .accordion-content li::before{content:'— ';color:#9C9A96;}

    /* PRODUCT CARD small */
    .product-card-sm{position:relative;cursor:pointer;}
    .product-card-sm .img-w{overflow:hidden;background:#EDEDEB;}
    .product-card-sm .img-w img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .6s cubic-bezier(.25,.46,.45,.94);}
    .product-card-sm:hover .img-w img{transform:scale(1.04);}

    /* STICKY ATC (mobile) */
    .sticky-atc{
      position:fixed;bottom:0;left:0;right:0;z-index:60;
      background:rgba(248,246,242,.95);backdrop-filter:blur(12px);
      border-top:1px solid #EDEDEB;padding:16px 20px;
      display:flex;align-items:center;gap:12px;
      transform:translateY(100%);transition:transform .4s cubic-bezier(.25,.46,.45,.94);
    }
    .sticky-atc.visible{transform:translateY(0);}

    /* LIGHTBOX */
    .lightbox{
      position:fixed;inset:0;background:rgba(10,10,10,.92);z-index:500;
      display:flex;align-items:center;justify-content:center;
      opacity:0;pointer-events:none;transition:opacity .35s ease;
    }
    .lightbox.open{opacity:1;pointer-events:all;}
    .lightbox img{max-width:90vw;max-height:90vh;object-fit:contain;}

    /* CART DRAWER */
    .cart-drawer{position:fixed;top:0;right:0;width:min(420px,100vw);height:100%;background:#F8F6F2;z-index:200;transform:translateX(100%);transition:transform .45s cubic-bezier(.25,.46,.45,.94);display:flex;flex-direction:column;border-left:1px solid #D5D3CF;}
    .cart-drawer.open{transform:translateX(0);}
    .cart-overlay{position:fixed;inset:0;background:rgba(10,10,10,.4);z-index:199;opacity:0;pointer-events:none;transition:opacity .4s ease;}
    .cart-overlay.open{opacity:1;pointer-events:all;}

    /* MOBILE MENU */
    .mobile-menu{position:fixed;inset:0;background:#0A0A0A;z-index:100;transform:translateX(-100%);transition:transform .5s cubic-bezier(.25,.46,.45,.94);display:flex;flex-direction:column;padding:32px;}
    .mobile-menu.open{transform:translateX(0);}

    /* REVEAL */
    .reveal{opacity:0;transform:translateY(24px);transition:opacity .7s cubic-bezier(.25,.46,.45,.94),transform .7s cubic-bezier(.25,.46,.45,.94);}
    .reveal.visible{opacity:1;transform:translateY(0);}

    /* TOAST */
    #toast{position:fixed;bottom:32px;left:50%;transform:translateX(-50%) translateY(80px);background:#0A0A0A;color:#F8F6F2;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.12em;text-transform:uppercase;padding:14px 28px;z-index:300;opacity:0;transition:all .4s cubic-bezier(.25,.46,.45,.94);white-space:nowrap;display:flex;align-items:center;gap:10px;}

    /* FOOTER */
    .footer-link{font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;text-decoration:none;transition:color .3s ease;}
    .footer-link:hover{color:#F8F6F2;}

    /* BREADCRUMB */
    .bc{font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;text-decoration:none;transition:color .2s ease;}
    .bc:hover,.bc.cur{color:#0A0A0A;}
  </style>
</head>
<body>



 {{-- Navbar --}}
 @include('partials.nav-bar2')

 {{-- Mobile Menu --}}
 @include('partials.mobile-menu')

 {{-- Cart Drawer --}}
 @include('partials.cart-drawer')

<!-- TOAST -->
<div id="toast">
  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="20 6 9 17 4 12"/></svg>
  Added to bag
  <button onclick="toggleCart()" style="background:none;border:none;cursor:pointer;color:#9C9A96;font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.1em;text-transform:uppercase;margin-left:8px;">View Bag</button>
</div>




<!-- ══════════════════════════════════════════
     BREADCRUMB
══════════════════════════════════════════ -->
<div style="padding:16px 40px;border-bottom:1px solid #EDEDEB;max-width:1440px;margin:0 auto;">
  <div style="display:flex;align-items:center;gap:8px;">
    <a href="index.html" class="bc">Home</a>
    <span style="color:#D5D3CF;font-size:11px;">/</span>
    <a href="shop.html" class="bc">Shop</a>
    <span style="color:#D5D3CF;font-size:11px;">/</span>
    <a href="shop.html" class="bc">Tops & Tees</a>
    <span style="color:#D5D3CF;font-size:11px;">/</span>
    <span class="bc cur">Oversized Essential Tee</span>
  </div>
</div>


<!-- ══════════════════════════════════════════
     PRODUCT MAIN LAYOUT
══════════════════════════════════════════ -->
<div style="max-width:1440px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:0;" id="pdpLayout">

  <!-- LEFT: GALLERY -->
  <div style="padding:32px 40px 0 40px;display:flex;gap:16px;position:sticky;top:64px;align-self:start;max-height:calc(100vh - 64px);">

    <!-- Thumbnail Strip -->
    <div style="display:flex;flex-direction:column;gap:8px;flex-shrink:0;" id="thumbs">
      <div class="gallery-thumb active" onclick="switchImg(0,this)">
        <img src="https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=300&q=80" alt="Thumb 1">
      </div>
      <div class="gallery-thumb" onclick="switchImg(1,this)">
        <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=300&q=80" alt="Thumb 2">
      </div>
      <div class="gallery-thumb" onclick="switchImg(2,this)">
        <img src="https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=300&q=80" alt="Thumb 3">
      </div>
      <div class="gallery-thumb" onclick="switchImg(3,this)">
        <img src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?w=300&q=80" alt="Thumb 4">
      </div>
      <div class="gallery-thumb" onclick="switchImg(4,this)">
        <img src="https://images.unsplash.com/photo-1578681994506-b8f463449011?w=300&q=80" alt="Thumb 5">
      </div>
    </div>

    <!-- Main Image -->
    <div class="main-img-wrap" style="flex:1;cursor:zoom-in;" onclick="openLightbox()">
      <img id="mainImg"
        src="https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&q=85"
        alt="Oversized Essential Tee - Main">
      <div class="zoom-badge">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
        Click to Zoom
      </div>
      <!-- Nav Arrows on mobile -->
      <button onclick="prevImg(event)" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);background:rgba(248,246,242,.85);border:none;cursor:pointer;width:36px;height:36px;display:flex;align-items:center;justify-content:center;" class="md:hidden">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M15 18l-6-6 6-6"/></svg>
      </button>
      <button onclick="nextImg(event)" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:rgba(248,246,242,.85);border:none;cursor:pointer;width:36px;height:36px;display:flex;align-items:center;justify-content:center;" class="md:hidden">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 18l6-6-6-6"/></svg>
      </button>
    </div>
  </div>


  <!-- RIGHT: PRODUCT INFO -->
  <div style="padding:40px 40px 80px 48px;max-width:560px;" id="productInfo">

    <!-- Brand + Collection -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
      <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;">RYO · SS25 Collection</p>
      <div style="display:flex;align-items:center;gap:4px;">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="#0A0A0A" stroke="none"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="#0A0A0A" stroke="none"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="#0A0A0A" stroke="none"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="#0A0A0A" stroke="none"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="#D5D3CF" stroke="none"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        <span style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-left:4px;">(124)</span>
      </div>
    </div>

    <!-- Title + Price -->
    <h1 style="font-family:'Cormorant Garamond',serif;font-size:clamp(32px,3.5vw,48px);font-weight:300;letter-spacing:-.01em;line-height:1.1;margin-bottom:16px;">
      Oversized Essential Tee
    </h1>
    <div style="display:flex;align-items:baseline;gap:16px;margin-bottom:32px;">
      <span style="font-family:'DM Sans',sans-serif;font-size:22px;font-weight:400;color:#0A0A0A;">EGP 2,200</span>
      <span style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.15em;text-transform:uppercase;color:#9C9A96;">Incl. VAT</span>
    </div>

    <!-- Divider -->
    <div style="height:1px;background:#EDEDEB;margin-bottom:28px;"></div>

    <!-- Color Selection -->
    <div style="margin-bottom:28px;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;">
          Color: <span id="selectedColor" style="color:#9C9A96;font-weight:400;">Stone</span>
        </p>
      </div>
      <div style="display:flex;gap:10px;align-items:center;">
        <div class="color-swatch-opt active" style="background:#D5D3CF;" title="Stone" onclick="selectColor(this,'Stone')"></div>
        <div class="color-swatch-opt" style="background:#0A0A0A;" title="Black" onclick="selectColor(this,'Black')"></div>
        <div class="color-swatch-opt" style="background:#F2EEE8;border:1px solid #D5D3CF;" title="Cream" onclick="selectColor(this,'Cream')"></div>
        <div class="color-swatch-opt" style="background:#3D3D3A;" title="Charcoal" onclick="selectColor(this,'Charcoal')"></div>
        <div class="color-swatch-opt" style="background:#8B6F5A;" title="Vintage Brown" onclick="selectColor(this,'Vintage Brown')"></div>
      </div>
    </div>

    <!-- Size Selection -->
    <div style="margin-bottom:32px;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;">
          Size: <span id="selectedSize" style="color:#9C9A96;">Select Size</span>
        </p>
        <button onclick="openSizeGuide()" style="background:none;border:none;cursor:pointer;font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;text-decoration:underline;text-underline-offset:3px;display:flex;align-items:center;gap:5px;">
          Size Guide
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </button>
      </div>
      <div style="display:flex;gap:8px;flex-wrap:wrap;" id="sizeBtns">
        <button class="size-btn" onclick="selectSize(this,'XS')">XS</button>
        <button class="size-btn" onclick="selectSize(this,'S')">S</button>
        <button class="size-btn" onclick="selectSize(this,'M')">M</button>
        <button class="size-btn" onclick="selectSize(this,'L')">L</button>
        <button class="size-btn" onclick="selectSize(this,'XL')">XL</button>
        <button class="size-btn sold-out" onclick="selectSize(this,'XXL')" disabled>XXL</button>
      </div>
      <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;margin-top:8px;" id="sizeError" style="display:none;"></p>
    </div>

    <!-- Quantity + Add to Cart -->
    <div style="margin-bottom:24px;">
      <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;margin-bottom:14px;">Quantity</p>
      <div style="display:flex;gap:12px;align-items:center;">
        <!-- Qty Control -->
        <div style="display:flex;align-items:center;border:1px solid #D5D3CF;height:52px;flex-shrink:0;">
          <button class="qty-btn" onclick="changeQty(-1)" style="width:44px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="5" y1="12" x2="19" y2="12"/></svg>
          </button>
          <span id="qty" style="font-family:'DM Sans',sans-serif;font-size:14px;width:36px;text-align:center;border-left:1px solid #EDEDEB;border-right:1px solid #EDEDEB;height:100%;display:flex;align-items:center;justify-content:center;">1</span>
          <button class="qty-btn" onclick="changeQty(1)" style="width:44px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          </button>
        </div>
        <!-- ATC -->
        <button class="atc-btn" id="atcBtn" onclick="addToCart()">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
          Add to Bag
        </button>
        <!-- Wishlist -->
        <button class="wishlist-btn-lg" id="wishBtn" onclick="toggleWishlist()" aria-label="Add to wishlist">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="1.5" id="wishIcon"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
        </button>
      </div>
    </div>

    <!-- Trust Badges -->
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0;border:1px solid #EDEDEB;margin-bottom:32px;">
      <div style="padding:14px 12px;text-align:center;border-right:1px solid #EDEDEB;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5" style="margin:0 auto 6px;display:block;"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
        <p style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;line-height:1.4;">Free Delivery<br>EGP 2,000+</p>
      </div>
      <div style="padding:14px 12px;text-align:center;border-right:1px solid #EDEDEB;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5" style="margin:0 auto 6px;display:block;"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 014-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 01-4 4H3"/></svg>
        <p style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;line-height:1.4;">14-Day<br>Returns</p>
      </div>
      <div style="padding:14px 12px;text-align:center;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5" style="margin:0 auto 6px;display:block;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        <p style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;line-height:1.4;">Secure<br>Checkout</p>
      </div>
    </div>

    <!-- Divider -->
    <div style="height:1px;background:#EDEDEB;margin-bottom:0;"></div>

    <!-- ACCORDION -->
    <div style="margin-bottom:0;">

      <!-- Description -->
      <div class="accordion-item open" id="acc-desc">
        <div class="accordion-header" onclick="toggleAccordion('acc-desc')">
          Description
          <svg class="accordion-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </div>
        <div class="accordion-body">
          <div class="accordion-content">
            <p style="margin-bottom:12px;">The RYO Essential Tee is our signature oversized silhouette — designed for those who understand that less is more. Cut from 350gsm heavyweight cotton, this tee drapes with intention and improves with every wash.</p>
            <p>Dropped shoulders, a relaxed chest, and a slightly elongated hem create a premium casual look that works from morning to midnight.</p>
          </div>
        </div>
      </div>

      <!-- Details -->
      <div class="accordion-item" id="acc-details">
        <div class="accordion-header" onclick="toggleAccordion('acc-details')">
          Material & Construction
          <svg class="accordion-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </div>
        <div class="accordion-body">
          <div class="accordion-content">
            <ul>
              <li>100% Premium Egyptian Cotton, 350gsm</li>
              <li>Heavyweight, garment-dyed finish</li>
              <li>Dropped shoulder, relaxed silhouette</li>
              <li>Reinforced double-stitched seams</li>
              <li>Ribbed collar with slight stretch</li>
              <li>Elongated back hem</li>
              <li>Preshrunk — minimal further shrinkage</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Fit -->
      <div class="accordion-item" id="acc-fit">
        <div class="accordion-header" onclick="toggleAccordion('acc-fit')">
          Fit & Sizing
          <svg class="accordion-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </div>
        <div class="accordion-body">
          <div class="accordion-content">
            <p style="margin-bottom:12px;">Fits oversized. Model is 185cm and wears size M. For a relaxed but not overwhelming fit, we recommend sizing down.</p>
            <table style="width:100%;border-collapse:collapse;margin-top:4px;">
              <thead>
                <tr style="border-bottom:1px solid #EDEDEB;">
                  <th style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;text-align:left;padding:8px 0;font-weight:400;">Size</th>
                  <th style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;text-align:left;padding:8px 0;font-weight:400;">Chest</th>
                  <th style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;text-align:left;padding:8px 0;font-weight:400;">Length</th>
                </tr>
              </thead>
              <tbody>
                <tr style="border-bottom:1px solid #EDEDEB;"><td style="padding:8px 0;font-size:12px;">XS</td><td style="padding:8px 0;font-size:12px;color:#9C9A96;">108cm</td><td style="padding:8px 0;font-size:12px;color:#9C9A96;">70cm</td></tr>
                <tr style="border-bottom:1px solid #EDEDEB;"><td style="padding:8px 0;font-size:12px;">S</td><td style="padding:8px 0;font-size:12px;color:#9C9A96;">114cm</td><td style="padding:8px 0;font-size:12px;color:#9C9A96;">72cm</td></tr>
                <tr style="border-bottom:1px solid #EDEDEB;"><td style="padding:8px 0;font-size:12px;">M</td><td style="padding:8px 0;font-size:12px;color:#9C9A96;">120cm</td><td style="padding:8px 0;font-size:12px;color:#9C9A96;">74cm</td></tr>
                <tr style="border-bottom:1px solid #EDEDEB;"><td style="padding:8px 0;font-size:12px;">L</td><td style="padding:8px 0;font-size:12px;color:#9C9A96;">126cm</td><td style="padding:8px 0;font-size:12px;color:#9C9A96;">76cm</td></tr>
                <tr><td style="padding:8px 0;font-size:12px;">XL</td><td style="padding:8px 0;font-size:12px;color:#9C9A96;">132cm</td><td style="padding:8px 0;font-size:12px;color:#9C9A96;">78cm</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Care -->
      <div class="accordion-item" id="acc-care">
        <div class="accordion-header" onclick="toggleAccordion('acc-care')">
          Care Instructions
          <svg class="accordion-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </div>
        <div class="accordion-body">
          <div class="accordion-content">
            <ul>
              <li>Machine wash cold, gentle cycle</li>
              <li>Do not bleach</li>
              <li>Tumble dry low heat</li>
              <li>Cool iron if needed</li>
              <li>Do not dry clean</li>
              <li>Turn inside out to preserve colour</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Shipping -->
      <div class="accordion-item" id="acc-shipping" style="border-bottom:none;">
        <div class="accordion-header" onclick="toggleAccordion('acc-shipping')">
          Shipping & Returns
          <svg class="accordion-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </div>
        <div class="accordion-body">
          <div class="accordion-content">
            <ul>
              <li>Standard delivery: 2–4 business days</li>
              <li>Free delivery on orders over EGP 2,000</li>
              <li>Express delivery available at checkout</li>
              <li>Free returns within 14 days of delivery</li>
              <li>Items must be unworn with tags attached</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

  </div>
</div><!-- end pdp layout -->


<!-- ══════════════════════════════════════════
     RELATED PRODUCTS
══════════════════════════════════════════ -->
<section style="padding:80px 40px 100px;border-top:1px solid #EDEDEB;max-width:1440px;margin:0 auto;" class="reveal">
  <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:40px;">
    <div>
      <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;margin-bottom:12px;">You Might Also Like</p>
      <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,3vw,44px);font-weight:300;">Complete the Look</h2>
    </div>
    <a href="shop.html" style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:#0A0A0A;text-decoration:none;border-bottom:1px solid #D5D3CF;padding-bottom:2px;" class="hidden md:block">View All</a>
  </div>

  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:24px;" id="relatedGrid">

    <div class="product-card-sm reveal" style="transition-delay:.05s;">
      <a href="product.html" style="text-decoration:none;color:inherit;">
        <div class="img-w" style="aspect-ratio:3/4;">
          <img src="https://images.unsplash.com/photo-1542272604-787c3835535d?w=500&q=80" alt="Slim Chino">
        </div>
        <div style="padding:12px 0 0;">
          <p style="font-size:13px;margin-bottom:3px;">Slim Tapered Chino</p>
          <p style="font-size:12px;color:#9C9A96;margin-bottom:3px;">Stone / Navy / Black</p>
          <p style="font-size:13px;color:#9C9A96;">EGP 2,900</p>
        </div>
      </a>
    </div>

    <div class="product-card-sm reveal" style="transition-delay:.1s;">
      <a href="product.html" style="text-decoration:none;color:inherit;">
        <div class="img-w" style="aspect-ratio:3/4;">
          <img src="https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=500&q=80" alt="Cargo Pant">
        </div>
        <div style="padding:12px 0 0;">
          <p style="font-size:13px;margin-bottom:3px;">Wide Leg Cargo Pant</p>
          <p style="font-size:12px;color:#9C9A96;margin-bottom:3px;">Washed Black</p>
          <p style="font-size:13px;color:#9C9A96;">EGP 3,800</p>
        </div>
      </a>
    </div>

    <div class="product-card-sm reveal" style="transition-delay:.15s;">
      <a href="product.html" style="text-decoration:none;color:inherit;">
        <div class="img-w" style="aspect-ratio:3/4;">
          <img src="https://images.unsplash.com/photo-1594938298603-f4d8c9a3a9a4?w=500&q=80" alt="Crewneck">
        </div>
        <div style="padding:12px 0 0;">
          <p style="font-size:13px;margin-bottom:3px;">Washed Crewneck</p>
          <p style="font-size:12px;color:#9C9A96;margin-bottom:3px;">Faded Grey / Black</p>
          <p style="font-size:13px;color:#9C9A96;">EGP 3,400</p>
        </div>
      </a>
    </div>

    <div class="product-card-sm reveal" style="transition-delay:.2s;">
      <a href="product.html" style="text-decoration:none;color:inherit;">
        <div class="img-w" style="aspect-ratio:3/4;">
          <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=500&q=80" alt="Coach Jacket">
        </div>
        <div style="padding:12px 0 0;">
          <p style="font-size:13px;margin-bottom:3px;">Utility Coach Jacket</p>
          <p style="font-size:12px;color:#9C9A96;margin-bottom:3px;">Olive / Black</p>
          <p style="font-size:13px;color:#9C9A96;">EGP 5,500</p>
        </div>
      </a>
    </div>

  </div>
</section>


<!-- ══════════════════════════════════════════
     STICKY ATC (MOBILE)
══════════════════════════════════════════ -->
<div class="sticky-atc md:hidden" id="stickyATC">
  <div style="flex:1;">
    <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:400;margin-bottom:1px;">Oversized Essential Tee</p>
    <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;">EGP 2,200</p>
  </div>
  <button onclick="addToCart()" style="background:#0A0A0A;color:#F8F6F2;border:none;cursor:pointer;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.12em;text-transform:uppercase;padding:14px 24px;">Add to Bag</button>
</div>


<!-- ══════════════════════════════════════════
     SIZE GUIDE MODAL
══════════════════════════════════════════ -->
<div id="sizeModal" style="position:fixed;inset:0;background:rgba(10,10,10,.5);z-index:400;display:flex;align-items:flex-end;justify-content:center;opacity:0;pointer-events:none;transition:opacity .35s ease;" class="md:items-center">
  <div style="background:#F8F6F2;width:min(640px,100%);max-height:85vh;overflow-y:auto;padding:40px;position:relative;transform:translateY(40px);transition:transform .4s cubic-bezier(.25,.46,.45,.94);" id="sizeModalInner">
    <button onclick="closeSizeGuide()" style="position:absolute;top:20px;right:20px;background:none;border:none;cursor:pointer;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
    </button>
    <h3 style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:300;margin-bottom:8px;">Size Guide</h3>
    <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9C9A96;margin-bottom:24px;">All measurements in centimeters. Our tees fit oversized — size down for a more fitted look.</p>
    <table style="width:100%;border-collapse:collapse;">
      <thead>
        <tr style="border-bottom:2px solid #0A0A0A;">
          <th style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.15em;text-transform:uppercase;padding:10px 0;text-align:left;font-weight:500;">Size</th>
          <th style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.15em;text-transform:uppercase;padding:10px 0;text-align:left;font-weight:500;">Chest (cm)</th>
          <th style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.15em;text-transform:uppercase;padding:10px 0;text-align:left;font-weight:500;">Length (cm)</th>
          <th style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.15em;text-transform:uppercase;padding:10px 0;text-align:left;font-weight:500;">Shoulder (cm)</th>
        </tr>
      </thead>
      <tbody>
        <tr style="border-bottom:1px solid #EDEDEB;"><td style="padding:12px 0;font-size:13px;">XS</td><td style="padding:12px 0;font-size:13px;color:#9C9A96;">108</td><td style="padding:12px 0;font-size:13px;color:#9C9A96;">70</td><td style="padding:12px 0;font-size:13px;color:#9C9A96;">52</td></tr>
        <tr style="border-bottom:1px solid #EDEDEB;"><td style="padding:12px 0;font-size:13px;">S</td><td style="padding:12px 0;font-size:13px;color:#9C9A96;">114</td><td style="padding:12px 0;font-size:13px;color:#9C9A96;">72</td><td style="padding:12px 0;font-size:13px;color:#9C9A96;">54</td></tr>
        <tr style="border-bottom:1px solid #EDEDEB;"><td style="padding:12px 0;font-size:13px;">M</td><td style="padding:12px 0;font-size:13px;color:#9C9A96;">120</td><td style="padding:12px 0;font-size:13px;color:#9C9A96;">74</td><td style="padding:12px 0;font-size:13px;color:#9C9A96;">56</td></tr>
        <tr style="border-bottom:1px solid #EDEDEB;"><td style="padding:12px 0;font-size:13px;">L</td><td style="padding:12px 0;font-size:13px;color:#9C9A96;">126</td><td style="padding:12px 0;font-size:13px;color:#9C9A96;">76</td><td style="padding:12px 0;font-size:13px;color:#9C9A96;">58</td></tr>
        <tr style="border-bottom:1px solid #EDEDEB;"><td style="padding:12px 0;font-size:13px;">XL</td><td style="padding:12px 0;font-size:13px;color:#9C9A96;">132</td><td style="padding:12px 0;font-size:13px;color:#9C9A96;">78</td><td style="padding:12px 0;font-size:13px;color:#9C9A96;">60</td></tr>
        <tr><td style="padding:12px 0;font-size:13px;">XXL</td><td style="padding:12px 0;font-size:13px;color:#9C9A96;">138</td><td style="padding:12px 0;font-size:13px;color:#9C9A96;">80</td><td style="padding:12px 0;font-size:13px;color:#9C9A96;">62</td></tr>
      </tbody>
    </table>
  </div>
</div>


{{-- New Letter --}}
@include('partials.newslater')

 {{-- Footer --}}
 @include('partials.footer')




<script>
  // ── IMAGES ──
  const images = [
    'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&q=85',
    'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=900&q=85',
    'https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=900&q=85',
    'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?w=900&q=85',
    'https://images.unsplash.com/photo-1578681994506-b8f463449011?w=900&q=85',
  ];
  let currentImg = 0;

  function switchImg(idx, thumb) {
    if (idx === currentImg) return;
    const mainImg = document.getElementById('mainImg');
    mainImg.classList.add('fade-out');
    setTimeout(() => {
      mainImg.src = images[idx];
      mainImg.classList.remove('fade-out');
    }, 200);
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    if (thumb) thumb.classList.add('active');
    currentImg = idx;
  }

  function nextImg(e) {
    e.stopPropagation();
    const next = (currentImg + 1) % images.length;
    switchImg(next, document.querySelectorAll('.gallery-thumb')[next]);
  }

  function prevImg(e) {
    e.stopPropagation();
    const prev = (currentImg - 1 + images.length) % images.length;
    switchImg(prev, document.querySelectorAll('.gallery-thumb')[prev]);
  }

  // ── LIGHTBOX ──
  function openLightbox() {
    document.getElementById('lightboxImg').src = images[currentImg].replace('w=900', 'w=1600');
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
    document.body.style.overflow = '';
  }

  // ── SIZE ──
  let selectedSize = null;
  function selectSize(btn, size) {
    if (btn.classList.contains('sold-out')) return;
    document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    selectedSize = size;
    document.getElementById('selectedSize').textContent = size;
    document.getElementById('selectedSize').style.color = '#0A0A0A';
  }

  // ── COLOR ──
  function selectColor(swatch, color) {
    document.querySelectorAll('.color-swatch-opt').forEach(s => s.classList.remove('active'));
    swatch.classList.add('active');
    document.getElementById('selectedColor').textContent = color;
  }

  // ── QTY ──
  let qty = 1;
  function changeQty(dir) {
    qty = Math.max(1, Math.min(10, qty + dir));
    document.getElementById('qty').textContent = qty;
  }

  // ── ADD TO CART ──
  function addToCart() {
    if (!selectedSize) {
      // Flash size error
      document.querySelectorAll('.size-btn:not(.sold-out)').forEach(b => {
        b.style.borderColor = '#0A0A0A';
        setTimeout(() => { if (!b.classList.contains('active')) b.style.borderColor = ''; }, 1200);
      });
      document.getElementById('selectedSize').textContent = '← Please select a size';
      document.getElementById('selectedSize').style.color = '#9C9A96';
      return;
    }

    // Update cart badge
    const badge = document.getElementById('cartBadge');
    badge.style.display = 'flex';
    badge.textContent = (parseInt(badge.textContent) || 0) + qty;

    // Add item to cart drawer
    document.getElementById('cartItems').innerHTML = `
      <div style="display:flex;gap:16px;">
        <div style="width:80px;height:100px;background:#EDEDEB;flex-shrink:0;overflow:hidden;">
          <img src="${images[0]}" style="width:100%;height:100%;object-fit:cover;">
        </div>
        <div style="flex:1;">
          <p style="font-size:13px;margin-bottom:4px;">Oversized Essential Tee</p>
          <p style="font-size:12px;color:#9C9A96;margin-bottom:2px;">Size: ${selectedSize}</p>
          <p style="font-size:12px;color:#9C9A96;margin-bottom:16px;">Qty: ${qty}</p>
          <p style="font-size:13px;">EGP ${(2200 * qty).toLocaleString('en-EG')}</p>
        </div>
      </div>
    `;

    // Show toast
    const toast = document.getElementById('toast');
    toast.style.opacity = '1';
    toast.style.transform = 'translateX(-50%) translateY(0)';
    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateX(-50%) translateY(80px)';
    }, 3000);

    // ATC button feedback
    const btn = document.getElementById('atcBtn');
    btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="20 6 9 17 4 12"/></svg> Added to Bag';
    btn.style.background = '#3D3D3A';
    setTimeout(() => {
      btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg> Add to Bag';
      btn.style.background = '';
    }, 2000);
  }

  // ── WISHLIST ──
  let wishlisted = false;
  function toggleWishlist() {
    wishlisted = !wishlisted;
    const icon = document.getElementById('wishIcon');
    icon.style.fill = wishlisted ? '#0A0A0A' : 'none';
    document.getElementById('wishBtn').style.borderColor = wishlisted ? '#0A0A0A' : '';
  }

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

  // ── ACCORDION ──
  function toggleAccordion(id) {
    document.getElementById(id).classList.toggle('open');
  }

  // ── SIZE GUIDE MODAL ──
  function openSizeGuide() {
    const modal = document.getElementById('sizeModal');
    const inner = document.getElementById('sizeModalInner');
    modal.style.opacity = '1';
    modal.style.pointerEvents = 'all';
    inner.style.transform = 'translateY(0)';
    document.body.style.overflow = 'hidden';
  }
  function closeSizeGuide() {
    const modal = document.getElementById('sizeModal');
    const inner = document.getElementById('sizeModalInner');
    modal.style.opacity = '0';
    modal.style.pointerEvents = 'none';
    inner.style.transform = 'translateY(40px)';
    document.body.style.overflow = '';
  }
  document.getElementById('sizeModal').addEventListener('click', function(e) {
    if (e.target === this) closeSizeGuide();
  });

  // ── STICKY ATC ──
  const stickyATC = document.getElementById('stickyATC');
  const atcBtn = document.getElementById('atcBtn');
  const observer = new IntersectionObserver(([e]) => {
    stickyATC.classList.toggle('visible', !e.isIntersecting);
  }, { threshold: 0 });
  observer.observe(atcBtn);

  // ── SCROLL REVEAL ──
  const revealObs = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revealObs.unobserve(e.target); }});
  }, { threshold: 0.1 });
  document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));

  // ── RESPONSIVE LAYOUT ──
  function checkLayout() {
    const layout = document.getElementById('pdpLayout');
    const relGrid = document.getElementById('relatedGrid');
    if (window.innerWidth < 768) {
      layout.style.gridTemplateColumns = '1fr';
      if (relGrid) relGrid.style.gridTemplateColumns = 'repeat(2,1fr)';
      document.getElementById('thumbs').style.flexDirection = 'row';
      document.getElementById('thumbs').style.overflowX = 'auto';
    } else {
      layout.style.gridTemplateColumns = '1fr 1fr';
      if (relGrid) relGrid.style.gridTemplateColumns = 'repeat(4,1fr)';
    }
  }
  checkLayout();
  window.addEventListener('resize', checkLayout);
</script>
</body>
</html>
