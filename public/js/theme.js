/**
 * Global theme toggle
 */
(function () {
    var storageKey = 'mulher-espiral-theme-override';
    var legacyModeKey = 'mulher-espiral-theme-mode';
    var legacyKey = 'mulher-espiral-theme';
    var root = document.documentElement;
    var metaThemeColor = document.getElementById('themeColorMeta');

    function getOverride() {
        try {
            var value = localStorage.getItem(storageKey) || localStorage.getItem(legacyModeKey) || localStorage.getItem(legacyKey);
            if (value === 'light' || value === 'dark') return value;
        } catch (error) {}
        return '';
    }

    function resolveTheme(override) {
        if (override === 'light' || override === 'dark') return override;
        var hour = (new Date()).getHours();
        return (hour >= 6 && hour < 19) ? 'light' : 'dark';
    }

    function setMetaThemeColor(theme) {
        if (!metaThemeColor) return;
        metaThemeColor.setAttribute('content', theme === 'light' ? '#F8F1E7' : '#0A0A0A');
    }

    function setButtons(theme, isAuto) {
        document.querySelectorAll('[data-theme-toggle]').forEach(function (button) {
            var icon = button.querySelector('.theme-toggle-icon');
            var text = button.querySelector('.theme-toggle-text');
            var label = '';
            var iconText = '';

            if (theme === 'dark') {
                label = 'Modo escuro';
                iconText = '\u263E';
            } else {
                label = 'Modo claro';
                iconText = '\u2600';
            }

            button.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
            button.setAttribute('aria-label', (isAuto ? 'Tema automatico. ' : '') + 'Clique para alternar. Duplo clique para voltar ao automatico.');

            if (icon) icon.textContent = iconText;
            if (text) text.textContent = label;
        });
    }

    function applyTheme(override) {
        var theme = resolveTheme(override);
        root.setAttribute('data-theme', theme);
        root.setAttribute('data-theme-mode', override ? 'manual' : 'auto');
        root.style.colorScheme = theme;

        setMetaThemeColor(theme);

        setButtons(theme, !override);
        return theme;
    }

    function msUntilNextBoundary() {
        var now = new Date();
        var hour = now.getHours();
        var next = new Date(now.getTime());
        if (hour < 6) {
            next.setHours(6, 0, 0, 0);
        } else if (hour < 19) {
            next.setHours(19, 0, 0, 0);
        } else {
            next.setDate(next.getDate() + 1);
            next.setHours(6, 0, 0, 0);
        }
        return Math.max(1000, next.getTime() - now.getTime());
    }

    var autoTimer = null;
    function scheduleAuto(override) {
        if (autoTimer) {
            clearTimeout(autoTimer);
            autoTimer = null;
        }
        if (override) return;
        autoTimer = setTimeout(function () {
            if ((root.getAttribute('data-theme-mode') || '') === 'auto') {
                applyTheme('');
                scheduleAuto('');
            }
        }, msUntilNextBoundary());
    }

    document.addEventListener('DOMContentLoaded', function () {
        var override = getOverride();
        applyTheme(override);
        scheduleAuto(override);

        document.querySelectorAll('[data-theme-toggle]').forEach(function (button) {
            button.addEventListener('click', function () {
                var current = root.getAttribute('data-theme') || 'dark';
                var next = current === 'dark' ? 'light' : 'dark';
                try { localStorage.setItem(storageKey, next); } catch (error) {}
                applyTheme(next);
                scheduleAuto(next);
            });
            button.addEventListener('dblclick', function () {
                try {
                    localStorage.removeItem(storageKey);
                    localStorage.removeItem(legacyModeKey);
                    localStorage.removeItem(legacyKey);
                } catch (error) {}
                applyTheme('');
                scheduleAuto('');
            });
        });
    });

    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState !== 'visible') return;
        if ((root.getAttribute('data-theme-mode') || '') === 'auto') {
            applyTheme('');
            scheduleAuto('');
        }
    });
})();
