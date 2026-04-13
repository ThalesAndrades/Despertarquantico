(function () {
    function clamp(value, min, max) {
        return Math.min(max, Math.max(min, value));
    }

    function normalizeText(value) {
        return (value || '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9\s]/g, ' ')
            .replace(/\s+/g, ' ')
            .trim();
    }

    function hashToHz(value) {
        var text = normalizeText(value);
        if (!text) return 432;
        var hash = 7;
        for (var i = 0; i < text.length; i++) {
            hash = (hash * 31 + text.charCodeAt(i)) % 1000000;
        }
        return 200 + (hash % 801);
    }

    function computeResult(personal, family, professional) {
        var hzPersonal = hashToHz(personal);
        var hzFamily = hashToHz(family);
        var hzProfessional = hashToHz(professional);
        var hz = Math.round((hzPersonal * 0.40) + (hzFamily * 0.30) + (hzProfessional * 0.30));
        hz = clamp(hz, 200, 1000);

        var totalLen = normalizeText(personal).length + normalizeText(family).length + normalizeText(professional).length;
        var intensity = clamp(Math.round(20 + Math.min(60, totalLen * 1.6)), 10, 85);

        var band;
        if (hz <= 360) band = { title: 'Acolhimento', desc: 'Seu campo pede pausas, protecao emocional e reconexao com o corpo.' };
        else if (hz <= 520) band = { title: 'Clareza', desc: 'Sua energia esta organizando o caminho. Decisoes simples destravam a vida.' };
        else if (hz <= 740) band = { title: 'Ativacao', desc: 'Fase de movimento consciente. Aja com firmeza e limite o excesso de informacao.' };
        else band = { title: 'Expansao', desc: 'Alta coerencia. Foque em um proximo passo por vez e sustente consistencia.' };

        return { hz: hz, intensity: intensity, band: band };
    }

    function setGlobalFrequency(hz, intensity) {
        if (window.FrequencyBackground && typeof window.FrequencyBackground.setHz === 'function') {
            window.FrequencyBackground.setHz(hz);
        }
        if (window.FrequencyBackground && typeof window.FrequencyBackground.setIntensity === 'function') {
            window.FrequencyBackground.setIntensity(intensity);
        }
    }

    function initTool(root) {
        var inputPersonal = root.querySelector('[data-ft-input="personal"]');
        var inputFamily = root.querySelector('[data-ft-input="family"]');
        var inputProfessional = root.querySelector('[data-ft-input="professional"]');
        var steps = Array.prototype.slice.call(root.querySelectorAll('[data-ft-step]'));
        var stepDots = Array.prototype.slice.call(root.querySelectorAll('[data-ft-dot]'));
        var btnNext = root.querySelector('[data-ft-next]');
        var btnBack = root.querySelector('[data-ft-back]');
        var btnApply = root.querySelector('[data-ft-apply]');
        var btnMinus = root.querySelector('[data-ft-minus]');
        var btnPlus = root.querySelector('[data-ft-plus]');
        var rangeHz = root.querySelector('[data-ft-range]');
        var valueHz = root.querySelector('[data-ft-value]');
        var titleBand = root.querySelector('[data-ft-band-title]');
        var descBand = root.querySelector('[data-ft-band-desc]');
        var meterFill = root.querySelector('[data-ft-meter]');

        var active = 0;
        var result = computeResult('', '', '');

        function updateStep() {
            steps.forEach(function (s, idx) {
                s.style.display = idx === active ? 'block' : 'none';
            });
            stepDots.forEach(function (d, idx) {
                d.classList.toggle('is-active', idx === active);
            });
            if (btnBack) btnBack.disabled = active === 0;
            if (btnNext) btnNext.style.display = active < 2 ? 'inline-flex' : 'none';
            if (btnApply) btnApply.style.display = active === 2 ? 'inline-flex' : 'none';
        }

        function refreshResult() {
            result = computeResult(inputPersonal.value, inputFamily.value, inputProfessional.value);
            var hz = result.hz;
            if (valueHz) valueHz.textContent = hz + ' Hz';
            if (rangeHz) rangeHz.value = String(hz);
            if (titleBand) titleBand.textContent = result.band.title;
            if (descBand) descBand.textContent = result.band.desc;
            if (meterFill) {
                var pct = Math.round(((hz - 200) / 800) * 100);
                meterFill.style.width = pct + '%';
            }
        }

        function apply() {
            refreshResult();
            setGlobalFrequency(result.hz, result.intensity);
        }

        steps.forEach(function (step) {
            var input = step.querySelector('input');
            if (input) {
                input.addEventListener('input', refreshResult);
            }
        });

        if (btnNext) {
            btnNext.addEventListener('click', function () {
                active = Math.min(2, active + 1);
                updateStep();
            });
        }

        if (btnBack) {
            btnBack.addEventListener('click', function () {
                active = Math.max(0, active - 1);
                updateStep();
            });
        }

        if (btnApply) {
            btnApply.addEventListener('click', apply);
        }

        if (btnMinus) {
            btnMinus.addEventListener('click', function () {
                refreshResult();
                var hz = clamp(result.hz - 1, 200, 1000);
                setGlobalFrequency(hz, result.intensity);
                if (valueHz) valueHz.textContent = hz + ' Hz';
                if (rangeHz) rangeHz.value = String(hz);
            });
        }

        if (btnPlus) {
            btnPlus.addEventListener('click', function () {
                refreshResult();
                var hz = clamp(result.hz + 1, 200, 1000);
                setGlobalFrequency(hz, result.intensity);
                if (valueHz) valueHz.textContent = hz + ' Hz';
                if (rangeHz) rangeHz.value = String(hz);
            });
        }

        if (rangeHz) {
            rangeHz.addEventListener('input', function () {
                var hz = clamp(parseInt(rangeHz.value, 10) || 432, 200, 1000);
                refreshResult();
                setGlobalFrequency(hz, result.intensity);
                if (valueHz) valueHz.textContent = hz + ' Hz';
            });
        }

        refreshResult();
        updateStep();
    }

    function init() {
        document.querySelectorAll('[data-frequency-tool]').forEach(function (root) {
            initTool(root);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();

