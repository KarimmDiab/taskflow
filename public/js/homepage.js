        // ── HERO BG LOAD ──
        window.addEventListener('load', () => {
            document.getElementById('heroBg').classList.add('loaded');
        });

        // ── NAVBAR SCROLL ──
        const navbar = document.getElementById('navbar');
        const navLinks = navbar.querySelectorAll('.nav-link');
        const navLogo = navbar.querySelector('.nav-logo img');
        const navIcons = navbar.querySelectorAll('.nav-icon');
        const cartCount = navbar.querySelector('#cartCount');

        window.addEventListener('scroll', () => {

            if (window.scrollY > 80) {

                navbar.classList.remove('nav-transparent');
                navbar.classList.add('nav-scrolled');

                navLinks.forEach(l => l.style.color = '#0A0A0A');

                navIcons.forEach(i => i.style.color = '#0A0A0A');

                cartCount.style.background = '#0A0A0A';
                cartCount.style.color = '#F8F6F2';

                // Change Logo
                navLogo.src = "{{ asset('images/logos/black_logo.png') }}";

            } else {

                navbar.classList.add('nav-transparent');
                navbar.classList.remove('nav-scrolled');

                navLinks.forEach(l => l.style.color = '#F8F6F2');

                navIcons.forEach(i => i.style.color = '#F8F6F2');

                cartCount.style.background = '#F8F6F2';
                cartCount.style.color = '#0A0A0A';

                // Change Logo
                navLogo.src = "{{ asset('images/logos/white_logo.png') }}";
            }


        });

        // ── CART DRAWER ──
        function toggleCart() {
            document.getElementById('cartDrawer').classList.toggle('open');
            document.getElementById('cartOverlay').classList.toggle('open');
            document.body.style.overflow = document.getElementById('cartDrawer').classList.contains('open') ? 'hidden' : '';
        }

        // ── MOBILE MENU ──
        function toggleMenu() {
            document.getElementById('mobileMenu').classList.toggle('open');
            document.body.style.overflow = document.getElementById('mobileMenu').classList.contains('open') ? 'hidden' : '';
        }

        // ── SCROLL REVEAL ──
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -60px 0px'
        });

        document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

        // ── EDITORIAL PARALLAX ──
        const editorialImg = document.getElementById('editorialImg');
        if (editorialImg) {
            window.addEventListener('scroll', () => {
                const rect = editorialImg.parentElement.getBoundingClientRect();
                if (rect.top < window.innerHeight && rect.bottom > 0) {
                    const progress = (window.innerHeight - rect.top) / (window.innerHeight + rect.height);
                    editorialImg.style.transform = `scale(1.08) translateY(${progress * -30}px)`;
                }
            });
        }

        // ── MOBILE RESPONSIVE ADJUSTMENTS ──
        if (window.innerWidth < 768) {
            document.querySelector('.hero-content').style.padding = '0 24px 60px';
            document.querySelectorAll('section').forEach(s => {
                if (s.style.padding && s.style.padding.includes('40px')) {
                    s.style.padding = s.style.padding.replace(/40px/g, '20px');
                }
            });
        }
