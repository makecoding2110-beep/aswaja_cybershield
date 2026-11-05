<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <script src="../../js/admin-guard.js"></script>
  <title>Laporan Masuk - Aswaja CyberShield Admin</title>
  <link rel="icon" type="image/svg+xml" href="../../favicon.svg">

  <!-- Google Fonts: Inter & Plus Jakarta Sans. -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap"
    rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  <!-- Tailwind CSS Play CDN & Config -->
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
  <div id="sidebar-backdrop"
    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity duration-300"></div>

  <!-- Sidebar Component (Kiri) -->
  <aside id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:static lg:inset-auto lg:z-auto border-r border-slate-800 flex-shrink-0">
    <!-- Brand Header -->
    <div class="h-16 flex items-center justify-between px-5 border-b border-slate-800/80 bg-slate-950/40">
      <a href="index.blade.php" class="flex items-center gap-3 group text-decoration-none">
        <img src="../../logo-shield.png" alt="Aswaja CyberShield"
          class="w-9 h-9 rounded-xl object-contain ring-1 ring-emerald-500/30 shadow-md">
        <div class="min-w-0">
          <span class="font-display font-bold text-sm text-white tracking-tight block truncate">Aswaja CyberShield</span>
          <span class="text-[10px] text-emerald-400 font-semibold uppercase tracking-wider block">Admin Dashboard</span>
      </div>
      </a>
      <button id="close-sidebar-btn" type="button"
        class="lg:hidden text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition-colors"
        aria-label="Tutup sidebar">
        <i class="bi bi-x-lg text-lg"></i>
      </button>
    </div>

    <!-- Navigation Menu Items -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6" aria-label="Navigasi Utama Admin">
      <div>
        <p class="px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-2">Menu Utama</p>
        <div class="space-y-1">
          <!-- 1. Dashboard -->
          <a href="index.blade.php"
            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/70 transition-all group">
            <i class="bi bi-speedometer2 text-base text-slate-400 group-hover:text-emerald-400 transition-colors"></i>
            <span>Dashboard</span>
          </a>

          <!-- 2. Artikel -->
          <a href="artikel.blade.php"
            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/70 transition-all group">
            <i
              class="bi bi-file-earmark-text text-base text-slate-400 group-hover:text-emerald-400 transition-colors"></i>
            <span>Artikel</span>
          </a>

          <!-- 3. Riwayat Pelaporan (Active) -->
          <a href="laporan.blade.php"
            class="flex items-center justify-between px-3 py-2.5 text-sm font-semibold rounded-xl bg-emerald-600 text-white shadow-md shadow-emerald-950/30 transition-all">
            <span class="flex items-center gap-3">
              <i class="bi bi-shield-exclamation text-base text-white"></i>
              <span>Laporan</span>
            </span>
            <span id="badge-sidebar-reports"
              class="bg-white/20 text-white text-xs px-2 py-0.5 rounded-full font-bold">0</span>
          </a>

          <!-- 4. Studi Kasus -->
          <a href="studi-kasus.blade.php"
            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/70 transition-all group">
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
          <div id="sidebar-avatar"
            class="w-8 h-8 rounded-lg bg-emerald-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-sm">
            A
          </div>
          <div class="min-w-0 flex-1">
            <p id="sidebar-user-name" class="text-xs font-semibold text-white truncate">Admin Utama</p>
            <p id="sidebar-user-role" class="text-[10px] text-emerald-400 truncate font-mono">Super Admin</p>
          </div>
        </div>
        <button id="sidebar-btn-logout" type="button"
          class="text-slate-400 hover:text-rose-400 p-1.5 rounded-lg hover:bg-slate-700/60 transition-colors"
          title="Keluar">
          <i class="bi bi-box-arrow-right"></i>
        </button>
      </div>
    </div>
  </aside>

  <!-- Main Content Wrapper (Tengah) -->
  <div class="flex-1 flex flex-col min-w-0 overflow-y-auto min-h-screen">

    <!-- Topbar Header (Atas) -->
    <header
      class="sticky top-0 z-30 h-16 bg-white/95 backdrop-blur-sm border-b border-slate-200/90 px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
      <!-- Left: Mobile Toggle & Breadcrumb -->
      <div class="flex items-center gap-3 min-w-0">
        <button id="toggle-sidebar-btn" type="button"
          class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors"
          aria-label="Buka navigasi">
          <i class="bi bi-list text-2xl leading-none"></i>
        </button>
      </div>

      <!-- Center: Global Search Bar -->
      <form id="global-search-form" class="relative max-w-md w-full hidden md:block" role="search"
        onsubmit="event.preventDefault(); handleGlobalSearch();">
        <div class="relative">
          <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
          <input type="search" name="q" id="global-search-input" placeholder="Cari di semua modul..."
            class="w-full pl-9 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-slate-800 placeholder-slate-400">
        </div>
      </form>

      <!-- Right: Clock, Notif, Profil Admin -->
      <div class="flex items-center gap-2 sm:gap-3">
        <!-- Live Clock Pill -->
        <div
          class="hidden xl:flex items-center gap-1.5 text-xs text-slate-600 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200 font-mono">
          <i class="bi bi-clock text-slate-400"></i>
          <span id="live-clock">--:--:-- WIB</span>
        </div>

        <button type="button" id="btn-refresh-reports"
          class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors"
          title="Segarkan Data">
          <i class="bi bi-arrow-clockwise text-base"></i>
        </button>

        <!-- Notification Bell Dropdown -->
        <div class="relative" id="notif-dropdown-wrapper">
          <button id="notif-btn" type="button"
            class="relative p-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors"
            aria-label="Notifikasi aduan">
            <i class="bi bi-bell text-lg"></i>
          </button>

          <div id="notif-menu"
            class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-slate-200 py-3 z-50 animate-in fade-in zoom-in-95">
            <div class="px-4 pb-2 border-b border-slate-100 flex items-center justify-between">
              <span class="font-display font-bold text-sm text-slate-800">Notifikasi Aduan</span>
              <span
                class="text-[11px] font-semibold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full border border-emerald-200">Real-time</span>
            </div>
            <div id="notif-list-container" class="divide-y divide-slate-100 max-h-72 overflow-y-auto"></div>
          </div>
        </div>

        <!-- Profil Admin Dropdown -->
        <div class="relative" id="profile-dropdown-wrapper">
          <button id="profile-btn" type="button"
            class="flex items-center gap-2.5 p-1 sm:px-2.5 sm:py-1.5 rounded-xl hover:bg-slate-100 transition-colors">
            <div id="topbar-avatar"
              class="w-8 h-8 rounded-xl bg-gradient-to-tr from-emerald-700 to-emerald-500 text-white font-bold text-xs flex items-center justify-center shadow-sm">
              A
            </div>
            <div class="hidden sm:block text-left">
              <p id="topbar-user-name" class="text-xs font-bold text-slate-800 leading-tight">Admin Utama</p>
              <p id="topbar-user-role" class="text-[10px] text-emerald-600 font-medium leading-tight">Super Admin</p>
            </div>
            <i class="bi bi-chevron-down text-xs text-slate-400 hidden sm:block"></i>
          </button>

          <!-- Dropdown Menu -->
          <div id="profile-menu"
            class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-200 py-2 z-50 divide-y divide-slate-100">
            <div class="px-4 py-2">
              <p class="text-xs text-slate-400">Masuk sebagai</p>
              <p id="dropdown-user-email" class="text-xs font-semibold text-slate-800 truncate">admin@aswajacyber.id</p>
            </div>
            <div class="py-1">
              <a href="akun.blade.php"
                class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                <i class="bi bi-person-gear text-slate-400"></i>
                <span>Kelola Akun &amp; Profil</span>
              </a>
            </div>
            <div class="pt-1">
              <button type="button" id="topbar-btn-logout"
                class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar (Logout)</span>
              </button>
            </div>
          </div>
        </div>

      </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-6">

      <!-- Breadcrumbs & Heading -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
          <h1 class="text-2xl font-display font-extrabold text-slate-900 tracking-tight">Riwayat Pelaporan
          </h1>
          <p class="text-sm text-slate-500 mt-0.5">Investigasi aduan masyarakat terkait tautan judi online,
            investasi bodong, dan jebakan finansial digital.</p>
        </div>
        <div class="flex items-center gap-3">
          <div id="role-badge-container">
            <!-- Populated by JS -->
          </div>
          <a href="../lapor.blade.php" target="_blank"
            class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-sm shadow-emerald-600/20 transition-all">
            <i class="bi bi-inbox"></i>
            <span>Aduan Baru</span>
          </a>
        </div>
      </div>

      <!-- Filter Berdasarkan Status (Semua, Pending, Proses, Selesai) & Search Bar -->
      <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
        <form id="filter-report-form"
          class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3"
          onsubmit="event.preventDefault(); applyFilter();">

          <!-- Search Bar Khusus Laporan -->
          <div class="relative flex-1 max-w-md">
            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="search" name="q_laporan" id="search-report"
              placeholder="Cari ID tiket, URL domain, pelapor, atau deskripsi..."
              class="w-full pl-9 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-slate-800 placeholder-slate-400">
          </div>

          <!-- Filter Toolbar -->
          <div class="flex flex-wrap items-center gap-2">
            <!-- Filter Status (Sesuai Spesifikasi: Semua, Pending, Proses, Selesai) -->
            <div class="flex items-center bg-slate-100 p-1 rounded-xl gap-1" id="status-button-group">
              <button type="button" onclick="setStatusFilter('')"
                class="status-btn active px-3 py-1.5 text-xs font-semibold rounded-lg bg-white text-slate-900 shadow-sm transition-all"
                data-status="">
                Semua
              </button>
              <button type="button" onclick="setStatusFilter('Pending')"
                class="status-btn px-3 py-1.5 text-xs font-semibold rounded-lg text-slate-600 hover:text-slate-900 transition-all"
                data-status="Pending">
                Pending
              </button>
              <button type="button" onclick="setStatusFilter('Proses')"
                class="status-btn px-3 py-1.5 text-xs font-semibold rounded-lg text-slate-600 hover:text-slate-900 transition-all"
                data-status="Proses">
                Proses
              </button>
              <button type="button" onclick="setStatusFilter('Selesai')"
                class="status-btn px-3 py-1.5 text-xs font-semibold rounded-lg text-slate-600 hover:text-slate-900 transition-all"
                data-status="Selesai">
                Selesai
              </button>
            </div>

            <!-- Filter Kategori Insiden Dropdown -->
            <select name="category" id="filter-category"
              class="px-3 py-2 text-xs font-medium bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-700">
              <option value="">Semua Kategori</option>
              <option value="Trading">Investasi Berkedok Trading</option>
              <option value="Cuan">Iklan Cuan Kilat</option>
              <option value="Slot">Slot / Kasino Berkedok Game</option>
              <option value="Arisan">Grup Arisan / Investasi</option>
            </select>

            <button type="button" id="btn-reset-filter"
              class="px-3 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
              Reset
            </button>
          </div>
        </form>
      </div>

      <!-- Data Table Laporan Cyber / Insiden -->
      <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-sm text-slate-600">
            <thead
              class="bg-slate-50 text-[11px] font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
              <tr>
                <th class="px-5 py-3.5">ID Laporan</th>
                <th class="px-4 py-3.5">Pelapor</th>
                <th class="px-4 py-3.5">Kategori Insiden</th>
                <th class="px-4 py-3.5">Tanggal Masuk</th>
                <th class="px-4 py-3.5">Status</th>
                <th class="px-5 py-3.5 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody id="reports-table-body" class="divide-y divide-slate-100">
              <tr>
                <td colspan="6" class="text-center py-8 text-slate-400 text-xs">
                  <div
                    class="inline-block w-5 h-5 border-2 border-emerald-500 border-t-transparent rounded-full animate-spin mb-2">
                  </div>
                  <p>Memuat riwayat laporan...</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination & Summary -->
        <div
          class="p-4 bg-slate-50/75 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
          <div id="reports-counter-info">Menampilkan 0 laporan aduan</div>
          <div class="flex items-center gap-1.5" id="reports-pagination-controls"></div>
        </div>
      </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/90 py-4 px-6 text-center text-xs text-slate-500 mt-auto">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-2 max-w-7xl mx-auto">
        <p>&copy; 2026 <strong class="font-semibold text-slate-700">Aswaja CyberShield</strong> &bull; Pengawasan Siber
          &amp; Pengawasan Konten</p>
        <p class="text-slate-400">Modul Verifikasi Laporan &bull; Komdigi / AduanKonten Ready</p>
      </div>
    </footer>
  </div>

  <!-- Modal Draf Ringkas untuk Melihat Detail & Ubah Status Laporan -->
  <div id="modal-report-action"
    class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="min-h-full flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl border border-slate-200 my-8">

      <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
        <h4 class="font-display font-bold text-base text-slate-900 flex items-center gap-2">
          <i class="bi bi-shield-check text-emerald-600"></i>
          <span>Detail &amp; Tindak Lanjut Laporan</span>
        </h4>
        <button type="button" onclick="closeActionModal()" class="text-slate-400 hover:text-slate-700 p-1 rounded-lg">
          <i class="bi bi-x-lg text-lg"></i>
        </button>
      </div>

      <!-- Semantic Form with Clear Name Attributes for Laravel -->
      <form id="form-report-action" method="POST" action="/admin/reports" onsubmit="handleSaveReportAction(event)">
        <!-- CSRF & Method Tokens -->
        <input type="hidden" name="_token" value="csrf_token_placeholder">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="report_id" id="modal-report-id">

        <!-- Draf Ringkas Informasi Laporan -->
        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80 mb-4 space-y-2.5 text-xs">
          <div class="flex items-center justify-between border-b border-slate-200/60 pb-2">
            <div>
              <span class="text-slate-400 block text-[10px] uppercase font-semibold">ID Tiket Laporan:</span>
              <span id="detail-ticket-id" class="font-mono font-bold text-slate-900 text-sm"></span>
            </div>
            <div id="detail-status-pill"></div>
          </div>

          <div>
            <span class="text-slate-400 block text-[10px] uppercase font-semibold mb-1">URL Target yang
              Dilaporkan:</span>
            <div class="flex items-center gap-2 p-2.5 bg-rose-50 border border-rose-200 rounded-xl">
              <i class="bi bi-exclamation-triangle-fill text-rose-500 text-sm flex-shrink-0"></i>
              <span id="detail-url"
                class="font-mono text-rose-700 font-semibold break-all select-all flex-1 text-xs"></span>
              <button type="button" onclick="copyTargetUrl()"
                class="px-2 py-1 bg-white hover:bg-rose-100 text-rose-700 rounded-lg border border-rose-200 font-semibold text-[11px] flex-shrink-0"
                title="Salin URL">
                <i class="bi bi-clipboard"></i> Salin
              </button>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3 pt-1">
            <div>
              <span class="text-slate-400 block text-[10px] uppercase font-semibold">Pelapor &amp; Waktu:</span>
              <span id="detail-reporter-date" class="font-medium text-slate-800"></span>
            </div>
            <div>
              <span class="text-slate-400 block text-[10px] uppercase font-semibold">Kategori Insiden:</span>
              <span id="detail-category" class="font-medium text-slate-800"></span>
            </div>
          </div>

          <div>
            <span class="text-slate-400 block text-[10px] uppercase font-semibold mb-0.5">Deskripsi / Modus
              Warga:</span>
            <p id="detail-desc"
              class="text-slate-700 bg-white p-2.5 rounded-lg border border-slate-200 leading-relaxed"></p>
          </div>
        </div>

        <!-- Form Ubah Status & Catatan Verifikasi -->
        <div class="space-y-4 text-xs">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="modal-status" class="block font-semibold text-slate-700 mb-1">
                Ubah Status <span class="text-rose-500">*</span>
              </label>
              <select name="status" id="modal-status" required
                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800 font-semibold">
                <option value="Pending">Pending (Menunggu Verifikasi)</option>
                <option value="Diproses">Diproses (Investigasi Tim)</option>
                <option value="Selesai">Selesai (Terverifikasi &amp; Diteruskan)</option>
              </select>
            </div>

            <div>
              <label for="modal-admin-name" class="block font-semibold text-slate-700 mb-1">
                Admin Penangani <span class="text-rose-500">*</span>
              </label>
              <input type="text" name="assigned_admin" id="modal-admin-name" required
                placeholder="Nama analis penangan..."
                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800">
            </div>
          </div>

          <div>
            <label for="modal-notes" class="block font-semibold text-slate-700 mb-1">
              Catatan Verifikasi &amp; Rekomendasi Pemblokiran
            </label>
            <textarea name="verification_notes" id="modal-notes" rows="3"
              placeholder="Contoh: Terkonfirmasi domain judi slot luar negeri tanpa izin OJK. Tautan telah diteruskan ke rekomendasi Komdigi aduankonten.id..."
              class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800 leading-relaxed"></textarea>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="pt-5 mt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
          <button type="button" onclick="closeActionModal()"
            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition-colors">
            Batal
          </button>
          <button type="submit" id="btn-save-report"
            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-sm transition-all active:scale-[0.98]">
            Simpan Pembaruan
          </button>
        </div>
      </form>

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

      const initial = (currentUser.name || 'A').charAt(0).toUpperCase();
      document.getElementById('sidebar-user-name').textContent = currentUser.name || 'Admin Utama';
      document.getElementById('sidebar-user-role').textContent = isSuper ? 'Super Admin' : 'Admin Basic';
      document.getElementById('sidebar-avatar').textContent = initial;

      document.getElementById('topbar-user-name').textContent = currentUser.name || 'Admin Utama';
      document.getElementById('topbar-user-role').textContent = isSuper ? 'Super Admin' : 'Admin Basic';
      document.getElementById('topbar-avatar').textContent = initial;
      document.getElementById('dropdown-user-email').textContent = currentUser.email || currentUser.username || 'admin@aswajacyber.id';

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

      toggleBtn?.addEventListener('click', () => {
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('hidden');
      });
      closeBtn?.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
      });
      backdrop?.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
      });

      // Topbar Dropdowns
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

      // 2. Data Management: Reports
      let reportsList = [];
      let activeStatusFilter = '';
      let currentPage = 1;
      const itemsPerPage = 5;

      // Handle search query param from URL (e.g. ?q=something)
      const urlParams = new URLSearchParams(window.location.search);
      const urlQuery = urlParams.get('q');
      if (urlQuery) {
        document.getElementById('search-report').value = urlQuery;
      }

      async function loadReports() {
        try {
          const res = await window.ApiService.getReports();
          reportsList = Array.isArray(res) ? res : (res?.data || []);

          const pendingCount = reportsList.filter(r => r.status === 'Pending' || r.status === 'Masuk').length;
          document.getElementById('badge-sidebar-reports').textContent = pendingCount;

          renderNotificationList(reportsList.slice(0, 4));
          renderReportsTable();
        } catch (err) {
          console.error('Error load reports:', err);
          document.getElementById('reports-table-body').innerHTML = `
            <tr><td colspan="6" class="text-center py-8 text-rose-500 text-xs">Gagal memuat data laporan: ${err.message}</td></tr>
          `;
        }
      }

      // Status Filter Button Handler (Semua, Pending, Proses, Selesai)
      window.setStatusFilter = function (status) {
        activeStatusFilter = status;
        currentPage = 1;

        document.querySelectorAll('#status-button-group .status-btn').forEach(btn => {
          if (btn.dataset.status === status) {
            btn.className = 'status-btn active px-3 py-1.5 text-xs font-semibold rounded-lg bg-white text-slate-900 shadow-sm transition-all';
          } else {
            btn.className = 'status-btn px-3 py-1.5 text-xs font-semibold rounded-lg text-slate-600 hover:text-slate-900 transition-all';
          }
        });

        renderReportsTable();
      };

      window.applyFilter = function () {
        currentPage = 1;
        renderReportsTable();
      };

      document.getElementById('search-report')?.addEventListener('input', applyFilter);
      document.getElementById('filter-category')?.addEventListener('change', applyFilter);
      document.getElementById('btn-reset-filter')?.addEventListener('click', () => {
        document.getElementById('search-report').value = '';
        document.getElementById('filter-category').value = '';
        setStatusFilter('');
      });

      function getFilteredReports() {
        const q = (document.getElementById('search-report').value || '').toLowerCase().trim();
        const cat = document.getElementById('filter-category').value;

        return reportsList.filter(r => {
          const matchQ = (r.url || r.target || '').toLowerCase().includes(q) ||
            (r.description || '').toLowerCase().includes(q) ||
            (r.reporter || r.reporter_name || '').toLowerCase().includes(q) ||
            (r.id || '').toLowerCase().includes(q);

          let matchStatus = true;
          if (activeStatusFilter === 'Pending') {
            matchStatus = r.status === 'Pending' || r.status === 'Masuk';
          } else if (activeStatusFilter === 'Proses') {
            matchStatus = r.status === 'Diproses' || r.status === 'Proses';
          } else if (activeStatusFilter === 'Selesai') {
            matchStatus = r.status === 'Selesai';
          }

          const matchCat = !cat || (r.category || '').includes(cat);

          return matchQ && matchStatus && matchCat;
        });
      }

      function renderReportsTable() {
        const filtered = getFilteredReports();
        const total = filtered.length;
        const totalPages = Math.ceil(total / itemsPerPage) || 1;

        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        const startIdx = (currentPage - 1) * itemsPerPage;
        const pageItems = filtered.slice(startIdx, startIdx + itemsPerPage);

        const tbody = document.getElementById('reports-table-body');

        if (pageItems.length === 0) {
          tbody.innerHTML = `
            <tr>
              <td colspan="6" class="text-center py-8 text-slate-400 text-xs">
                Tidak ada laporan yang sesuai dengan kriteria filter.
              </td>
            </tr>
          `;
          document.getElementById('reports-counter-info').textContent = 'Menampilkan 0 laporan aduan';
          document.getElementById('reports-pagination-controls').innerHTML = '';
          return;
        }

        tbody.innerHTML = pageItems.map(r => {
          const isPending = r.status === 'Pending' || r.status === 'Masuk';
          const isProcess = r.status === 'Diproses' || r.status === 'Proses';

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
          const truncUrl = safeUrl.length > 36 ? safeUrl.substring(0, 36) + '...' : safeUrl;

          return `
            <tr class="hover:bg-slate-50/75 transition-colors">
              <!-- 1. ID Laporan -->
              <td class="px-5 py-4 whitespace-nowrap">
                <div class="font-mono font-bold text-xs text-slate-900">${escapeHtml(r.id)}</div>
                <div class="font-mono text-[10px] text-rose-600 truncate max-w-[200px]" title="${safeUrl}">
                  ${truncUrl}
                </div>
              </td>

              <!-- 2. Pelapor -->
              <td class="px-4 py-4 whitespace-nowrap">
                <div class="text-xs font-semibold text-slate-800 flex items-center gap-1.5">
                  <i class="bi bi-person text-slate-400"></i>
                  <span>${escapeHtml(r.reporter || r.reporter_name || 'Hamba Allah')}</span>
                </div>
              </td>

              <!-- 3. Kategori Insiden -->
              <td class="px-4 py-4">
                <span class="inline-block text-[11px] font-semibold bg-slate-100 text-slate-700 px-2.5 py-0.5 rounded-lg border border-slate-200 truncate max-w-[180px]">
                  ${escapeHtml(r.category || 'Tautan Berbahaya')}
                </span>
                <p class="text-[11px] text-slate-400 line-clamp-1 mt-0.5">${escapeHtml(r.description || '')}</p>
              </td>

              <!-- 4. Tanggal Masuk -->
              <td class="px-4 py-4 whitespace-nowrap text-xs text-slate-500 font-mono">
                ${escapeHtml(r.date || '-')}
              </td>

              <!-- 5. Status -->
              <td class="px-4 py-4 whitespace-nowrap">
                ${statusBadge}
              </td>

              <!-- 6. Aksi (Detail / Ubah Status & Hapus) -->
              <td class="px-5 py-4 whitespace-nowrap text-right">
                <div class="inline-flex items-center gap-1.5">
                  <button type="button" onclick="openActionModal('${r.id}')" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 transition-colors">
                    <i class="bi bi-pencil-square"></i>
                    <span>Detail / Ubah</span>
                  </button>
                  ${isSuper ? `
                    <button type="button" onclick="handleDeleteReport('${r.id}')" class="p-1 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-500 hover:text-rose-600 transition-colors" title="Hapus Laporan">
                      <i class="bi bi-trash text-sm"></i>
                    </button>
                  ` : ''}
                </div>
              </td>
            </tr>
          `;
        }).join('');

        // Update Counter & Pagination
        const startNum = startIdx + 1;
        const endNum = Math.min(startIdx + itemsPerPage, total);
        document.getElementById('reports-counter-info').textContent = `Menampilkan ${startNum} - ${endNum} dari ${total} laporan aduan`;

        let pagerHtml = `
          <button type="button" onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled class="opacity-40 cursor-not-allowed"' : 'class="hover:bg-slate-200"'} class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-semibold text-xs transition-colors">
            &larr; Prev
          </button>
        `;

        for (let p = 1; p <= totalPages; p++) {
          if (p === currentPage) {
            pagerHtml += `<button type="button" class="px-3 py-1 rounded-lg bg-emerald-600 text-white font-bold text-xs shadow-sm">${p}</button>`;
          } else {
            pagerHtml += `<button type="button" onclick="changePage(${p})" class="px-3 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors">${p}</button>`;
          }
        }

        pagerHtml += `
          <button type="button" onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled class="opacity-40 cursor-not-allowed"' : 'class="hover:bg-slate-200"'} class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-semibold text-xs transition-colors">
            Next &rarr;
          </button>
        `;

        document.getElementById('reports-pagination-controls').innerHTML = pagerHtml;
      }

      window.changePage = function (page) {
        currentPage = page;
        renderReportsTable();
      };

      // Modal Draf Ringkas Handlers
      window.openActionModal = function (id) {
        const item = reportsList.find(r => String(r.id) === String(id));
        if (!item) return;

        document.getElementById('modal-report-id').value = item.id;
        document.getElementById('detail-ticket-id').textContent = item.id;
        document.getElementById('detail-url').textContent = item.url || item.target || '-';
        document.getElementById('detail-reporter-date').textContent = `${item.reporter || item.reporter_name || 'Anonim'} &bull; ${item.date || '-'}`;
        document.getElementById('detail-category').textContent = item.category || 'Tautan Berbahaya';
        document.getElementById('detail-desc').textContent = item.description || 'Tidak ada deskripsi.';

        // Status pill in modal
        const isPending = item.status === 'Pending' || item.status === 'Masuk';
        const isProcess = item.status === 'Diproses' || item.status === 'Proses';
        let badgeHtml = '';
        if (isPending) {
          badgeHtml = '<span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">Pending</span>';
        } else if (isProcess) {
          badgeHtml = '<span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">Diproses</span>';
        } else {
          badgeHtml = '<span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Selesai</span>';
        }
        document.getElementById('detail-status-pill').innerHTML = badgeHtml;

        // Form fields
        document.getElementById('modal-status').value = isPending ? 'Pending' : (isProcess ? 'Diproses' : 'Selesai');
        document.getElementById('modal-admin-name').value = item.handledBy || item.assigned_admin || currentUser.name || 'Admin Analis';
        document.getElementById('modal-notes').value = item.verificationNotes || item.verification_note || '';

        document.getElementById('modal-report-action').classList.remove('hidden');
      };

      window.closeActionModal = function () {
        document.getElementById('modal-report-action').classList.add('hidden');
      };

      window.copyTargetUrl = function () {
        const url = document.getElementById('detail-url').textContent;
        navigator.clipboard.writeText(url).then(() => {
          alert('URL berhasil disalin ke clipboard.');
        }).catch(() => {
          prompt('Salin URL target:', url);
        });
      };

      // Submit Form Update Status Laporan
      window.handleSaveReportAction = async function (e) {
        e.preventDefault();
        const id = document.getElementById('modal-report-id').value;
        const status = document.getElementById('modal-status').value;
        const adminName = document.getElementById('modal-admin-name').value.trim();
        const notes = document.getElementById('modal-notes').value.trim();

        const btn = document.getElementById('btn-save-report');
        btn.disabled = true;
        btn.textContent = 'Menyimpan...';

        try {
          await window.ApiService.updateReportStatus(id, {
            status,
            assigned_admin: adminName,
            handledBy: adminName,
            verificationNotes: notes,
            verification_note: notes
          });

          closeActionModal();
          await loadReports();
        } catch (err) {
          alert('Gagal memperbarui status laporan: ' + err.message);
        } finally {
          btn.disabled = false;
          btn.textContent = 'Simpan Pembaruan';
        }
      };

      // Delete Report Handler (Super Admin Only)
      window.handleDeleteReport = async function (id) {
        if (!isSuper) {
          alert('Akses Ditolak: Hanya Super Admin yang berwenang menghapus data aduan.');
          return;
        }

        if (confirm(`Hapus tiket laporan #${id} secara permanen? Tindakan ini tidak dapat dibatalkan.`)) {
          try {
            await window.ApiService.deleteReport(id);
            await loadReports();
          } catch (err) {
            alert('Gagal menghapus laporan: ' + err.message);
          }
        }
      };

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
          </a>
        `).join('');
      }

      window.handleGlobalSearch = function () {
        const q = document.getElementById('global-search-input').value.trim();
        if (q) {
          document.getElementById('search-report').value = q;
          applyFilter();
        }
      };

      function escapeHtml(str) {
        return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
      }

      document.getElementById('btn-refresh-reports')?.addEventListener('click', loadReports);

      loadReports();
    });
  </script>
</body>

</html>