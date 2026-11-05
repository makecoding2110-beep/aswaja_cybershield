/**
 * Aswaja CyberShield - Global Animation Engine
 * Premium UI/UX micro-interactions, scroll animations, and ambient effects.
 */

/* 1. PAGE LOADING SCREEN & PAGE TRANSITIONS */
(function initLoadingScreen() {
  function resolveLogoSrc() {
    var headerImg = document.querySelector('header img[src*="logo-shield"], .header img, nav img, img[alt*="Aswaja"]');
    if (headerImg && headerImg.src) {
      return headerImg.src;
    }
    var origin = window.location.origin || (window.location.protocol + '//' + window.location.host);
    return origin + '/logo-shield.png';
  }

  function ensureLoaderLogo(l) {
    if (!l) return;
    var img = l.querySelector('.loader-logo-img');
    if (img) {
      var bestSrc = resolveLogoSrc();
      if (!img.src || img.naturalWidth === 0 || img.getAttribute('src') === '../logo-shield.png' || img.getAttribute('src') === '../../logo-shield.png') {
        img.src = bestSrc;
      }
      img.onerror = function() {
        if (this.src.indexOf('/frontend/') === -1) {
          this.src = '/frontend/logo-shield.png';
        } else {
          this.src = '/logo-shield.png';
        }
      };
    }
  }

  function getLoader() {
    var l = document.getElementById('page-loader');
    if (!l) {
      l = document.createElement('div');
      l.id = 'page-loader';
      l.setAttribute('role', 'status');
      l.setAttribute('aria-label', 'Memuat halaman');
      var logoSrc = resolveLogoSrc();
      l.innerHTML = '<div class="loader-content"><div class="loader-logo-wrap"><img src="' + logoSrc + '" alt="Aswaja CyberShield" class="loader-logo-img"></div><div class="loader-bar-track"><div class="loader-bar-fill"></div></div><span class="loader-text">Memuat...</span></div>';
      document.body.prepend(l);
    }
    ensureLoaderLogo(l);
    return l;
  }

  var loader = getLoader();
  var bar = loader ? loader.querySelector('.loader-bar-fill') : null;
  var text = loader ? loader.querySelector('.loader-text') : null;
  ensureLoaderLogo(loader);

  function hideLoader() {
    if (!loader) return;
    if (bar) bar.style.width = '100%';
    setTimeout(function() {
      loader.classList.add('loader-hide');
    }, 220);
  }

  function showLoader(targetText) {
    if (!loader) loader = getLoader();
    if (!loader) return;
    ensureLoaderLogo(loader);
    bar = loader.querySelector('.loader-bar-fill');
    text = loader.querySelector('.loader-text');
    if (text) text.textContent = targetText || 'Memuat Halaman...';
    if (bar) {
      bar.style.transition = 'none';
      bar.style.width = '0%';
      requestAnimationFrame(function() {
        bar.style.transition = 'width 0.35s cubic-bezier(0.4, 0, 0.2, 1)';
        bar.style.width = '45%';
        setTimeout(function() { if (bar) bar.style.width = '80%'; }, 180);
      });
    }
    loader.classList.remove('loader-hide');
  }

  // 1. Initial Page Load
  if (bar) {
    var w = 15;
    bar.style.width = w + '%';
    var iv = setInterval(function() {
      w = Math.min(w + Math.random() * 20, 92);
      bar.style.width = w + '%';
    }, 90);

    window.addEventListener('load', function() {
      clearInterval(iv);
      hideLoader();
    });
    setTimeout(function() {
      clearInterval(iv);
      hideLoader();
    }, 700);
  } else {
    window.addEventListener('load', hideLoader);
    setTimeout(hideLoader, 500);
  }

  // 2. Hide on BFCache restore (pageshow)
  window.addEventListener('pageshow', function() {
    hideLoader();
  });

  // 3. Intercept link clicks to show loader on page navigation
  document.addEventListener('click', function(e) {
    var link = e.target.closest('a');
    if (!link) return;
    var href = link.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:') || link.getAttribute('target') === '_blank' || link.hasAttribute('download')) {
      return;
    }
    try {
      var url = new URL(link.href, window.location.href);
      if (url.origin !== window.location.origin) return;
      if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash) return;
    } catch (err) {}

    showLoader('Memuat Halaman...');
    setTimeout(function() {
      if (loader && !loader.classList.contains('loader-hide')) {
        loader.classList.add('loader-hide');
      }
    }, 6000);
  }, true);

  window.showAswajaPageLoader = showLoader;
  window.hideAswajaPageLoader = hideLoader;
})();

/* 2. ENHANCED SCROLL REVEAL */
(function initScrollReveal() {
  var selector = '.reveal, .reveal-up, .reveal-down, .reveal-left, .reveal-right, .reveal-scale, .stagger-children';
  var elements = document.querySelectorAll(selector);
  if (!elements.length) return;
  var io = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (!entry.isIntersecting) return;
      var el = entry.target;
      if (el.classList.contains('stagger-children')) {
        var children = el.children;
        Array.from(children).forEach(function(child, i) {
          child.style.setProperty('--stagger-delay', (i * 80) + 'ms');
          child.classList.add('stagger-child-reveal');
        });
        el.classList.add('stagger-done');
      } else {
        el.classList.add('revealed');
      }
      io.unobserve(el);
    });
  }, { threshold: 0.08, rootMargin: '0px 0px -50px 0px' });
  elements.forEach(function(el) { io.observe(el); });
})();

/* 3. ANIMATED COUNTERS */
(function initCounters() {
  var counters = document.querySelectorAll('[data-counter]');
  if (!counters.length) return;
  function easeOut(t) { return 1 - Math.pow(1 - t, 4); }
  function animateCounter(el) {
    var raw = el.getAttribute('data-counter');
    var suffix = el.getAttribute('data-counter-suffix') || '';
    var prefix = el.getAttribute('data-counter-prefix') || '';
    var duration = parseInt(el.getAttribute('data-counter-duration') || '1800');
    var target = parseFloat(raw);
    var decimals = (raw.split('.')[1] || '').length;
    var startTime = performance.now();
    function update(now) {
      var elapsed = now - startTime;
      var progress = Math.min(elapsed / duration, 1);
      var value = (target * easeOut(progress)).toFixed(decimals);
      el.textContent = prefix + value + suffix;
      if (progress < 1) requestAnimationFrame(update);
    }
    requestAnimationFrame(update);
  }
  var io = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) {
        animateCounter(entry.target);
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });
  counters.forEach(function(el) { io.observe(el); });
})();

/* 4. CURSOR GLOW */
(function initCursorGlow() {
  if (window.matchMedia('(hover: none)').matches) return;
  var glow = document.createElement('div');
  glow.id = 'cursor-glow';
  document.body.appendChild(glow);
  var mx = -200, my = -200, cx = -200, cy = -200;
  document.addEventListener('mousemove', function(e) { mx = e.clientX; my = e.clientY; });
  (function loop() {
    cx += (mx - cx) * 0.12;
    cy += (my - cy) * 0.12;
    glow.style.transform = 'translate(' + (cx - 200) + 'px, ' + (cy - 200) + 'px)';
    requestAnimationFrame(loop);
  })();
  var interactive = 'a, button, [role="button"], input, textarea, select, .card-tilt';
  document.addEventListener('mouseover', function(e) {
    if (e.target.closest(interactive)) glow.classList.add('cursor-glow-active');
  });
  document.addEventListener('mouseout', function(e) {
    if (e.target.closest(interactive)) glow.classList.remove('cursor-glow-active');
  });
  document.addEventListener('mouseleave', function() { glow.style.opacity = '0'; });
  document.addEventListener('mouseenter', function() { glow.style.opacity = ''; });
})();

/* 5. NAVBAR SCROLL PROGRESS BAR */
(function initNavbar() {
  var header = document.querySelector('header');
  if (!header) return;
  var progressBar = document.createElement('div');
  progressBar.id = 'scroll-progress';
  document.body.prepend(progressBar);
  window.addEventListener('scroll', function() {
    var scrollTop = window.scrollY;
    var docHeight = document.documentElement.scrollHeight - window.innerHeight;
    var pct = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
    progressBar.style.width = pct + '%';
    if (scrollTop > 60) header.classList.add('header-scrolled');
    else header.classList.remove('header-scrolled');
  }, { passive: true });
})();

/* 6. SECTION AMBIENT PARTICLES (Disetiap Blok Beranda) */
(function initSectionParticles() {
  var containers = document.querySelectorAll('.section-particles');
  if (!containers.length) return;
  containers.forEach(function(container) {
    var count = 10;
    for (var i = 0; i < count; i++) {
      var p = document.createElement('div');
      p.className = 'ambient-particle';
      var isRing = (i % 3 === 0);
      var size = isRing ? (5 + Math.random() * 4) : (3 + Math.random() * 5);
      var x = Math.random() * 92 + 4; // 4% to 96%
      var y = Math.random() * 84 + 8; // 8% to 92% across block height
      var delay = Math.random() * 6;
      var duration = 5 + Math.random() * 6;
      var opacity = 0.2 + Math.random() * 0.4;
      var dx = (Math.random() - 0.5) * 35;
      var dy = (Math.random() - 0.5) * 30;
      p.style.cssText = 'left:' + x + '%;top:' + y + '%;width:' + size + 'px;height:' + size + 'px;opacity:' + opacity + ';animation:particleHoverDrift ' + duration + 's ' + delay + 's infinite ease-in-out;';
      p.style.setProperty('--dx', dx + 'px');
      p.style.setProperty('--dy', dy + 'px');
      if (isRing) {
        p.classList.add('particle-ring');
      } else {
        p.classList.add('particle-dot');
      }
      container.appendChild(p);
    }
  });
})();

/* 7. CARD MAGNETIC TILT */
(function initMagneticTilt() {
  var cards = document.querySelectorAll('.card-tilt');
  cards.forEach(function(card) {
    card.addEventListener('mousemove', function(e) {
      var rect = card.getBoundingClientRect();
      var dx = (e.clientX - rect.left - rect.width / 2) / (rect.width / 2);
      var dy = (e.clientY - rect.top - rect.height / 2) / (rect.height / 2);
      card.style.transform = 'perspective(800px) rotateX(' + (dy * -7) + 'deg) rotateY(' + (dx * 7) + 'deg) scale(1.025)';
    });
    card.addEventListener('mouseleave', function() { card.style.transform = ''; });
  });
})();

/* 8. TYPEWRITER TEXT */
(function initTypewriter() {
  var els = document.querySelectorAll('[data-typewriter]');
  els.forEach(function(el) {
    var words = (el.getAttribute('data-typewriter') || '').split('|');
    if (!words.length) return;
    var wi = 0, ci = 0, deleting = false;
    el.classList.add('typewriter-cursor');
    function tick() {
      var word = words[wi % words.length];
      if (!deleting) {
        el.textContent = word.slice(0, ci + 1);
        ci++;
        if (ci === word.length) { deleting = true; setTimeout(tick, 1800); return; }
      } else {
        el.textContent = word.slice(0, ci - 1);
        ci--;
        if (ci === 0) { deleting = false; wi++; }
      }
      setTimeout(tick, deleting ? 55 : 95);
    }
    tick();
  });
})();

/* 9. RIPPLE BUTTONS */
(function initRipple() {
  document.addEventListener('click', function(e) {
    var btn = e.target.closest('.ripple-btn');
    if (!btn) return;
    var rect = btn.getBoundingClientRect();
    var x = e.clientX - rect.left;
    var y = e.clientY - rect.top;
    var size = Math.max(rect.width, rect.height) * 2;
    var ripple = document.createElement('span');
    ripple.className = 'ripple-wave';
    ripple.style.cssText = 'width:' + size + 'px;height:' + size + 'px;left:' + (x - size/2) + 'px;top:' + (y - size/2) + 'px;';
    btn.appendChild(ripple);
    setTimeout(function() { if (ripple.parentNode) ripple.parentNode.removeChild(ripple); }, 700);
  });
})();

/* 10. TOAST SYSTEM */
window.AgyToast = (function() {
  var container = document.getElementById('toast-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toast-container';
    document.body.appendChild(container);
  }
  return function(message, type, duration) {
    type = type || 'info';
    duration = duration || 3500;
    var icons = { info: 'info', success: 'check_circle', warning: 'warning', error: 'error' };
    var t = document.createElement('div');
    t.className = 'agy-toast agy-toast-' + type;
    t.innerHTML = '<span class="material-symbols-outlined toast-icon">' + (icons[type]||'info') + '</span><span>' + message + '</span>';
    container.appendChild(t);
    requestAnimationFrame(function() { t.classList.add('toast-show'); });
    setTimeout(function() {
      t.classList.remove('toast-show');
      setTimeout(function() { if (t.parentNode) t.parentNode.removeChild(t); }, 400);
    }, duration);
  };
})();

/* 11. PARALLAX */
(function initParallax() {
  var els = document.querySelectorAll('[data-parallax]');
  if (!els.length) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  function update() {
    var sy = window.scrollY;
    els.forEach(function(el) {
      var speed = parseFloat(el.getAttribute('data-parallax') || '0.15');
      el.style.transform = 'translateY(' + (sy * speed) + 'px)';
    });
  }
  window.addEventListener('scroll', update, { passive: true });
  update();
})();

/* 12. UNIVERSAL CLICKABLE CONTENT CARDS (KLIK DI BAGIAN MANA SAJA) */
(function initClickableCards() {
  document.addEventListener('click', function(e) {
    // Abaikan jika klik terjadi pada input, tombol, textarea, atau label
    if (e.target.closest('button, input, textarea, select, label')) return;

    // Cari kartu konten terdekat yang memiliki efek hover, modul, atau atribut data-href
    var card = e.target.closest('.card-hover-effect, .clickable-card, .module-item, [data-href]');
    if (!card) return;

    // Jika pengguna mengklik tautan <a> langsung, biarkan navigasi alami browser
    if (e.target.closest('a')) return;

    // Cari URL tujuan dari data-href atau dari tag <a> di dalam kartu
    var href = card.getAttribute('data-href');
    var targetLink = card.querySelector('a[href]');
    var destination = href || (targetLink ? targetLink.getAttribute('href') : null);

    if (!destination || destination === '#' || destination.startsWith('javascript:')) return;

    var isBlank = (targetLink && targetLink.getAttribute('target') === '_blank') || card.getAttribute('data-target') === '_blank';
    if (isBlank) {
      window.open(destination, '_blank');
    } else {
      window.location.href = destination;
    }
  });

  // Aksesibilitas Keyboard: Tekan Enter pada kartu yang sedang fokus
  document.addEventListener('keydown', function(e) {
    if (e.key !== 'Enter') return;
    var card = document.activeElement;
    if (!card || !card.matches('.card-hover-effect, .clickable-card, .module-item, [data-href]')) return;
    if (['INPUT', 'TEXTAREA', 'BUTTON', 'SELECT'].includes(card.tagName)) return;

    var href = card.getAttribute('data-href');
    var targetLink = card.querySelector('a[href]');
    var destination = href || (targetLink ? targetLink.getAttribute('href') : null);

    if (!destination || destination === '#' || destination.startsWith('javascript:')) return;

    e.preventDefault();
    window.location.href = destination;
  });
})();

