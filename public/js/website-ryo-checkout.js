// ─────────────────────────────────────
// CONFIG
// ─────────────────────────────────────

const FREE_SHIPPING_THRESHOLD = 1500;

const checkoutState = {
    cartSubtotal: 0,
    selectedCityShippingCost: 0,
    shippingCost: 0,
    discount: 0,
    couponCode: "",
};

// ─────────────────────────────────────
// HELPERS
// ─────────────────────────────────────

function formatEGP(value) {
    return `EGP ${Math.round(value).toLocaleString("en-EG")}`;
}

function escapeHtml(str) {
    return String(str || "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#39;");
}

function setText(id, value) {
    const element = document.getElementById(id);

    if (element) {
        element.textContent = value;
    }
}

function setDisplay(id, value) {
    const element = document.getElementById(id);

    if (element) {
        element.style.display = value;
    }
}

// ─────────────────────────────────────
// CART
// ─────────────────────────────────────

function loadCheckoutCart() {
    try {
        if (!window.localStorage) {
            return [];
        }

        const parsed = JSON.parse(localStorage.getItem("ryo_cart") || "[]");

        if (!Array.isArray(parsed)) {
            return [];
        }

        return parsed
            .map((item) => ({
                variantId: Number.parseInt(item.variantId, 10) || 0,
                productName: item.productName || "RYO Product",
                colorName: item.colorName || "Default",
                sizeName: item.sizeName || "OS",
                price: Number(item.price) || 0,
                quantity: Math.max(1, Number.parseInt(item.quantity, 10) || 1),
                imageUrl: item.imageUrl || "",
            }))
            .filter((item) => item.variantId > 0 && item.quantity > 0);
    } catch (error) {
        localStorage.setItem("ryo_cart", "[]");

        return [];
    }
}

// ─────────────────────────────────────
// SHIPPING
// ─────────────────────────────────────

function getSelectedCityShippingCost() {
    const citySelect = document.getElementById("city");

    if (!citySelect || citySelect.selectedIndex <= 0) {
        return 0;
    }

    const selectedOption = citySelect.options[citySelect.selectedIndex];

    const price = Number(selectedOption?.dataset?.price);

    return Number.isFinite(price) ? price : 0;
}

function getCurrentShippingCost() {
    const hasCartItems = loadCheckoutCart().length > 0;

    const citySelect = document.getElementById("city");

    const hasSelectedCity = citySelect && citySelect.selectedIndex > 0;

    if (
        !hasCartItems ||
        !hasSelectedCity ||
        checkoutState.cartSubtotal >= FREE_SHIPPING_THRESHOLD
    ) {
        return 0;
    }

    return checkoutState.selectedCityShippingCost;
}

function renderShippingPrice() {
    const shippingDisplay = document.getElementById("shippingDisplay");

    const standardShippingPrice = document.getElementById("std-price");

    const hasSelectedCity = checkoutState.selectedCityShippingCost > 0;

    const text = !hasSelectedCity
        ? "Select city"
        : checkoutState.shippingCost > 0
          ? formatEGP(checkoutState.shippingCost)
          : "Free";

    if (shippingDisplay) {
        shippingDisplay.textContent = text;
    }

    if (standardShippingPrice) {
        standardShippingPrice.textContent = text;
    }
}

function updateShippingByCity() {
    checkoutState.selectedCityShippingCost = getSelectedCityShippingCost();

    checkoutState.shippingCost = getCurrentShippingCost();

    renderShippingPrice();
    updateEstimatedDays(); // <-- إضافة هذا السطر
    updateTotal();
}

function selectShipping(el, type) {
    document.querySelectorAll(".shipping-opt").forEach((option) => {
        option.classList.remove("active");

        const dot = option.querySelector(".pay-radio-dot");

        if (dot) {
            dot.style.opacity = "0";
        }
    });

    el.classList.add("active");

    const activeDot = el.querySelector(".pay-radio-dot");

    if (activeDot) {
        activeDot.style.opacity = "1";
    }

    renderShippingPrice();

    updateTotal();
}

// ─────────────────────────────────────
// UPDATE ESTIMATED DAYS DISPLAY
// ─────────────────────────────────────

function updateEstimatedDays() {
    const citySelect = document.getElementById("city");
    const daysElement = document.getElementById("estimated-days-text"); // سنضيف هذا id في HTML
    const daysElement2 = document.getElementById("estimated-days-text2"); // سنضيف هذا id في HTML

    if (!citySelect || !daysElement || !daysElement2) return;

    const selectedIndex = citySelect.selectedIndex;
    if (selectedIndex <= 0) {
        daysElement.textContent = "Delivered  In : ";
        daysElement2.textContent = "Delivered  In : ";
        return;
    }

    const selectedOption = citySelect.options[selectedIndex];
    const days = selectedOption?.dataset?.days;

    if (days) {
        daysElement.textContent = "Delivered  In : " + days;
        daysElement2.textContent = "Delivered  In : " + days;
    } else {
        daysElement.textContent = "Not specified";
        daysElement2.textContent = "Not specified";
    }
}

// ─────────────────────────────────────
// PAYMENT
// ─────────────────────────────────────

function selectPayment(type) {
    const options = {
        cod: "pay-cod",
    };

    Object.values(options).forEach((id) => {
        const el = document.getElementById(id);

        if (!el) {
            return;
        }

        el.classList.remove("active");

        el.querySelector(".pay-radio-dot").style.opacity = "0";
    });

    const selected = document.getElementById(options[type]);

    if (selected) {
        selected.classList.add("active");

        selected.querySelector(".pay-radio-dot").style.opacity = "1";
    }

    const cardFields = document.getElementById("cardFields");

    const walletFields = document.getElementById("walletFields");

    if (cardFields) {
        cardFields.style.display = type === "card" ? "block" : "none";
    }

    if (walletFields) {
        walletFields.style.display = type === "wallet" ? "block" : "none";
    }
}

// ─────────────────────────────────────
// PROMO
// ─────────────────────────────────────

function togglePromo() {
    const wrap = document.getElementById("promoWrap");

    const chevron = document.getElementById("promoChevron");

    if (!wrap || !chevron) {
        return;
    }

    const isOpen = wrap.style.display !== "none";

    wrap.style.display = isOpen ? "none" : "block";

    chevron.style.transform = isOpen ? "" : "rotate(180deg)";
}

async function applyPromo() {
    const promoCode = document.getElementById("promoCode");

    if (!promoCode) {
        return;
    }

    const code = promoCode.value.toUpperCase().trim();

    const msg = document.getElementById("promoMsg");

    if (code === "") {
        if (msg) {
            msg.textContent = "Please enter a promo code";

            msg.style.color = "#9C9A96";
        }
        return;
    }

    if (msg) {
        msg.textContent = "Checking code...";
        msg.style.color = "#9C9A96";
    }

    try {
        const response = await fetch(window.checkoutCouponUrl || "/ryo-checkout/coupon", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN":
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || "",
            },
            body: JSON.stringify({
                coupon_code: code,
                cart: loadCheckoutCart().map((item) => ({
                    variantId: item.variantId,
                    quantity: item.quantity,
                })),
            }),
        });

        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            const errors = data.errors ? Object.values(data.errors).flat() : [];
            throw new Error(errors[0] || data.message || "Invalid code");
        }

        checkoutState.discount = Number(data.discount_amount) || 0;
        checkoutState.couponCode = data.coupon?.code || code;

        if (msg) {
            msg.textContent = `${checkoutState.couponCode} applied`;
            msg.style.color = "#0A0A0A";
        }

        setDisplay("discountRow", "flex");
        setText("discountDisplay", `- ${formatEGP(checkoutState.discount)}`);
    } catch (error) {
        if (msg) {
            msg.textContent = error.message || "Invalid code";

            msg.style.color = "#c0392b";
        }

        checkoutState.discount = 0;
        checkoutState.couponCode = "";

        setDisplay("discountRow", "none");
    }

    updateTotal();
}

// ─────────────────────────────────────
// TOTAL
// ─────────────────────────────────────

function updateTotal() {
    const total = Math.max(
        0,
        checkoutState.cartSubtotal +
            checkoutState.shippingCost -
            checkoutState.discount,
    );

    setText("grandTotal", formatEGP(total));

    setText("totalInBtn", total.toLocaleString("en-EG"));
}

// ─────────────────────────────────────
// ORDER SUMMARY
// ─────────────────────────────────────

function renderOrderSummary() {
    const cart = loadCheckoutCart();

    const itemsWrap = document.getElementById("orderItems");

    const placeOrderBtn = document.getElementById("placeOrderBtn");

    checkoutState.cartSubtotal = cart.reduce(
        (sum, item) => sum + item.price * item.quantity,
        0,
    );

    checkoutState.selectedCityShippingCost = getSelectedCityShippingCost();

    checkoutState.shippingCost = getCurrentShippingCost();

    if (!itemsWrap) {
        return;
    }

    if (cart.length === 0) {
        itemsWrap.innerHTML = `
            <div style="text-align:center;padding:36px 16px;">
                Your bag is empty
            </div>
        `;

        if (placeOrderBtn) {
            placeOrderBtn.disabled = true;

            placeOrderBtn.style.opacity = ".45";

            placeOrderBtn.style.cursor = "not-allowed";
        }
    } else {
        itemsWrap.innerHTML = cart
            .map(
                (item) => `
                <div class="summary-item">

                    <div class="summary-img">

                        <img
                            src="${escapeHtml(item.imageUrl)}"
                            alt="${escapeHtml(item.productName)}">

                        <div class="item-qty-badge">
                            ${item.quantity}
                        </div>

                    </div>

                    <div style="flex:1;">

                        <p>
                            ${escapeHtml(item.productName)}
                        </p>

                        <p>
                            Size:
                            ${escapeHtml(item.sizeName)}
                            ·
                            ${escapeHtml(item.colorName)}
                        </p>

                    </div>

                    <span>
                        ${formatEGP(item.price * item.quantity)}
                    </span>

                </div>
            `,
            )
            .join("");

        if (placeOrderBtn) {
            placeOrderBtn.disabled = false;

            placeOrderBtn.style.opacity = "";

            placeOrderBtn.style.cursor = "";
        }
    }

    setText("subtotalDisplay", formatEGP(checkoutState.cartSubtotal));

    renderShippingPrice();

    updateTotal();
}

// ─────────────────────────────────────
// EXPIRY
// ─────────────────────────────────────

function formatExpiry(input) {
    let val = input.value.replace(/\D/g, "");

    if (val.length > 2) {
        val = val.slice(0, 2) + " / " + val.slice(2, 4);
    }

    input.value = val;
}

// ─────────────────────────────────────
// PLACE ORDER
// ─────────────────────────────────────

function showCheckoutError(message) {
    const error = document.getElementById("checkoutError");

    if (!error) {
        alert(message);
        return;
    }

    error.textContent = message;
    error.style.display = "block";
}

function hideCheckoutError() {
    const error = document.getElementById("checkoutError");

    if (error) {
        error.textContent = "";
        error.style.display = "none";
    }
}

function getCheckoutPayload() {
    const citySelect = document.getElementById("city");

    return {
        first_name: document.getElementById("firstName")?.value.trim() || "",
        last_name: document.getElementById("lastName")?.value.trim() || "",
        email: document.getElementById("email")?.value.trim() || "",
        phone: document.getElementById("phone")?.value.trim() || "",
        shipping_id: citySelect?.value || "",
        address1: document.getElementById("address1")?.value.trim() || "",
        address2: document.getElementById("address2")?.value.trim() || "",
        district: document.getElementById("district")?.value.trim() || "",
        notes: document.getElementById("notes")?.value.trim() || "",
        shipping_cost: checkoutState.shippingCost,
        discount: checkoutState.discount,
        coupon_code: checkoutState.couponCode,
        cart: loadCheckoutCart().map((item) => ({
            variantId: item.variantId,
            quantity: item.quantity,
        })),
    };
}

function validateCheckoutPayload(payload) {
    if (!payload.first_name || !payload.last_name) {
        return "Please enter your full name.";
    }

    if (!payload.phone) {
        return "Please enter your phone number.";
    }

    if (!payload.address1 || !payload.shipping_id || !payload.district) {
        return "Please complete your shipping address.";
    }

    return "";
}

async function placeOrder() {
    const cart = loadCheckoutCart();

    if (cart.length === 0) {
        showCheckoutError("Your bag is empty.");
        return;
    }

    const btn = document.getElementById("placeOrderBtn");
    const originalHtml = btn.innerHTML;

    btn.innerHTML = "Processing...";
    btn.style.background = "#3D3D3A";
    btn.disabled = true;
    hideCheckoutError();

    try {
        const payload = getCheckoutPayload();
        const validationError = validateCheckoutPayload(payload);

        if (validationError) {
            throw new Error(validationError);
        }

        const response = await fetch(window.checkoutStoreUrl || "/ryo-checkout", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN":
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || "",
            },
            body: JSON.stringify(payload),
        });

        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            const errors = data.errors ? Object.values(data.errors).flat() : [];
            throw new Error(
                errors[0] || data.message || "Unable to place your order.",
            );
        }

        localStorage.setItem("ryo_cart", "[]");
        renderOrderSummary();

        if (data.invoice_number) {
            setText("confirmedOrderNumber", data.invoice_number);
            setText("confirmedInvoiceNumber", data.invoice_number);
        }

        const successOverlay = document.getElementById("successOverlay");
        if (successOverlay) {
            successOverlay.classList.add("show");
        }
    } catch (error) {
        showCheckoutError(error.message || "Unable to place your order.");
        btn.innerHTML = originalHtml;
        btn.style.background = "";
        btn.disabled = false;
    }
}

// ─────────────────────────────────────
// RESPONSIVE
// ─────────────────────────────────────

function checkLayout() {
    const layout = document.getElementById("checkoutLayout");

    layout.style.gridTemplateColumns =
        window.innerWidth < 900 ? "1fr" : "1fr 420px";
}

// ─────────────────────────────────────
// REVEAL ANIMATION
// ─────────────────────────────────────

const revObs = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("visible");

                revObs.unobserve(entry.target);
            }
        });
    },
    { threshold: 0.1 },
);

document.querySelectorAll(".reveal").forEach((el) => revObs.observe(el));

// ─────────────────────────────────────
// INIT
// ─────────────────────────────────────

renderOrderSummary();

updateShippingByCity();

selectPayment("cod");

checkLayout();

window.addEventListener("resize", checkLayout);

window.addEventListener("storage", (event) => {
    if (event.key === "ryo_cart") {
        renderOrderSummary();
    }
});

// ─────────────────────────────────────
// GLOBALS
// ─────────────────────────────────────

window.updateShippingByCity = updateShippingByCity;

window.renderShippingPrice = renderShippingPrice;
