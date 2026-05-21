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
      entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revObs.unobserve(e.target); } });
    }, { threshold: 0.08 });
    document.querySelectorAll('.reveal').forEach(el => revObs.observe(el));

    // ── FAQ ACCORDION ──
    function toggleFaq(element) {
      element.classList.toggle('open');
    }

    // ── FORM SUBMIT (prevent default + console simulation) ──
    const contactForm = document.getElementById('contactForm');
    if(contactForm) {
      contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Thank you for reaching out. Our team will respond within 24 hours.');
        contactForm.reset();
      });
    }

    // ── RESPONSIVE (no sidebar needed but keep consistency) ──
    function checkLayout() {
      // nothing critical for contact page, but keep pattern
    }
    checkLayout();
    window.addEventListener('resize', checkLayout);
