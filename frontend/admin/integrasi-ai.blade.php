<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <script src="../../js/admin-guard.js"></script>
  <title>Integrasi AI (Google Gemini AI) - Aswaja CyberShield Admin</title>
  <link rel="icon" type="image/svg+xml" href="../../favicon.svg">

  <!-- Google Fonts: Inter & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Tailwind CSS Play CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
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
              950: '#022c22',
            }
          },
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
            display: ['Plus Jakarta Sans', 'sans-serif']
          }
        }
      }
    }
  </script>
  <link rel="stylesheet" href="../../css/ui-kit.css">
</head>

<body class="h-full text-slate-800 antialiased flex bg-slate-50 font-sans">

  <!-- Sidebar Navigasi -->
  <aside id="admin-sidebar" class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col flex-shrink-0 fixed inset-y-0 left-0 z-40 lg:static transition-transform -translate-x-full lg:translate-x-0">
    <!-- Brand Logo -->
    <div class="h-16 flex items-center gap-3 px-5 border-b border-slate-800/80 bg-slate-950/40">
      <img src="../../logo-shield.png" alt="Aswaja CyberShield" class="w-9 h-9 object-contain">
      <div class="min-w-0">
        <span class="font-display font-bold text-sm text-white tracking-tight block truncate">Aswaja CyberShield</span>
        <span class="text-[10px] text-emerald-400 font-semibold uppercase tracking-wider block">Admin Dashboard</span>
      </div>
    </div>

    <!-- Navigation Menu Items -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6" aria-label="Navigasi Utama Admin">
      <div>
        <p class="px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-2">Menu Utama</p>
        <div class="space-y-1">
          <!-- 1. Dashboard -->
          <a href="/admin" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/70 transition-all group">
            <i class="bi bi-speedometer2 text-base text-slate-400 group-hover:text-emerald-400 transition-colors"></i>
            <span>Dashboard</span>
          </a>

          <!-- 2. Artikel -->
          <a href="/admin/artikel" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/70 transition-all group">
            <i class="bi bi-file-earmark-text text-base text-slate-400 group-hover:text-emerald-400 transition-colors"></i>
            <span>Artikel</span>
          </a>

          <!-- 3. Riwayat Pelaporan -->
          <a href="/admin/laporan" class="flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/70 transition-all group">
            <span class="flex items-center gap-3">
              <i class="bi bi-shield-exclamation text-base text-slate-400 group-hover:text-emerald-400 transition-colors"></i>
              <span>Laporan</span>
            </span>
            <span id="badge-sidebar-reports" class="bg-rose-500/20 text-rose-400 text-xs px-2 py-0.5 rounded-full font-bold border border-rose-500/30">0</span>
          </a>

          <!-- 4. Studi Kasus -->
          <a href="/admin/studi-kasus" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/70 transition-all group">
            <i class="bi bi-journal-check text-base text-slate-400 group-hover:text-emerald-400 transition-colors"></i>
            <span>Studi Kasus</span>
          </a>

          <!-- 6. Kelola Akun -->
          <a href="/admin/akun" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/70 transition-all group">
            <i class="bi bi-people text-base text-slate-400 group-hover:text-emerald-400 transition-colors"></i>
            <span>Kelola Akun</span>
          </a>

        </div>
      </div>

    <!-- Sidebar Bottom / User Bar -->
    <div class="p-3 mt-auto border-t border-slate-800/80 bg-slate-950/40">
      <div class="flex items-center justify-between px-2.5 py-2 rounded-xl bg-slate-800/60 border border-slate-700/50">
        <div class="flex items-center gap-2.5 min-w-0">
          <div id="sidebar-avatar" class="w-8 h-8 rounded-lg bg-emerald-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-sm">
            A
          </div>
          <div class="min-w-0 flex-1">
            <p id="sidebar-user-name" class="text-xs font-semibold text-white truncate">Admin Utama</p>
            <p id="sidebar-user-role" class="text-[10px] text-emerald-400 truncate font-mono">Super Admin</p>
          </div>
        </div>
        <button id="sidebar-btn-logout" type="button" class="text-slate-400 hover:text-rose-400 p-1.5 rounded-lg hover:bg-slate-700/60 transition-colors" title="Keluar">
          <i class="bi bi-box-arrow-right"></i>
        </button>
      </div>
    </div>
  </aside>

  <!-- Main Content Wrapper -->
  <div class="flex-1 flex flex-col min-w-0 overflow-y-auto min-h-screen">

    <!-- Topbar Header -->
    <header class="sticky top-0 z-30 h-16 bg-white/95 backdrop-blur-sm border-b border-slate-200/90 px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
      <div class="flex items-center gap-3 min-w-0">
        <button id="toggle-sidebar-btn" type="button" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors" aria-label="Buka navigasi">
          <i class="bi bi-list text-2xl leading-none"></i>
        </button>
      </div>

      <!-- Right: Clock, Refresh, Profil Admin -->
      <div class="flex items-center gap-2 sm:gap-3">
        <div class="hidden xl:flex items-center gap-1.5 text-xs text-slate-600 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200 font-mono">
          <i class="bi bi-clock text-slate-400"></i>
          <span id="live-clock"><?php date_default_timezone_set('Asia/Makassar'); echo date('H:i:s'); ?> WITA</span>
        </div>

        <button type="button" id="btn-refresh-status" class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors" title="Segarkan Status">
          <i class="bi bi-arrow-clockwise text-base"></i>
        </button>

        <!-- Profil Admin Dropdown -->
        <div class="relative" id="profile-dropdown-wrapper">
          <button id="profile-btn" type="button" class="flex items-center gap-2.5 p-1 sm:px-2.5 sm:py-1.5 rounded-xl hover:bg-slate-100 transition-colors">
            <div id="topbar-avatar" class="w-8 h-8 rounded-xl bg-gradient-to-tr from-emerald-700 to-emerald-500 text-white font-bold text-xs flex items-center justify-center shadow-sm">
              A
            </div>
            <div class="hidden sm:block text-left">
              <p id="topbar-user-name" class="text-xs font-bold text-slate-800 leading-tight">Admin Utama</p>
              <p id="topbar-user-role" class="text-[10px] text-emerald-600 font-medium leading-tight">Super Admin</p>
            </div>
            <i class="bi bi-chevron-down text-xs text-slate-400 hidden sm:block"></i>
          </button>

          <div id="profile-menu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-200 py-2 z-50 divide-y divide-slate-100">
            <div class="px-4 py-2">
              <p class="text-xs text-slate-400">Masuk sebagai</p>
              <p id="dropdown-user-email" class="text-xs font-semibold text-slate-800 truncate">admin@aswajacyber.id</p>
            </div>
            <div class="py-1">
              <a href="/admin/akun" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                <i class="bi bi-person-gear text-slate-400"></i>
                <span>Kelola Akun &amp; Profil</span>
              </a>
            </div>
            <div class="pt-1">
              <button type="button" id="topbar-btn-logout" class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar (Logout)</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content Body -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-6">

      <!-- Breadcrumbs & Heading -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
          <h1 class="text-2xl font-display font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
            <i class="bi bi-cpu text-emerald-600"></i>
            <span>Integrasi AI (Google Gemini AI)</span>
          </h1>
          <p class="text-sm text-slate-500 mt-0.5">Monitoring status orkestrasi AI (Google Gemini AI + Suricata IDS &amp; UrlThreatAnalyzer) untuk Tabayyun tautan dan audit laporan siber.</p>
        </div>
        <div id="super-admin-badge">
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-300">
            <i class="bi bi-shield-check"></i> Super Admin Only
          </span>
        </div>
      </div>

      <!-- Warning jika bukan Super Admin -->
      <div id="rbac-warning" class="hidden">
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-start gap-3">
          <i class="bi bi-exclamation-triangle-fill text-amber-600 text-lg shrink-0 mt-0.5"></i>
          <div>
            <strong class="font-semibold block">Akses Terbatas:</strong>
            Halaman konfigurasi integrasi AI hanya dapat dikelola secara penuh oleh <strong>Super Admin</strong>. Anda login sebagai <strong>Admin Basic</strong> dengan hak baca status.
          </div>
        </div>
      </div>

      <!-- 4 Status Metric Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        
        <!-- 1. Status Integrasi -->
        <div onclick="runProbeTest()" role="button" tabindex="0" class="bg-white rounded-2xl p-5 border border-slate-200/80 hover:border-emerald-500/50 hover:shadow-md transition-all cursor-pointer active:scale-[0.99] group" title="Klik untuk menguji probe orkestrasi AI">
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider group-hover:text-emerald-700 transition-colors">Status Integrasi</span>
            <div id="icon-status-box" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
              <i class="bi bi-link-45deg text-xl"></i>
            </div>
          </div>
          <div class="flex items-baseline gap-2">
            <span id="stat-integration-status" class="text-lg font-display font-extrabold text-slate-900 group-hover:text-emerald-700 transition-colors">Memeriksa...</span>
          </div>
          <p id="stat-integration-sub" class="text-xs text-slate-400 mt-1">Multi-Agent Orchestrator</p>
        </div>

        <!-- 2. Agen 1: Gemini -->
        <div onclick="runProbeTest()" role="button" tabindex="0" class="bg-white rounded-2xl p-5 border border-slate-200/80 hover:border-emerald-500/50 hover:shadow-md transition-all cursor-pointer active:scale-[0.99] group" title="Klik untuk menguji probe Agen Gemini">
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider group-hover:text-emerald-700 transition-colors">Agen 1 (Gemini)</span>
            <div id="icon-webhook-box" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
              <i class="bi bi-lightning-charge text-lg"></i>
            </div>
          </div>
          <div class="flex items-baseline gap-2">
            <span id="stat-webhook-status" class="text-lg font-display font-extrabold text-slate-900 group-hover:text-emerald-700 transition-colors">Siaga</span>
          </div>
          <p id="stat-webhook-sub" class="text-xs text-slate-400 mt-1">Spesialis Cek URL Tabayyun</p>
        </div>

        <!-- 3. Sensor Suricata NIDS -->
        <div onclick="runProbeTest()" role="button" tabindex="0" class="bg-white rounded-2xl p-5 border border-slate-200/80 hover:border-emerald-500/50 hover:shadow-md transition-all cursor-pointer active:scale-[0.99] group" title="Klik untuk menguji probe Sensor NIDS">
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider group-hover:text-emerald-700 transition-colors">Sensor NIDS</span>
            <div id="icon-ai-box" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
              <i class="bi bi-shield-check text-lg"></i>
            </div>
          </div>
          <div class="flex items-baseline gap-2">
            <span id="stat-ai-status" class="text-lg font-display font-extrabold text-slate-900 group-hover:text-emerald-700 transition-colors">Aktif</span>
          </div>
          <p id="stat-ai-sub" class="text-xs text-slate-400 mt-1">Suricata IDS &amp; Threat Analyzer</p>
        </div>

        <!-- 4. Pengujian Terakhir -->
        <div onclick="runProbeTest()" role="button" tabindex="0" class="bg-white rounded-2xl p-5 border border-slate-200/80 hover:border-emerald-500/50 hover:shadow-md transition-all cursor-pointer active:scale-[0.99] group" title="Klik untuk memperbarui uji probe">
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider group-hover:text-emerald-700 transition-colors">Pengujian Terakhir</span>
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
              <i class="bi bi-clock-history text-lg"></i>
            </div>
          </div>
          <div class="flex items-baseline gap-2">
            <span id="stat-last-checked" class="text-xs font-mono font-semibold text-slate-700 group-hover:text-emerald-700 transition-colors">--</span>
          </div>
          <p class="text-xs text-slate-400 mt-1">Klik untuk Uji Probe Ulang</p>
        </div>

      </div>

      <!-- Action Panel & Diagnostics Box -->
      <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-5">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
          <div>
            <h2 class="text-base font-display font-bold text-slate-900">Pengujian Probe Koneksi AI</h2>
            <p class="text-xs text-slate-500 mt-0.5">Kirimkan paket probe ringan (*health-check payload*) untuk memastikan Google Gemini AI aktif dan siap menerima data tanpa latensi.</p>
          </div>
          <button type="button" id="btn-test-connection" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-sm shadow-emerald-600/20 transition-all shrink-0">
            <span id="test-btn-spinner" class="hidden">
              <span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
            </span>
            <i id="test-btn-icon" class="bi bi-broadcast"></i>
            <span id="test-btn-text">UJI KONEKSI SEKARANG</span>
          </button>
        </div>

        <!-- Feedback Alert Box -->
        <div id="test-alert-box" class="hidden p-4 rounded-xl text-xs space-y-1.5 transition-all">
          <div class="flex items-center gap-2 font-bold text-sm" id="test-alert-title">
            <i id="test-alert-icon" class="bi"></i>
            <span id="test-alert-heading">Hasil Uji Koneksi</span>
          </div>
          <p id="test-alert-desc" class="text-slate-600 leading-relaxed"></p>
        </div>

        <!-- Masked Configuration Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
          
          <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1.5">
            <div class="flex items-center justify-between">
              <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Google Gemini (Tabayyun Cepat)</span>
              <span class="text-[10px] px-2 py-0.5 rounded-md bg-blue-100 text-blue-800 font-semibold font-mono">gemini-1.5-flash</span>
            </div>
            <div class="flex items-center justify-between gap-2">
              <span id="config-webhook-url" class="font-mono text-xs font-semibold text-slate-800 truncate">AIzaSy... (Gemini API)</span>
              <span class="text-[10px] px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-semibold">Tersandikan</span>
            </div>
            <p class="text-[11px] text-slate-400">Dikonfigurasi via server environment `GEMINI_API_KEY`.</p>
          </div>

          <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1.5">
            <div class="flex items-center justify-between">
              <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Suricata IDS &amp; Threat Analyzer</span>
              <span class="text-[10px] px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-semibold font-mono">SID 1000101-103</span>
            </div>
            <div class="flex items-center justify-between gap-2">
              <span id="config-api-key" class="font-mono text-xs font-semibold text-slate-800">Rule Engine Aktif</span>
              <span class="text-[10px] px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-semibold">Tersinkron</span>
            </div>
            <p class="text-[11px] text-slate-400">Inspeksi L1 Packet &amp; Normalisasi Teks URL.</p>
          </div>

        </div>
      </div>

      <!-- Panduan & Spesifikasi Arsitektur Multi-Agent -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Card 1: Alur Kerja Integrasi -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-4">
          <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
              <i class="bi bi-diagram-3"></i>
            </div>
            <h3 class="font-display font-bold text-base text-slate-900">Alur Orkestrasi AI &amp; Fallback</h3>
          </div>
          <ol class="space-y-3 text-xs text-slate-600 list-decimal list-inside leading-relaxed">
            <li><strong>Permintaan dari Pengunjung:</strong> Tautan URL dikirimkan dari frontend Tabayyun ke endpoint Laravel <code>POST /api/analyze-link</code>.</li>
            <li><strong>Validasi Keamanan &amp; SSRF:</strong> Backend memvalidasi format URL dan memastikan skema bukan private/loopback IP (tanpa mengambil konten eksternal).</li>
            <li><strong>Sensor Heuristik &amp; Suricata IDS:</strong> Menganalisis indikator ancaman URL (Defacement, Open Redirect, Typosquatting).</li>
            <li><strong>Google Gemini AI:</strong> Menjalankan audit sanad domain dan pola 4T dengan kecepatan tinggi (&lt; 1 detik).</li>
            <li><strong>Fallback Heuristik Anggun:</strong> Jika API AI eksternal mengalami kendala jaringan, sistem otomatis beralih ke <em>Aswaja Heuristic Engine</em> lokal sehingga sistem tidak pernah mogok.</li>
          </ol>
        </div>

        <!-- Card 2: Petunjuk Konfigurasi Server .env -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-4">
          <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
              <i class="bi bi-gear-wide-connected"></i>
            </div>
            <h3 class="font-display font-bold text-base text-slate-900">Petunjuk Konfigurasi Server (.env)</h3>
          </div>
          <p class="text-xs text-slate-600 leading-relaxed">
            Untuk mengaktifkan AI pada server atau Vercel, tambahkan variabel environment berikut:
          </p>
          <div class="p-3.5 rounded-xl bg-slate-900 text-slate-200 font-mono text-xs overflow-x-auto space-y-1">
            <p><span class="text-emerald-400">GEMINI_API_KEY</span>=AIzaSy_your_gemini_key_here</p>
            <p><span class="text-slate-400">GEMINI_MODEL</span>=gemini-1.5-flash</p>
          </div>
          <p class="text-[11px] text-slate-400 italic">
            *Catatan: Menggunakan Google Gemini AI sebagai mesin utama yang diintegrasikan dengan sensor Suricata IDS lokal.
          </p>
        </div>

      </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/90 py-4 px-6 text-center text-xs text-slate-500 mt-auto">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-2 max-w-7xl mx-auto">
        <p>&copy; 2026 <strong class="font-semibold text-slate-700">Aswaja CyberShield</strong> &bull; Modul Integrasi Dual AI</p>
        <p class="text-slate-400">Orkestrasi AI Multi-Agent Berbasis Nilai Islam Moderat</p>
      </div>
    </footer>
  </div>

  <!-- Scripts -->
  <script src="../../js/api.js"></script>
  <script src="../../js/ui-kit.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // 1. Cek Sesi Autentikasi
      const currentUser = AuthService.getUser();
      const token = AuthService.getToken();

      if (!token || !currentUser) {
        if (window.adminLogoutRedirect) window.adminLogoutRedirect();
        else window.location.replace('/login');
        return;
      }

      const isSuper = (currentUser.role === 'Super Admin' || currentUser.role === 'Superadmin');
      const initial = (currentUser.name || 'Admin').charAt(0).toUpperCase();

      document.getElementById('sidebar-user-name').textContent = currentUser.name || 'Admin Utama';
      document.getElementById('sidebar-user-role').textContent = currentUser.role || (isSuper ? 'Super Admin' : 'Admin Basic');
      document.getElementById('sidebar-avatar').textContent = initial;

      document.getElementById('topbar-user-name').textContent = currentUser.name || 'Admin Utama';
      document.getElementById('topbar-user-role').textContent = isSuper ? 'Super Admin' : 'Admin Basic';
      document.getElementById('topbar-avatar').textContent = initial;
      document.getElementById('dropdown-user-email').textContent = currentUser.email || currentUser.username || 'admin@aswajacyber.id';

      // RBAC Warning jika bukan super admin
      if (!isSuper) {
        document.getElementById('rbac-warning')?.classList.remove('hidden');
        const testBtn = document.getElementById('btn-test-connection');
        if (testBtn) {
          testBtn.disabled = true;
          testBtn.classList.add('opacity-50', 'cursor-not-allowed');
          testBtn.title = 'Hanya Super Admin yang dapat menjalankan pengujian koneksi.';
        }
      }

      // Logout handlers
      function handleLogout() {
        if (window.AuthService && typeof window.AuthService.confirmLogout === 'function') {
          window.AuthService.confirmLogout();
        } else if (confirm('Keluar dari sesi administrator Aswaja CyberShield?')) {
          window.AuthService.logout();
        }
      }
      document.getElementById('sidebar-btn-logout')?.addEventListener('click', handleLogout);
      document.getElementById('topbar-btn-logout')?.addEventListener('click', handleLogout);

      // Mobile Sidebar Toggle
      const sidebar = document.getElementById('admin-sidebar');
      const toggleBtn = document.getElementById('toggle-sidebar-btn');
      toggleBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar?.classList.toggle('-translate-x-full');
      });
      document.addEventListener('click', (e) => {
        if (window.innerWidth < 1024 && sidebar && !sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
          sidebar.classList.add('-translate-x-full');
        }
      });

      // Topbar Profile Dropdown
      const profileBtn = document.getElementById('profile-btn');
      const profileMenu = document.getElementById('profile-menu');
      profileBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        profileMenu?.classList.toggle('hidden');
      });
      document.addEventListener('click', () => {
        profileMenu?.classList.add('hidden');
      });

      // Live Clock
      function updateClock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', timeZone: 'Asia/Makassar' });
        const clockEl = document.getElementById('live-clock');
        if (clockEl) clockEl.textContent = `${timeStr} WITA`;
      }
      setInterval(updateClock, 1000);
      updateClock();

      // --- LOAD STATUS & TEST INTEGRASI AI ---
      const statIntegration = document.getElementById('stat-integration-status');
      const statWebhook = document.getElementById('stat-webhook-status');
      const statAi = document.getElementById('stat-ai-status');
      const statLastChecked = document.getElementById('stat-last-checked');
      const configWebhookUrl = document.getElementById('config-webhook-url');
      const configApiKey = document.getElementById('config-api-key');
      const iconStatusBox = document.getElementById('icon-status-box');
      const iconWebhookBox = document.getElementById('icon-webhook-box');
      const iconAiBox = document.getElementById('icon-ai-box');

      const btnTestConnection = document.getElementById('btn-test-connection');
      const testSpinner = document.getElementById('test-btn-spinner');
      const testIcon = document.getElementById('test-btn-icon');
      const testText = document.getElementById('test-btn-text');

      const testAlertBox = document.getElementById('test-alert-box');
      const testAlertHeading = document.getElementById('test-alert-heading');
      const testAlertIcon = document.getElementById('test-alert-icon');
      const testAlertDesc = document.getElementById('test-alert-desc');

      async function fetchAiStatus() {
        try {
          const res = await apiFetch('/admin/ai/status');
          if (res && res.success && res.data) {
            renderStatus(res.data);
          } else {
            renderOfflineStatus();
          }
        } catch (err) {
          console.warn('[AI] Backend status tidak merespons, menggunakan mode siaga:', err);
          renderOfflineStatus();
        }
      }

      function renderStatus(data) {
        statIntegration.textContent = data.status || 'Siaga';
        statWebhook.textContent = (data.gemini && data.gemini.configured) ? 'Aktif' : 'Nonaktif';
        statAi.textContent = (data.suricata && data.suricata.available) ? 'Aktif' : 'Standby';
        statLastChecked.textContent = data.last_checked || '--';

        if (configWebhookUrl) {
          const g = data.gemini;
          configWebhookUrl.textContent = g && g.configured ? `${g.masked_key} (${g.model})` : 'Belum diatur';
        }
        if (configApiKey) {
          configApiKey.textContent = (data.suricata && data.suricata.available) ? 'Sensor EVE Log Terhubung' : 'Sensor Signature Siaga';
        }

        if (data.configured) {
          iconStatusBox.className = 'w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center';
          iconWebhookBox.className = 'w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center';
          iconAiBox.className = 'w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center';
        } else {
          iconStatusBox.className = 'w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center';
          iconWebhookBox.className = 'w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center';
          iconAiBox.className = 'w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center';
        }
      }

      function renderOfflineStatus() {
        statIntegration.textContent = 'Mode Heuristik';
        statWebhook.textContent = 'Nonaktif';
        statAi.textContent = 'Standby';
        statLastChecked.textContent = new Date().toLocaleTimeString('id-ID', { timeZone: 'Asia/Makassar' }) + ' WITA';
        if (configWebhookUrl) configWebhookUrl.textContent = 'Setel GEMINI_API_KEY di .env';
        if (configApiKey) configApiKey.textContent = 'Suricata Rules Aktif';
      }

      async function runTestConnection() {
        if (!btnTestConnection) return;
        btnTestConnection.disabled = true;
        testSpinner.classList.remove('hidden');
        testIcon.classList.add('hidden');
        testText.textContent = 'MENGUJI PROBE...';
        testAlertBox.classList.add('hidden');

        try {
          const res = await apiFetch('/admin/ai/test', { method: 'POST' });
          const data = res?.data || {};

          testAlertBox.classList.remove('hidden');

          if (data.status === 'Terhubung') {
            testAlertBox.className = 'p-4 rounded-xl text-xs space-y-1.5 bg-emerald-50 border border-emerald-200 text-emerald-800';
            testAlertHeading.textContent = 'Koneksi AI Berhasil Diverifikasi!';
            testAlertIcon.className = 'bi bi-check-circle-fill text-emerald-600 text-base';
            testAlertDesc.textContent = data.message || 'Probe Google Gemini AI merespons dengan sukses. Layanan AI siap digunakan secara penuh.';
          } else {
            testAlertBox.className = 'p-4 rounded-xl text-xs space-y-1.5 bg-amber-50 border border-amber-200 text-amber-800';
            testAlertHeading.textContent = `Status: ${data.status || 'Belum Terhubung'}`;
            testAlertIcon.className = 'bi bi-exclamation-triangle-fill text-amber-600 text-base';
            testAlertDesc.textContent = data.message || 'API Key belum disetel atau server AI belum merespons. Sistem saat ini berjalan dalam mode fallback heuristik lokal yang aman.';
          }

          if (data) renderStatus(data);
        } catch (err) {
          testAlertBox.classList.remove('hidden');
          testAlertBox.className = 'p-4 rounded-xl text-xs space-y-1.5 bg-rose-50 border border-rose-200 text-rose-800';
          testAlertHeading.textContent = 'Pengujian Gagal Dilakukan';
          testAlertIcon.className = 'bi bi-x-circle-fill text-rose-600 text-base';
          testAlertDesc.textContent = err.message || 'Gagal menghubungi endpoint backend. Pastikan server Laravel sedang berjalan.';
        } finally {
          btnTestConnection.disabled = false;
          testSpinner.classList.add('hidden');
          testIcon.classList.remove('hidden');
          testText.textContent = 'UJI KONEKSI SEKARANG';
        }
      }

      window.runProbeTest = function() {
        if (btnTestConnection) {
          btnTestConnection.scrollIntoView({ behavior: 'smooth', block: 'center' });
          runTestConnection();
        }
      };

      btnTestConnection?.addEventListener('click', runTestConnection);
      document.getElementById('btn-refresh-status')?.addEventListener('click', fetchAiStatus);

      // Inisialisasi awal
      fetchAiStatus();

      // Sinkronisasi badge laporan sidebar
      async function loadReportBadge() {
        try {
          const reports = await ApiService.getReports();
          const list = Array.isArray(reports) ? reports : (reports?.data || []);
          const pending = list.filter(r => r.status === 'Pending' || r.status === 'Masuk').length;
          const badge = document.getElementById('badge-sidebar-reports');
          if (badge) badge.textContent = pending;
        } catch {}
      }
      loadReportBadge();
    });
  </script>
</body>
</html>



