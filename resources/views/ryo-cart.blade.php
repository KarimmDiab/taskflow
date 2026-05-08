<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RYO - Your Bag</title>
  <meta name="description" content="Review your RYO bag and proceed to checkout.">
  <link href="{{ asset('css/website.css') }}" rel="stylesheet">

  <script src="https://cdn.tailwindcss.com"></script>
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

    /* ── NAV ── */
    .nav-link{font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:#0A0A0A;text-decoration:none;position:relative;padding-bottom:2px;}
    .nav-link::after{content:'';position:absolute;bottom:0;left:0;width:0;height:1px;background:#0A0A0A;transition:width .3s cubic-bezier(.25,.46,.45,.94);}
    .nav-link:hover::after{width:100%;}

    /* ── CART ITEM ── */
    .cart-item{
      display:grid;
      grid-template-columns:120px 1fr auto;
      gap:28px;
      padding:32px 0;
      border-bottom:1px solid #EDEDEB;
      align-items:start;
      transition:opacity .35s ease;
    }
    .cart-item.removing{opacity:0;transform:translateX(-12px);transition:opacity .35s ease,transform .35s ease;}
    .item-img{
      width:120px;height:150px;overflow:hidden;background:#EDEDEB;
      flex-shrink:0;position:relative;
    }
    .item-img img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .6s cubic-bezier(.25,.46,.45,.94);}
    .cart-item:hover .item-img img{transform:scale(1.04);}

    /* ── QTY CONTROL ── */
    .qty-control{
      display:inline-flex;align-items:center;
      border:1px solid #D5D3CF;height:38px;
    }
    .qty-btn{
      width:36px;height:100%;background:none;border:none;cursor:pointer;
      display:flex;align-items:center;justify-content:center;
      color:#9C9A96;transition:color .2s ease,background .2s ease;
      font-size:18px;
    }
    .qty-btn:hover{color:#0A0A0A;background:#EDEDEB;}
    .qty-num{
      width:36px;height:100%;display:flex;align-items:center;justify-content:center;
      font-family:'DM Sans',sans-serif;font-size:13px;
      border-left:1px solid #EDEDEB;border-right:1px solid #EDEDEB;
    }

    /* ── REMOVE BTN ── */
    .remove-btn{
      background:none;border:none;cursor:pointer;
      font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.14em;text-transform:uppercase;
      color:#9C9A96;display:flex;align-items:center;gap:5px;
      padding:0;margin-top:12px;transition:color .2s ease;
    }
    .remove-btn:hover{color:#0A0A0A;}

    /* ── SIZE/COLOR CHIP ── */
    .chip{
      display:inline-block;font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.12em;
      text-transform:uppercase;color:#9C9A96;border:1px solid #EDEDEB;padding:3px 8px;
      margin-right:6px;margin-bottom:6px;
    }

    /* ── PROMO ── */
    .promo-wrap{display:flex;gap:0;border:1px solid #D5D3CF;overflow:hidden;}
    .promo-input{
      flex:1;border:none;background:transparent;font-family:'DM Sans',sans-serif;font-size:13px;
      font-weight:300;padding:11px 16px;outline:none;color:#0A0A0A;
    }
    .promo-input::placeholder{color:#9C9A96;}
    .promo-btn{
      background:#0A0A0A;color:#F8F6F2;border:none;cursor:pointer;
      font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.12em;text-transform:uppercase;
      padding:11px 20px;transition:background .2s ease;white-space:nowrap;
    }
    .promo-btn:hover{background:#3D3D3A;}

    /* ── CHECKOUT BTN ── */
    .checkout-btn{
      width:100%;background:#0A0A0A;color:#F8F6F2;border:none;cursor:pointer;
      font-family:'Space Grotesk',sans-serif;font-size:12px;letter-spacing:.16em;text-transform:uppercase;
      padding:17px;display:flex;align-items:center;justify-content:center;gap:12px;
      transition:all .3s cubic-bezier(.25,.46,.45,.94);text-decoration:none;
    }
    .checkout-btn:hover{background:#3D3D3A;}

    /* ── CONTINUE LINK ── */
    .continue-link{
      display:inline-flex;align-items:center;gap:8px;
      font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.14em;text-transform:uppercase;
      color:#9C9A96;text-decoration:none;transition:color .25s ease;
    }
    .continue-link:hover{color:#0A0A0A;}

    /* ── UPSELL CARD ── */
    .upsell-card{
      display:flex;gap:16px;padding:16px;border:1px solid #EDEDEB;
      cursor:pointer;transition:border-color .25s ease;
    }
    .upsell-card:hover{border-color:#0A0A0A;}
    .upsell-img{width:72px;height:88px;overflow:hidden;background:#EDEDEB;flex-shrink:0;}
    .upsell-img img{width:100%;height:100%;object-fit:cover;transition:transform .5s ease;}
    .upsell-card:hover .upsell-img img{transform:scale(1.05);}
    .upsell-add{
      width:30px;height:30px;background:#0A0A0A;border:none;cursor:pointer;
      display:flex;align-items:center;justify-content:center;
      color:#F8F6F2;flex-shrink:0;align-self:flex-end;
      transition:background .2s ease;
    }
    .upsell-add:hover{background:#3D3D3A;}

    /* ── PRODUCT CARD (recs) ── */
    .rec-card{position:relative;cursor:pointer;}
    .rec-img{overflow:hidden;background:#EDEDEB;aspect-ratio:3/4;}
    .rec-img img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .65s cubic-bezier(.25,.46,.45,.94);}
    .rec-card:hover .rec-img img{transform:scale(1.04);}
    .rec-quick-add{
      position:absolute;bottom:0;left:0;right:0;background:rgba(10,10,10,.88);
      color:#F8F6F2;font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.14em;
      text-transform:uppercase;text-align:center;padding:12px;
      opacity:0;transform:translateY(6px);transition:all .3s cubic-bezier(.25,.46,.45,.94);
      cursor:pointer;border:none;width:100%;
    }
    .rec-card:hover .rec-quick-add{opacity:1;transform:translateY(0);}

    /* ── MOBILE MENU ── */
    .mobile-menu{position:fixed;inset:0;background:#0A0A0A;z-index:100;transform:translateX(-100%);transition:transform .5s cubic-bezier(.25,.46,.45,.94);display:flex;flex-direction:column;padding:32px;}
    .mobile-menu.open{transform:translateX(0);}

    /* ── EMPTY STATE ── */
    .empty-state{
      display:flex;flex-direction:column;align-items:center;justify-content:center;
      padding:120px 40px;text-align:center;
    }

    /* ── REVEAL ── */
    .reveal{opacity:0;transform:translateY(20px);transition:opacity .7s cubic-bezier(.25,.46,.45,.94),transform .7s cubic-bezier(.25,.46,.45,.94);}
    .reveal.visible{opacity:1;transform:translateY(0);}

    /* ── FOOTER ── */
    .footer-link{font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;text-decoration:none;transition:color .3s ease;}
    .footer-link:hover{color:#F8F6F2;}

    /* ── TOAST ── */
    #toast{position:fixed;bottom:32px;left:50%;transform:translateX(-50%) translateY(80px);background:#0A0A0A;color:#F8F6F2;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.12em;text-transform:uppercase;padding:14px 28px;z-index:300;opacity:0;transition:all .4s cubic-bezier(.25,.46,.45,.94);white-space:nowrap;display:flex;align-items:center;gap:10px;}

    /* ── STICKY ORDER SUMMARY (mobile) ── */
    .mobile-summary-sticky{
      position:fixed;bottom:0;left:0;right:0;
      background:rgba(248,246,242,.96);backdrop-filter:blur(12px);
      border-top:1px solid #EDEDEB;padding:16px 20px;
      display:flex;align-items:center;gap:12px;z-index:40;
    }

    /* ── PROGRESS BAR (free shipping) ── */
    .progress-track{height:2px;background:#EDEDEB;position:relative;overflow:hidden;margin:8px 0;}
    .progress-fill{height:100%;background:#0A0A0A;transition:width .6s cubic-bezier(.25,.46,.45,.94);}
  </style>
</head>
<body>

<!-- TOAST -->
<div id="toast">
  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="20 6 9 17 4 12"/></svg>
  <span id="toastMsg">Item added to bag</span>
</div>

 {{-- Navbar --}}
 @include('partials.nav-bar2')


 {{-- Cart Drawer --}}
 @include('partials.cart-drawer')



<!-- ══════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════ -->
<div style="padding:40px 40px 0;max-width:1440px;margin:0 auto;">
  <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;">
    <a href="index.html" style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;text-decoration:none;transition:color .2s ease;" onmouseover="this.style.color='#0A0A0A'" onmouseout="this.style.color='#9C9A96'">Home</a>
    <span style="color:#D5D3CF;font-size:11px;">/</span>
    <span style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:#0A0A0A;">Your Bag</span>
  </div>
  <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:0;flex-wrap:wrap;gap:12px;">
    <div>
      <h1 style="font-family:'Cormorant Garamond',serif;font-size:clamp(40px,5vw,72px);font-weight:300;letter-spacing:-.02em;line-height:.95;">Your Bag</h1>
    </div>
    <a href="shop.html" class="continue-link">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
      Continue Shopping
    </a>
  </div>
</div>


<!-- ══════════════════════════════════════════
     FREE SHIPPING PROGRESS BAR
══════════════════════════════════════════ -->
<div style="padding:20px 40px 0;max-width:1440px;margin:0 auto;" id="freeShippingBar">
  <div style="background:#F2EEE8;padding:14px 20px;display:flex;align-items:center;gap:16px;">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5" style="flex-shrink:0;"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
    <div style="flex:1;">
      <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:#3D3D3A;margin-bottom:6px;" id="shippingMsg">
        You're <strong>EGP 350</strong> away from free shipping!
      </p>
      <div class="progress-track">
        <div class="progress-fill" id="progressFill" style="width:83%;"></div>
      </div>
    </div>
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5" style="flex-shrink:0;"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
  </div>
</div>


<!-- ══════════════════════════════════════════
     MAIN CART LAYOUT
══════════════════════════════════════════ -->
<div style="max-width:1440px;margin:0 auto;padding:32px 40px 160px;display:grid;grid-template-columns:1fr 380px;gap:64px;align-items:start;" id="cartLayout">

  <!-- ── LEFT: CART ITEMS ── -->
  <div>

    <!-- COLUMN HEADERS -->
    <div style="display:grid;grid-template-columns:120px 1fr auto;gap:28px;padding-bottom:16px;border-bottom:2px solid #0A0A0A;" class="hidden md:grid">
      <span style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.18em;text-transform:uppercase;color:#9C9A96;">Product</span>
      <span style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.18em;text-transform:uppercase;color:#9C9A96;padding-left:0;"></span>
      <span style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.18em;text-transform:uppercase;color:#9C9A96;text-align:right;">Total</span>
    </div>

    <!-- CART ITEMS CONTAINER -->
    <div id="cartItemsContainer">

      <!-- ── ITEM 1 ── -->
      <div class="cart-item reveal" id="item-1" data-price="1,000" data-qty="1">
        <!-- Image -->
        <div class="item-img">
          <a href="product.html">
            <img src="https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=400&q=80" alt="Oversized Essential Tee">
          </a>
        </div>

        <!-- Details -->
        <div>
          <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:10px;">
            <div>
              <p style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.15em;text-transform:uppercase;color:#9C9A96;margin-bottom:6px;">RYO · SS25</p>
              <a href="product.html" style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:400;color:#0A0A0A;text-decoration:none;display:block;margin-bottom:8px;transition:opacity .2s ease;" onmouseover="this.style.opacity='.6'" onmouseout="this.style.opacity='1'">Oversized Essential Tee</a>
            </div>
            <!-- Mobile price -->
            <span style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:300;" class="md:hidden" id="item-1-mobile-price">EGP 1000</span>
          </div>

          <div style="margin-bottom:16px;">
            <span class="chip">Stone</span>
            <span class="chip">Size: L</span>
          </div>

          <!-- Qty + Remove row -->
          <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
            <div class="qty-control">
              <button class="qty-btn" onclick="changeQty('item-1', -1)" aria-label="Decrease">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="5" y1="12" x2="19" y2="12"/></svg>
              </button>
              <span class="qty-num" id="item-1-qty">1</span>
              <button class="qty-btn" onclick="changeQty('item-1', 1)" aria-label="Increase">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              </button>
            </div>
            <button class="remove-btn" onclick="removeItem('item-1')">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
              Remove
            </button>
            <button style="background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:5px;font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.14em;text-transform:uppercase;color:#9C9A96;transition:color .2s ease;padding:0;" onmouseover="this.style.color='#0A0A0A'" onmouseout="this.style.color='#9C9A96'">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
              Save for Later
            </button>
          </div>
        </div>

        <!-- Price (desktop) -->
        <div style="text-align:right;" class="hidden md:block">
          <p style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:300;" id="item-1-price">EGP 1000</p>
        </div>
      </div>


      <!-- ── ITEM 2 ── -->
      <div class="cart-item reveal" id="item-2" data-price="3800" data-qty="1" style="transition-delay:.07s;">
        <div class="item-img">
          <a href="product.html">
            <img src="https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=400&q=80" alt="Wide Leg Cargo Pant">
          </a>
        </div>
        <div>
          <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:10px;">
            <div>
              <p style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.15em;text-transform:uppercase;color:#9C9A96;margin-bottom:6px;">RYO · SS25</p>
              <a href="product.html" style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:400;color:#0A0A0A;text-decoration:none;display:block;margin-bottom:8px;transition:opacity .2s ease;" onmouseover="this.style.opacity='.6'" onmouseout="this.style.opacity='1'">Wide Leg Cargo Pant</a>
            </div>
            <span style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:300;" class="md:hidden" id="item-2-mobile-price">EGP 1000</span>
          </div>
          <div style="margin-bottom:16px;">
            <span class="chip">Washed Black</span>
            <span class="chip">Size: 32</span>
          </div>
          <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
            <div class="qty-control">
              <button class="qty-btn" onclick="changeQty('item-2', -1)"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
              <span class="qty-num" id="item-2-qty">1</span>
              <button class="qty-btn" onclick="changeQty('item-2', 1)"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
            </div>
            <button class="remove-btn" onclick="removeItem('item-2')">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
              Remove
            </button>
            <button style="background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:5px;font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.14em;text-transform:uppercase;color:#9C9A96;transition:color .2s ease;padding:0;" onmouseover="this.style.color='#0A0A0A'" onmouseout="this.style.color='#9C9A96'">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
              Save for Later
            </button>
          </div>
        </div>
        <div style="text-align:right;" class="hidden md:block">
          <p style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:300;" id="item-2-price">EGP 3,800</p>
        </div>
      </div>


      <!-- ── ITEM 3 ── -->
      <div class="cart-item reveal" id="item-3" data-price="4200" data-qty="1" style="transition-delay:.14s;">
        <div class="item-img">
          <a href="product.html">
            <img src="https://images.unsplash.com/photo-1509631179647-0177331693ae?w=400&q=80" alt="Relaxed Heavy Hoodie">
          </a>
        </div>
        <div>
          <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:10px;">
            <div>
              <p style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.15em;text-transform:uppercase;color:#9C9A96;margin-bottom:6px;">RYO · SS25</p>
              <a href="product.html" style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:400;color:#0A0A0A;text-decoration:none;display:block;margin-bottom:8px;transition:opacity .2s ease;" onmouseover="this.style.opacity='.6'" onmouseout="this.style.opacity='1'">Relaxed Heavy Hoodie</a>
            </div>
            <span style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:300;" class="md:hidden" id="item-3-mobile-price">EGP 4,200</span>
          </div>
          <div style="margin-bottom:16px;">
            <span class="chip">Charcoal</span>
            <span class="chip">Size: M</span>
          </div>
          <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
            <div class="qty-control">
              <button class="qty-btn" onclick="changeQty('item-3', -1)"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
              <span class="qty-num" id="item-3-qty">1</span>
              <button class="qty-btn" onclick="changeQty('item-3', 1)"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
            </div>
            <button class="remove-btn" onclick="removeItem('item-3')">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
              Remove
            </button>
            <button style="background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:5px;font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.14em;text-transform:uppercase;color:#9C9A96;transition:color .2s ease;padding:0;" onmouseover="this.style.color='#0A0A0A'" onmouseout="this.style.color='#9C9A96'">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
              Save for Later
            </button>
          </div>
        </div>
        <div style="text-align:right;" class="hidden md:block">
          <p style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:300;" id="item-3-price">EGP 4,200</p>
        </div>
      </div>

    </div><!-- end cartItemsContainer -->


    <!-- ── EMPTY STATE (hidden by default) ── -->
    <div class="empty-state" id="emptyState" style="display:none;">
      <div style="width:64px;height:64px;border:1px solid #EDEDEB;display:flex;align-items:center;justify-content:center;margin-bottom:28px;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
      </div>
      <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;margin-bottom:16px;">Your bag is empty</p>
      <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,4vw,44px);font-weight:300;margin-bottom:16px;line-height:1.1;">Nothing here <em>yet</em></h2>
      <p style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#9C9A96;margin-bottom:40px;max-width:360px;line-height:1.7;">Looks like you haven't added anything to your bag. Explore our latest collection and find something you love.</p>
      <a href="shop.html" style="display:inline-flex;align-items:center;gap:10px;background:#0A0A0A;color:#F8F6F2;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.14em;text-transform:uppercase;padding:14px 32px;text-decoration:none;transition:background .3s ease;" onmouseover="this.style.background='#3D3D3A'" onmouseout="this.style.background='#0A0A0A'">
        Shop Collection
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
    </div>


    <!-- ── YOU MAY ALSO LIKE (upsell) ── -->
    <div id="upsellSection" style="padding-top:48px;margin-top:16px;border-top:1px solid #EDEDEB;">
      <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:#9C9A96;margin-bottom:20px;">Complete Your Look</p>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;" id="upsellGrid">

        <div class="upsell-card" onclick="addUpsell(this, 'Utility Coach Jacket', 5500)">
          <div class="upsell-img">
            <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=300&q=80" alt="Coach Jacket">
          </div>
          <div style="flex:1;min-width:0;">
            <p style="font-family:'DM Sans',sans-serif;font-size:12px;font-weight:400;margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Utility Coach Jacket</p>
            <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;margin-bottom:2px;">Olive / Black</p>
            <p style="font-family:'DM Sans',sans-serif;font-size:12px;font-weight:300;">EGP 5,500</p>
          </div>
          <button class="upsell-add" aria-label="Add to bag">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          </button>
        </div>

        <div class="upsell-card" onclick="addUpsell(this, 'Washed Crewneck Sweat', 3400)">
          <div class="upsell-img">
            <img src="https://images.unsplash.com/photo-1594938298603-f4d8c9a3a9a4?w=300&q=80" alt="Crewneck">
          </div>
          <div style="flex:1;min-width:0;">
            <p style="font-family:'DM Sans',sans-serif;font-size:12px;font-weight:400;margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Washed Crewneck Sweat</p>
            <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;margin-bottom:2px;">Faded Grey</p>
            <p style="font-family:'DM Sans',sans-serif;font-size:12px;font-weight:300;">EGP 3,400</p>
          </div>
          <button class="upsell-add" aria-label="Add to bag">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          </button>
        </div>

      </div>
    </div>

  </div><!-- end left col -->


  <!-- ── RIGHT: ORDER SUMMARY ── -->
  <div style="position:sticky;top:88px;" class="reveal" style="transition-delay:.15s;">
    <div style="border:1px solid #EDEDEB;background:#F2EEE8;padding:32px;">

      <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid #D5D3CF;">Order Summary</p>

      <!-- Item Count -->
      <div style="display:flex;justify-content:space-between;margin-bottom:14px;">
        <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;" id="itemCountLabel">3 items</span>
        <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;" id="subtotalDisplay">EGP 10,200</span>
      </div>

      <!-- Shipping -->
      <div style="display:flex;justify-content:space-between;margin-bottom:14px;">
        <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">Shipping</span>
        <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;" id="shippingDisplay">Free</span>
      </div>

      <!-- Discount -->
      <div style="display:flex;justify-content:space-between;margin-bottom:14px;" id="discountRow" style="display:none;">
        <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">Discount</span>
        <span style="font-family:'DM Sans',sans-serif;font-size:13px;" id="discountDisplay">— EGP 0</span>
      </div>

      <!-- Divider -->
      <div style="height:1px;background:#D5D3CF;margin:16px 0;"></div>

      <!-- Total -->
      <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:28px;">
        <span style="font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.15em;text-transform:uppercase;">Total</span>
        <div style="text-align:right;">
          <p style="font-family:'Cormorant Garamond',serif;font-size:32px;font-weight:300;" id="totalDisplay">EGP 10,200</p>
          <p style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;">VAT included</p>
        </div>
      </div>

      <!-- Promo Code -->
      <div style="margin-bottom:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;cursor:pointer;" onclick="togglePromo()">
          <div style="display:flex;align-items:center;gap:6px;">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
            <span style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:#9C9A96;">Promo Code</span>
          </div>
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5" id="promoChevron" style="transition:transform .3s ease;"><path d="M6 9l6 6 6-6"/></svg>
        </div>
        <div id="promoWrap" style="display:none;">
          <div class="promo-wrap">
            <input class="promo-input" type="text" placeholder="Enter promo code" id="promoCode">
            <button class="promo-btn" onclick="applyPromo()">Apply</button>
          </div>
          <p id="promoMsg" style="font-family:'DM Sans',sans-serif;font-size:11px;margin-top:8px;"></p>
        </div>
      </div>

      <!-- Checkout Button -->
      <a href="checkout.html" class="checkout-btn" id="checkoutBtn">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        Secure Checkout
      </a>

      <!-- Or -->
      <div style="display:flex;align-items:center;gap:12px;margin:16px 0;">
        <div style="flex:1;height:1px;background:#D5D3CF;"></div>
        <span style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;">or</span>
        <div style="flex:1;height:1px;background:#D5D3CF;"></div>
      </div>

      <!-- Guest Checkout -->
      <a href="checkout.html" style="display:flex;align-items:center;justify-content:center;gap:10px;border:1px solid #D5D3CF;color:#0A0A0A;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.12em;text-transform:uppercase;padding:14px;text-decoration:none;transition:border-color .3s ease;" onmouseover="this.style.borderColor='#0A0A0A'" onmouseout="this.style.borderColor='#D5D3CF'">
        Continue as Guest
      </a>

      <!-- Trust -->
      <div style="margin-top:20px;padding-top:16px;border-top:1px solid #D5D3CF;display:flex;flex-direction:column;gap:8px;">
        <div style="display:flex;align-items:center;gap:8px;">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          <span style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;font-weight:300;">SSL Encrypted · Secure Payment</span>
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 014-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 01-4 4H3"/></svg>
          <span style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;font-weight:300;">Free returns within 14 days</span>
        </div>
      </div>

      <!-- Payment Icons -->
      <div style="display:flex;align-items:center;gap:8px;margin-top:16px;flex-wrap:wrap;">
        <div style="background:#1a1f71;color:#F8F6F2;font-size:8px;font-family:'Space Grotesk',sans-serif;font-weight:500;padding:4px 8px;letter-spacing:.04em;">VISA</div>
        <div style="display:flex;"><div style="width:22px;height:14px;border-radius:2px;background:#eb001b;"></div><div style="width:22px;height:14px;border-radius:2px;background:#f79e1b;margin-left:-8px;"></div></div>
        <div style="background:#0070BA;color:#F8F6F2;font-size:8px;font-family:'Space Grotesk',sans-serif;font-weight:500;padding:4px 8px;border-radius:2px;">PayPal</div>
        <div style="background:#00A650;color:#F8F6F2;font-size:8px;font-family:'Space Grotesk',sans-serif;font-weight:500;padding:4px 8px;border-radius:2px;">Fawry</div>
        <div style="background:#E6002D;color:#F8F6F2;font-size:8px;font-family:'Space Grotesk',sans-serif;font-weight:500;padding:4px 8px;border-radius:2px;">Voda Cash</div>
      </div>

    </div>
  </div>
</div><!-- end cart layout -->


<!-- ══════════════════════════════════════════
     YOU MAY ALSO LIKE (full width recs)
══════════════════════════════════════════ -->
<section style="padding:0 40px 100px;max-width:1440px;margin:0 auto;" id="recsSection">
  <div style="border-top:1px solid #EDEDEB;padding-top:64px;">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:40px;" class="reveal">
      <div>
        <p style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;margin-bottom:12px;">You Might Like</p>
        <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,3.5vw,48px);font-weight:300;letter-spacing:-.01em;">Recommended for You</h2>
      </div>
      <a href="shop.html" style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:#0A0A0A;text-decoration:none;border-bottom:1px solid #D5D3CF;padding-bottom:2px;" class="hidden md:block">View All</a>
    </div>

    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:24px;" id="recsGrid">

      <div class="rec-card reveal" style="transition-delay:.05s;">
        <a href="product.html" style="text-decoration:none;color:inherit;">
          <div class="rec-img">
            <img src="https://images.unsplash.com/photo-1542272604-787c3835535d?w=500&q=80" alt="Slim Chino">
            <button class="rec-quick-add" onclick="event.preventDefault();showToast('Slim Tapered Chino added')">Quick Add</button>
          </div>
          <div style="padding:12px 0 0;">
            <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:400;margin-bottom:3px;">Slim Tapered Chino</p>
            <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-bottom:3px;">Stone / Navy</p>
            <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">EGP 2,900</p>
          </div>
        </a>
      </div>

      <div class="rec-card reveal" style="transition-delay:.1s;">
        <a href="product.html" style="text-decoration:none;color:inherit;">
          <div class="rec-img">
            <img src="https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=500&q=80" alt="Linen Shirt">
            <button class="rec-quick-add" onclick="event.preventDefault();showToast('Relaxed Linen Shirt added')">Quick Add</button>
          </div>
          <div style="padding:12px 0 0;">
            <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:400;margin-bottom:3px;">Relaxed Linen Shirt</p>
            <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-bottom:3px;">Cream / White</p>
            <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">EGP 2,600</p>
          </div>
        </a>
      </div>

      <div class="rec-card reveal" style="transition-delay:.15s;">
        <a href="product.html" style="text-decoration:none;color:inherit;">
          <div class="rec-img">
            <img src="https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=500&q=80" alt="Bomber">
            <button class="rec-quick-add" onclick="event.preventDefault();showToast('Structured Bomber added')">Quick Add</button>
          </div>
          <div style="padding:12px 0 0;">
            <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:400;margin-bottom:3px;">Structured Bomber</p>
            <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-bottom:3px;">Black / Vintage Brown</p>
            <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">EGP 6,200</p>
          </div>
        </a>
      </div>

      <div class="rec-card reveal" style="transition-delay:.2s;">
        <a href="product.html" style="text-decoration:none;color:inherit;">
          <div class="rec-img">
            <img src="https://images.unsplash.com/photo-1578681994506-b8f463449011?w=500&q=80" alt="Quarter Zip">
            <button class="rec-quick-add" onclick="event.preventDefault();showToast('Quarter Zip Fleece added')">Quick Add</button>
          </div>
          <div style="padding:12px 0 0;">
            <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:400;margin-bottom:3px;">Quarter Zip Fleece</p>
            <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-bottom:3px;">Cream / Washed Grey</p>
            <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:300;color:#9C9A96;">EGP 2,500</p>
          </div>
        </a>
      </div>

    </div>
  </div>
</section>


<!-- ══════════════════════════════════════════
     MOBILE STICKY CHECKOUT BAR
══════════════════════════════════════════ -->
<div class="mobile-summary-sticky md:hidden" id="mobileStickyBar">
  <div style="flex:1;">
    <p style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.14em;text-transform:uppercase;color:#9C9A96;margin-bottom:2px;" id="mobileItemCount">3 items</p>
    <p style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:300;" id="mobileTotalDisplay">EGP 10,200</p>
  </div>
  <a href="checkout.html" style="background:#0A0A0A;color:#F8F6F2;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.12em;text-transform:uppercase;padding:14px 24px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;white-space:nowrap;">
    Checkout
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
  </a>
</div>


  {{-- Newslater --}}
  @include('partials.newslater')


 {{-- Footer --}}
 @include('partials.footer')


<script>
  // ══════════════════════════
  // CART STATE
  // ══════════════════════════
  const cartState = {
    'item-1': { price: 1000, qty: 1 },
    'item-2': { price: 3800, qty: 1 },
    'item-3': { price: 4200, qty: 1 },
  };
  let discount = 0;
  const FREE_SHIPPING_THRESHOLD = 1500;

  // ── CHANGE QTY ──
  function changeQty(id, dir) {
    const item = cartState[id];
    if (!item) return;
    const newQty = Math.max(1, Math.min(10, item.qty + dir));
    item.qty = newQty;
    document.getElementById(id + '-qty').textContent = newQty;
    const lineTotal = item.price * newQty;
    const formatted = 'EGP ' + lineTotal.toLocaleString('en-EG');
    if (document.getElementById(id + '-price')) document.getElementById(id + '-price').textContent = formatted;
    if (document.getElementById(id + '-mobile-price')) document.getElementById(id + '-mobile-price').textContent = formatted;
    recalculate();
  }

  // ── REMOVE ITEM ──
  function removeItem(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.add('removing');
    setTimeout(() => {
      el.remove();
      delete cartState[id];
      recalculate();
      checkEmpty();
    }, 380);
  }

  // ── RECALCULATE ──
  function recalculate() {
    const subtotal = Object.values(cartState).reduce((sum, i) => sum + i.price * i.qty, 0);
    const itemCount = Object.values(cartState).reduce((sum, i) => sum + i.qty, 0);
    const shipping = subtotal >= FREE_SHIPPING_THRESHOLD ? 0 : 60;
    const total = subtotal + shipping - discount;

    // Update displays
    document.getElementById('subtotalDisplay').textContent = 'EGP ' + subtotal.toLocaleString('en-EG');
    document.getElementById('shippingDisplay').textContent = shipping === 0 ? 'Free' : 'EGP 60';
    document.getElementById('totalDisplay').textContent = 'EGP ' + total.toLocaleString('en-EG');
    document.getElementById('itemCountLabel').textContent = itemCount + ' item' + (itemCount !== 1 ? 's' : '');
    document.getElementById('cartBadge').textContent = itemCount;

    // Mobile bar
    document.getElementById('mobileTotalDisplay').textContent = 'EGP ' + total.toLocaleString('en-EG');
    document.getElementById('mobileItemCount').textContent = itemCount + ' item' + (itemCount !== 1 ? 's' : '');

    // Free shipping bar
    const remaining = Math.max(0, FREE_SHIPPING_THRESHOLD - subtotal);
    const pct = Math.min(100, (subtotal / FREE_SHIPPING_THRESHOLD) * 100);
    document.getElementById('progressFill').style.width = pct + '%';
    if (remaining === 0) {
      document.getElementById('shippingMsg').innerHTML = '<strong>🎉 You\'ve unlocked free shipping!</strong>';
    } else {
      document.getElementById('shippingMsg').innerHTML = 'You\'re <strong>EGP ' + remaining.toLocaleString('en-EG') + '</strong> away from free shipping!';
    }

    // Discount row
    if (discount > 0) {
      document.getElementById('discountRow').style.display = 'flex';
      document.getElementById('discountDisplay').textContent = '− EGP ' + discount.toLocaleString('en-EG');
    }
  }

  // ── CHECK EMPTY ──
  function checkEmpty() {
    const hasItems = Object.keys(cartState).length > 0;
    document.getElementById('emptyState').style.display = hasItems ? 'none' : 'flex';
    document.getElementById('upsellSection').style.display = hasItems ? 'block' : 'none';
    document.getElementById('freeShippingBar').style.display = hasItems ? 'block' : 'none';
    document.getElementById('mobileStickyBar').style.display = hasItems ? 'flex' : 'none';
    if (!hasItems) {
      document.querySelector('[style*="grid-template-columns: 120px"]')?.setAttribute('style','display:none');
    }
  }

  // ── PROMO CODE ──
  function togglePromo() {
    const wrap = document.getElementById('promoWrap');
    const chevron = document.getElementById('promoChevron');
    const isOpen = wrap.style.display !== 'none';
    wrap.style.display = isOpen ? 'none' : 'block';
    chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
  }

  function applyPromo() {
    const code = document.getElementById('promoCode').value.toUpperCase().trim();
    const msg = document.getElementById('promoMsg');
    if (code === 'RYO10') {
      const subtotal = Object.values(cartState).reduce((s,i) => s + i.price * i.qty, 0);
      discount = Math.round(subtotal * 0.1);
      msg.textContent = '✓ RYO10 applied — 10% off your order';
      msg.style.color = '#0A0A0A';
    } else if (code === 'FREESHIP') {
      discount = 60;
      msg.textContent = '✓ FREESHIP applied — free shipping';
      msg.style.color = '#0A0A0A';
    } else if (code === '') {
      msg.textContent = 'Please enter a promo code.';
      msg.style.color = '#9C9A96';
      return;
    } else {
      msg.textContent = 'Invalid code. Try RYO10 or FREESHIP.';
      msg.style.color = '#c0392b';
      discount = 0;
    }
    recalculate();
  }

  // ── ADD UPSELL ──
  function addUpsell(card, name, price) {
    const btn = card.querySelector('.upsell-add');
    btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="20 6 9 17 4 12"/></svg>';
    btn.style.background = '#3D3D3A';
    setTimeout(() => {
      btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>';
      btn.style.background = '';
    }, 2000);
    showToast(name + ' added to bag');
  }

  // ── TOAST ──
  function showToast(msg) {
    const toast = document.getElementById('toast');
    document.getElementById('toastMsg').textContent = msg;
    toast.style.opacity = '1';
    toast.style.transform = 'translateX(-50%) translateY(0)';
    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateX(-50%) translateY(80px)';
    }, 3000);
  }

  // ── MENU ──
  function toggleMenu() {
    document.getElementById('mobileMenu').classList.toggle('open');
    document.body.style.overflow = document.getElementById('mobileMenu').classList.contains('open') ? 'hidden' : '';
  }

  // ── SCROLL REVEAL ──
  const revObs = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revObs.unobserve(e.target); }});
  }, { threshold: 0.08 });
  document.querySelectorAll('.reveal').forEach(el => revObs.observe(el));

  // ── RESPONSIVE ──
  function checkLayout() {
    const layout = document.getElementById('cartLayout');
    const recsGrid = document.getElementById('recsGrid');
    if (window.innerWidth < 900) {
      layout.style.gridTemplateColumns = '1fr';
      layout.style.gap = '0';
      if (recsGrid) recsGrid.style.gridTemplateColumns = 'repeat(2,1fr)';
    } else {
      layout.style.gridTemplateColumns = '1fr 380px';
      layout.style.gap = '64px';
      if (recsGrid) recsGrid.style.gridTemplateColumns = 'repeat(4,1fr)';
    }
  }
  checkLayout();
  window.addEventListener('resize', checkLayout);

  // ── INIT ──
  recalculate();
</script>
</body>
</html>
