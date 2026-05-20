<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>RYO — Collections</title>

    <meta name="description"
        content="Discover curated RYO collections — premium oversized silhouettes, timeless essentials and seasonal edits.">
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="{{ asset('css/website.css') }}" rel="stylesheet">
    <link href="{{ asset('css/website-shop.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon/favicon.png') }}">

    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=DM+Sans:wght@300;400;500&family=Space+Grotesk:wght@400;500&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {

                    colors: {

                        'ryo-black': '#0A0A0A',
                        'ryo-white': '#F8F6F2',
                        'ryo-gray-100': '#ECE8E1',
                        'ryo-gray-200': '#D9D4CD',
                        'ryo-gray-400': '#8C8882',
                        'ryo-gray-700': '#3D3A36',

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
        html {
            scroll-behavior: smooth;
        }

        body {
            background: #F8F6F2;
            color: #0A0A0A;
            overflow-x: hidden;
        }

        .editorial-card {
            position: relative;
            overflow: hidden;
            background: #000;
        }

        .editorial-card img {

            width: 100%;
            height: 100%;
            object-fit: cover;

            transition:
                transform 1.2s cubic-bezier(.19, 1, .22, 1),
                opacity .7s ease;
        }

        .editorial-card:hover img {
            transform: scale(1.05);
            opacity: .92;
        }

        .editorial-overlay {

            position: absolute;
            inset: 0;

            background:
                linear-gradient(to top,
                    rgba(0, 0, 0, .78) 0%,
                    rgba(0, 0, 0, .28) 45%,
                    rgba(0, 0, 0, .05) 100%);

            transition: all .5s ease;
        }

        .editorial-card:hover .editorial-overlay {

            background:
                linear-gradient(to top,
                    rgba(0, 0, 0, .85) 0%,
                    rgba(0, 0, 0, .35) 50%,
                    rgba(0, 0, 0, .08) 100%);
        }

        .collection-title {
            transition: transform .45s ease;
        }

        .editorial-card:hover .collection-title {
            transform: translateY(-4px);
        }

        .editorial-link {

            opacity: 0;

            transform: translateY(12px);

            transition: all .4s ease;
        }

        .editorial-card:hover .editorial-link {

            opacity: 1;

            transform: translateY(0);
        }

        .fade-line {
            background:
                linear-gradient(to right,
                    transparent,
                    rgba(255, 255, 255, .12),
                    transparent);
        }
    </style>

    {{-- Add necessary animations to your stylesheet or inline --}}
    <style>
        @keyframes kenburns {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.08);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.96);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-\[fadeInUp_0\.8s_ease-out_forwards\] {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .animate-\[slideUp_0\.6s_ease-out_0\.1s_both\] {
            animation: slideUp 0.6s ease-out 0.1s both;
        }

        .animate-\[slideUp_0\.6s_ease-out_0\.2s_both\] {
            animation: slideUp 0.6s ease-out 0.2s both;
        }

        .animate-\[fadeIn_0\.8s_ease-out_0\.4s_both\] {
            animation: fadeIn 0.8s ease-out 0.4s both;
        }

        .animate-\[fadeInUp_0\.8s_ease-out_0\.5s_both\] {
            animation: fadeInUp 0.8s ease-out 0.5s both;
        }

        .animate-\[fadeInScale_0\.9s_ease-out_0\.3s_forwards\] {
            animation: fadeInScale 0.9s ease-out 0.3s forwards;
        }

        .animate-\[kenburns_25s_ease-in-out_infinite\] {
            animation: kenburns 25s ease-in-out infinite;
        }
    </style>


</head>

<body class="font-body">

    {{-- NAVBAR --}}
    @include('partials.nav-bar2')

    {{-- Mobile Menu --}}
    @include('partials.mobile-menu')

    {{-- Cart Drawer --}}
    @include('partials.cart-drawer')



    {{-- HERO - ENHANCED EDITORIAL STYLE --}}
    <section class="relative bg-ryo-black overflow-hidden">

        {{-- Layered Background with Parallax Effect --}}
        <div class="absolute inset-0 opacity-20 transition-opacity duration-1000">
            <div class="absolute inset-0 bg-gradient-to-br from-black/80 via-black/40 to-transparent z-10"></div>
            <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?q=80&w=1800&auto=format&fit=crop"
                class="w-full h-full object-cover scale-105 animate-[kenburns_25s_ease-in-out_infinite]"
                alt="RYO background texture" style="animation: kenburns 25s ease-in-out infinite;">
        </div>

        {{-- Subtle Grain Texture Overlay --}}
        <div class="absolute inset-0 opacity-30 pointer-events-none mix-blend-overlay"
            style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMDAiIGhlaWdodD0iMzAwIj48ZmlsdGVyIGlkPSJmIj48ZmVUdXJidWxlbmNlIHR5cGU9ImZyYWN0YWxOb2lzZSIgYmFzZUZyZXF1ZW5jeT0iLjciIG51bU9jdGF2ZXM9IjMiLz48L2ZpbHRlcj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWx0ZXI9InVybCgjZikiIG9wYWNpdHk9IjAuNCIvPjwvc3ZnPg=='); background-repeat: repeat;">
        </div>

        <div class="relative max-w-[1440px] mx-auto px-6 md:px-10 pt-28 md:pt-40 pb-24 md:pb-32">

            <div class="grid md:grid-cols-2 gap-16 items-center">

                {{-- Left Content with Staggered Animations --}}
                <div class="space-y-8 opacity-0 animate-[fadeInUp_0.8s_ease-out_forwards]">

                    <div class="overflow-hidden">
                        <p
                            class="uppercase tracking-[0.3em] text-[11px] text-white/50 mb-7 font-label animate-[slideUp_0.6s_ease-out_0.1s_both] inline-block relative">
                            Curated Editions
                            <span class="absolute -bottom-2 left-0 w-8 h-px bg-white/40"></span>
                        </p>
                    </div>

                    <h1
                        class="font-display text-[64px] md:text-[120px] leading-[0.85] text-white font-light tracking-[-0.06em] animate-[slideUp_0.6s_ease-out_0.2s_both]">
                        Collections
                        <span class="block text-4xl md:text-7xl text-white/30 tracking-[-0.04em] mt-2">RYO
                            EDITION</span>
                    </h1>

                    <p
                        class="max-w-xl text-sm md:text-base leading-8 text-white/60 font-light animate-[fadeIn_0.8s_ease-out_0.4s_both] border-l-2 border-white/20 pl-6">
                        Oversized silhouettes, muted palettes and timeless essentials crafted for modern streetwear
                        culture.
                    </p>

                    {{-- CTA Buttons --}}
                    <div class="flex flex-wrap gap-5 pt-4 animate-[fadeInUp_0.8s_ease-out_0.5s_both]">
                        <a href="#featured-collections"
                            class="group inline-flex items-center gap-2 px-6 py-3 bg-white text-ryo-black text-[11px] font-label tracking-[0.2em] uppercase hover:bg-white/90 transition-all duration-300 hover:gap-4">
                            Explore Collections
                            <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                        <a href="{{ route('all-products') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 border border-white/30 text-white text-[11px] font-label tracking-[0.2em] uppercase hover:bg-white/10 transition-all duration-300">
                            Shop All
                        </a>
                    </div>
                </div>

                {{-- Right Side: Dynamic Image with Overlay and Floating Elements --}}
                <div
                    class="relative h-[420px] md:h-[620px] overflow-hidden rounded-2xl shadow-2xl opacity-0 animate-[fadeInScale_0.9s_ease-out_0.3s_forwards] group">

                    {{-- Main Image with Hover Zoom --}}
                    <div class="absolute inset-0 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?q=80&w=1600&auto=format&fit=crop"
                            class="w-full h-full object-cover transition-transform duration-[1.2s] group-hover:scale-105"
                            alt="RYO Editorial">
                    </div>

                    {{-- Layered Gradient Overlay for Depth --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-black/20 via-transparent to-black/10"></div>

                    {{-- Decorative Diagonal Lines --}}
                    <div class="absolute top-0 right-0 w-32 h-32 border-t border-r border-white/10"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 border-b border-l border-white/10"></div>

                    {{-- Text Overlay --}}
                    <div class="absolute bottom-8 left-8 right-8 text-white">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="w-8 h-px bg-white/40"></span>
                            <p class="uppercase tracking-[0.25em] text-[10px] text-white/50 font-label">
                                RYO EDITORIAL · SS25
                            </p>
                        </div>
                        <h2
                            class="font-display text-[36px] md:text-[52px] leading-[1.05] font-light tracking-[-0.04em] max-w-md">
                            Modern Luxury<br>Streetwear
                        </h2>
                        <a href="#"
                            class="inline-flex items-center gap-2 mt-5 text-[11px] font-label tracking-[0.2em] uppercase text-white/70 hover:text-white transition-colors group/link">
                            Discover the story
                            <svg class="w-3 h-3 group-hover/link:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    {{-- Floating Badge --}}
                    <div
                        class="absolute top-6 right-6 backdrop-blur-md bg-white/5 border border-white/15 rounded-full px-4 py-1.5">
                        <span class="text-[9px] font-label tracking-[0.2em] uppercase text-white/70">Limited
                            Release</span>
                    </div>

                    {{-- Play Icon Overlay for Video Ambiance (optional) --}}
                    <div
                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-14 h-14 rounded-full border border-white/30 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500">
                        <svg class="w-5 h-5 text-white/70 translate-x-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sophisticated Bottom Fade Line --}}
        <div
            class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent">
        </div>
    </section>


    {{-- FEATURED --}}
    <section class="px-6 md:px-10 py-24 md:py-32" id="featured-collections">

        <div class="max-w-[1440px] mx-auto">
            @if ($featured_collections)
                <div class="grid md:grid-cols-2 gap-16 items-center">

                    <div class="relative overflow-hidden h-[600px]">

                        <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=1600&auto=format&fit=crop"
                            class="w-full h-full object-cover">

                    </div>


                    <div>

                        <p class="uppercase tracking-[0.22em] text-[11px] text-ryo-gray-400 mb-6 font-label">
                            Featured Collection
                        </p>

                        <h2
                            class="font-display text-[52px] md:text-[86px] leading-[0.95] font-light tracking-[-0.05em] text-ryo-black">
                            {{ $featured_collections->collection_name }}
                        </h2>

                        <p class="mt-8 text-sm md:text-base leading-8 text-ryo-gray-700 max-w-xl">

                            {{ $featured_collections->collection_desc }}

                        </p>

                        <div class="flex gap-10 mt-12">

                            <div>

                                <h3 class="font-display text-[36px] leading-none text-ryo-black">
                                    {{ $featured_collections->products_count }}
                                </h3>

                                <p class="uppercase tracking-[0.18em] text-[11px] text-ryo-gray-400 font-label mt-2">
                                    Pieces
                                </p>

                            </div>


                            <a href="{{ route('collection.show', $featured_collections->slug) }}"
                                class="group inline-flex items-center gap-4 mt-3">
                                <div>
                                    <h3
                                        class="font-display text-[28px] md:text-[36px] leading-none text-ryo-black transition duration-300 group-hover:translate-x-1">
                                        Explore
                                    </h3>
                                    <p
                                        class="uppercase tracking-[0.18em] text-[11px] text-ryo-gray-400 font-label mt-2">
                                        Collection
                                    </p>
                                </div>

                                <svg class="transition duration-300 group-hover:translate-x-1" width="18"
                                    height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5">
                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>
                                </svg>
                            </a>

                        </div>

                    </div>

                </div>
            @endif

        </div>

    </section>



    {{-- COLLECTION GRID --}}
    <section class="px-6 md:px-10 pb-24 md:pb-32">

        <div class="max-w-[1440px] mx-auto">

            <div class="mb-16">

                <p class="uppercase tracking-[0.22em] text-[11px] text-ryo-gray-400 mb-5 font-label">
                    All Collections
                </p>

                <h2 class="font-display text-[42px] md:text-[76px] leading-none font-light tracking-[-0.05em]">
                    Editorial Archive
                </h2>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 auto-rows-[340px] md:auto-rows-[420px]">

                @foreach ($all_collections as $index => $collection)
                    <a href="{{ route('collection.show', ['slug' => $collection->slug]) }}"
                        class="editorial-card

                        @if ($index == 0) md:col-span-7 md:row-span-2
                        @elseif($index == 1)
                            md:col-span-5
                        @elseif($index == 2)
                            md:col-span-5
                        @elseif($index == 3)
                            md:col-span-4
                        @elseif($index == 4)
                            md:col-span-8 @endif">

                        <img
                            src="https://images.unsplash.com/photo-1496747611176-843222e1e57c?q=80&w=1600&auto=format&fit=crop">

                        <div class="editorial-overlay"></div>


                        <div class="absolute inset-0 flex flex-col justify-end p-8 md:p-14 text-white z-10">

                            <div class="max-w-xl">

                                <p class="uppercase tracking-[0.2em] text-[11px] text-white/60 mb-4 font-label">
                                    Collection
                                </p>

                                <h2
                                    class="collection-title font-display text-[40px] md:text-[88px] leading-[0.9] font-light tracking-[-0.06em] mb-6">

                                    {{ $collection->collection_name }}

                                </h2>

                                <p class="text-[13px] md:text-[15px] leading-7 text-white/75 max-w-lg">

                                    {{ $collection->collection_desc }}

                                </p>


                                <div class="flex items-center gap-5 mt-8 flex-wrap">

                                    <div
                                        class="border border-white/15 bg-white/10 backdrop-blur-sm px-5 py-3 text-[11px] uppercase tracking-[0.18em] font-label">

                                        {{ $collection->products_count }} Products

                                    </div>


                                    <div
                                        class="editorial-link inline-flex items-center gap-3 uppercase tracking-[0.2em] text-[11px] font-label">

                                        Explore Collection

                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.5">

                                            <path d="M5 12h14"></path>

                                            <path d="m12 5 7 7-7 7"></path>

                                        </svg>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </a>
                @endforeach

            </div>

        </div>

    </section>


    {{-- BRAND QUOTES --}}
    <section class="bg-ryo-black text-white py-24 md:py-32 overflow-hidden relative">

        {{-- BACKGROUND --}}
        <div class="absolute inset-0 opacity-[0.04]">

            <img src="https://images.unsplash.com/photo-1514996937319-344454492b37?q=80&w=1800&auto=format&fit=crop"
                class="w-full h-full object-cover">

        </div>



        {{-- CONTENT --}}
        <div class="relative max-w-5xl mx-auto px-6 text-center">

            <p class="uppercase tracking-[0.25em] text-[11px] text-white/50 mb-8 font-label">

                Manifesto

            </p>



            {{-- SLIDER --}}
            <div class="relative min-h-[320px] md:min-h-[320px] overflow-hidden">
                {{-- QUOTE 1 --}}
                <div
                    class="quote-slide active absolute inset-0 flex flex-col items-center justify-center opacity-100 translate-y-0">

                    <blockquote
                        class="font-display text-[36px] md:text-[72px] leading-[1.1] tracking-[-0.04em] font-light max-w-4xl">

                        Every collection is a fragment of movement, texture and emotion.

                    </blockquote>

                    <p class="mt-10 max-w-2xl mx-auto text-sm md:text-base text-white/60 leading-8">

                        RYO blends oversized silhouettes, muted palettes and refined
                        tailoring into wearable editorial streetwear.

                    </p>

                </div>



                {{-- QUOTE 2 --}}
                <div
                    class="quote-slide absolute inset-0 flex flex-col items-center justify-center opacity-0 translate-y-10">

                    <blockquote
                        class="font-display text-[36px] md:text-[72px] leading-[1.1] tracking-[-0.04em] font-light max-w-4xl">

                        Minimalism is not absence. It is intentional restraint.

                    </blockquote>

                    <p class="mt-10 max-w-2xl mx-auto text-sm md:text-base text-white/60 leading-8">

                        Designed with muted tones, heavyweight fabrics and timeless
                        oversized proportions.

                    </p>

                </div>



                {{-- QUOTE 3 --}}
                <div
                    class="quote-slide absolute inset-0 flex flex-col items-center justify-center opacity-0 translate-y-10">

                    <blockquote
                        class="font-display text-[36px] md:text-[72px] leading-[1.1] tracking-[-0.04em] font-light max-w-4xl">

                        Built for everyday movement with a luxury editorial perspective.

                    </blockquote>

                    <p class="mt-10 max-w-2xl mx-auto text-sm md:text-base text-white/60 leading-8">

                        RYO explores contemporary streetwear through cinematic visuals
                        and elevated silhouettes.

                    </p>

                </div>

            </div>

        </div>



        {{-- STYLE --}}
        <style>
            .quote-slide {

                transition:
                    opacity 1s ease,
                    transform 1s ease;

            }

            .quote-slide.active {

                opacity: 1;

                transform: translateY(0);

                z-index: 10;
            }

            .quote-slide.hidden-slide {

                opacity: 0;

                transform: translateY(40px);

                z-index: 1;
            }
        </style>



        {{-- SCRIPT --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                const slides = document.querySelectorAll('.quote-slide');

                let current = 0;

                setInterval(() => {

                    slides[current].classList.remove('active');

                    slides[current].classList.add('hidden-slide');

                    current = (current + 1) % slides.length;

                    slides[current].classList.remove('hidden-slide');

                    slides[current].classList.add('active');

                }, 5000);

            });
        </script>

    </section>



    {{-- Footer --}}
    @include('partials.footer')

    <script src="{{ asset('js/website-product.js') }}" defer></script>
    <script src="{{ asset('js/website-shop.js') }}"></script>
</body>

</html>
