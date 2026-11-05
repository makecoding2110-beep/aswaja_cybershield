<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <script src="../../js/admin-guard.js"></script>
  <title>Manajemen Akun Admin - Aswaja CyberShield</title>
  <link rel="icon" type="image/svg+xml" href="../../favicon.svg">

  <!-- Google Fonts: Inter & Plus Jakarta Sans -->
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

          <!-- 3. Riwayat Pelaporan -->
          <a href="laporan.blade.php"
            class="flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/70 transition-all group">
            <span class="flex items-center gap-3">
              <i
                class="bi bi-shield-exclamation text-base text-slate-400 group-hover:text-emerald-400 transition-colors"></i>
              <span>Laporan</span>
            </span>
            <span id="badge-sidebar-reports"
              class="bg-rose-500/20 text-rose-400 text-xs px-2 py-0.5 rounded-full font-bold border border-rose-500/30">0</span>
          </a>

          <!-- 4. Studi Kasus -->
          <a href="studi-kasus.blade.php"
            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/70 transition-all group">
            <i class="bi bi-journal-check text-base text-slate-400 group-hover:text-emerald-400 transition-colors"></i>
            <span>Studi Kasus</span>
          </a>

          <!-- 5. Integrasi Dual AI -->
          <a href="integrasi-ai.blade.php"
            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/70 transition-all group">
            <i class="bi bi-cpu text-base text-slate-400 group-hover:text-emerald-400 transition-colors"></i>
            <span>Integrasi Dual AI</span>
          </a>

          <!-- 6. Kelola Akun (Active) -->
          <a href="akun.blade.php"
            class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl bg-emerald-600 text-white shadow-md shadow-emerald-950/30 transition-all">
            <i class="bi bi-people text-base text-white"></i>
            <span>Kelola Akun</span>
          </a>
        </div>
      </div>

      <!-- Sidebar Bottom / User Bar -->
      <div class="p-3 border-t border-slate-800/80 bg-slate-950/40">
        <div
          class="flex items-center justify-between px-2.5 py-2 rounded-xl bg-slate-800/60 border border-slate-700/50">
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

        <button type="button" id="btn-refresh-admins"
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
              <span class="font-display font-bold text-sm text-slate-800">Notifikasi</span>
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

      <!-- Header Halaman + Tombol "Tambah Akun Baru" -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-display font-extrabold text-slate-900 tracking-tight">Manajemen Akun Pengguna &amp;
            Admin</h1>
          <p class="text-sm text-slate-500 mt-0.5">Kelola pengguna administrator, peran hak akses (Super Admin vs Admin
            Basic), dan status keaktifan staf.</p>
        </div>
        <button type="button" id="btn-add-account" onclick="openAddAdminModal()"
          class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-sm shadow-emerald-600/20 transition-all active:scale-[0.98]">
          <i class="bi bi-person-plus text-base"></i>
          <span>Tambah Akun Baru</span>
        </button>
      </div>

      <!-- Warning Notice for Basic Admin -->
      <div id="basic-admin-notice" class="hidden">
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-start gap-3">
          <i class="bi bi-shield-exclamation text-amber-600 text-lg flex-shrink-0"></i>
          <div>
            <strong class="font-bold">Perhatian Hak Akses:</strong>
            <span> Anda masuk sebagai <strong>Admin Basic</strong>. Penambahan, pengeditan staf lain, dan penghapusan
              akun dibatasi khusus bagi <strong>Super Admin</strong>. Anda dapat melihat informasi profil akun Anda di
              bawah ini.</span>
          </div>
        </div>
      </div>

      <!-- Search & Filter Bar -->
      <div
        class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
        <div class="relative flex-1 max-w-md">
          <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
          <input type="search" name="q_admin" id="search-admin" placeholder="Cari nama, email, atau username staf..."
            class="w-full pl-9 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-slate-800 placeholder-slate-400">
        </div>
        <div class="flex items-center gap-2">
          <span class="text-xs font-semibold text-slate-500">Total Akun:</span>
          <span id="admin-badge-count"
            class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">0</span>
        </div>
      </div>

      <!-- Data Table Pengguna / Admin -->
      <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-sm text-slate-600">
            <thead
              class="bg-slate-50 text-[11px] font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
              <tr>
                <th class="px-5 py-3.5">Nama</th>
                <th class="px-4 py-3.5">Email</th>
                <th class="px-4 py-3.5">Role / Peran</th>
                <th class="px-4 py-3.5">Status Keaktifan</th>
                <th class="px-5 py-3.5 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody id="admins-table-body" class="divide-y divide-slate-100">
              <tr>
                <td colspan="5" class="text-center py-8 text-slate-400 text-xs">
                  <div
                    class="inline-block w-5 h-5 border-2 border-emerald-500 border-t-transparent rounded-full animate-spin mb-2">
                  </div>
                  <p>Memuat data pengguna...</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Matrix Hak Akses (Panduan Hak Akses Sistem) -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Super Admin Privileges -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm space-y-2">
          <div class="flex items-center gap-2">
            <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
              <i class="bi bi-shield-lock-fill text-sm"></i>
            </span>
            <h3 class="font-display font-bold text-sm text-slate-900">Peran: Super Admin </h3>
          </div>
          <p class="text-xs text-slate-500 leading-relaxed">
            Memiliki wewenang mutlak untuk mengelola seluruh data platform: menambah, mengedit, dan menghapus artikel,
            studi kasus, riwayat aduan, serta menetapkan peran staf admin lain.
          </p>
        </div>

        <!-- Admin Basic Privileges -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm space-y-2">
          <div class="flex items-center gap-2">
            <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
              <i class="bi bi-shield text-sm"></i>
            </span>
            <h3 class="font-display font-bold text-sm text-slate-900">Peran: Admin Basic </h3>
          </div>
          <p class="text-xs text-slate-500 leading-relaxed">
            Dapat melakukan input konten edukasi, mengelola soal simulasi Cyber Guard, dan memverifikasi laporan
            masyarakat. Tidak memiliki izin menghapus data permanen maupun mengubah hak akses akun staf lain.
          </p>
        </div>
      </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/90 py-4 px-6 text-center text-xs text-slate-500 mt-auto">
      <div class="flex flex-col sm:flex-row items-center justify-center gap-2 max-w-7xl mx-auto">
        <p>&copy; 2026 <strong class="font-semibold text-slate-700">Aswaja CyberShield</strong> &bull; Manajemen Akun
          &amp; Keamanan</p>
      </div>
    </footer>
  </div>

  <!-- Modal Tambah / Edit Akun Pengguna (Semantic Laravel-Ready Form) -->
  <div id="modal-admin" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="min-h-full flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 my-8">

        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
          <h4 id="modal-admin-title" class="font-display font-bold text-base text-slate-900 flex items-center gap-2">
            <i class="bi bi-person-plus text-emerald-600"></i>
            <span>Tambah Akun Pengguna Baru</span>
          </h4>
          <button type="button" onclick="closeAdminModal()" class="text-slate-400 hover:text-slate-700 p-1 rounded-lg">
            <i class="bi bi-x-lg text-lg"></i>
          </button>
        </div>

        <!-- Semantic Form with Proper Name Attributes -->
        <form id="form-admin" method="POST" action="/admin/admins" onsubmit="handleSaveAdmin(event)">
          <!-- CSRF & Hidden ID -->
          <input type="hidden" name="_token" value="csrf_token_placeholder">
          <input type="hidden" name="admin_id" id="form-admin-id">

          <div class="space-y-4 text-xs">
            <!-- Nama Lengkap -->
            <div>
              <label for="admin-name-input" class="block font-semibold text-slate-700 mb-1">
                Nama Lengkap <span class="text-rose-500">*</span>
              </label>
              <input type="text" name="name" id="admin-name-input" required placeholder="Contoh: Gus Ahmad Fauzi, S.Kom"
                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800">
            </div>

            <!-- Username & Email -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label for="admin-username-input" class="block font-semibold text-slate-700 mb-1">
                  Username Login <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="username" id="admin-username-input" required placeholder="ahmad_fauzi"
                  class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800 font-mono text-xs">
              </div>

              <div>
                <label for="admin-email-input" class="block font-semibold text-slate-700 mb-1">
                  Email Resmi <span class="text-rose-500">*</span>
                </label>
                <input type="email" name="email" id="admin-email-input" required placeholder="fauzi@aswajacyber.id"
                  class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800">
              </div>
            </div>

            <!-- Role / Peran & Status Keaktifan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label for="admin-role-input" class="block font-semibold text-slate-700 mb-1">
                  Role / Peran <span class="text-rose-500">*</span>
                </label>
                <select name="role" id="admin-role-input" required
                  class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800 font-semibold">
                  <option value="Admin Basic">Admin Basic</option>
                  <option value="Super Admin">Super Admin</option>
                </select>
              </div>

              <div>
                <label for="admin-status-input" class="block font-semibold text-slate-700 mb-1">
                  Status Keaktifan <span class="text-rose-500">*</span>
                </label>
                <select name="status" id="admin-status-input" required
                  class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800 font-semibold">
                  <option value="Aktif">Aktif</option>
                  <option value="Nonaktif">Nonaktif</option>
                </select>
              </div>
            </div>

            <!-- Password (Opsional saat edit) -->
            <div>
              <label for="admin-password-input" class="block font-semibold text-slate-700 mb-1">
                Kata Sandi (Password) <span id="pw-required-label" class="text-rose-500">*</span>
              </label>
              <input type="password" name="password" id="admin-password-input" placeholder="Minimal 8 karakter..."
                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800">
              <span id="pw-hint" class="text-[10px] text-slate-400 block mt-1">Kosongkan jika tidak ingin mengubah kata
                sandi saat edit.</span>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="pt-5 mt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
            <button type="button" onclick="closeAdminModal()"
              class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition-colors">
              Batal
            </button>
            <button type="submit" id="btn-save-admin"
              class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-sm transition-all active:scale-[0.98]">
              Simpan Akun
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

      // Check permissions notice
      if (!isSuper) {
        document.getElementById('basic-admin-notice')?.classList.remove('hidden');
        document.getElementById('btn-add-account')?.classList.add('hidden');
      }

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

      // 2. Data Management: Admins
      let adminsList = [];

      async function loadAdmins() {
        try {
          const [adminsRes, reportsRes] = await Promise.all([
            window.ApiService.getAdmins(),
            window.ApiService.getReports()
          ]);

          adminsList = Array.isArray(adminsRes) ? adminsRes : (adminsRes?.data || []);
          const reports = Array.isArray(reportsRes) ? reportsRes : (reportsRes?.data || []);

          const pendingCount = reports.filter(r => r.status === 'Pending' || r.status === 'Masuk').length;
          document.getElementById('badge-sidebar-reports').textContent = pendingCount;
          document.getElementById('admin-badge-count').textContent = `${adminsList.length} Akun`;

          renderNotificationList(reports.slice(0, 4));
          renderAdminsTable();
        } catch (err) {
          console.error('Error load admins:', err);
          document.getElementById('admins-table-body').innerHTML = `
            <tr><td colspan="5" class="text-center py-8 text-rose-500 text-xs">Gagal memuat data pengguna: ${err.message}</td></tr>
          `;
        }
      }

      document.getElementById('search-admin')?.addEventListener('input', renderAdminsTable);

      function renderAdminsTable() {
        const query = (document.getElementById('search-admin').value || '').toLowerCase().trim();
        const filtered = adminsList.filter(a => {
          return (a.name || '').toLowerCase().includes(query) ||
            (a.email || '').toLowerCase().includes(query) ||
            (a.username || '').toLowerCase().includes(query) ||
            (a.role || '').toLowerCase().includes(query);
        });

        const tbody = document.getElementById('admins-table-body');
        if (filtered.length === 0) {
          tbody.innerHTML = `
            <tr>
              <td colspan="5" class="text-center py-8 text-slate-400 text-xs">
                Tidak ada pengguna yang cocok dengan pencarian.
              </td>
            </tr>
          `;
          return;
        }

        tbody.innerHTML = filtered.map(a => {
          const isSuperRole = a.role === 'Super Admin' || a.role === 'Superadmin';
          const roleBadge = isSuperRole
            ? `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-300"><i class="bi bi-shield-check"></i> Super Admin</span>`
            : `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-300"><i class="bi bi-shield"></i> Admin Basic</span>`;

          const isActive = a.status === 'Aktif' || a.status === 'active';
          const statusBadge = isActive
            ? `<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif</span>`
            : `<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 border border-slate-200"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Nonaktif</span>`;

          const avatarInit = (a.name || 'A').charAt(0).toUpperCase();

          return `
            <tr class="hover:bg-slate-50/75 transition-colors">
              <!-- 1. Kolom: Nama -->
              <td class="px-5 py-4 whitespace-nowrap">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center border border-slate-200 flex-shrink-0">
                    ${avatarInit}
                  </div>
                  <div>
                    <div class="font-display font-bold text-xs sm:text-sm text-slate-900">${escapeHtml(a.name)}</div>
                    <div class="font-mono text-[11px] text-slate-400">@${escapeHtml(a.username || '-')}</div>
                  </div>
                </div>
              </td>

              <!-- 2. Kolom: Email -->
              <td class="px-4 py-4 whitespace-nowrap">
                <span class="text-xs font-medium text-slate-700 flex items-center gap-1.5">
                  <i class="bi bi-envelope text-slate-400"></i>
                  ${escapeHtml(a.email || '-')}
                </span>
              </td>

              <!-- 3. Kolom: Role / Peran -->
              <td class="px-4 py-4 whitespace-nowrap">
                ${roleBadge}
              </td>

              <!-- 4. Kolom: Status Keaktifan -->
              <td class="px-4 py-4 whitespace-nowrap">
                ${statusBadge}
              </td>

              <!-- 5. Kolom: Aksi (Edit / Hapus) -->
              <td class="px-5 py-4 whitespace-nowrap text-right">
                <div class="inline-flex items-center gap-1.5">
                  ${(isSuper || currentUser.id === a.id) ? `
                    <button type="button" onclick="openEditAdminModal(${a.id})" class="p-1.5 rounded-lg bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 transition-colors" title="Edit Akun">
                      <i class="bi bi-pencil-square text-sm"></i>
                    </button>
                  ` : ''}
                  ${(isSuper && currentUser.id !== a.id) ? `
                    <button type="button" onclick="handleDeleteAdmin(${a.id})" class="p-1.5 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 transition-colors" title="Hapus Akun">
                      <i class="bi bi-trash text-sm"></i>
                    </button>
                  ` : ''}
                </div>
              </td>
            </tr>
          `;
        }).join('');
      }

      // Modal Handlers
      window.openAddAdminModal = function () {
        if (!isSuper) {
          alert('Akses Ditolak: Hanya Super Admin yang berwenang menambah akun.');
          return;
        }

        document.getElementById('modal-admin-title').innerHTML = '<i class="bi bi-person-plus text-emerald-600"></i><span>Tambah Akun Pengguna Baru</span>';
        document.getElementById('form-admin').reset();
        document.getElementById('form-admin-id').value = '';
        document.getElementById('admin-password-input').required = true;
        document.getElementById('pw-required-label').style.display = 'inline';
        document.getElementById('pw-hint').style.display = 'none';

        document.getElementById('modal-admin').classList.remove('hidden');
      };

      window.openEditAdminModal = function (id) {
        const item = adminsList.find(a => Number(a.id) === Number(id));
        if (!item) return;

        document.getElementById('modal-admin-title').innerHTML = '<i class="bi bi-person-gear text-emerald-600"></i><span>Edit Akun Pengguna</span>';
        document.getElementById('form-admin-id').value = item.id;
        document.getElementById('admin-name-input').value = item.name || '';
        document.getElementById('admin-username-input').value = item.username || '';
        document.getElementById('admin-email-input').value = item.email || '';
        document.getElementById('admin-role-input').value = (item.role === 'Superadmin' || item.role === 'Super Admin') ? 'Super Admin' : 'Admin Basic';
        document.getElementById('admin-status-input').value = item.status || 'Aktif';
        document.getElementById('admin-password-input').value = '';
        document.getElementById('admin-password-input').required = false;
        document.getElementById('pw-required-label').style.display = 'none';
        document.getElementById('pw-hint').style.display = 'block';

        // Non-super admins cannot change roles or statuses
        if (!isSuper) {
          document.getElementById('admin-role-input').disabled = true;
          document.getElementById('admin-status-input').disabled = true;
        } else {
          document.getElementById('admin-role-input').disabled = false;
          document.getElementById('admin-status-input').disabled = false;
        }

        document.getElementById('modal-admin').classList.remove('hidden');
      };

      window.closeAdminModal = function () {
        document.getElementById('modal-admin').classList.add('hidden');
      };

      // Submit Save Admin
      window.handleSaveAdmin = async function (e) {
        e.preventDefault();
        const id = document.getElementById('form-admin-id').value;
        const name = document.getElementById('admin-name-input').value.trim();
        const username = document.getElementById('admin-username-input').value.trim();
        const email = document.getElementById('admin-email-input').value.trim();
        const role = document.getElementById('admin-role-input').value;
        const status = document.getElementById('admin-status-input').value;
        const password = document.getElementById('admin-password-input').value;

        const btn = document.getElementById('btn-save-admin');
        btn.disabled = true;
        btn.textContent = 'Menyimpan...';

        try {
          const payload = { name, username, email, role, status };
          if (password) payload.password = password;

          if (id) {
            await window.ApiService.updateAdmin(id, payload);
          } else {
            await window.ApiService.createAdmin(payload);
          }

          closeAdminModal();
          await loadAdmins();
        } catch (err) {
          alert('Gagal menyimpan data pengguna: ' + err.message);
        } finally {
          btn.disabled = false;
          btn.textContent = 'Simpan Akun';
        }
      };

      // Delete Admin
      window.handleDeleteAdmin = async function (id) {
        if (!isSuper) {
          alert('Akses Ditolak: Hanya Super Admin yang berwenang menghapus akun.');
          return;
        }

        const item = adminsList.find(a => Number(a.id) === Number(id));
        if (confirm(`Apakah Anda yakin ingin menghapus akun ${item?.name || 'ini'}? Tindakan ini permanen.`)) {
          try {
            await window.ApiService.deleteAdmin(id);
            await loadAdmins();
          } catch (err) {
            alert('Gagal menghapus akun: ' + err.message);
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
          document.getElementById('search-admin').value = q;
          renderAdminsTable();
        }
      };

      function escapeHtml(str) {
        return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
      }

      document.getElementById('btn-refresh-admins')?.addEventListener('click', loadAdmins);

      loadAdmins();
    });
  </script>
</body>

</html>