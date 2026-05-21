        // ── CART ──
        function toggleCart() {
            document.getElementById('cartDrawer').classList.toggle('open');
            document.getElementById('cartOverlay').classList.toggle('open');
        }

        // ── MENU ──
        function toggleMenu() {
            document.getElementById('mobileMenu').classList.toggle('open');
        }

        // ── ACTIVE SIDEBAR NAV ──
        function setActive(el) {
            document.querySelectorAll('.policy-nav-link').forEach(l => l.classList.remove('active'));
            el.classList.add('active');
        }

        // ── SCROLL SPY ──
        const sections = ['shipping', 'returns', 'privacy', 'terms', 'cookies', 'contact'];
        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(id => {
                const el = document.getElementById(id);
                if (el && el.getBoundingClientRect().top < 120) current = id;
            });
            if (current) {
                document.querySelectorAll('.policy-nav-link').forEach(l => {
                    l.classList.remove('active');
                    if (l.getAttribute('href') === `#${current}`) l.classList.add('active');
                });
            }
        });

        // ── SCROLL REVEAL ──
        const revObs = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    revObs.unobserve(e.target);
                }
            });
        }, {
            threshold: 0.06
        });
        document.querySelectorAll('.reveal').forEach(el => revObs.observe(el));

        // ── RESPONSIVE ──
        function checkLayout() {
            const layout = document.getElementById('policyLayout');
            if (window.innerWidth < 900) {
                layout.style.gridTemplateColumns = '1fr';
                layout.style.gap = '0';
                document.querySelector('.policy-sidebar').style.display = 'none';
            } else {
                layout.style.gridTemplateColumns = '220px 1fr';
                layout.style.gap = '64px';
                document.querySelector('.policy-sidebar').style.display = 'block';
            }
        }
        checkLayout();
        window.addEventListener('resize', checkLayout);

