/**
 * Landing Hero 3D loader (ESM)
 * - Lazily imports the 3D module only when the hero is near viewport
 * - Respects prefers-reduced-motion and data-saver / low-end signals
 * - Keeps LCP fast by defaulting to the poster image
 */
function webglSupported() {
  try {
    const canvas = document.createElement('canvas');
    return !!(window.WebGLRenderingContext && (canvas.getContext('webgl') || canvas.getContext('experimental-webgl')));
  } catch (_) {
    return false;
  }
}

function shouldSkip3D() {
  const prefersReduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (prefersReduce) return true;
  if (!webglSupported()) return true;

  // Network / device heuristics (soft signals; keep conservative for UX/perf)
  const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
  if (conn && conn.saveData) return true;
  if (conn && typeof conn.effectiveType === 'string' && (conn.effectiveType === '2g' || conn.effectiveType === 'slow-2g')) return true;

  if (typeof navigator.deviceMemory === 'number' && navigator.deviceMemory > 0 && navigator.deviceMemory < 4) return true;
  if (typeof navigator.hardwareConcurrency === 'number' && navigator.hardwareConcurrency > 0 && navigator.hardwareConcurrency < 4) return true;

  return false;
}

function prefetch(url) {
  try {
    const u = String(url || '');
    if (!u) return;
    const link = document.createElement('link');
    link.rel = 'prefetch';
    link.as = 'script';
    link.href = u;
    document.head.appendChild(link);
  } catch (_) {
    // no-op
  }
}

const root = document.getElementById('heroSpiral');
if (!root) {
  // no-op
} else {
  const moduleUrl = root.getAttribute('data-hero3d-module') || '';

  // Explicit hard-disable/enable hooks (optional)
  if (root.getAttribute('data-hero3d') === 'off') {
    root.dataset.mode = 'poster';
  } else if (!moduleUrl) {
    root.dataset.mode = 'poster';
  } else if (shouldSkip3D() && root.getAttribute('data-hero3d') !== 'force') {
    root.dataset.mode = 'poster';
  } else {
    let loaded = false;
    const load3D = () => {
      if (loaded) return;
      loaded = true;
      import(moduleUrl)
        .then((m) => (m && typeof m.mountHeroSpiral3D === 'function' ? m.mountHeroSpiral3D(root) : null))
        .catch(() => {
          root.dataset.mode = 'poster';
        });
    };

    // Warm cache with very low priority once the page is settled
    const idle = window.requestIdleCallback;
    if (idle) idle(() => prefetch(moduleUrl), { timeout: 1200 });
    else setTimeout(() => prefetch(moduleUrl), 900);

    if ('IntersectionObserver' in window) {
      const io = new IntersectionObserver(
        (entries) => {
          const entry = entries && entries[0];
          if (!entry) return;
          if (entry.isIntersecting) {
            io.disconnect();
            const idle2 = window.requestIdleCallback;
            if (idle2) idle2(load3D, { timeout: 800 });
            else setTimeout(load3D, 250);
          }
        },
        { rootMargin: '260px 0px' }
      );
      io.observe(root);
    } else {
      // Fallback: delay to let LCP settle
      setTimeout(load3D, 1200);
    }
  }
}

