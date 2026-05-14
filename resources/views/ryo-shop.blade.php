<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RYO - Shop</title>
    <meta name="description"
        content="Shop RYO's full collection of premium oversized streetwear. New arrivals, best sellers, tees, bottoms, outerwear.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="{{ asset('css/website.css') }}" rel="stylesheet">
    <link href="{{ asset('css/website-shop.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon/favicon.png') }}">

    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=DM+Sans:wght@300;400;500&family=Space+Grotesk:wght@400;500&display=swap"
        rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ryo-black': '#0A0A0A',
                        'ryo-white': '#F8F6F2',
                        'ryo-gray-100': '#EDEDEB',
                        'ryo-gray-200': '#D5D3CF',
                        'ryo-gray-400': '#9C9A96',
                        'ryo-gray-700': '#3D3D3A',
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
</head>

<body>

    {{-- Navbar --}}
    @include('partials.nav-bar2')

    {{-- Mobile Menu --}}
    @include('partials.mobile-menu')

    {{-- Cart Drawer --}}
    @include('partials.cart-drawer')

    <?php
    use App\Models\Product;
    use App\Models\Category;

    // الحصول على التصنيف المختار من URL
    $selectedCategory = request('category', 'all');
    $perPage = request('per_page', 12);
    $sort = request('sort', '');
    $currentPage = request('page', 1);

    // جلب جميع التصنيفات مع عدد المنتجات في كل تصنيف
    $categories = Category::withCount([
        'products' => function ($query) {
            $query->whereHas('productVariants');
        },
    ])
        ->orderBy('category_name')
        ->get();

    $totalProducts = 0;
    foreach ($categories as $cat) {
        $totalProducts += $cat->products_count;
    }

    // بناء الاستعلام للمنتجات
    $productsQuery = Product::with(['productVariants.color', 'productVariants.size'])->whereHas('productVariants');

    // تطبيق فلترة التصنيف
    if ($selectedCategory !== 'all') {
        $productsQuery->whereHas('category', function ($q) use ($selectedCategory) {
            $q->where('category_name', $selectedCategory);
        });
    }

    // تطبيق الترتيب
    switch ($sort) {
        case 'price-asc':
            $productsQuery->orderBy('product_price', 'asc');
            break;
        case 'price-desc':
            $productsQuery->orderBy('product_price', 'desc');
            break;
        case 'name-asc':
            $productsQuery->orderBy('product_name', 'asc');
            break;
        case 'name-desc':
            $productsQuery->orderBy('product_name', 'desc');
            break;
        default:
            $productsQuery->latest();
    }

    $products = $productsQuery->paginate($perPage);

    // حساب عدد المنتجات في التصنيف الحالي
    $currentCategoryCount = $products->total();

    // الحصول على اسم التصنيف الحالي
    $currentCategoryName = 'All Products';
    if ($selectedCategory !== 'all') {
        $currentCat = Category::where('category_name', $selectedCategory)->first();
        $currentCategoryName = $currentCat ? $currentCat->category_name : 'Products';
    }
    ?>

    <!-- ═══════════════════════════════════════════
     SHOP HEADER
═══════════════════════════════════════════ -->
    <div style="padding:48px 40px 32px;max-width:1440px;margin:0 auto;border-bottom:1px solid #EDEDEB;">
        <!-- Breadcrumb -->
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:24px;">
            <a href="index.html" class="breadcrumb-item"
                style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;text-decoration:none;">Home</a>
            <span style="color:#D5D3CF;font-size:12px;">/</span>
            <span class="breadcrumb-item current"
                style="font-family:'DM Sans',sans-serif;font-size:12px;color:#0A0A0A;">Shop</span>
        </div>

        <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:16px;">
            <div>
                <h1
                    style="font-family:'Cormorant Garamond',serif;font-size:clamp(36px,5vw,64px);font-weight:300;letter-spacing:-0.01em;line-height:1;margin-bottom:8px;">
                    {{ $currentCategoryName }}
                </h1>
                <p id="productsCount" style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9C9A96;font-weight:300;">
                    {{ $currentCategoryCount }} {{ $currentCategoryCount == 1 ? 'product' : 'products' }}
                </p>
            </div>

            <!-- Top Filter Bar (desktop) -->
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;" class="hidden md:flex">
                <button class="filter-btn {{ $selectedCategory == 'all' ? 'active' : '' }}"
                        onclick="setCategory('all')"
                        data-cat="all"
                        style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:500;padding:8px 16px;border-radius:30px;background:{{ $selectedCategory == 'all' ? '#0A0A0A' : 'transparent' }};border:1px solid #D5D3CF;color:{{ $selectedCategory == 'all' ? '#FFFFFF' : '#0A0A0A' }};cursor:pointer;transition:all 0.3s ease;display:inline-flex;align-items:center;gap:6px;">
                    All
                    <span style="font-size:11px;opacity:0.8;">({{ $totalProducts }})</span>
                </button>

                @foreach ($categories as $category)
                    @if ($category->products_count > 0)
                        <button class="filter-btn {{ $selectedCategory == $category->category_name ? 'active' : '' }}"
                                onclick="setCategory('{{ $category->category_name }}')"
                                data-cat="{{ $category->category_name }}"
                                style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:500;padding:8px 16px;border-radius:30px;background:{{ $selectedCategory == $category->category_name ? '#0A0A0A' : 'transparent' }};border:1px solid #D5D3CF;color:{{ $selectedCategory == $category->category_name ? '#FFFFFF' : '#0A0A0A' }};cursor:pointer;transition:all 0.3s ease;display:inline-flex;align-items:center;gap:6px;">
                            {{ $category->category_name }}
                            <span style="font-size:11px;opacity:0.8;">({{ $category->products_count }})</span>
                        </button>
                    @endif
                @endforeach
            </div>
        </div>
    </div>



    <!-- ═══════════════════════════════════════════
     MAIN SHOP LAYOUT
═══════════════════════════════════════════ -->
    <div style="max-width:1440px;margin:0 auto;display:flex;gap:0;">

        <!-- MAIN PRODUCT AREA -->
        <main style="flex:1;padding:0 40px 80px;min-width:0;">

            <!-- Controls Bar -->
            <div
                style="display:flex;align-items:center;justify-content:space-between;padding:20px 0;border-bottom:1px solid #EDEDEB;margin-bottom:32px;">

                <span style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;" class="hidden md:block">
                    Displaying {{ $products->firstItem() ?? 0 }} – {{ $products->lastItem() ?? 0 }} From
                    {{ $products->total() }} Product
                </span>

                <div style="display:flex;align-items:center;gap:16px;">
                    <!-- Grid toggle -->
                    <div style="display:flex;align-items:center;gap:4px;" class="hidden md:flex">
                        <button class="grid-btn active" onclick="setGrid(4,this)" aria-label="4-column grid">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                <rect x="0" y="0" width="3" height="3" />
                                <rect x="4.5" y="0" width="3" height="3" />
                                <rect x="9" y="0" width="3" height="3" />
                                <rect x="13.5" y="0" width="2.5" height="3" />
                                <rect x="0" y="4.5" width="3" height="3" />
                                <rect x="4.5" y="4.5" width="3" height="3" />
                                <rect x="9" y="4.5" width="3" height="3" />
                                <rect x="13.5" y="4.5" width="2.5" height="3" />
                                <rect x="0" y="9" width="3" height="3" />
                                <rect x="4.5" y="9" width="3" height="3" />
                                <rect x="9" y="9" width="3" height="3" />
                                <rect x="13.5" y="9" width="2.5" height="3" />
                            </svg>
                        </button>
                        <button class="grid-btn" onclick="setGrid(3,this)" aria-label="3-column grid">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                <rect x="0" y="0" width="4" height="4" />
                                <rect x="6" y="0" width="4" height="4" />
                                <rect x="12" y="0" width="4" height="4" />
                                <rect x="0" y="6" width="4" height="4" />
                                <rect x="6" y="6" width="4" height="4" />
                                <rect x="12" y="6" width="4" height="4" />
                                <rect x="0" y="12" width="4" height="4" />
                                <rect x="6" y="12" width="4" height="4" />
                                <rect x="12" y="12" width="4" height="4" />
                            </svg>
                        </button>
                        <button class="grid-btn" onclick="setGrid(2,this)" aria-label="2-column grid">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                <rect x="0" y="0" width="6.5" height="6.5" />
                                <rect x="9.5" y="0" width="6.5" height="6.5" />
                                <rect x="0" y="9.5" width="6.5" height="6.5" />
                                <rect x="9.5" y="9.5" width="6.5" height="6.5" />
                            </svg>
                        </button>
                    </div>

                    <!-- Sort & Per Page -->
                    <div style="display:flex;align-items:center;gap:8px;">
                        <select class="sort-select" id="sortSelect" onchange="sortProducts(this.value)"
                            style="padding: 8px 12px; border-radius: 8px; border: 1px solid #e0e0e0;">
                            <option value="">-- Sort By --</option>
                            <option value="price-asc" {{ $sort == 'price-asc' ? 'selected' : '' }}>Price: Low to High
                            </option>
                            <option value="price-desc" {{ $sort == 'price-desc' ? 'selected' : '' }}>Price: High to
                                Low
                            </option>
                            <option value="name-asc" {{ $sort == 'name-asc' ? 'selected' : '' }}>Name: A-Z</option>
                            <option value="name-desc" {{ $sort == 'name-desc' ? 'selected' : '' }}>Name: Z-A</option>
                        </select>

                        <select class="sort-select" id="sortSelect" onchange="changePerPage(this.value)"
                            style="padding: 8px 12px; border-radius: 8px; border: 1px solid #e0e0e0; width: 120px;">
                            <option value="12" {{ $perPage == 12 ? 'selected' : '' }}>12 Products</option>
                            <option value="24" {{ $perPage == 24 ? 'selected' : '' }}>24 Products</option>
                            <option value="36" {{ $perPage == 36 ? 'selected' : '' }}>36 Products</option>
                            <option value="48" {{ $perPage == 48 ? 'selected' : '' }}>48 Products</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- PRODUCT GRID -->
            <div id="productGrid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:32px 20px;">
                @forelse ($products as $product)
                    <!-- Product Card -->
                    <div class="product-card product-item reveal"
                         data-cat="{{ $product->category ? $product->category->slug : 'uncategorized' }}"
                         data-price="{{ $product->productVariants->first()?->variant_price ?? ($product->product_price ?? 0) }}"
                         data-product-id="{{ $product->id }}">
                        <div class="product-img-wrap" style="aspect-ratio:3/4;">
                            <img src="https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=500&q=80"
                                alt="{{ $product->product_name }}">
                            <img class="hover-img"
                                src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&q=80"
                                alt="{{ $product->product_name }} alt">
                            <span class="product-badge badge-new">New</span>
                            <button class="wishlist-btn" aria-label="Wishlist" onclick="addToWishlist({{ $product->id }})">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                    stroke="#0A0A0A" stroke-width="1.5">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                                </svg>
                            </button>
                            <button class="product-quick-add"
                                onclick="addToCart(event,'{{ addslashes($product->product_name) }}', {{ $product->id }})">
                                Quick Add
                            </button>
                        </div>
                        <div class="product-meta">
                            <p class="product-name">
                                <a href="product.html"
                                    style="text-decoration:none;color:inherit;">{{ $product->product_name }}</a>
                            </p>
                            <p class="product-color">
                                @php
                                    $colors = $product->productVariants
                                        ->pluck('color.color_name')
                                        ->filter()
                                        ->unique()
                                        ->implode(' - ');
                                @endphp
                                {{ $colors ?: 'No color available' }}
                            </p>
                            <p class="product-price">
                                @php
                                    $firstVariant = $product->productVariants->first();
                                    $price = $firstVariant
                                        ? $firstVariant->variant_price
                                        : $product->product_price ?? 0;
                                @endphp
                                {{ number_format($price, 2) }} ج.م
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="no-products" style="text-align: center; padding: 60px 20px; grid-column: 1/-1;">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#999"
                            stroke-width="1.5">
                            <path
                                d="M20 7H4a2 2 0 00-2 2v9a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16" />
                        </svg>
                        <p style="margin-top: 16px;">لا توجد منتجات متاحة</p>
                    </div>
                @endforelse
            </div><!-- end grid -->

            <!-- PAGINATION -->
            @if ($products->hasPages())
                <div class="pagination-wrapper" style="text-align:center;padding-top:64px;">
                    <div style="display:flex;justify-content:center;align-items:center;gap:8px;flex-wrap:wrap;">
                        {{-- Previous Page Link --}}
                        @if ($products->onFirstPage())
                            <span class="pagination-item disabled" style="opacity:0.5;cursor:not-allowed;">‹
                                Previous</span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" class="pagination-item"
                                style="cursor:pointer;">‹ Previous</a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            @if ($page == $products->currentPage())
                                <span class="pagination-item active"
                                    style="background:#000;color:#fff;">{{ $page }}</span>
                            @elseif (
                                $page == 1 ||
                                    $page == $products->lastPage() ||
                                    ($page >= $products->currentPage() - 2 && $page <= $products->currentPage() + 2))
                                <a href="{{ $url }}" class="pagination-item">{{ $page }}</a>
                            @elseif ($page == $products->currentPage() - 3 || $page == $products->currentPage() + 3)
                                <span class="pagination-dots">...</span>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="pagination-item">Next ›</a>
                        @else
                            <span class="pagination-item disabled" style="opacity:0.5;cursor:not-allowed;">Next
                                ›</span>
                        @endif
                    </div>

                    <!-- Product count info -->
                    <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-top:24px;">
                        Displaying {{ $products->firstItem() ?? 0 }} – {{ $products->lastItem() ?? 0 }} of total
                        {{ $products->total() }} products
                    </p>
                </div>
            @endif

        </main>
    </div><!-- end layout -->

    <!-- ═══════════════════════════════════════════
     ADDED TO CART TOAST
═══════════════════════════════════════════ -->
    <div id="toast"
        style="position:fixed;bottom:32px;left:50%;transform:translateX(-50%) translateY(80px);background:#0A0A0A;color:#F8F6F2;font-family:'Space Grotesk',sans-serif;font-size:11px;letter-spacing:0.12em;text-transform:uppercase;padding:14px 28px;z-index:300;opacity:0;transition:all 0.4s cubic-bezier(0.25,0.46,0.45,0.94);white-space:nowrap;display:flex;align-items:center;gap:12px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="1.5">
            <polyline points="20 6 9 17 4 12" />
        </svg>
        <span id="toastMsg">Added to bag</span>
        <button onclick="toggleCart()"
            style="background:none;border:none;cursor:pointer;color:#9C9A96;font-size:10px;letter-spacing:0.1em;text-transform:uppercase;margin-left:8px;">View
            Bag</button>
    </div>

    {{-- Newslater --}}
    @include('partials.newslater')

    {{-- Footer --}}
    @include('partials.footer')

    <script src="{{ asset('js/website-shop.js') }}"></script>
</body>

</html>
