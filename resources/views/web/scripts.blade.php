<!-- ═══════════════ JAVASCRIPT ═══════════════ -->
<script>
    // Scroll reveal
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) e.target.classList.add('active');
        });
    }, {
        threshold: 0.1
    });

    document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale')
        .forEach(el => observer.observe(el));

    // Prefers reduced motion
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale')
            .forEach(el => el.classList.add('active'));
    }

    // ── Menu Toggle ──
    const menuToggle = document.getElementById('menuToggle');
    const navDropdown = document.getElementById('navDropdown');
    let menuOpen = false;

    function closeMenu() {
        navDropdown.classList.add('dropdown-hidden');
        menuToggle.innerHTML = "<i class='bx bx-menu text-2xl sm:text-3xl'></i>";
        menuOpen = false;
    }

    menuToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        menuOpen = !menuOpen;
        if (menuOpen) {
            navDropdown.classList.remove('dropdown-hidden');
            menuToggle.innerHTML = "<i class='bx bx-x text-2xl sm:text-3xl'></i>";
        } else {
            closeMenu();
        }
    });

    document.addEventListener('click', (e) => {
        if (!navDropdown.contains(e.target) && !menuToggle.contains(e.target)) closeMenu();
    });

    // Close dropdown links on click (mobile UX)
    navDropdown.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) closeMenu();
    });

    // ── Video Modal ──
    const videoModal = document.getElementById('videoModal');
    const videoContainer = document.getElementById('videoContainer');
    const videoIframe = document.getElementById('videoIframe');

    function openVideoModal() {
        videoIframe.src = "https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1";
        videoModal.classList.remove('hidden');
        setTimeout(() => {
            videoModal.classList.remove('opacity-0');
            videoContainer.classList.remove('scale-95');
            videoContainer.classList.add('scale-100');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeVideoModal() {
        videoModal.classList.add('opacity-0');
        videoContainer.classList.remove('scale-100');
        videoContainer.classList.add('scale-95');
        setTimeout(() => {
            videoModal.classList.add('hidden');
            videoIframe.src = "";
        }, 300);
        document.body.style.overflow = '';
    }

    const openVideoBtn = document.getElementById('openVideo');
    if (openVideoBtn) openVideoBtn.addEventListener('click', openVideoModal);
    document.getElementById('closeVideo').addEventListener('click', closeVideoModal);

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !videoModal.classList.contains('hidden')) closeVideoModal();
    });
    videoModal.addEventListener('click', (e) => {
        if (e.target === videoModal) closeVideoModal();
    });

    // ── FAQ ──
    document.querySelectorAll('.faq-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            const content = btn.nextElementSibling;
            const icon = btn.querySelector('.faq-icon');
            const isExpanded = btn.getAttribute('aria-expanded') === 'true';

            // Close others
            document.querySelectorAll('.faq-toggle').forEach(other => {
                if (other !== btn) {
                    other.setAttribute('aria-expanded', 'false');
                    other.nextElementSibling.classList.remove('open');
                    other.querySelector('.faq-icon').classList.remove('rotate');
                }
            });

            btn.setAttribute('aria-expanded', !isExpanded);
            content.classList.toggle('open');
            icon.classList.toggle('rotate');
        });
    });

    // ── Smooth Scroll ──
    function scrollToSection(id) {
        const el = document.getElementById(id);
        if (el) el.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    // ── Contact Form ──
    document.getElementById('contactForm').addEventListener('submit', (e) => {
        e.preventDefault();
        showToast("Message sent! We'll get back to you soon.");
        e.target.reset();
    });

    // ── Toast ──
    function showToast(msg) {
        const toast = document.getElementById('toast');
        document.getElementById('toastMessage').textContent = msg;
        toast.classList.remove('translate-y-20', 'opacity-0');
        setTimeout(() => toast.classList.add('translate-y-20', 'opacity-0'), 4000);
    }

    // ── Generic button fallbacks ──
    document.querySelectorAll(
            'button:not([type="submit"]):not(#menuToggle):not(#openVideo):not(#closeVideo):not(.faq-toggle)')
        .forEach(btn => {
            if (!btn.onclick && !btn.hasAttribute('onclick')) {
                btn.addEventListener('click', function() {
                    const t = this.textContent.trim();
                    if (t.includes('Start Free') || t.includes('Go Pro')) showToast(
                        'Coming soon! Join our waitlist.');
                    else if (t.includes('Get Your QR Code')) scrollToSection('contact');
                });
            }
        });

    // ── Navbar tint on scroll ──
    window.addEventListener('scroll', () => {
        const nav = document.querySelector('nav');
        if (window.pageYOffset > 100) nav.classList.add('bg-red-900/90', 'shadow-lg');
        else nav.classList.remove('bg-red-900/90', 'shadow-lg');
    }, {
        passive: true
    });
</script>
