/**
 * Landing Page - Mulher Espiral
 * Professional interactions & animations
 */
document.addEventListener('DOMContentLoaded', function () {

    // === Throttle utility ===
    function throttle(fn, delay) {
        var last = 0;
        var timer = null;
        return function () {
            var now = Date.now();
            var remaining = delay - (now - last);
            if (remaining <= 0) {
                if (timer) { clearTimeout(timer); timer = null; }
                last = now;
                fn();
            } else if (!timer) {
                timer = setTimeout(function () {
                    last = Date.now();
                    timer = null;
                    fn();
                }, remaining);
            }
        };
    }

    // === Nav scroll effect ===
    var nav = document.getElementById('nav');
    var floatingCta = document.getElementById('floatingCta');
    var heroSection = document.getElementById('hero');
    var ctaSection = document.getElementById('comprar');
    var scrollTopBtn = document.getElementById('scrollTop');
    var heroSpiral = document.getElementById('heroSpiral');

    function onScroll() {
        var scrollY = window.scrollY;

        // Nav background
        if (nav) {
            nav.classList.toggle('scrolled', scrollY > 50);
        }

        // Floating CTA visibility
        if (floatingCta) {
            var heroBottom = heroSection ? heroSection.offsetTop + heroSection.offsetHeight : 600;
            var ctaTop = ctaSection ? ctaSection.offsetTop - window.innerHeight : Infinity;
            floatingCta.classList.toggle('show', scrollY > heroBottom && scrollY < ctaTop);
        }

        // Scroll to top button
        if (scrollTopBtn) {
            scrollTopBtn.classList.toggle('show', scrollY > 800);
        }
    }

    window.addEventListener('scroll', throttle(onScroll, 100), { passive: true });

    if (heroSpiral && !(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches)) {
        var spiralTicking = false;
        var spiralCard = heroSpiral.querySelector('.hero-spiral3d-card') || heroSpiral;

        function clamp01(v) {
            return Math.max(0, Math.min(1, v));
        }

        function updateSpiral() {
            spiralTicking = false;
            if (!heroSection) return;
            var rect = heroSection.getBoundingClientRect();
            var progress = clamp01((0 - rect.top) / (rect.height * 0.9));
            var y = progress * 42;
            var rz = -10 + (progress * 6);
            var rx = 10 + (progress * 8);
            var ry = -14 + (progress * 10);
            var s = 1 + (progress * 0.05);
            spiralCard.style.transform = 'translate3d(0,' + y.toFixed(2) + 'px,0) rotateZ(' + rz.toFixed(2) + 'deg) rotateX(' + rx.toFixed(2) + 'deg) rotateY(' + ry.toFixed(2) + 'deg) scale(' + s.toFixed(4) + ')';
        }

        function onSpiralScroll() {
            if (spiralTicking) return;
            spiralTicking = true;
            requestAnimationFrame(updateSpiral);
        }

        window.addEventListener('scroll', onSpiralScroll, { passive: true });
        window.addEventListener('resize', onSpiralScroll);
        requestAnimationFrame(updateSpiral);
    }

    // === Scroll to top button ===
    if (scrollTopBtn) {
        scrollTopBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // === Mobile menu toggle ===
    var toggle = document.getElementById('navToggle');
    var links = document.getElementById('navLinks');
    if (toggle && links) {
        toggle.addEventListener('click', function () {
            var isOpen = links.classList.toggle('open');
            toggle.classList.toggle('active');
            toggle.setAttribute('aria-expanded', isOpen);
        });
        links.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                links.classList.remove('open');
                toggle.classList.remove('active');
                toggle.setAttribute('aria-expanded', 'false');
            });
        });
    }

    // === FAQ accordion (CSS-only arrow rotation, no text change) ===
    document.querySelectorAll('.faq-question').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var item = this.closest('.faq-item');
            var wasOpen = item.classList.contains('open');

            // Close all
            document.querySelectorAll('.faq-item.open').forEach(function (i) {
                i.classList.remove('open');
                i.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
            });

            // Toggle current
            if (!wasOpen) {
                item.classList.add('open');
                this.setAttribute('aria-expanded', 'true');
            }
        });
    });

    // === Smooth scroll ===
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            var id = this.getAttribute('href');
            if (id === '#') return;
            var target = document.querySelector(id);
            if (target) {
                e.preventDefault();
                var offset = nav ? nav.offsetHeight + 20 : 20;
                var top = target.getBoundingClientRect().top + window.pageYOffset - offset;
                window.scrollTo({ top: top, behavior: 'smooth' });
            }
        });
    });

    // === Fade-in on scroll (IntersectionObserver) ===
    if ('IntersectionObserver' in window) {
        var fadeObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    fadeObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -30px 0px' });

        document.querySelectorAll('.section, .program-item, .testimonial-item, .bonus-item, .method-pillar, .price-card').forEach(function (el) {
            el.classList.add('fade-up');
            fadeObserver.observe(el);
        });

        // Make hero always visible
        var hero = document.getElementById('hero');
        if (hero) {
            hero.classList.remove('fade-up');
            hero.style.opacity = '1';
            hero.style.transform = 'none';
        }
    }

    // === Counter animation for proof bar ===
    var countersAnimated = false;
    var counterElements = document.querySelectorAll('.result-number[data-count]');
    if (counterElements.length > 0 && 'IntersectionObserver' in window) {
        var counterObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting && !countersAnimated) {
                    countersAnimated = true;
                    animateCounters();
                    counterObserver.disconnect();
                }
            });
        }, { threshold: 0.3 });

        var resultsBar = document.querySelector('.results-bar');
        if (resultsBar) counterObserver.observe(resultsBar);
    }

    function animateCounters() {
        counterElements.forEach(function (el) {
            var target = parseInt(el.getAttribute('data-count'), 10);
            var duration = 2000;
            var startTime = null;

            function easeOutQuart(t) { return 1 - Math.pow(1 - t, 4); }

            function step(timestamp) {
                if (!startTime) startTime = timestamp;
                var progress = Math.min((timestamp - startTime) / duration, 1);
                var current = Math.floor(easeOutQuart(progress) * target);
                el.textContent = current.toLocaleString('pt-BR');
                if (progress < 1) {
                    requestAnimationFrame(step);
                } else {
                    el.textContent = target.toLocaleString('pt-BR');
                }
            }
            requestAnimationFrame(step);
        });
    }

    // === Stagger children animation ===
    document.querySelectorAll('.program-grid, .testimonial-stack, .bonus-list, .method-pillars').forEach(function (grid) {
        var children = grid.children;
        for (var i = 0; i < children.length; i++) {
            children[i].style.transitionDelay = (i * 0.08) + 's';
        }
    });

    // === Button loading on form submit ===
    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function () {
            var btn = form.querySelector('[type="submit"], .btn');
            if (btn && !btn.classList.contains('is-loading')) {
                btn.classList.add('is-loading');
                setTimeout(function () { btn.classList.remove('is-loading'); }, 8000);
            }
        });
    });
});
