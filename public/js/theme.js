/**
 * Global theme toggle
 */
(function () {
    var storageKey = 'mulher-espiral-theme';
    var root = document.documentElement;
    var metaThemeColor = document.getElementById('themeColorMeta');

    function getPreferredTheme() {
        try {
            var savedTheme = localStorage.getItem(storageKey);
            if (savedTheme === 'light' || savedTheme === 'dark') {
                return savedTheme;
            }
        } catch (error) {}

        return window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches
            ? 'light'
            : 'dark';
    }

    function applyTheme(theme) {
        root.setAttribute('data-theme', theme);
        root.style.colorScheme = theme;

        if (metaThemeColor) {
            metaThemeColor.setAttribute('content', theme === 'light' ? '#F8F1E7' : '#0A0A0A');
        }

        try {
            localStorage.setItem(storageKey, theme);
        } catch (error) {}

        document.querySelectorAll('[data-theme-toggle]').forEach(function (button) {
            var isDark = theme === 'dark';
            button.setAttribute('aria-pressed', String(!isDark));
            button.setAttribute('aria-label', isDark ? 'Ativar modo claro' : 'Ativar modo escuro');

            var icon = button.querySelector('.theme-toggle-icon');
            var text = button.querySelector('.theme-toggle-text');

            if (icon) {
                icon.textContent = isDark ? '☀' : '☾';
            }

            if (text) {
                text.textContent = isDark ? 'Modo claro' : 'Modo escuro';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        applyTheme(getPreferredTheme());

        document.querySelectorAll('[data-theme-toggle]').forEach(function (button) {
            button.addEventListener('click', function () {
                var nextTheme = root.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
                applyTheme(nextTheme);
            });
        });
    });
})();
