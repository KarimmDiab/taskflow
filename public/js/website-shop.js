        // ── CART ──
        function toggleCart() {
            const cartDrawer = document.getElementById("cartDrawer");
            const cartOverlay = document.getElementById("cartOverlay");
            if (cartDrawer) cartDrawer.classList.toggle("open");
            if (cartOverlay) cartOverlay.classList.toggle("open");
            document.body.style.overflow = cartDrawer && cartDrawer.classList.contains("open") ? "hidden" : "";
        }

        // ── MENU ──
        function toggleMenu() {
            const mobileMenu = document.getElementById("mobileMenu");
            if (mobileMenu) mobileMenu.classList.toggle("open");
        }

        // ── CATEGORY FILTER FIXED ──
        function setCategory(cat) {
            // إعادة توجيه إلى URL مع التصنيف المحدد
            const url = new URL(window.location.href);
            if (cat === 'all') {
                url.searchParams.delete('category');
            } else {
                url.searchParams.set('category', cat);
            }
            url.searchParams.set('page', 1); // إعادة تعيين إلى الصفحة الأولى
            window.location.href = url.toString();
        }

        // ── GRID COLUMNS ──
        function setGrid(cols, btn) {
            document.querySelectorAll(".grid-btn").forEach((b) => b.classList.remove("active"));
            btn.classList.add("active");
            const productGrid = document.getElementById("productGrid");
            if (productGrid) {
                productGrid.style.gridTemplateColumns = `repeat(${cols},1fr)`;
            }
            // حفظ التفضيل في localStorage
            localStorage.setItem('gridColumns', cols);
        }

        // ── SORT ──
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

        // ── ADD TO CART TOAST ──
        function addToCart(e, name, productId) {
            if (e) e.stopPropagation();
            const toast = document.getElementById("toast");
            const toastMsg = document.getElementById("toastMsg");
            if (toastMsg) toastMsg.textContent = name + " added to bag";
            if (toast) {
                toast.style.opacity = "1";
                toast.style.transform = "translateX(-50%) translateY(0)";
                setTimeout(() => {
                    toast.style.opacity = "0";
                    toast.style.transform = "translateX(-50%) translateY(80px)";
                }, 3000);
            }
            console.log('Added to cart:', name, 'ID:', productId);
        }

        // ── WISHLIST ──
        function addToWishlist(productId) {
            console.log('Added to wishlist:', productId);
            // أضف منطق إضافة المنتج إلى المفضلة هنا
        }

        // ── PAGINATION FUNCTIONS ──
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

        // ── SCROLL REVEAL ──
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

        // ── RESPONSIVE GRID ──
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

        // تهيئة الصفحة عند التحميل
        document.addEventListener("DOMContentLoaded", function () {
            // تهيئة الـ scroll reveal
            document.querySelectorAll(".reveal").forEach((el) => revealObserver.observe(el));

            // تحميل تفضيل الشبكة المحفوظ
            const savedColumns = localStorage.getItem('gridColumns');
            if (savedColumns) {
                const grid = document.getElementById('productGrid');
                if (grid) {
                    grid.style.gridTemplateColumns = `repeat(${savedColumns}, 1fr)`;
                    const activeBtn = document.querySelector(`.grid-btn`);
                    if (activeBtn) {
                        document.querySelectorAll('.grid-btn').forEach(b => b.classList.remove('active'));
                        activeBtn.classList.add('active');
                    }
                }
            }

            // تهيئة الـ responsive grid
            checkGrid();

            // تحديث الـ active state للأزرار بناءً على URL الحالي
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

        window.addEventListener("resize", checkGrid);
