/**
 * Global App JS - Member Area
 * Sidebar toggle, mobile menu, smooth interactions
 */
document.addEventListener('DOMContentLoaded', function () {

    // === Sidebar toggle (mobile) ===
    var sidebar = document.getElementById('sidebar');
    var toggle = document.getElementById('sidebarToggle');
    var overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    overlay.setAttribute('aria-hidden', 'true');
    document.body.appendChild(overlay);

    function openSidebar() {
        if (sidebar) {
            sidebar.classList.add('open');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeSidebar() {
        if (sidebar) {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    if (toggle) {
        toggle.addEventListener('click', function () {
            if (sidebar && sidebar.classList.contains('open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    }

    overlay.addEventListener('click', closeSidebar);

    // Close sidebar on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && sidebar && sidebar.classList.contains('open')) {
            closeSidebar();
        }
    });

    // === Alert close buttons & auto-dismiss ===
    document.querySelectorAll('.alert').forEach(function (alert) {
        // Add close button
        var closeBtn = document.createElement('button');
        closeBtn.className = 'alert-close';
        closeBtn.innerHTML = '&times;';
        closeBtn.setAttribute('aria-label', 'Fechar');
        closeBtn.addEventListener('click', function () {
            dismissAlert(alert);
        });
        alert.appendChild(closeBtn);

        // Auto-dismiss after 6s
        setTimeout(function () {
            dismissAlert(alert);
        }, 6000);
    });

    function dismissAlert(el) {
        if (!el || el.dataset.dismissed) return;
        el.dataset.dismissed = 'true';
        el.style.transition = 'opacity 0.4s, transform 0.4s';
        el.style.opacity = '0';
        el.style.transform = 'translateY(-8px)';
        setTimeout(function () { el.remove(); }, 400);
    }

    // === Button loading state on form submit ===
    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function () {
            var btn = form.querySelector('[type="submit"]');
            if (btn && !btn.classList.contains('is-loading')) {
                btn.classList.add('is-loading');
                // Safety: remove loading after 8s in case of slow response
                setTimeout(function () {
                    btn.classList.remove('is-loading');
                }, 8000);
            }
        });
    });

    // === Confirm dialogs for destructive actions ===
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            if (!confirm(this.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });

    // === Content appear animation ===
    var mainInner = document.querySelector('.main-inner');
    if (mainInner) {
        mainInner.classList.add('content-appear');
    }
});
