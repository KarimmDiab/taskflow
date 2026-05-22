<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RYO - Shipping Policies</title>
    <meta name="description" content="RYO shipping policy, return policy, privacy policy, and terms of service.">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/website.css') }}" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon/favicon.png') }}">
    <link href="{{ asset('css/website-shipping-policy.css') }}" rel="stylesheet">
    <script src="{{ asset('js/tailwind.js') }}"></script>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    {{-- Navbar --}}
    @include('partials.nav-bar2')

    {{-- Mobile Menu --}}
    @include('partials.mobile-menu')

    {{-- Cart Drawer --}}
    @include('partials.cart-drawer')


    <!-- ══════════════════════════════════════════
     PAGE HERO
══════════════════════════════════════════ -->
    <section
        style="
    position:relative;
    padding:70px 40px 120px;
    background:#F8F6F2;
    overflow:hidden;
    border-bottom:1px solid #EDEDEB;
">

        <!-- Background Accent -->
        <div
            style="
        position:absolute;
        top:-180px;
        right:-120px;
        width:520px;
        height:520px;
        border-radius:50%;
        background:rgba(10,10,10,0.025);
        pointer-events:none;
    ">
        </div>

        <div style="
        position:relative;
        max-width:1400px;
        margin:0 auto;
    ">

            <!-- Breadcrumb -->
            <div
                style="
            display:flex;
            align-items:center;
            gap:10px;
            margin-bottom:40px;
        ">

                <a href="{{ route('home') }}"
                    style="
                    font-family:'Space Grotesk',sans-serif;
                    font-size:10px;
                    letter-spacing:.18em;
                    text-transform:uppercase;
                    color:#9C9A96;
                    text-decoration:none;
                    transition:color .3s ease;
               "
                    onmouseover="this.style.color='#0A0A0A'" onmouseout="this.style.color='#9C9A96'">

                    Home

                </a>

                <span style="
                color:#D5D3CF;
                font-size:10px;
            ">
                    /
                </span>

                <span
                    style="
                font-family:'Space Grotesk',sans-serif;
                font-size:10px;
                letter-spacing:.18em;
                text-transform:uppercase;
                color:#0A0A0A;
            ">
                    Policies
                </span>

            </div>

            <!-- Main Grid -->
            <div
                style="
            display:grid;
            grid-template-columns:1.1fr 0.9fr;
            gap:120px;
            align-items:end;
        ">

                <!-- Left -->
                <div>

                    <p
                        style="
                    font-family:'Space Grotesk',sans-serif;
                    font-size:10px;
                    letter-spacing:.28em;
                    text-transform:uppercase;
                    color:#9C9A96;
                    margin-bottom:28px;
                ">
                        Legal & Information
                    </p>

                    <h1
                        style="
                    font-family:'Cormorant Garamond',serif;
                    font-size:clamp(64px,8vw,130px);
                    font-weight:300;
                    letter-spacing:-.05em;
                    line-height:.88;
                    color:#0A0A0A;
                    margin:0;
                ">

                        Our<br>

                        <span
                            style="
                        font-style:italic;
                        font-weight:400;
                    ">
                            Policies
                        </span>

                    </h1>

                </div>

                <!-- Right -->
                <div>

                    <div
                        style="
                    width:72px;
                    height:1px;
                    background:#0A0A0A;
                    margin-bottom:32px;
                ">
                    </div>

                    <p
                        style="
                    font-family:'DM Sans',sans-serif;
                    font-size:15px;
                    font-weight:300;
                    color:#5A5A57;
                    line-height:2;
                    max-width:460px;
                    margin-bottom:40px;
                ">

                        Everything you need to know about shipping,
                        returns, privacy, and terms. Clear policies
                        designed to create a smooth and transparent experience.

                    </p>

                    <!-- Mini Info -->
                    <div
                        style="
                    display:flex;
                    flex-wrap:wrap;
                    gap:48px;
                ">

                        <div>
                            <p
                                style="
                            font-family:'Space Grotesk',sans-serif;
                            font-size:10px;
                            letter-spacing:.16em;
                            text-transform:uppercase;
                            color:#9C9A96;
                            margin-bottom:10px;
                        ">
                                Updated
                            </p>

                            <p
                                style="
                            font-family:'DM Sans',sans-serif;
                            font-size:14px;
                            color:#0A0A0A;
                        ">
                                May 2026
                            </p>
                        </div>

                        <div>
                            <p
                                style="
                            font-family:'Space Grotesk',sans-serif;
                            font-size:10px;
                            letter-spacing:.16em;
                            text-transform:uppercase;
                            color:#9C9A96;
                            margin-bottom:10px;
                        ">
                                Support
                            </p>

                            <p
                                style="
                            font-family:'DM Sans',sans-serif;
                            font-size:14px;
                            color:#0A0A0A;
                        ">
                                info.ryo.brand@gmail.com
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- ══════════════════════════════════════════
     MAIN LAYOUT
══════════════════════════════════════════ -->
    <section style="
    background:#FAF8F5;
">


        <div style="max-width:1200px;margin:0 auto;gap:64px;padding:0 40px 120px;" id="policyLayout">



            <!-- CONTENT -->
            <main style="min-width:0;">

                <!-- ── SHIPPING ── -->
                <div class="policy-section reveal" id="shipping">

                    <h2>Shipping Policy</h2>

                    <div class="info-box">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9C9A96"
                            stroke-width="1.5" style="flex-shrink:0;margin-top:2px;">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <p>Orders placed before 2:00 PM on business days are typically processed the same day. Orders
                            placed on weekends or public holidays are processed the next business day.</p>
                    </div>

                    <h3>Delivery Timeframes</h3>
                    <table class="policy-table">
                        <thead>
                            <tr>
                                <th>Method</th>
                                <th>Delivery Time</th>
                                <th>Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Standard Delivery</td>
                                <td>3–5 business days</td>
                                <td>
                                    Shipping fees are automatically calculated based on your delivery governorate.
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <h3>Coverage Area</h3>
                    <p>We currently deliver across all governorates in Egypt. Remote areas may experience an additional
                        1–2 business days on delivery. We are continuously expanding our network.</p>

                    <h3>Order Tracking</h3>
                    <p>Once your order is dispatched, tracking details will be shared via email or WhatsApp. You may also contact our customer support team anytime for delivery updates.</p>

                    <h3>Delivery Attempts</h3>
                    <ul>
                        <li>Our courier will attempt delivery up to 2 times</li>
                        <li>If both attempts are unsuccessful, the order will be held for 3 days before being returned
                        </li>
                        <li>Re-delivery fees may apply after a failed delivery</li>
                        <li>Please ensure your phone number and address are accurate at checkout</li>
                    </ul>

                    <h3>Damaged in Transit</h3>
                    <p>If your order arrives damaged or incomplete, please contact us within 48 hours of delivery with
                        photos of the damaged item and packaging. We will arrange a replacement or full refund with no
                        questions asked.</p>
                </div>

            </main>

        </div>


    </section>


    {{-- Newslater --}}
    @include('partials.newslater')


    {{-- Footer --}}
    @include('partials.footer')



<script src="{{ asset('js/website-shipping-policy.js') }}"></script>
    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif


</body>

</html>
