<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RYO - About Us</title>
    <meta name="description"
        content="Discover the story behind RYO — minimalist luxury, timeless design, and Egyptian craftsmanship. Learn about our philosophy, materials, and commitment to slow fashion.">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/website.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon/favicon.png') }}">
    <link href="{{ asset('css/website-about-me.css') }}" rel="stylesheet" type="text/css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind.js') }}"></script>
</head>

<body>

    {{-- Navbar --}}
    @include('partials.nav-bar2')

    {{-- Mobile Menu --}}
    @include('partials.mobile-menu')

    {{-- Cart Drawer --}}
    @include('partials.cart-drawer')


    <!-- ══════════════════════════════════════════
       ABOUT HERO SECTION (same elegance as policies)
══════════════════════════════════════════ -->
    <section
        style="position:relative;padding:70px 40px 120px;background:#F8F6F2;overflow:hidden;border-bottom:1px solid #EDEDEB;">
        <!-- Background Accent -->
        <div
            style="position:absolute;top:-180px;right:-120px;width:520px;height:520px;border-radius:50%;background:rgba(10,10,10,0.025);pointer-events:none;">
        </div>

        <div style="position:relative;max-width:1400px;margin:0 auto;">
            <!-- Breadcrumb -->
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:40px;">
                <a href="{{ route('home') }}"
                    style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:#9C9A96;text-decoration:none;transition:color .3s ease;"
                    onmouseover="this.style.color='#0A0A0A'" onmouseout="this.style.color='#9C9A96'">Home</a>
                <span style="color:#D5D3CF;font-size:10px;">/</span>
                <span
                    style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:#0A0A0A;">About</span>
            </div>

            <!-- Main Grid -->
            <div class="about-hero-grid"
                style="display:grid;grid-template-columns:1.1fr 0.9fr;gap:120px;align-items:end;">
                <!-- Left -->
                <div>
                    <p
                        style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.28em;text-transform:uppercase;color:#9C9A96;margin-bottom:28px;">
                        The story
                    </p>
                    <h1
                        style="font-family:'Cormorant Garamond',serif;font-size:clamp(64px,8vw,130px);font-weight:300;letter-spacing:-.05em;line-height:.88;color:#0A0A0A;margin:0;">
                        RYO<br>
                        <span style="font-style:italic;font-weight:400;">Essentials</span>
                    </h1>
                </div>

                <!-- Right -->
                <div>
                    <div style="width:72px;height:1px;background:#0A0A0A;margin-bottom:32px;"></div>
                    <p
                        style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:bolder;color:#5A5A57;line-height:2;max-width:460px;margin-bottom:10px;">
                        Be Real, Be Royal.
                    </p>
                    <p
                        style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:300;color:#5A5A57;line-height:2;max-width:460px;margin-bottom:10px;">
                        In a world constantly chasing what’s next, RYO focuses on what lasts.
                        Every piece is designed with intention — balancing comfort, simplicity, and understated luxury
                        in a way that feels effortless every time you wear it.
                    </p>
                    <p
                        style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:bolder;color:#5A5A57;line-height:2;max-width:460px;margin-bottom:10px;">
                        Built beyond seasons.
                    </p>
                    <div style="display:flex;flex-wrap:wrap;gap:48px;">
                        <div>
                            <p
                                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.16em;text-transform:uppercase;color:#9C9A96;margin-bottom:10px;">
                                Founded</p>
                            <p style="font-family:'DM Sans',sans-serif;font-size:14px;color:#0A0A0A;">2026</p>
                        </div>
                        <div>
                            <p
                                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.16em;text-transform:uppercase;color:#9C9A96;margin-bottom:10px;">
                                Origin</p>
                            <p style="font-family:'DM Sans',sans-serif;font-size:14px;color:#0A0A0A;">Alexandria, Egypt
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ══════════════════════════════════════════
       PHILOSOPHY SECTION (two column with image placeholder)
══════════════════════════════════════════ -->
    <section style="background:#FAF8F5;padding:100px 40px;">
        <div style="max-width:1200px;margin:0 auto;">
            <div class="about-two-col" style="display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center;">
                <!-- Left: Text -->
                <div class="reveal">
                    <p
                        style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.28em;text-transform:uppercase;color:#9C9A96;margin-bottom:20px;">
                        Our philosophy</p>
                    <h2
                        style="font-family:'Cormorant Garamond',serif;font-size:clamp(36px,5vw,58px);font-weight:300;letter-spacing:-.02em;line-height:1.15;margin-bottom:28px;">
                        Designed to feel effortless
                    </h2>
                    <p
                        style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:300;color:#3D3D3A;line-height:1.85;margin-bottom:24px;">
                        RYO is built around simplicity that feels expensive. Every piece is intentional — relaxed fits,
                        refined textures, and effortless styling made to last beyond trends.</p>
                    <p
                        style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:300;color:#3D3D3A;line-height:1.85;margin-bottom:32px;">
                        We believe that true luxury lies in the invisible details: the hand of the fabric, the precision
                        of a seam, the way a garment moves with you. Our collections are designed to be lived in,
                        cherished, and passed down.</p>
                    <div style="width:48px;height:1px;background:#0A0A0A;"></div>
                </div>
                <!-- Right: Image Placeholder (same aesthetic as map in contact) -->
                <div class="reveal"
                    style="background:#EDEDEB;height:460px;display:flex;align-items:center;justify-content:center;border:1px solid #D5D3CF;">
                    <img src="{{ asset('images/about-me/about-me.png') }}" alt="Philosophy Image"
                        style="max-width:100%;border-radius:4px;height: 100%;">
                </div>
            </div>
        </div>
    </section>


    <!-- ══════════════════════════════════════════
       VALUES SECTION (3 cards)
══════════════════════════════════════════ -->
    <section style="background:#F8F6F2;padding:80px 40px;">
        <div style="max-width:1200px;margin:0 auto;">
            <div style="text-align:center;margin-bottom:64px;" class="reveal">
                <p
                    style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.28em;text-transform:uppercase;color:#9C9A96;">
                    What we stand for</p>
                <h2
                    style="font-family:'Cormorant Garamond',serif;font-size:clamp(36px,4.5vw,52px);font-weight:300;letter-spacing:-.02em;margin-top:16px;">
                    Core values</h2>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:40px;">
                <div class="value-card reveal">
                    <div style="font-size:32px;margin-bottom:24px;">💎</div>
                    <h3
                        style="font-family:'Space Grotesk',sans-serif;font-size:12px;letter-spacing:.2em;text-transform:uppercase;margin-bottom:16px;">
                        Accessible luxury</h3>
                    <p
                        style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#5A5A57;line-height:1.7;">
                        Premium feel and refined essentials at prices designed for everyday wear.</p>
                </div>
                <div class="value-card reveal">
                    <div style="font-size:32px;margin-bottom:24px;">♾️</div>
                    <h3
                        style="font-family:'Space Grotesk',sans-serif;font-size:12px;letter-spacing:.2em;text-transform:uppercase;margin-bottom:16px;">
                        Quality that lasts</h3>
                    <p
                        style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#5A5A57;line-height:1.7;">
                        Carefully selected fabrics, clean finishes, and comfort you notice from the first wear.</p>
                </div>
                <div class="value-card reveal">
                    <div style="font-size:32px;margin-bottom:24px;">🤝</div>
                    <h3
                        style="font-family:'Space Grotesk',sans-serif;font-size:12px;letter-spacing:.2em;text-transform:uppercase;margin-bottom:16px;">
                        Customer experience</h3>
                    <p
                        style="font-family:'DM Sans',sans-serif;font-size:14px;font-weight:300;color:#5A5A57;line-height:1.7;">
                        From ordering to after-sales support, we focus on making every experience smooth, personal, and
                        reliable.</p>
                </div>
            </div>
        </div>
    </section>




    <!-- ══════════════════════════════════════════
       TEAM / FOUNDER MESSAGE (simple & elegant)
══════════════════════════════════════════ -->
    <section style="background:#FAF8F5;padding:80px 40px 120px;">
        <div style="max-width:900px;margin:0 auto;text-align:center;" class="reveal">
            <div
                style="width:80px;height:80px;background:#EDEDEB;border-radius:50%;margin:0 auto 28px;display:flex;align-items:center;justify-content:center;border:1px solid #D5D3CF;">
                <span style="font-family:'Cormorant Garamond',serif;font-size:32px;font-style:italic;">RYO</span>
            </div>
            <p
                style="font-family:'Cormorant Garamond',serif;font-size:clamp(22px,3vw,30px);font-weight:300;font-style:italic;color:#0A0A0A;line-height:1.5;margin-bottom:28px;">
                “We design for those who appreciate the quiet power of simplicity. Clothes should never compete with the
                wearer — they should support, elevate, and fade into the background of a life well lived.”</p>
            <p
                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;">
                — Karim Diab, Founder & CEO</p>
        </div>
    </section>


    {{-- Newsletter --}}
    @include('partials.newslater')

    {{-- Footer --}}
    @include('partials.footer')




    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif

</body>

</html>
