(function () {
  'use strict';

  var AUTH_TOKEN_KEY = 'aswaja_admin_token';
  var AUTH_USER_KEY = 'aswaja_admin_user';
  var NAV_FLAG = 'aswaja_admin_navigating';

  var initialPathname = window.location.pathname || '/admin';
  var isVercel = window.location.hostname.indexOf('vercel.app') !== -1;

  function getBasePrefix(pathname) {
    var feIdx = pathname.indexOf('/frontend/');
    if (feIdx >= 0) return pathname.slice(0, feIdx);
    var adminIdx = pathname.indexOf('/admin');
    if (adminIdx >= 0) return pathname.slice(0, adminIdx);
    var loginIdx = pathname.indexOf('/login');
    if (loginIdx >= 0) return pathname.slice(0, loginIdx);
    return '';
  }

  var basePrefix = getBasePrefix(initialPathname);

  function toCleanAdminRoute(rawHref) {
    if (!rawHref) return (basePrefix || '') + '/admin';
    var clean = rawHref.split('?')[0].split('#')[0];
    var suffix = rawHref.slice(clean.length);

    var parts = clean.replace(/\/+$/, '').split('/').filter(Boolean);
    var last = parts.length ? parts[parts.length - 1] : 'index';
    last = last.replace(/\.blade\.php$/i, '').replace(/\.php$/i, '');

    if (last === 'login') {
      return (basePrefix || '') + '/login' + suffix;
    }
    if (last === 'index' || last === 'admin') {
      return (basePrefix || '') + '/admin' + suffix;
    }
    return (basePrefix || '') + '/admin/' + last + suffix;
  }

  function toNavigableAdminRoute(rawHref) {
    return toCleanAdminRoute(rawHref);
  }

  function redirectToLogin() {
    try {
      sessionStorage.removeItem(AUTH_TOKEN_KEY);
      sessionStorage.removeItem(AUTH_USER_KEY);
      sessionStorage.removeItem(NAV_FLAG);
      localStorage.removeItem(AUTH_TOKEN_KEY);
      localStorage.removeItem(AUTH_USER_KEY);
    } catch (e) {}
    window.location.replace(toNavigableAdminRoute('login'));
  }

  // Expose helper global untuk logout dari halaman admin mana pun
  window.adminLogoutRedirect = redirectToLogin;

  // 1. Verifikasi token aktif pada sesi saat halaman mulai dimuat
  var activeToken = sessionStorage.getItem(AUTH_TOKEN_KEY);
  if (!activeToken) {
    redirectToLogin();
    return;
  }

  // 2. Bersihkan URL di address bar browser secara instan (hilangkan /frontend dan .blade.php)
  try {
    var cleanCurrent = toCleanAdminRoute(initialPathname);
    if (cleanCurrent && cleanCurrent !== initialPathname) {
      window.history.replaceState(null, '', cleanCurrent + window.location.search + window.location.hash);
    }
  } catch (e) {}

  // 3. Pastikan seluruh link sidebar admin valid di lokal maupun di Vercel
  document.addEventListener('DOMContentLoaded', function () {
    if (window.AuthService) {
      window.AuthService.logout = redirectToLogin;
    }
    document.querySelectorAll('a[href]').forEach(function (a) {
      var raw = a.getAttribute('href');
      if (!raw || raw.startsWith('#') || raw.startsWith('javascript:') || raw.startsWith('http://') || raw.startsWith('https://')) {
        return;
      }
      if (raw.indexOf('.blade.php') !== -1 || raw.indexOf('.php') !== -1) {
        a.setAttribute('href', toNavigableAdminRoute(raw));
      }
    });
  });

  // 4. Proteksi BFCache (Back/Forward Cache peramban):
  window.addEventListener('pageshow', function () {
    var checkToken = sessionStorage.getItem(AUTH_TOKEN_KEY);
    if (!checkToken) {
      redirectToLogin();
    }
  });

  // 5. Tangkap klik pada tautan internal modul admin
  document.addEventListener('click', function (e) {
    var link = e.target.closest('a');
    if (!link) return;

    var href = link.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;

    // Jika tautan menuju logout, login, atau keluar ke domain lain, biarkan sesi hangus
    if (href.indexOf('login') !== -1 || href.indexOf('logout') !== -1 || href.startsWith('http')) {
      sessionStorage.removeItem(NAV_FLAG);
      return;
    }

    // Tandai bahwa ini adalah perpindahan menu internal admin yang sah
    sessionStorage.setItem(NAV_FLAG, '1');

    // Tampilkan page loader perisai saat berpindah menu admin
    var loader = document.getElementById('page-loader');
    if (loader) {
      var bar = loader.querySelector('.loader-bar-fill');
      var text = loader.querySelector('.loader-text');
      if (text) text.textContent = 'Memuat Panel Admin...';
      if (bar) {
        bar.style.transition = 'none';
        bar.style.width = '0%';
        requestAnimationFrame(function () {
          bar.style.transition = 'width 0.35s cubic-bezier(0.4, 0, 0.2, 1)';
          bar.style.width = '55%';
          setTimeout(function () { if (bar) bar.style.width = '85%'; }, 180);
        });
      }
      loader.classList.remove('loader-hide');
    }
  }, true);

  // Sembunyikan page-loader admin saat selesai dimuat
  window.addEventListener('load', function () {
    var loader = document.getElementById('page-loader');
    if (loader) {
      var bar = loader.querySelector('.loader-bar-fill');
      if (bar) bar.style.width = '100%';
      setTimeout(function () {
        loader.classList.add('loader-hide');
      }, 220);
    }
  });

  // 6. Helper navigasi internal terprogram (misal pencarian global atau tombol aksi)
  window.adminNavigate = function (url) {
    sessionStorage.setItem(NAV_FLAG, '1');
    window.location.href = toNavigableAdminRoute(url);
  };

  // 7. Saat halaman ditinggalkan (pagehide / unload / reload):
  window.addEventListener('pagehide', function () {
    var isInternal = sessionStorage.getItem(NAV_FLAG) === '1';
    if (!isInternal) {
      try {
        sessionStorage.removeItem(AUTH_TOKEN_KEY);
        sessionStorage.removeItem(AUTH_USER_KEY);
        sessionStorage.removeItem(NAV_FLAG);
        localStorage.removeItem(AUTH_TOKEN_KEY);
        localStorage.removeItem(AUTH_USER_KEY);
      } catch (e) {}
    } else {
      sessionStorage.removeItem(NAV_FLAG);
    }
  });
})();
