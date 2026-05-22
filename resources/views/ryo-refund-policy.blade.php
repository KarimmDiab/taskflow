<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RYO - Refund Policies</title>
    <meta name="description" content="RYO shipping policy, return policy, privacy policy, and terms of service.">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/website.css') }}" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon/favicon.png') }}">
    <link href="{{ asset('css/website-refund-policy.css') }}" rel="stylesheet">
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
    <section class="hero">

        <div class="hero-accent"></div>

        <div class="hero-inner">

            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="/">Home</a>
                <span class="sep">/</span>
                <span class="current">Refund Policy</span>
            </div>

            <!-- Main Grid -->
            <div class="hero-grid">

                <!-- Left -->
                <div>
                    <p class="hero-label">Legal & Information</p>
                    <h1 class="hero-title">
                        Refund &<br>
                        <em>Returns</em>
                    </h1>
                </div>

                <!-- Right -->
                <div>
                    <div class="hero-divider"></div>
                    <p class="hero-desc">
                        We want you to love what you ordered. If something
                        isn't right, our refund policy is here to make things
                        as straightforward and fair as possible.
                    </p>
                    <div class="hero-meta">
                        <div class="hero-meta-item">
                            <p>Updated</p>
                            <p>May 2026</p>
                        </div>
                        <div class="hero-meta-item">
                            <p>Support</p>
                            <p>info.ryo.brand@gmail.com</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </section>


    <!-- ══════════════════════════════════════════
         MAIN CONTENT
    ══════════════════════════════════════════ -->
    <section class="main-layout">
        <div class="main-inner">

            <!-- ── OVERVIEW ── -->
            <div class="policy-section reveal" id="refund">

                <h2>Refund Policy</h2>

                <p>At RYO, we stand behind the quality of everything we make. If for any reason you are not completely
                    satisfied with your purchase, we are here to help. Please read the following carefully to understand
                    your options.</p>

                <div class="highlight-box">
                    <p>We accept returns and issue refunds within <strong>7 days</strong> of the delivery date,
                        provided items are unused, unworn, and in their original packaging with all tags attached.</p>
                </div>

                <!-- ── ELIGIBLE ITEMS ── -->
                <h3>Eligible for Return</h3>
                <div class="tag-row">
                    <span class="tag eligible">Unworn items</span>
                    <span class="tag eligible">Original packaging</span>
                    <span class="tag eligible">Tags attached</span>
                    <span class="tag eligible">Within 7 days</span>
                    <span class="tag eligible">Proof of purchase</span>
                </div>

                <!-- ── NOT ELIGIBLE ── -->
                <h3>Not Eligible for Return</h3>
                <ul>
                    <li>Items that have been worn, washed, or altered</li>
                    <li>Items without original tags or packaging</li>
                    <li>Sale or discounted items marked as final sale</li>
                    <li>Gift cards and store credit</li>
                    <li>Items returned after the 7-days window</li>
                    <li>Items damaged due to misuse or improper care</li>
                </ul>

                <!-- ── HOW TO RETURN ── -->
                <h3>How to Initiate a Return</h3>
                <div class="steps">
                    <div class="step">
                        <span class="step-num">01</span>
                        <div class="step-content">
                            <h4>Contact Us</h4>
                            <p>Email us at <a href="mailto:info.ryo.brand@gmail.com">info.ryo.brand@gmail.com</a> with
                                your order number and the reason for your return. Our customer support team is also
                                available to assist you throughout the process. We typically respond within 1–2 business
                                days.</p>
                            </p>
                        </div>
                    </div>
                    <div class="step">
                        <span class="step-num">02</span>
                        <div class="step-content">
                            <h4>Receive Approval</h4>
                            <p>Once your return request is approved, we will send you detailed instructions and a return
                                address. Do not ship items back without prior approval.</p>
                        </div>
                    </div>
                    <div class="step">
                        <span class="step-num">03</span>
                        <div class="step-content">
                            <h4>Ship the Item</h4>
                            <p>Pack the item securely in its original packaging and ship it back using a tracked
                                courier.
                                Return shipping costs are the responsibility of the customer unless the item arrived
                                damaged or incorrect.</p>
                        </div>
                    </div>
                    <div class="step">
                        <span class="step-num">04</span>
                        <div class="step-content">
                            <h4>Receive Your Refund</h4>
                            <p>Once we receive and inspect the returned item, we will process your refund within 5–7
                                business days. Refunds are issued to the original payment method.</p>
                        </div>
                    </div>
                </div>

                <!-- ── EXCHANGES ── -->
                <h3>Exchanges</h3>
                <p>
                    For size or colour exchanges, please contact our customer support team with your order details and
                    we’ll gladly assist you with the process.
                </p>

                <!-- ── DAMAGED / WRONG ── -->
                <h3>Damaged or Incorrect Items</h3>
                <p>If your order arrived damaged, defective, or incorrect, please contact us within <strong>48
                        hours</strong>
                    of delivery. Include your order number and clear photos of the item and packaging. In these cases,
                    we will cover return shipping and issue a full refund or send a replacement at no additional
                    cost.</p>

                <!-- ── REFUND TIMELINE ── -->
                <h3>Refund Timeline</h3>
                <ul>
                    <li>Refund approval confirmation: 1–2 business days after we receive the item</li>
                    <li>Processing time: 5–7 business days</li>
                    <li>Bank or card posting time: an additional 3–5 business days depending on your provider</li>
                </ul>
                <p>If you haven't received your refund after 10 business days, please check with your bank first.
                    If the issue persists, reach out to us and we'll investigate promptly.</p>

                <!-- ── QUESTIONS ── -->
                <h3>Questions?</h3>
                <p>We are always happy to help. Reach out to us at
                    <a href="mailto:info.ryo.brand@gmail.com">info.ryo.brand@gmail.com</a> and our team will
                    get back to you as soon as possible.
                </p>

            </div>

        </div>
    </section>

    {{-- Newslater --}}
    @include('partials.newslater')


    {{-- Footer --}}
    @include('partials.footer')



<script src="{{ asset('js/website-refund-policy.js') }}"></script>


</body>

</html>
