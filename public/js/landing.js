/**
 * Landing Page - Mulher Espiral
 * Professional interactions & animations
 */
document.addEventListener('DOMContentLoaded', function () {
    var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

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
        function closeMenu() {
            links.classList.remove('open');
            toggle.classList.remove('active');
            toggle.setAttribute('aria-expanded', 'false');
            toggle.setAttribute('aria-label', 'Abrir menu');
            document.body.classList.remove('sidebar-open');
        }

        toggle.addEventListener('click', function () {
            links.classList.toggle('open');
            toggle.classList.toggle('active');
            var isOpen = links.classList.contains('open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            toggle.setAttribute('aria-label', isOpen ? 'Fechar menu' : 'Abrir menu');
            document.body.classList.toggle('sidebar-open', isOpen);
        });

        links.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                closeMenu();
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && links.classList.contains('open')) {
                closeMenu();
                toggle.focus();
            }
        });
    }

    // === FAQ accordion ===
    document.querySelectorAll('.faq-question').forEach(function (btn) {
        var answer = btn.nextElementSibling;
        if (answer && !answer.id) {
            answer.id = 'faq-answer-' + Math.random().toString(36).slice(2, 8);
        }
        if (answer) {
            btn.setAttribute('aria-controls', answer.id);
            btn.setAttribute('aria-expanded', 'false');
        }

        btn.addEventListener('click', function () {
            var item = this.closest('.faq-item');
            var arrow = this.querySelector('.faq-arrow');
            var wasOpen = item.classList.contains('open');
            document.querySelectorAll('.faq-item').forEach(function (i) {
                i.classList.remove('open');
                var trigger = i.querySelector('.faq-question');
                if (trigger) {
                    trigger.setAttribute('aria-expanded', 'false');
                }
                var a = i.querySelector('.faq-arrow');
                if (a) a.textContent = '+';
            });
            if (!wasOpen) {
                item.classList.add('open');
                this.setAttribute('aria-expanded', 'true');
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
                window.scrollTo({ top: top, behavior: prefersReducedMotion ? 'auto' : 'smooth' });
            }
        });
    });

    // === Fade-in on scroll (IntersectionObserver) ===
    if (!prefersReducedMotion && 'IntersectionObserver' in window) {
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

    // === Story scroll sync ===
    var storySteps = document.querySelectorAll('[data-story-step]');
    var storyImages = document.querySelectorAll('[data-story-image]');
    if (!prefersReducedMotion && storySteps.length > 0 && storyImages.length > 0 && 'IntersectionObserver' in window) {
        var storyObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }

                var activeIndex = entry.target.getAttribute('data-story-step');

                storySteps.forEach(function (step) {
                    step.classList.toggle('is-active', step.getAttribute('data-story-step') === activeIndex);
                });

                storyImages.forEach(function (image) {
                    image.classList.toggle('is-active', image.getAttribute('data-story-image') === activeIndex);
                });
            });
        }, { threshold: 0.45, rootMargin: '-10% 0px -20% 0px' });

        storySteps.forEach(function (step) {
            storyObserver.observe(step);
        });
    }

    // === Soft hero parallax ===
    if (!prefersReducedMotion) {
        var heroVisual = document.querySelector('.hero-visual-frame');
        if (heroVisual) {
            window.addEventListener('scroll', function () {
                var offset = Math.min(window.scrollY * 0.08, 28);
                heroVisual.style.transform = 'translate3d(0,' + offset + 'px,0)';
            }, { passive: true });
        }
    }

    // === Counter animation for results bar ===
    var countersAnimated = false;
    var counterElements = document.querySelectorAll('.result-number[data-count]');
    if (!prefersReducedMotion && counterElements.length > 0 && 'IntersectionObserver' in window) {
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
    if (!prefersReducedMotion) {
        document.querySelectorAll('.program-grid, .testimonial-stack, .bonus-list, .method-pillars').forEach(function (grid) {
            var children = grid.children;
            for (var i = 0; i < children.length; i++) {
                children[i].style.transitionDelay = (i * 0.08) + 's';
            }
        });
    }
});
