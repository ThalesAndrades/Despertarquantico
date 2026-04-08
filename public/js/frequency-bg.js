(function () {
    var minHz = 200;
    var maxHz = 1000;
    var storageKey = 'mulher-espiral-frequency-settings';

    function clamp(value, min, max) {
        return Math.min(max, Math.max(min, value));
    }

    function createEl(tag, attrs) {
        var el = document.createElement(tag);
        if (!attrs) return el;
        Object.keys(attrs).forEach(function (key) {
            if (key === 'className') el.className = attrs[key];
            else if (key === 'text') el.textContent = attrs[key];
            else el.setAttribute(key, attrs[key]);
        });
        return el;
    }

    function loadState() {
        try {
            var raw = localStorage.getItem(storageKey);
            if (!raw) return null;
            var parsed = JSON.parse(raw);
            if (!parsed) return null;
            return {
                hz: clamp(parseInt(parsed.hz, 10) || 432, minHz, maxHz),
                intensity: clamp(parseInt(parsed.intensity, 10) || 40, 0, 100),
            };
        } catch (error) {
            return null;
        }
    }

    function saveState(state) {
        try {
            localStorage.setItem(storageKey, JSON.stringify(state));
        } catch (error) {}
    }

    function prefersReducedMotion() {
        return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }

    function init() {
        if (document.getElementById('frequency-background')) {
            return;
        }

        var state = loadState() || { hz: 432, intensity: 40 };
        var body = document.body;
        if (!body) return;

        body.classList.add('has-frequency-bg');

        var background = createEl('div', { id: 'frequency-background', className: 'frequency-background', 'aria-hidden': 'true' });
        var canvas = createEl('canvas', { id: 'frequency-canvas', className: 'frequency-canvas' });
        background.appendChild(canvas);

        var controls = createEl('div', { id: 'frequency-controls', className: 'frequency-controls' });
        controls.appendChild(createEl('div', { className: 'frequency-controls-title', text: 'Frequência' }));

        var rowHz = createEl('div', { className: 'frequency-controls-row' });
        var hzLabel = createEl('div', { className: 'frequency-controls-label', text: 'Hz' });
        var hzValue = createEl('div', { className: 'frequency-controls-value', text: state.hz + ' Hz' });
        rowHz.appendChild(hzLabel);
        rowHz.appendChild(hzValue);
        controls.appendChild(rowHz);

        var hzFine = createEl('div', { className: 'frequency-controls-fine' });
        var btnDec = createEl('button', { type: 'button', className: 'frequency-btn', 'aria-label': 'Diminuir 1 Hz', text: '−' });
        var hzRange = createEl('input', { type: 'range', className: 'frequency-range', min: String(minHz), max: String(maxHz), step: '1', value: String(state.hz) });
        var btnInc = createEl('button', { type: 'button', className: 'frequency-btn', 'aria-label': 'Aumentar 1 Hz', text: '+' });
        hzFine.appendChild(btnDec);
        hzFine.appendChild(hzRange);
        hzFine.appendChild(btnInc);
        controls.appendChild(hzFine);

        var rowIntensity = createEl('div', { className: 'frequency-controls-row' });
        var intensityLabel = createEl('div', { className: 'frequency-controls-label', text: 'Intensidade' });
        var intensityValue = createEl('div', { className: 'frequency-controls-value', text: state.intensity + '%' });
        rowIntensity.appendChild(intensityLabel);
        rowIntensity.appendChild(intensityValue);
        controls.appendChild(rowIntensity);

        var intensityRange = createEl('input', { type: 'range', className: 'frequency-range', min: '0', max: '100', step: '1', value: String(state.intensity), 'aria-label': 'Intensidade do ruído' });
        controls.appendChild(intensityRange);

        var rowAudio = createEl('div', { className: 'frequency-controls-row' });
        var audioLabel = createEl('div', { className: 'frequency-controls-label', text: 'Som' });
        var audioButton = createEl('button', { type: 'button', className: 'frequency-audio-btn', 'aria-label': 'Ativar som', 'aria-pressed': 'false', text: 'Mudo' });
        rowAudio.appendChild(audioLabel);
        rowAudio.appendChild(audioButton);
        controls.appendChild(rowAudio);

        var footer = createEl('div', { className: 'frequency-controls-footer', text: '200–1000 Hz • Ajuste em tempo real' });
        controls.appendChild(footer);

        body.insertBefore(background, body.firstChild);
        body.appendChild(controls);

        var ctx = canvas.getContext('2d', { alpha: true });
        var rafId = 0;
        var lastT = 0;
        var dpr = Math.max(1, Math.min(2, window.devicePixelRatio || 1));

        var audio = {
            enabled: false,
            context: null,
            oscillator: null,
            noiseSource: null,
            noiseFilter: null,
            gain: null,
        };

        function audioGainFromIntensity(intensityPercent) {
            var x = clamp(intensityPercent, 0, 100) / 100;
            return 0.0001 + (x * x) * 0.08;
        }

        function ensureAudio() {
            if (audio.context) return true;
            var AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return false;

            try {
                audio.context = new AudioContext();
                audio.gain = audio.context.createGain();
                audio.gain.gain.value = audioGainFromIntensity(state.intensity);
                audio.gain.connect(audio.context.destination);

                audio.oscillator = audio.context.createOscillator();
                audio.oscillator.type = 'sine';
                audio.oscillator.frequency.value = state.hz;

                var bufferSize = audio.context.sampleRate * 2;
                var noiseBuffer = audio.context.createBuffer(1, bufferSize, audio.context.sampleRate);
                var data = noiseBuffer.getChannelData(0);
                for (var i = 0; i < bufferSize; i++) {
                    data[i] = Math.random() * 2 - 1;
                }
                audio.noiseSource = audio.context.createBufferSource();
                audio.noiseSource.buffer = noiseBuffer;
                audio.noiseSource.loop = true;

                audio.noiseFilter = audio.context.createBiquadFilter();
                audio.noiseFilter.type = 'bandpass';
                audio.noiseFilter.frequency.value = state.hz;
                audio.noiseFilter.Q.value = 14;

                var noiseGain = audio.context.createGain();
                noiseGain.gain.value = 0.25;

                audio.oscillator.connect(audio.gain);
                audio.noiseSource.connect(audio.noiseFilter);
                audio.noiseFilter.connect(noiseGain);
                noiseGain.connect(audio.gain);

                audio.oscillator.start();
                audio.noiseSource.start();
                return true;
            } catch (error) {
                return false;
            }
        }

        function stopAudio() {
            audio.enabled = false;
            if (audio.oscillator) {
                try { audio.oscillator.stop(); } catch (error) {}
            }
            if (audio.noiseSource) {
                try { audio.noiseSource.stop(); } catch (error) {}
            }
            if (audio.context) {
                try { audio.context.close(); } catch (error) {}
            }
            audio.context = null;
            audio.oscillator = null;
            audio.noiseSource = null;
            audio.noiseFilter = null;
            audio.gain = null;
        }

        function setAudioEnabled(enabled) {
            audio.enabled = !!enabled;
            if (audio.enabled) {
                var ok = ensureAudio();
                if (ok && audio.context && audio.context.state === 'suspended') {
                    audio.context.resume().catch(function () {});
                }
            } else {
                stopAudio();
            }
            audioButton.textContent = audio.enabled ? 'Ativo' : 'Mudo';
            audioButton.setAttribute('aria-pressed', audio.enabled ? 'true' : 'false');
            audioButton.setAttribute('aria-label', audio.enabled ? 'Desativar som' : 'Ativar som');
        }

        function resize() {
            var w = window.innerWidth;
            var h = window.innerHeight;
            canvas.width = Math.floor(w * dpr);
            canvas.height = Math.floor(h * dpr);
            canvas.style.width = w + 'px';
            canvas.style.height = h + 'px';
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            draw(0);
        }

        function getTheme() {
            return document.documentElement.getAttribute('data-theme') === 'light' ? 'light' : 'dark';
        }

        function lineColor(alpha) {
            var theme = getTheme();
            if (theme === 'light') {
                return 'rgba(25, 20, 14, ' + alpha + ')';
            }
            return 'rgba(255, 255, 255, ' + alpha + ')';
        }

        function accentColor(alpha) {
            var theme = getTheme();
            if (theme === 'light') {
                return 'rgba(201, 168, 76, ' + alpha + ')';
            }
            return 'rgba(201, 168, 76, ' + alpha + ')';
        }

        function draw(timeMs) {
            var w = window.innerWidth;
            var h = window.innerHeight;
            ctx.clearRect(0, 0, w, h);

            var hz = state.hz;
            var intensity = state.intensity / 100;
            var speed = 0.00035 * hz;
            var t = timeMs * speed;

            var lines = Math.round(14 + (hz - minHz) / 55);
            var baseAmp = 6 + 22 * intensity;
            var noiseAmp = 2 + 18 * intensity;
            var lineGap = h / (lines + 1);

            var baseAlpha = 0.10 + 0.12 * intensity;
            var accentAlpha = 0.10 + 0.16 * intensity;

            for (var i = 1; i <= lines; i++) {
                var y0 = i * lineGap;
                var phase = (i * 0.37) + t;
                var alpha = baseAlpha * (0.8 + 0.2 * Math.sin(i));
                ctx.lineWidth = 1;
                ctx.strokeStyle = lineColor(alpha);
                ctx.beginPath();

                var x = 0;
                var step = Math.max(8, Math.floor(w / 140));
                ctx.moveTo(0, y0);
                for (x = 0; x <= w; x += step) {
                    var wave = Math.sin((x / w) * Math.PI * 2 * (1 + hz / 280) + phase);
                    var micro = Math.sin((x / w) * Math.PI * 2 * (7 + hz / 90) - phase * 1.4);
                    var jitter = micro * noiseAmp;
                    var y = y0 + wave * baseAmp + jitter;
                    ctx.lineTo(x, y);
                }
                ctx.stroke();
            }

            var glowY = h * 0.72;
            var grad = ctx.createRadialGradient(w * 0.78, glowY, 40, w * 0.78, glowY, Math.max(w, h) * 0.55);
            grad.addColorStop(0, accentColor(accentAlpha));
            grad.addColorStop(1, accentColor(0));
            ctx.fillStyle = grad;
            ctx.fillRect(0, 0, w, h);
        }

        function loop(ts) {
            if (!lastT) lastT = ts;
            var dt = ts - lastT;
            if (dt >= 33) {
                lastT = ts;
                draw(ts);
            }
            rafId = requestAnimationFrame(loop);
        }

        function updateHz(value) {
            state.hz = clamp(value, minHz, maxHz);
            hzRange.value = String(state.hz);
            hzValue.textContent = state.hz + ' Hz';
            saveState(state);
            draw(performance.now());
            if (audio.enabled && audio.oscillator) {
                try { audio.oscillator.frequency.setTargetAtTime(state.hz, audio.context.currentTime, 0.015); } catch (error) {}
            }
            if (audio.enabled && audio.noiseFilter) {
                try { audio.noiseFilter.frequency.setTargetAtTime(state.hz, audio.context.currentTime, 0.02); } catch (error) {}
            }
        }

        function updateIntensity(value) {
            state.intensity = clamp(value, 0, 100);
            intensityRange.value = String(state.intensity);
            intensityValue.textContent = state.intensity + '%';
            saveState(state);
            draw(performance.now());
            if (audio.enabled && audio.gain) {
                try { audio.gain.gain.setTargetAtTime(audioGainFromIntensity(state.intensity), audio.context.currentTime, 0.03); } catch (error) {}
            }
        }

        hzRange.addEventListener('input', function () {
            updateHz(parseInt(hzRange.value, 10));
        });

        intensityRange.addEventListener('input', function () {
            updateIntensity(parseInt(intensityRange.value, 10));
        });

        btnDec.addEventListener('click', function () {
            updateHz(state.hz - 1);
        });

        btnInc.addEventListener('click', function () {
            updateHz(state.hz + 1);
        });

        audioButton.addEventListener('click', function () {
            setAudioEnabled(!audio.enabled);
        });

        window.addEventListener('resize', resize, { passive: true });
        window.addEventListener('pagehide', stopAudio, { passive: true });

        resize();

        window.FrequencyBackground = {
            setHz: updateHz,
            setIntensity: updateIntensity,
            getState: function () {
                return { hz: state.hz, intensity: state.intensity };
            },
            setAudioEnabled: setAudioEnabled,
            isAudioEnabled: function () {
                return audio.enabled;
            },
        };

        setAudioEnabled(false);

        if (!prefersReducedMotion()) {
            rafId = requestAnimationFrame(loop);
        } else {
            draw(performance.now());
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();

