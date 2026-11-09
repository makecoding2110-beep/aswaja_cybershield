<?php
  /**
   * Formulir Portal Lapor Siber - Aswaja CyberShield */
  $prefilledUrl = htmlspecialchars(trim($_GET['url'] ?? $_GET['q'] ?? ''), ENT_QUOTES, 'UTF-8');
  
  $kategoriList = [
      'Promosi Situs Judi Online/Slot',
      'Investasi Bodong & Trading Ilegal',
      'Domain Instansi Diretas',
      'Undangan Investasi Menyesatkan',
      'Iklan Cuan Kilat Bersyarat Deposit',
      'Lainnya'
  ];
?>
<!DOCTYPE html>

<html lang="id" class="scroll-smooth" data-theme="aswaja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Aswaja CyberShield - Platform edukasi literasi digital, verifikasi hoaks berbasis prinsip Tabayyun, dan penguatan keamanan siber masyarakat moderat.">
  <link rel="icon" type="image/svg+xml" href="../favicon.svg">
  <title>Lapor judol - Aswaja CyberShield</title>
  

  <!-- Google Fonts: Inter & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

  <!-- Google Material Symbols Outlined -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

  
    <!-- Theme Anti-FOUC Script -->
  <script>
    (function() {
      try {
        const savedTheme = localStorage.getItem('aswaja_theme');
        const isDark = savedTheme === 'dark' || (!savedTheme && localStorage.getItem('aswaja_darkmode') === '1') || (!savedTheme && !localStorage.getItem('aswaja_darkmode') && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
        if (isDark) {
          document.documentElement.classList.add('dark');
          document.documentElement.setAttribute('data-theme', 'dark');
        } else {
          document.documentElement.classList.remove('dark');
          document.documentElement.setAttribute('data-theme', 'light');
        }
      } catch (e) {}
    })();
  </script>

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
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/ui-kit.css">
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col selection:bg-emerald-100 selection:text-emerald-900">
  <!-- PAGE LOADING SCREEN -->
  <div id="page-loader" role="status" aria-label="Memuat halaman">
    <div class="loader-content">
      <div class="loader-logo-wrap">
        <img src="../logo-shield.png" alt="Aswaja CyberShield" class="loader-logo-img">
      </div>
      <div class="loader-bar-track">
        <div class="loader-bar-fill"></div>
      </div>
      <span class="loader-text">Memuat...</span>
    </div>
  </div>
  <header class="sticky top-0 z-50 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 transition-all duration-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-20">

      <!-- Logo & Brand Name -->
      <div class="flex items-center gap-2 sm:gap-3 xl:gap-8 shrink min-w-0">
        <a href="index.blade.php" class="flex items-center gap-2 sm:gap-2.5 xl:gap-3 group focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 rounded-lg py-1 min-w-0">
          <img src="../logo-shield.png" alt="Logo Resmi Aswaja CyberShield" class="w-8 h-8 sm:w-10 sm:h-10 object-contain group-hover:scale-105 transition-transform shrink-0">
          <div class="flex flex-col min-w-0">
            <span class="font-display font-bold text-base sm:text-lg text-slate-900 dark:text-white tracking-tight leading-tight group-hover:text-emerald-700 transition-colors truncate">
              Aswaja <span class="text-emerald-600">CyberShield</span>
            </span>
            <span class="text-[9px] sm:text-[10px] font-medium text-slate-500 dark:text-slate-400 tracking-wider uppercase truncate">Literasi & Keamanan Siber</span>
          </div>
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="hidden lg:flex items-center gap-1 xl:gap-1.5 shrink-0" aria-label="Navigasi Utama">
          <a href="index.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap transition-all duration-150 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800">
                Beranda
              </a><a href="tabayyun.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap transition-all duration-150 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800">
                AI Tabayyun
              </a><a href="etika.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap transition-all duration-150 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800">
                Etika Siber
              </a><a href="cyber-guard.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap transition-all duration-150 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800">
                Cyber Guard
              </a><a href="lapor.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap transition-all duration-150 text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 shadow-sm">
                Lapor
              </a>
        </nav>
      </div>

      <!-- Right Action Button & Mobile Hamburger -->
      <div class="flex items-center gap-1.5 sm:gap-2 xl:gap-3 shrink-0">
        <!-- Emergency Report CTA Button -->
        <a href="lapor.blade.php" class="inline-flex items-center gap-1.5 xl:gap-2 px-2.5 sm:px-3 xl:px-4 py-1.5 sm:py-2 rounded-xl bg-red-50 text-red-700 whitespace-nowrap shrink-0 dark:bg-red-950/50 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/50 border border-red-200/70 dark:border-red-800 text-xs sm:text-sm font-semibold transition-all duration-200 shadow-sm active:scale-95">
          <span class="material-symbols-outlined text-[16px] sm:text-[18px] text-red-600 dark:text-red-400">emergency</span>
          <span class="hidden sm:inline whitespace-nowrap">Lapor Darurat</span>
        </a>

        <!-- Primary CTA Button (Desktop) -->
        <a href="tabayyun.blade.php" class="hidden sm:inline-flex items-center gap-1.5 xl:gap-2 px-3 xl:px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs xl:text-sm font-semibold whitespace-nowrap shadow-sm shrink-0 shadow-emerald-600/20 transition-all duration-200 active:scale-95">
          <span class="material-symbols-outlined text-[18px]">bolt</span>
          <span class="whitespace-nowrap">Cek Fakta</span>
        </a>

        <!-- Dark Mode Toggle -->
        <button type="button" id="darkmode-toggle" aria-label="Ganti Mode Tampilan" class="p-1.5 sm:p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500">
          <span class="material-symbols-outlined text-lg sm:text-xl" id="darkmode-icon">dark_mode</span>
        </button>

        <!-- Mobile Hamburger Menu Button -->
        <button type="button" id="mobile-menu-btn" aria-label="Buka Menu Navigasi" aria-expanded="false" class="lg:hidden p-1.5 sm:p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500">
          <span class="material-symbols-outlined text-xl sm:text-2xl" id="menu-icon">menu</span>
        </button>
      </div>

    </div>
  </div>

  <!-- Mobile Navigation Drawer -->
  <div id="mobile-menu" class="hidden lg:hidden border-t border-slate-200/80 dark:border-slate-800 bg-white/98 dark:bg-slate-900/98 backdrop-blur-lg px-4 pt-3 pb-5 space-y-1.5 shadow-xl transition-all">
    <a href="index.blade.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-base font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800">
          <span class="material-symbols-outlined text-[20px]">home</span>
          <span>Beranda</span>
        </a><a href="tabayyun.blade.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-base font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800">
          <span class="material-symbols-outlined text-[20px]">fact_check</span>
          <span>AI Tabayyun</span>
        </a><a href="etika.blade.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-base font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800">
          <span class="material-symbols-outlined text-[20px]">menu_book</span>
          <span>Etika Siber</span>
        </a><a href="cyber-guard.blade.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-base font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800">
          <span class="material-symbols-outlined text-[20px]">security</span>
          <span>Cyber Guard</span>
        </a><a href="lapor.blade.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-base font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60">
          <span class="material-symbols-outlined text-[20px]">report_problem</span>
          <span>Lapor</span>
        </a>
    <div class="pt-2 border-t border-slate-100 dark:border-slate-800 flex flex-col gap-2">
      <button type="button" class="darkmode-toggle-btn w-full inline-flex items-center justify-between px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-medium text-sm transition-colors">
        <span class="inline-flex items-center gap-2">
          <span class="material-symbols-outlined text-[20px] darkmode-icon">dark_mode</span>
          <span class="darkmode-text">Ganti Mode Tampilan</span>
        </span>
        <span class="text-xs text-slate-400">Terang / Gelap</span>
      </button>

      <a href="tabayyun.blade.php" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 text-white font-semibold text-sm shadow-sm">
        <span class="material-symbols-outlined text-[18px]">bolt</span>
        <span>Mulai AI Tabayyun</span>
      </a>
    </div>
  </div>
</header>



  <main class="flex-grow relative overflow-hidden">
    <!-- Ambient Section Particles & Floating Orbs -->
    <div class="section-particles absolute inset-0 pointer-events-none overflow-hidden" aria-hidden="true"></div>
    <div class="absolute top-10 left-1/2 -translate-x-1/2 w-[600px] h-[350px] bg-emerald-400/15 blur-[100px] rounded-full pointer-events-none -z-10 animate-float"></div>
    <div class="absolute -top-20 right-10 w-96 h-96 bg-blue-400/15 blur-[90px] rounded-full pointer-events-none -z-10 animate-float-delayed"></div>
    
  <div class="py-12 md:py-16 relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Page Header -->
      <div class="text-center max-w-3xl mx-auto mb-12">
        <h1 class="font-display font-extrabold text-3xl sm:text-4xl text-slate-900 tracking-tight mb-3">
          Portal Lapor Judol & Siber
        </h1>
        <p class="text-slate-600 text-base leading-relaxed">
          Wadah partisipasi masyarakat untuk melaporkan tautan investasi menyesatkan, situs judol berkedok trading, dan promosi perjudian daring demi melindungi ekosistem finansial digital yang halal dan aman.
        </p>
      </div>

      <div class="max-w-4xl mx-auto space-y-10 sm:space-y-12">

        <!-- Form Column (Terpusat & Rapi) -->
        <section id="form-lapor" data-scroll-focus="#lapor-target" aria-labelledby="form-lapor-title" class="ui-card shadow-md p-6 sm:p-8 space-y-6">
          <div class="flex items-center justify-between gap-3 pb-4 border-b ui-divider">
            <div>
              <h2 id="form-lapor-title" class="font-display font-bold text-lg ui-heading">Formulir Aduan</h2>
              <p class="text-xs ui-muted">Identitas pelapor dijamin aman dan terlindungi</p>
            </div>
            <span class="ui-badge-soft shrink-0">
              <span class="material-symbols-outlined text-sm" aria-hidden="true">lock</span>
              <span>Kerahasiaan Aman</span>
            </span>
          </div>

          <!-- Indikator Batas Kuota Pelaporan (Rate Limiting: Max 2x / 2 Jam) -->
          <div id="quota-container" class="rounded-2xl border p-4 transition-all duration-300 bg-emerald-50/70 border-emerald-200/80 text-emerald-950 dark:bg-emerald-950/20 dark:border-emerald-800/60 dark:text-emerald-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
              <div class="flex items-center gap-3">
                <div id="quota-icon-wrap" class="w-9 h-9 rounded-xl bg-emerald-600/10 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300 flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-xl" aria-hidden="true">speed</span>
                </div>
                <div>
                  <div class="font-semibold text-xs sm:text-sm flex items-center gap-2">
                    <span>Kuota Pelaporan: <strong>Maksimal 2x / 2 Jam</strong></span>
                  </div>
                </div>
              </div>
              <div class="flex items-center gap-2 self-start sm:self-auto shrink-0">
                <span id="quota-badge" class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-600 text-white shadow-xs tracking-wide">
                  Sisa Kuota: 2 / 2
                </span>
              </div>
            </div>
            
            <!-- Dynamic Countdown Alert if Quota Exhausted -->
            <div id="quota-warning-box" class="hidden mt-3 pt-3 border-t border-emerald-200/60 dark:border-emerald-800/40 text-xs">
              <div class="flex items-start gap-2 text-amber-800 dark:text-amber-300 font-medium">
                <span class="material-symbols-outlined text-base shrink-0 mt-0.5">hourglass_top</span>
                <div>
                  <span id="quota-countdown-text">Batas kuota pelaporan tercapai. Anda dapat mengirimkan laporan kembali dalam <strong>2 jam</strong>.</span>
                </div>
              </div>
            </div>
          </div>

          <!-- novalidate: validasi ditangani JS agar error tampil lewat pop up/toast bertema -->
          <form id="form-lapor-siber" class="space-y-5" novalidate>
            <div>
              <label for="lapor-nama" class="ui-label">Nama Pelapor (Opsional/Boleh Samaran):</label>
              <input type="text" id="lapor-nama" name="nama" maxlength="100" autocomplete="off" placeholder="Anonim / Boleh Samaran" class="ui-input">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 stagger-children">
              <div>
                <label for="lapor-kategori" class="ui-label">Kategori Laporan: <span class="ui-required" aria-hidden="true">*</span></label>
                <select id="lapor-kategori" name="kategori" required aria-required="true" class="ui-input">
                  <option value="">-- Pilih Kategori Laporan --</option>
                  <?php foreach ($kategoriList as $kat): ?>
                    <option value="<?= $kat ?>"><?= $kat ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div>
                <label for="lapor-target" class="ui-label">URL Tautan Mencurigakan yang Dilaporkan: <span class="ui-required" aria-hidden="true">*</span></label>
                <input type="url" id="lapor-target" name="url" required aria-required="true" inputmode="url" autocomplete="off" value="<?= $prefilledUrl ?>" placeholder="https://contoh-investasi-judol.xyz/slot" class="ui-input">
              </div>
            </div>

            <div>
              <label for="lapor-deskripsi" class="ui-label">Kronologi Kejadian (Opsional):</label>
              <textarea id="lapor-deskripsi" name="deskripsi" rows="4" maxlength="5000" placeholder="Ceritakan ringkas bagaimana Anda menemukan tautan ini, apakah ada tawaran profit/cuan, desakan deposit, atau rekrutmen referal yang mencurigakan..." class="ui-input resize-none"></textarea>
            </div>

            <!-- Upload Area -->
            <div>
              <span id="upload-label" class="ui-label">Unggah Bukti Tangkapan Layar atau Dokumen (Opsional):</span>
              <div id="upload-dropzone" class="ui-dropzone" role="button" tabindex="0" aria-labelledby="upload-label" aria-describedby="upload-help">
                <span class="material-symbols-outlined text-3xl ui-muted mb-1" aria-hidden="true">upload_file</span>
                <div class="text-xs font-medium ui-text">Klik untuk memilih file tangkapan layar atau seret ke area ini</div>
                <span id="upload-help" class="text-[11px] ui-muted block mt-1">Format JPG, PNG, atau PDF (Maks. 5 MB)</span>
                <input type="file" id="file-upload" name="evidence" class="hidden" accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf" tabindex="-1">
              </div>
              <div id="file-preview" class="hidden mt-2">
                <div class="ui-file-chip">
                  <span id="file-name" class="font-medium truncate max-w-xs">bukti_screenshot.png</span>
                  <button type="button" id="btn-remove-file" class="font-semibold underline" style="color:var(--color-danger-text)">Hapus</button>
                </div>
              </div>
            </div>

            <div class="pt-2">
              <button type="submit" id="btn-submit-lapor" class="ui-btn ui-btn--danger w-full py-3.5 shadow-md">
                <span class="material-symbols-outlined text-[18px]" aria-hidden="true">send</span>
                <span id="btn-submit-lapor-text">Kirim Laporan Tabayyun</span>
              </button>
            </div>

            <!-- Transparansi Penggunaan Layanan Pihak Ketiga -->
            <div class="ui-note">
              <span class="material-symbols-outlined text-lg shrink-0 mt-0.5" style="color:var(--color-primary)" aria-hidden="true">info</span>
              <div class="text-[11px] leading-relaxed ui-text">
                <strong class="font-semibold ui-heading">Transparansi Penggunaan:</strong> Laporan yang Anda kirimkan akan diteruskan ke <strong>sistem penanganan aduan</strong> untuk diproses dan ditindaklanjuti. Identitas pelapor <strong>tetap aman dan terjaga (anonim)</strong>. Informasi ini semata-mata digunakan untuk membantu mencegah penipuan agar tidak memakan korban lain.
              </div>
            </div>
          </form>
        </section>

        <!-- Tabel Rekapitulasi Laporan Publik -->
        <section id="rekap-laporan" class="ui-card shadow-sm p-6 sm:p-8 ui-scroll-target mt-10 sm:mt-12" aria-labelledby="rekap-title">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
          <div>
            <h2 id="rekap-title" class="font-display font-bold text-lg ui-heading">Rekapitulasi Pelaporan Publik</h2>
            <p class="text-xs ui-muted">Transparansi penanganan aduan masyarakat yang telah disamarkan demi privasi.</p>
          </div>
          <div class="w-full sm:w-64">
            <label for="table-search-input" class="sr-only">Cari status atau tiket</label>
            <input type="search" id="table-search-input" placeholder="Cari status atau tiket..." class="ui-input" style="padding:.5rem .875rem;font-size:.75rem">
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs sm:text-sm">
            <caption class="sr-only">Daftar laporan publik yang telah disamarkan</caption>
            <thead class="ui-table-head">
              <tr>
                <th scope="col" class="py-3 px-4 font-semibold">No. Tiket</th>
                <th scope="col" class="py-3 px-4 font-semibold">Kategori &amp; Objek Laporan</th>
                <th scope="col" class="py-3 px-4 font-semibold">Status Penanganan</th>
                <th scope="col" class="py-3 px-4 font-semibold">Waktu</th>
              </tr>
            </thead>
            <tbody id="table-reports-body" aria-live="polite">
              <tr>
                <td colspan="4" class="py-8 px-4 text-center text-xs ui-muted">Memuat data laporan…</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      </div>

    </div>
  </div>

  

  <!-- Modal Penjelasan 4 Alasan Batasan Kuota Pelaporan (Maksimal 2x / 2 Jam) -->
  <div id="modal-quota-reasons" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="modal-quota-title">
    <div class="min-h-full flex items-center justify-center p-4">
      <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl max-w-xl w-full p-6 sm:p-7 shadow-2xl space-y-5 animate-scale-up">
        <div class="flex items-start justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 flex items-center justify-center shrink-0">
              <span class="material-symbols-outlined text-2xl">policy</span>
            </div>
            <div>
              <h3 id="modal-quota-title" class="font-display font-bold text-base sm:text-lg text-slate-900 dark:text-white">
                Mengapa Pelaporan Dibatasi 2x / 2 Jam?
              </h3>
              <p class="text-xs text-slate-500 dark:text-slate-400">Kebijakan ketahanan digital & integritas investigasi aduan.</p>
            </div>
          </div>
          <button type="button" id="btn-close-quota-modal" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer" aria-label="Tutup modal">
            <span class="material-symbols-outlined text-xl">close</span>
          </button>
        </div>

        <div class="space-y-3 text-xs sm:text-[13px] text-slate-600 dark:text-slate-300">
          <!-- Poin 1 -->
          <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex items-start gap-3">
            <span class="material-symbols-outlined text-lg text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5">security</span>
            <div>
              <strong class="font-bold text-slate-900 dark:text-white block mb-0.5">1. Mencegah Serangan Spam dan DDoS</strong>
              <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                Membatasi frekuensi kiriman (request) mencegah pihak tidak bertanggung jawab atau bot otomatis membanjiri server dengan ribuan laporan palsu dalam waktu singkat yang bisa membuat website menjadi lambat atau tumbang (down).
              </p>
            </div>
          </div>

          <!-- Poin 2 -->
          <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex items-start gap-3">
            <span class="material-symbols-outlined text-lg text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5">checklist</span>
            <div>
              <strong class="font-bold text-slate-900 dark:text-white block mb-0.5">2. Efisiensi Validasi Tim Penyelidik</strong>
              <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                Menghindari penumpukan laporan duplikat atau berulang-ulang untuk masalah yang sama. Hal ini membantu tim admin atau investigator fokus memproses laporan yang benar-benar valid dan unik.
              </p>
            </div>
          </div>

          <!-- Poin 3 -->
          <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex items-start gap-3">
            <span class="material-symbols-outlined text-lg text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5">cloud_done</span>
            <div>
              <strong class="font-bold text-slate-900 dark:text-white block mb-0.5">3. Optimalisasi Bandwidth dan Penyimpanan</strong>
              <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                Menghemat ruang penyimpanan basis data (database) server dari data sampah atau spam yang tidak perlu.
              </p>
            </div>
          </div>

          <!-- Poin 4 -->
          <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex items-start gap-3">
            <span class="material-symbols-outlined text-lg text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5">rate_review</span>
            <div>
              <strong class="font-bold text-slate-900 dark:text-white block mb-0.5">4. Mendorong Kualitas Laporan</strong>
              <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                Memastikan pengguna lebih berhati-hati, detail, dan serius saat menyusun isi laporan mereka karena adanya keterbatasan kuota pengiriman.
              </p>
            </div>
          </div>
        </div>

        <div class="pt-2 flex justify-end">
          <button type="button" id="btn-dismiss-quota-modal" class="px-5 py-2.5 rounded-xl font-semibold text-xs bg-emerald-600 hover:bg-emerald-700 text-white transition-colors shadow-sm cursor-pointer">
            Saya Mengerti
          </button>
        </div>
      </div>
    </div>
  </div>

  </main>

  <footer class="bg-gradient-to-b from-[#064e3b] via-[#022c22] to-[#011a14] dark:bg-none dark:bg-black dark:from-black dark:via-black dark:to-black text-emerald-100/90 dark:text-slate-300 border-t border-emerald-900/40 dark:border-slate-800 mt-0">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-10">

      <!-- Column 1: Brand Info -->
      <div class="md:col-span-6 space-y-4">
        <div class="flex items-center gap-2 xl:gap-3 shrink-0">
          <img src="../logo-shield.png" alt="Logo Resmi Aswaja CyberShield" class="w-9 h-9 object-contain">
          <span class="font-display font-bold text-xl text-white tracking-tight">Aswaja CyberShield</span>
        </div>
        <p class="text-sm text-emerald-100/80 dark:text-slate-400 leading-relaxed max-w-md" align="justify">
          Inisiatif ketahanan digital berbasis prinsip Tabayyun dan nilai Ahlussunnah wal Jama'ah untuk mendeteksi serta membentengi masyarakat dari bahaya judi online, penipuan finansial, dan jebakan investasi ilegal.
        </p>
        <div class="pt-2 text-xs text-emerald-300 dark:text-emerald-400 font-medium">
          Amanah &bull; Tabayyun &bull; Maslahah Ummah
        </div>
      </div>

      <!-- Column 2: Quick Links -->
      <div class="md:col-span-3 space-y-3">
        <h4 class="font-display font-semibold text-sm text-white uppercase tracking-wider">Navigasi Utama</h4>
        <ul class="space-y-2 text-sm">
          <li><a href="index.blade.php" class="text-emerald-200/90 hover:text-white dark:text-slate-300 dark:hover:text-emerald-400 transition-colors">Beranda Utama</a></li>
          <li><a href="tabayyun.blade.php" class="text-emerald-200/90 hover:text-white dark:text-slate-300 dark:hover:text-emerald-400 transition-colors">Analisis Tautan Tabayyun</a></li>
          <li><a href="etika.blade.php" class="text-emerald-200/90 hover:text-white dark:text-slate-300 dark:hover:text-emerald-400 transition-colors">Etika Siber</a></li>
          <li><a href="cyber-guard.blade.php" class="text-emerald-200/90 hover:text-white dark:text-slate-300 dark:hover:text-emerald-400 transition-colors">Cyber Guard</a></li>
        </ul>
      </div>

      <!-- Column 3: Layanan -->
      <div class="md:col-span-3 space-y-3">
        <h4 class="font-display font-semibold text-sm text-white uppercase tracking-wider">Layanan</h4>
        <ul class="space-y-2 text-sm">
          <li><a href="lapor.blade.php" class="text-emerald-200/90 hover:text-white dark:text-slate-300 dark:hover:text-emerald-400 transition-colors">Lapor</a></li>
        </ul>
      </div>

    </div>
  </div>
</footer>
  

  <script src="../js/navbar.js"></script>
  <script src="../js/scroll-reveal.js"></script>
  <script src="../js/api.js"></script>
  <script src="../js/ui-kit.js"></script>
  <script src="../js/lapor.js"></script>
  <script src="../js/animations.js"></script>
</body>
</html>









