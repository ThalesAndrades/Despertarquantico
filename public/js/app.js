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
    document.body.appendChild(overlay);

    function openSidebar() {
        if (sidebar) {
            sidebar.classList.add('open');
            overlay.classList.add('show');
        }
    }

    function closeSidebar() {
        if (sidebar) {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
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

    // === Auto-dismiss alerts after 5s ===
    document.querySelectorAll('.alert').forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity 0.5s, transform 0.5s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(function () { alert.remove(); }, 500);
        }, 5000);
    });

    // === Confirm dialogs for destructive actions ===
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            if (!confirm(this.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });
});
