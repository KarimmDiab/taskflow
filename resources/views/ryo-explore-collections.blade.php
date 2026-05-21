<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>RYO — Monochromatica Collection</title>

    <meta name="description"
        content="Discover the Monochromatica collection — oversized silhouettes, muted palettes and premium minimalist streetwear.">

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="{{ asset('css/website.css') }}" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=DM+Sans:wght@300;400;500&family=Space+Grotesk:wght@400;500&display=swap"
        rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/website-explore-collections.css') }}">
        <script src="{{ asset('js/tailwind.js') }}"></script>

</head>

<body class="font-body">


    {{-- NAVBAR --}}
    @include('partials.nav-bar2')

    @include('partials.mobile-menu')

    @include('partials.cart-drawer')



    {{-- HERO - ENHANCED CINEMATIC STYLE --}}
    <section class="relative bg-ryo-black overflow-hidden">

        {{-- Layered Background with Parallax Motion --}}
        <div class="absolute inset-0 opacity-30 transition-opacity duration-1000">
            <div class="absolute inset-0 bg-gradient-to-br from-black/80 via-black/40 to-transparent z-10"></div>
            <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=1800&auto=format&fit=crop"
                class="w-full h-full object-cover scale-105 animate-[kenburns_20s_ease-in-out_infinite]"
                alt="Collection backdrop" style="animation: kenburns 20s ease-in-out infinite;">
        </div>

        {{-- Subtle Grain Texture Overlay for Depth --}}
        <div class="absolute inset-0 opacity-20 pointer-events-none mix-blend-overlay"
            style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMDAiIGhlaWdodD0iMzAwIj48ZmlsdGVyIGlkPSJmIj48ZmVUdXJidWxlbmNlIHR5cGU9ImZyYWN0YWxOb2lzZSIgYmFzZUZyZXF1ZW5jeT0iLjciIG51bU9jdGF2ZXM9IjMiLz48L2ZpbHRlcj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWx0ZXI9InVybCgjZikiIG9wYWNpdHk9IjAuNCIvPjwvc3ZnPg=='); background-repeat: repeat;">
        </div>

        {{-- Animated Light Leak Effect --}}
        <div class="absolute inset-0 pointer-events-none opacity-20 animate-[pulseLight_8s_ease-in-out_infinite]"
            style="background: radial-gradient(circle at 30% 20%, rgba(255,215,150,0.15) 0%, transparent 60%);"></div>

        <div class="relative max-w-[1440px] mx-auto px-6 md:px-10 pt-28 md:pt-44 pb-24 md:pb-36">

            <div class="grid md:grid-cols-2 gap-12 lg:gap-20 items-end">

                {{-- Left Column: Collection Badge & Title --}}
                <div class="space-y-6 opacity-0 animate-[fadeInUp_0.8s_ease-out_forwards]">

                    {{-- Collection Badge with Decorative Elements --}}
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-px bg-white/40"></span>
                        <span
                            class="uppercase tracking-[0.3em] text-[10px] md:text-[11px] text-white/45 font-label font-medium">
                            SS26 Collection
                        </span>
                        <span class="w-12 h-px bg-white/20"></span>
                    </div>

                    {{-- Main Title with Elegant Line Break Support --}}
                    <div class="relative">
                        <h1
                            class="font-display text-[56px] md:text-[100px] lg:text-[130px] leading-[0.9] text-white font-light tracking-[-0.06em]">
                            {{ $collection->collection_name }}
                        </h1>

                        {{-- Subtle Decorative Underline --}}
                        <div
                            class="absolute -bottom-5 left-0 w-16 h-0.5 bg-gradient-to-r from-white/40 to-transparent md:w-24">
                        </div>
                    </div>
                </div>

                {{-- Right Column: Description with Cinematic Reveal --}}
                <div class="space-y-6 opacity-0 animate-[fadeInUp_0.8s_ease-out_0.2s_forwards]">

                    {{-- Quote/Decorative Element --}}
                    <div class="relative">
                        <div class="absolute -top-3 -left-3 text-white/10 font-display text-6xl leading-none">"</div>
                        <p
                            class="text-sm md:text-base lg:text-lg leading-[1.75] text-white/70 max-w-xl font-light pl-4 border-l-2 border-white/25">
                            {{ $collection->collection_desc }}
                        </p>
                    </div>

                    {{-- Interactive CTAs --}}
                    <div class="flex flex-wrap gap-6 pt-6 pl-4">
                        <a href="#collection-editorial"
                            class="group inline-flex items-center gap-2 px-0 py-2 text-[10px] md:text-[11px] font-label tracking-[0.25em] uppercase text-white/80 border-b border-white/30 hover:border-white hover:text-white transition-all duration-300">
                            <span>Discover Narrative</span>
                            <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>

                        <a href="shop.html?collection={{ urlencode($collection->collection_name) }}"
                            class="group inline-flex items-center gap-2 px-4 py-2 bg-white/5 backdrop-blur-sm border border-white/20 rounded-full text-[10px] font-label tracking-[0.2em] uppercase text-white/70 hover:bg-white/20 hover:border-white/40 transition-all duration-300">
                            Shop Collection
                            <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>

                    {{-- Collection Meta Indicator --}}
                    <div class="flex items-center gap-4 pl-4 pt-4">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-white/40 rounded-full"></span>
                            <span class="text-[9px] font-label tracking-[0.15em] uppercase text-white/40">Limited
                                Edition</span>
                        </div>
                        <div class="w-px h-3 bg-white/20"></div>
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-white/40 rounded-full"></span>
                            <span class="text-[9px] font-label tracking-[0.15em] uppercase text-white/40">Global
                                Release</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Elegant Bottom Fade Line with Gradient --}}
        <div
            class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/30 to-transparent">
        </div>
    </section>


    {{-- COLLECTION EDITORIAL - ENHANCED CINEMATIC STYLE --}}
    <section class="px-6 md:px-10 py-24 md:py-32 bg-gradient-to-b from-ryo-white via-white to-ryo-gray-100/30">
        <div class="max-w-[1440px] mx-auto">

            <div class="grid md:grid-cols-2 gap-12 lg:gap-20 items-center">

                {{-- Left Side: Image with Artistic Overlay & Floating Elements --}}
                <div class="relative group opacity-0 animate-[fadeInScale_0.9s_ease-out_0.1s_forwards]">

                    {{-- Main Image Container with Refined Frame --}}
                    <div class="relative overflow-hidden rounded-2xl shadow-2xl">
                        <!-- Decorative Frame Lines -->
                        <div class="absolute inset-0 pointer-events-none z-10">
                            <div class="absolute top-4 left-4 w-12 h-12 border-t-2 border-l-2 border-white/30"></div>
                            <div class="absolute top-4 right-4 w-12 h-12 border-t-2 border-r-2 border-white/30"></div>
                            <div class="absolute bottom-4 left-4 w-12 h-12 border-b-2 border-l-2 border-white/30"></div>
                            <div class="absolute bottom-4 right-4 w-12 h-12 border-b-2 border-r-2 border-white/30">
                            </div>
                        </div>

                        <img src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?q=80&w=1400&auto=format&fit=crop"
                            class="w-full h-[500px] md:h-[620px] object-cover transition-all duration-[1.2s] group-hover:scale-105"
                            alt="Collection Editorial">

                        {{-- Gradient Overlay for Depth --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-black/10"></div>

                        {{-- Floating Badge Overlay --}}
                        <div
                            class="absolute bottom-6 left-6 z-10 backdrop-blur-md bg-black/30 rounded-full px-4 py-1.5 border border-white/20">
                            <span class="text-[9px] font-label tracking-[0.2em] uppercase text-white">EDITORIAL
                                FEATURE</span>
                        </div>
                    </div>

                    {{-- Decorative Accent Element --}}
                    <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-ryo-black/5 rounded-full blur-2xl -z-10"></div>
                    <div class="absolute -top-6 -left-6 w-40 h-40 bg-ryo-gray-200/30 rounded-full blur-2xl -z-10"></div>
                </div>

                {{-- Right Side: Editorial Content with Staggered Reveal --}}
                <div class="space-y-6 opacity-0 animate-[fadeInRight_0.8s_ease-out_0.2s_forwards]">

                    {{-- Pre-title with Decorative Line --}}
                    <div class="flex items-center gap-4">
                        <span class="w-8 h-px bg-ryo-black/30"></span>
                        <p
                            class="uppercase tracking-[0.28em] text-[10px] md:text-[11px] text-ryo-gray-500 font-label font-medium">
                            Collection Narrative
                        </p>
                        <span class="w-12 h-px bg-ryo-black/20"></span>
                    </div>

                    {{-- Collection Title with Elegant Styling --}}
                    <div class="relative">
                        <h2
                            class="font-display text-[44px] md:text-[72px] lg:text-[88px] leading-[1.05] tracking-[-0.04em] font-light text-ryo-black">
                            {{ $collection->collection_name }}
                        </h2>

                        {{-- Subtle Underline Accent --}}
                        <div
                            class="absolute -bottom-4 left-0 w-20 h-0.5 bg-gradient-to-r from-ryo-black/40 to-transparent">
                        </div>
                    </div>

                    {{-- Collection Description with Refined Typography --}}
                    <div class="mt-8 space-y-5">
                        <p
                            class="text-sm md:text-base leading-[1.85] text-ryo-gray-700 max-w-lg font-light border-l-2 border-ryo-black/20 pl-6">
                            {{ $collection->collection_story }}
                        </p>

                        {{-- Optional: Decorative Quote or Detail --}}
                        <div class="flex items-center gap-3 text-ryo-gray-400 text-xs font-label tracking-wide pl-6">
                            <span class="w-6 h-px bg-ryo-gray-300"></span>
                            <span>Capsule Collection · Limited Release</span>
                        </div>
                    </div>

                    {{-- Interactive CTA with Hover Effect --}}
                    <div class="flex flex-wrap gap-6 pt-6">
                        <a href="#"
                            class="group inline-flex items-center gap-2 px-0 py-2 text-ryo-black text-[11px] font-label tracking-[0.2em] uppercase border-b border-ryo-black/20 hover:border-ryo-black transition-all duration-300">
                            <span>Discover the full story</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 group-hover:-translate-y-0.5 transition-all duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>

                        <a href="shop.html?collection={{ $collection->collection_name }}"
                            class="inline-flex items-center gap-2 text-ryo-gray-500 text-[11px] font-label tracking-[0.2em] uppercase hover:text-ryo-black transition-colors duration-300">
                            Shop Collection
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>

                    {{-- Collection Meta Info (Optional) --}}
                    <div class="flex flex-wrap gap-8 pt-8 border-t border-ryo-gray-200/50">
                        <div class="flex flex-col">
                            <span
                                class="text-[9px] font-label tracking-[0.2em] uppercase text-ryo-gray-400">Release</span>
                            <span class="text-sm font-body text-ryo-black mt-1">Spring Summer 2025</span>
                        </div>
                        <div class="flex flex-col">
                            <span
                                class="text-[9px] font-label tracking-[0.2em] uppercase text-ryo-gray-400">Edition</span>
                            <span class="text-sm font-body text-ryo-black mt-1">Limited Run</span>
                        </div>
                        <div class="flex flex-col">
                            <span
                                class="text-[9px] font-label tracking-[0.2em] uppercase text-ryo-gray-400">Designer</span>
                            <span class="text-sm font-body text-ryo-black mt-1">RYO Atelier</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




    {{-- PRODUCTS - ENHANCED WITH QUICK ADD TO CART --}}
    <section class="px-6 md:px-10 pb-24 md:pb-32 bg-gradient-to-b from-white to-ryo-gray-100/20">
        <div class="max-w-[1440px] mx-auto">

            {{-- Section Header with Refined Typography --}}
            <div class="flex items-end justify-between gap-8 mb-14 flex-wrap border-b border-ryo-gray-200/50 pb-8">
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-px bg-ryo-black/30"></span>
                        <p
                            class="uppercase tracking-[0.28em] text-[10px] md:text-[11px] text-ryo-gray-500 font-label font-medium">
                            Curated Selection
                        </p>
                    </div>
                    <h2
                        class="font-display text-[42px] md:text-[68px] lg:text-[80px] leading-[0.95] font-light tracking-[-0.04em] text-ryo-black">
                        Featured Pieces
                    </h2>
                </div>

                <div class="flex items-center gap-4">
                    <div class="w-px h-8 bg-ryo-gray-300 hidden md:block"></div>
                    <div
                        class="uppercase tracking-[0.2em] text-[10px] md:text-[11px] text-ryo-gray-500 font-label bg-ryo-gray-100/50 px-4 py-2 rounded-full">
                        {{ $countCollectionProducts }} {{ $countCollectionProducts == 1 ? 'Product' : 'Products' }}
                    </div>
                </div>
            </div>

            {{-- Product Grid with Enhanced Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">

                @foreach ($collectionProducts as $product)
                    @php
                        $primaryImage = $product->images
                            ->sortBy([['is_primary', 'desc'], ['sort_order', 'asc']])
                            ->first();
                        $hoverImage = $product->images
                            ->where('id', '!=', $primaryImage?->id)
                            ->sortBy('sort_order')
                            ->first();
                        $formatImageUrl = function ($path) {
                            if (!$path) {
                                return null;
                            }

                            return \Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])
                                ? $path
                                : asset('storage/' . ltrim($path, '/'));
                        };
                        $productImage =
                            $formatImageUrl($primaryImage?->image_path) ??
                            'https://images.unsplash.com/photo-1523398002811-999ca8dec234?q=80&w=900&auto=format&fit=crop';
                        $productHoverImage = $formatImageUrl($hoverImage?->image_path);
                        $firstAvailableVariant =
                            $product->productVariants->first(
                                fn($variant) => $variant->inventories->sum('quantity') > 0,
                            ) ?? $product->productVariants->first();
                        $price = $firstAvailableVariant?->variant_price ?? ($product->product_price ?? 0);
                        $stock = $product->productVariants->sum(fn($variant) => $variant->inventories->sum('quantity'));
                    @endphp
                    {{-- PRODUCT CARD --}}
                    <div class="group relative opacity-0 animate-[fadeInUp_0.6s_ease-out_forwards]"
                        style="animation-delay: {{ $loop->index * 0.05 }}s">

                        {{-- IMAGE --}}
                        <div class="relative overflow-hidden bg-[#ECE8E1] rounded-[28px] transition duration-700">

                            {{-- PRODUCT LINK --}}
                            <a href="{{ route('product', $product->slug) }}">

                                <div class="relative overflow-hidden aspect-[3/4]">

                                    {{-- MAIN IMAGE --}}
                                    <img src="{{ $productImage }}"
                                        class="w-full h-full object-cover transition duration-[1400ms] ease-out group-hover:scale-[1.03]"
                                        alt="{{ $product->product_name }}">


                                    {{-- HOVER IMAGE --}}
                                    @if ($productHoverImage)
                                        <img src="{{ $productHoverImage }}"
                                            class="absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-100 transition-opacity duration-700"
                                            alt="{{ $product->product_name }}">
                                    @endif


                                    {{-- SOFT OVERLAY --}}
                                    <div
                                        class="absolute inset-0 bg-black/0 group-hover:bg-black/[0.03] transition duration-700">
                                    </div>

                                </div>

                            </a>



                            {{-- STOCK STATUS --}}
                            @if ($stock <= 0)
                                <span
                                    class="absolute top-5 left-5 bg-black text-white text-[9px] font-label tracking-[0.22em] uppercase px-3 py-1.5 rounded-full">

                                    Sold Out

                                </span>
                            @elseif($stock < 3)
                                <span
                                    class="absolute top-5 left-5 bg-[#D9C7A2] text-black text-[9px] font-label tracking-[0.22em] uppercase px-3 py-1.5 rounded-full">

                                    Low Stock

                                </span>
                            @else
                                <span
                                    class="absolute top-5 left-5 bg-white/80 backdrop-blur-md text-black text-[9px] font-label tracking-[0.22em] uppercase px-3 py-1.5 rounded-full border border-white/40">

                                    In Stock

                                </span>
                            @endif





                        </div>



                        {{-- CONTENT --}}
                        <div class="pt-6 px-1">
                            <div class="flex items-start justify-between gap-5">
                                <div class="flex-1">
                                    <a href="{{ route('product', $product->slug) }}">
                                        <h3
                                            class="font-display text-[24px] md:text-[28px]
                               leading-[1.05]
                               tracking-[-0.03em]
                               text-ryo-black
                               transition duration-300 group-hover:text-ryo-gray-700">
                                            {{ $product->product_name }}
                                        </h3>
                                    </a>
                                    <p
                                        class="mt-2 text-[10px]
                           uppercase tracking-[0.18em]
                           text-ryo-gray-400 font-label">
                                        {{ $product->material ?? 'Heavyweight Cotton' }}
                                    </p>
                                </div>

                                <div class="text-right">
                                    <p
                                        class="text-[15px] md:text-base
                           text-ryo-black font-medium">
                                        {{ number_format($price, 0) }}
                                        <span class="text-[10px] text-ryo-gray-400">
                                            EGP
                                        </span>
                                    </p>
                                </div>
                            </div>



                            {{-- LINK --}}
                            <a href="{{ route('product', $product->slug) }}"
                                class="inline-flex items-center gap-2 mt-5
                                uppercase tracking-[0.2em]
                                text-[10px] font-label
                                text-ryo-gray-500
                                transition duration-300 hover:text-ryo-black">
                                View Product
                                <svg class="w-3.5 h-3.5 transition duration-300 hover:translate-x-1"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="flex justify-center mt-16">
                <a href="{{ route('all-products') }}"
                    class="group inline-flex items-center gap-3 px-8 py-4 border border-ryo-black/20 rounded-full
                      hover:bg-ryo-black hover:text-white hover:border-ryo-black
                      transition-all duration-400 text-[11px] font-label tracking-[0.2em] uppercase">
                    <span>View All Products</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>





    {{-- QUOTE --}}
    <section class="bg-ryo-black text-white py-24 md:py-32 relative overflow-hidden">

        <div class="relative max-w-5xl mx-auto px-6 text-center">

            <p class="uppercase tracking-[0.25em] text-[11px] text-white/50 mb-8 font-label">
                RYO Manifesto
            </p>

            <blockquote class="font-display text-[36px] md:text-[72px] leading-[1.1] tracking-[-0.04em] font-light">

                Oversized silhouettes crafted with timeless restraint.

            </blockquote>

        </div>

    </section>




    {{-- MORE COLLECTIONS --}}
    <section class="px-6 md:px-10 py-24 md:py-32">

        <div class="max-w-[1440px] mx-auto">

            <div class="mb-16">

                <p class="uppercase tracking-[0.22em] text-[11px] text-ryo-gray-400 mb-4 font-label">
                    Continue Exploring
                </p>

                <h2 class="font-display text-[42px] md:text-[72px] leading-none tracking-[-0.05em] font-light">

                    More Collections

                </h2>

            </div>



            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">




                <a href="#" class="group relative overflow-hidden bg-black">

                    <img src="https://images.unsplash.com/photo-1496747611176-843222e1e57c?q=80&w=1200&auto=format&fit=crop"
                        class="w-full h-[520px] object-cover transition duration-1000 group-hover:scale-105">

                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent">
                    </div>

                    <div class="absolute bottom-8 left-8 text-white">

                        <p class="uppercase tracking-[0.18em] text-[11px] text-white/60 font-label mb-3">
                            Collection
                        </p>

                        <h3 class="font-display text-[42px] leading-none tracking-[-0.04em] font-light">

                            After Hours

                        </h3>

                    </div>

                </a>





                <a href="#" class="group relative overflow-hidden bg-black">

                    <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=1200&auto=format&fit=crop"
                        class="w-full h-[520px] object-cover transition duration-1000 group-hover:scale-105">

                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent">
                    </div>

                    <div class="absolute bottom-8 left-8 text-white">

                        <p class="uppercase tracking-[0.18em] text-[11px] text-white/60 font-label mb-3">
                            Collection
                        </p>

                        <h3 class="font-display text-[42px] leading-none tracking-[-0.04em] font-light">

                            Neutral Series

                        </h3>

                    </div>

                </a>





                <a href="#" class="group relative overflow-hidden bg-black">

                    <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=1200&auto=format&fit=crop"
                        class="w-full h-[520px] object-cover transition duration-1000 group-hover:scale-105">

                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent">
                    </div>

                    <div class="absolute bottom-8 left-8 text-white">

                        <p class="uppercase tracking-[0.18em] text-[11px] text-white/60 font-label mb-3">
                            Collection
                        </p>

                        <h3 class="font-display text-[42px] leading-none tracking-[-0.04em] font-light">

                            Earth Protocol

                        </h3>

                    </div>

                </a>



            </div>

        </div>

    </section>



    {{-- FOOTER --}}
    @include('partials.footer')


    <script src="{{ asset('js/website-explore-collections.js') }}"></script>
    <script src="{{ asset('js/website-shop.js') }}"></script>

</body>

</html>
