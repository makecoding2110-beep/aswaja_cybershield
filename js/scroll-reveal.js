/**
 * Aswaja CyberShield — Public Scroll Reveal & Micro-Interactions Engine
 * Diterapkan penuh di SELURUH halaman publik:
 * - Beranda (index.blade.php)
 * - AI Tabayyun (tabayyun.blade.php)
 * - Etika & Fiqih Siber (etika.blade.php)
 * - Cyber Guard Test (cyber-guard.blade.php)
 * - Portal Lapor Siber (lapor.blade.php)
 * - 10 Halaman Artikel Kajian (artikel/*.blade.php)
 *
 * DILARANG AKTIF PADA AREA ADMIN (/admin/...).
 */
document.addEventListener('DOMContentLoaded', () => {
  // 1. Guard keamanan: Jangan pernah aktif di area admin
  const path = window.location.pathname || '';
  if (path.startsWith('/admin') || path.includes('/admin/')) {
    return;
  }

  // 2. Aksesibilitas: Hormati preferensi reduced-motion
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.querySelectorAll('.reveal-on-scroll, .public-reveal-init').forEach(el => {
      el.classList.add('is-revealed', 'public-reveal-visible');
    });
    return;
  }

  // 3. Dekorasi Ambient Halus Latar Hero & Section Publik (Animasi Dibanyakin)
  function initHeroAmbient() {
    const main = document.querySelector('main') || document.body;
    if (!main) return;

    // Cari area hero / container utama publik
    const heroTargets = main.querySelectorAll('.hero-ambient-target, .relative.pt-32, .relative.py-20, .relative.py-16');
    if (!heroTargets.length) return;

    heroTargets.forEach((heroTarget) => {
      // Jika sudah memiliki partikel ambient atau jika target adalah section-particles, lewati
      if (
        heroTarget.classList.contains('section-particles') ||
        heroTarget.querySelector('.public-hero-ambient') ||
        heroTarget.querySelector('.section-particles')
      ) return;

      const ambientBox = document.createElement('div');
      ambientBox.className = 'public-hero-ambient';
      ambientBox.setAttribute('aria-hidden', 'true');

      // 16 bentuk lembut mengambang pelan dengan variasi dot, ring, dan sparkle
      const shapesCount = 16;
      for (let i = 0; i < shapesCount; i++) {
        const shape = document.createElement('div');
        const mod = i % 3;
        const isRing = (mod === 0);
        const isPulse = (mod === 1);
        shape.className = `public-ambient-shape ${isRing ? 'public-ambient-ring' : 'public-ambient-dot'} ${isPulse ? 'particle-pulse' : ''}`;

        const size = isRing ? (14 + Math.random() * 18) : (6 + Math.random() * 10);
        const left = 3 + (i * 6) + (Math.random() * 4 - 2); // Tersebar merata 3% - 97%
        const top = 8 + Math.random() * 82; // 8% - 90%
        const duration = 5.5 + Math.random() * 6.5; // 5.5s - 12s durasi melayang pelan
        const driftX = (Math.random() - 0.5) * 36;
        const driftY = (Math.random() - 0.5) * 30;

        shape.style.cssText = `
          left: ${left}%;
          top: ${top}%;
          width: ${size}px;
          height: ${size}px;
          --anim-duration: ${duration}s;
          --drift-x: ${driftX}px;
          --drift-y: ${driftY}px;
        `;
        ambientBox.appendChild(shape);
      }

      heroTarget.style.position = heroTarget.style.position || 'relative';
      heroTarget.prepend(ambientBox);
    });
  }

  // 4. Observer Masuk Viewport (Fade-in + Slide-up + Stagger di Seluruh Halaman)
  function initViewportReveal() {
    const selector = [
      '.reveal-on-scroll',
      'main h1',
      'main h2',
      'main h3',
      '.ui-card',
      '.card-hover-effect',
      '#form-lapor',
      '#rekap-laporan',
      '#quiz-container',
      '#analisis-tautan',
      'article',
      '.rounded-2xl.border',
      '.accordion-item',
      'blockquote'
    ].join(', ');

    const targets = Array.from(document.querySelectorAll(selector))
      .filter((el, idx, arr) => arr.indexOf(el) === idx && !el.closest('header') && !el.closest('footer'));

    if (!targets.length) return;

    // Tandai inisialisasi state awal
    targets.forEach(el => {
      if (!el.classList.contains('is-revealed')) {
        el.classList.add('public-reveal-init');
      }
    });

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const el = entry.target;
          
          // Efek stagger jika elemen berada dalam kontainer koleksi (grid / list)
          const parent = el.parentElement;
          if (parent && (parent.classList.contains('grid') || parent.classList.contains('stagger-children') || parent.classList.contains('space-y-6') || parent.classList.contains('space-y-4'))) {
            const index = Array.from(parent.children).indexOf(el);
            const delayMs = Math.min(index * 70, 350);
            el.style.transitionDelay = `${delayMs}ms`;
          }

          el.classList.add('is-revealed', 'public-reveal-visible');
          observer.unobserve(el);
        }
      });
    }, {
      threshold: 0.06,
      rootMargin: '0px 0px -25px 0px'
    });

    targets.forEach(el => observer.observe(el));
  }

  // 5. Animasi Angka Statistik Menghitung Naik (Counter Animation)
  function initCounters() {
    const counterEls = document.querySelectorAll('[data-counter], .stat-counter');
    if (!counterEls.length) return;

    const counterObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const el = entry.target;
        counterObserver.unobserve(el);
        if (el.dataset.counterActive === '1') return;
        el.dataset.counterActive = '1';

        const raw = el.getAttribute('data-counter') || el.textContent.replace(/[^0-9.]/g, '');
        const targetVal = parseFloat(raw) || 0;
        const prefix = el.getAttribute('data-counter-prefix') || '';
        const suffix = el.getAttribute('data-counter-suffix') || (el.textContent.includes('%') ? '%' : (el.textContent.includes('+') ? '+' : ''));
        const duration = 1400; // ms
        const isDecimal = raw.includes('.');
        const startTime = performance.now();

        function step(now) {
          const elapsed = now - startTime;
          const progress = Math.min(elapsed / duration, 1);
          const easeOut = 1 - Math.pow(1 - progress, 3);
          const current = isDecimal ? (targetVal * easeOut).toFixed(1) : Math.floor(targetVal * easeOut);
          el.textContent = `${prefix}${current.toLocaleString('id-ID')}${suffix}`;
          if (progress < 1) {
            requestAnimationFrame(step);
          } else {
            el.textContent = `${prefix}${isDecimal ? targetVal.toFixed(1) : targetVal.toLocaleString('id-ID')}${suffix}`;
          }
        }
        requestAnimationFrame(step);
      });
    }, { threshold: 0.25 });

    counterEls.forEach(el => counterObserver.observe(el));
  }

  // Jalankan inisialisasi animasi publik
  try {
    initHeroAmbient();
    initViewportReveal();
    initCounters();
  } catch (err) {
    console.warn('[AswajaPublicAnim] Pemuatan animasi publik dilewati:', err);
  }
});
