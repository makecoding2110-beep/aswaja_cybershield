<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <script src="../../js/admin-guard.js"></script>
  <title>Studi Kasus &amp; Soal Cyber Guard - Aswaja CyberShield Admin</title>
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
  <link rel="stylesheet" href="../../css/ui-kit.css">
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
          <a href="/admin/artikel" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-emerald-100/75 hover:text-white hover:bg-emerald-800/40 transition-all group">
            <i class="bi bi-file-earmark-text text-base text-emerald-300/80 group-hover:text-emerald-200 transition-colors"></i>
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
          <a href="/admin/studi-kasus" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl bg-emerald-500 text-white shadow-lg shadow-emerald-950/50 transition-all group">
            <i class="bi bi-journal-check text-base text-white transition-colors"></i>
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

        <button type="button" id="btn-refresh-cases" class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors" title="Segarkan Data">
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
            <div id="notif-list-container" class="divide-y divide-slate-100 max-h-72 overflow-y-auto"></div>
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

      <!-- Header Halaman + Tombol "Tambah Studi Kasus" -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-display font-extrabold text-slate-900 tracking-tight">Studi Kasus Cyber &amp; Soal Kuis</h1>
          <p class="text-sm text-slate-500 mt-0.5">Kelola skenario simulasi bahaya judi online, tingkat risiko, pilihan ganda (A, B, C, D), dan penjelasan fiqih tabayyun.</p>
        </div>
        <button type="button" onclick="openAddCaseModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-sm shadow-emerald-600/20 transition-all active:scale-[0.98]">
          <i class="bi bi-patch-plus text-base"></i>
          <span>Tambah Soal Kasus</span>
        </button>
      </div>

      <!-- Toolbar: Pencarian, Filter Tingkat Risiko, & View Toggle (Data Table / Grid Kartu) -->
      <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
        <div class="relative flex-1 max-w-md">
          <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
          <input type="search" name="q_kasus" id="search-cases" placeholder="Cari judul kasus, skenario, atau pengirim..." class="w-full pl-9 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-slate-800 placeholder-slate-400">
        </div>

        <div class="flex flex-wrap items-center gap-2">
          <!-- Filter Tingkat Risiko -->
          <select name="risk_level_filter" id="filter-risk" class="px-3 py-2 text-xs font-medium bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-700">
            <option value="">Semua Tingkat Risiko</option>
            <option value="Kritis">Risiko Kritis</option>
            <option value="Tinggi">Risiko Tinggi</option>
            <option value="Sedang">Risiko Sedang</option>
            <option value="Rendah">Risiko Rendah</option>
          </select>

          <!-- View Toggle (Table View vs Card Grid View) -->
          <div class="flex items-center bg-slate-100 p-1 rounded-xl gap-1">
            <button type="button" id="btn-view-table" onclick="setViewMode('table')" class="p-1.5 rounded-lg text-xs font-semibold bg-white text-slate-900 shadow-sm transition-all" title="Tampilan Tabel Data">
              <i class="bi bi-table"></i>
            </button>
            <button type="button" id="btn-view-grid" onclick="setViewMode('grid')" class="p-1.5 rounded-lg text-xs font-semibold text-slate-500 hover:text-slate-900 transition-all" title="Tampilan Grid Kartu">
              <i class="bi bi-grid"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- VIEW 1: Data Table Studi Kasus Cyber (Kolom: Judul Kasus, Tingkat Risiko, Tanggal, Aksi) -->
      <div id="view-table-container" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-[11px] font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
              <tr>
                <th class="px-5 py-3.5">Judul Kasus</th>
                <th class="px-4 py-3.5">Tingkat Risiko</th>
                <th class="px-4 py-3.5">Tanggal</th>
                <th class="px-5 py-3.5 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody id="cases-table-body" class="divide-y divide-slate-100">
              <tr>
                <td colspan="4" class="text-center py-8 text-slate-400 text-xs">
                  <div class="inline-block w-5 h-5 border-2 border-emerald-500 border-t-transparent rounded-full animate-spin mb-2"></div>
                  <p>Memuat data studi kasus...</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- VIEW 2: Grid Kartu Studi Kasus Cyber -->
      <div id="view-grid-container" class="hidden grid-cols-1 md:grid-cols-2 gap-5">
        <!-- Populated by JS -->
      </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/90 py-4 px-6 text-center text-xs text-slate-500 mt-auto">
      <div class="flex items-center justify-center gap-2 max-w-7xl mx-auto">
        <p>&copy; 2026 <strong class="font-semibold text-slate-700">Aswaja CyberShield</strong> &bull; Modul Latihan Cyber Guard</p>
      </div>
    </footer>
  </div>

  <!-- Modal Tambah / Edit Studi Kasus & Soal (Semantic Form Laravel-Ready) -->
  <div id="modal-case" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="min-h-full flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-slate-200 my-8">
      
      <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
        <h4 id="modal-case-title" class="font-display font-bold text-base text-slate-900 flex items-center gap-2">
          <i class="bi bi-patch-plus text-emerald-600"></i>
          <span>Tambah Studi Kasus &amp; Soal</span>
        </h4>
        <button type="button" onclick="closeCaseModal()" class="text-slate-400 hover:text-slate-700 p-1 rounded-lg">
          <i class="bi bi-x-lg text-lg"></i>
        </button>
      </div>

      <!-- Semantic Form with Proper Name Attributes -->
      <form id="form-case" method="POST" action="/admin/case-studies" onsubmit="handleSaveCase(event)">
        <!-- CSRF & Hidden ID -->
        <input type="hidden" name="_token" value="csrf_token_placeholder">
        <input type="hidden" name="case_id" id="case-id">

        <div class="space-y-4 text-xs">
          <!-- Judul Skenario Kasus -->
          <div>
            <label for="case-title" class="block font-semibold text-slate-700 mb-1">
              Judul Kasus <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="title" id="case-title" required placeholder="Contoh: Tautan Robot Trading Syariah yang Berujung Deposit Slot" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800">
          </div>

          <!-- Tingkat Risiko & Kesulitan -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="case-risk" class="block font-semibold text-slate-700 mb-1">
                Tingkat Risiko <span class="text-rose-500">*</span>
              </label>
              <select name="risk_level" id="case-risk" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800 font-semibold">
                <option value="Kritis">Kritis (Tautan Judi Slot &amp; Phishing)</option>
                <option value="Tinggi">Tinggi (Investasi Bodong / Robot Trading)</option>
                <option value="Sedang">Sedang (Deepfake &amp; Skema Arisan)</option>
                <option value="Rendah">Rendah (Pancingan Data Dasar)</option>
              </select>
            </div>

            <div>
              <label for="case-difficulty" class="block font-semibold text-slate-700 mb-1">
                Tingkat Kesulitan Kuis
              </label>
              <select name="difficulty" id="case-difficulty" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800">
                <option value="Dasar">Dasar (Pemula)</option>
                <option value="Menengah">Menengah</option>
                <option value="Lanjutan">Lanjutan</option>
              </select>
            </div>
          </div>

          <!-- Konteks Pengirim & Tautan Jebakan -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="case-sender" class="block font-semibold text-slate-700 mb-1">
                Pengirim / Kanal Skenario
              </label>
              <input type="text" name="sender" id="case-sender" placeholder="Grup Telegram: CUAN BERKAH VIP" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800">
            </div>

            <div>
              <label for="case-target-url" class="block font-semibold text-slate-700 mb-1">
                URL Domain Jebakan (Opsional)
              </label>
              <input type="text" name="target_url" id="case-target-url" placeholder="https://cuan-trading-vip88.xyz" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800 font-mono text-xs">
            </div>
          </div>

          <!-- Isi Pesan Skenario Simulasi -->
          <div>
            <label for="case-scenario" class="block font-semibold text-slate-700 mb-1">
              Isi Pesan Skenario Simulasi <span class="text-rose-500">*</span>
            </label>
            <textarea name="scenario" id="case-scenario" rows="2" required placeholder="Contoh: Pengguna menerima broadcast pesan WhatsApp yang menjanjikan keuntungan pasif harian..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800 leading-relaxed"></textarea>
          </div>

          <!-- Pertanyaan Evaluasi -->
          <div>
            <label for="case-question" class="block font-semibold text-slate-700 mb-1">
              Pertanyaan Kuis Tabayyun <span class="text-rose-500">*</span>
            </label>
            <textarea name="question" id="case-question" rows="2" required placeholder="Contoh: Apa tindakan Tabayyun dan kaidah Fiqih yang tepat setelah melihat tawaran tersebut?" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800 leading-relaxed"></textarea>
          </div>

          <!-- Pilihan Jawaban A, B, C, D & Kunci Jawaban -->
          <div class="space-y-2 pt-2 border-t border-slate-100">
            <span class="block font-bold text-slate-800 mb-2">Pilihan Jawaban (Pilih Radio untuk Kunci Jawaban yang Benar):</span>

            <!-- Pilihan A -->
            <div class="flex items-center gap-2">
              <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-700 font-bold flex items-center justify-center flex-shrink-0">A</span>
              <input type="text" name="option_a" id="case-opt-a" required placeholder="Teks pilihan jawaban A..." class="flex-1 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-slate-800">
              <label class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer hover:bg-slate-100">
                <input type="radio" name="correct_option" value="A" required class="text-emerald-600 focus:ring-emerald-500">
                <span class="font-semibold text-slate-700">Benar</span>
              </label>
            </div>

            <!-- Pilihan B -->
            <div class="flex items-center gap-2">
              <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-700 font-bold flex items-center justify-center flex-shrink-0">B</span>
              <input type="text" name="option_b" id="case-opt-b" required placeholder="Teks pilihan jawaban B..." class="flex-1 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-slate-800">
              <label class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer hover:bg-slate-100">
                <input type="radio" name="correct_option" value="B" class="text-emerald-600 focus:ring-emerald-500">
                <span class="font-semibold text-slate-700">Benar</span>
              </label>
            </div>

            <!-- Pilihan C -->
            <div class="flex items-center gap-2">
              <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-700 font-bold flex items-center justify-center flex-shrink-0">C</span>
              <input type="text" name="option_c" id="case-opt-c" required placeholder="Teks pilihan jawaban C..." class="flex-1 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-slate-800">
              <label class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer hover:bg-slate-100">
                <input type="radio" name="correct_option" value="C" class="text-emerald-600 focus:ring-emerald-500">
                <span class="font-semibold text-slate-700">Benar</span>
              </label>
            </div>

            <!-- Pilihan D -->
            <div class="flex items-center gap-2">
              <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-700 font-bold flex items-center justify-center flex-shrink-0">D</span>
              <input type="text" name="option_d" id="case-opt-d" required placeholder="Teks pilihan jawaban D..." class="flex-1 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-slate-800">
              <label class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer hover:bg-slate-100">
                <input type="radio" name="correct_option" value="D" class="text-emerald-600 focus:ring-emerald-500">
                <span class="font-semibold text-slate-700">Benar</span>
              </label>
            </div>
          </div>

          <!-- Penjelasan Edukasi / Pembahasan Fiqih -->
          <div>
            <label for="case-explanation" class="block font-semibold text-slate-700 mb-1">
              Penjelasan Jawaban &amp; Tinjauan Fiqih Tabayyun <span class="text-rose-500">*</span>
            </label>
            <textarea name="explanation" id="case-explanation" rows="3" required placeholder="Jelaskan kaidah hukumnya, misal prinsip Tawaqquf untuk menahan diri dari menyetor deposit judol..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800 leading-relaxed"></textarea>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="pt-5 mt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
          <button type="button" onclick="closeCaseModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition-colors">
            Batal
          </button>
          <button type="submit" id="btn-save-case" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-sm transition-all active:scale-[0.98]">
            Simpan Studi Kasus
          </button>
        </div>
      </form>

    </div>
    </div>
  </div>

  <!-- Modal Preview Kuis Interaktif -->
  <div id="modal-preview-case" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="min-h-full flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 my-8 space-y-4">
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <h4 class="font-display font-bold text-base text-slate-900 flex items-center gap-2">
          <i class="bi bi-eye text-emerald-600"></i>
          <span>Pratinjau Skenario Kuis</span>
        </h4>
        <button type="button" onclick="closePreviewModal()" class="text-slate-400 hover:text-slate-700 p-1 rounded-lg">
          <i class="bi bi-x-lg text-lg"></i>
        </button>
      </div>

      <div class="space-y-3 text-xs">
        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
          <span class="text-slate-400 block text-[10px] uppercase font-semibold mb-1" id="prev-sender">Pengirim:</span>
          <p class="text-slate-800 font-semibold italic" id="prev-message"></p>
        </div>

        <div>
          <span class="text-slate-400 block text-[10px] uppercase font-semibold mb-1">Pertanyaan Simulasi:</span>
          <p class="text-slate-800 font-bold text-sm" id="prev-question"></p>
        </div>

        <div class="space-y-1.5" id="prev-options"></div>

        <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200 text-emerald-900 mt-3">
          <span class="font-bold block text-xs mb-1"><i class="bi bi-lightbulb-fill text-emerald-600"></i> Tinjauan Edukasi &amp; Pembahasan:</span>
          <p class="leading-relaxed text-[11px]" id="prev-explanation"></p>
        </div>
      </div>

      <div class="pt-3 border-t border-slate-100 text-right">
        <button type="button" onclick="closePreviewModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl">
          Tutup Pratinjau
        </button>
      </div>
    </div>
    </div>
  </div>

  <!-- API Layer & Scripts -->
  <script src="../../js/api.js"></script>
  <script src="../../js/ui-kit.js"></script>
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

      // 2. Data Management: Case Studies
      let casesList = [];
      let currentView = 'table'; // 'table' or 'grid'

      async function loadCaseStudies() {
        try {
          const [casesRes, reportsRes] = await Promise.all([
            window.ApiService.getCaseStudies(),
            window.ApiService.getReports()
          ]);

          casesList = Array.isArray(casesRes) ? casesRes : (casesRes?.data || []);
          const reports = Array.isArray(reportsRes) ? reportsRes : (reportsRes?.data || []);

          const pendingCount = reports.filter(r => r.status === 'Pending' || r.status === 'Masuk').length;
          document.getElementById('badge-sidebar-reports').textContent = pendingCount;

          renderNotificationList(reports.slice(0, 4));
          renderCases();
        } catch (err) {
          console.error('Error load case studies:', err);
        }
      }

      window.setViewMode = function(mode) {
        currentView = mode;
        const btnTable = document.getElementById('btn-view-table');
        const btnGrid = document.getElementById('btn-view-grid');
        const viewTable = document.getElementById('view-table-container');
        const viewGrid = document.getElementById('view-grid-container');

        if (mode === 'table') {
          btnTable.className = 'p-1.5 rounded-lg text-xs font-semibold bg-white text-slate-900 shadow-sm transition-all';
          btnGrid.className = 'p-1.5 rounded-lg text-xs font-semibold text-slate-500 hover:text-slate-900 transition-all';
          viewTable.classList.remove('hidden');
          viewGrid.classList.add('hidden');
          viewGrid.classList.remove('grid');
        } else {
          btnGrid.className = 'p-1.5 rounded-lg text-xs font-semibold bg-white text-slate-900 shadow-sm transition-all';
          btnTable.className = 'p-1.5 rounded-lg text-xs font-semibold text-slate-500 hover:text-slate-900 transition-all';
          viewGrid.classList.remove('hidden');
          viewGrid.classList.add('grid');
          viewTable.classList.add('hidden');
        }
        renderCases();
      };

      document.getElementById('search-cases')?.addEventListener('input', renderCases);
      document.getElementById('filter-risk')?.addEventListener('change', renderCases);

      function getFilteredCases() {
        const q = (document.getElementById('search-cases').value || '').toLowerCase().trim();
        const risk = document.getElementById('filter-risk').value;

        return casesList.filter(c => {
          const matchQ = (c.title || '').toLowerCase().includes(q) ||
                         (c.sender || '').toLowerCase().includes(q) ||
                         (c.scenario || '').toLowerCase().includes(q) ||
                         (c.message || '').toLowerCase().includes(q);

          const cRisk = c.riskLevel || c.risk_level || 'Sedang';
          const matchRisk = !risk || cRisk.toLowerCase() === risk.toLowerCase();

          return matchQ && matchRisk;
        });
      }

      function renderCases() {
        const filtered = getFilteredCases();

        if (currentView === 'table') {
          renderTable(filtered);
        } else {
          renderGrid(filtered);
        }
      }

      // Render View 1: Data Table (Kolom: Judul Kasus, Tingkat Risiko, Tanggal, Aksi)
      function renderTable(list) {
        const tbody = document.getElementById('cases-table-body');
        if (list.length === 0) {
          tbody.innerHTML = `
            <tr>
              <td colspan="4" class="text-center py-8 text-slate-400 text-xs">
                Tidak ada studi kasus yang cocok dengan filter.
              </td>
            </tr>
          `;
          return;
        }

        tbody.innerHTML = list.map(c => {
          const risk = c.riskLevel || c.risk_level || 'Sedang';
          let riskBadge = '';
          if (risk === 'Kritis') {
            riskBadge = `<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Kritis</span>`;
          } else if (risk === 'Tinggi') {
            riskBadge = `<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Tinggi</span>`;
          } else {
            riskBadge = `<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Sedang</span>`;
          }

          return `
            <tr class="hover:bg-slate-50/75 transition-colors cursor-pointer group" onclick="openPreviewModal(${c.id})" title="Klik untuk pratinjau studi kasus: ${escapeHtml(c.title)}">
              <!-- 1. Kolom: Judul Kasus -->
              <td class="px-5 py-4 max-w-sm">
                <div class="font-display font-bold text-slate-900 text-xs sm:text-sm line-clamp-1 group-hover:text-emerald-700 transition-colors">
                  ${escapeHtml(c.title)}
                </div>
                <p class="text-[11px] text-slate-400 line-clamp-1 mt-0.5">${escapeHtml(c.message || c.scenario || '')}</p>
                <span class="text-[10px] text-slate-400 font-mono mt-0.5 block">Kanal: ${escapeHtml(c.sender || 'Online')}</span>
              </td>

              <!-- 2. Kolom: Tingkat Risiko -->
              <td class="px-4 py-4 whitespace-nowrap">
                ${riskBadge}
              </td>

              <!-- 3. Kolom: Tanggal -->
              <td class="px-4 py-4 whitespace-nowrap text-xs text-slate-500 font-mono">
                ${escapeHtml(c.date || '02 Nov 2026')}
              </td>

              <!-- 4. Kolom: Aksi -->
              <td class="px-5 py-4 whitespace-nowrap text-right" onclick="event.stopPropagation()">
                <div class="inline-flex items-center gap-1.5">
                  <button type="button" onclick="event.stopPropagation(); openPreviewModal(${c.id})" class="p-1.5 rounded-lg bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 transition-colors" title="Pratinjau Kuis">
                    <i class="bi bi-eye text-sm"></i>
                  </button>
                  <button type="button" onclick="event.stopPropagation(); openEditCaseModal(${c.id})" class="p-1.5 rounded-lg bg-slate-100 hover:bg-blue-50 text-slate-600 hover:text-blue-700 transition-colors" title="Edit Kasus">
                    <i class="bi bi-pencil-square text-sm"></i>
                  </button>
                  ${isSuper ? `
                    <button type="button" onclick="event.stopPropagation(); handleDeleteCase(${c.id})" class="p-1.5 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 transition-colors" title="Hapus Kasus">
                      <i class="bi bi-trash text-sm"></i>
                    </button>
                  ` : ''}
                </div>
              </td>
            </tr>
          `;
        }).join('');
      }

      // Render View 2: Grid Kartu
      function renderGrid(list) {
        const grid = document.getElementById('view-grid-container');
        if (list.length === 0) {
          grid.innerHTML = '<div class="col-span-2 text-center py-8 text-slate-400 text-xs">Tidak ada studi kasus yang cocok.</div>';
          return;
        }

        grid.innerHTML = list.map(c => {
          const risk = c.riskLevel || c.risk_level || 'Sedang';
          let riskBadge = '';
          if (risk === 'Kritis') {
            riskBadge = `<span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">Risiko Kritis</span>`;
          } else if (risk === 'Tinggi') {
            riskBadge = `<span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">Risiko Tinggi</span>`;
          } else {
            riskBadge = `<span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Risiko Sedang</span>`;
          }

          return `
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:border-emerald-500/40 hover:shadow-md transition-all flex flex-col justify-between cursor-pointer group hover:scale-[1.01] active:scale-[0.99]" onclick="openPreviewModal(${c.id})" title="Klik untuk pratinjau studi kasus: ${escapeHtml(c.title)}">
              <div>
                <div class="flex items-center justify-between gap-2 mb-2">
                  ${riskBadge}
                  <span class="text-xs text-slate-400 font-mono">${escapeHtml(c.date || '02 Nov 2026')}</span>
                </div>
                <h3 class="font-display font-bold text-slate-900 group-hover:text-emerald-700 transition-colors text-sm sm:text-base line-clamp-2">${escapeHtml(c.title)}</h3>
                <div class="mt-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200/60 text-xs text-slate-700">
                  <span class="font-bold block text-slate-500 text-[10px] uppercase mb-0.5">${escapeHtml(c.sender || 'Pesan Broadcast')}:</span>
                  <p class="italic line-clamp-3">"${escapeHtml(c.message || c.scenario || '')}"</p>
                </div>
              </div>

              <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs" onclick="event.stopPropagation()">
                <span class="text-slate-400 font-medium">Difficulty: ${escapeHtml(c.difficulty || 'Dasar')}</span>
                <div class="inline-flex items-center gap-1.5">
                  <button type="button" onclick="event.stopPropagation(); openPreviewModal(${c.id})" class="px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold transition-colors">
                    Pratinjau
                  </button>
                  <button type="button" onclick="event.stopPropagation(); openEditCaseModal(${c.id})" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition-colors">
                    Edit
                  </button>
                  ${isSuper ? `
                    <button type="button" onclick="event.stopPropagation(); handleDeleteCase(${c.id})" class="p-1.5 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-500 hover:text-rose-600 transition-colors">
                      <i class="bi bi-trash"></i>
                    </button>
                  ` : ''}
                </div>
              </div>
            </div>
          `;
        }).join('');
      }

      // Modal Tambah / Edit Case Handlers
      window.openAddCaseModal = function() {
        document.getElementById('modal-case-title').innerHTML = '<i class="bi bi-patch-plus text-emerald-600"></i><span>Tambah Studi Kasus &amp; Soal</span>';
        document.getElementById('form-case').reset();
        document.getElementById('case-id').value = '';
        document.getElementById('case-difficulty').value = 'Dasar';
        document.getElementById('case-risk').value = 'Kritis';

        document.getElementById('modal-case').classList.remove('hidden');
      };

      window.openEditCaseModal = function(id) {
        const item = casesList.find(c => Number(c.id) === Number(id));
        if (!item) return;

        document.getElementById('modal-case-title').innerHTML = '<i class="bi bi-pencil-square text-emerald-600"></i><span>Edit Studi Kasus &amp; Soal</span>';
        document.getElementById('case-id').value = item.id;
        document.getElementById('case-title').value = item.title || '';
        document.getElementById('case-risk').value = item.riskLevel || item.risk_level || 'Sedang';
        document.getElementById('case-difficulty').value = item.difficulty || 'Dasar';
        document.getElementById('case-sender').value = item.sender || '';
        document.getElementById('case-target-url').value = item.targetUrl || item.target_url || '';
        document.getElementById('case-scenario').value = item.message || item.scenario || '';
        document.getElementById('case-question').value = item.question || '';

        // Options A, B, C, D
        const opts = item.options || [];
        const optA = opts.find(o => o.key === 'A');
        const optB = opts.find(o => o.key === 'B');
        const optC = opts.find(o => o.key === 'C');
        const optD = opts.find(o => o.key === 'D');

        document.getElementById('case-opt-a').value = optA?.text || '';
        document.getElementById('case-opt-b').value = optB?.text || '';
        document.getElementById('case-opt-c').value = optC?.text || '';
        document.getElementById('case-opt-d').value = optD?.text || '';

        // Correct Option
        const correctOpt = opts.find(o => o.correct)?.key || 'B';
        const radio = document.querySelector(`input[name="correct_option"][value="${correctOpt}"]`);
        if (radio) radio.checked = true;

        document.getElementById('case-explanation').value = item.explanation || '';

        document.getElementById('modal-case').classList.remove('hidden');
      };

      window.closeCaseModal = function() {
        document.getElementById('modal-case').classList.add('hidden');
      };

      // Submit Save Case
      window.handleSaveCase = async function(e) {
        e.preventDefault();
        const id = document.getElementById('case-id').value;
        const title = document.getElementById('case-title').value.trim();
        const riskLevel = document.getElementById('case-risk').value;
        const difficulty = document.getElementById('case-difficulty').value;
        const sender = document.getElementById('case-sender').value.trim();
        const targetUrl = document.getElementById('case-target-url').value.trim();
        const scenario = document.getElementById('case-scenario').value.trim();
        const question = document.getElementById('case-question').value.trim();
        const explanation = document.getElementById('case-explanation').value.trim();

        const correctKey = document.querySelector('input[name="correct_option"]:checked')?.value || 'A';
        const options = [
          { key: 'A', text: document.getElementById('case-opt-a').value.trim(), correct: correctKey === 'A' },
          { key: 'B', text: document.getElementById('case-opt-b').value.trim(), correct: correctKey === 'B' },
          { key: 'C', text: document.getElementById('case-opt-c').value.trim(), correct: correctKey === 'C' },
          { key: 'D', text: document.getElementById('case-opt-d').value.trim(), correct: correctKey === 'D' },
        ];

        const payload = {
          title,
          riskLevel,
          difficulty,
          sender,
          targetUrl,
          message: scenario,
          scenario,
          question,
          options,
          explanation
        };

        const btn = document.getElementById('btn-save-case');
        btn.disabled = true;
        btn.textContent = 'Menyimpan...';

        try {
          if (id) {
            await window.ApiService.updateCaseStudy(id, payload);
          } else {
            await window.ApiService.createCaseStudy(payload);
          }

          closeCaseModal();
          await loadCaseStudies();
        } catch (err) {
          alert('Gagal menyimpan studi kasus: ' + err.message);
        } finally {
          btn.disabled = false;
          btn.textContent = 'Simpan Studi Kasus';
        }
      };

      const UI = window.AswajaUI;

      if (UI) {
        UI.enhanceModal(document.getElementById('modal-case'), {
          labelledBy: 'modal-case-title',
          initialFocus: '#case-title',
          onRequestClose: () => closeCaseModal()
        });
      }

      // Universal Reusable Delete Modal for Case Studies
      window.handleDeleteCase = async function(id) {
        if (!isSuper) {
          UI.notice({
            icon: 'warning',
            title: 'Akses Ditolak',
            message: 'Hanya Super Admin yang berwenang menghapus soal studi kasus.'
          });
          return;
        }

        const target = casesList.find(c => Number(c.id) === Number(id));
        const caseTitle = target ? target.title : `ID #${id}`;

        await UI.confirmDelete({
          type: 'studi kasus',
          name: caseTitle,
          successMessage: 'Soal studi kasus berhasil dihapus.',
          onDelete: async () => {
            await window.ApiService.deleteCaseStudy(id);
            const row = document.querySelector(`button[onclick*="handleDeleteCase(${id})"]`)?.closest('tr');
            if (row) {
              row.style.transition = 'opacity 250ms ease, transform 250ms ease';
              row.style.opacity = '0';
              row.style.transform = 'translateY(-6px)';
              await new Promise(r => setTimeout(r, 260));
            }
            await loadCaseStudies();
          }
        });
      };
      window.openPreviewModal = function(id) {
        const item = casesList.find(c => Number(c.id) === Number(id));
        if (!item) return;

        document.getElementById('prev-sender').textContent = `Pengirim: ${item.sender || 'Broadcast Simulasi'}`;
        document.getElementById('prev-message').textContent = `"${item.message || item.scenario || ''}"`;
        document.getElementById('prev-question').textContent = item.question || 'Apa tindakan Tabayyun Anda?';

        const optsContainer = document.getElementById('prev-options');
        optsContainer.innerHTML = (item.options || []).map(o => `
          <div class="p-2.5 rounded-xl border ${o.correct ? 'bg-emerald-50 border-emerald-300 text-emerald-950 font-semibold' : 'bg-slate-50 border-slate-200 text-slate-700'} flex items-start gap-2">
            <span class="w-5 h-5 rounded-full ${o.correct ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-700'} font-bold text-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">
              ${o.key}
            </span>
            <span class="flex-1">${escapeHtml(o.text)}</span>
            ${o.correct ? '<span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded border border-emerald-300 flex-shrink-0">Kunci Benar</span>' : ''}
          </div>
        `).join('');

        document.getElementById('prev-explanation').textContent = item.explanation || '-';

        document.getElementById('modal-preview-case').classList.remove('hidden');
      };

      window.closePreviewModal = function() {
        document.getElementById('modal-preview-case').classList.add('hidden');
      };

      function renderNotificationList(reports) {
        const container = document.getElementById('notif-list-container');
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
          document.getElementById('search-cases').value = q;
          renderCases();
        }
      };

      function escapeHtml(str) {
        return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
      }

      document.getElementById('btn-refresh-cases')?.addEventListener('click', loadCaseStudies);

      loadCaseStudies();
    });
  </script>
</body>
</html>

