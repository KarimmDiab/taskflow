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
     HERO - Full Screen Image with Minimal Overlay
    ═══════════════════════════════════════════ -->
    <section class="relative h-screen max-h-[900px] min-h-[600px] flex items-center justify-center overflow-hidden">
        <!-- Background Image (replace with dynamic if needed) -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1606902965551-dce093cda6e7?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80"
                alt="Hero Background" class="w-full h-full object-cover object-center">
        </div>
        <!-- Subtle overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-black/10 to-black/40 z-10"></div>

        <!-- Content -->
        <div class="relative z-20 text-center px-6 max-w-4xl mx-auto">
            <p class="text-white/80 text-xs tracking-[0.25em] uppercase mb-6 font-medium">SS25 Collection — Now
                Available</p>
            <h1 class="font-display text-5xl md:text-7xl lg:text-8xl font-light text-white leading-tight mb-8">
                Wear the <em class="italic font-medium">silence</em>
            </h1>
            <p class="text-white/80 text-lg md:text-xl font-light max-w-xl mx-auto mb-10">
                Minimal luxury for those who speak through what they wear.
                Premium oversized essentials, crafted for the modern generation.
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="shop.html"
                    class="inline-flex items-center gap-2 bg-white text-gray-900 px-8 py-3 text-sm font-medium tracking-wide uppercase hover:bg-gray-100 transition-colors duration-300">
                    Shop Collection
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="#collections"
                    class="inline-flex items-center gap-2 border border-white/40 text-white px-8 py-3 text-sm font-medium tracking-wide uppercase hover:bg-white/10 transition-colors duration-300">
                    Explore Drops
                </a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div
            class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 flex flex-col items-center gap-2 text-white/60">
            <span class="text-xs tracking-widest uppercase">Scroll</span>
            <div class="w-px h-8 bg-white/40 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full bg-white animate-pulse"></div>
            </div>
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
     BRAND STATEMENT - Refined Typography Layout
    ═══════════════════════════════════════════ -->
    <section class="relative py-20 lg:py-32 px-6 max-w-7xl mx-auto overflow-hidden">
        <div class="grid lg:grid-cols-5 gap-16 items-center">
            <div class="lg:col-span-3 reveal">
                <p class="text-xs tracking-[0.25em] uppercase text-gray-400 mb-6">The Philosophy</p>
                <h2 class="font-display text-4xl md:text-6xl lg:text-7xl font-light text-gray-900 leading-[1.1] mb-10">
                    Clothing that <em class="italic font-medium">speaks</em><br> without noise.
                </h2>
            </div>
            <div class="lg:col-span-2 reveal">
                <div class="w-12 h-px bg-gray-300 mb-6"></div>
                <p class="text-gray-600 text-lg font-light leading-relaxed mb-8 max-w-md">
                    RYO was created for people who value presence over attention.
                    Every silhouette is intentional — refined proportions,
                    elevated fabrics, and understated details designed to feel timeless.
                </p>
                <div class="flex gap-12">
                    <div>
                        <p class="text-xs tracking-widest uppercase text-gray-400 mb-2">Focus</p>
                        <p class="text-gray-900 font-medium">Minimal Luxury</p>
                    </div>
                    <div>
                        <p class="text-xs tracking-widest uppercase text-gray-400 mb-2">Identity</p>
                        <p class="text-gray-900 font-medium">Premium Streetwear</p>
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
            <a href="{{ route('all-products') }}" class="btn-outline-dark hidden md:inline-flex">View All</a>
        </div>

        <!-- Product Grid -->
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:32px 24px;">


            @foreach ($newArrivalProducts as $variant)
                @php
                    // الصورة الأساسية
                    $product = $variant->product;
                    $variantImage =
                        $product?->images?->firstWhere('color_id', $variant->color_id) ??
                        ($product?->primaryImage ?? $product?->images?->first());
                    $imageUrl = $variantImage?->image_path
                        ? Storage::url($variantImage->image_path)
                        : asset('images/placeholder.jpg');

                    // الصورة الثانية (أول صورة غير أساسية من علاقة images)
                    $hoverImage = $product?->images?->where('id', '!=', $variantImage?->id)->first();
                    $hoverImageUrl = $hoverImage?->image_path ? Storage::url($hoverImage->image_path) : $imageUrl;
                    $variantLabel = collect([$variant->color?->color_name])
                        ->filter()
                        ->implode(' / ');
                @endphp

                <div class="product-card reveal reveal-delay-1">
                    <a href="{{ $product ? route('product', $product->slug) : '#' }}" class="product-link">
                        <div class="product-img-wrap" style="aspect-ratio:3/4;">
                            <img src="{{ $imageUrl }}" alt="{{ $product?->product_name }} {{ $variantLabel }}">
                            <img class="hover-img" src="{{ $hoverImageUrl }}"
                                alt="{{ $product?->product_name }} hover">
                            <span class="product-badge badge-new">New</span>
                        </div>
                        <div class="product-meta">
                            <p class="product-name">{{ $product?->product_name }}</p>
                            <p class="product-color">{{ $variantLabel ?: 'Default variant' }}</p>
                            <p class="product-price">EGP
                                {{ number_format($variant->variant_price ?? ($product?->product_price ?? 0), 0) }}</p>
                        </div>
                    </a>
                </div>
            @endforeach


        </div>

        <div class="md:hidden" style="margin-top:32px;text-align:center;">
            <a href="{{ route('all-products') }}" class="btn-outline-dark">View All New Arrivals</a>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════
     EDITORIAL DOUBLE BANNER
═══════════════════════════════════════════ -->
    <section style="display:grid;grid-template-columns:1fr 1fr;max-width:1440px;margin:0 auto 0;gap:2px;"
        class="reveal">

        @foreach ($collections as $collection)
            <div class="editorial-banner" style="height:clamp(400px,55vw,700px);position:relative;">
                <img src="{{ Storage::url($collection->main_image) }}" alt="Editorial 1">
                <div
                    style="position:absolute;inset:0;background:linear-gradient(to top,rgba(10,10,10,0.6) 0%,transparent 60%);">
                </div>
                <div style="position:absolute;bottom:40px;left:40px;">
                    <p
                        style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#C8C6C2;margin-bottom:8px;">
                        Collection 01</p>
                    <h3
                        style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,3vw,44px);font-weight:300;color:#F8F6F2;margin-bottom:20px;">
                        {{ $collection->collection_name }}</h3>
                    <a href="{{ route('collection.show', $collection->slug) }}"
                        style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.18em;text-transform:uppercase;color:#F8F6F2;text-decoration:none;border-bottom:1px solid rgba(248,246,242,0.5);padding-bottom:3px;transition:border-color 0.3s ease;">
                        Shop
                        Now</a>
                </div>
            </div>
        @endforeach


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
            <a href="{{ route('all-products') }}" class="btn-outline-dark hidden md:inline-flex">View All</a>
        </div>



        <!-- Horizontal scroll on mobile, grid on desktop -->
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:24px;">
            @foreach ($bestSeller as $variant)
                @php
                    // الصورة الأساسية
                    $product = $variant->product;
                    $variantImage =
                        $product?->images?->firstWhere('color_id', $variant->color_id) ??
                        ($product?->primaryImage ?? $product?->images?->first());
                    $imageUrl = $variantImage?->image_path
                        ? Storage::url($variantImage->image_path)
                        : asset('images/placeholder.jpg');

                    // الصورة الثانية (أول صورة غير أساسية من علاقة images)
                    $hoverImage = $product?->images?->where('id', '!=', $variantImage?->id)->first();
                    $hoverImageUrl = $hoverImage?->image_path ? Storage::url($hoverImage->image_path) : $imageUrl;
                    $variantLabel = collect([$variant->color?->color_name])
                        ->filter()
                        ->implode(' / ');
                @endphp
                <div class="product-card reveal reveal-delay-1">
                    <a href="{{ $product ? route('product', $product->slug) : '#' }}" class="product-link">
                        <div class="product-img-wrap" style="aspect-ratio:3/4;">
                            <img src="{{ $imageUrl }}" alt="{{ $product?->product_name }} {{ $variantLabel }}">
                            <img class="hover-img" src="{{ $hoverImageUrl }}"
                                alt="{{ $product?->product_name }} hover">
                            <span class="product-badge badge-sale">Best Seller</span>
                            <button class="wishlist-btn" aria-label="Wishlist" type="button">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    stroke="#0A0A0A" stroke-width="1.5">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                                </svg>
                            </button>
                        </div>
                        <div class="product-meta">
                            <p class="product-name">{{ $product?->product_name }}</p>
                            <p class="product-color">{{ $variantLabel ?: 'Default variant' }}</p>
                            <p class="product-price">EGP
                                {{ number_format($variant->variant_price ?? ($product?->product_price ?? 0), 0) }}</p>
                        </div>
                    </a>
                </div>
            @endforeach

        </div>
    </section>


    <!-- ═══════════════════════════════════════════
     FULL-WIDTH EDITORIAL BANNER
═══════════════════════════════════════════ -->
    <section style="position:relative;overflow:hidden;height:clamp(500px,60vw,800px);max-width:1440px;margin:0 auto;"
        class="reveal">

        @foreach ($featuredCollections as $featuredCollection)
        @endforeach
        @php
            $featuredCollectionUrl = isset($featuredCollection)
                ? route('collection.show', $featuredCollection->slug)
                : route('collections');
        @endphp
        <a href="{{ $featuredCollectionUrl }}" aria-label="Open featured collection"
            style="position:absolute;inset:0;z-index:1;"></a>
        <img src="{{ $featuredCollection->main_image && Storage::disk('public')->exists($featuredCollection->main_image) ? Storage::url($featuredCollection->main_image) : 'https://placehold.co/800x600' }}"
            alt="Editorial Campaign" id="editorialImg">
        <div style="position:absolute;inset:0;background:rgba(10,10,10,0.35);"></div>
        <div
            style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:40px;z-index:2;pointer-events:none;">
            <p
                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:0.25em;text-transform:uppercase;color:#C8C6C2;margin-bottom:20px;">
                The Drop</p>
            @php
                $nameParts = explode(' ', $featuredCollection->collection_name, 2);
                $firstPart = $nameParts[0] ?? '';
                $secondPart = $nameParts[1] ?? '';
            @endphp

            <h2
                style="font-family:'Cormorant Garamond',serif;font-size:clamp(48px,7vw,100px);font-weight:300;color:#F8F6F2;line-height:0.95;letter-spacing:-0.02em;margin-bottom:32px;">
                {{ $firstPart }}<br>
                <em>{{ $secondPart ?: 'Essentials' }}</em><br>
                {{-- يمكنك إزالة السطر الثالث أو استبداله بشيء آخر --}}
            </h2>
            <a href="{{ $featuredCollectionUrl }}" class="btn-primary" style="pointer-events:auto;">Shop the
                Drop</a>
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

        @php
            // Ensure we have exactly 4 categories (if fewer, you can handle placeholders)
            $categoryList = $categories->take(4);
        @endphp

        <div style="display:grid;grid-template-columns:2fr 1fr 1fr;grid-template-rows:auto auto;gap:4px;height:640px;"
            class="reveal reveal-delay-1">

            @foreach ($categoryList as $index => $category)
                @php
                    // Define grid classes based on position
                    $gridClass = '';
                    $labelNumber = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                    $sizeClass = '';

                    if ($index === 0) {
                        // Large left item - spans both rows
                        $gridClass = 'grid-row:1/3;';
                        $imgSize = 'w=800&q=80';
                    } elseif ($index === 1) {
                        // Top right item - spans columns 2 to 4 (i.e., second and third column)
                        $gridClass = 'grid-column:2/4;';
                        $imgSize = 'w=700&q=80';
                    } else {
                        // Bottom middle (index 2) and bottom right (index 3)
                        $gridClass = '';
                        $imgSize = 'w=500&q=80';
                    }
                @endphp

                <a href="{{ route('all-products', ['category' => $category->category_name]) }}" class="category-card"
                    style="display:block;position:relative;overflow:hidden;text-decoration:none;{{ $gridClass }}">
                    <img src="{{ $category->image_path ? Storage::url($category->image_path) : asset('images/default-category.jpg') }}"
                        alt="{{ $category->name ?? 'Category image' }}"
                        style="width:100%; height:100%; object-fit:cover;">
                    <div
                        style="position:absolute;inset:0;background:linear-gradient(to top,rgba(10,10,10,0.5) 0%,transparent 50%);">
                    </div>
                    <div class="category-card-label" style="position:absolute;bottom:24px;left:24px;right:24px;">
                        <p
                            style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:0.2em;text-transform:uppercase;color:#C8C6C2;margin-bottom:4px;">
                            Category {{ $labelNumber }}
                        </p>
                        <h3
                            style="font-family:'Cormorant Garamond',serif;font-size:clamp(18px,2vw,28px);font-weight:300;color:#F8F6F2;">
                            {{ $category->category_name }}
                        </h3>
                    </div>
                </a>
            @endforeach

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
