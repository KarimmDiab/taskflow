  // ── CART & MENU (same as policy page) ──
        function toggleCart() {
            document.getElementById('cartDrawer')?.classList.toggle('open');
            document.getElementById('cartOverlay')?.classList.toggle('open');
        }

        function toggleMenu() {
            document.getElementById('mobileMenu')?.classList.toggle('open');
        }

        // ── SCROLL REVEAL ──
        const revObs = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    revObs.unobserve(e.target);
                }
            });
        }, {
            threshold: 0.08
        });
        document.querySelectorAll('.reveal').forEach(el => revObs.observe(el));

        // ── RESPONSIVE (no sidebar needed but maintain consistency) ──
        function checkLayout() {
            // nothing critical for about page
        }
        checkLayout();
        window.addEventListener('resize', checkLayout);
