(function () {
    // ---------- IMAGES ----------
    const GALLERY_IMAGES = [
        "https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&q=85",
        "https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=900&q=85",
        "https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=900&q=85",
        "https://images.unsplash.com/photo-1529139574466-a303027c1d8b?w=900&q=85",
        "https://images.unsplash.com/photo-1578681994506-b8f463449011?w=900&q=85",
    ];
    let currentImageIdx = 0;
    const mainImg = document.getElementById("mainImg");
    const thumbs = document.querySelectorAll(".gallery-thumb");
    function updateGallery(index) {
        if (!mainImg) return;
        if (index === currentImageIdx) return;
        mainImg.classList.add("fade-out");
        setTimeout(() => {
            mainImg.src = GALLERY_IMAGES[index];
            mainImg.classList.remove("fade-out");
        }, 150);
        thumbs.forEach((t, i) => {
            t.classList.toggle("active", i === index);
        });
        currentImageIdx = index;
    }
    window.gallery = {
        next: (e) => {
            if (e) e.stopPropagation();
            updateGallery((currentImageIdx + 1) % GALLERY_IMAGES.length);
        },
        prev: (e) => {
            if (e) e.stopPropagation();
            updateGallery(
                (currentImageIdx - 1 + GALLERY_IMAGES.length) %
                    GALLERY_IMAGES.length,
            );
        },
        switch: (idx) => updateGallery(idx),
    };
    thumbs.forEach((thumb, idx) => {
        thumb.addEventListener("click", () => window.gallery.switch(idx));
    });

    // ---------- STATE ----------
    let state = {
        selectedColorId: window.VARIANTS?.[0]?.colorId ?? null,
        selectedSizeId: null,
        selectedVariant: null,
        qty: 1,
        wishlisted: false,
    };
    const formatEGP = (val) => "EGP " + Math.round(val).toLocaleString("en-EG");
    const variantsForColor = (colorId) =>
        window.VARIANTS.filter((v) => v.colorId == colorId);

    // DOM elements
    const priceEl = document.getElementById("currentPrice");
    const oldPriceEl = document.getElementById("oldPrice");
    const sizeBtnsContainer = document.getElementById("sizeBtns");
    const stockBadge = document.getElementById("stockBadge");
    const selectedColorLabel = document.getElementById("selectedColorLabel");
    const selectedSizeLabelSpan = document.getElementById("selectedSizeLabel");
    const qtyDisplaySpan = document.getElementById("qtyDisplay");
    const atcBtn = document.getElementById("atcBtn");
    const wishBtn = document.getElementById("wishBtn");
    const wishIcon = document.getElementById("wishIcon");
    const stickyATC = document.getElementById("stickyATC");
    const stickyPriceSpan = document.getElementById("stickyPrice");
    const toastEl = document.getElementById("toast");
    const cartBadge = document.getElementById("cartBadge");

    function updatePriceDisplay(price) {
        if (priceEl && oldPriceEl) {
            priceEl.textContent = formatEGP(price);
            oldPriceEl.textContent = formatEGP(price / 0.75);
            if (stickyPriceSpan) stickyPriceSpan.textContent = formatEGP(price);
        }
    }
    function updateStockDisplay(stock) {
        if (!stockBadge) return;
        if (stock <= 0) {
            stockBadge.textContent = "Out of stock";
            stockBadge.style.display = "block";
            stockBadge.style.color = "#C0392B";
        } else if (stock <= 5) {
            stockBadge.textContent = `Only ${stock} left`;
            stockBadge.style.display = "block";
            stockBadge.style.color = "#C0392B";
        } else {
            stockBadge.style.display = "none";
        }
    }
    function renderSizeButtons() {
        if (!sizeBtnsContainer) return;
        sizeBtnsContainer.innerHTML = "";
        const variants = variantsForColor(state.selectedColorId);
        const seen = new Set();
        variants.forEach((v) => {
            if (seen.has(v.sizeId)) return;
            seen.add(v.sizeId);
            const btn = document.createElement("button");
            btn.className = `size-btn ${v.stock <= 0 ? "sold-out" : ""}`;
            btn.textContent = v.sizeName;
            if (v.stock <= 0) btn.disabled = true;
            btn.addEventListener("click", () => {
                if (v.stock <= 0) return;
                state.selectedSizeId = v.sizeId;
                state.selectedVariant = v;
                document
                    .querySelectorAll(".size-btn")
                    .forEach((b) => b.classList.remove("active"));
                btn.classList.add("active");
                if (selectedSizeLabelSpan) {
                    selectedSizeLabelSpan.textContent = v.sizeName;
                    selectedSizeLabelSpan.style.color = "#0A0A0A";
                }
                updatePriceDisplay(v.price);
                updateStockDisplay(v.stock);
            });
            sizeBtnsContainer.appendChild(btn);
        });
    }
    window.selectColor = (colorId, colorName) => {
        state.selectedColorId = colorId;
        state.selectedSizeId = null;
        state.selectedVariant = null;
        document.querySelectorAll(".color-swatch-opt").forEach((el) => {
            el.classList.toggle(
                "active",
                parseInt(el.dataset.colorId) === colorId,
            );
        });
        if (selectedColorLabel) selectedColorLabel.textContent = colorName;
        if (selectedSizeLabelSpan) {
            selectedSizeLabelSpan.textContent = "— Select a size";
            selectedSizeLabelSpan.style.color = "#9C9A96";
        }
        renderSizeButtons();
        const first = variantsForColor(colorId)[0];
        if (first) updatePriceDisplay(first.price);
    };
    document.querySelectorAll(".color-swatch-opt").forEach((sw) => {
        const cid = parseInt(sw.dataset.colorId);
        const cname = sw.getAttribute("title");
        sw.addEventListener("click", () => window.selectColor(cid, cname));
    });

    // Replace your existing window.cart object with this complete version
    window.cart = {
        changeQty: (delta) => {
            // Get current stock from selected variant
            let maxStock = state.selectedVariant
                ? state.selectedVariant.stock
                : 10;
            let max = Math.min(maxStock, 10); // Limit to 10 per transaction
            let newQty = state.qty + delta;

            if (newQty < 1) newQty = 1;
            if (newQty > max) newQty = max;

            state.qty = newQty;
            if (qtyDisplaySpan) qtyDisplaySpan.textContent = state.qty;

            // Show warning if trying to exceed stock
            if (newQty === max && delta > 0 && maxStock <= 10) {
                const stockWarning = document.getElementById("stockWarning");
                if (!stockWarning) {
                    const warning = document.createElement("p");
                    warning.id = "stockWarning";
                    warning.style.cssText =
                        'font-family: "DM Sans", sans-serif; font-size: 11px; color: #C0392B; margin-top: 8px;';
                    warning.textContent = `Only ${maxStock} items available in stock`;
                    document
                        .querySelector(".qty-btn")
                        ?.parentElement?.parentElement?.appendChild(warning);
                    setTimeout(() => warning.remove(), 3000);
                }
            }
        },

        add: () => {
            if (!state.selectedVariant) {
                document
                    .querySelectorAll(".size-btn:not(.sold-out)")
                    .forEach((btn) => {
                        btn.style.borderColor = "#C0392B";
                        setTimeout(() => {
                            if (!btn.classList.contains("active"))
                                btn.style.borderColor = "";
                        }, 1200);
                    });
                return;
            }

            // Check inventory before adding
            const availableStock = state.selectedVariant.stock;

            if (availableStock <= 0) {
                // Show out of stock error
                const errorDiv = document.createElement("p");
                errorDiv.style.cssText =
                    'font-family: "DM Sans", sans-serif; font-size: 12px; color: #C0392B; margin-top: 8px; text-align: center;';
                errorDiv.textContent = "Out of stock - cannot add to cart";
                document
                    .querySelector(".atc-btn")
                    ?.parentElement?.appendChild(errorDiv);
                setTimeout(() => errorDiv.remove(), 3000);
                return;
            }

            // Get existing cart items from localStorage
            let cartItems = JSON.parse(
                localStorage.getItem("ryo_cart") || "[]",
            );

            // Check if item already exists in cart
            const existingIndex = cartItems.findIndex(
                (item) => item.variantId === state.selectedVariant.id,
            );

            let currentQtyInCart = 0;
            if (existingIndex !== -1) {
                currentQtyInCart = cartItems[existingIndex].quantity;
            }

            // Calculate total requested quantity
            const requestedQty = currentQtyInCart + state.qty;

            // Check if requested quantity exceeds available stock
            if (requestedQty > availableStock) {
                const maxAllowed = availableStock - currentQtyInCart;
                const errorMsg =
                    maxAllowed <= 0
                        ? `Cannot add more. Only ${availableStock} available in stock (${currentQtyInCart} already in cart)`
                        : `Only ${maxAllowed} more can be added (${availableStock} total available, ${currentQtyInCart} already in cart)`;

                const errorDiv = document.createElement("p");
                errorDiv.style.cssText =
                    'font-family: "DM Sans", sans-serif; font-size: 11px; color: #C0392B; margin-top: 8px; text-align: center;';
                errorDiv.textContent = errorMsg;
                document
                    .querySelector(".atc-btn")
                    ?.parentElement?.appendChild(errorDiv);
                setTimeout(() => errorDiv.remove(), 4000);
                return;
            }

            if (existingIndex !== -1) {
                // Update quantity if already in cart (respecting stock)
                const newQty = cartItems[existingIndex].quantity + state.qty;
                if (newQty <= availableStock) {
                    cartItems[existingIndex].quantity = newQty;
                } else {
                    cartItems[existingIndex].quantity = availableStock;
                }
            } else {
                // Add new item
                cartItems.push({
                    variantId: state.selectedVariant.id,
                    productName:
                        document.querySelector("h1")?.textContent || "",
                    colorName: state.selectedVariant.colorName,
                    sizeName: state.selectedVariant.sizeName,
                    price: state.selectedVariant.price,
                    quantity: Math.min(state.qty, availableStock),
                    imageUrl: GALLERY_IMAGES[0],
                    sku: state.selectedVariant.sku || "",
                });
            }

            // Save to localStorage
            localStorage.setItem("ryo_cart", JSON.stringify(cartItems));

            // Update cart UI
            window.cart.updateCartDrawer();

            // Update cart badge
            const totalItems = cartItems.reduce(
                (sum, item) => sum + item.quantity,
                0,
            );
            if (cartBadge) {
                cartBadge.style.display = totalItems > 0 ? "flex" : "none";
                cartBadge.textContent = totalItems;
            }

            // Show success toast
            if (toastEl) {
                toastEl.style.opacity = "1";
                toastEl.style.transform = "translateX(-50%) translateY(0)";
                setTimeout(() => {
                    toastEl.style.opacity = "0";
                    toastEl.style.transform =
                        "translateX(-50%) translateY(80px)";
                }, 3000);
            }

            // Button feedback
            if (atcBtn) {
                const orig = atcBtn.innerHTML;
                atcBtn.innerHTML = "Added to Bag ✓";
                atcBtn.style.background = "#2C5F2D";
                setTimeout(() => {
                    atcBtn.innerHTML = orig;
                    atcBtn.style.background = "";
                }, 2000);
            }

            // Reset quantity display to 1
            state.qty = 1;
            if (qtyDisplaySpan) qtyDisplaySpan.textContent = "1";
        },

        updateCartDrawer: () => {
            const cartItems = JSON.parse(
                localStorage.getItem("ryo_cart") || "[]",
            );
            const cartItemsContainer =
                document.getElementById("cartItemsContainer");
            const cartDrawerSubtotal =
                document.getElementById("cartDrawerSubtotal");
            const cartDrawerCount = document.getElementById("cartDrawerCount");

            if (!cartItemsContainer) return;

            if (cartItems.length === 0) {
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
                if (cartDrawerSubtotal)
                    cartDrawerSubtotal.textContent = "EGP 0";
                if (cartDrawerCount) cartDrawerCount.textContent = "0";
                return;
            }

            let subtotal = 0;
            let itemCount = 0;

            cartItemsContainer.innerHTML = cartItems
                .map((item, idx) => {
                    const itemTotal = item.price * item.quantity;
                    subtotal += itemTotal;
                    itemCount += item.quantity;

                    // Get current stock for this variant from original data
                    const variantStock =
                        window.VARIANTS?.find((v) => v.id === item.variantId)
                            ?.stock || 999;
                    const isMaxStock = item.quantity >= variantStock;

                    return `
                <div class="flex gap-4 pb-6 mb-6" style="border-bottom: 1px solid #EDEDEB;">
                    <div style="width: 80px; height: 100px; background: #EDEDEB; flex-shrink: 0; overflow: hidden;">
                        <img src="${item.imageUrl}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div style="flex: 1;">
                        <p style="font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500; margin-bottom: 4px;">${item.productName}</p>
                        <p style="font-family: 'DM Sans', sans-serif; font-size: 12px; color: #9C9A96; margin-bottom: 2px;">Size: ${item.sizeName}</p>
                        <p style="font-family: 'DM Sans', sans-serif; font-size: 12px; color: #9C9A96; margin-bottom: 16px;">${item.colorName}</p>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 12px; border: 1px solid #EDEDEB; padding: 6px 14px;">
                                <button onclick="window.cart.updateQuantity(${idx}, -1)" style="background: none; border: none; cursor: pointer; font-size: 16px; color: #9C9A96;">−</button>
                                <span style="font-size: 13px; font-family: 'DM Sans', sans-serif;">${item.quantity}</span>
                                <button onclick="window.cart.updateQuantity(${idx}, 1)" style="background: none; border: none; cursor: pointer; font-size: 16px; ${isMaxStock ? "opacity: 0.5; cursor: not-allowed;" : ""}" ${isMaxStock ? "disabled" : ""}>+</button>
                            </div>
                            <span style="font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500;">${formatEGP(itemTotal)}</span>
                        </div>
                        ${variantStock <= 5 ? `<p style="font-family: 'DM Sans', sans-serif; font-size: 10px; color: #C0392B; margin-top: 6px;">Only ${variantStock} left in stock</p>` : ""}
                        <button onclick="window.cart.removeItem(${idx})" style="background: none; border: none; cursor: pointer; font-family: 'Space Grotesk', sans-serif; font-size: 9px; letter-spacing: 0.1em; text-transform: uppercase; color: #C0392B; margin-top: 12px; padding: 0;">Remove</button>
                    </div>
                </div>
            `;
                })
                .join("");

            if (cartDrawerSubtotal)
                cartDrawerSubtotal.textContent = formatEGP(subtotal);
            if (cartDrawerCount) cartDrawerCount.textContent = itemCount;

            // Update badge
            if (cartBadge) {
                cartBadge.style.display = itemCount > 0 ? "flex" : "none";
                cartBadge.textContent = itemCount;
            }
        },

        updateQuantity: (index, delta) => {
            let cartItems = JSON.parse(
                localStorage.getItem("ryo_cart") || "[]",
            );
            if (!cartItems[index]) return;

            const variant = window.VARIANTS?.find(
                (v) => v.id === cartItems[index].variantId,
            );
            const maxStock = variant?.stock || 999;

            const newQuantity = cartItems[index].quantity + delta;

            if (newQuantity < 1) {
                cartItems.splice(index, 1);
            } else if (newQuantity > maxStock) {
                // Show error if exceeding stock
                const errorDiv = document.createElement("p");
                errorDiv.style.cssText =
                    'font-family: "DM Sans", sans-serif; font-size: 11px; color: #C0392B; margin-top: 8px;';
                errorDiv.textContent = `Cannot exceed available stock (${maxStock} items)`;
                document
                    .getElementById("cartItemsContainer")
                    ?.appendChild(errorDiv);
                setTimeout(() => errorDiv.remove(), 3000);
                return;
            } else {
                cartItems[index].quantity = newQuantity;
            }

            localStorage.setItem("ryo_cart", JSON.stringify(cartItems));
            window.cart.updateCartDrawer();
        },

        removeItem: (index) => {
            let cartItems = JSON.parse(
                localStorage.getItem("ryo_cart") || "[]",
            );
            cartItems.splice(index, 1);
            localStorage.setItem("ryo_cart", JSON.stringify(cartItems));
            window.cart.updateCartDrawer();
        },

        toggle: () => {
            const drawer = document.getElementById("cartDrawer");
            const overlay = document.getElementById("cartOverlay");
            drawer?.classList.toggle("open");
            overlay?.classList.toggle("open");
            document.body.style.overflow = drawer?.classList.contains("open")
                ? "hidden"
                : "";
        },
    };

    // Initialize cart drawer on page load and sync stock
    document.addEventListener("DOMContentLoaded", () => {
        window.cart.updateCartDrawer();

        // Optional: Clear expired cart items if stock becomes 0
        const cartItems = JSON.parse(localStorage.getItem("ryo_cart") || "[]");
        let hasChanges = false;

        cartItems.forEach((item, idx) => {
            const variant = window.VARIANTS?.find(
                (v) => v.id === item.variantId,
            );
            if (variant && variant.stock <= 0) {
                cartItems.splice(idx, 1);
                hasChanges = true;
            } else if (variant && item.quantity > variant.stock) {
                item.quantity = variant.stock;
                hasChanges = true;
            }
        });

        if (hasChanges) {
            localStorage.setItem("ryo_cart", JSON.stringify(cartItems));
            window.cart.updateCartDrawer();
        }
    });

    // Wishlist
    window.wishlist = {
        toggle: () => {
            state.wishlisted = !state.wishlisted;
            wishBtn?.classList.toggle("active", state.wishlisted);
            if (wishIcon)
                wishIcon.style.fill = state.wishlisted ? "#0A0A0A" : "none";
        },
    };

    // Size Guide Modal
    const modal = document.getElementById("sizeModal");
    const modalInner = document.getElementById("sizeModalInner");
    window.sizeGuide = {
        open: () => {
            if (modal && modalInner) {
                modal.style.opacity = "1";
                modal.style.pointerEvents = "all";
                modalInner.style.transform = "translateY(0)";
                document.body.style.overflow = "hidden";
            }
        },
        close: () => {
            if (modal && modalInner) {
                modal.style.opacity = "0";
                modal.style.pointerEvents = "none";
                modalInner.style.transform = "translateY(40px)";
                document.body.style.overflow = "";
            }
        },
    };
    if (modal)
        modal.addEventListener("click", (e) => {
            if (e.target === modal) window.sizeGuide.close();
        });

    // Accordion
    document.querySelectorAll(".accordion-item").forEach((item) => {
        const header = item.querySelector(".accordion-header");
        const body = item.querySelector(".accordion-body");
        if (item.classList.contains("open") && body)
            body.style.maxHeight = body.scrollHeight + "px";
        header?.addEventListener("click", () => {
            const isOpen = item.classList.contains("open");
            document.querySelectorAll(".accordion-item").forEach((acc) => {
                acc.classList.remove("open");
                const b = acc.querySelector(".accordion-body");
                if (b) b.style.maxHeight = null;
            });
            if (!isOpen) {
                item.classList.add("open");
                if (body) body.style.maxHeight = body.scrollHeight + "px";
            }
        });
    });

    // Sticky ATC observer
    if (atcBtn && stickyATC) {
        const observer = new IntersectionObserver(
            ([entry]) => {
                stickyATC.classList.toggle("visible", !entry.isIntersecting);
            },
            { threshold: 0 },
        );
        observer.observe(atcBtn);
    }

    // Responsive grid
    const layoutDiv = document.getElementById("pdpLayout");
    const relatedGrid = document.getElementById("relatedGrid");
    function handleResize() {
        if (layoutDiv)
            layoutDiv.style.gridTemplateColumns =
                window.innerWidth < 768 ? "1fr" : "1fr 1fr";
        if (relatedGrid)
            relatedGrid.style.gridTemplateColumns =
                window.innerWidth < 768 ? "repeat(2,1fr)" : "repeat(4,1fr)";
    }
    window.addEventListener("resize", handleResize);

    // Scroll reveal
    const revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) {
                    e.target.classList.add("visible");
                    revealObserver.unobserve(e.target);
                }
            });
        },
        { threshold: 0.1 },
    );
    document
        .querySelectorAll(".reveal")
        .forEach((el) => revealObserver.observe(el));

    // Initialize
    renderSizeButtons();
    const initVariant = window.VARIANTS[0];
    if (initVariant) updatePriceDisplay(initVariant.price);
    if (selectedColorLabel && initVariant)
        selectedColorLabel.textContent = initVariant.colorName;
    handleResize();
})();

