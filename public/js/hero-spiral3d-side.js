function webglSupported() {
    try {
        var canvas = document.createElement('canvas');
        return !!(window.WebGLRenderingContext && (canvas.getContext('webgl') || canvas.getContext('experimental-webgl')));
    } catch (e) {
        return false;
    }
}

function clamp01(v) {
    return Math.max(0, Math.min(1, v));
}

export async function mountHeroSpiral3D(rootEl) {
    var root = rootEl || document.getElementById('heroSpiral');
    if (!root) return;

    var prefersReduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!webglSupported() || prefersReduce) {
        root.dataset.mode = 'poster';
        return;
    }

    var THREE;
    try {
        var threeUrl = new URL('../vendor/three/three.module.js', import.meta.url).href;
        THREE = await import(threeUrl);
    } catch (e) {
        root.dataset.mode = 'poster';
        return;
    }

    var card = root.querySelector('.hero-spiral3d-card') || root;
    var poster = root.querySelector('.hero-spiral3d-poster');

    var renderer = null;
    var scene = null;
    var camera = null;
    var spiral = null;
    var rafId = 0;
    var running = false;
    var inView = false;
    var scrollProgress = 0;

    function setPosterReady() {
        if (!poster) return;
        poster.style.opacity = '0';
    }

    function size() {
        var w = root.clientWidth || 360;
        var h = root.clientHeight || w;
        return { w: w, h: h };
    }

    function applySize() {
        if (!renderer || !camera) return;
        var s = size();
        renderer.setSize(s.w, s.h, false);
        camera.aspect = s.w / s.h;
        camera.updateProjectionMatrix();
        if (!running) renderer.render(scene, camera);
    }

    function start() {
        if (running) return;
        running = true;
        loop();
    }

    function stop() {
        running = false;
        if (rafId) cancelAnimationFrame(rafId);
        rafId = 0;
    }

    function loop() {
        if (!running) return;
        rafId = requestAnimationFrame(loop);

        if (spiral) {
            var t = performance.now() * 0.001;
            var base = t * 0.35;
            var scrollY = (scrollProgress - 0.35) * 0.65;
            spiral.rotation.y = (Math.PI * 0.5) + base + scrollY;
            spiral.rotation.x = 0.18 + Math.sin(t * 0.22) * 0.06;
        }

        renderer.render(scene, camera);
    }

    function buildSpiral() {
        var turns = 7.2;
        var steps = 260;
        var radius = 0.62;
        var height = 1.18;
        var points = [];
        for (var i = 0; i <= steps; i++) {
            var p = i / steps;
            var a = p * turns * Math.PI * 2;
            var y = (p - 0.5) * height;
            var x = Math.cos(a) * radius;
            var z = Math.sin(a) * radius;
            points.push(new THREE.Vector3(x, y, z));
        }
        var curve = new THREE.CatmullRomCurve3(points, false, 'catmullrom', 0.5);
        var geom = new THREE.TubeGeometry(curve, 520, 0.035, 16, false);
        var mat = new THREE.MeshStandardMaterial({
            color: 0xC9A84C,
            metalness: 0.85,
            roughness: 0.28
        });
        var mesh = new THREE.Mesh(geom, mat);
        mesh.rotation.x = 0.18;
        mesh.rotation.y = Math.PI * 0.5;
        return mesh;
    }

    function init() {
        if (renderer) return;
        scene = new THREE.Scene();

        var s = size();
        camera = new THREE.PerspectiveCamera(34, s.w / s.h, 0.1, 100);
        camera.position.set(2.45, 0.15, 0.15);
        camera.lookAt(0, 0, 0);

        renderer = new THREE.WebGLRenderer({
            alpha: true,
            antialias: false,
            powerPreference: 'high-performance',
            preserveDrawingBuffer: false
        });

        var dpr = Math.min(window.devicePixelRatio || 1, 1.5);
        renderer.setPixelRatio(dpr);
        renderer.setSize(s.w, s.h, false);
        renderer.outputColorSpace = THREE.SRGBColorSpace;

        renderer.domElement.className = 'hero-spiral3d-canvas';
        renderer.domElement.setAttribute('aria-hidden', 'true');
        card.appendChild(renderer.domElement);

        var hemi = new THREE.HemisphereLight(0xffffff, 0x0a0a0a, 1.15);
        scene.add(hemi);
        var dir = new THREE.DirectionalLight(0xffffff, 0.95);
        dir.position.set(2.2, 3.1, 3.6);
        scene.add(dir);
        var rim = new THREE.DirectionalLight(0xDFC06A, 0.55);
        rim.position.set(-2.6, 0.6, -2.2);
        scene.add(rim);

        spiral = buildSpiral();
        scene.add(spiral);

        setPosterReady();
        root.classList.add('hero-spiral3d-ready');

        var ro = new ResizeObserver(function () { applySize(); });
        ro.observe(root);

        window.addEventListener('scroll', function () {
            if (!inView) return;
            var hero = document.getElementById('hero');
            if (!hero) return;
            var rect = hero.getBoundingClientRect();
            scrollProgress = clamp01((0 - rect.top) / (rect.height * 0.95));
        }, { passive: true });

        document.addEventListener('visibilitychange', function () {
            if (document.hidden) stop();
            else if (inView) start();
        });

        renderer.render(scene, camera);
        if (inView) start();
    }

    var inited = false;
    function scheduleInit() {
        if (inited) return;
        inited = true;
        var idle = window.requestIdleCallback;
        if (idle) idle(function () { init(); }, { timeout: 800 });
        else setTimeout(function () { init(); }, 260);
    }

    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            var entry = entries && entries[0];
            if (!entry) return;
            inView = !!entry.isIntersecting;
            if (inView) {
                if (!inited) scheduleInit();
                else start();
            } else {
                stop();
            }
        }, { rootMargin: '220px 0px' });
        io.observe(root);
    } else {
        inView = true;
        scheduleInit();
    }
}

