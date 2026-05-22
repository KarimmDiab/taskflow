<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RYO - Policies</title>
    <meta name="description" content="RYO shipping policy, return policy, privacy policy, and terms of service.">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/website.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon/favicon.png') }}">
    <link href="{{ asset('css/website-privacy-policy.css') }}" rel="stylesheet">
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

                <!-- ── PRIVACY ── -->
                <div class="policy-section reveal" id="privacy">

                    <h2>Privacy Policy</h2>

                    <p>Your privacy is important to us. This policy explains how RYO collects, uses, and protects your
                        personal information when you shop with us or use our website.</p>

                    <h3>Information We Collect</h3>
                    <p>When you create an account or place an order, we collect:</p>
                    <ul>
                        <li>Your name, email address, phone number, and delivery address</li>
                        <li>Payment information (processed securely — we never store card details)</li>
                        <li>Order history and communication preferences</li>
                        <li>Device and browsing data when you visit our website (see Cookie Policy)</li>
                    </ul>

                    <h3>How We Use Your Information</h3>
                    <ul>
                        <li>To process and fulfil your orders</li>
                        <li>To send order confirmations, shipping updates, and receipts</li>
                        <li>To personalise your shopping experience</li>
                        <li>To send marketing communications (only with your consent)</li>
                        <li>To improve our website, products, and customer service</li>
                    </ul>

                    <h3>Data Sharing</h3>
                    <p>We do not sell your personal data. We only share it with trusted third parties when necessary to
                        provide our services, including:</p>
                    <ul>
                        <li>Delivery and logistics partners (to fulfil orders)</li>
                        <li>Payment processors (to handle transactions securely)</li>
                        <li>Customer support tools (to assist you when you reach out)</li>
                    </ul>

                    <h3>Data Security</h3>
                    <p>We implement industry-standard security measures including SSL encryption, secure data storage,
                        and access controls to protect your personal information from unauthorised access, disclosure,
                        or misuse.</p>

                    <h3>Your Rights</h3>
                    <p>You have the right to:</p>
                    <ul>
                        <li>Access the personal data we hold about you</li>
                        <li>Request correction of inaccurate information</li>
                        <li>Request deletion of your account and associated data</li>
                        <li>Opt out of marketing communications at any time</li>
                    </ul>

                    <h3>Data Retention</h3>
                    <p>We retain your data for as long as your account is active. You may request deletion of your data
                        at any time by contacting us at <a href="mailto:privacy@ryo.com">info.ryo.brand@gmail.com</a>.
                    </p>
                </div>


            </main>

        </div>


    </section>


    {{-- Newslater --}}
    @include('partials.newslater')


    {{-- Footer --}}
    @include('partials.footer')




<script src="{{ asset('js/website-privacy-policy.js') }}"></script>

    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif


</body>

</html>
