<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RYO - {{ $selectedProdcut->product_name }}</title>
    <meta name="description"
        content="The RYO Oversized Essential Tee. Premium 350gsm cotton. Dropped shoulders, relaxed silhouette. SS25 Collection.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon/favicon.png') }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=DM+Sans:wght@300;400;500&family=Space+Grotesk:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/website-product.css') }}">
    <script src="{{ asset('js/tailwind.js') }}"></script>

</head>

<body>

    {{-- Partials (unchanged) --}}
    @include('partials.nav-bar2')
    @include('partials.mobile-menu')
    @include('partials.cart-drawer')

    <!-- TOAST -->
    <div id="toast">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <polyline points="20 6 9 17 4 12" />
        </svg>
        Added to bag
        <button onclick="window.cart.toggle()"
            style="background:none;border:none;cursor:pointer;color:#9C9A96;font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.1em;text-transform:uppercase;margin-left:8px;">View
            Bag</button>
    </div>

    <!-- BREADCRUMB -->
    <div style="padding:16px 40px;border-bottom:1px solid #EDEDEB;max-width:1440px;margin:0 auto;">
        <div style="display:flex;align-items:center;gap:8px;">
            <a href="{{ route('home') }}" class="bc">Home</a><span style="color:#D5D3CF;font-size:11px;">/</span>
            <a href="{{ route('all-products') }}" class="bc">Shop</a><span
                style="color:#D5D3CF;font-size:11px;">/</span>
            <a href="{{ route('all-products', ['category' => $selectedProdcut->category?->category_name]) }}"
                class="bc">{{ $selectedProdcut->category?->category_name }}</a>
            <span style="color:#D5D3CF;font-size:11px;">/</span><span
                class="bc cur">{{ $selectedProdcut->product_name }}</span>
        </div>
    </div>

    <!-- PRODUCT MAIN LAYOUT -->
    <div style="max-width:1440px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:0;" id="pdpLayout">
        <!-- LEFT GALLERY -->
        <div
            style="padding:32px 40px 0 40px;display:flex;gap:16px;position:sticky;top:64px;align-self:start;max-height:calc(100vh - 64px);">
            <div style="display:flex;flex-direction:column;gap:8px;flex-shrink:0;" id="thumbs">
                <div class="gallery-thumb active" data-img-index="0"><img
                        src="https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=300&q=80" alt="Thumb 1">
                </div>
                <div class="gallery-thumb" data-img-index="1"><img
                        src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=300&q=80" alt="Thumb 2">
                </div>
                <div class="gallery-thumb" data-img-index="2"><img
                        src="https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=300&q=80" alt="Thumb 3">
                </div>
                <div class="gallery-thumb" data-img-index="3"><img
                        src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?w=300&q=80" alt="Thumb 4">
                </div>
                <div class="gallery-thumb" data-img-index="4"><img
                        src="https://images.unsplash.com/photo-1578681994506-b8f463449011?w=300&q=80" alt="Thumb 5">
                </div>
            </div>
            <div class="main-img-wrap" style="flex:1;cursor:zoom-in;" onclick="window.lightbox?.open()">
                <img id="mainImg" src="https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&q=85"
                    alt="Oversized Essential Tee - Main">
                <div class="zoom-badge"><svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5">
                        <circle cx="11" cy="11" r="8" />
                        <path d="M21 21l-4.35-4.35" />
                        <line x1="11" y1="8" x2="11" y2="14" />
                        <line x1="8" y1="11" x2="14" y2="11" />
                    </svg>Click to Zoom</div>
                <button class="md:hidden" onclick="window.gallery.prev(event)"
                    style="position:absolute;left:12px;top:50%;transform:translateY(-50%);background:rgba(248,246,242,.85);border:none;cursor:pointer;width:36px;height:36px;display:flex;align-items:center;justify-content:center;"><svg
                        width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path d="M15 18l-6-6 6-6" />
                    </svg></button>
                <button class="md:hidden" onclick="window.gallery.next(event)"
                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:rgba(248,246,242,.85);border:none;cursor:pointer;width:36px;height:36px;display:flex;align-items:center;justify-content:center;"><svg
                        width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path d="M9 18l6-6-6-6" />
                    </svg></button>
            </div>
        </div>

        <!-- RIGHT PRODUCT INFO -->
        <div style="padding:40px 40px 80px 48px;max-width:560px;" id="productInfo">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <p
                    style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;">
                    RYO · {{ $selectedProdcut->slug }}</p>
            </div>
            <h1
                style="font-family:'Cormorant Garamond',serif;font-size:clamp(32px,3.5vw,48px);font-weight:300;letter-spacing:-.01em;line-height:1.1;margin-bottom:16px;">
                {{ $selectedProdcut->product_name }}</h1>
            @php
                $firstVariant = $selectedProdcut->productVariants->first();
                $price = $firstVariant?->variant_price ?? 0;
                $oldPrice = $price > 0 ? $price / (1 - 0.25) : 0;
            @endphp
            <div style="display:flex;align-items:baseline;gap:16px;margin-bottom:32px;" id="priceBlock"><span
                    id="currentPrice"
                    style="font-family:'DM Sans',sans-serif;font-size:22px;font-weight:400;color:#0A0A0A;">EGP
                    {{ number_format($price) }}</span><span id="oldPrice"
                    style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.15em;text-transform:uppercase;color:#9C9A96;text-decoration:line-through;">EGP
                    {{ number_format($oldPrice) }}</span><span
                    style="background:#0A0A0A;color:white;padding:3px 6px;border-radius:999px;font-family:'Space Grotesk',sans-serif;font-size:7px;letter-spacing:.12em;text-transform:uppercase;">Discount
                    25%</span></div>
            <div style="height:1px;background:black;margin-bottom:28px;"></div>

            @php $variantsJson = $selectedProdcut->productVariants->map(fn($v) => ['id' => $v->id, 'colorId' => $v->color_id, 'colorName' => $v->color?->color_name ?? '', 'colorHex' => $v->color?->color_hex_code ?? '#000', 'sizeId' => $v->size_id, 'sizeName' => $v->size?->size_name ?? '', 'price' => (float)$v->variant_price, 'stock' => (int)$v->inventories->sum('quantity'), 'sku' => $v->sku ?? ''])->values(); @endphp
            <script>
                window.VARIANTS = @json($variantsJson);
                window.CHECKOUT_URL = @json(route('checkout'));
            </script>

            <!-- COLOR -->
            <div style="margin-bottom:28px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                    <p
                        style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;">
                        Color: <span id="selectedColorLabel"
                            style="color:#9C9A96;font-weight:400;">{{ $firstVariant?->color?->color_name ?? '' }}</span>
                    </p>
                </div>
                <div style="display:flex;gap:10px;align-items:center;" id="colorSwatches">@php $uniqueColors = $selectedProdcut->productVariants->unique('color_id'); @endphp
                    @foreach ($uniqueColors as $variant)
                        <div class="color-swatch-opt {{ $loop->first ? 'active' : '' }}"
                            data-color-id="{{ $variant->color_id }}"
                            style="background:{{ $variant->color?->color_hex_code ?? '#000' }};"
                            title="{{ $variant->color?->color_name }}"></div>
                    @endforeach
                </div>
            </div>

            <!-- SIZE -->
            <div style="margin-bottom:32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                    <p
                        style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;">
                        Size: <span id="selectedSizeLabel" style="color:#9C9A96;font-weight:400;">— Select a
                            size</span></p><button onclick="window.sizeGuide.open()"
                        style="background:none;border:none;cursor:pointer;font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;text-decoration:underline;text-underline-offset:3px;display:flex;align-items:center;gap:5px;">Size
                        Guide<svg width="11" height="11" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg></button>
                </div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;" id="sizeBtns"></div>
                <p id="sizeError"
                    style="font-family:'DM Sans',sans-serif;font-size:11px;color:#C0392B;margin-top:8px;display:none;">
                    Please select a size to continue.</p>
            </div>
            <p id="stockBadge"
                style="font-family:'DM Sans',sans-serif;font-size:11px;color:#9C9A96;margin-top:-20px;margin-bottom:20px;display:none;">
            </p>

            <!-- QUANTITY + CART -->
            <div style="margin-bottom:24px;">
                <p
                    style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;margin-bottom:14px;">
                    Quantity</p>
                <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
                    <div style="display:flex;align-items:center;border:1px solid #D5D3CF;height:52px;flex-shrink:0;">
                        <button class="qty-btn" onclick="window.cart.changeQty(-1)" style="width:44px;"><svg
                                width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5">
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg></button><span id="qtyDisplay"
                            style="font-family:'DM Sans',sans-serif;font-size:14px;width:36px;text-align:center;border-left:1px solid #EDEDEB;border-right:1px solid #EDEDEB;height:100%;display:flex;align-items:center;justify-content:center;">1</span><button
                            class="qty-btn" onclick="window.cart.changeQty(1)" style="width:44px;"><svg
                                width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg></button>
                    </div>
                    <button class="atc-btn" id="atcBtn" onclick="window.cart.add()"><svg width="16"
                            height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <path d="M16 10a4 4 0 01-8 0" />
                        </svg>Add to Bag</button>
                    <button class="pay-now-btn" id="payNowBtn" onclick="window.cart.payNow()"
                        style="height:52px;background:#F8F6F2;color:#0A0A0A;border:1px solid #0A0A0A;cursor:pointer;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.12em;text-transform:uppercase;padding:0 24px;display:flex;align-items:center;gap:10px;transition:all .2s ease;"><svg
                            width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <rect x="2" y="5" width="20" height="14" rx="2" />
                            <line x1="2" y1="10" x2="22" y2="10" />
                        </svg>Pay Now</button>
                    <button class="wishlist-btn-lg" id="wishBtn" onclick="window.wishlist.toggle()"
                        aria-label="Add to wishlist"><svg width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="#0A0A0A" stroke-width="1.5" id="wishIcon">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                        </svg></button>
                </div>
            </div>

            <!-- TRUST BADGES (unchanged) -->
            <div
                style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0;border:1px solid #EDEDEB;margin-bottom:32px;">
                <div style="padding:14px 12px;text-align:center;border-right:1px solid #EDEDEB;"><svg width="18"
                        height="18" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"
                        style="margin:0 auto 6px;display:block;">
                        <path d="M5 12h14" />
                        <path d="M12 5l7 7-7 7" />
                    </svg>
                    <p
                        style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;line-height:1.4;">
                        Free Delivery<br>EGP 2,000+</p>
                </div>
                <div style="padding:14px 12px;text-align:center;border-right:1px solid #EDEDEB;"><svg width="18"
                        height="18" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"
                        style="margin:0 auto 6px;display:block;">
                        <polyline points="17 1 21 5 17 9" />
                        <path d="M3 11V9a4 4 0 014-4h14" />
                        <polyline points="7 23 3 19 7 15" />
                        <path d="M21 13v2a4 4 0 01-4 4H3" />
                    </svg>
                    <p
                        style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;line-height:1.4;">
                        14-Day<br>Returns</p>
                </div>
                <div style="padding:14px 12px;text-align:center;"><svg width="18" height="18"
                        viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.5"
                        style="margin:0 auto 6px;display:block;">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                    <p
                        style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;line-height:1.4;">
                        Secure<br>Checkout</p>
                </div>
            </div>
            <div style="height:1px;background:#EDEDEB;margin-bottom:0;"></div>

            <!-- ACCORDION (simplified markup) -->
            <div style="margin-bottom:0;">
                <div class="accordion-item open" id="acc-desc">
                    <div class="accordion-header">Description<svg class="accordion-icon" width="14"
                            height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg></div>
                    <div class="accordion-body">
                        <div class="accordion-content">
                            <p style="margin-bottom:12px;">The RYO Essential Tee is our signature oversized silhouette
                                — designed for those who understand that less is more. Cut from 350gsm heavyweight
                                cotton, this tee drapes with intention and improves with every wash.</p>
                            <p>Dropped shoulders, a relaxed chest, and a slightly elongated hem create a premium casual
                                look that works from morning to midnight.</p>
                        </div>
                    </div>
                </div>
                <div class="accordion-item" id="acc-details">
                    <div class="accordion-header">Material & Construction<svg class="accordion-icon" width="14"
                            height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg></div>
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
                <div class="accordion-item" id="acc-fit">
                    <div class="accordion-header">Fit & Sizing<svg class="accordion-icon" width="14"
                            height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg></div>
                    <div class="accordion-body">
                        <div class="accordion-content">
                            <p style="margin-bottom:12px;">Fits oversized. Model is 185cm and wears size M. For a
                                relaxed but not overwhelming fit, we recommend sizing down.</p>
                            <table style="width:100%;border-collapse:collapse;">
                                <thead>
                                    <tr style="border-bottom:1px solid #EDEDEB;">
                                        <th
                                            style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;text-align:left;padding:8px 0;">
                                            Size</th>
                                        <th
                                            style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;text-align:left;padding:8px 0;">
                                            Chest</th>
                                        <th
                                            style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.12em;text-transform:uppercase;color:#9C9A96;text-align:left;padding:8px 0;">
                                            Length</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="padding:8px 0;">XS</td>
                                        <td style="padding:8px 0;color:#9C9A96;">108cm</td>
                                        <td style="padding:8px 0;color:#9C9A96;">70cm</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:8px 0;">S</td>
                                        <td style="padding:8px 0;color:#9C9A96;">114cm</td>
                                        <td style="padding:8px 0;color:#9C9A96;">72cm</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:8px 0;">M</td>
                                        <td style="padding:8px 0;color:#9C9A96;">120cm</td>
                                        <td style="padding:8px 0;color:#9C9A96;">74cm</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:8px 0;">L</td>
                                        <td style="padding:8px 0;color:#9C9A96;">126cm</td>
                                        <td style="padding:8px 0;color:#9C9A96;">76cm</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:8px 0;">XL</td>
                                        <td style="padding:8px 0;color:#9C9A96;">132cm</td>
                                        <td style="padding:8px 0;color:#9C9A96;">78cm</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="accordion-item" id="acc-care">
                    <div class="accordion-header">Care Instructions<svg class="accordion-icon" width="14"
                            height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg></div>
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
                <div class="accordion-item" id="acc-shipping" style="border-bottom:none;">
                    <div class="accordion-header">Shipping & Returns<svg class="accordion-icon" width="14"
                            height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg></div>
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
    </div>

    <!-- RELATED PRODUCTS (unchanged) -->
    <section style="padding:80px 40px 100px;border-top:1px solid #EDEDEB;max-width:1440px;margin:0 auto;"
        class="reveal">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:40px;">
            <div>
                <p
                    style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#9C9A96;margin-bottom:12px;">
                    You Might Also Like</p>
                <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,3vw,44px);font-weight:300;">
                    Complete the Look</h2>
            </div><a href="shop.html"
                style="font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:#0A0A0A;text-decoration:none;border-bottom:1px solid #D5D3CF;padding-bottom:2px;"
                class="hidden md:block">View All</a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:24px;" id="relatedGrid">
            <div class="product-card-sm reveal"><a href="product.html">
                    <div class="img-w" style="aspect-ratio:3/4;"><img
                            src="https://images.unsplash.com/photo-1542272604-787c3835535d?w=500&q=80"
                            alt="Slim Chino"></div>
                    <div>
                        <p style="font-size:13px;">Slim Tapered Chino</p>
                        <p style="font-size:12px;color:#9C9A96;">Stone / Navy / Black</p>
                        <p style="font-size:13px;color:#9C9A96;">EGP 2,900</p>
                    </div>
                </a></div>
            <div class="product-card-sm reveal"><a href="product.html">
                    <div class="img-w" style="aspect-ratio:3/4;"><img
                            src="https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=500&q=80"
                            alt="Cargo Pant"></div>
                    <div>
                        <p style="font-size:13px;">Wide Leg Cargo Pant</p>
                        <p style="font-size:12px;color:#9C9A96;">Washed Black</p>
                        <p style="font-size:13px;color:#9C9A96;">EGP 3,800</p>
                    </div>
                </a></div>
            <div class="product-card-sm reveal"><a href="product.html">
                    <div class="img-w" style="aspect-ratio:3/4;"><img
                            src="https://images.unsplash.com/photo-1594938298603-f4d8c9a3a9a4?w=500&q=80"
                            alt="Crewneck"></div>
                    <div>
                        <p style="font-size:13px;">Washed Crewneck</p>
                        <p style="font-size:12px;color:#9C9A96;">Faded Grey / Black</p>
                        <p style="font-size:13px;color:#9C9A96;">EGP 3,400</p>
                    </div>
                </a></div>
            <div class="product-card-sm reveal"><a href="product.html">
                    <div class="img-w" style="aspect-ratio:3/4;"><img
                            src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=500&q=80"
                            alt="Coach Jacket"></div>
                    <div>
                        <p style="font-size:13px;">Utility Coach Jacket</p>
                        <p style="font-size:12px;color:#9C9A96;">Olive / Black</p>
                        <p style="font-size:13px;color:#9C9A96;">EGP 5,500</p>
                    </div>
                </a></div>
        </div>
    </section>

    <!-- STICKY ATC -->
    <div class="sticky-atc md:hidden" id="stickyATC">
        <div>
            <p style="font-family:'DM Sans',sans-serif;font-size:13px;margin-bottom:1px;">
                {{ $selectedProdcut->product_name }}</p>
            <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;" id="stickyPrice">EGP
                {{ number_format($price) }}</p>
        </div>
        <div style="display:flex;gap:8px;">
            <button onclick="window.cart.add()"
                style="background:#0A0A0A;color:#F8F6F2;border:none;cursor:pointer;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.12em;text-transform:uppercase;padding:14px 18px;">Add
                to Bag</button>
            <button onclick="window.cart.payNow()"
                style="background:#F8F6F2;color:#0A0A0A;border:1px solid #0A0A0A;cursor:pointer;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:.12em;text-transform:uppercase;padding:14px 18px;">Pay
                Now</button>
        </div>
    </div>

    <!-- SIZE GUIDE MODAL -->
    <div id="sizeModal"
        style="position:fixed;inset:0;background:rgba(10,10,10,.5);z-index:400;display:flex;align-items:flex-end;justify-content:center;opacity:0;pointer-events:none;transition:opacity .35s ease;"
        class="md:items-center">
        <div style="background:#F8F6F2;width:min(640px,100%);max-height:85vh;overflow-y:auto;padding:40px;position:relative;transform:translateY(40px);transition:transform .4s cubic-bezier(.25,.46,.45,.94);"
            id="sizeModalInner"><button onclick="window.sizeGuide.close()"
                style="position:absolute;top:20px;right:20px;background:none;border:none;cursor:pointer;"><svg
                    width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5">
                    <path d="M18 6L6 18M6 6l12 12" />
                </svg></button>
            <h3 style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:300;margin-bottom:8px;">Size
                Guide</h3>
            <p style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9C9A96;margin-bottom:24px;">All
                measurements in centimeters. Our tees fit oversized — size down for a more fitted look.</p>
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:2px solid #0A0A0A;">
                        <th
                            style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.15em;text-transform:uppercase;padding:10px 0;text-align:left;">
                            Size</th>
                        <th
                            style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.15em;text-transform:uppercase;padding:10px 0;text-align:left;">
                            Chest (cm)</th>
                        <th
                            style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.15em;text-transform:uppercase;padding:10px 0;text-align:left;">
                            Length (cm)</th>
                        <th
                            style="font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.15em;text-transform:uppercase;padding:10px 0;text-align:left;">
                            Shoulder (cm)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:12px 0;">XS</td>
                        <td style="color:#9C9A96;">108</td>
                        <td style="color:#9C9A96;">70</td>
                        <td style="color:#9C9A96;">52</td>
                    </tr>
                    <tr>
                        <td style="padding:12px 0;">S</td>
                        <td style="color:#9C9A96;">114</td>
                        <td style="color:#9C9A96;">72</td>
                        <td style="color:#9C9A96;">54</td>
                    </tr>
                    <tr>
                        <td style="padding:12px 0;">M</td>
                        <td style="color:#9C9A96;">120</td>
                        <td style="color:#9C9A96;">74</td>
                        <td style="color:#9C9A96;">56</td>
                    </tr>
                    <tr>
                        <td style="padding:12px 0;">L</td>
                        <td style="color:#9C9A96;">126</td>
                        <td style="color:#9C9A96;">76</td>
                        <td style="color:#9C9A96;">58</td>
                    </tr>
                    <tr>
                        <td style="padding:12px 0;">XL</td>
                        <td style="color:#9C9A96;">132</td>
                        <td style="color:#9C9A96;">78</td>
                        <td style="color:#9C9A96;">60</td>
                    </tr>
                    <tr>
                        <td style="padding:12px 0;">XXL</td>
                        <td style="color:#9C9A96;">138</td>
                        <td style="color:#9C9A96;">80</td>
                        <td style="color:#9C9A96;">62</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @include('partials.newslater')
    @include('partials.footer')

    <script src="{{ asset('js/website-product.js') }}" defer></script>

</body>

</html>
