<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <script src="../../js/admin-guard.js?v=20261109v2"></script>
  <title>Kelola Artikel Edukasi - Aswaja CyberShield Admin</title>
  <link rel="icon" type="image/svg+xml" href="../../favicon.svg">

  <!-- Google Fonts: Inter & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

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
  <link rel="stylesheet" href="../../css/ui-kit.css?v=20261109v3">
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

    <!-- Sidebar Component (Kiri: Hijau Gradasi) -->
  <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-[#064e3b] via-[#022c22] to-[#011a14] text-emerald-100 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen border-r border-emerald-900/40 shrink-0 shadow-2xl">
    <!-- Brand Header -->
    <div class="h-16 flex items-center justify-between px-5 border-b border-emerald-800/40 bg-emerald-950/40">
      <a href="/admin" class="flex items-center gap-3 group text-decoration-none">
        <img src="../../logo-shield.png" alt="Aswaja CyberShield" class="w-9 h-9 rounded-xl object-contain ring-1 ring-emerald-400/40 shadow-md">
        <div class="min-w-0">
          <span class="font-display font-bold text-sm text-white tracking-tight block truncate">Aswaja CyberShield</span>
          <span class="text-[10px] text-emerald-300 font-semibold uppercase tracking-wider block">Admin Dashboard</span>
        </div>
      </a>
      <button id="close-sidebar-btn" type="button" class="lg:hidden text-emerald-200/80 hover:text-white p-1 rounded-lg hover:bg-emerald-800/40 transition-colors" aria-label="Tutup sidebar">
        <i class="bi bi-x-lg text-lg"></i>
      </button>
    </div>

    <!-- Navigation Menu Items -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6" aria-label="Navigasi Utama Admin">
      <div>
        <p class="px-3 text-[11px] font-semibold uppercase tracking-wider text-emerald-300/70 mb-2">Menu Utama</p>
        <div class="space-y-1">
          <!-- 1. Dashboard -->
          <a href="/admin" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-emerald-100/75 hover:text-white hover:bg-emerald-800/40 transition-all group">
            <i class="bi bi-speedometer2 text-base text-emerald-300/80 group-hover:text-emerald-200 transition-colors"></i>
            <span>Dashboard</span>
          </a>

          <!-- 2. Artikel -->
          <a href="/admin/artikel" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl bg-emerald-500 text-white shadow-lg shadow-emerald-950/50 transition-all group">
            <i class="bi bi-file-earmark-text text-base text-white transition-colors"></i>
            <span>Artikel</span>
          </a>

          <!-- 3. Riwayat Pelaporan -->
          <a href="/admin/laporan" class="flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-xl text-emerald-100/75 hover:text-white hover:bg-emerald-800/40 transition-all group">
            <span class="flex items-center gap-3">
              <i class="bi bi-shield-exclamation text-base text-emerald-300/80 group-hover:text-emerald-200 transition-colors"></i>
              <span>Laporan</span>
            </span>
            <span id="badge-sidebar-reports" class="bg-rose-500/20 text-rose-300 text-xs px-2 py-0.5 rounded-full font-bold border border-rose-500/30">0</span>
          </a>

          <!-- 5. Studi Kasus -->
          <a href="/admin/studi-kasus" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-emerald-100/75 hover:text-white hover:bg-emerald-800/40 transition-all group">
            <i class="bi bi-journal-check text-base text-emerald-300/80 group-hover:text-emerald-200 transition-colors"></i>
            <span>Studi Kasus</span>
          </a>

          <!-- 4. Kelola Akun -->
          <a href="/admin/akun" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-emerald-100/75 hover:text-white hover:bg-emerald-800/40 transition-all group">
            <i class="bi bi-people text-base text-emerald-300/80 group-hover:text-emerald-200 transition-colors"></i>
            <span>Kelola Akun</span>
          </a>
        </div>
      </div>
    </nav>

    <!-- Sidebar Bottom / User Bar -->
    <div class="p-3 mt-auto shrink-0 border-t border-emerald-800/40 bg-emerald-950/50">
      <div class="flex items-center justify-between px-2.5 py-2 rounded-xl bg-emerald-900/40 border border-emerald-700/40">
        <div class="flex items-center gap-2.5 min-w-0">
          <div id="sidebar-avatar" class="w-8 h-8 rounded-lg bg-emerald-500 text-white font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-sm">
            A
          </div>
          <div class="min-w-0 flex-1">
            <p id="sidebar-user-name" class="text-xs font-semibold text-white truncate">Admin Utama</p>
            <p id="sidebar-user-role" class="text-[10px] text-emerald-300 truncate font-mono">Super Admin</p>
          </div>
        </div>
        <button id="sidebar-btn-logout" type="button" class="text-emerald-200/80 hover:text-rose-400 p-1.5 rounded-lg hover:bg-emerald-800/50 transition-colors" title="Keluar">
          <i class="bi bi-box-arrow-right"></i>
        </button>
      </div>
    </div>
  </aside>

  <!-- Main Content Wrapper (Tengah) -->
  <div class="flex-1 flex flex-col min-w-0 overflow-y-auto min-h-screen">

    <!-- Topbar Header (Atas) -->
    <header class="sticky top-0 z-30 h-16 bg-white/95 backdrop-blur-sm border-b border-slate-200/90 px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
      <!-- Left: Mobile Toggle & Breadcrumb -->
      <div class="flex items-center gap-3 min-w-0">
        <button id="toggle-sidebar-btn" type="button" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors" aria-label="Buka navigasi">
          <i class="bi bi-list text-2xl leading-none"></i>
        </button>
      </div>

      <!-- Center: Global Search Bar -->
      <form id="global-search-form" class="relative max-w-md w-full hidden md:block" role="search" onsubmit="event.preventDefault(); handleGlobalSearch();">
        <div class="relative">
          <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
          <input type="search" name="q" id="global-search-input" placeholder="Cari di semua modul..." class="w-full pl-9 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-slate-800 placeholder-slate-400">
        </div>
      </form>

      <!-- Right: Clock, Notif, Profil Admin -->
      <div class="flex items-center gap-2 sm:gap-3">
        <!-- Live Clock Pill -->
        <div class="hidden xl:flex items-center gap-1.5 text-xs text-slate-600 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200 font-mono">
          <i class="bi bi-clock text-slate-400"></i>
          <span id="live-clock"><?php date_default_timezone_set('Asia/Makassar'); echo date('H:i:s'); ?> WITA</span>
        </div>

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
            <div id="notif-list-container" class="divide-y divide-slate-100 max-h-72 overflow-y-auto"></div>
            <div class="px-4 pt-2 border-t border-slate-100 text-center">
              <a href="/admin/laporan" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat Semua Laporan &rarr;</a>
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

    <!-- Main Content Container -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-6">

      <!-- Header Halaman + Tombol "Tambah Artikel Baru" -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-display font-extrabold text-slate-900 tracking-tight">Kelola Artikel Edukasi</h1>
          <p class="text-sm text-slate-500 mt-0.5">Kelola artikel literasi siber, bahaya judi daring (maysir), modus investasi bodong, dan fiqih digital.</p>
        </div>
        <button type="button" onclick="openAddArticleModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-sm shadow-emerald-600/20 transition-all active:scale-[0.98]">
          <i class="bi bi-plus-circle text-base"></i>
          <span>Tambah Artikel Baru</span>
        </button>
      </div>

      <!-- Filter Kategori dan Search Bar Khusus Artikel (Semantic Form) -->
      <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
        <form id="filter-article-form" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3" onsubmit="event.preventDefault(); applyFilter();">
          <div class="relative flex-1 max-w-md">
            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="search" name="q_artikel" id="search-article" placeholder="Cari judul artikel, penulis, atau kata kunci..." class="w-full pl-9 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-slate-800 placeholder-slate-400">
          </div>

          <div class="flex flex-wrap items-center gap-2">
            <!-- Filter Kategori -->
            <select name="category" id="filter-category" class="px-3 py-2 text-xs font-medium bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-700">
              <option value="">Semua Kategori</option>
              <optgroup label="── Kategori Umum (Dampak & Modus) ──">
                <option value="Literasi & Modus Judi Online">Literasi &amp; Modus Judi Online</option>
                <option value="Dampak Sosial & Jeratan Finansial">Dampak Sosial &amp; Jeratan Finansial</option>
                <option value="Regulasi & Penegakan Hukum Siber">Regulasi &amp; Penegakan Hukum Siber</option>
              </optgroup>
              <optgroup label="── Kategori IT & Keamanan Siber ──">
                <option value="Forensik Digital & Investigasi Judol">Forensik Digital &amp; Investigasi Judol</option>
                <option value="Manipulasi Algoritma & Script Judol">Manipulasi Algoritma &amp; Script Judol</option>
                <option value="Infiltrasi Web & Malware APK">Infiltrasi Web &amp; Malware APK</option>
              </optgroup>
              <optgroup label="── Kategori Nilai Agama & Fiqih Aswaja ──">
                <option value="Fiqih Muamalah & Larangan Maysir">Fiqih Muamalah &amp; Larangan Maysir</option>
                <option value="Metodologi Tabayyun & Kaidah 4T">Metodologi Tabayyun &amp; Kaidah 4T</option>
                <option value="Kajian Fatwa & Maqashid Syariah">Kajian Fatwa &amp; Maqashid Syariah</option>
              </optgroup>
            </select>

            <!-- Filter Status Publish -->
            <select name="status" id="filter-status" class="px-3 py-2 text-xs font-medium bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-700">
              <option value="">Semua Status</option>
              <option value="Published">Published (Tayang)</option>
              <option value="Draft">Draft (Konsep)</option>
            </select>

            <button type="button" id="btn-reset-filter" class="px-3 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
              Reset
            </button>
          </div>
        </form>
      </div>

      <!-- Data Table Artikel -->
      <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-[11px] font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
              <tr>
                <th class="px-5 py-3.5">Judul</th>
                <th class="px-4 py-3.5">Penulis</th>
                <th class="px-4 py-3.5">Kategori</th>
                <th class="px-4 py-3.5">Tanggal</th>
                <th class="px-4 py-3.5">Status Publish</th>
                <th class="px-5 py-3.5 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody id="article-table-body" class="divide-y divide-slate-100">
              <tr>
                <td colspan="6" class="text-center py-8 text-slate-400 text-xs">
                  <div class="inline-block w-5 h-5 border-2 border-emerald-500 border-t-transparent rounded-full animate-spin mb-2"></div>
                  <p>Memuat daftar artikel...</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination di Bagian Bawah Tabel -->
        <div class="p-4 bg-slate-50/75 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
          <div id="pagination-info">Menampilkan 0 data artikel</div>
          <div class="flex items-center gap-1.5" id="pagination-controls">
            <!-- Populated by JS -->
          </div>
        </div>
      </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/90 py-4 px-6 text-center text-xs text-slate-500 mt-auto">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-2 max-w-7xl mx-auto">
        <p>&copy; 2026 <strong class="font-semibold text-slate-700">Aswaja CyberShield</strong> &bull; Kelola Artikel</p>
      </div>
    </footer>
  </div>

  <!-- Tambah/Edit Artik -->
  <div id="modal-article" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="min-h-full flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-slate-200 my-8">
      
      <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
        <h4 id="modal-article-title" class="font-display font-bold text-base text-slate-900 flex items-center gap-2">
          <i class="bi bi-file-earmark-plus text-emerald-600"></i>
          <span>Tambah Artikel Baru</span>
        </h4>
        <button type="button" onclick="closeArticleModal()" class="text-slate-400 hover:text-slate-700 p-1 rounded-lg">
          <i class="bi bi-x-lg text-lg"></i>
        </button>
      </div>

      <!-- Semantic Form with Proper Name Attributes -->
      <form id="form-article" method="POST" action="/admin/articles" onsubmit="handleSaveArticle(event)">
        <!-- Laravel CSRF & Method Spoofing Placeholders -->
        <input type="hidden" name="_token" value="csrf_token_placeholder">
        <input type="hidden" name="article_id" id="article-id" value="">

        <div class="space-y-4 text-xs">
          <!-- Judul Artikel -->
          <div>
            <label for="article-title" class="block font-semibold text-slate-700 mb-1">
              Judul Artikel <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="title" id="article-title" required placeholder="Contoh: Muamalah Finansial: Bahaya Manipulasi Trading Berkedok Slot" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800">
          </div>

          <!-- Slug URL & Kategori -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="article-slug" class="block font-semibold text-slate-700 mb-1">
                Slug URL (Permalink)
              </label>
              <input type="text" name="slug" id="article-slug" placeholder="muamalah-finansial-scam" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800 font-mono text-xs">
            </div>

            <div>
              <label for="article-category" class="block font-semibold text-slate-700 mb-1">
                Kategori <span class="text-rose-500">*</span>
              </label>
              <select name="category" id="article-category" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800">
                <optgroup label="── 1. Kategori Umum (Dampak & Modus) ──">
                  <option value="Literasi & Modus Judi Online">Literasi &amp; Modus Judi Online</option>
                  <option value="Dampak Sosial & Jeratan Finansial">Dampak Sosial &amp; Jeratan Finansial</option>
                  <option value="Regulasi & Penegakan Hukum Siber">Regulasi &amp; Penegakan Hukum Siber</option>
                </optgroup>
                <optgroup label="── 2. Kategori IT & Keamanan Siber ──">
                  <option value="Forensik Digital & Investigasi Judol">Forensik Digital &amp; Investigasi Judol</option>
                  <option value="Manipulasi Algoritma & Script Judol">Manipulasi Algoritma &amp; Script Judol</option>
                  <option value="Infiltrasi Web & Malware APK">Infiltrasi Web &amp; Malware APK</option>
                </optgroup>
                <optgroup label="── 3. Kategori Nilai Agama & Fiqih Aswaja ──">
                  <option value="Fiqih Muamalah & Larangan Maysir">Fiqih Muamalah &amp; Larangan Maysir</option>
                  <option value="Metodologi Tabayyun & Kaidah 4T">Metodologi Tabayyun &amp; Kaidah 4T</option>
                  <option value="Kajian Fatwa & Maqashid Syariah">Kajian Fatwa &amp; Maqashid Syariah</option>
                </optgroup>
              </select>
            </div>
          </div>

          <!-- Penulis & Status Publish -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="article-author" class="block font-semibold text-slate-700 mb-1">
                Penulis / Lembaga <span class="text-rose-500">*</span>
              </label>
              <input type="text" name="author" id="article-author" required placeholder="Lembaga Bahtsul Masail" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800">
            </div>

            <div>
              <label for="article-status" class="block font-semibold text-slate-700 mb-1">
                Status Publish <span class="text-rose-500">*</span>
              </label>
              <select name="status" id="article-status" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800">
                <option value="Published">Published (Tayang)</option>
                <option value="Draft">Draft (Konsep)</option>
              </select>
            </div>
          </div>

          <!-- Ringkasan Eksekutif -->
          <div>
            <label for="article-summary" class="block font-semibold text-slate-700 mb-1">
              Ringkasan Singkat / Abstrak <span class="text-rose-500">*</span>
            </label>
            <textarea name="summary" id="article-summary" rows="2" required placeholder="Tuliskan intisari materi artikel dalam 1-2 kalimat..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800 leading-relaxed"></textarea>
          </div>

          <!-- Konten Lengkap -->
          <div>
            <label for="article-content" class="block font-semibold text-slate-700 mb-1">
              Konten / Naskah Lengkap
            </label>
            <textarea name="content" id="article-content" rows="4" placeholder="Tuliskan naskah isi artikel edukasi di sini..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800 font-sans"></textarea>
          </div>

          <!-- Kolom Unggah Gambar / Dokumen (Dibawah Konten/Naskah Lengkap) -->
          <div>
            <label class="block font-semibold text-slate-700 mb-1">
              Unggah Gambar Sampul / Dokumen (Opsional)
            </label>
            <div id="article-dropzone" class="border-2 border-dashed border-slate-300 hover:border-emerald-500 rounded-2xl p-5 text-center cursor-pointer bg-slate-50/50 hover:bg-slate-50 transition-colors">
              <i class="bi bi-cloud-arrow-up text-3xl text-slate-400 block mb-1"></i>
              <p class="text-xs text-slate-600 font-medium">
                Klik untuk memilih file gambar atau seret ke area ini
              </p>
              <span class="text-[11px] text-slate-400 block mt-0.5">Format gambar JPG, PNG, atau WEBP (Maks. 5MB)</span>
              <input type="file" id="article-file-input" class="hidden" accept="image/*,application/pdf">
            </div>
            
            <div id="article-file-preview" class="hidden">
              <div class="mt-2 p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between text-xs text-emerald-800">
                <div class="flex items-center gap-2 truncate">
                  <img id="article-img-thumb" src="" alt="Thumbnail" class="w-9 h-9 rounded-lg object-cover border border-emerald-300">
                  <span id="article-file-name" class="font-medium truncate max-w-xs">gambar.png</span>
                </div>
                <button type="button" id="btn-remove-article-image" class="text-rose-600 hover:underline font-semibold ml-2">Hapus</button>
              </div>
            </div>
            <input type="hidden" name="image" id="article-image-data">
          </div>
        </div>

        <!-- Tombol Aksi Modal -->
        <div class="pt-5 mt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
          <button type="button" onclick="closeArticleModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition-colors">
            Batal
          </button>
          <button type="submit" id="btn-submit-article" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-sm transition-all active:scale-[0.98]">
            Simpan Artikel
          </button>
        </div>
      </form>

    </div>
    </div>
  </div>

  <!-- API Layer & Scripts -->
  <script src="../../js/api.js?v=20261109v2"></script>
  <script src="../../js/ui-kit.js?v=20261109v3"></script>
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
        if (window.AuthService && typeof window.AuthService.confirmLogout === 'function') {
          window.AuthService.confirmLogout();
        } else if (confirm('Keluar dari sesi administrator Aswaja CyberShield?')) {
          window.AuthService.logout();
        }
      }
      document.getElementById('sidebar-btn-logout')?.addEventListener('click', handleLogout);
      document.getElementById('topbar-btn-logout')?.addEventListener('click', handleLogout);

      // Live Clock
      function updateClock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', timeZone: 'Asia/Makassar' });
        const el = document.getElementById('live-clock');
        if (el) el.textContent = `${timeStr} WITA`;
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

      // 2. Data Management: Articles
      function getFallbackArticles() {
        try {
          const stored = localStorage.getItem('aswaja_mock_articles');
          if (stored) {
            const parsed = JSON.parse(stored);
            if (Array.isArray(parsed) && parsed.length > 0) return parsed;
          }
          if (window.MockDatabase && Array.isArray(window.MockDatabase.articles)) {
            return window.MockDatabase.articles;
          }
        } catch (e) {}
        return [];
      }

      function withTimeout(promise, ms = 3500) {
        return Promise.race([
          promise,
          new Promise((_, reject) => setTimeout(() => reject(new Error('Request timeout (' + ms + 'ms)')), ms))
        ]);
      }

      let articlesList = getFallbackArticles();
      let currentPage = 1;
      const itemsPerPage = 4;

      async function loadInitialData() {
        applyFilter();

        // Ambil data terbaru secara asinkron dan paralel tanpa memblokir rendering UI
        try {
          const [articlesResult, reportsResult] = await Promise.allSettled([
            withTimeout(window.ApiService.getArticles(), 3500),
            withTimeout(window.ApiService.getReports(), 3500)
          ]);

          if (articlesResult.status === 'fulfilled' && articlesResult.value) {
            const articlesRes = articlesResult.value;
            const items = Array.isArray(articlesRes) ? articlesRes : (articlesRes?.data || []);
            if (items && items.length > 0) {
              articlesList = items;
              applyFilter();
            }
          }

          if (reportsResult.status === 'fulfilled' && reportsResult.value) {
            const reportsRes = reportsResult.value;
            const reports = Array.isArray(reportsRes) ? reportsRes : (reportsRes?.data || []);
            const pendingCount = reports.filter(r => r.status === 'Pending' || r.status === 'Masuk').length;
            const badgeReports = document.getElementById('badge-sidebar-reports');
            if (badgeReports) badgeReports.textContent = pendingCount;
            renderNotificationList(reports.slice(0, 4));
          }
        } catch (err) {
          console.warn('[Admin Artikel] Background refresh error:', err);
        }
      }

      // Filter & Pagination Logic
      window.applyFilter = function() {
        currentPage = 1;
        renderArticles();
      };

      document.getElementById('search-article')?.addEventListener('input', applyFilter);
      document.getElementById('filter-category')?.addEventListener('change', applyFilter);
      document.getElementById('filter-status')?.addEventListener('change', applyFilter);
      document.getElementById('btn-reset-filter')?.addEventListener('click', () => {
        document.getElementById('search-article').value = '';
        document.getElementById('filter-category').value = '';
        document.getElementById('filter-status').value = '';
        applyFilter();
      });

      function getFilteredArticles() {
        const q = (document.getElementById('search-article').value || '').toLowerCase().trim();
        const cat = document.getElementById('filter-category').value;
        const stat = document.getElementById('filter-status').value;

        return articlesList.filter(a => {
          const matchQ = (a.title || '').toLowerCase().includes(q) ||
                         (a.author || '').toLowerCase().includes(q) ||
                         (a.summary || '').toLowerCase().includes(q);
          const matchCat = !cat || a.category === cat;
          const matchStat = !stat || a.status === stat;
          return matchQ && matchCat && matchStat;
        });
      }

      function renderArticles() {
        const filtered = getFilteredArticles();
        const total = filtered.length;
        const totalPages = Math.ceil(total / itemsPerPage) || 1;

        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        const startIdx = (currentPage - 1) * itemsPerPage;
        const pageItems = filtered.slice(startIdx, startIdx + itemsPerPage);

        const tbody = document.getElementById('article-table-body');

        if (pageItems.length === 0) {
          tbody.innerHTML = `
            <tr>
              <td colspan="6" class="text-center py-8 text-slate-400 text-xs">
                Tidak ada artikel yang cocok dengan filter.
              </td>
            </tr>
          `;
          document.getElementById('pagination-info').textContent = 'Menampilkan 0 dari 0 artikel';
          document.getElementById('pagination-controls').innerHTML = '';
          return;
        }

        tbody.innerHTML = pageItems.map(a => {
          const isPublished = a.status === 'Published';
          const statusBadge = isPublished
            ? `<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Published</span>`
            : `<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Draft</span>`;

          return `
            <tr class="hover:bg-slate-50/75 transition-colors cursor-pointer group" onclick="openEditArticleModal(${a.id})" title="Klik untuk edit artikel: ${escapeHtml(a.title)}">
              <td class="px-5 py-4 max-w-sm">
                <div class="font-display font-bold text-slate-900 text-xs sm:text-sm line-clamp-1 group-hover:text-emerald-700 transition-colors">
                  ${escapeHtml(a.title)}
                </div>
                <p class="text-[11px] text-slate-400 line-clamp-1 mt-0.5">${escapeHtml(a.summary || '')}</p>
                <span class="font-mono text-[10px] text-slate-400 block mt-0.5">slug: ${escapeHtml(a.slug || '-')}</span>
              </td>
              <td class="px-4 py-4 whitespace-nowrap">
                <span class="text-xs font-medium text-slate-700">${escapeHtml(a.author || 'Tim Literasi')}</span>
              </td>
              <td class="px-4 py-4 whitespace-nowrap">
                <span class="inline-block text-[11px] font-semibold bg-emerald-50 text-emerald-800 px-2.5 py-0.5 rounded-lg border border-emerald-200/80">
                  ${escapeHtml(a.category || 'Fiqih Finansial')}
                </span>
              </td>
              <td class="px-4 py-4 whitespace-nowrap text-xs text-slate-500 font-mono">
                ${escapeHtml(a.date || '-')}
              </td>
              <td class="px-4 py-4 whitespace-nowrap">
                ${statusBadge}
              </td>
              <td class="px-5 py-4 whitespace-nowrap text-right" onclick="event.stopPropagation()">
                <div class="inline-flex items-center gap-1.5">
                  <button type="button" onclick="event.stopPropagation(); openEditArticleModal(${a.id})" class="p-1.5 rounded-lg bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 transition-colors" title="Edit Artikel">
                    <i class="bi bi-pencil-square text-sm"></i>
                  </button>
                  <button type="button" onclick="event.stopPropagation(); handleDeleteArticle(${a.id})" class="p-1.5 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 transition-colors" title="Hapus Artikel">
                    <i class="bi bi-trash text-sm"></i>
                  </button>
                </div>
              </td>
            </tr>
          `;
        }).join('');

        // Update Pagination Info & Buttons
        const startNum = startIdx + 1;
        const endNum = Math.min(startIdx + itemsPerPage, total);
        document.getElementById('pagination-info').textContent = `Menampilkan ${startNum} - ${endNum} dari ${total} artikel`;

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

        document.getElementById('pagination-controls').innerHTML = pagerHtml;
      }

      window.changePage = function(page) {
        currentPage = page;
        renderArticles();
      };

      // Modal Handlers
      const UI = window.AswajaUI;
      const articleForm = document.getElementById('form-article');
      const articleContentArea = document.getElementById('article-content');
      const articleRte = UI ? UI.richEditor(articleContentArea, { label: 'Isi artikel edukasi', placeholder: 'Tuliskan naskah isi artikel edukasi di sini...' }) : null;
      const articleDirtyTracker = UI ? UI.form.trackDirty(articleForm, () => (articleRte ? articleRte.getHTML() : '')) : null;

      if (UI) {
        UI.enhanceModal(document.getElementById('modal-article'), {
          labelledBy: 'modal-article-title',
          initialFocus: '#article-title',
          onRequestClose: () => closeArticleModal()
        });
      }

      window.openAddArticleModal = function() {
        document.getElementById('modal-article-title').innerHTML = '<i class="bi bi-file-earmark-plus text-emerald-600"></i><span>Tambah Artikel Baru</span>';
        articleForm.reset();
        document.getElementById('article-id').value = '';
        document.getElementById('article-author').value = currentUser.name || 'Tim Literasi Siber';
        document.getElementById('article-image-data').value = '';
        const addImgThumb = document.getElementById('article-img-thumb');
        if (addImgThumb) {
          addImgThumb.removeAttribute('src');
          addImgThumb.classList.add('hidden');
        }
        document.getElementById('article-file-preview').classList.add('hidden');
        document.getElementById('article-dropzone').classList.remove('hidden');
        document.getElementById('article-status').value = 'Published';
        if (articleRte) articleRte.setHTML('');
        if (UI) UI.form.clearErrors(articleForm);
        if (articleDirtyTracker) articleDirtyTracker.reset();
        document.getElementById('modal-article').classList.remove('hidden');
      };

      window.openEditArticleModal = function(id) {
        const item = articlesList.find(a => Number(a.id) === Number(id));
        if (!item) return;

        document.getElementById('modal-article-title').innerHTML = '<i class="bi bi-pencil-square text-emerald-600"></i><span>Edit Artikel Edukasi</span>';
        document.getElementById('article-id').value = item.id;
        document.getElementById('article-title').value = item.title || '';
        document.getElementById('article-slug').value = item.slug || '';
        const catSelect = document.getElementById('article-category');
        const targetCat = item.category || 'Literasi & Modus Judi Online';
        let exists = Array.from(catSelect.options).some(opt => opt.value.toLowerCase() === targetCat.toLowerCase());
        if (!exists && targetCat) {
          const opt = document.createElement('option');
          opt.value = targetCat;
          opt.textContent = targetCat;
          catSelect.appendChild(opt);
        }
        catSelect.value = targetCat;
        document.getElementById('article-author').value = item.author || '';
        document.getElementById('article-status').value = item.status || 'Published';
        document.getElementById('article-summary').value = item.summary || item.excerpt || '';
        const bodyContent = item.content || item.summary || item.excerpt || '';
        if (articleRte) articleRte.setHTML(bodyContent);
        else articleContentArea.value = bodyContent;

        const rawImg = item.image || item.cover_image || item.coverImage || '';
        const isValidImg = rawImg && (
          rawImg.startsWith('data:image/') ||
          rawImg.startsWith('http://') ||
          rawImg.startsWith('https://') ||
          rawImg.startsWith('/')
        ) && !rawImg.includes('../images/');

        const imgData = document.getElementById('article-image-data');
        const imgThumb = document.getElementById('article-img-thumb');
        const fileNameEl = document.getElementById('article-file-name');
        const previewEl = document.getElementById('article-file-preview');
        const dropzoneEl = document.getElementById('article-dropzone');

        // Pastikan handler error jika gambar gagal dimuat
        imgThumb.onerror = function() {
          imgData.value = '';
          imgThumb.removeAttribute('src');
          previewEl.classList.add('hidden');
          dropzoneEl.classList.remove('hidden');
        };

        if (isValidImg) {
          imgData.value = rawImg;
          imgThumb.src = rawImg;
          imgThumb.classList.remove('hidden');
          fileNameEl.textContent = rawImg.startsWith('data:image/') ? 'Gambar Sampul Terlampir' : (rawImg.split('/').pop() || 'Gambar Terlampir');
          previewEl.classList.remove('hidden');
          dropzoneEl.classList.add('hidden');
        } else {
          imgData.value = '';
          imgThumb.removeAttribute('src');
          imgThumb.classList.add('hidden');
          previewEl.classList.add('hidden');
          dropzoneEl.classList.remove('hidden');
        }

        if (UI) UI.form.clearErrors(articleForm);
        if (articleDirtyTracker) articleDirtyTracker.reset();
        document.getElementById('modal-article').classList.remove('hidden');
      };

      window.closeArticleModal = async function(force) {
        if (!force && articleDirtyTracker && articleDirtyTracker.isDirty()) {
          const discard = await UI.confirmDiscard({
            title: 'Tutup form artikel?',
            message: 'Perubahan pada artikel ini belum disimpan. Apakah Anda yakin ingin membatalkan perubahan?'
          });
          if (!discard) return;
        }
        document.getElementById('modal-article').classList.add('hidden');
      };

      // Auto generate slug from title
      document.getElementById('article-title')?.addEventListener('input', (e) => {
        const slugInput = document.getElementById('article-slug');
        if (!document.getElementById('article-id').value) {
          slugInput.value = e.target.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
        }
      });

      // Dropzone Gambar Artikel Handlers
      const articleDropzone = document.getElementById('article-dropzone');
      const articleFileInput = document.getElementById('article-file-input');
      const articleFilePreview = document.getElementById('article-file-preview');
      const articleImgThumb = document.getElementById('article-img-thumb');
      const articleFileName = document.getElementById('article-file-name');
      const articleImageData = document.getElementById('article-image-data');
      const btnRemoveArticleImage = document.getElementById('btn-remove-article-image');

      if (articleDropzone && articleFileInput) {
        articleDropzone.addEventListener('click', () => articleFileInput.click());
        articleDropzone.addEventListener('dragover', (e) => {
          e.preventDefault();
          articleDropzone.classList.add('border-emerald-500', 'bg-emerald-50/40');
        });
        articleDropzone.addEventListener('dragleave', () => {
          articleDropzone.classList.remove('border-emerald-500', 'bg-emerald-50/40');
        });
        articleDropzone.addEventListener('drop', (e) => {
          e.preventDefault();
          articleDropzone.classList.remove('border-emerald-500', 'bg-emerald-50/40');
          if (e.dataTransfer.files.length > 0) handleArticleFile(e.dataTransfer.files[0]);
        });
        articleFileInput.addEventListener('change', (e) => {
          if (e.target.files.length > 0) handleArticleFile(e.target.files[0]);
        });
      }

      function handleArticleFile(file) {
        if (file.size > 5 * 1024 * 1024) {
          alert('Ukuran file maksimal 5MB.');
          return;
        }
        const reader = new FileReader();
        reader.onload = (e) => {
          const res = e.target.result;
          articleImageData.value = res;
          articleImgThumb.src = res;
          articleImgThumb.classList.remove('hidden');
          articleFileName.textContent = file.name;
          articleFilePreview.classList.remove('hidden');
          articleDropzone.classList.add('hidden');
        };
        reader.readAsDataURL(file);
      }

      if (btnRemoveArticleImage) {
        btnRemoveArticleImage.addEventListener('click', () => {
          articleFileInput.value = '';
          articleImageData.value = '';
          if (articleImgThumb) {
            articleImgThumb.removeAttribute('src');
            articleImgThumb.classList.add('hidden');
          }
          articleFilePreview.classList.add('hidden');
          articleDropzone.classList.remove('hidden');
        });
      }

      // Save Article
      // Save Article with Validation, Confirmation & Feedback
      window.handleSaveArticle = async function(e) {
        e.preventDefault();
        const id = document.getElementById('article-id').value;
        const titleInput = document.getElementById('article-title');
        const catInput = document.getElementById('article-category');
        const authorInput = document.getElementById('article-author');
        const summaryInput = document.getElementById('article-summary');

        const title = titleInput.value.trim();
        const slug = document.getElementById('article-slug').value.trim();
        const category = catInput.value;
        const author = authorInput.value.trim();
        const image = document.getElementById('article-image-data')?.value || '';
        const status = document.getElementById('article-status').value;
        const summary = summaryInput.value.trim();
        const content = articleRte ? articleRte.getHTML() : document.getElementById('article-content').value.trim();

        // 1. Validasi field
        const errs = [];
        if (!title) errs.push({ input: titleInput, label: 'Judul Artikel', message: 'Judul artikel wajib diisi.' });
        if (!category) errs.push({ input: catInput, label: 'Kategori', message: 'Kategori artikel wajib dipilih.' });
        if (!author) errs.push({ input: authorInput, label: 'Penulis', message: 'Penulis atau lembaga wajib diisi.' });
        if (!summary) errs.push({ input: summaryInput, label: 'Ringkasan', message: 'Ringkasan singkat wajib diisi.' });
        if (errs.length) {
          UI.form.showErrors(articleForm, errs, { title: 'Form Belum Lengkap' });
          return;
        }
        UI.form.clearErrors(articleForm);

        // 2. Alur konfirmasi
        const actionLabel = id ? 'Simpan Perubahan' : (status === 'Published' ? 'Terbitkan Sekarang' : 'Simpan sebagai Draf');
        const confirmMsg = id
          ? `Simpan perubahan pada artikel "${title}"?`
          : `Simpan dan ${status === 'Published' ? 'terbitkan' : 'arsipkan draf'} artikel "${title}"?`;

        const outcome = await UI.confirm({
          icon: 'info',
          title: id ? 'Simpan editan artikel?' : 'Terbitkan artikel baru?',
          message: confirmMsg,
          summary: [
            { label: 'Judul', value: title },
            { label: 'Kategori', value: category },
            { label: 'Penulis', value: author },
            { label: 'Status', value: status }
          ],
          confirmText: actionLabel,
          cancelText: 'Periksa Lagi',
          loadingTitle: 'Menyimpan artikel...',
          loadingText: 'Mohon tunggu, naskah artikel sedang diproses.',
          errorTitle: 'Gagal menyimpan artikel',
          onConfirm: async () => {
            const payload = {
              title,
              slug,
              category,
              author,
              image: image || null,
              cover_image: image || null,
              readTime: '5 Menit Baca',
              status,
              summary,
              content
            };
            if (id) {
              return await window.ApiService.updateArticle(id, payload);
            } else {
              return await window.ApiService.createArticle(payload);
            }
          },
          success: () => ({
            title: id ? 'Artikel berhasil diperbarui' : 'Artikel berhasil disimpan',
            message: `Artikel "${title}" kini telah tersimpan dengan status ${status}.`,
            actions: [{ id: 'close', label: 'Tutup & Kembali', variant: 'primary', autofocus: true }]
          })
        });

        if (outcome.status === 'success') {
          if (articleDirtyTracker) articleDirtyTracker.reset();
          closeArticleModal(true);
          await loadInitialData();
        }
      };

      // Delete Article with Universal Reusable Delete Modal
      window.handleDeleteArticle = async function(id) {
        if (!isSuper) {
          UI.notice({
            icon: 'warning',
            title: 'Akses Ditolak',
            message: 'Hanya Super Admin yang berwenang menghapus artikel edukasi.'
          });
          return;
        }

        const target = articlesList.find(a => Number(a.id) === Number(id));
        const title = target ? target.title : `ID #${id}`;

        await UI.confirmDelete({
          type: 'artikel',
          name: title,
          successMessage: 'Artikel edukasi berhasil dihapus.',
          onDelete: async () => {
            await window.ApiService.deleteArticle(id);
            const row = document.querySelector(`button[onclick*="handleDeleteArticle(${id})"]`)?.closest('tr');
            if (row) {
              row.style.transition = 'opacity 250ms ease, transform 250ms ease';
              row.style.opacity = '0';
              row.style.transform = 'translateY(-6px)';
              await new Promise(r => setTimeout(r, 260));
            }
            await loadInitialData();
          }
        });
      };

      function renderNotificationList(reports) {
        const container = document.getElementById('notif-list-container');
        if (!container) return;
        if (!reports || reports.length === 0) {
          container.innerHTML = '<div class="p-4 text-center text-xs text-slate-400">Tidak ada notifikasi baru.</div>';
          return;
        }

        container.innerHTML = reports.map(r => `
          <a href="/admin/laporan" class="block p-3 hover:bg-slate-50 transition-colors">
            <div class="flex items-center justify-between mb-1">
              <span class="font-mono font-bold text-[11px] text-slate-800">${escapeHtml(r.id)}</span>
              <span class="text-[10px] text-slate-400">${escapeHtml(r.date || 'Hari ini')}</span>
            </div>
            <p class="text-xs text-slate-600 font-medium truncate">${escapeHtml(r.url || r.target || 'Laporan baru')}</p>
          </a>
        `).join('');
      }

      window.handleGlobalSearch = function() {
        const q = document.getElementById('global-search-input').value.trim();
        if (q) {
          window.location.href = `laporan.blade.php?q=${encodeURIComponent(q)}`;
        }
      };

      function escapeHtml(str) {
        return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
      }

      loadInitialData();
    });
  </script>
</body>
</html>

