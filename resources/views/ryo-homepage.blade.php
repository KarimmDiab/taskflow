<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>RYO - Casual Streetwear</title>

    <link rel="icon" type="image/png" href="{{ asset('images/favicon/favicon.png') }}">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/website.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind.js') }}"></script>

</head>

<body>



    {{-- Navbar --}}
    @include('partials.nav-bar')

    {{-- Mobile Menu --}}
    @include('partials.mobile-menu')

    {{-- Cart Drawer --}}
    @include('partials.cart-drawer')



    <!-- ═══════════════════════════════════════════
     HERO
═══════════════════════════════════════════ -->
    <section class="hero-section">
        <div class="hero-bg"></div>
        <div class="hero-img-overlay" id="heroBg"></div>
        <div class="hero-gradient"></div>

        <div class="hero-content" style="max-width:1440px;margin:0 auto;width:100%;padding:0 40px 80px;">
            <p class="hero-eyebrow">SS25 Collection — Now Available</p>
            <h1 class="hero-title">
                Wear<br>
                the <em>silence</em>
            </h1>
            <p class="hero-sub">Minimal luxury for those who speak through what they wear. Premium oversized essentials,
                crafted for the modern generation.</p>
            <div class="hero-cta-group">
                <a href="shop.html" class="btn-primary">
                    Shop Collection
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="#collections" class="btn-outline-light">Explore Drops</a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="hero-scroll">
            <div class="scroll-line"></div>
            <span>Scroll</span>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════
     MARQUEE TICKER
═══════════════════════════════════════════ -->
    <div style="background:#0A0A0A;overflow:hidden;padding:14px 0;border-top:1px solid #1a1a1a;">
        <div style="display:flex;width:max-content;" class="marquee-track">
            <span
                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#9C9A96;white-space:nowrap;padding-right:60px;">Free
                Free Shipping on Orders Over EGP 1,500 EGP</span>
            <span
                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#3D3D3A;padding-right:60px;">✦</span>
            <span
                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#9C9A96;white-space:nowrap;padding-right:60px;">New
                Drop: SS25 Essentials Collection</span>
            <span
                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#3D3D3A;padding-right:60px;">✦</span>
            <span
                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#9C9A96;white-space:nowrap;padding-right:60px;">Premium
                Streetwear — Made to Last</span>
            <span
                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#3D3D3A;padding-right:60px;">✦</span>
            <!-- Duplicate for seamless loop -->
            <span
                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#9C9A96;white-space:nowrap;padding-right:60px;">Free
                Free Shipping on Orders Over EGP 1,500 EGP</span>
            <span
                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#3D3D3A;padding-right:60px;">✦</span>
            <span
                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#9C9A96;white-space:nowrap;padding-right:60px;">New
                Drop: SS25 Essentials Collection</span>
            <span
                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#3D3D3A;padding-right:60px;">✦</span>
            <span
                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#9C9A96;white-space:nowrap;padding-right:60px;">Premium
                Streetwear — Made to Last</span>
            <span
                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#3D3D3A;padding-right:60px;">✦</span>
        </div>
    </div>


    <!-- ═══════════════════════════════════════════
     BRAND STATEMENT
═══════════════════════════════════════════ -->
    <section style="
    position:relative;
    padding:75px 40px;
    background:#F8F6F2;
    overflow:hidden;
">

        <!-- Background Accent -->
        <div
            style="
        position:absolute;
        top:-120px;
        right:-120px;
        width:420px;
        height:420px;
        border-radius:50%;
        background:rgba(10,10,10,0.03);
        pointer-events:none;
    ">
        </div>

        <div
            style="
        position:relative;
        max-width:1440px;
        margin:0 auto;
        display:grid;
        grid-template-columns:1.2fr 0.8fr;
        gap:120px;
        align-items:end;
    ">

            <!-- Left Content -->
            <div>

                <p class="section-eyebrow reveal"
                    style="
                    margin-bottom:24px;
                    letter-spacing:0.25em;
               ">
                    The Philosophy
                </p>

                <h2 class="section-title reveal reveal-delay-1"
                    style="
                    font-size:clamp(52px,7vw,110px);
                    line-height:0.92;
                    letter-spacing:-0.04em;
                    font-weight:300;
                    margin-bottom:42px;
                    color:#0A0A0A;
               ">

                    Clothing<br>

                    that <span
                        style="
                    font-style:italic;
                    font-weight:400;
                ">speaks</span><br>

                    without noise.

                </h2>

            </div>

            <!-- Right Content -->
            <div class="reveal reveal-delay-2">

                <div
                    style="
                width:60px;
                height:1px;
                background:#0A0A0A;
                margin-bottom:28px;
            ">
                </div>

                <p
                    style="
                font-family:'DM Sans',sans-serif;
                font-size:16px;
                font-weight:300;
                line-height:2;
                color:#3D3D3A;
                max-width:460px;
                margin-bottom:36px;
            ">

                    RYO was created for people who value presence over attention.
                    Every silhouette is intentional — refined proportions,
                    elevated fabrics, and understated details designed to feel timeless.

                </p>

                <div
                    style="
                display:flex;
                gap:48px;
                flex-wrap:wrap;
            ">

                    <div>
                        <p
                            style="
                        font-size:12px;
                        letter-spacing:0.18em;
                        text-transform:uppercase;
                        color:#8A8A86;
                        margin-bottom:10px;
                    ">
                            Focus
                        </p>

                        <p
                            style="
                        font-size:15px;
                        color:#0A0A0A;
                    ">
                            Minimal Luxury
                        </p>
                    </div>

                    <div>
                        <p
                            style="font-size:12px;letter-spacing:0.18em;text-transform:uppercase;color:#8A8A86; margin-bottom:10px;">
                            Identity
                        </p>
                        <p style="font-size:15px;color:#0A0A0A;">
                            Premium Streetwear
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════
     FEATURED COLLECTION — NEW DROP
═══════════════════════════════════════════ -->
    <section id="collections" style="padding:0 40px 120px;max-width:1440px;margin:0 auto;">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:48px;" class="reveal">
            <div>
                <h2 class="section-title" style="font-size:clamp(32px,4vw,56px);">New Arrivals</h2>
            </div>
            <a href="shop.html" class="btn-outline-dark hidden md:inline-flex">View All</a>
        </div>

        <!-- Product Grid -->
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:32px 24px;">


            @foreach ($newArrivalProducts as $product)
                @php
                    // الصورة الأساسية
                    $primaryImage = $product->primaryImage;
                    $imageUrl = $primaryImage
                        ? Storage::url($primaryImage->image_path)
                        : asset('images/placeholder.jpg');

                    // الصورة الثانية (أول صورة غير أساسية من علاقة images)
                    $secondaryImage = $product->images->filter(fn($img) => !$img->is_primary)->first();
                    $hoverImageUrl = $secondaryImage ? Storage::url($secondaryImage->image_path) : $imageUrl;
                @endphp

                <div class="product-card reveal reveal-delay-1">
                    <a href="{{ route('product', $product) }}" class="product-link">
                        <div class="product-img-wrap" style="aspect-ratio:3/4;">
                            <img src="{{ $imageUrl }}" alt="{{ $product->product_name }}">
                            <img class="hover-img" src="{{ $hoverImageUrl }}" alt="{{ $product->product_name }} hover">
                            <span class="product-badge badge-new">New</span>
                        </div>
                        <div class="product-meta">
                            <p class="product-name">{{ $product->product_name }}</p>
                            <p class="product-price">EGP {{ number_format($product->product_price, 0) }}</p>
                        </div>
                    </a>
                </div>
            @endforeach


        </div>

        <div class="md:hidden" style="margin-top:32px;text-align:center;">
            <a href="shop.html" class="btn-outline-dark">View All New Arrivals</a>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════
     EDITORIAL DOUBLE BANNER
═══════════════════════════════════════════ -->
    <section style="display:grid;grid-template-columns:1fr 1fr;max-width:1440px;margin:0 auto 0;gap:2px;"
        class="reveal">
        <!-- Left Banner -->
        <div class="editorial-banner" style="height:clamp(400px,55vw,700px);position:relative;">
            <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=900&q=80" alt="Editorial 1">
            <div
                style="position:absolute;inset:0;background:linear-gradient(to top,rgba(10,10,10,0.6) 0%,transparent 60%);">
            </div>
            <div style="position:absolute;bottom:40px;left:40px;">
                <p
                    style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#C8C6C2;margin-bottom:8px;">
                    Collection 01</p>
                <h3
                    style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,3vw,44px);font-weight:300;color:#F8F6F2;margin-bottom:20px;">
                    The Essentials</h3>
                <a href="shop.html"
                    style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.18em;text-transform:uppercase;color:#F8F6F2;text-decoration:none;border-bottom:1px solid rgba(248,246,242,0.5);padding-bottom:3px;transition:border-color 0.3s ease;">Shop
                    Now</a>
            </div>
        </div>
        <!-- Right Banner -->
        <div class="editorial-banner" style="height:clamp(400px,55vw,700px);position:relative;background:#1a1814;">
            <img src="https://images.unsplash.com/photo-1550614000-4895a10e1bfd?w=900&q=80" alt="Editorial 2"
                style="opacity:0.8;">
            <div
                style="position:absolute;inset:0;background:linear-gradient(to top,rgba(10,10,10,0.65) 0%,transparent 55%);">
            </div>
            <div style="position:absolute;bottom:40px;left:40px;">
                <p
                    style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#C8C6C2;margin-bottom:8px;">
                    Collection 02</p>
                <h3
                    style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,3vw,44px);font-weight:300;color:#F8F6F2;margin-bottom:20px;">
                    Monochrome Edit</h3>
                <a href="shop.html"
                    style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.18em;text-transform:uppercase;color:#F8F6F2;text-decoration:none;border-bottom:1px solid rgba(248,246,242,0.5);padding-bottom:3px;transition:border-color 0.3s ease;">Explore</a>
            </div>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════
     BEST SELLERS
═══════════════════════════════════════════ -->
    <section style="padding:120px 40px;max-width:1440px;margin:0 auto;">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:48px;"
            class="reveal">
            <div>
                <p class="section-eyebrow">Community Favorites</p>
                <h2 class="section-title" style="font-size:clamp(32px,4vw,56px);">Best Sellers</h2>
            </div>
            <a href="shop.html" class="btn-outline-dark hidden md:inline-flex">View All</a>
        </div>

        <!-- Horizontal scroll on mobile, grid on desktop -->
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:24px;">

            <div class="product-card reveal reveal-delay-1">
                <div class="product-img-wrap" style="aspect-ratio:3/4;">
                    <img src="https://images.unsplash.com/photo-1594938298603-f4d8c9a3a9a4?w=500&q=80"
                        alt="Best Seller 1">
                    <span class="product-badge badge-sale">Best Seller</span>
                    <button class="wishlist-btn" aria-label="Wishlist">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A"
                            stroke-width="1.5">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                        </svg>
                    </button>
                    <button class="product-quick-add">Quick Add</button>
                </div>
                <div class="product-meta">
                    <p class="product-name">Washed Crewneck Sweat</p>
                    <p class="product-price">EGP 3,400</p>
                </div>
            </div>

            <div class="product-card reveal reveal-delay-2">
                <div class="product-img-wrap" style="aspect-ratio:3/4;">
                    <img src="https://images.unsplash.com/photo-1542272604-787c3835535d?w=500&q=80"
                        alt="Best Seller 2">
                    <span class="product-badge badge-sale">Best Seller</span>
                    <button class="wishlist-btn" aria-label="Wishlist">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A"
                            stroke-width="1.5">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                        </svg>
                    </button>
                    <button class="product-quick-add">Quick Add</button>
                </div>
                <div class="product-meta">
                    <p class="product-name">Slim Tapered Chino</p>
                    <p class="product-price">EGP 2,900</p>
                </div>
            </div>

            <div class="product-card reveal reveal-delay-3">
                <div class="product-img-wrap" style="aspect-ratio:3/4;">
                    <img src="https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=500&q=80"
                        alt="Best Seller 3">
                    <span class="product-badge badge-sale">Best Seller</span>
                    <button class="wishlist-btn" aria-label="Wishlist">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A"
                            stroke-width="1.5">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                        </svg>
                    </button>
                    <button class="product-quick-add">Quick Add</button>
                </div>
                <div class="product-meta">
                    <p class="product-name">Relaxed Linen Shirt</p>
                    <p class="product-price">EGP 2,600</p>
                </div>
            </div>

            <div class="product-card reveal reveal-delay-4">
                <div class="product-img-wrap" style="aspect-ratio:3/4;">
                    <img src="https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=500&q=80"
                        alt="Best Seller 4">
                    <span class="product-badge badge-sale">Best Seller</span>
                    <button class="wishlist-btn" aria-label="Wishlist">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A"
                            stroke-width="1.5">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                        </svg>
                    </button>
                    <button class="product-quick-add">Quick Add</button>
                </div>
                <div class="product-meta">
                    <p class="product-name">Structured Bomber</p>
                    <p class="product-price">EGP 6,200</p>
                </div>
            </div>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════
     FULL-WIDTH EDITORIAL BANNER
═══════════════════════════════════════════ -->
    <section style="position:relative;overflow:hidden;height:clamp(500px,60vw,800px);max-width:1440px;margin:0 auto;"
        class="reveal">
        <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=1600&q=80" alt="Editorial Campaign"
            style="width:100%;height:100%;object-fit:cover;display:block;transition:transform 0.8s cubic-bezier(0.25,0.46,0.45,0.94);"
            id="editorialImg">
        <div style="position:absolute;inset:0;background:rgba(10,10,10,0.35);"></div>
        <div
            style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:40px;">
            <p
                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.25em;text-transform:uppercase;color:#C8C6C2;margin-bottom:20px;">
                The Drop</p>
            <h2
                style="font-family:'Cormorant Garamond',serif;font-size:clamp(48px,7vw,100px);font-weight:300;color:#F8F6F2;line-height:0.95;letter-spacing:-0.02em;margin-bottom:32px;">
                Summer<br>
                <em>Essentials</em><br>
                SS25
            </h2>
            <a href="shop.html" class="btn-primary">Shop the Drop</a>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════
     CATEGORY SHOWCASE
═══════════════════════════════════════════ -->
    <section style="padding:120px 40px;max-width:1440px;margin:0 auto;">
        <div style="margin-bottom:48px;" class="reveal">
            <p class="section-eyebrow">Shop by Category</p>
            <h2 class="section-title" style="font-size:clamp(32px,4vw,56px);">Browse</h2>
        </div>

        <div style="display:grid;grid-template-columns:2fr 1fr 1fr;grid-template-rows:auto auto;gap:4px;height:640px;"
            class="reveal reveal-delay-1">
            <!-- Large Left -->
            <div class="category-card" style="grid-row:1/3;">
                <img src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?w=800&q=80" alt="Tops & Tees">
                <div
                    style="position:absolute;inset:0;background:linear-gradient(to top,rgba(10,10,10,0.5) 0%,transparent 50%);">
                </div>
                <div class="category-card-label">
                    <p
                        style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:0.2em;text-transform:uppercase;color:#C8C6C2;margin-bottom:4px;">
                        Category 01</p>
                    <h3
                        style="font-family:'Cormorant Garamond',serif;font-size:clamp(24px,2.5vw,38px);font-weight:300;color:#F8F6F2;">
                        Tops & Tees</h3>
                </div>
            </div>
            <!-- Top Right -->
            <div class="category-card" style="grid-column:2/4;">
                <img src="https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=700&q=80" alt="Bottoms">
                <div
                    style="position:absolute;inset:0;background:linear-gradient(to top,rgba(10,10,10,0.5) 0%,transparent 60%);">
                </div>
                <div class="category-card-label">
                    <p
                        style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:0.2em;text-transform:uppercase;color:#C8C6C2;margin-bottom:4px;">
                        Category 02</p>
                    <h3
                        style="font-family:'Cormorant Garamond',serif;font-size:clamp(20px,2vw,32px);font-weight:300;color:#F8F6F2;">
                        Bottoms</h3>
                </div>
            </div>
            <!-- Bottom Middle -->
            <div class="category-card">
                <img src="https://images.unsplash.com/photo-1551537482-f2075a1d41f2?w=500&q=80" alt="Outerwear">
                <div
                    style="position:absolute;inset:0;background:linear-gradient(to top,rgba(10,10,10,0.5) 0%,transparent 60%);">
                </div>
                <div class="category-card-label">
                    <p
                        style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:0.2em;text-transform:uppercase;color:#C8C6C2;margin-bottom:4px;">
                        Category 03</p>
                    <h3
                        style="font-family:'Cormorant Garamond',serif;font-size:clamp(18px,1.8vw,28px);font-weight:300;color:#F8F6F2;">
                        Outerwear</h3>
                </div>
            </div>
            <!-- Bottom Right -->
            <div class="category-card">
                <img src="https://images.unsplash.com/photo-1537832816519-689ad163238b?w=500&q=80" alt="Accessories">
                <div
                    style="position:absolute;inset:0;background:linear-gradient(to top,rgba(10,10,10,0.5) 0%,transparent 60%);">
                </div>
                <div class="category-card-label">
                    <p
                        style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:0.2em;text-transform:uppercase;color:#C8C6C2;margin-bottom:4px;">
                        Category 04</p>
                    <h3
                        style="font-family:'Cormorant Garamond',serif;font-size:clamp(18px,1.8vw,28px);font-weight:300;color:#F8F6F2;">
                        Accessories</h3>
                </div>
            </div>
        </div>
    </section>


    {{-- Instgram Feed --}}
    @include('partials.instgram-feed')


    {{-- Newslater --}}
    @include('partials.newslater')


    {{-- Footer --}}
    @include('partials.footer')



    <script src="{{ asset('js/homepage.js') }}" defer></script>
    <script src="{{ asset('js/website-product.js') }}" defer></script>
    <script src="{{ asset('js/website-shop.js') }}"></script>
    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif
</body>

</html>
