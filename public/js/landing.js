/**
 * Landing Page - Mulher Espiral
 * Professional interactions & animations
 */
document.addEventListener('DOMContentLoaded', function () {

    // === Nav scroll effect ===
    var nav = document.getElementById('nav');
    if (nav) {
        window.addEventListener('scroll', function () {
            nav.classList.toggle('scrolled', window.scrollY > 50);
        }, { passive: true });
    }

    // === Mobile menu toggle ===
    var toggle = document.getElementById('navToggle');
    var links = document.getElementById('navLinks');
    if (toggle && links) {
        toggle.addEventListener('click', function () {
            links.classList.toggle('open');
            // Animate hamburger
            toggle.classList.toggle('active');
        });
        links.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                links.classList.remove('open');
                toggle.classList.remove('active');
            });
        });
    }

    // === FAQ accordion ===
    document.querySelectorAll('.faq-question').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var item = this.closest('.faq-item');
            var arrow = this.querySelector('.faq-arrow');
            var wasOpen = item.classList.contains('open');
            document.querySelectorAll('.faq-item').forEach(function (i) {
                i.classList.remove('open');
                var a = i.querySelector('.faq-arrow');
                if (a) a.textContent = '+';
            });
            if (!wasOpen) {
                item.classList.add('open');
                if (arrow) arrow.textContent = '\u2212';
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
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

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

    // === Counter animation for results bar ===
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
            var start = 0;
            var startTime = null;

            function easeOutQuart(t) { return 1 - Math.pow(1 - t, 4); }

            function step(timestamp) {
                if (!startTime) startTime = timestamp;
                var progress = Math.min((timestamp - startTime) / duration, 1);
                var easedProgress = easeOutQuart(progress);
                var current = Math.floor(easedProgress * target);
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

    // === Floating CTA visibility ===
    var floatingCta = document.getElementById('floatingCta');
    if (floatingCta) {
        var heroSection = document.getElementById('hero');
        var ctaSection = document.getElementById('comprar');

        window.addEventListener('scroll', function () {
            var scrollY = window.scrollY;
            var heroBottom = heroSection ? heroSection.offsetTop + heroSection.offsetHeight : 600;
            var ctaTop = ctaSection ? ctaSection.offsetTop - window.innerHeight : Infinity;

            if (scrollY > heroBottom && scrollY < ctaTop) {
                floatingCta.classList.add('show');
            } else {
                floatingCta.classList.remove('show');
            }
        }, { passive: true });
    }

    // === Stagger children animation ===
    document.querySelectorAll('.program-grid, .testimonial-stack, .bonus-list, .method-pillars').forEach(function (grid) {
        var children = grid.children;
        for (var i = 0; i < children.length; i++) {
            children[i].style.transitionDelay = (i * 0.08) + 's';
        }
    });
});
