<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <title>Login Admin - Aswaja CyberShield</title>
  <link rel="icon" type="image/svg+xml" href="../../favicon.svg">

  <!-- Google Fonts: Inter & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
            display: ['"Plus Jakarta Sans"', 'sans-serif']
          },
          colors: {
            emerald: {
              50: '#ecfdf5',
              100: '#d1fae5',
              200: '#a7f3d0',
              300: '#6ee7b7',
              400: '#34d399',
              500: '#10b981',
              600: '#059669',
              700: '#047857',
              800: '#065f46',
              900: '#064e3b',
              950: '#022c22'
            }
          }
        }
      }
    }
  </script>

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/style.css">

  <style>
    /* Spinner Loading Berputar Sesuai Desain */
    .aswaja-spinner {
      width: 20px;
      height: 20px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-top-color: #ffffff;
      border-radius: 50%;
      display: inline-block;
      animation: spin-emerald 0.75s linear infinite;
    }
    @keyframes spin-emerald {
      to { transform: rotate(360deg); }
    }
  </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800 min-h-screen">

  <!-- PAGE LOADING SCREEN -->
  <div id="page-loader" role="status" aria-label="Memuat halaman">
    <div class="loader-content">
      <div class="loader-logo-wrap">
        <img src="../../logo-shield.png" alt="Aswaja CyberShield" class="loader-logo-img">
      </div>
      <div class="loader-bar-track">
        <div class="loader-bar-fill"></div>
      </div>
      <span class="loader-text">Memuat...</span>
    </div>
  </div>

  <!-- MAIN SPLIT SCREEN LAYOUT -->
  <div class="min-h-screen flex flex-col lg:flex-row">

    <!-- LEFT PANEL: Dark Islamic Cyber Green Gradient (Hero / Brand Identity) -->
    <div class="lg:w-1/2 xl:w-7/12 bg-gradient-to-br from-[#064e3b] via-[#022c22] to-[#01140e] p-8 sm:p-12 lg:p-16 xl:p-20 flex flex-col justify-between relative overflow-hidden text-white">
      <!-- Ambient Glow Orbs -->
      <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-emerald-600/10 rounded-full blur-3xl pointer-events-none"></div>
      
      <!-- Top Brand Header -->
      <div class="relative z-10">
        <div class="flex items-center gap-3.5">
          <img src="../../logo-shield.png" alt="Logo Resmi Aswaja CyberShield" class="w-12 h-12 object-contain rounded-2xl ring-1 ring-emerald-400/40 shadow-xl p-1 bg-emerald-950/40">
          <div class="flex flex-col">
            <span class="font-display font-extrabold text-xl tracking-tight text-white">
              ASWAJA <span class="text-emerald-400">CYBERSHIELD</span>
            </span>
            <span class="text-[10px] text-emerald-300 font-semibold uppercase tracking-widest">
              &bull; Literasi &amp; Keamanan Siber &bull;
            </span>
          </div>
        </div>
      </div>

      <!-- Middle Content -->
      <div class="my-auto py-12 relative z-10 max-w-xl">
        <!-- Admin Panel Pill Badge -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-400/30 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-6 backdrop-blur-sm shadow-sm">
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
          <span>ADMIN PANEL</span>
        </div>

        <!-- Big Headline -->
        <h1 class="font-display font-extrabold text-3xl sm:text-4xl lg:text-5xl text-white tracking-tight leading-[1.15] mb-5">
          Kelola Platform <br>
          <span class="text-emerald-400">Aswaja CyberShield</span> dengan aman.
        </h1>

        <!-- Subtitle Paragraph -->
        <p class="text-slate-300 text-sm sm:text-base leading-relaxed mb-8 font-normal" align="justify">
          Dashboard internal untuk memverifikasi laporan aduan siber, mengelola konten edukasi fiqih, dan membentengi masyarakat dari bahaya kejahatan digital.
        </p>

        <!-- Quote Box (Workhard and show your dignity!) -->
        <div class="p-5 rounded-2xl bg-white/[0.05] border border-emerald-500/20 backdrop-blur-md shadow-2xl">
          <p class="text-emerald-400 font-bold text-sm mb-1.5 font-display flex items-center gap-2">
            <i class="bi bi-quote text-lg text-emerald-400"></i>
            <span>&ldquo;Tabayyun adalah benteng kehormatan dan adab digital !&rdquo;</span>
          </p>
          <p class="text-slate-300/90 text-xs leading-relaxed" align="justify">
            Verifikasi fakta, jaga integritas data, dan tampilkan kualitas terbaik untuk kemaslahatan ummah.
          </p>
        </div>
      </div>
    </div>

    <!-- RIGHT PANEL: Crisp White Smoke Container with Floating Card -->
    <div class="flex-1 flex flex-col justify-center items-center p-6 sm:p-10 lg:p-12 xl:p-16 bg-slate-100/80 relative">
      
      <!-- Floating White Login Card -->
      <div class="bg-white rounded-3xl p-8 sm:p-10 shadow-2xl border border-slate-200/80 max-w-md w-full relative z-10 transition-all">
        
        <!-- Card Header -->
        <div class="mb-6">
          <h2 class="text-2xl font-display font-bold text-slate-900 tracking-tight mb-1">
            Login Admin ACS
          </h2>
          <p class="text-xs text-slate-500 leading-relaxed">
            Masuk untuk mengelola sistem dan verifikasi laporan Aswaja CyberShield.
          </p>
        </div>

        <!-- Alert Error Message -->
        <div id="login-alert" class="hidden items-center gap-2.5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs mb-5 shadow-sm transition-all" role="alert">
          <i class="bi bi-exclamation-triangle-fill text-rose-500 shrink-0 text-sm" id="login-alert-icon"></i>
          <span id="login-alert-text">Kredensial tidak valid.</span>
        </div>

        <!-- Login Form -->
        <form id="form-login" novalidate autocomplete="off" class="space-y-4">
          
          <!-- Username / Email Field -->
          <div>
            <label for="username" class="block text-xs font-semibold text-slate-700 mb-1.5">
              Email / Username
            </label>
            <div class="relative">
              <input 
                type="text" 
                id="username" 
                name="username" 
                placeholder="Masukkan email atau username" 
                required 
                autofocus 
                autocomplete="off"
                class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium">
            </div>
          </div>

          <!-- Password Field -->
          <div>
            <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">
              Password
            </label>
            <div class="relative">
              <input 
                type="password" 
                id="password" 
                name="password" 
                placeholder="Masukkan password" 
                required 
                autocomplete="new-password"
                class="w-full px-4 py-3 pr-11 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium">
              <button 
                type="button" 
                id="btn-toggle-password" 
                class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 text-slate-400 hover:text-slate-600 transition-colors"
                title="Tampilkan / Sembunyikan Password">
                <i class="bi bi-eye text-base" id="toggle-icon"></i>
              </button>
            </div>
          </div>

          <!-- Options Row: Ingat Saya & Lupa Password -->
          <div class="flex items-center justify-between text-xs pt-1">
            <label class="flex items-center gap-2 text-slate-600 cursor-pointer select-none">
              <input type="checkbox" id="remember-me" class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300">
              <span class="text-xs text-slate-600">Ingat saya</span>
            </label>
            <a href="/lupa-password" class="text-emerald-700 hover:text-emerald-800 font-semibold hover:underline">
              Lupa password?
            </a>
          </div>

          <!-- Submit Button: Sleek Black Button with Emerald Hover Glow -->
          <button 
            type="submit" 
            id="btn-submit" 
            class="w-full py-3.5 px-6 rounded-xl bg-slate-950 hover:bg-emerald-700 text-white font-bold text-sm tracking-wider uppercase shadow-lg shadow-slate-900/10 hover:shadow-emerald-950/20 transition-all duration-200 flex items-center justify-center gap-2 mt-2">
            <span id="btn-spinner" class="aswaja-spinner hidden" role="status" aria-label="Memverifikasi..."></span>
            <i class="bi bi-shield-lock text-sm" id="btn-icon"></i>
            <span id="btn-text">LOG IN</span>
          </button>
        </form>

      </div>

      <!-- Copyright Under Card -->
      <p class="text-center text-xs text-slate-400 mt-6 tracking-wide">
        &copy; 2026 Aswaja CyberShield. Internal access only.
      </p>

    </div>

  </div>

  <!-- API Layer -->
  <script src="../../js/api.js"></script>

  <!-- Login Logic Script -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('form-login');
      const usernameInput = document.getElementById('username');
      const passwordInput = document.getElementById('password');
      const btnToggle = document.getElementById('btn-toggle-password');
      const toggleIcon = document.getElementById('toggle-icon');
      const alertBox = document.getElementById('login-alert');
      const alertText = document.getElementById('login-alert-text');
      const alertIcon = document.getElementById('login-alert-icon');
      const btnSubmit = document.getElementById('btn-submit');
      const btnSpinner = document.getElementById('btn-spinner');
      const btnIcon = document.getElementById('btn-icon');
      const btnText = document.getElementById('btn-text');

      // Deteksi prefix base path (kosong di Vercel/root domain, atau nama folder di localhost)
      function getBasePrefix(pathname) {
        const feIdx = pathname.indexOf('/frontend/');
        if (feIdx >= 0) return pathname.slice(0, feIdx);
        const adminIdx = pathname.indexOf('/admin');
        if (adminIdx >= 0) return pathname.slice(0, adminIdx);
        const loginIdx = pathname.indexOf('/login');
        if (loginIdx >= 0) return pathname.slice(0, loginIdx);
        return '';
      }

      const basePrefix = getBasePrefix(window.location.pathname || '');

      // Bersihkan URL di address bar menjadi /login (tanpa .blade.php)
      try {
        const cleanLoginUrl = (basePrefix || '') + '/login';
        if (window.location.pathname !== cleanLoginUrl) {
          window.history.replaceState(null, '', cleanLoginUrl + window.location.search + window.location.hash);
        }
      } catch (e) {}

      // =========================================================================
      // LOGIN THROTTLING & ACCOUNT LOCKOUT POLICY (ANTI-BRUTE FORCE)
      // Cooldown berjenjang: 1 menit, 3 menit, 5 menit, 15 menit, hingga 1 jam
      // =========================================================================
      const THROTTLE_KEY = 'aswaja_admin_throttle';
      const COOLDOWN_TIERS = [60, 180, 300, 900, 3600]; // Dalam detik: 1m, 3m, 5m, 15m, 1h
      let lockoutInterval = null;

      function getThrottle() {
        try {
          const raw = localStorage.getItem(THROTTLE_KEY);
          return raw ? JSON.parse(raw) : { failed: 0, tier: 0, lockedUntil: 0 };
        } catch {
          return { failed: 0, tier: 0, lockedUntil: 0 };
        }
      }

      function saveThrottle(t) {
        try {
          localStorage.setItem(THROTTLE_KEY, JSON.stringify(t));
        } catch (e) {}
      }

      function clearThrottle() {
        try {
          localStorage.removeItem(THROTTLE_KEY);
        } catch (e) {}
      }

      function formatRemaining(ms) {
        const totalSec = Math.max(0, Math.ceil(ms / 1000));
        const hours = Math.floor(totalSec / 3600);
        const mins = Math.floor((totalSec % 3600) / 60);
        const secs = totalSec % 60;
        if (hours > 0) {
          const mm = String(mins).padStart(2, '0');
          const ss = String(secs).padStart(2, '0');
          return `${hours} jam ${mm}:${ss}`;
        }
        const mm = String(mins).padStart(2, '0');
        const ss = String(secs).padStart(2, '0');
        return `${mm}:${ss}`;
      }

      function startLockoutCountdown(lockedUntil) {
        if (lockoutInterval) clearInterval(lockoutInterval);

        function tick() {
          const now = Date.now();
          const remaining = lockedUntil - now;
          if (remaining <= 0) {
            clearInterval(lockoutInterval);
            lockoutInterval = null;
            btnSubmit.disabled = false;
            usernameInput.disabled = false;
            passwordInput.disabled = false;
            btnSubmit.classList.remove('opacity-60', 'cursor-not-allowed');
            resetButton();
            showAlert('Waktu penguncian akun telah berakhir. Anda dapat mencoba login kembali.', 'info');
            return;
          }

          btnSubmit.disabled = true;
          usernameInput.disabled = true;
          passwordInput.disabled = true;
          btnSubmit.classList.add('opacity-60', 'cursor-not-allowed');
          btnSpinner.classList.add('hidden');
          btnIcon.className = 'bi bi-shield-slash-fill text-sm';
          btnIcon.classList.remove('hidden');
          btnText.classList.remove('hidden');
          btnText.textContent = `TERKUNCI (${formatRemaining(remaining)})`;

          showAlert(`Akun dikunci sementara untuk mencegah serangan Brute Force. Cooldown tersisa: ${formatRemaining(remaining)}`, 'danger');
        }

        tick();
        lockoutInterval = setInterval(tick, 1000);
      }

      function isCurrentlyLockedOut() {
        const t = getThrottle();
        if (t.lockedUntil && t.lockedUntil > Date.now()) {
          startLockoutCountdown(t.lockedUntil);
          return true;
        }
        return false;
      }

      // Helper Tampilan Pesan Peringatan & Status
      function showAlert(msg, variant = 'danger') {
        alertText.textContent = msg;
        alertBox.className = 'flex items-center gap-2.5 p-3 rounded-xl text-xs mb-5 shadow-sm transition-all';
        if (variant === 'warning') {
          alertBox.classList.add('bg-amber-50', 'border', 'border-amber-200', 'text-amber-800');
          if (alertIcon) alertIcon.className = 'bi bi-clock-history text-amber-600 shrink-0 text-sm';
        } else if (variant === 'info') {
          alertBox.classList.add('bg-emerald-50', 'border', 'border-emerald-200', 'text-emerald-800');
          if (alertIcon) alertIcon.className = 'bi bi-check-circle-fill text-emerald-600 shrink-0 text-sm';
        } else {
          // danger
          alertBox.classList.add('bg-rose-50', 'border', 'border-rose-200', 'text-rose-700');
          if (alertIcon) alertIcon.className = 'bi bi-shield-exclamation text-rose-500 shrink-0 text-sm';
        }
      }

      function resetButton() {
        if (isCurrentlyLockedOut()) return;
        btnSubmit.disabled = false;
        btnSubmit.classList.remove('opacity-60', 'cursor-not-allowed');
        btnSpinner.classList.add('hidden');
        btnIcon.className = 'bi bi-shield-lock text-sm';
        btnIcon.classList.remove('hidden');
        btnText.classList.remove('hidden');
        btnText.textContent = 'LOG IN';
        btnSubmit.classList.remove('bg-emerald-600');
        btnSubmit.classList.add('bg-slate-950');
      }

      // Helper redirect ke dashboard admin
      function redirectToDashboard() {
        const isVercel = window.location.hostname.indexOf('vercel.app') !== -1;
        const loader = document.getElementById('page-loader');
        if (loader) {
          const bar = loader.querySelector('.loader-bar-fill');
          const text = loader.querySelector('.loader-text');
          if (text) text.textContent = 'Membuka Dashboard...';
          if (bar) bar.style.width = '75%';
          loader.classList.remove('loader-hide');
        }
        window.location.replace((basePrefix || '') + (isVercel ? '/admin' : '/frontend/admin/index.blade.php'));
      }

      window.addEventListener('load', function() {
        const loader = document.getElementById('page-loader');
        if (loader) {
          const bar = loader.querySelector('.loader-bar-fill');
          if (bar) bar.style.width = '100%';
          setTimeout(function() { loader.classList.add('loader-hide'); }, 200);
        }
      });

      // Bersihkan seluruh riwayat sesi dan input saat halaman login dibuka
      function purgeSessionHistory() {
        try {
          sessionStorage.removeItem('aswaja_admin_token');
          sessionStorage.removeItem('aswaja_admin_user');
          sessionStorage.removeItem('aswaja_admin_navigating');
          localStorage.removeItem('aswaja_admin_token');
          localStorage.removeItem('aswaja_admin_user');
        } catch (e) {}
        if (form) form.reset();
        if (usernameInput) usernameInput.value = '';
        if (passwordInput) passwordInput.value = '';
      }

      purgeSessionHistory();
      window.addEventListener('pageshow', purgeSessionHistory);

      // Cek apakah halaman login dibuka karena Session Timeout (Inactivity Timeout 5 menit)
      const urlParams = new URLSearchParams(window.location.search);
      const isTimeout = urlParams.get('reason') === 'timeout' || localStorage.getItem('aswaja_logout_reason') === 'timeout';
      if (isTimeout) {
        showAlert('Sesi Anda telah berakhir karena tidak ada aktivitas selama 5 menit. Silakan login kembali untuk memverifikasi identitas Anda.', 'warning');
        try {
          localStorage.removeItem('aswaja_logout_reason');
          if (urlParams.has('reason')) {
            window.history.replaceState(null, '', (basePrefix || '') + '/login');
          }
        } catch (e) {}
      }

      // Cek apakah akun sedang dalam periode penguncian anti-brute force saat halaman dimuat
      isCurrentlyLockedOut();

      // Toggle lihat password
      btnToggle?.addEventListener('click', () => {
        const isPassword = passwordInput.getAttribute('type') === 'password';
        passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
        toggleIcon.className = isPassword ? 'bi bi-eye-slash text-base' : 'bi bi-eye text-base';
      });

      // Submit form
      form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // 1. Validasi proteksi penguncian akun (Lockout Guard)
        if (isCurrentlyLockedOut()) {
          return;
        }

        const username = usernameInput.value.trim();
        const password = passwordInput.value;

        if (!username || !password) {
          showAlert('Silakan isi username/email dan kata sandi.', 'danger');
          return;
        }

        // Tampilkan loading state
        btnSubmit.disabled = true;
        btnSpinner.classList.remove('hidden');
        btnIcon.classList.add('hidden');
        btnText.classList.add('hidden');
        btnText.textContent = '';

        try {
          // Panggil API backend login
          const response = await fetch('/api/admin/login', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ username, password })
          });

          const data = await response.json().catch(() => ({}));

          if (response.ok && data.success) {
            // Otentikasi Sukses: Bersihkan jejak lockout & catat waktu awal sesi
            clearThrottle();
            sessionStorage.setItem('aswaja_admin_token', data.token || 'aswaja_session_' + Date.now());
            sessionStorage.setItem('aswaja_admin_user', JSON.stringify(data.user || { name: 'Admin Utama', role: 'Super Admin' }));
            sessionStorage.setItem('aswaja_admin_navigating', '1');
            localStorage.setItem('aswaja_admin_last_activity', String(Date.now()));

            btnSpinner.classList.add('hidden');
            btnIcon.className = 'bi bi-check-circle-fill text-sm';
            btnIcon.classList.remove('hidden');
            btnText.classList.remove('hidden');
            btnText.textContent = 'LOGIN BERHASIL!';
            btnSubmit.classList.remove('bg-slate-950');
            btnSubmit.classList.add('bg-emerald-600');

            setTimeout(() => {
              redirectToDashboard();
            }, 300);
            return;
          }

          // Kasus Terkunci dari Backend (HTTP 429 - Account Lockout)
          if (response.status === 429 || data.locked) {
            const cooldownSec = data.cooldown_seconds || 60;
            const lockedUntil = Date.now() + (cooldownSec * 1000);
            const t = getThrottle();
            t.failed = 0;
            t.tier = data.tier || (t.tier + 1);
            t.lockedUntil = lockedUntil;
            saveThrottle(t);
            startLockoutCountdown(lockedUntil);
            return;
          }

          // Kasus Gagal Kredensial dari Backend (HTTP 401)
          const t = getThrottle();
          t.failed = (t.failed || 0) + 1;

          if (t.failed >= 3) {
            // 3x salah berturut-turut -> picu lockout
            const tier = t.tier || 0;
            const cooldownSec = COOLDOWN_TIERS[Math.min(tier, COOLDOWN_TIERS.length - 1)];
            const lockedUntil = Date.now() + (cooldownSec * 1000);
            t.failed = 0;
            t.tier = tier + 1;
            t.lockedUntil = lockedUntil;
            saveThrottle(t);
            startLockoutCountdown(lockedUntil);
          } else {
            saveThrottle(t);
            const remaining = 3 - t.failed;
            showAlert(data.error || data.message || `Username atau password salah. Sisa percobaan: ${remaining}x sebelum akun dikunci sementara.`, 'danger');
            resetButton();
          }
        } catch (err) {
          console.warn('Backend API tidak merespons, verifikasi secara lokal:', err);

          // Cek Kredensial Offline / Demo
          const isOfflineAdmin = (username === 'admin' && (password === 'admin' || password === 'Aswaja2026!')) ||
                                 (username === 'admin@aswajacyber.id' && (password === 'admin' || password === 'Aswaja2026!'));
          const isOfflineAnalis = (username === 'analis' && (password === 'analis' || password === 'Aswaja2026!'));

          if (isOfflineAdmin || isOfflineAnalis) {
            clearThrottle();
            sessionStorage.setItem('aswaja_admin_token', 'offline_admin_token_' + Date.now());
            sessionStorage.setItem('aswaja_admin_user', JSON.stringify(isOfflineAdmin ? { name: 'Admin Utama', username: 'admin', role: 'Super Admin' } : { name: 'Ahmad Fauzi (Analis)', username: 'analis', role: 'Admin Basic' }));
            sessionStorage.setItem('aswaja_admin_navigating', '1');
            localStorage.setItem('aswaja_admin_last_activity', String(Date.now()));
            redirectToDashboard();
            return;
          }

          // Gagal verifikasi offline -> terapkan kebijakan lockout anti brute-force
          const t = getThrottle();
          t.failed = (t.failed || 0) + 1;

          if (t.failed >= 3) {
            const tier = t.tier || 0;
            const cooldownSec = COOLDOWN_TIERS[Math.min(tier, COOLDOWN_TIERS.length - 1)];
            const lockedUntil = Date.now() + (cooldownSec * 1000);
            t.failed = 0;
            t.tier = tier + 1;
            t.lockedUntil = lockedUntil;
            saveThrottle(t);
            startLockoutCountdown(lockedUntil);
          } else {
            saveThrottle(t);
            const remaining = 3 - t.failed;
            showAlert(`Username atau password tidak cocok. Sisa percobaan: ${remaining}x sebelum akun dikunci sementara.`, 'danger');
            resetButton();
          }
        }
      });
    });
  </script>
</body>
</html>
