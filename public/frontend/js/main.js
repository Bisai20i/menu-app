/**
 * MenuApp - Main JavaScript
 * Pure Vanilla JS with zero external dependencies
 */

// ============================================
// DOM Ready
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    initNavigation();
    initScrollAnimations();
    initParallax();
    initPricingToggle();
    initAccordion();
    initContactForm();
    initSmoothScroll();
});

// ============================================
// Navigation
// ============================================
function initNavigation() {
    const navbar = document.getElementById('navbar');
    const menuToggle = document.getElementById('menuToggle');
    const menuClose = document.getElementById('menuClose');
    const mobileMenu = document.getElementById('mobileMenu');
    
    // Scroll effect for navbar
    let lastScroll = 0;
    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
        
        lastScroll = currentScroll;
    }, { passive: true });
    
    // Mobile menu toggle
    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', function() {
            mobileMenu.classList.add('open');
            document.body.style.overflow = 'hidden';
        });
    }
    
    if (menuClose && mobileMenu) {
        menuClose.addEventListener('click', function() {
            mobileMenu.classList.remove('open');
            document.body.style.overflow = '';
        });
    }
    
    // Close mobile menu when clicking on links
    const mobileLinks = mobileMenu ? mobileMenu.querySelectorAll('a') : [];
    mobileLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            mobileMenu.classList.remove('open');
            document.body.style.overflow = '';
        });
    });
}

// ============================================
// Scroll Animations (Intersection Observer)
// ============================================
function initScrollAnimations() {
    const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');
    
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };
    
    const revealObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                // Add visible class with slight delay for stagger effect
                const delay = entry.target.style.transitionDelay || '0s';
                
                setTimeout(function() {
                    entry.target.classList.add('visible');
                }, parseFloat(delay) * 1000);
                
                // Unobserve after revealing
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    revealElements.forEach(function(el) {
        revealObserver.observe(el);
    });
    
    // Hero elements - reveal immediately
    const heroElements = document.querySelectorAll('#hero .reveal');
    heroElements.forEach(function(el, index) {
        setTimeout(function() {
            el.classList.add('visible');
        }, 100 + (index * 100));
    });
}

// ============================================
// Parallax Effect (Vanilla JS)
// ============================================
function initParallax() {
    const heroPhoto = document.getElementById('heroPhoto');
    const heroRightPanel = document.getElementById('heroRightPanel');
    
    if (!heroPhoto && !heroRightPanel) return;
    
    let ticking = false;
    let lastScrollY = 0;
    
    function updateParallax() {
        const scrollY = window.pageYOffset;
        const windowHeight = window.innerHeight;
        
        // Only apply parallax when hero is in view
        if (scrollY < windowHeight) {
            const parallaxValue = scrollY * 0.3;
            
            if (heroPhoto) {
                heroPhoto.style.transform = 'translateY(' + parallaxValue + 'px)';
            }
            
            if (heroRightPanel) {
                heroRightPanel.style.transform = 'translateY(' + (parallaxValue * 0.5) + 'px)';
            }
        }
        
        ticking = false;
    }
    
    window.addEventListener('scroll', function() {
        lastScrollY = window.pageYOffset;
        
        if (!ticking) {
            window.requestAnimationFrame(updateParallax);
            ticking = true;
        }
    }, { passive: true });
    
    // Mouse parallax for hero (subtle)
    const hero = document.getElementById('hero');
    if (hero && window.matchMedia('(pointer: fine)').matches) {
        hero.addEventListener('mousemove', function(e) {
            const rect = hero.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width - 0.5;
            const y = (e.clientY - rect.top) / rect.height - 0.5;
            
            if (heroPhoto) {
                heroPhoto.style.transform = 'translate(' + (x * -10) + 'px, ' + (y * -10) + 'px)';
            }
        });
        
        hero.addEventListener('mouseleave', function() {
            if (heroPhoto) {
                heroPhoto.style.transform = 'translate(0, 0)';
            }
        });
    }
}

// ============================================
// Pricing Toggle
// ============================================
function initPricingToggle() {
    const toggle = document.getElementById('pricingToggle');
    const starterPrice = document.getElementById('starterPrice');
    const starterPeriod = document.getElementById('starterPeriod');
    const proPrice = document.getElementById('proPrice');
    const proPeriod = document.getElementById('proPeriod');
    const monthlyLabel = document.getElementById('monthlyLabel');
    const yearlyLabel = document.getElementById('yearlyLabel');
    
    if (!toggle) return;
    
    let isYearly = false;
    
    toggle.addEventListener('click', function() {
        isYearly = !isYearly;
        toggle.classList.toggle('active');
        
        // Animate price change with crossfade effect
        if (starterPrice && proPrice) {
            // Fade out
            starterPrice.style.opacity = '0';
            proPrice.style.opacity = '0';
            
            setTimeout(function() {
                if (isYearly) {
                    starterPrice.textContent = 'Free';
                    starterPeriod.textContent = '';
                    proPrice.textContent = '$23';
                    proPeriod.textContent = '/month billed yearly';
                    monthlyLabel.classList.remove('text-text-primary');
                    monthlyLabel.classList.add('text-text-secondary');
                    yearlyLabel.classList.remove('text-text-secondary');
                    yearlyLabel.classList.add('text-text-primary');
                } else {
                    starterPrice.textContent = 'Free';
                    starterPeriod.textContent = '';
                    proPrice.textContent = '$29';
                    proPeriod.textContent = '/month';
                    monthlyLabel.classList.add('text-text-primary');
                    monthlyLabel.classList.remove('text-text-secondary');
                    yearlyLabel.classList.add('text-text-secondary');
                    yearlyLabel.classList.remove('text-text-primary');
                }
                
                // Fade in
                starterPrice.style.opacity = '1';
                proPrice.style.opacity = '1';
            }, 200);
        }
    });
    
    // Add transition styles
    if (starterPrice && proPrice) {
        starterPrice.style.transition = 'opacity 0.2s ease';
        proPrice.style.transition = 'opacity 0.2s ease';
    }
}

// ============================================
// Accordion
// ============================================
function initAccordion() {
    const accordionItems = document.querySelectorAll('[data-accordion]');
    
    accordionItems.forEach(function(item) {
        const button = item.querySelector('button');
        const content = item.querySelector('.accordion-content');
        const number = item.querySelector('.accordion-number');
        
        if (!button || !content) return;
        
        button.addEventListener('click', function() {
            const isOpen = item.classList.contains('open');
            
            // Close all other accordions
            accordionItems.forEach(function(otherItem) {
                if (otherItem !== item) {
                    otherItem.classList.remove('open');
                    const otherContent = otherItem.querySelector('.accordion-content');
                    const otherNumber = otherItem.querySelector('.accordion-number');
                    if (otherContent) otherContent.classList.remove('open');
                    if (otherNumber) {
                        otherNumber.classList.remove('text-accent');
                        otherNumber.classList.add('text-text-secondary');
                    }
                }
            });
            
            // Toggle current accordion
            if (isOpen) {
                item.classList.remove('open');
                content.classList.remove('open');
                if (number) {
                    number.classList.remove('text-accent');
                    number.classList.add('text-text-secondary');
                }
            } else {
                item.classList.add('open');
                content.classList.add('open');
                if (number) {
                    number.classList.add('text-accent');
                    number.classList.remove('text-text-secondary');
                }
            }
        });
    });
}

// ============================================
// Contact Form
// ============================================
function initContactForm() {
    const form = document.getElementById('contactForm');
    
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const message = document.getElementById('message').value;
        
        // Simple validation
        if (!name || !email || !message) {
            alert('Please fill in all fields.');
            return;
        }
        
        // Show success message
        const button = form.querySelector('button[type="submit"]');
        const originalText = button.textContent;
        
        button.textContent = 'Message Sent!';
        button.style.background = '#C62828';
        button.style.color = '#0B0C10';
        
        // Reset form
        form.reset();
        
        // Reset button after delay
        setTimeout(function() {
            button.textContent = originalText;
            button.style.background = '';
            button.style.color = '';
        }, 3000);
        
        // Log form data (in production, send to server)
        console.log('Form submitted:', { name, email, message });
    });
}

// ============================================
// Smooth Scroll
// ============================================
function initSmoothScroll() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(function(link) {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            if (href === '#') return;
            
            const target = document.querySelector(href);
            
            if (target) {
                e.preventDefault();
                
                const offsetTop = target.offsetTop - 80; // Account for fixed nav
                
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// ============================================
// Performance: Prefers Reduced Motion
// ============================================
if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    // Disable parallax and complex animations
    document.documentElement.style.setProperty('--animation-duration', '0.01ms');
    
    // Make all elements visible immediately
    const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');
    revealElements.forEach(function(el) {
        el.classList.add('visible');
        el.style.transition = 'none';
    });
}