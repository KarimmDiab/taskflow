(function () {
    const dataEl = document.getElementById("product-data");
    if (!dataEl) return;

    const mainImg = document.getElementById("mainImg");
    const thumbsContainer = document.getElementById("thumbs");
    const sizeBtnsContainer = document.getElementById("sizeBtns");
    const selectedColorLabel = document.getElementById("selectedColorLabel");
    const selectedSizeLabel = document.getElementById("selectedSizeLabel");
    const currentPriceSpan = document.getElementById("currentPrice");
    const oldPriceSpan = document.getElementById("oldPrice");
    const stickyPriceSpan = document.getElementById("stickyPrice");
    const stockBadge = document.getElementById("stockBadge");
    const sizeError = document.getElementById("sizeError");
    const qtyDisplay = document.getElementById("qtyDisplay");
    const atcBtn = document.getElementById("atcBtn");
    const toast = document.getElementById("toast");
    const cartBadge = document.getElementById("cartBadge");
    const productName = () =>
        document.querySelector("h1")?.textContent?.trim() || "Product";

    let productData = {};
    try {
        productData = JSON.parse(dataEl.textContent || "{}");
    } catch (error) {
        console.error("Failed to parse product data JSON:", error);
    }

    window.VARIANTS = productData.variants || [];
    window.CHECKOUT_URL = productData.checkoutUrl || "/ryo-checkout";
    window.productImages = productData.productImages || {
        all: [],
        byColor: {},
        placeholderImage: "/images/placeholder.jpg",
    };

    const formatEGP = (value) =>
        "EGP " + Math.round(Number(value) || 0).toLocaleString("en-EG");

    const state = {
        images: [],
        imageIndex: 0,
        selectedVariant: null,
        qty: 1,
        lightbox: null,
        zoom: 1,
        panX: 0,
        panY: 0,
    };

    function imageFallback() {
        return (
            window.productImages.placeholderImage || "/images/placeholder.jpg"
        );
    }

    function colorKey(colorId) {
        return colorId == null || colorId === "" ? "default" : String(colorId);
    }

    function imagesForColor(colorId) {
        const byColor = window.productImages.byColor || {};
        const allImages = window.productImages.all || [];
        const key = colorKey(colorId);
        const colorImages = byColor[key] || [];
        const images = colorImages.length ? colorImages : allImages;
        return images.length ? [...new Set(images)] : [imageFallback()];
    }

    function currentImage() {
        return state.images[state.imageIndex] || imageFallback();
    }

    function updateNavState() {
        const disabled = state.images.length <= 1;
        document.querySelectorAll("[data-gallery-nav]").forEach((button) => {
            button.disabled = disabled;
            button.style.opacity = disabled ? "0.45" : "1";
            button.style.cursor = disabled ? "not-allowed" : "pointer";
        });
        document.querySelectorAll("[data-lightbox-nav]").forEach((button) => {
            button.disabled = disabled;
            button.style.opacity = disabled ? "0.45" : "1";
            button.style.cursor = disabled ? "not-allowed" : "pointer";
        });
    }

    function setMainImage(index) {
        if (!mainImg || !state.images.length) return;
        const nextIndex = (index + state.images.length) % state.images.length;
        state.imageIndex = nextIndex;
        mainImg.classList.add("fade-out");
        setTimeout(() => {
            mainImg.src = currentImage();
            mainImg.alt = `${productName()} photo ${nextIndex + 1}`;
            mainImg.classList.remove("fade-out");
        }, 120);

        document.querySelectorAll(".gallery-thumb").forEach((thumb, idx) => {
            thumb.classList.toggle("active", idx === nextIndex);
        });

        if (state.lightbox?.img) {
            state.lightbox.img.src = currentImage();
            resetZoom();
        }
        updateNavState();
    }

    function renderThumbnails(images) {
        if (!thumbsContainer) return;
        thumbsContainer.innerHTML = images
            .map(
                (url, idx) => `
                    <button type="button" class="gallery-thumb ${idx === 0 ? "active" : ""}" data-index="${idx}" aria-label="Show product photo ${idx + 1}">
                        <img src="${url}" alt="Product thumbnail ${idx + 1}">
                    </button>
                `,
            )
            .join("");

        thumbsContainer.querySelectorAll(".gallery-thumb").forEach((thumb) => {
            thumb.addEventListener("click", () => {
                setMainImage(Number(thumb.dataset.index));
            });
        });
    }

    function updateGalleryByColor(colorId) {
        state.images = imagesForColor(colorId);
        state.imageIndex = 0;
        renderThumbnails(state.images);
        setMainImage(0);
    }

    function variantsForColor(colorId) {
        const key = colorKey(colorId);
        return window.VARIANTS.filter(
            (variant) => colorKey(variant.colorId) === key,
        );
    }

    function updatePrice(price) {
        if (currentPriceSpan) currentPriceSpan.textContent = formatEGP(price);
        if (oldPriceSpan)
            oldPriceSpan.textContent = formatEGP((Number(price) || 0) / 0.75);
        if (stickyPriceSpan) stickyPriceSpan.textContent = formatEGP(price);
    }

    function updateStockBadge(stock) {
        if (!stockBadge) return;
        stockBadge.style.display = "block";
        if (stock <= 0) {
            stockBadge.textContent = "Out of stock";
            stockBadge.style.color = "#C0392B";
        } else if (stock <= 5) {
            stockBadge.textContent = `Only ${stock} left`;
            stockBadge.style.color = "#C0392B";
        } else {
            stockBadge.style.display = "none";
        }
    }

    function hideSizeError() {
        if (sizeError) sizeError.style.display = "none";
    }

    function renderSizeButtons(colorId) {
        if (!sizeBtnsContainer) return;
        const variants = variantsForColor(colorId);
        const seen = new Set();
        const buttons = [];

        variants.forEach((variant) => {
            if (seen.has(variant.sizeId)) return;
            seen.add(variant.sizeId);
            const soldOut = variant.stock <= 0;
            buttons.push(`
                <button type="button"
                    class="size-btn ${soldOut ? "sold-out" : ""}"
                    data-variant-id="${variant.id}"
                    ${soldOut ? "disabled" : ""}>
                    ${variant.sizeName || "OS"}
                </button>
            `);
        });

        sizeBtnsContainer.innerHTML = buttons.length
            ? buttons.join("")
            : "<p style=\"font-family:'DM Sans',sans-serif;font-size:13px;color:#9C9A96;\">No sizes available</p>";

        sizeBtnsContainer
            .querySelectorAll(".size-btn:not(.sold-out)")
            .forEach((button) => {
                button.addEventListener("click", () => {
                    const variant = window.VARIANTS.find(
                        (item) => item.id == button.dataset.variantId,
                    );
                    if (variant) selectSize(button, variant);
                });
            });
    }

    function selectSize(button, variant) {
        state.selectedVariant = variant;
        document
            .querySelectorAll(".size-btn")
            .forEach((item) => item.classList.remove("active"));
        button.classList.add("active");
        if (selectedSizeLabel) {
            selectedSizeLabel.textContent = variant.sizeName || "OS";
            selectedSizeLabel.style.color = "#0A0A0A";
        }
        updatePrice(variant.price);
        updateStockBadge(variant.stock);
        hideSizeError();
    }

    function selectColor(element, colorId) {
        state.selectedVariant = null;

        document.querySelectorAll(".color-swatch-opt").forEach((swatch) => {
            swatch.classList.toggle(
                "active",
                swatch === element || swatch.dataset.colorId == colorId,
            );
        });

        const colorName = element?.getAttribute("title") || "";
        if (selectedColorLabel) selectedColorLabel.textContent = colorName;
        if (selectedSizeLabel) {
            selectedSizeLabel.textContent = "- Select a size";
            selectedSizeLabel.style.color = "#9C9A96";
        }

        updateGalleryByColor(colorId);
        renderSizeButtons(colorId);
        const firstVariant = variantsForColor(colorId)[0];
        if (firstVariant) updatePrice(firstVariant.price);
        hideSizeError();
    }

    function resetZoom() {
        state.zoom = 1;
        state.panX = 0;
        state.panY = 0;
        if (!state.lightbox?.img) return;
        state.lightbox.img.style.transform = "translate(0px, 0px) scale(1)";
        state.lightbox.img.style.cursor = "zoom-in";
    }

    function applyZoom() {
        if (!state.lightbox?.img) return;
        state.lightbox.img.style.transform = `translate(${state.panX}px, ${state.panY}px) scale(${state.zoom})`;
        state.lightbox.img.style.cursor = state.zoom > 1 ? "grab" : "zoom-in";
    }

    function zoomBy(delta) {
        state.zoom = Math.min(
            4,
            Math.max(1, Number((state.zoom + delta).toFixed(2))),
        );
        if (state.zoom === 1) {
            state.panX = 0;
            state.panY = 0;
        }
        applyZoom();
    }

    function closeLightbox() {
        if (!state.lightbox?.overlay) return;
        state.lightbox.controller?.abort();
        state.lightbox.overlay.remove();
        state.lightbox = null;
        resetZoom();
        document.body.style.overflow = "";
    }

    function openLightbox() {
        if (state.lightbox || !state.images.length) return;

        const overlay = document.createElement("div");
        overlay.id = "lightbox-overlay";
        overlay.style.cssText = `
            position:fixed;inset:0;z-index:600;background:rgba(10,10,10,.94);
            display:flex;align-items:center;justify-content:center;padding:28px;
        `;

        overlay.innerHTML = `
            <button type="button" data-lightbox-close aria-label="Close zoom" style="position:absolute;top:22px;right:22px;width:42px;height:42px;border:1px solid rgba(255,255,255,.35);background:rgba(10,10,10,.35);color:white;cursor:pointer;font-size:24px;line-height:1;">&times;</button>
            <button type="button" data-lightbox-nav="prev" aria-label="Previous photo" style="position:absolute;left:22px;top:50%;transform:translateY(-50%);width:44px;height:44px;border:1px solid rgba(255,255,255,.35);background:rgba(10,10,10,.35);color:white;cursor:pointer;font-size:26px;">‹</button>
            <img data-lightbox-img src="${currentImage()}" alt="Zoomed product photo" style="max-width:88vw;max-height:86vh;object-fit:contain;transition:transform .16s ease;cursor:zoom-in;user-select:none;">
            <button type="button" data-lightbox-nav="next" aria-label="Next photo" style="position:absolute;right:22px;top:50%;transform:translateY(-50%);width:44px;height:44px;border:1px solid rgba(255,255,255,.35);background:rgba(10,10,10,.35);color:white;cursor:pointer;font-size:26px;">›</button>
            <div style="position:absolute;left:50%;bottom:22px;transform:translateX(-50%);display:flex;gap:8px;background:rgba(10,10,10,.45);padding:8px;border:1px solid rgba(255,255,255,.2);">
                <button type="button" data-zoom-out aria-label="Zoom out" style="width:38px;height:34px;border:1px solid rgba(255,255,255,.35);background:transparent;color:white;cursor:pointer;font-size:20px;">−</button>
                <button type="button" data-zoom-reset aria-label="Reset zoom" style="height:34px;border:1px solid rgba(255,255,255,.35);background:transparent;color:white;cursor:pointer;font-family:'Space Grotesk',sans-serif;font-size:10px;letter-spacing:.12em;text-transform:uppercase;padding:0 12px;">Reset</button>
                <button type="button" data-zoom-in aria-label="Zoom in" style="width:38px;height:34px;border:1px solid rgba(255,255,255,.35);background:transparent;color:white;cursor:pointer;font-size:20px;">+</button>
            </div>
        `;

        document.body.appendChild(overlay);
        document.body.style.overflow = "hidden";

        const img = overlay.querySelector("[data-lightbox-img]");
        const controller = new AbortController();
        const listenerOptions = { signal: controller.signal };
        state.lightbox = { overlay, img, controller };
        resetZoom();
        updateNavState();

        let dragging = false;
        let startX = 0;
        let startY = 0;

        img.addEventListener(
            "click",
            (event) => {
                event.stopPropagation();
                zoomBy(state.zoom > 1 ? -1 : 1);
            },
            listenerOptions,
        );
        img.addEventListener(
            "mousedown",
            (event) => {
                if (state.zoom <= 1) return;
                dragging = true;
                startX = event.clientX - state.panX;
                startY = event.clientY - state.panY;
                img.style.cursor = "grabbing";
            },
            listenerOptions,
        );
        window.addEventListener(
            "mousemove",
            (event) => {
                if (!dragging) return;
                state.panX = event.clientX - startX;
                state.panY = event.clientY - startY;
                applyZoom();
            },
            listenerOptions,
        );
        window.addEventListener(
            "mouseup",
            () => {
                dragging = false;
                if (state.lightbox?.img && state.zoom > 1)
                    state.lightbox.img.style.cursor = "grab";
            },
            listenerOptions,
        );

        overlay
            .querySelector("[data-lightbox-close]")
            .addEventListener("click", closeLightbox, listenerOptions);
        overlay.querySelector("[data-lightbox-nav='prev']").addEventListener(
            "click",
            (event) => {
                event.stopPropagation();
                window.gallery.prev();
            },
            listenerOptions,
        );
        overlay.querySelector("[data-lightbox-nav='next']").addEventListener(
            "click",
            (event) => {
                event.stopPropagation();
                window.gallery.next();
            },
            listenerOptions,
        );
        overlay.querySelector("[data-zoom-in]").addEventListener(
            "click",
            (event) => {
                event.stopPropagation();
                zoomBy(0.5);
            },
            listenerOptions,
        );
        overlay.querySelector("[data-zoom-out]").addEventListener(
            "click",
            (event) => {
                event.stopPropagation();
                zoomBy(-0.5);
            },
            listenerOptions,
        );
        overlay.querySelector("[data-zoom-reset]").addEventListener(
            "click",
            (event) => {
                event.stopPropagation();
                resetZoom();
            },
            listenerOptions,
        );
        overlay.addEventListener(
            "click",
            (event) => {
                if (event.target === overlay) closeLightbox();
            },
            listenerOptions,
        );
    }

    function loadCart() {
        try {
            return JSON.parse(localStorage.getItem("ryo_cart") || "[]");
        } catch (error) {
            localStorage.setItem("ryo_cart", "[]");
            return [];
        }
    }

    function saveCart(items) {
        localStorage.setItem("ryo_cart", JSON.stringify(items));
        updateCartBadge();
        updateCartDrawer();
    }

    function updateCartBadge() {
        const count = loadCart().reduce((sum, item) => sum + item.quantity, 0);
        if (!cartBadge) return;
        cartBadge.style.display = count > 0 ? "flex" : "none";
        cartBadge.textContent = count;
    }

    function updateCartDrawer() {
        const cartItems = loadCart();
        const cartItemsContainer =
            document.getElementById("cartItemsContainer");
        const cartDrawerSubtotal =
            document.getElementById("cartDrawerSubtotal");
        const cartDrawerCount = document.getElementById("cartDrawerCount");
        if (!cartItemsContainer) return;

        if (!cartItems.length) {
            cartItemsContainer.innerHTML = `
                <div style="text-align:center;padding:48px 24px;">
                    <p style="font-family:'DM Sans',sans-serif;font-size:14px;color:#9C9A96;">Your bag is empty</p>
                </div>
            `;
            if (cartDrawerSubtotal) cartDrawerSubtotal.textContent = "EGP 0";
            if (cartDrawerCount) cartDrawerCount.textContent = "0";
            return;
        }

        let subtotal = 0;
        let count = 0;
        cartItemsContainer.innerHTML = cartItems
            .map((item, idx) => {
                const total = item.price * item.quantity;
                subtotal += total;
                count += item.quantity;
                return `
                    <div class="flex gap-4 pb-6 mb-6" style="border-bottom:1px solid #EDEDEB;">
                        <div style="width:80px;height:100px;background:#EDEDEB;flex-shrink:0;overflow:hidden;">
                            <img src="${item.imageUrl}" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                        <div style="flex:1;">
                            <p style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:500;margin-bottom:4px;">${item.productName}</p>
                            <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-bottom:2px;">Size: ${item.sizeName}</p>
                            <p style="font-family:'DM Sans',sans-serif;font-size:12px;color:#9C9A96;margin-bottom:16px;">${item.colorName}</p>
                            <div style="display:flex;align-items:center;justify-content:space-between;">
                                <div style="display:flex;align-items:center;gap:12px;border:1px solid #EDEDEB;padding:6px 14px;">
                                    <button onclick="window.cart.updateQuantity(${idx}, -1)" style="background:none;border:none;cursor:pointer;font-size:16px;color:#9C9A96;">-</button>
                                    <span style="font-size:13px;font-family:'DM Sans',sans-serif;">${item.quantity}</span>
                                    <button onclick="window.cart.updateQuantity(${idx}, 1)" style="background:none;border:none;cursor:pointer;font-size:16px;">+</button>
                                </div>
                                <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:500;">${formatEGP(total)}</span>
                            </div>
                            <button onclick="window.cart.removeItem(${idx})" style="background:none;border:none;cursor:pointer;font-family:'Space Grotesk',sans-serif;font-size:9px;letter-spacing:.1em;text-transform:uppercase;color:#C0392B;margin-top:12px;padding:0;">Remove</button>
                        </div>
                    </div>
                `;
            })
            .join("");

        if (cartDrawerSubtotal)
            cartDrawerSubtotal.textContent = formatEGP(subtotal);
        if (cartDrawerCount) cartDrawerCount.textContent = count;
    }

    function toggleCart() {
        const drawer = document.getElementById("cartDrawer");
        const overlay = document.getElementById("cartOverlay");
        drawer?.classList.toggle("open");
        overlay?.classList.toggle("open");
        document.body.style.overflow = drawer?.classList.contains("open")
            ? "hidden"
            : "";
        updateCartDrawer();
    }

    function showToast(message) {
        const toastMsg = document.getElementById("toastMsg");
        if (toastMsg) toastMsg.textContent = message;
        if (!toast) return;
        toast.style.opacity = "1";
        toast.style.transform = "translateX(-50%) translateY(0)";
        setTimeout(() => {
            toast.style.opacity = "0";
            toast.style.transform = "translateX(-50%) translateY(80px)";
        }, 3000);
    }

    function changeQty(delta) {
        const maxStock = Math.min(state.selectedVariant?.stock || 10, 10);
        state.qty = Math.max(1, Math.min(maxStock, state.qty + delta));
        if (qtyDisplay) qtyDisplay.textContent = state.qty;
    }

    function addToCart(options = {}) {
        if (!state.selectedVariant) {
            if (sizeError) sizeError.style.display = "block";
            document
                .querySelectorAll(".size-btn:not(.sold-out)")
                .forEach((button) => {
                    button.style.borderColor = "#C0392B";
                    setTimeout(() => {
                        if (!button.classList.contains("active"))
                            button.style.borderColor = "";
                    }, 1200);
                });
            return false;
        }

        const availableStock = state.selectedVariant.stock;
        if (availableStock <= 0) {
            showToast("Out of stock");
            return false;
        }

        const cartItems = loadCart();
        const existingIndex = cartItems.findIndex(
            (item) => item.variantId === state.selectedVariant.id,
        );
        const currentQty =
            existingIndex >= 0 ? cartItems[existingIndex].quantity : 0;
        const requestedQty = currentQty + state.qty;

        if (requestedQty > availableStock) {
            showToast(
                `Only ${Math.max(availableStock - currentQty, 0)} more available`,
            );
            return false;
        }

        const cartImage = currentImage();
        if (existingIndex >= 0) {
            cartItems[existingIndex].quantity += state.qty;
            cartItems[existingIndex].imageUrl = cartImage;
        } else {
            cartItems.push({
                variantId: state.selectedVariant.id,
                productName: productName(),
                colorName: state.selectedVariant.colorName || "",
                sizeName: state.selectedVariant.sizeName || "OS",
                price: state.selectedVariant.price,
                quantity: Math.min(state.qty, availableStock),
                imageUrl: cartImage,
                sku: state.selectedVariant.sku || "",
            });
        }

        saveCart(cartItems);
        showToast("Added to bag");

        if (atcBtn && !options.redirectToCheckout) {
            const original = atcBtn.innerHTML;
            atcBtn.innerHTML = "Added to Bag";
            atcBtn.style.background = "#2C5F2D";
            setTimeout(() => {
                atcBtn.innerHTML = original;
                atcBtn.style.background = "";
            }, 1800);
        }

        state.qty = 1;
        if (qtyDisplay) qtyDisplay.textContent = "1";

        if (options.redirectToCheckout) {
            window.location.href = window.CHECKOUT_URL;
        }
        return true;
    }

    window.selectColor = selectColor;
    window.gallery = {
        prev: (event) => {
            event?.stopPropagation();
            if (state.images.length <= 1) return;
            setMainImage(state.imageIndex - 1);
        },
        next: (event) => {
            event?.stopPropagation();
            if (state.images.length <= 1) return;
            setMainImage(state.imageIndex + 1);
        },
        switch: (index) => setMainImage(index),
    };
    window.lightbox = {
        open: openLightbox,
        close: closeLightbox,
        zoomIn: () => zoomBy(0.5),
        zoomOut: () => zoomBy(-0.5),
        reset: resetZoom,
    };
    window.cart = {
        getItems: loadCart,
        saveItems: saveCart,
        updateBadge: updateCartBadge,
        updateCartDrawer,
        toggle: toggleCart,
        changeQty,
        add: addToCart,
        payNow: () => addToCart({ redirectToCheckout: true }),
        updateQuantity: (index, delta) => {
            const items = loadCart();
            if (!items[index]) return;
            const variant = window.VARIANTS.find(
                (item) => item.id === items[index].variantId,
            );
            const maxStock = variant?.stock || 999;
            const nextQty = items[index].quantity + delta;
            if (nextQty < 1) {
                items.splice(index, 1);
            } else {
                items[index].quantity = Math.min(nextQty, maxStock);
            }
            saveCart(items);
        },
        removeItem: (index) => {
            const items = loadCart();
            items.splice(index, 1);
            saveCart(items);
        },
    };
    window.sizeGuide = window.sizeGuide || {
        open: () => {
            const modal = document.getElementById("sizeModal");
            const inner = document.getElementById("sizeModalInner");
            if (modal) {
                modal.style.opacity = "1";
                modal.style.pointerEvents = "auto";
            }
            if (inner) inner.style.transform = "translateY(0)";
        },
        close: () => {
            const modal = document.getElementById("sizeModal");
            const inner = document.getElementById("sizeModalInner");
            if (modal) {
                modal.style.opacity = "0";
                modal.style.pointerEvents = "none";
            }
            if (inner) inner.style.transform = "translateY(40px)";
        },
    };

    document.addEventListener("DOMContentLoaded", () => {
        const activeSwatch =
            document.querySelector(".color-swatch-opt.active") ||
            document.querySelector(".color-swatch-opt");
        if (activeSwatch) {
            activeSwatch.classList.add("active");
            selectColor(
                activeSwatch,
                activeSwatch.getAttribute("data-color-id") || null,
            );
        } else {
            updateGalleryByColor("default");
        }

        updateCartBadge();
        updateCartDrawer();
    });
})();
