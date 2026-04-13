/**
 * Hero 3D (GLB) - ESM
 * Importado sob demanda pelo loader para não pesar no carregamento inicial.
 *
 * Export:
 *   mountHeroSpiral3D(rootEl?: HTMLElement): Promise<void>
 */

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

    // Lazy-load heavy deps only when we actually mount
    var THREE;
    var GLTFLoader;
    try {
        var threeUrl = new URL('../vendor/three/three.module.js', import.meta.url).href;
        var gltfUrl = new URL('../vendor/three/GLTFLoader.js', import.meta.url).href;
        THREE = await import(threeUrl);
        GLTFLoader = (await import(gltfUrl)).GLTFLoader;
    } catch (e) {
        root.dataset.mode = 'poster';
        return;
    }

    var card = root.querySelector('.hero-spiral3d-card') || root;
    var poster = root.querySelector('.hero-spiral3d-poster');
    var modelGlb = root.getAttribute('data-glb') || ''; // mantém ?v=... (cache-busting)

    var renderer = null;
    var scene = null;
    var camera = null;
    var model = null;
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

        if (model) {
            var t = performance.now() * 0.001;
            var baseY = Math.sin(t * 0.35) * 0.18;
            var baseX = Math.cos(t * 0.22) * 0.08;
            var scrollY = (scrollProgress - 0.35) * 0.55;
            model.rotation.y = baseY + scrollY;
            model.rotation.x = baseX;
        }

        renderer.render(scene, camera);
    }

    function init() {
        if (renderer) return;
        if (!modelGlb) {
            root.dataset.mode = 'poster';
            return;
        }

        scene = new THREE.Scene();

        var s = size();
        camera = new THREE.PerspectiveCamera(34, s.w / s.h, 0.1, 100);
        camera.position.set(0, 0.1, 2.6);

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

        var hemi = new THREE.HemisphereLight(0xffffff, 0x0a0a0a, 1.05);
        scene.add(hemi);
        var dir = new THREE.DirectionalLight(0xffffff, 0.85);
        dir.position.set(2.2, 3.4, 4.2);
        scene.add(dir);

        var manager = new THREE.LoadingManager();
        manager.onLoad = function () {
            setPosterReady();
            root.classList.add('hero-spiral3d-ready');
            if (inView) start();
        };

        var loader = new GLTFLoader(manager);
        loader.load(modelGlb, function (gltf) {
            var obj = gltf.scene || (gltf.scenes && gltf.scenes[0]);
            if (!obj) {
                root.dataset.mode = 'poster';
                return;
            }

            var box = new THREE.Box3().setFromObject(obj);
            var center = box.getCenter(new THREE.Vector3());
            obj.position.sub(center);

            var sizeVec = box.getSize(new THREE.Vector3());
            var maxDim = Math.max(sizeVec.x, sizeVec.y, sizeVec.z) || 1;
            var scale = 1.65 / maxDim;
            obj.scale.setScalar(scale);

            obj.rotation.x = 0.12;
            obj.rotation.y = -0.25;
            obj.position.y = -0.05;

            model = obj;
            scene.add(obj);
            renderer.render(scene, camera);
        }, undefined, function () {
            root.dataset.mode = 'poster';
        });

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
    }

    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            var entry = entries[0];
            if (!entry) return;
            inView = !!entry.isIntersecting;
            if (inView) {
                io.disconnect();
                var idle = window.requestIdleCallback;
                if (idle) idle(function () { init(); }, { timeout: 800 });
                else setTimeout(function () { init(); }, 260);
            }
        }, { rootMargin: '220px 0px' });
        io.observe(root);
    } else {
        inView = true;
        setTimeout(function () { init(); }, 800);
    }
}
