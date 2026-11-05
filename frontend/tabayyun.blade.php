<!DOCTYPE html>
<html lang="id" class="scroll-smooth" data-theme="aswaja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="AI Tabayyun - Analisis dan verifikasi mandiri tautan investasi menyesatkan yang berpotensi mengarah pada perjudian daring (judol).">
  <link rel="icon" type="image/svg+xml" href="../favicon.svg">
  <title>Analisis Tautan Investasi &amp; Judol - AI Tabayyun</title>
  
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
        } else {
          document.documentElement.classList.remove('dark');
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
      <div class="flex items-center gap-3 xl:gap-8 shrink-0">
        <a href="index.blade.php" class="flex items-center gap-2.5 xl:gap-3 group focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 rounded-lg py-1 shrink-0">
          <img src="../logo-shield.png" alt="Logo Resmi Aswaja CyberShield" class="w-10 h-10 object-contain group-hover:scale-105 transition-transform">
          <div class="flex flex-col">
            <span class="font-display font-bold text-lg text-slate-900 dark:text-white tracking-tight leading-tight group-hover:text-emerald-700 transition-colors">
              Aswaja <span class="text-emerald-600">CyberShield</span>
            </span>
            <span class="text-[10px] font-medium text-slate-500 dark:text-slate-400 tracking-wider uppercase">Literasi & Keamanan Siber</span>
          </div>
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="hidden lg:flex items-center gap-1 xl:gap-1.5 shrink-0" aria-label="Navigasi Utama">
          <a href="index.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap transition-all duration-150 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800">
                Beranda
              </a><a href="tabayyun.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap transition-all duration-150 text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 shadow-sm">
                AI Tabayyun
              </a><a href="etika.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap transition-all duration-150 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800">
                Etika Siber
              </a><a href="cyber-guard.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap transition-all duration-150 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800">
                Cyber Guard
              </a><a href="lapor.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap transition-all duration-150 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800">
                Lapor
              </a>
        </nav>
      </div>

      <!-- Right Action Button & Mobile Hamburger -->
      <div class="flex items-center gap-2 xl:gap-3 shrink-0">
        <!-- Emergency Report CTA Button -->
        <a href="lapor.blade.php" class="inline-flex items-center gap-1.5 xl:gap-2 px-3 xl:px-4 py-2 rounded-xl bg-red-50 text-red-700 whitespace-nowrap shrink-0 dark:bg-red-950/50 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/50 border border-red-200/70 dark:border-red-800 text-sm font-semibold transition-all duration-200 shadow-sm active:scale-95">
          <span class="material-symbols-outlined text-[18px] text-red-600 dark:text-red-400">emergency</span>
          <span class="hidden sm:inline whitespace-nowrap">Lapor Darurat</span>
        </a>

        <!-- Primary CTA Button (Desktop) -->
        <a href="tabayyun.blade.php" class="hidden sm:inline-flex items-center gap-1.5 xl:gap-2 px-3 xl:px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs xl:text-sm font-semibold whitespace-nowrap shadow-sm shrink-0 shadow-emerald-600/20 transition-all duration-200 active:scale-95">
          <span class="material-symbols-outlined text-[18px]">bolt</span>
          <span class="whitespace-nowrap">Cek Fakta</span>
        </a>

        <!-- Dark Mode Toggle -->
        <button type="button" id="darkmode-toggle" aria-label="Ganti Mode Tampilan" class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500">
          <span class="material-symbols-outlined text-xl" id="darkmode-icon">dark_mode</span>
        </button>

        <!-- Mobile Hamburger Menu Button -->
        <button type="button" id="mobile-menu-btn" aria-label="Buka Menu Navigasi" aria-expanded="false" class="lg:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500">
          <span class="material-symbols-outlined text-2xl" id="menu-icon">menu</span>
        </button>
      </div>

    </div>
  </div>

  <!-- Mobile Navigation Drawer -->
  <div id="mobile-menu" class="hidden lg:hidden border-t border-slate-200/80 dark:border-slate-800 bg-white/98 dark:bg-slate-900/98 backdrop-blur-lg px-4 pt-3 pb-5 space-y-1.5 shadow-xl transition-all">
    <a href="index.blade.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-base font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800">
          <span class="material-symbols-outlined text-[20px]">home</span>
          <span>Beranda</span>
        </a><a href="tabayyun.blade.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-base font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60">
          <span class="material-symbols-outlined text-[20px]">fact_check</span>
          <span>AI Tabayyun</span>
        </a><a href="etika.blade.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-base font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800">
          <span class="material-symbols-outlined text-[20px]">menu_book</span>
          <span>Etika Siber</span>
        </a><a href="cyber-guard.blade.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-base font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800">
          <span class="material-symbols-outlined text-[20px]">security</span>
          <span>Cyber Guard</span>
        </a><a href="lapor.blade.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-base font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800">
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



  <main class="flex-grow">
    
  <div class="py-10 md:py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Page Header -->
      <div class="text-center max-w-3xl mx-auto mb-10">
        <h1 class="font-display font-extrabold text-3xl sm:text-4xl text-slate-900 tracking-tight mb-3">
          Analisis Tautan Investasi &amp; Judi Online
        </h1>
        <p class="text-slate-600 text-base leading-relaxed">
          Uji keabsahan alamat web penawaran investasi, robot trading, arisan kilat, atau grup finansial yang dicurigai sebagai kedok judi online (<em>maysir modern</em>).
        </p>
      </div>

      <!-- 2-Column Responsive Layout (Penuh & Seimbang) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Kolom Kiri: Form Input & Hasil Analisis (lg:col-span-8) -->
        <div class="lg:col-span-8 space-y-6">

          <!-- Main Checker Card (Input State) -->
          <div class="bg-white rounded-2xl shadow-md border border-slate-200/90 p-6 sm:p-8">

            <!-- Title & Mode Info -->
            <div class="flex items-center justify-between border-b border-slate-200/80 pb-4 mb-6">
              <div class="flex items-center gap-2">
                <span class="p-2 rounded-xl bg-emerald-50 text-emerald-700">
                  <span class="material-symbols-outlined text-xl">link</span>
                </span>
                <div>
                  <h3 class="font-display font-bold text-base text-slate-900">Pemeriksaan URL / Tautan Mencurigakan</h3>
                  <p class="text-xs text-slate-500">Mendeteksi indikasi pengalihan deposit ke jaringan perjudian daring</p>
                </div>
              </div>

            </div>

            <!-- Analysis Form -->
            <form id="tabayyun-analysis-form" class="space-y-4">
              <div>
                <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                  <label for="checker-input-content" class="font-semibold text-slate-700 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-emerald-600 text-[16px]">link</span>
                    <span>Masukkan URL Tautan yang Ingin Diperiksa: <span class="text-red-500">*</span></span>
                  </label>
                  <span id="char-counter" class="font-mono text-slate-400">0 / 500 Karakter</span>
                </div>
                <div class="relative rounded-xl border border-slate-300 focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-500/20 transition-all bg-slate-50/50">
                  <input type="url" id="checker-input-content" maxlength="500" required placeholder="https://contoh-investasi-trading-cuan.xyz/register" value="https://cuan-trading-harian-vip88.vip/deposit-slot" class="w-full px-4 py-3.5 bg-transparent text-slate-800 text-sm sm:text-base outline-none leading-relaxed placeholder:text-slate-400 font-mono">
                  <div class="flex items-center justify-between px-4 py-2.5 bg-slate-100/70 border-t border-slate-200/80 text-xs">
                    <div class="flex items-center gap-1.5 text-slate-500">
                      <span class="material-symbols-outlined text-emerald-600 text-[16px]">shield</span>
                      <span>Input diperlakukan sebagai data tidak tepercaya &bull; Tidak dibuka otomatis</span>
                    </div>
                    <button type="button" id="btn-clear-input" class="text-slate-500 hover:text-red-600 flex items-center gap-1 font-medium transition-colors">
                      <span class="material-symbols-outlined text-[16px]">delete_sweep</span>
                      <span>Bersihkan</span>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Action Trigger Row -->
              <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-3">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                  <span class="material-symbols-outlined text-emerald-600 text-[18px]">verified</span>
                  <span>Parameter: Audit Sanad Domain, Deteksi Skema Maysir, &amp; Pola Deposit.</span>
                </div>
                <button type="submit" id="btn-submit-tabayyun" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-md shadow-emerald-600/20 hover:shadow-lg transition-all flex items-center justify-center gap-2 active:scale-95">
                  <span class="material-symbols-outlined text-[18px]">bolt</span>
                  <span id="btn-submit-text">Mulai Analisis Tautan</span>
                </button>
              </div>

              <!-- Label Simulasi & Transparansi Pihak Ketiga -->
              <div class="mt-4 p-3.5 rounded-xl bg-blue-50/70 border border-blue-200/80 space-y-1.5">
                <div class="flex items-start gap-2.5">
                  <span class="material-symbols-outlined text-blue-600 text-lg shrink-0 mt-0.5">info</span>
                  <p class="text-[11px] text-blue-900/80 leading-relaxed">
                    <strong class="font-semibold text-blue-900">Transparansi Analisis &amp; Keamanan:</strong> Tautan yang Anda uji diperiksa menggunakan mesin analitik keamanan siber. Demi keselamatan Anda, sistem <strong>tidak pernah mengeksekusi skrip atau mengunduh berkas</strong> dari tautan tersebut. Jangan pernah menyetorkan uang atau mengisi kata sandi pribadi pada tautan yang belum terverifikasi.
                  </p>
                </div>
              </div>
            </form>

            <!-- 3 Parameter Evaluasi Fiqih & Forensik -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-6 border-t border-slate-200/80 mt-6 stagger-children">
              <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70 flex items-start gap-2.5">
                <span class="material-symbols-outlined text-emerald-600 text-xl shrink-0 mt-0.5">travel_explore</span>
                <div>
                  <h4 class="font-display font-bold text-xs text-slate-900">Audit Sanad Domain</h4>
                  <p class="text-[11px] text-slate-500 mt-0.5">Pengecekan umur domain, otoritas registrasi, dan izin OJK/Bappebti.</p>
                </div>
              </div>
              <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70 flex items-start gap-2.5">
                <span class="material-symbols-outlined text-emerald-600 text-xl shrink-0 mt-0.5">casino</span>
                <div>
                  <h4 class="font-display font-bold text-xs text-slate-900">Deteksi Maysir (Judi)</h4>
                  <p class="text-[11px] text-slate-500 mt-0.5">Mendeteksi roda spin, tebak angka, dan spekulasi berkedok trading.</p>
                </div>
              </div>
              <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70 flex items-start gap-2.5">
                <span class="material-symbols-outlined text-emerald-600 text-xl shrink-0 mt-0.5">account_balance_wallet</span>
                <div>
                  <h4 class="font-display font-bold text-xs text-slate-900">Jebakan Deposit</h4>
                  <p class="text-[11px] text-slate-500 mt-0.5">Menganalisis penguncian penarikan dana bersyarat setoran berulang.</p>
                </div>
              </div>
            </div>

          </div>

          <!-- Loading State Indicator (Hidden by default) -->
          <div id="tabayyun-loading-state" class="hidden bg-white rounded-2xl border border-slate-200 p-8 text-center space-y-4">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-emerald-50 text-emerald-600">
              <span class="material-symbols-outlined text-3xl animate-spin">sync</span>
            </div>
            <div>
              <h4 class="font-display font-bold text-base text-slate-900" id="loading-step-title">Memeriksa Sanad Domain &amp; Legalitas...</h4>
              <p class="text-xs text-slate-500 mt-1">Mencocokkan parameter dengan basis data CSIRT dan pola heuristik judi online</p>
            </div>
            <div class="w-full max-w-md mx-auto bg-slate-100 rounded-full h-2 overflow-hidden">
              <div class="bg-emerald-600 h-2 rounded-full transition-all duration-300" id="loading-progress-bar" style="width: 40%"></div>
            </div>
          </div>

          <!-- Result Box (State 2: Analisis) -->
          <div id="tabayyun-result-card" data-alert="red" class="bg-red-50/80 dark:bg-red-950/30 rounded-2xl border border-red-200/90 dark:border-red-800/60 p-6 sm:p-8 shadow-sm space-y-6">

            <!-- Result Header -->
            <div id="result-header-divider" class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-red-200/60 dark:border-red-800/40">
              <div class="flex items-start sm:items-center gap-3">
                <div id="result-icon-box" class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900/60 text-red-700 dark:text-red-300 flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-2xl" id="result-icon">warning</span>
                </div>
                <div>
                  <div class="flex items-center gap-2">
                    <span id="result-category-badge" class="badge px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-red-200 dark:bg-red-800/80 text-red-900 dark:text-red-100">
                      Terindikasi Judi Online
                    </span>
                    <span class="text-xs text-slate-500 dark:text-slate-400" id="result-scanned-time">Waktu Pindai: Barusan (Realtime)</span>
                  </div>
                  <h3 id="result-main-title" class="font-display font-bold text-xl text-red-950 dark:text-red-100 mt-1">
                    Waspada: Terindikasi Tautan Investasi Palsu Mengarah ke Judi Online
                  </h3>
                </div>
              </div>

              <!-- Score Pill -->
              <div id="result-score-pill" class="result-inner-box bg-white dark:bg-slate-800 px-4 py-2 rounded-xl shadow-xs border border-red-200/70 dark:border-red-800/50 flex items-center gap-3 self-end sm:self-auto">
                <div class="text-right">
                  <span class="text-[11px] text-slate-400 dark:text-slate-400 uppercase tracking-wider block">Tingkat Keaslian</span>
                  <span id="result-score-text" class="font-display font-bold text-lg text-red-700 dark:text-red-300">5%</span>
                </div>
                <div id="result-score-icon-box" class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-950/70 flex items-center justify-center text-red-700 dark:text-red-300">
                  <span class="material-symbols-outlined text-[18px]">gpp_bad</span>
                </div>
              </div>
            </div>

            <!-- Safe URL Target Monospace Display -->
            <div id="result-url-box" class="result-inner-box p-3 bg-white dark:bg-slate-800 rounded-xl border border-red-200/60 dark:border-red-800/50 text-xs">
              <span class="text-slate-400 dark:text-slate-400 font-semibold uppercase tracking-wider block mb-1">Tautan yang Diuji (Tidak Dieksekusi):</span>
              <div id="result-target-url" class="font-mono text-red-800 dark:text-red-200 break-all select-all font-semibold">https://cuan-trading-harian-vip88.vip/deposit-slot</div>
            </div>

            <!-- Verified Facts Breakdown -->
            <div id="result-findings-box" class="result-inner-box bg-white dark:bg-slate-800 rounded-xl p-5 border border-red-100 dark:border-red-800/50 space-y-3">
              <h4 class="font-display font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-[18px]">checklist_rtl</span>
                <span>Temuan Analisis Tautan &amp; Alasan Verifikasi:</span>
              </h4>
              <ul id="result-findings-list" class="space-y-2.5 text-sm text-slate-700 dark:text-slate-200">
                <li class="flex items-start gap-2.5">
                  <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-[18px] shrink-0 mt-0.5">domain_disabled</span>
                  <span><strong>Anomali Domain &amp; Kedok Usaha:</strong> Menggunakan nama berbau trading, namun parameter URL mengarahkan ke sistem deposit dan putaran taruhan judi online (slot/kasino).</span>
                </li>
                <li class="flex items-start gap-2.5">
                  <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-[18px] shrink-0 mt-0.5">psychology_alt</span>
                  <span><strong>Manipulasi Psikologis:</strong> Menjanjikan passive income instan dan profit pasti yang melanggar prinsip fiqih muamalah serta regulasi OJK/Bappebti.</span>
                </li>
                <li class="flex items-start gap-2.5">
                  <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-[18px] shrink-0 mt-0.5">verified</span>
                  <span><strong>Rekomendasi Tindakan:</strong> Jangan lakukan registrasi atau transfer dana. Laporkan tautan ini ke portal aduan Aswaja CyberShield untuk ditindaklanjuti.</span>
                </li>
              </ul>
            </div>

            <!-- Islamic Guidance Quote -->
            <div id="result-quote-box" class="result-inner-box p-4 rounded-xl bg-white/70 dark:bg-slate-800/80 border border-red-200/60 dark:border-red-800/50">
              <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-emerald-700 dark:text-emerald-400 text-xl shrink-0 mt-0.5">balance</span>
                <div>
                  <blockquote class="text-sm font-serif italic text-slate-800 dark:text-slate-200 mb-1">
                    “Wahai orang-orang yang beriman, janganlah kamu saling memakan harta sesamamu dengan jalan yang batil...”
                  </blockquote>
                  <cite class="text-xs text-slate-500 dark:text-slate-400 font-sans not-italic font-medium">
                    (QS. An-Nisa: 29 &bull; Prinsip Kehalalan Transaksi Finansial dan Larangan Maysir)
                  </cite>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
              <div class="flex flex-wrap items-center gap-3">
                <button type="button" id="btn-copy-summary" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm font-semibold border border-slate-200 dark:border-slate-700 shadow-sm transition-all">
                  <span class="material-symbols-outlined text-[16px]">content_copy</span>
                  <span id="copy-btn-text">Salin Ringkasan Verifikasi</span>
                </button>
                <a href="lapor.blade.php" id="btn-forward-report" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-red-100/80 dark:bg-red-950/60 hover:bg-red-100 dark:hover:bg-red-900/60 text-red-800 dark:text-red-200 text-sm font-semibold transition-all">
                  <span class="material-symbols-outlined text-[16px]">campaign</span>
                  <span>Laporkan Tautan Ini</span>
                </a>
              </div>
              <button type="button" id="btn-reset-checker" class="text-sm font-semibold text-emerald-700 dark:text-emerald-400 hover:underline">
                Uji Tautan Lain &rarr;
              </button>
            </div>

          </div>

        </div>

        <!-- Kolom Kanan: Sidebar Panduan & Aksi Cepat (lg:col-span-4) -->
        <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-28">

          <!-- Widget 1: Red Flags Tautan Investasi & Judol -->
          <div class="card-hover-effect bg-amber-50/80 dark:bg-amber-950/30 p-5 rounded-2xl border border-amber-200/80 dark:border-amber-800/60 shadow-sm space-y-3">
            <div class="flex items-center gap-2 text-amber-900 dark:text-amber-200 font-bold text-sm">
              <span class="material-symbols-outlined text-amber-600 dark:text-amber-400">warning</span>
              <span>5 Red Flag Tautan Investasi Judol</span>
            </div>
            <ul class="space-y-2 text-xs text-amber-950/90 dark:text-amber-100/90">
              <li class="flex items-start gap-2">
                <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-[16px] shrink-0 mt-0.5">priority_high</span>
                <span><strong>Janji Profit Pasti:</strong> "Cuan 30%/hari", "auto profit", atau "robot trading anti rugi".</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-[16px] shrink-0 mt-0.5">priority_high</span>
                <span><strong>Sistem Deposit Bersyarat:</strong> Penarikan baru bisa setelah deposit minimal atau referal tertentu.</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-[16px] shrink-0 mt-0.5">priority_high</span>
                <span><strong>Domain Tidak Resmi:</strong> URL menggunakan ekstensi .vip, .cc, .xyz tanpa izin OJK/Bappebti.</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-[16px] shrink-0 mt-0.5">priority_high</span>
                <span><strong>Kata Kunci Tersembunyi:</strong> Path URL mengandung /slot, /spin, /casino, /bet tersembunyi di balik nama trading.</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-[16px] shrink-0 mt-0.5">priority_high</span>
                <span><strong>Rekrutmen Referal Agresif:</strong> Bonus besar jika mengajak anggota baru — ciri skema ponzi/MLM judol.</span>
              </li>
            </ul>
          </div>

          <!-- Widget 2: Pusat Aduan Lapor Darurat -->
          <div class="card-hover-effect bg-red-50/80 dark:bg-red-950/30 p-5 rounded-2xl border border-red-200/80 dark:border-red-800/60 space-y-2.5">
            <div class="flex items-center gap-2 text-red-700 dark:text-red-300 font-bold text-sm">
              <span class="material-symbols-outlined text-lg">emergency</span>
              <span>Pusat Aduan Siber</span>
            </div>
            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
              Menemukan nomor rekening penipu atau tautan mencurigakan? Laporkan segera ke tim verifikasi.
            </p>
            <a href="lapor.blade.php" class="inline-flex items-center gap-1.5 text-xs font-bold text-red-700 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 pt-1">
              <span>Buka Formulir Laporan</span>
              <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
            </a>
          </div>

        </aside>

      </div>

    </div>
  </div>

  </main>

  <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 mt-0">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-10">

      <!-- Column 1: Brand Info -->
      <div class="md:col-span-6 space-y-4">
        <div class="flex items-center gap-2 xl:gap-3 shrink-0">
          <img src="../logo-shield.png" alt="Logo Resmi Aswaja CyberShield" class="w-9 h-9 object-contain">
          <span class="font-display font-bold text-xl text-white tracking-tight">Aswaja CyberShield</span>
        </div>
        <p class="text-sm text-slate-400 leading-relaxed max-w-md">
          Inisiatif ketahanan digital berbasis prinsip Tabayyun dan nilai Ahlussunnah wal Jama'ah untuk mendeteksi serta membentengi masyarakat dari bahaya judi online, penipuan finansial, dan jebakan investasi ilegal.
        </p>
        <div class="pt-2 text-xs text-emerald-400 font-medium">
          Amanah &bull; Tabayyun &bull; Maslahah Ummah
        </div>
      </div>

      <!-- Column 2: Quick Links -->
      <div class="md:col-span-3 space-y-3">
        <h4 class="font-display font-semibold text-sm text-white uppercase tracking-wider">Navigasi Utama</h4>
        <ul class="space-y-2 text-sm">
          <li><a href="index.blade.php" class="hover:text-emerald-400 transition-colors">Beranda Utama</a></li>
          <li><a href="tabayyun.blade.php" class="hover:text-emerald-400 transition-colors">Analisis Tautan Tabayyun</a></li>
          <li><a href="etika.blade.php" class="hover:text-emerald-400 transition-colors">Etika Digital</a></li>
          <li><a href="cyber-guard.blade.php" class="hover:text-emerald-400 transition-colors">Cyber Guard Test (Kuis)</a></li>
        </ul>
      </div>

      <!-- Column 3: Layanan Aduan -->
      <div class="md:col-span-3 space-y-3">
        <h4 class="font-display font-semibold text-sm text-white uppercase tracking-wider">Layanan Terpadu</h4>
        <ul class="space-y-2 text-sm">
          <li><a href="lapor.blade.php" class="hover:text-red-400 transition-colors">Portal Lapor</a></li>
          <li><a href="etika.blade.php" class="hover:text-emerald-400 transition-colors">Pedoman Literasi</a></li>
          <li><span class="text-slate-500">Protokol Fiqih Informasi Digital</span></li>
        </ul>
      </div>

    </div>
  </div>
</footer>
  

  <script src="../js/api.js"></script>
  <script src="../js/navbar.js"></script>
  <script src="../js/scroll-reveal.js"></script>
  <script src="../js/tabayyun.js"></script>
  <script src="../js/animations.js"></script>
</body>
</html>








