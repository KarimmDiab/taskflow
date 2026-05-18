    // ═══════════════════════════════════════════════════════════════
    // CART FUNCTIONS
    // ═══════════════════════════════════════════════════════════════

    // Load cart from localStorage
    function loadCart() {
        const savedCart = localStorage.getItem('ryo_cart');
        if (savedCart) {
            return JSON.parse(savedCart);
        }
        return [];
    }

    // Save cart to localStorage
    function saveCart(cart) {
        localStorage.setItem('ryo_cart', JSON.stringify(cart));
        updateCartBadge(cart);
        updateCartDrawer(cart);
    }

    // Update cart badge in navbar
    function updateCartBadge(cart) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        const cartBadge = document.getElementById('cartBadge');
        if (cartBadge) {
            if (totalItems > 0) {
                cartBadge.style.display = 'flex';
                cartBadge.textContent = totalItems;
            } else {
                cartBadge.style.display = 'none';
            }
        }
    }

    // Format EGP currency
    function formatEGP(value) {
        return 'EGP ' + Math.round(value).toLocaleString('en-EG');
    }

    // Escape HTML to prevent XSS
    function escapeHtml(str) {
        if (!str) return '';
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    // Update cart drawer UI
    function updateCartDrawer(cart) {
        const cartItemsContainer = document.getElementById('cartItemsContainer');
        const cartDrawerSubtotal = document.getElementById('cartDrawerSubtotal');
        const cartDrawerCount = document.getElementById('cartDrawerCount');

        if (!cartItemsContainer) return;

        if (!cart || cart.length === 0) {
            cartItemsContainer.innerHTML = `
                <div style="text-align: center; padding: 48px 24px;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#9C9A96" stroke-width="1.2">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 01-8 0" />
                    </svg>
                    <p style="font-family: 'DM Sans', sans-serif; font-size: 14px; color: #9C9A96; margin-top: 16px;">Your bag is empty</p>
                </div>
            `;
            if (cartDrawerSubtotal) cartDrawerSubtotal.textContent = 'EGP 0';
            if (cartDrawerCount) cartDrawerCount.textContent = '0';
            return;
        }

        let subtotal = 0;
        let itemCount = 0;

        cartItemsContainer.innerHTML = cart.map((item, idx) => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            itemCount += item.quantity;

            return `
                <div style="display: flex; gap: 16px; padding-bottom: 20px; margin-bottom: 20px; border-bottom: 1px solid #EDEDEB;">
                    <div style="width: 80px; height: 100px; background: #EDEDEB; flex-shrink: 0; overflow: hidden; border-radius: 4px;">
                        <img src="${item.imageUrl}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22130%22 height=%22173%22><rect width=%22100%25%22 height=%22100%25%22 fill=%22%23ddd%22/><text x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%23666%22>No Image</text></svg>'">
                    </div>
                    <div style="flex: 1;">
                        <p style="font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500; margin-bottom: 6px;">${escapeHtml(item.productName)}</p>
                        <p style="font-family: 'DM Sans', sans-serif; font-size: 11px; color: #9C9A96; margin-bottom: 4px;">${escapeHtml(item.colorName || 'Default')}</p>
                        <p style="font-family: 'DM Sans', sans-serif; font-size: 11px; color: #9C9A96; margin-bottom: 12px;">Size: ${escapeHtml(item.sizeName || 'OS')}</p>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 12px; border: 1px solid #EDEDEB; padding: 4px 12px;">
                                <button onclick="updateCartItemQuantity(${idx}, -1)" style="background: none; border: none; cursor: pointer; font-size: 14px; color: #9C9A96;">−</button>
                                <span style="font-size: 12px; font-family: 'DM Sans', sans-serif;">${item.quantity}</span>
                                <button onclick="updateCartItemQuantity(${idx}, 1)" style="background: none; border: none; cursor: pointer; font-size: 14px;">+</button>
                            </div>
                            <span style="font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500;">${formatEGP(itemTotal)}</span>
                        </div>
                        <button onclick="removeCartItem(${idx})" style="background: none; border: none; cursor: pointer; font-family: 'Space Grotesk', sans-serif; font-size: 9px; letter-spacing: 0.1em; text-transform: uppercase; color: #C0392B; margin-top: 12px; padding: 0;">Remove</button>
                    </div>
                </div>
            `;
        }).join('');

        if (cartDrawerSubtotal) cartDrawerSubtotal.textContent = formatEGP(subtotal);
        if (cartDrawerCount) cartDrawerCount.textContent = itemCount;
    }

    // Update cart item quantity
    function updateCartItemQuantity(index, delta) {
        const cart = loadCart();
        if (!cart[index]) return;

        const newQty = cart[index].quantity + delta;
        if (newQty < 1) {
            cart.splice(index, 1);
        } else {
            cart[index].quantity = newQty;
        }

        saveCart(cart);
    }

    // Remove cart item
    function removeCartItem(index) {
        const cart = loadCart();
        cart.splice(index, 1);
        saveCart(cart);
        showToastMessage('Item removed from bag');
    }

    // Show toast message
    function showToastMessage(message) {
        const toast = document.getElementById("toast");
        const toastMsg = document.getElementById("toastMsg");
        if (toastMsg) toastMsg.textContent = message;
        if (toast) {
            toast.style.opacity = "1";
            toast.style.transform = "translateX(-50%) translateY(0)";
            setTimeout(() => {
                toast.style.opacity = "0";
                toast.style.transform = "translateX(-50%) translateY(80px)";
            }, 3000);
        }
    }

    // ── ADD TO CART (Main function) ──
    function addToCart(event, productName, productId, productPrice = null, productImage = null, colorName = null, sizeName = null) {
        if (event) event.stopPropagation();

        let price = productPrice;
        let image = productImage;
        let color = colorName;
        let size = sizeName;

        // Try to get from card if not provided
        const card = event?.target?.closest('.product-card');
        if (card) {
            if (!price) {
                const priceEl = card.querySelector('.product-price');
                if (priceEl) {
                    const priceText = priceEl.textContent.replace('EGP', '').replace(',', '').trim();
                    price = parseInt(priceText);
                }
            }
            if (!image) {
                const imgEl = card.querySelector('img');
                if (imgEl) image = imgEl.src;
            }
        }

        // Default values if still missing
        if (!price) price = 1000;
        if (!image) image = 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=300&q=80';
        if (!color) color = 'Default';
        if (!size) size = 'M';

        // Create cart item
        const cartItem = {
            variantId: productId,
            productName: productName,
            colorName: color,
            sizeName: size,
            price: price,
            quantity: 1,
            imageUrl: image,
            addedAt: Date.now()
        };

        // Load existing cart
        const cart = loadCart();

        // Check if item already exists
        const existingIndex = cart.findIndex(item =>
            item.variantId === productId &&
            item.sizeName === size &&
            item.colorName === color
        );

        if (existingIndex !== -1) {
            cart[existingIndex].quantity += 1;
            showToastMessage(productName + ' quantity updated');
        } else {
            cart.push(cartItem);
            showToastMessage(productName + ' added to bag');
        }

        // Save and update UI
        saveCart(cart);

        console.log('Added to cart:', productName, 'ID:', productId, cart);
    }

    // Quick add function for products with data attributes
    function quickAddToCart(btn) {
        const productId = parseInt(btn.dataset.productId);
        const productName = btn.dataset.productName;
        const productPrice = parseInt(btn.dataset.productPrice);
        const productImage = btn.dataset.productImage;
        const productColor = btn.dataset.productColor || 'Default';
        const productSize = btn.dataset.productSize || 'M';

        addToCart(event, productName, productId, productPrice, productImage, productColor, productSize);
    }

    // ── TOGGLE CART DRAWER ──
    function toggleCart() {
        const cartDrawer = document.getElementById("cartDrawer");
        const cartOverlay = document.getElementById("cartOverlay");
        if (cartDrawer) cartDrawer.classList.toggle("open");
        if (cartOverlay) cartOverlay.classList.toggle("open");
        document.body.style.overflow = cartDrawer && cartDrawer.classList.contains("open") ? "hidden" : "";

        // Refresh cart drawer when opened
        if (cartDrawer && cartDrawer.classList.contains("open")) {
            const cart = loadCart();
            updateCartDrawer(cart);
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // MENU FUNCTIONS
    // ═══════════════════════════════════════════════════════════════

    function toggleMenu() {
        const mobileMenu = document.getElementById("mobileMenu");
        if (mobileMenu) mobileMenu.classList.toggle("open");
    }

    // ═══════════════════════════════════════════════════════════════
    // CATEGORY FILTER FUNCTIONS
    // ═══════════════════════════════════════════════════════════════

    function setCategory(cat) {
        const url = new URL(window.location.href);
        if (cat === 'all') {
            url.searchParams.delete('category');
        } else {
            url.searchParams.set('category', cat);
        }
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    }

    // ═══════════════════════════════════════════════════════════════
    // GRID COLUMN FUNCTIONS
    // ═══════════════════════════════════════════════════════════════

    function setGrid(cols, btn) {
        document.querySelectorAll(".grid-btn").forEach((b) => b.classList.remove("active"));
        btn.classList.add("active");
        const productGrid = document.getElementById("productGrid");
        if (productGrid) {
            productGrid.style.gridTemplateColumns = `repeat(${cols},1fr)`;
        }
        localStorage.setItem('gridColumns', cols);
    }

    // ═══════════════════════════════════════════════════════════════
    // SORT FUNCTIONS
    // ═══════════════════════════════════════════════════════════════

    function sortProducts(val) {
        const url = new URL(window.location.href);
        if (val) {
            url.searchParams.set('sort', val);
        } else {
            url.searchParams.delete('sort');
        }
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    }

    // ═══════════════════════════════════════════════════════════════
    // WISHLIST FUNCTIONS
    // ═══════════════════════════════════════════════════════════════

    function addToWishlist(productId) {
        console.log('Added to wishlist:', productId);
        showToastMessage('Added to wishlist');
    }

    // ═══════════════════════════════════════════════════════════════
    // PAGINATION FUNCTIONS
    // ═══════════════════════════════════════════════════════════════

    function changePerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    }

    function goToPage(page) {
        const url = new URL(window.location.href);
        url.searchParams.set('page', page);
        window.location.href = url.toString();
    }

    // ═══════════════════════════════════════════════════════════════
    // SCROLL REVEAL
    // ═══════════════════════════════════════════════════════════════

    const revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("visible");
                    revealObserver.unobserve(entry.target);
                }
            });
        },
        {
            threshold: 0.08,
            rootMargin: "0px 0px -40px 0px",
        }
    );

    // ═══════════════════════════════════════════════════════════════
    // RESPONSIVE GRID
    // ═══════════════════════════════════════════════════════════════

    function checkGrid() {
        const grid = document.getElementById("productGrid");
        if (!grid) return;

        if (window.innerWidth < 640) {
            grid.style.gridTemplateColumns = "repeat(2,1fr)";
        } else if (window.innerWidth < 900) {
            const savedColumns = localStorage.getItem('gridColumns');
            if (!savedColumns || savedColumns > 3) {
                grid.style.gridTemplateColumns = "repeat(3,1fr)";
            }
        } else {
            const savedColumns = localStorage.getItem('gridColumns');
            if (savedColumns) {
                grid.style.gridTemplateColumns = `repeat(${savedColumns}, 1fr)`;
            }
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // PAGE INITIALIZATION
    // ═══════════════════════════════════════════════════════════════

    document.addEventListener("DOMContentLoaded", function () {
        // Initialize cart
        const cart = loadCart();
        updateCartBadge(cart);
        updateCartDrawer(cart);

        // Initialize scroll reveal
        document.querySelectorAll(".reveal").forEach((el) => revealObserver.observe(el));

        // Load saved grid preference
        const savedColumns = localStorage.getItem('gridColumns');
        if (savedColumns) {
            const grid = document.getElementById('productGrid');
            if (grid) {
                grid.style.gridTemplateColumns = `repeat(${savedColumns}, 1fr)`;
                const activeBtn = document.querySelector(`.grid-btn[data-cols="${savedColumns}"]`);
                if (activeBtn) {
                    document.querySelectorAll('.grid-btn').forEach(b => b.classList.remove('active'));
                    activeBtn.classList.add('active');
                }
            }
        }

        // Initialize responsive grid
        checkGrid();

        // Update active state for filter buttons based on URL
        const urlParams = new URLSearchParams(window.location.search);
        const currentCategory = urlParams.get('category') || 'all';

        document.querySelectorAll('.filter-btn').forEach(btn => {
            const btnCat = btn.getAttribute('data-cat');
            if (btnCat === currentCategory) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    });

    // Window resize event
    window.addEventListener("resize", checkGrid);

    // Make functions globally available
    window.toggleCart = toggleCart;
    window.toggleMenu = toggleMenu;
    window.setCategory = setCategory;
    window.setGrid = setGrid;
    window.sortProducts = sortProducts;
    window.addToCart = addToCart;
    window.quickAddToCart = quickAddToCart;
    window.addToWishlist = addToWishlist;
    window.changePerPage = changePerPage;
    window.goToPage = goToPage;
    window.updateCartItemQuantity = updateCartItemQuantity;
    window.removeCartItem = removeCartItem;
