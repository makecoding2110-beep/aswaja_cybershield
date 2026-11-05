(function initCleanUrls() {
  const initialHref = window.location.href;
  const initialPathname = window.location.pathname || '/';

  function getBasePrefix(pathname) {
    const feIdx = pathname.indexOf('/frontend/');
    if (feIdx >= 0) return pathname.slice(0, feIdx);
    const rootRoutes = ['/', '/index', '/tabayyun', '/etika', '/cyber-guard', '/lapor', '/artikel', '/admin', '/login'];
    for (let i = 0; i < rootRoutes.length; i++) {
      const r = rootRoutes[i];
      if (pathname === r || (r !== '/' && pathname.indexOf(r + '/') === 0) || pathname.indexOf(r + '.blade.php') === 0 || pathname.indexOf(r + '.php') === 0) {
        return '';
      }
    }
    const parts = pathname.split('/').filter(Boolean);
    if (parts[0] === 'frontend') return '';
    return parts.length > 0 ? '/' + parts[0] : '';
  }

  const basePrefix = getBasePrefix(initialPathname);
  const isArticlePage = initialPathname.indexOf('/artikel/') !== -1;

  function toCleanRoute(pathname) {
    let rel = basePrefix && pathname.indexOf(basePrefix) === 0
      ? pathname.slice(basePrefix.length)
      : pathname;
    if (!rel.startsWith('/')) rel = '/' + rel;

    // Hilangkan semua prefix /frontend berulang
    rel = rel.replace(/^(\/frontend)+(\/|$)/, '/');
    rel = rel.replace(/\.blade\.php$/i, '').replace(/\.php$/i, '');

    if (rel === '/index' || rel === '') return (basePrefix || '') + '/';
    if (rel === '/admin/index') return (basePrefix || '') + '/admin';
    if (rel === '/admin/login') return (basePrefix || '') + '/login';
    return (basePrefix || '') + rel;
  }

  // 1. Ubah seluruh atribut href pada <a href="..."> menjadi URL bersih tanpa .php / /frontend
  function rewriteAnchor(a) {
    const rawHref = a.getAttribute('href');
    if (!rawHref || rawHref.startsWith('#') || rawHref.startsWith('javascript:') || rawHref.startsWith('mailto:') || rawHref.startsWith('tel:') || rawHref.startsWith('http://') || rawHref.startsWith('https://')) {
      return;
    }
    try {
      const virtualDir = isArticlePage ? (basePrefix + '/frontend/artikel/') : (basePrefix + '/frontend/');
      const resolved = new URL(rawHref, window.location.origin + virtualDir);
      if (resolved.origin !== window.location.origin) return;
      const cleanPath = toCleanRoute(resolved.pathname);
      const finalHref = cleanPath + resolved.search + resolved.hash;
      if (finalHref && rawHref !== finalHref) {
        a.setAttribute('href', finalHref);
      }
    } catch (e) {}
  }

  function rewriteAllAnchors(root) {
    if (!root || !root.querySelectorAll) return;
    root.querySelectorAll('a[href]').forEach(rewriteAnchor);
  }

  // 2. Sembunyikan /frontend dan .blade.php dari address bar browser secara instan
  try {
    const cleanCurrent = toCleanRoute(initialPathname);
    if (cleanCurrent && cleanCurrent !== initialPathname) {
      window.history.replaceState(null, '', cleanCurrent + window.location.search + window.location.hash);
    }
  } catch (e) {}

  document.addEventListener('DOMContentLoaded', () => {
    rewriteAllAnchors(document);
    if (window.MutationObserver && document.body) {
      const observer = new MutationObserver((mutations) => {
        mutations.forEach((m) => {
          if (m.addedNodes && m.addedNodes.length) {
            m.addedNodes.forEach((node) => {
              if (node.nodeType === 1) {
                if (node.tagName === 'A') rewriteAnchor(node);
                rewriteAllAnchors(node);
              }
            });
          }
        });
      });
      observer.observe(document.body, {
        childList: true,
        subtree: true
      });
    }
  });
})();

document.addEventListener('DOMContentLoaded', () => {
  // ── Mobile Menu Handler ─────────────────────────────────────────────────────
  const menuBtn = document.getElementById('mobile-menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  const menuIcon = document.getElementById('menu-icon');

  if (menuBtn && mobileMenu) {
    menuBtn.addEventListener('click', () => {
      const isExpanded = menuBtn.getAttribute('aria-expanded') === 'true';
      menuBtn.setAttribute('aria-expanded', String(!isExpanded));
      mobileMenu.classList.toggle('hidden');
      if (menuIcon) {
        menuIcon.textContent = isExpanded ? 'menu' : 'close';
      }
    });

    mobileMenu.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => {
        menuBtn.setAttribute('aria-expanded', 'false');
        mobileMenu.classList.add('hidden');
        if (menuIcon) menuIcon.textContent = 'menu';
      });
    });
  }

  // ── Theme Switcher (Light & Dark Mode) ──────────────────────────────────────
  setupThemeManager();
});

function setupThemeManager() {
  function getPreferredTheme() {
    const saved = localStorage.getItem('aswaja_theme') || (localStorage.getItem('aswaja_darkmode') === '1' ? 'dark' : null);
    if (saved) return saved;
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }

  function applyTheme(theme) {
    const isDark = theme === 'dark';
    if (isDark) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
    localStorage.setItem('aswaja_theme', theme);
    localStorage.setItem('aswaja_darkmode', isDark ? '1' : '0');

    // Update icons
    const icons = document.querySelectorAll('#darkmode-icon, .darkmode-icon');
    icons.forEach(icon => {
      icon.textContent = isDark ? 'light_mode' : 'dark_mode';
    });

    // Update buttons
    const btns = document.querySelectorAll('#darkmode-toggle, .darkmode-toggle-btn');
    btns.forEach(btn => {
      const title = isDark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap';
      btn.setAttribute('title', title);
      btn.setAttribute('aria-label', title);
    });

    // Update text in mobile menu if exists
    const texts = document.querySelectorAll('.darkmode-text');
    texts.forEach(txt => {
      txt.textContent = isDark ? 'Mode Terang' : 'Mode Gelap';
    });
  }

  // Initial UI sync
  applyTheme(getPreferredTheme());

  // Attach click listeners to all theme toggles
  const toggleButtons = document.querySelectorAll('#darkmode-toggle, .darkmode-toggle-btn');
  toggleButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const currentDark = document.documentElement.classList.contains('dark');
      applyTheme(currentDark ? 'light' : 'dark');
    });
  });

  // Listen to OS theme changes if user hasn't explicitly set preference
  if (window.matchMedia) {
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
      if (!localStorage.getItem('aswaja_theme')) {
        applyTheme(e.matches ? 'dark' : 'light');
      }
    });
  }
}
