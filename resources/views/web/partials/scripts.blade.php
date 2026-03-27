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

    if (menuToggle && navDropdown) {
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

        navDropdown.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) closeMenu();
        });
    }

    // ── Video Modal ──
    const videoModal = document.getElementById('videoModal');
    if (videoModal) {
        const videoContainer = document.getElementById('videoContainer');
        const videoIframe = document.getElementById('videoIframe');

        function openVideoModal() {
            if (!videoIframe) return;
            videoIframe.src = "https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1";
            videoModal.classList.remove('hidden');
            setTimeout(() => {
                videoModal.classList.remove('opacity-0');
                if (videoContainer) {
                    videoContainer.classList.remove('scale-95');
                    videoContainer.classList.add('scale-100');
                }
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeVideoModal() {
            videoModal.classList.add('opacity-0');
            if (videoContainer) {
                videoContainer.classList.remove('scale-100');
                videoContainer.classList.add('scale-95');
            }
            setTimeout(() => {
                videoModal.classList.add('hidden');
                if (videoIframe) videoIframe.src = "";
            }, 300);
            document.body.style.overflow = '';
        }

        const openVideoBtn = document.getElementById('openVideo');
        const closeVideoBtn = document.getElementById('closeVideo');
        if (openVideoBtn) openVideoBtn.addEventListener('click', openVideoModal);
        if (closeVideoBtn) closeVideoBtn.addEventListener('click', closeVideoModal);

        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !videoModal.classList.contains('hidden')) closeVideoModal();
        });
        videoModal.addEventListener('click', (e) => {
            if (e.target === videoModal) closeVideoModal();
        });
    }

    // ── FAQ ──
    document.querySelectorAll('.faq-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            const content = btn.nextElementSibling;
            const icon = btn.querySelector('.faq-icon');
            if (!content || !icon) return;
            const isExpanded = btn.getAttribute('aria-expanded') === 'true';

            // Close others
            document.querySelectorAll('.faq-toggle').forEach(other => {
                if (other !== btn) {
                    other.setAttribute('aria-expanded', 'false');
                    const otherContent = other.nextElementSibling;
                    const otherIcon = other.querySelector('.faq-icon');
                    if (otherContent) otherContent.classList.remove('open');
                    if (otherIcon) otherIcon.classList.remove('rotate');
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
    document.getElementById('contactForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const loadingSpan = document.getElementById('loadingSpan');
        
        // Anti-spam checks
        const honeyPotInput = form.querySelector('[name="honey_pot"]');
        if (honeyPotInput && honeyPotInput.value) {
            console.log('Spam detected');
            return;
        }

        const mathInput = document.getElementById('math_answer');
        if (mathInput) {
            const correctAnswer = mathInput.getAttribute('data-answer');
            if (mathInput.value != correctAnswer) {
                showToast('Please provide the correct answer to the math question.', 'error');
                return;
            }
        }

        // Show loading state
        if (submitBtn) {
            submitBtn.disabled = true;
            btnText?.classList.add('hidden');
            loadingSpan?.classList.remove('hidden');
        }
        
        const formData = new FormData(form);
        
        fetch('{{ route('contact.store') }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                form.reset();
            } else {
                showToast(data.message || 'Something went wrong. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Something went wrong. Please try again.', 'error');
        })
        .finally(() => {
            // Reset button state
            if (submitBtn) {
                submitBtn.disabled = false;
                btnText?.classList.remove('hidden');
                loadingSpan?.classList.add('hidden');
            }
        });
    });

    // ── Copy Link ──
    document.getElementById('copyLinkBtn')?.addEventListener('click', function() {
        const url = this.getAttribute('data-url');
        if (!url) return;
        navigator.clipboard.writeText(url).then(() => {
            showToast('Link copied to clipboard!', 'success');
        }).catch(err => {
            console.error('Could not copy text: ', err);
            showToast('Failed to copy link.', 'error');
        });
    });

    // ── Toast ──
    function showToast(msg, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        if (!toast || !toastMessage) return;
        
        toastMessage.textContent = msg;
        
        if (type === 'error') {
            toast.classList.remove('bg-green-500');
            toast.classList.add('bg-red-500');
        } else {
            toast.classList.remove('bg-red-500');
            toast.classList.add('bg-green-500');
        }
        
        toast.classList.remove('translate-y-20', 'opacity-0');
        setTimeout(() => toast.classList.add('translate-y-20', 'opacity-0'), 5000);
    }

    // ── Generic button fallbacks ──
    document.querySelectorAll('button:not([type="submit"]):not(#menuToggle):not(#openVideo):not(#closeVideo):not(.faq-toggle)')
        .forEach(btn => {
            if (!btn.onclick && !btn.hasAttribute('onclick')) {
                btn.addEventListener('click', function() {
                    const t = this.textContent.trim();
                    if (t.includes('Start Free') || t.includes('Go Pro')) showToast('Coming soon! Join our waitlist.');
                    else if (t.includes('Get Your QR Code')) scrollToSection('contact');
                });
            }
        });

    // ── Navbar tint on scroll ──
    window.addEventListener('scroll', () => {
        const navPill = document.getElementById('navPill');
        const mainNavbar = document.getElementById('mainNavbar');
        if (!navPill) return;

        if (window.scrollY > 100) {
            navPill.classList.add('bg-black/70', 'shadow-2xl', 'border-white/40');
            navPill.classList.remove('bg-white/10', 'border-white/20');
            navDropdown.classList.add('bg-black/70', 'shadow-2xl', 'border-white/40')
            if (mainNavbar) mainNavbar.classList.add('bg-black/10', 'backdrop-blur-sm');
        } else {
            navPill.classList.remove('bg-black/70', 'shadow-2xl', 'border-white/40');
            navPill.classList.add('bg-white/10', 'border-white/20');
            navDropdown.classList.remove('bg-black/70', 'shadow-2xl', 'border-white/40')
            if (mainNavbar) mainNavbar.classList.remove('bg-black/10', 'backdrop-blur-sm');
        }
    }, {
        passive: true
    });
</script>
