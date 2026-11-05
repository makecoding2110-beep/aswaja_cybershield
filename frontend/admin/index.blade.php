<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <script src="../../js/admin-guard.js"></script>
  <title>Dashboard Admin - Aswaja CyberShield</title>
  <link rel="icon" type="image/svg+xml" href="../../favicon.svg">

  <!-- Google Fonts: Inter & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  <!-- Tailwind CSS Play CDN & Config (Matches tailwind.config.js & frontend/) -->
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
              950: '#022c22'
            }
          },
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
            display: ['"Plus Jakarta Sans"', 'sans-serif']
          }
        }
      }
    }
  </script>
  <link rel="stylesheet" href="../../css/style.css">
</head>
<body class="h-full bg-slate-50 font-sans text-slate-800 antialiased flex flex-row overflow-x-hidden">

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

  <!-- Mobile Sidebar Overlay Backdrop -->
  <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity duration-300"></div>

  <!-- Sidebar Component (Kiri) -->
  <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:static lg:inset-auto lg:z-auto border-r border-slate-800 flex-shrink-0">
    <!-- Brand Header -->
    <div class="h-16 flex items-center justify-between px-5 border-b border-slate-800/80 bg-slate-950/40">
      <a href="index.blade.php" class="flex items-center gap-3 group text-decoration-none">
        <img src="../../logo-shield.png" alt="Aswaja CyberShield" class="w-9 h-9 rounded-xl object-contain ring-1 ring-emerald-500/30 shadow-md">
        <div class="min-w-0">
        <span class="font-display font-bold text-sm text-white tracking-tight block truncate">Aswaja CyberShield</span>
        <span class="text-[10px] text-emerald-400 font-semibold uppercase tracking-wider block">Admin Dashboard</span>
      </div>
      </a>
      <button id="close-sidebar-btn" type="button" class="lg:hidden text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition-colors" aria-label="Tutup sidebar">
        <i class="bi bi-x-lg text-lg"></i>
      </button>
    </div>

    <!-- Navigation Menu Items -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6" aria-label="Navigasi Utama Admin">
      <div>
        <p class="px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-2">Menu Utama</p>
        <div class="space-y-1">
          <!-- 1. Dashboard -->
          <a href="index.blade.php" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl bg-emerald-600 text-white shadow-md shadow-emerald-950/30 transition-all">
            <i class="bi bi-speedometer2 text-base text-white"></i>
            <span>Dashboard</span>
          </a>

          <!-- 2. Artikel -->
          <a href="artikel.blade.php" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/70 transition-all group">
            <i class="bi bi-file-earmark-text text-base text-slate-400 group-hover:text-emerald-400 transition-colors"></i>
            <span>Artikel</span>
          </a>

          <!-- 3. Riwayat Pelaporan -->
          <a href="laporan.blade.php" class="flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/70 transition-all group">
            <span class="flex items-center gap-3">
              <i class="bi bi-shield-exclamation text-base text-slate-400 group-hover:text-emerald-400 transition-colors"></i>
              <span>Laporan</span>
            </span>
            <span id="badge-sidebar-reports" class="bg-rose-500/20 text-rose-400 text-xs px-2 py-0.5 rounded-full font-bold border border-rose-500/30">0</span>
          </a>

          <!-- 4. Studi Kasus -->
          <a href="studi-kasus.blade.php" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/70 transition-all group">
            <i class="bi bi-journal-check text-base text-slate-400 group-hover:text-emerald-400 transition-colors"></i>
            <span>Studi Kasus</span>
          </a>
          <!-- 5. Integrasi Dual AI -->
          <a href="integrasi-ai.blade.php" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/70 transition-all group">
            <i class="bi bi-cpu text-base text-slate-400 group-hover:text-emerald-400 transition-colors"></i>
            <span>Integrasi Dual AI</span>
          </a>

          <!-- 6. Kelola Akun -->
          <a href="akun.blade.php" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/70 transition-all group">
            <i class="bi bi-people text-base text-slate-400 group-hover:text-emerald-400 transition-colors"></i>
            <span>Kelola Akun</span>
          </a>

        </div>
      </div>

    <!-- Sidebar Bottom / User Bar -->
    <div class="p-3 border-t border-slate-800/80 bg-slate-950/40">
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

  <!-- Main Content Wrapper (Tengah) -->
  <div class="flex-1 flex flex-col min-w-0 overflow-y-auto min-h-screen">

    <!-- Topbar Header (Atas) -->
    <header class="sticky top-0 z-30 h-16 bg-white/95 backdrop-blur-sm border-b border-slate-200/90 px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
      <!-- Left: Mobile Toggle & Status Pill -->
      <div class="flex items-center gap-3 min-w-0">
        <button id="toggle-sidebar-btn" type="button" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors" aria-label="Buka navigasi">
          <i class="bi bi-list text-2xl leading-none"></i>
        </button>
      </div>


      <!-- Right: Live Clock, Notifikasi, Profil Admin -->
      <div class="flex items-center gap-2 sm:gap-3">
        <!-- Live Clock Pill -->
        <div class="hidden xl:flex items-center gap-1.5 text-xs text-slate-600 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200 font-mono">
          <i class="bi bi-clock text-slate-400"></i>
          <span id="live-clock">--:--:-- WIB</span>
        </div>

        <!-- Refresh Button -->
        <button type="button" id="btn-refresh-dashboard" class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors" title="Segarkan Data">
          <i class="bi bi-arrow-clockwise text-base"></i>
        </button>

        <!-- Notification Bell Dropdown -->
        <div class="relative" id="notif-dropdown-wrapper">
          <button id="notif-btn" type="button" class="relative p-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors" aria-label="Notifikasi aduan">
            <i class="bi bi-bell text-lg"></i>
          </button>
          
          <div id="notif-menu" class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-slate-200 py-3 z-50 animate-in fade-in zoom-in-95">
            <div class="px-4 pb-2 border-b border-slate-100 flex items-center justify-between">
              <span class="font-display font-bold text-sm text-slate-800">Notifikasi Aduan</span>
              <span class="text-[11px] font-semibold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full border border-emerald-200">Real-time</span>
            </div>
            <div id="notif-list-container" class="divide-y divide-slate-100 max-h-72 overflow-y-auto">
              <!-- Dynamically populated -->
            </div>
            <div class="px-4 pt-2 border-t border-slate-100 text-center">
              <a href="laporan.blade.php" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat Semua Laporan &rarr;</a>
            </div>
          </div>
        </div>

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

          <!-- Dropdown Menu -->
          <div id="profile-menu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-200 py-2 z-50 divide-y divide-slate-100">
            <div class="px-4 py-2">
              <p class="text-xs text-slate-400">Masuk sebagai</p>
              <p id="dropdown-user-email" class="text-xs font-semibold text-slate-800 truncate">admin@aswajacyber.id</p>
            </div>
            <div class="py-1">
              <a href="akun.blade.php" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                <i class="bi bi-person-gear text-slate-400"></i>
                <span>Kelola Akun &amp; Profil</span>
              </a>
              <a href="laporan.blade.php" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                <i class="bi bi-inbox text-slate-400"></i>
                <span>Laporan</span>
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
      <div>
        <h1 class="text-2xl font-display font-extrabold text-slate-900 tracking-tight">Dashboard</h1>
        <p class="text-sm text-slate-500 mt-0.5">Ringkasan aktivitas platform: aduan tautan investasi judol, artikel literasi, dan studi kasus.</p>
      </div>

      <!-- 4 Stat Cards di Bagian Atas (Spesifikasi Bagian 2) -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        
        <!-- 1. Total Artikel -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-200 group">
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Artikel</span>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
              <i class="bi bi-file-earmark-text text-xl"></i>
            </div>
          </div>
          <div class="flex items-baseline gap-2">
            <span class="text-3xl font-display font-extrabold text-slate-900" id="stat-articles">0</span>
            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">Edukasi</span>
          </div>
          <p class="text-xs text-slate-400 mt-2">Materi literasi siber publik &amp; fiqih</p>
        </div>

        <!-- 2. Laporan Masuk -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-200 group">
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Laporan Masuk</span>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
              <i class="bi bi-shield-exclamation text-xl"></i>
            </div>
          </div>
          <div class="flex items-baseline gap-2">
            <span class="text-3xl font-display font-extrabold text-amber-600" id="stat-reports-in">0</span>
            <span class="text-xs font-medium text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">Pending</span>
          </div>
          <p class="text-xs text-slate-400 mt-2">Menunggu investigasi tim analis</p>
        </div>

        <!-- 3. Akun Aktif -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-200 group">
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Akun Aktif</span>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
              <i class="bi bi-people text-xl"></i>
            </div>
          </div>
          <div class="flex items-baseline gap-2">
            <span class="text-3xl font-display font-extrabold text-slate-900" id="stat-accounts">0</span>
            <span class="text-xs font-medium text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">Pengelola</span>
          </div>
          <p class="text-xs text-slate-400 mt-2">Super Admin &amp; Analis Tim Siber</p>
        </div>

        <!-- 4. Studi Kasus -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-200 group">
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Studi Kasus</span>
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
              <i class="bi bi-patch-question text-xl"></i>
            </div>
          </div>
          <div class="flex items-baseline gap-2">
            <span class="text-3xl font-display font-extrabold text-slate-900" id="stat-case-studies">0</span>
            <span class="text-xs font-medium text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full border border-purple-200">Skenario</span>
          </div>
          <p class="text-xs text-slate-400 mt-2">Simulasi interaktif Cyber Guard</p>
        </div>

      </div>

      <!-- Main Grid: Tabel Laporan Terbaru (8 cols) & Widget Aktivitas/Pintasan (4 cols) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Kolom Kiri: Tabel Laporan Terbaru (5 data terakhir) -->
        <div class="lg:col-span-8 space-y-6">
          <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <!-- Header Card Tabel -->
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
              <div>
                <h2 class="font-display font-bold text-base text-slate-900 flex items-center gap-2">
                  <i class="bi bi-shield-lock-fill text-emerald-600"></i>
                  <span>Laporan Tautan Judol &amp; Investasi Terbaru</span>
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">5 aduan masyarakat terkini dalam antrean penanganan sistem</p>
              </div>
              <a href="laporan.blade.php" class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">
                <span>Kelola Semua</span>
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>

            <!-- Responsive Table Container -->
            <div class="overflow-x-auto">
              <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-[11px] font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                  <tr>
                    <th class="px-5 py-3">ID &amp; Tanggal</th>
                    <th class="px-4 py-3">URL yang Dilaporkan</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                  </tr>
                </thead>
                <tbody id="dashboard-reports-body" class="divide-y divide-slate-100">
                  <tr>
                    <td colspan="5" class="text-center py-8 text-slate-400 text-xs">
                      <div class="inline-block w-5 h-5 border-2 border-emerald-500 border-t-transparent rounded-full animate-spin mb-2"></div>
                      <p>Memuat laporan terbaru...</p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Footer Card -->
            <div class="p-4 bg-slate-50/75 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
              <span id="reports-summary-text">Menampilkan 5 aduan terakhir.</span>
              <a href="laporan.blade.php" class="font-semibold text-emerald-600 hover:text-emerald-700">Lihat Semua Laporan &rarr;</a>
            </div>
          </div>

        </div>

        <!-- Kolom Kanan: Widget Ringkasan Statistik & Aktivitas Sistem (4 cols) -->
        <div class="lg:col-span-4 space-y-6">

          <!-- Widget 1: Ringkasan Status -->
          <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
              <h3 class="font-display font-bold text-sm text-slate-900 flex items-center gap-2">
                <i class="bi bi-pie-chart text-emerald-600"></i>
                <span>Status Laporan</span>
              </h3>
              <span class="text-[11px] font-mono text-slate-400" id="stat-total-reports-label">0 Total</span>
            </div>

            <!-- Progress Bars Breakdown -->
            <div class="space-y-3.5">
              <div>
                <div class="flex justify-between text-xs mb-1">
                  <span class="font-medium text-slate-700 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Selesai
                  </span>
                  <span class="font-bold text-slate-800" id="rate-done-text">0%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                  <div id="bar-reports-done" class="bg-emerald-500 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
              </div>

              <div>
                <div class="flex justify-between text-xs mb-1">
                  <span class="font-medium text-slate-700 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    Diproses
                  </span>
                  <span class="font-bold text-slate-800" id="rate-process-text">0%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                  <div id="bar-reports-process" class="bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
              </div>

              <div>
                <div class="flex justify-between text-xs mb-1">
                  <span class="font-medium text-slate-700 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    Pending
                  </span>
                  <span class="font-bold text-slate-800" id="rate-in-text">0%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                  <div id="bar-reports-in" class="bg-amber-500 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Widget 2: Aktivitas Sistem Terbaru -->
          <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
              <h3 class="font-display font-bold text-sm text-slate-900 flex items-center gap-2">
                <i class="bi bi-clock-history text-emerald-600"></i>
                <span>Aktivitas Sistem Terbaru</span>
              </h3>
              <span class="text-[10px] font-semibold bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">Audit Log</span>
            </div>

            <!-- Feed Timeline -->
            <div class="space-y-4 relative before:absolute before:left-3.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200" id="recent-activity-feed">
              <!-- Populated by JS -->
            </div>
          </div>

          <!-- Widget 3: Pintasan Cepat Admin -->
          <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
            <h3 class="font-display font-bold text-sm text-slate-900 mb-3 flex items-center gap-2">
              <i class="bi bi-grid-fill text-slate-700"></i>
              <span>Pintasan Cepat Admin</span>
            </h3>
            <div class="grid grid-cols-1 gap-2">
              <a href="artikel.blade.php" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 hover:border-emerald-400 hover:bg-emerald-50/40 text-slate-700 hover:text-emerald-800 text-xs font-semibold transition-all group">
                <span class="flex items-center gap-2.5">
                  <i class="bi bi-plus-circle text-emerald-600 text-sm"></i>
                  <span>Tulis Artikel Edukasi Baru</span>
                </span>
                <i class="bi bi-chevron-right text-slate-400 group-hover:translate-x-0.5 transition-transform"></i>
              </a>

              <a href="laporan.blade.php" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 hover:border-amber-400 hover:bg-amber-50/40 text-slate-700 hover:text-amber-800 text-xs font-semibold transition-all group">
                <span class="flex items-center gap-2.5">
                  <i class="bi bi-search text-amber-600 text-sm"></i>
                  <span>Verifikasi Aduan Siber</span>
                </span>
                <i class="bi bi-chevron-right text-slate-400 group-hover:translate-x-0.5 transition-transform"></i>
              </a>

              <a href="studi-kasus.blade.php" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 hover:border-purple-400 hover:bg-purple-50/40 text-slate-700 hover:text-purple-800 text-xs font-semibold transition-all group">
                <span class="flex items-center gap-2.5">
                  <i class="bi bi-journal-plus text-purple-600 text-sm"></i>
                  <span>Tambah Soal Kasus Siber</span>
                </span>
                <i class="bi bi-chevron-right text-slate-400 group-hover:translate-x-0.5 transition-transform"></i>
              </a>

              <a href="akun.blade.php" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 hover:border-blue-400 hover:bg-blue-50/40 text-slate-700 hover:text-blue-800 text-xs font-semibold transition-all group">
                <span class="flex items-center gap-2.5">
                  <i class="bi bi-person-gear text-blue-600 text-sm"></i>
                  <span>Kelola Akun Staf Admin</span>
                </span>
                <i class="bi bi-chevron-right text-slate-400 group-hover:translate-x-0.5 transition-transform"></i>
              </a>
            </div>
          </div>

        </div>

      </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/90 py-4 px-6 text-center text-xs text-slate-500 mt-auto">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-2 max-w-7xl mx-auto">
        <p>&copy; 2026 <strong class="font-semibold text-slate-700">Aswaja CyberShield</strong> &bull; Dashboard Administrator</p>
        <p class="text-slate-400">Laravel-Ready Semantic Markup &bull; UNU Kaltim Keamanan Siber</p>
      </div>
    </footer>
  </div>

  <!-- Modal Detail Cepat Laporan (Dashboard Preview) -->
  <div id="modal-dashboard-report" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="min-h-full flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-4">
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <h4 class="font-display font-bold text-base text-slate-900 flex items-center gap-2">
          <i class="bi bi-shield-exclamation text-rose-600"></i>
          <span>Detail Aduan Tautan</span>
        </h4>
        <button type="button" onclick="closeDashboardModal()" class="text-slate-400 hover:text-slate-700 p-1 rounded-lg">
          <i class="bi bi-x-lg text-lg"></i>
        </button>
      </div>

      <div class="space-y-3 text-xs">
        <div>
          <span class="text-slate-400 block mb-0.5">ID Tiket:</span>
          <span id="modal-d-id" class="font-mono font-bold text-slate-800 text-sm"></span>
        </div>
        <div>
          <span class="text-slate-400 block mb-0.5">Tautan Terindikasi:</span>
          <p id="modal-d-url" class="font-mono text-rose-600 bg-rose-50 p-2.5 rounded-xl border border-rose-200 break-all select-all font-semibold"></p>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <span class="text-slate-400 block mb-0.5">Kategori:</span>
            <span id="modal-d-cat" class="font-semibold text-slate-700"></span>
          </div>
          <div>
            <span class="text-slate-400 block mb-0.5">Status Saat Ini:</span>
            <span id="modal-d-status"></span>
          </div>
        </div>
        <div>
          <span class="text-slate-400 block mb-0.5">Deskripsi / Modus yang Dilaporkan:</span>
          <p id="modal-d-desc" class="text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-200 leading-relaxed"></p>
        </div>
      </div>

      <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
        <button type="button" onclick="closeDashboardModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl">
          Tutup
        </button>
        <a href="laporan.blade.php" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-sm">
          Buka di Laporan &rarr;
        </a>
      </div>
    </div>
    </div>
  </div>

  <!-- API Layer & Scripts -->
  <script src="../../js/api.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // 1. Auth Guard
      if (!window.AuthService || !window.AuthService.isAuthenticated()) {
        if (window.adminLogoutRedirect) window.adminLogoutRedirect();
        else window.location.replace('/login');
        return;
      }

      const currentUser = window.AuthService.getUser() || { name: 'Admin Utama', role: 'Super Admin' };
      const isSuper = window.AuthService.isSuperAdmin();

      // Set user info
      const initial = (currentUser.name || 'A').charAt(0).toUpperCase();
      document.getElementById('sidebar-user-name').textContent = currentUser.name || 'Admin Utama';
      document.getElementById('sidebar-user-role').textContent = isSuper ? 'Super Admin' : 'Admin Basic';
      document.getElementById('sidebar-avatar').textContent = initial;

      document.getElementById('topbar-user-name').textContent = currentUser.name || 'Admin Utama';
      document.getElementById('topbar-user-role').textContent = isSuper ? 'Super Admin' : 'Admin Basic';
      document.getElementById('topbar-avatar').textContent = initial;
      document.getElementById('dropdown-user-email').textContent = currentUser.email || currentUser.username || 'admin@aswajacyber.id';

      // Logout handlers
      function handleLogout() {
        if (confirm('Keluar dari sesi administrator Aswaja CyberShield?')) {
          window.AuthService.logout();
        }
      }
      document.getElementById('sidebar-btn-logout')?.addEventListener('click', handleLogout);
      document.getElementById('topbar-btn-logout')?.addEventListener('click', handleLogout);

      // Live Clock
      function updateClock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', timeZone: 'Asia/Jakarta' });
        const el = document.getElementById('live-clock');
        if (el) el.textContent = `${timeStr} WIB`;
      }
      updateClock();
      setInterval(updateClock, 1000);

      // Mobile Sidebar Toggle
      const sidebar = document.getElementById('sidebar');
      const backdrop = document.getElementById('sidebar-backdrop');
      const toggleBtn = document.getElementById('toggle-sidebar-btn');
      const closeBtn = document.getElementById('close-sidebar-btn');

      function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('hidden');
      }
      function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
      }

      toggleBtn?.addEventListener('click', openSidebar);
      closeBtn?.addEventListener('click', closeSidebar);
      backdrop?.addEventListener('click', closeSidebar);

      // Topbar Dropdowns (Profile & Notif)
      const profileBtn = document.getElementById('profile-btn');
      const profileMenu = document.getElementById('profile-menu');
      const notifBtn = document.getElementById('notif-btn');
      const notifMenu = document.getElementById('notif-menu');

      profileBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        profileMenu.classList.toggle('hidden');
        notifMenu.classList.add('hidden');
      });

      notifBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        notifMenu.classList.toggle('hidden');
        profileMenu.classList.add('hidden');
      });

      document.addEventListener('click', () => {
        profileMenu?.classList.add('hidden');
        notifMenu?.classList.add('hidden');
      });

      // 2. Load Dashboard Data
      let currentReports = [];

      async function loadDashboardData() {
        try {
          const [reportsRes, articlesRes, casesRes, adminsRes] = await Promise.all([
            window.ApiService.getReports(),
            window.ApiService.getArticles(),
            window.ApiService.getCaseStudies(),
            window.ApiService.getAdmins(),
          ]);

          const reports = Array.isArray(reportsRes) ? reportsRes : (reportsRes?.data || []);
          const articles = Array.isArray(articlesRes) ? articlesRes : (articlesRes?.data || []);
          const caseStudies = Array.isArray(casesRes) ? casesRes : (casesRes?.data || []);
          const admins = Array.isArray(adminsRes) ? adminsRes : (adminsRes?.data || []);

          currentReports = reports;

          // Compute Counts
          const totalReports = reports.length;
          const reportsIn = reports.filter(r => r.status === 'Masuk' || r.status === 'Pending').length;
          const reportsProcess = reports.filter(r => r.status === 'Diproses').length;
          const reportsDone = reports.filter(r => r.status === 'Selesai').length;

          // Update 4 Stat Cards
          document.getElementById('stat-articles').textContent = articles.length;
          document.getElementById('stat-reports-in').textContent = reportsIn;
          document.getElementById('stat-accounts').textContent = admins.length;
          document.getElementById('stat-case-studies').textContent = caseStudies.length;
          document.getElementById('badge-sidebar-reports').textContent = reportsIn;

          // Update Status Breakdown Widget
          document.getElementById('stat-total-reports-label').textContent = `${totalReports} Total Aduan`;
          const pDone = totalReports ? Math.round((reportsDone / totalReports) * 100) : 0;
          const pProc = totalReports ? Math.round((reportsProcess / totalReports) * 100) : 0;
          const pIn = totalReports ? Math.round((reportsIn / totalReports) * 100) : 0;

          document.getElementById('rate-done-text').textContent = `${pDone}% (${reportsDone})`;
          document.getElementById('rate-process-text').textContent = `${pProc}% (${reportsProcess})`;
          document.getElementById('rate-in-text').textContent = `${pIn}% (${reportsIn})`;

          document.getElementById('bar-reports-done').style.width = `${pDone}%`;
          document.getElementById('bar-reports-process').style.width = `${pProc}%`;
          document.getElementById('bar-reports-in').style.width = `${pIn}%`;

          // Render Recent Reports Table (5 items)
          renderRecentReportsTable(reports.slice(0, 5));

          // Render Notification Dropdown List
          renderNotificationList(reports.slice(0, 4));

          // Render Activity Feed
          renderRecentActivity(reports, articles);

        } catch (err) {
          console.error('Error load dashboard data:', err);
        }
      }

      function renderRecentReportsTable(reports) {
        const tbody = document.getElementById('dashboard-reports-body');
        if (!reports || reports.length === 0) {
          tbody.innerHTML = `
            <tr>
              <td colspan="5" class="text-center py-6 text-slate-400 text-xs">Belum ada laporan dalam sistem.</td>
            </tr>
          `;
          return;
        }

        tbody.innerHTML = reports.map(r => {
          const isPending = r.status === 'Pending' || r.status === 'Masuk';
          const isProcess = r.status === 'Diproses';
          const isDone = r.status === 'Selesai';

          let statusBadge = '';
          if (isPending) {
            statusBadge = `<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>Pending</span>`;
          } else if (isProcess) {
            statusBadge = `<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>Diproses</span>`;
          } else {
            statusBadge = `<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Selesai</span>`;
          }

          const rawUrl = r.url || r.target || '-';
          const safeUrl = escapeHtml(rawUrl);
          const truncUrl = safeUrl.length > 34 ? safeUrl.substring(0, 34) + '...' : safeUrl;

          return `
            <tr class="hover:bg-slate-50/75 transition-colors">
              <td class="px-5 py-3.5 whitespace-nowrap">
                <div class="font-mono font-bold text-xs text-slate-800">${escapeHtml(r.id)}</div>
                <div class="text-[11px] text-slate-400">${escapeHtml(r.date || '-')}</div>
              </td>
              <td class="px-4 py-3.5">
                <div class="font-mono text-xs font-semibold text-rose-600 truncate max-w-xs" title="${safeUrl}">
                  ${truncUrl}
                </div>
                <div class="text-[11px] text-slate-400 truncate max-w-xs">${escapeHtml(r.reporter || r.reporter_name || 'Warga Anonim')}</div>
              </td>
              <td class="px-4 py-3.5 whitespace-nowrap">
                <span class="inline-block text-[11px] font-medium bg-slate-100 text-slate-700 px-2.5 py-0.5 rounded-lg border border-slate-200 truncate max-w-[150px]">
                  ${escapeHtml(r.category || 'Insiden Siber')}
                </span>
              </td>
              <td class="px-4 py-3.5 whitespace-nowrap">
                ${statusBadge}
              </td>
              <td class="px-5 py-3.5 whitespace-nowrap text-right">
                <button type="button" onclick="showDashboardReportDetail('${r.id}')" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-100 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 border border-slate-200 hover:border-emerald-200 transition-colors">
                  <i class="bi bi-eye"></i>
                  <span>Detail</span>
                </button>
              </td>
            </tr>
          `;
        }).join('');
      }

      function renderNotificationList(reports) {
        const container = document.getElementById('notif-list-container');
        if (!reports || reports.length === 0) {
          container.innerHTML = '<div class="p-4 text-center text-xs text-slate-400">Tidak ada notifikasi baru.</div>';
          return;
        }

        container.innerHTML = reports.map(r => `
          <a href="laporan.blade.php" class="block p-3 hover:bg-slate-50 transition-colors">
            <div class="flex items-center justify-between mb-1">
              <span class="font-mono font-bold text-[11px] text-slate-800">${escapeHtml(r.id)}</span>
              <span class="text-[10px] text-slate-400">${escapeHtml(r.date || 'Hari ini')}</span>
            </div>
            <p class="text-xs text-slate-600 font-medium truncate">${escapeHtml(r.url || r.target || 'Laporan baru')}</p>
            <span class="inline-block text-[10px] text-emerald-600 mt-1">${escapeHtml(r.category || 'Insiden')}</span>
          </a>
        `).join('');
      }

      function renderRecentActivity(reports, articles) {
        const feed = document.getElementById('recent-activity-feed');
        const activities = [
          {
            icon: 'bi-inbox-fill text-amber-500 bg-amber-50',
            title: `Laporan Aduan #${reports[0]?.id || 'LPR081'}`,
            desc: `Tautan judol dilaporkan oleh warga. Status: ${reports[0]?.status || 'Pending'}`,
            time: '10 menit lalu'
          },
          {
            icon: 'bi-shield-check text-emerald-500 bg-emerald-50',
            title: 'Verifikasi Domain Phishing',
            desc: 'Tim analis mencatat rekomendasi pemblokiran ke AduanKonten Kominfo.',
            time: '1 jam lalu'
          },
          {
            icon: 'bi-file-text-fill text-blue-500 bg-blue-50',
            title: 'Artikel Literasi Diterbitkan',
            desc: `"${articles[0]?.title || 'Muamalah Finansial'}" telah tayang di portal publik.`,
            time: '3 jam lalu'
          },
          {
            icon: 'bi-person-check-fill text-purple-500 bg-purple-50',
            title: 'Audit Hak Akses Staf',
            desc: 'Sesi login Super Admin aktif dengan token otentikasi terverifikasi.',
            time: 'Hari ini'
          }
        ];

        feed.innerHTML = activities.map(act => `
          <div class="relative flex items-start gap-3 pl-1">
            <div class="w-7 h-7 rounded-xl flex items-center justify-center text-xs flex-shrink-0 z-10 ${act.icon}">
              <i class="bi ${act.icon.split(' ')[0]}"></i>
            </div>
            <div class="min-w-0 flex-1 pt-0.5">
              <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-800 leading-tight">${act.title}</p>
                <span class="text-[10px] text-slate-400 font-mono">${act.time}</span>
              </div>
              <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">${act.desc}</p>
            </div>
          </div>
        `).join('');
      }

      // Modal Helper
      window.showDashboardReportDetail = function(id) {
        const item = currentReports.find(r => String(r.id) === String(id));
        if (!item) return;

        document.getElementById('modal-d-id').textContent = item.id;
        document.getElementById('modal-d-url').textContent = item.url || item.target || '-';
        document.getElementById('modal-d-cat').textContent = item.category || '-';
        document.getElementById('modal-d-desc').textContent = item.description || 'Tidak ada deskripsi rinci.';

        const isPending = item.status === 'Pending' || item.status === 'Masuk';
        const isProcess = item.status === 'Diproses';
        let badgeHtml = '';
        if (isPending) {
          badgeHtml = '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">Pending</span>';
        } else if (isProcess) {
          badgeHtml = '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">Diproses</span>';
        } else {
          badgeHtml = '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Selesai</span>';
        }
        document.getElementById('modal-d-status').innerHTML = badgeHtml;

        document.getElementById('modal-dashboard-report').classList.remove('hidden');
      };

      window.closeDashboardModal = function() {
        document.getElementById('modal-dashboard-report').classList.add('hidden');
      };

      // Global Search Handler
      window.handleGlobalSearch = function() {
        const input = document.getElementById('global-search-input');
        const q = input ? input.value.trim() : '';
        if (q) {
          window.location.href = `laporan.blade.php?q=${encodeURIComponent(q)}`;
        }
      };

      function escapeHtml(str) {
        return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
      }

      document.getElementById('btn-refresh-dashboard')?.addEventListener('click', loadDashboardData);

      loadDashboardData();
    });
  </script>
</body>
</html>
