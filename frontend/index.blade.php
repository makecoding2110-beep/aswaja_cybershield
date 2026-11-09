<!DOCTYPE html>
<html lang="id" class="scroll-smooth" data-theme="aswaja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Aswaja CyberShield - Platform edukasi literasi digital, deteksi tautan investasi menyesatkan yang mengarah pada judi online (judol), dan penguatan ketahanan siber masyarakat.">
  <link rel="icon" type="image/svg+xml" href="../favicon.svg">
  <title>Aswaja CyberShield - Edukasi & Pengawasan Tautan Investasi & Judi Online</title>
  

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
  <link rel="stylesheet" href="../css/style.css?v=20261109v4">
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
          <a href="index.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap transition-all duration-150 text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 shadow-sm">
                Beranda
              </a><a href="tabayyun.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap transition-all duration-150 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800">
                AI Tabayyun
              </a><a href="etika.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap transition-all duration-150 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800">
                Edukasi Siber
              </a><a href="cyber-guard.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap transition-all duration-150 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800">
                Cyber Guard
              </a><a href="lapor.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap transition-all duration-150 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800">
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
    <a href="index.blade.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-base font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60">
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
    
  
  <div class="relative overflow-hidden pt-12 pb-20 md:pt-16 md:pb-28">
    <!-- Section Ambient Particles -->
    <div class="section-particles absolute inset-0 pointer-events-none overflow-hidden" aria-hidden="true"></div>
    <!-- Floating Ambient Glow Effects -->
    <div class="absolute top-10 left-1/2 -translate-x-1/2 w-[600px] h-[350px] bg-emerald-400/15 blur-[100px] rounded-full pointer-events-none -z-10 animate-float"></div>
    <div class="absolute -top-20 right-10 w-96 h-96 bg-blue-400/15 blur-[90px] rounded-full pointer-events-none -z-10 animate-float-delayed"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col items-center text-center">

        <!-- Main Headline -->
        <h1 class="font-display font-extrabold text-4xl sm:text-5xl lg:text-6xl text-slate-900 tracking-tight leading-[1.15] mb-6 reveal-up" style="transition-delay:80ms">
          Waspadai Jebakan <br>
          <span class="hero-brand-shimmer">Investasi Palsu &amp; Judol.</span>
        </h1>

        <!-- Subheadline -->
        <p class="text-lg sm:text-xl text-slate-600 leading-relaxed max-w-2xl mb-8">
          Platform edukasi, verifikasi tautan, dan pelaporan mandiri untuk melindungi umat dan masyarakat dari modus tautan investasi menyesatkan yang berujung pada praktik judi online (<em>maysir modern</em>).
        </p>

        <!-- CTA Button Row -->
        <div class="relative z-20 flex flex-wrap items-center justify-center gap-4 w-full sm:w-auto mb-10">
          <a href="tabayyun.blade.php#analisis-tautan" class="btn-shimmer w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-xl text-white font-semibold text-base shadow-lg shadow-emerald-600/30 hover:shadow-xl hover:shadow-emerald-600/40 hover:-translate-y-0.5 transition-all duration-200 active:scale-[0.98]">
            <span class="material-symbols-outlined text-xl">link</span>
            <span>Mulai AI Tabayyun</span>
          </a>
          <a href="etika.blade.php" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-xl bg-white hover:bg-slate-50 hover:-translate-y-0.5 text-slate-700 font-semibold text-base border border-slate-300/80 shadow-sm transition-all duration-200">
            <span class="material-symbols-outlined text-xl text-slate-500">menu_book</span>
            <span>Pelajari Edukasi Siber</span>
          </a>
        </div>

        <!-- Metric Stats Strip -->
        <div class="w-full max-w-2xl grid grid-cols-3 gap-6 pt-6 border-t border-slate-200/80 stagger-children mx-auto text-center">
          <div>
            <div class="font-display font-bold text-2xl sm:text-3xl text-slate-900" data-counter="99.4" data-counter-suffix="%">99.4%</div>
            <div class="text-xs sm:text-sm text-slate-500 mt-0.5">Akurasi Verifikasi Tautan</div>
          </div>
          <div>
            <div id="stat-total-tautan" class="font-display font-bold text-2xl sm:text-3xl text-slate-900" data-counter="41" data-counter-suffix="+" data-counter-duration="1500">41+</div>
            <div class="text-xs sm:text-sm text-slate-500 mt-0.5">Tautan Dianalisis &amp; Dilaporkan</div>
          </div>
          <div>
            <div class="font-display font-bold text-2xl sm:text-3xl text-emerald-600" data-counter="100" data-counter-suffix="%">100%</div>
            <div class="text-xs sm:text-sm text-slate-500 mt-0.5">Akses Edukasi Terbuka</div>
          </div>
        </div>

      </div>
    </div>
  </div>
  

  
  <!-- 3 Pendekatan Utama: Recognize, Learn, Act -->
  <div class="py-16 relative overflow-hidden">
    <!-- Section Ambient Particles -->
    <div class="section-particles absolute inset-0 pointer-events-none overflow-hidden" aria-hidden="true"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center max-w-3xl mx-auto mb-12">
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-300 bg-emerald-100/70 dark:bg-emerald-950/70 px-3 py-1 rounded-full border border-emerald-200/60 dark:border-emerald-800">Metodologi Perlindungan Umat</span>
        <h2 class="font-display font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white tracking-tight mt-3 mb-4">
          Tiga Pendekatan Utama Aswaja CyberShield
        </h2>
        <p class="text-slate-600 dark:text-slate-300 text-base leading-relaxed">
          Kerangka kerja komprehensif untuk membendung penyebaran tautan investasi palsu dan sindikat judi online berkedok finansial.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- 1. Recognize -->
        <div data-href="tabayyun.blade.php" role="link" tabindex="0" class="card-hover-effect cursor-pointer p-8 rounded-2xl bg-gradient-to-b from-emerald-50/50 to-white dark:from-slate-800 dark:to-slate-850 border border-emerald-100 dark:border-slate-700 shadow-sm flex flex-col justify-between">
          <div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center mb-6 shadow-md shadow-emerald-600/20">
              <span class="material-symbols-outlined text-3xl">troubleshoot</span>
            </div>
            <div class="flex items-center gap-2 mb-2">
              <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">Tahap 1</span>
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Identifikasi</span>
            </div>
            <h3 class="font-display font-bold text-xl text-slate-900 dark:text-white mb-3">1. Recognize (Kenali)</h3>
            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" align="justify">
              Membantu pengguna mendeteksi dan menguji tautan mencurigakan secara mandiri. Mengidentifikasi ciri khas domain penipuan, skema robot trading bodong, dan jebakan tautan slot online sebelum terlanjur diklik.
            </p>
          </div>
          <div class="pt-6 mt-6 border-t border-emerald-100/80 dark:border-slate-700">
            <a href="tabayyun.blade.php" class="inline-flex items-center gap-2 text-sm font-bold text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300">
              <span>Uji Tautan Sekarang</span>
              <span class="material-symbols-outlined text-lg">arrow_forward</span>
            </a>
          </div>
        </div>

        <!-- 2. Learn -->
        <div data-href="etika.blade.php" role="link" tabindex="0" class="card-hover-effect cursor-pointer p-8 rounded-2xl bg-gradient-to-b from-blue-50/50 to-white dark:from-slate-800 dark:to-slate-850 border border-blue-100 dark:border-slate-700 shadow-sm flex flex-col justify-between">
          <div>
            <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center mb-6 shadow-md shadow-blue-600/20">
              <span class="material-symbols-outlined text-3xl">menu_book</span>
            </div>
            <div class="flex items-center gap-2 mb-2">
              <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-950/80 dark:text-blue-300">Tahap 2</span>
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Literasi</span>
            </div>
            <h3 class="font-display font-bold text-xl text-slate-900 dark:text-white mb-3">2. Learn (Edukasi)</h3>
            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" align="justify">
              Memberikan wawasan fiqih muamalah maaliyah, hukum maysir &amp; gharar modern, serta membedah psikologi rekayasa sosial di balik iming-iming cuan instan agar masyarakat memiliki imunitas digital.
            </p>
          </div>
          <div class="pt-6 mt-6 border-t border-blue-100/80 dark:border-slate-700">
            <a href="etika.blade.php" class="inline-flex items-center gap-2 text-sm font-bold text-blue-700 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
              <span>Baca Modul Edukasi</span>
              <span class="material-symbols-outlined text-lg">arrow_forward</span>
            </a>
          </div>
        </div>

        <!-- 3. Act -->
        <div data-href="lapor.blade.php" role="link" tabindex="0" class="card-hover-effect cursor-pointer p-8 rounded-2xl bg-gradient-to-b from-red-50/50 to-white dark:from-slate-800 dark:to-slate-850 border border-red-100 dark:border-slate-700 shadow-sm flex flex-col justify-between">
          <div>
            <div class="w-14 h-14 rounded-2xl bg-red-600 text-white flex items-center justify-center mb-6 shadow-md shadow-red-600/20">
              <span class="material-symbols-outlined text-3xl">campaign</span>
            </div>
            <div class="flex items-center gap-2 mb-2">
              <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 dark:bg-red-950/80 dark:text-red-300">Tahap 3</span>
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Aksi Nyata</span>
            </div>
            <h3 class="font-display font-bold text-xl text-slate-900 dark:text-white mb-3">3. Act (Tindakan)</h3>
            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" align="justify">
              Mengarahkan pengguna untuk melaporkan tautan mencurigakan ke portal aduan siber yang terhubung dengan mekanisme verifikasi, rekomendasi pemblokiran domain, dan langkah perlindungan rekening warga.
            </p>
          </div>
          <div class="pt-6 mt-6 border-t border-red-100/80 dark:border-slate-700">
            <a href="lapor.blade.php" class="inline-flex items-center gap-2 text-sm font-bold text-red-700 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
              <span>Laporkan Tautan</span>
              <span class="material-symbols-outlined text-lg">arrow_forward</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Empat Pilar Modul Platform -->
  <div class="py-16 relative overflow-hidden">
    <!-- Section Ambient Particles -->
    <div class="section-particles absolute inset-0 pointer-events-none overflow-hidden" aria-hidden="true"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center max-w-3xl mx-auto mb-12">
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-300 bg-emerald-100/70 dark:bg-emerald-950/70 px-3 py-1 rounded-full border border-emerald-200/60 dark:border-emerald-800">Fitur &amp; Modul Platform</span>
        <h2 class="font-display font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white tracking-tight mt-3 mb-4">
          Edukasi Terpadu
        </h2>
        <p class="text-slate-600 dark:text-slate-300 text-base leading-relaxed">
          Menggabungkan ketajaman analisis tautan siber dengan kearifan fiqih informasi Islam moderat.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 stagger-children">

        <!-- Pilar 1 -->
        <div data-href="tabayyun.blade.php" role="link" tabindex="0" class="reveal-on-scroll card-hover-effect p-6 rounded-2xl bg-white dark:bg-slate-800/90 border border-slate-200/80 dark:border-slate-700 shadow-sm flex flex-col justify-between group">
          <div>
            <div class="flex items-center justify-between mb-5">
              <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                <span class="material-symbols-outlined text-2xl">fact_check</span>
              </div>
              <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/70 px-2 py-0.5 rounded border border-emerald-100 dark:border-emerald-800">Recognize</span>
            </div>
            <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white mb-2 group-hover:text-emerald-700 dark:group-hover:text-emerald-400 transition-colors">Analisis Tautan Tabayyun</h3>
            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" align="justify">
              Pemeriksaan format URL dan indikasi situs investasi bodong, kloning domain, serta jebakan setoran taruhan judi online.
            </p>
          </div>
          <div class="mt-8 pt-4 border-t border-slate-100 dark:border-slate-700">
            <a href="tabayyun.blade.php" class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-600 dark:text-emerald-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-300">
              <span>Cek Tautan Sekarang</span>
              <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
          </div>
        </div>

        <!-- Pilar 2 -->
        <div data-href="etika.blade.php" role="link" tabindex="0" class="reveal-on-scroll card-hover-effect p-6 rounded-2xl bg-white dark:bg-slate-800/90 border border-slate-200/80 dark:border-slate-700 shadow-sm flex flex-col justify-between group">
          <div>
            <div class="flex items-center justify-between mb-5">
              <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/70 text-blue-600 dark:text-blue-400 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                <span class="material-symbols-outlined text-2xl">menu_book</span>
              </div>
              <span class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded">Learn</span>
            </div>
            <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white mb-2 group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">Edukasi Fiqih Siber</h3>
            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" align="justify">
              Kajian mendalam bahaya maysir dan gharar digital, tips mengenali skema ponzi, serta adab bermuamalah secara amanah di ruang maya.
            </p>
          </div>
          <div class="mt-8 pt-4 border-t border-slate-100 dark:border-slate-700">
            <a href="etika.blade.php" class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 dark:text-blue-400 group-hover:text-blue-700 dark:group-hover:text-blue-300">
              <span>Buka Materi Edukasi</span>
              <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
          </div>
        </div>

        <!-- Pilar 3 -->
        <div data-href="cyber-guard.blade.php" role="link" tabindex="0" class="reveal-on-scroll card-hover-effect p-6 rounded-2xl bg-white dark:bg-slate-800/90 border border-slate-200/80 dark:border-slate-700 shadow-sm flex flex-col justify-between group">
          <div>
            <div class="flex items-center justify-between mb-5">
              <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950/70 text-purple-600 dark:text-purple-400 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                <span class="material-symbols-outlined text-2xl">psychology</span>
              </div>
              <span class="text-xs font-bold uppercase tracking-wider text-purple-700 dark:text-purple-300 bg-purple-50 dark:bg-purple-950/70 px-2 py-0.5 rounded border border-purple-100 dark:border-purple-800">Simulasi</span>
            </div>
            <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white mb-2 group-hover:text-purple-700 dark:group-hover:text-purple-400 transition-colors">Studi Kasus &amp; Latihan</h3>
            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" align="justify">
              Simulasi interaktif menghadapi modus tawaran trading palsu, spin reward, dan manipulasi pencairan saldo taruhan.
            </p>
          </div>
          <div class="mt-8 pt-4 border-t border-slate-100 dark:border-slate-700">
            <a href="cyber-guard.blade.php" class="inline-flex items-center gap-1.5 text-sm font-semibold text-purple-600 dark:text-purple-400 group-hover:text-purple-700 dark:group-hover:text-purple-300">
              <span>Uji Kemampuan</span>
              <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
          </div>
        </div>

        <!-- Pilar 4 -->
        <div data-href="lapor.blade.php" role="link" tabindex="0" class="reveal-on-scroll card-hover-effect p-6 rounded-2xl bg-white dark:bg-slate-800/90 border border-slate-200/80 dark:border-slate-700 shadow-sm flex flex-col justify-between group">
          <div>
            <div class="flex items-center justify-between mb-5">
              <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-950/70 text-red-600 dark:text-red-400 flex items-center justify-center group-hover:bg-red-600 group-hover:text-white transition-colors duration-300">
                <span class="material-symbols-outlined text-2xl">campaign</span>
              </div>
              <span class="text-xs font-bold uppercase tracking-wider text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-950/70 px-2 py-0.5 rounded border border-red-100 dark:border-red-800">Act</span>
            </div>
            <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white mb-2 group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors">Portal Lapor Judol</h3>
            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" align="justify">
              Formulir pengaduan masyarakat untuk melaporkan tautan investasi mencurigakan yang mengarah pada perjudian daring.
            </p>
          </div>
          <div class="mt-8 pt-4 border-t border-slate-100 dark:border-slate-700">
            <a href="lapor.blade.php" class="inline-flex items-center gap-1.5 text-sm font-semibold text-red-600 dark:text-red-400 group-hover:text-red-700 dark:group-hover:text-red-300">
              <span>Buat Laporan</span>
              <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>
  

  
  <div class="py-20 relative overflow-hidden">
    <!-- Section Ambient Particles -->
    <div class="section-particles absolute inset-0 pointer-events-none overflow-hidden" aria-hidden="true"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="reveal-on-scroll text-center max-w-3xl mx-auto mb-14">
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/70 px-3 py-1 rounded-full border border-emerald-200 dark:border-emerald-800">Filosofi &amp; Nilai Inti</span>
        <h2 class="font-display font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white tracking-tight mt-3 mb-4">
          4 Pilar Wasathiyah dalam Ruang Siber
        </h2>
        <p class="text-slate-600 dark:text-slate-300 text-base leading-relaxed">
          Nilai-nilai keislaman moderat yang diwujudkan secara konkret ke dalam 4 fitur utama platform untuk membentengi masyarakat dari jeratan investasi bodong dan judi online.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 stagger-children">

        <!-- 01 Tawasuth -> AI Tabayyun -->
        <div class="reveal-on-scroll card-hover-effect p-6 rounded-2xl bg-white dark:bg-slate-800/90 border border-slate-200/90 dark:border-slate-700 hover:border-emerald-500 shadow-sm flex flex-col justify-between group transition-all">
          <div>
            <div class="flex items-center justify-between mb-4">
              <span class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 flex items-center justify-center font-bold text-sm">01</span>
              <span class="inline-flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/70 px-2.5 py-1 rounded-lg border border-emerald-100 dark:border-emerald-800">
                <span class="material-symbols-outlined text-[14px]">troubleshoot</span> AI Tabayyun
              </span>
            </div>
            <h3 class="font-display font-bold text-lg text-slate-900 mb-1 group-hover:text-emerald-700 transition-colors">Tawasuth (Jalan Tengah)</h3>
            <span class="text-xs font-semibold text-emerald-700 block mb-2">Objektivitas &amp; Tolak Spekulasi Buta</span>
            <p class="text-xs text-slate-600 leading-relaxed" align="justify">
              Menganalisis keabsahan tautan secara objektif berbasis data sanad domain, tanpa terjebak kepanikan maupun iming-iming cuan instan yang menjerumuskan ke perjudian daring.
            </p>
          </div>
          <div class="mt-6 pt-4 border-t border-slate-100">
            <a href="tabayyun.blade.php" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 group-hover:text-emerald-700 transition-colors">
              <span>Uji Tautan</span>
              <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
          </div>
        </div>

        <!-- 02 Tawazun -> Modul Edukasi -->
        <div class="reveal-on-scroll card-hover-effect p-6 rounded-2xl bg-white dark:bg-slate-800/90 border border-slate-200/90 dark:border-slate-700 hover:border-blue-500 shadow-sm flex flex-col justify-between group transition-all">
          <div>
            <div class="flex items-center justify-between mb-4">
              <span class="w-9 h-9 rounded-xl bg-blue-100 text-blue-800 dark:bg-blue-950/80 dark:text-blue-300 flex items-center justify-center font-bold text-sm">02</span>
              <span class="inline-flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-950/70 px-2.5 py-1 rounded-lg border border-blue-100 dark:border-blue-800">
                <span class="material-symbols-outlined text-[14px]">menu_book</span> Edukasi Siber
              </span>
            </div>
            <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white mb-1 group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">Tawazun (Keseimbangan)</h3>
            <span class="text-xs font-semibold text-blue-700 dark:text-blue-400 block mb-2">Inovasi Teknologi &amp; Moral Fiqih</span>
            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed" align="justify">
              Menyeimbangkan adopsi instrumen finansial modern dengan etika moral syariah, adab bermuamalah, dan pemahaman fiqih atas larangan spekulasi maysir serta gharar.
            </p>
          </div>
          <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
            <a href="etika.blade.php" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 dark:text-blue-400 group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors">
              <span>Buka Materi Edukasi</span>
              <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
          </div>
        </div>

        <!-- 03 I'tidal -> Portal Lapor -->
        <div class="reveal-on-scroll card-hover-effect p-6 rounded-2xl bg-white dark:bg-slate-800/90 border border-slate-200/90 dark:border-slate-700 hover:border-red-500 shadow-sm flex flex-col justify-between group transition-all">
          <div>
            <div class="flex items-center justify-between mb-4">
              <span class="w-9 h-9 rounded-xl bg-red-100 text-red-800 dark:bg-red-950/80 dark:text-red-300 flex items-center justify-center font-bold text-sm">03</span>
              <span class="inline-flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-950/70 px-2.5 py-1 rounded-lg border border-red-100 dark:border-red-800">
                <span class="material-symbols-outlined text-[14px]">campaign</span> Portal Lapor
              </span>
            </div>
            <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white mb-1 group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors">I'tidal (Keadilan &amp; Tegas)</h3>
            <span class="text-xs font-semibold text-red-700 dark:text-red-400 block mb-2">Tegak Lurus Melawan Kebatilan</span>
            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed" align="justify">
              Bersikap adil dan tegas menolak kebatilan digital secara transparan dengan melaporkan tautan investasi palsu dan sindikat judol demi melindungi hak sesama warga.
            </p>
          </div>
          <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
            <a href="lapor.blade.php" class="inline-flex items-center gap-1.5 text-xs font-bold text-red-600 dark:text-red-400 group-hover:text-red-700 dark:group-hover:text-red-300 transition-colors">
              <span>Laporkan Tautan Mencurigakan</span>
              <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
          </div>
        </div>

        <!-- 04 Tasamuh -> Cyber Guard -->
        <div class="reveal-on-scroll card-hover-effect p-6 rounded-2xl bg-white dark:bg-slate-800/90 border border-slate-200/90 dark:border-slate-700 hover:border-purple-500 shadow-sm flex flex-col justify-between group transition-all">
          <div>
            <div class="flex items-center justify-between mb-4">
              <span class="w-9 h-9 rounded-xl bg-purple-100 text-purple-800 dark:bg-purple-950/80 dark:text-purple-300 flex items-center justify-center font-bold text-sm">04</span>
              <span class="inline-flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider text-purple-700 dark:text-purple-300 bg-purple-50 dark:bg-purple-950/70 px-2.5 py-1 rounded-lg border border-purple-100 dark:border-purple-800">
                <span class="material-symbols-outlined text-[14px]">psychology</span> Cyber Guard
              </span>
            </div>
            <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white mb-1 group-hover:text-purple-700 dark:group-hover:text-purple-400 transition-colors">Tasamuh (Kebijaksanaan)</h3>
            <span class="text-xs font-semibold text-purple-700 dark:text-purple-400 block mb-2">Edukasi Empatik Tanpa Menghakimi</span>
            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed" align="justify">
              Membangun kewaspadaan digital lewat simulasi skenario yang santun, persuasif, dan mendidik, guna merangkul serta mengedukasi korban yang pernah terperangkap tipuan siber.
            </p>
          </div>
          <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
            <a href="cyber-guard.blade.php" class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-600 dark:text-purple-400 group-hover:text-purple-700 dark:group-hover:text-purple-300 transition-colors">
              <span>Uji Pemahaman</span>
              <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>
  

  
  <div class="reveal-on-scroll py-20 relative overflow-hidden">
    <!-- Section Ambient Particles -->
    <div class="section-particles absolute inset-0 pointer-events-none overflow-hidden" aria-hidden="true"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <div class="lg:col-span-7 space-y-6">
          <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-100/80 dark:bg-emerald-950/70 text-emerald-800 dark:text-emerald-300 text-xs font-bold tracking-wide uppercase">
            <span class="material-symbols-outlined text-[16px] text-emerald-600 dark:text-emerald-400">verified_user</span>
            <span>Nilai Kearifan Aswaja</span>
          </div>
          <h2 class="font-display font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white tracking-tight leading-tight">
            Tradisi Sanad &amp; Validasi Presisi di Era Siber
          </h2>
          <p class="text-slate-600 dark:text-slate-300 leading-relaxed text-base sm:text-lg" align="justify">
            Dalam tradisi keilmuan Ahlussunnah wal Jama'ah, setiap riwayat wajib diaudit mata rantai transmisinya (<em class="text-emerald-700 dark:text-emerald-400 font-semibold not-italic">sanad</em>), integritas moral pembawanya (<em class="text-emerald-700 dark:text-emerald-400 font-semibold not-italic">rawi</em>), serta kesesuaian kontennya (<em class="text-emerald-700 dark:text-emerald-400 font-semibold not-italic">matan</em>). Aswaja CyberShield mengangkat kearifan klasik ini menjadi metodologi verifikasi digital modern untuk melindungi masyarakat dari manipulasi siber dan jebakan investasi bodong atau judi online.
          </p>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 stagger-children">
            <div class="card-hover-effect p-5 rounded-2xl bg-white dark:bg-slate-800/90 border border-slate-200/90 dark:border-slate-700 shadow-sm flex items-start gap-3.5">
              <div class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 shrink-0">
                <span class="material-symbols-outlined text-2xl">menu_book</span>
              </div>
              <div>
                <span class="font-display font-bold text-slate-900 dark:text-white block text-base mb-1">Tradisi Tabayyun</span>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed" align="justify">Prinsip Fiqih Informasi &bull; Cermat, tenang, dan tidak tergesa-gesa menyebarkan atau mempercayai tautan.</p>
              </div>
            </div>
            <div class="card-hover-effect p-5 rounded-2xl bg-white dark:bg-slate-800/90 border border-slate-200/90 dark:border-slate-700 shadow-sm flex items-start gap-3.5">
              <div class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 shrink-0">
                <span class="material-symbols-outlined text-2xl">fact_check</span>
              </div>
              <div>
                <span class="font-display font-bold text-slate-900 dark:text-white block text-base mb-1">Verifikasi Terpadu</span>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed" align="justify">Pemeriksaan struktur domain, pola rekayasa sosial, dan indikasi manipulasi situs taruhan digital.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Graphic Card Presentation -->
        <div class="lg:col-span-5">
          <div class="card-hover-effect rounded-2xl p-7 sm:p-8 bg-white dark:bg-slate-800/95 border border-emerald-100 dark:border-slate-700 shadow-xl relative overflow-hidden">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
              <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 text-xs font-semibold">
                <span class="material-symbols-outlined text-[16px] text-emerald-600 dark:text-emerald-400">auto_stories</span>
                <span>Kaidah Qur'ani</span>
              </div>
              <span class="text-xs font-medium text-slate-400 dark:text-slate-500">QS. Al-Hujurat : 6</span>
            </div>

            <!-- Arabic Calligraphy snippet -->
            <div class="mb-4 text-right" dir="rtl">
              <p class="font-serif text-lg sm:text-xl text-emerald-900 dark:text-emerald-300 leading-loose font-normal">
                يَا أَيُّهَا الَّذِينَ آمَنُوا إِنْ جَاءَكُمْ فَاسِقٌ بِنَبَإٍ فَتَبَيَّنُوا
              </p>
            </div>

            <blockquote class="text-base text-slate-700 dark:text-slate-200 font-serif italic mb-6 leading-relaxed bg-slate-50/80 dark:bg-slate-900/60 p-4 rounded-xl border-l-4 border-emerald-500" align="justify">
              <i align="center">“Wahai orang-orang yang beriman! Jika seseorang yang fasik datang kepadamu membawa suatu berita, maka telitilah kebenarannya (ber-tabayyun-lah)...”</i>
            </blockquote>

            <div class="pt-2 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
              <span class="inline-flex items-center gap-1.5 font-medium text-slate-700 dark:text-slate-300">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Dalil Etika Fiqih Informasi
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  

  <div class="custom-cursor"></div>
</main>
<script src="../js/custom-cursor.js"></script>

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
  

  <script src="../js/api.js"></script>
  <script src="../js/ui-kit.js"></script>
  <script src="../js/animations.js"></script>
  <script src="../js/navbar.js"></script>
  <script src="../js/scroll-reveal.js"></script>
  <script src="../js/home.js"></script>
</body>
</html>







