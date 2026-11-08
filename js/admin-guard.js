/**
 * Aswaja CyberShield - Admin Guard & Session Security
 * Mengamankan modul panel administrator dengan perlindungan:
 * 1. Clean Admin Routing (bebas ekstensi .blade.php / .php)
 * 2. Autentikasi Tab Aktif (SessionStorage)
 * 3. Preservasi Sesi saat Reload (F5 / Refresh halaman TIDAK logout)
 * 4. Session & Inactivity Timeout (5 menit tanpa aktivitas pengguna -> logout otomatis)
 * 5. BFCache Protection & Activity Tracking (Mouse, Keyboard, Touch, Click, Scroll)
 */
(function () {
  'use strict';

  var AUTH_TOKEN_KEY = 'aswaja_admin_token';
  var AUTH_USER_KEY = 'aswaja_admin_user';
  var NAV_FLAG = 'aswaja_admin_navigating';
  var LAST_ACTIVITY_KEY = 'aswaja_admin_last_activity';
  var LOGOUT_REASON_KEY = 'aswaja_logout_reason';
  var INACTIVITY_TIMEOUT_MS = 5 * 60 * 1000; // 5 Menit (300.000 ms)

  var initialPathname = window.location.pathname || '/admin';

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

  function clearAdminSession() {
    try {
      sessionStorage.removeItem(AUTH_TOKEN_KEY);
      sessionStorage.removeItem(AUTH_USER_KEY);
      sessionStorage.removeItem(NAV_FLAG);
      localStorage.removeItem(AUTH_TOKEN_KEY);
      localStorage.removeItem(AUTH_USER_KEY);
      localStorage.removeItem(LAST_ACTIVITY_KEY);
    } catch (e) {}
  }

  function redirectToLogin() {
    clearAdminSession();
    window.location.replace(toNavigableAdminRoute('login'));
  }

  function handleSessionTimeout() {
    clearAdminSession();
    try {
      localStorage.setItem(LOGOUT_REASON_KEY, 'timeout');
    } catch (e) {}
    window.location.replace(toNavigableAdminRoute('login') + '?reason=timeout');
  }

  // Expose helper global untuk logout dari modul admin mana pun
  window.adminLogoutRedirect = redirectToLogin;
  window.adminSessionTimeout = handleSessionTimeout;

  // 1. Verifikasi token aktif pada sesi saat halaman mulai dimuat
  var activeToken = null;
  try {
    var searchParams = new URLSearchParams(window.location.search || '');
    var urlToken = searchParams.get('_auth_token');
    if (urlToken) {
      sessionStorage.setItem(AUTH_TOKEN_KEY, urlToken);
      localStorage.setItem(AUTH_TOKEN_KEY, urlToken);
      localStorage.setItem(LAST_ACTIVITY_KEY, String(Date.now()));
    }
    activeToken = sessionStorage.getItem(AUTH_TOKEN_KEY) || localStorage.getItem(AUTH_TOKEN_KEY) || urlToken;
    if (activeToken && !sessionStorage.getItem(AUTH_TOKEN_KEY)) {
      sessionStorage.setItem(AUTH_TOKEN_KEY, activeToken);
    }
    if (activeToken && !localStorage.getItem(AUTH_TOKEN_KEY)) {
      localStorage.setItem(AUTH_TOKEN_KEY, activeToken);
    }
    var storedUser = localStorage.getItem(AUTH_USER_KEY) || sessionStorage.getItem(AUTH_USER_KEY);
    if (storedUser) {
      if (!sessionStorage.getItem(AUTH_USER_KEY)) sessionStorage.setItem(AUTH_USER_KEY, storedUser);
      if (!localStorage.getItem(AUTH_USER_KEY)) localStorage.setItem(AUTH_USER_KEY, storedUser);
    }
  } catch (e) {}

  if (!activeToken) {
    redirectToLogin();
    return;
  }

  // 2. Cek Inactivity Timeout (5 menit tanpa interaksi pengguna)
  var now = Date.now();
  var lastAct = 0;
  try {
    lastAct = parseInt(localStorage.getItem(LAST_ACTIVITY_KEY) || '0', 10);
  } catch (e) {}
  if (lastAct > 0 && (now - lastAct > INACTIVITY_TIMEOUT_MS)) {
    handleSessionTimeout();
    return;
  }
  // Sesi valid saat dimuat / di-reload -> perbarui waktu aktivitas
  try {
    localStorage.setItem(LAST_ACTIVITY_KEY, String(now));
  } catch (e) {}

  // 3. Bersihkan URL di address bar browser secara instan (hilangkan /frontend, .blade.php, dan _auth_token)
  try {
    var cleanCurrent = toCleanAdminRoute(initialPathname);
    var cleanParams = new URLSearchParams(window.location.search || '');
    var hadUrlToken = cleanParams.has('_auth_token');
    if (hadUrlToken) cleanParams.delete('_auth_token');
    var cleanSearch = cleanParams.toString() ? ('?' + cleanParams.toString()) : '';
    if ((cleanCurrent && cleanCurrent !== initialPathname) || hadUrlToken) {
      window.history.replaceState(null, '', cleanCurrent + cleanSearch + window.location.hash);
    }
  } catch (e) {}

  // 4. Pastikan seluruh link sidebar admin valid di lokal maupun di Vercel
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

  // 5. Proteksi BFCache (Back/Forward Cache peramban):
  window.addEventListener('pageshow', function () {
    var checkToken = null;
    try {
      checkToken = sessionStorage.getItem(AUTH_TOKEN_KEY) || localStorage.getItem(AUTH_TOKEN_KEY);
    } catch (e) {}
    if (!checkToken) {
      redirectToLogin();
      return;
    }
    var currentCheck = Date.now();
    var currentLast = parseInt(localStorage.getItem(LAST_ACTIVITY_KEY) || '0', 10);
    if (currentLast > 0 && (currentCheck - currentLast > INACTIVITY_TIMEOUT_MS)) {
      handleSessionTimeout();
    }
  });

  // 6. Pemantau Aktivitas Pengguna (Inactivity Timeout Monitor):
  // Memantau gerakan kursor mouse, klik, ketukan kibor, sentuhan, dan scroll.
  var lastActivityRecorded = Date.now();
  function recordUserActivity() {
    var currentTime = Date.now();
    // Throttle pencatatan ke localStorage setiap 5 detik agar performa tetap ringan
    if (currentTime - lastActivityRecorded < 5000) return;
    lastActivityRecorded = currentTime;

    // Cek apakah sudah melebihi 5 menit sebelum memperbarui
    var stored = parseInt(localStorage.getItem(LAST_ACTIVITY_KEY) || '0', 10);
    if (stored > 0 && (currentTime - stored > INACTIVITY_TIMEOUT_MS)) {
      handleSessionTimeout();
      return;
    }
    localStorage.setItem(LAST_ACTIVITY_KEY, String(currentTime));
  }

  ['mousemove', 'mousedown', 'keydown', 'touchstart', 'scroll', 'click'].forEach(function (eventName) {
    window.addEventListener(eventName, recordUserActivity, { passive: true });
  });

  // Background interval memeriksa ketidakaktifan setiap 5 detik secara proaktif
  setInterval(function () {
    var token = sessionStorage.getItem(AUTH_TOKEN_KEY);
    if (!token) return;
    var stored = parseInt(localStorage.getItem(LAST_ACTIVITY_KEY) || '0', 10);
    if (stored > 0 && (Date.now() - stored > INACTIVITY_TIMEOUT_MS)) {
      handleSessionTimeout();
    }
  }, 5000);

  // 7. Tangkap klik pada tautan internal modul admin
  document.addEventListener('click', function (e) {
    var link = e.target.closest('a');
    if (!link) return;

    var href = link.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;

    // Jika tautan menuju logout atau keluar ke domain lain
    if (href.indexOf('login') !== -1 || href.indexOf('logout') !== -1 || href.startsWith('http')) {
      sessionStorage.removeItem(NAV_FLAG);
      return;
    }

    // Tandai perpindahan menu internal admin yang sah
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

  // 8. Helper navigasi internal terprogram (misal pencarian global atau tombol aksi)
  window.adminNavigate = function (url) {
    sessionStorage.setItem(NAV_FLAG, '1');
    localStorage.setItem(LAST_ACTIVITY_KEY, String(Date.now()));
    window.location.href = toNavigableAdminRoute(url);
  };
})();
