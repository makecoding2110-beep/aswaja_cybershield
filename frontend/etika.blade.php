<!DOCTYPE html>
<html lang="id" class="scroll-smooth" data-theme="aswaja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Aswaja CyberShield - Platform edukasi literasi digital, verifikasi hoaks berbasis prinsip Tabayyun, dan penguatan keamanan siber masyarakat moderat.">
  <link rel="icon" type="image/svg+xml" href="../favicon.svg">
  <title>Etika Siber - Aswaja CyberShield</title>
  
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
              </a><a href="tabayyun.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap transition-all duration-150 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800">
                AI Tabayyun
              </a><a href="etika.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap transition-all duration-150 text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 shadow-sm">
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
        </a><a href="tabayyun.blade.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-base font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800">
          <span class="material-symbols-outlined text-[20px]">fact_check</span>
          <span>AI Tabayyun</span>
        </a><a href="etika.blade.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-base font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60">
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



  <main class="flex-grow relative overflow-hidden">
    <!-- Ambient Section Particles & Floating Orbs -->
    <div class="section-particles absolute inset-0 pointer-events-none overflow-hidden" aria-hidden="true"></div>
    <div class="absolute top-6 left-1/2 -translate-x-1/2 w-[600px] h-[320px] bg-emerald-400/15 blur-[100px] rounded-full pointer-events-none -z-10 animate-float"></div>
    <div class="absolute -top-10 right-10 w-80 h-80 bg-blue-400/15 blur-[90px] rounded-full pointer-events-none -z-10 animate-float-delayed"></div>
    
  <div class="pt-6 pb-12 sm:pt-8 sm:pb-14 relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Page Header (Padat, Berimbang & Tanpa Ruang Kosong) -->
      <div class="max-w-3xl mx-auto mb-8 sm:mb-10 text-center">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-100 dark:bg-emerald-950/70 border border-emerald-200/80 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-semibold uppercase tracking-wider mb-4 shadow-2xs">
          <span class="material-symbols-outlined text-[16px] text-emerald-600 dark:text-emerald-400">menu_book</span>
          <span>Modul Edukasi &amp; Fiqih Informasi Digital</span>
        </div>
        <h1 class="font-display font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white tracking-tight mb-3">
          Waspada Judi Online: <span class="text-emerald-600 dark:text-emerald-400">Literasi Siber Berbasis Nilai Islam</span>
        </h1>
        <p class="text-slate-600 dark:text-slate-300 text-base leading-relaxed mb-6 max-w-2xl mx-auto">
          Panduan komprehensif mengenali dan menghindari jebakan investasi bodong, manipulasi maysir digital, dan kejahatan siber berlandaskan kaidah Ahlussunnah wal Jama'ah.
        </p>

        <!-- Quick Highlight Badges (Menghilangkan kekosongan visual) -->
        <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3">
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300 shadow-xs">
            <span class="material-symbols-outlined text-[18px] text-emerald-600">verified</span>
            <span>4 Kaidah Fiqih Medsos (4T)</span>
          </span>
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300 shadow-xs">
            <span class="material-symbols-outlined text-[18px] text-emerald-600">auto_stories</span>
            <span id="etika-module-count-label">3 Modul Literasi Praktis</span>
          </span>
          <a href="cyber-guard.blade.php" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs shadow-emerald-600/20 transition-all">
            <span class="material-symbols-outlined text-[18px]">sports_esports</span>
            <span>Uji Pemahaman di Cyber Guard &rarr;</span>
          </a>
        </div>
      </div>

      <!-- Featured Accordion Spotlight: Prinsip 4T -->
      <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/90 dark:border-slate-700 overflow-hidden mb-8 sm:mb-10">
        <div id="accordion-4t-header" class="p-6 md:p-8 flex items-center justify-between cursor-pointer select-none bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-750 transition-colors">
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0">
              <span class="material-symbols-outlined text-2xl">balance</span>
            </div>
            <div>
              <div class="flex items-center gap-2 mb-1">
                <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800">Materi Utama</span>
                <span class="text-xs text-slate-400">Pondasi Fiqih Medsos</span>
              </div>
              <h2 class="font-display font-bold text-xl text-slate-900 dark:text-white">Prinsip 4T dalam Literasi Siber</h2>
              <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kaidah fiqih verifikasi Aswaja untuk menangkal jebakan judol, investasi bodong, dan maysir digital yang marak di media sosial.</p>
            </div>
          </div>
          <button type="button" id="accordion-chevron" aria-label="Buka Rincian Materi" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition-transform duration-200">
            <span class="material-symbols-outlined text-xl">expand_more</span>
          </button>
        </div>

        <!-- Accordion Expanded Body -->
        <div id="accordion-4t-body" class="p-6 md:p-8 pt-0 border-t border-slate-100 dark:border-slate-700 space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 pt-4 stagger-children" id="prinsip-4t-public-grid">
            <!-- T1 -->
            <a href="artikel/prinsip-tawaqquf.blade.php" class="card-hover-effect group p-4 rounded-xl bg-slate-50 dark:bg-slate-750 hover:bg-emerald-50/60 dark:hover:bg-emerald-950/40 border border-slate-200/70 dark:border-slate-700 hover:border-emerald-300 dark:hover:border-emerald-600 space-y-2 transition-all block shadow-xs hover:shadow-sm">
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-emerald-700 bg-white px-2 py-0.5 rounded border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-colors">T1</span>
                <span class="material-symbols-outlined text-emerald-600 text-lg group-hover:scale-110 transition-transform">pause_circle</span>
              </div>
              <h3 class="font-display font-bold text-base text-slate-900 dark:text-white group-hover:text-emerald-700 transition-colors">Tawaqquf</h3>
              <span class="font-serif text-sm font-semibold text-emerald-700 dark:text-emerald-400 block" dir="rtl">تَوَقُّفْ</span>
              <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                Menahan jempol saat menerima tautan sensasional berbau cuan instan. Menghentikan penyebaran sebelum fakta teruji.
              </p>
              <div class="pt-2 flex items-center gap-1 text-[11px] font-bold text-emerald-600 group-hover:text-emerald-700">
                <span>Kaji Selengkapnya</span>
                <span class="material-symbols-outlined text-[14px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
              </div>
            </a>

            <!-- T2 -->
            <a href="artikel/prinsip-tastabbus.blade.php" class="card-hover-effect group p-4 rounded-xl bg-slate-50 dark:bg-slate-750 hover:bg-emerald-50/60 dark:hover:bg-emerald-950/40 border border-slate-200/70 dark:border-slate-700 hover:border-emerald-300 dark:hover:border-emerald-600 space-y-2 transition-all block shadow-xs hover:shadow-sm">
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-emerald-700 bg-white px-2 py-0.5 rounded border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-colors">T2</span>
                <span class="material-symbols-outlined text-emerald-600 text-lg group-hover:scale-110 transition-transform">search</span>
              </div>
              <h3 class="font-display font-bold text-base text-slate-900 dark:text-white group-hover:text-emerald-700 transition-colors">Tastabbus</h3>
              <span class="font-serif text-sm font-semibold text-emerald-700 dark:text-emerald-400 block" dir="rtl">تَثَبُّتْ</span>
              <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                Melacak jejak digital domain, memverifikasi perizinan regulator OJK/Bappebti, dan membongkar kedok samaran tautan.
              </p>
              <div class="pt-2 flex items-center gap-1 text-[11px] font-bold text-emerald-600 group-hover:text-emerald-700">
                <span>Kaji Selengkapnya</span>
                <span class="material-symbols-outlined text-[14px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
              </div>
            </a>

            <!-- T3 -->
            <a href="artikel/prinsip-tatsabbut.blade.php" class="card-hover-effect group p-4 rounded-xl bg-slate-50 dark:bg-slate-750 hover:bg-emerald-50/60 dark:hover:bg-emerald-950/40 border border-slate-200/70 dark:border-slate-700 hover:border-emerald-300 dark:hover:border-emerald-600 space-y-2 transition-all block shadow-xs hover:shadow-sm">
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-emerald-700 bg-white px-2 py-0.5 rounded border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-colors">T3</span>
                <span class="material-symbols-outlined text-emerald-600 text-lg group-hover:scale-110 transition-transform">verified_user</span>
              </div>
              <h3 class="font-display font-bold text-base text-slate-900 dark:text-white group-hover:text-emerald-700 transition-colors">Tatsabbut</h3>
              <span class="font-serif text-sm font-semibold text-emerald-700 dark:text-emerald-400 block" dir="rtl">تَبَيَّنْ</span>
              <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                Menguji kesesuaian muamalah dengan fatwa alim ulama dan hukum fiqih untuk memastikan tiadanya unsur judi (maysir).
              </p>
              <div class="pt-2 flex items-center gap-1 text-[11px] font-bold text-emerald-600 group-hover:text-emerald-700">
                <span>Kaji Selengkapnya</span>
                <span class="material-symbols-outlined text-[14px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
              </div>
            </a>

            <!-- T4 -->
            <a href="artikel/prinsip-taaqqul.blade.php" class="card-hover-effect group p-4 rounded-xl bg-slate-50 dark:bg-slate-750 hover:bg-emerald-50/60 dark:hover:bg-emerald-950/40 border border-slate-200/70 dark:border-slate-700 hover:border-emerald-300 dark:hover:border-emerald-600 space-y-2 transition-all block shadow-xs hover:shadow-sm">
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-emerald-700 bg-white px-2 py-0.5 rounded border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-colors">T4</span>
                <span class="material-symbols-outlined text-emerald-600 text-lg group-hover:scale-110 transition-transform">psychology</span>
              </div>
              <h3 class="font-display font-bold text-base text-slate-900 dark:text-white group-hover:text-emerald-700 transition-colors">Ta'aqqul</h3>
              <span class="font-serif text-sm font-semibold text-emerald-700 dark:text-emerald-400 block" dir="rtl">تَعَقُّلْ</span>
              <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                Menimbang apakah konten valid tersebut jika disebarkan membawa kemaslahatan atau malah memicu kegaduhan.
              </p>
              <div class="pt-2 flex items-center gap-1 text-[11px] font-bold text-emerald-600 group-hover:text-emerald-700">
                <span>Kaji Selengkapnya</span>
                <span class="material-symbols-outlined text-[14px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
              </div>
            </a>
          </div>
        </div>
      </div>

      <!-- Module Cards Grid (Disinkronkan otomatis dengan tabel public.articles di Database) -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 stagger-children" id="module-cards-grid">

        <!-- Card 1 (Database ID 1) -->
        <article class="module-item reveal-on-scroll card-hover-effect bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/90 dark:border-slate-700 overflow-hidden shadow-md hover:shadow-xl flex flex-col justify-between hover:border-emerald-300 dark:hover:border-emerald-600 transition-all" data-category="fiqih finansial" data-title="anatomi jebakan robot trading bodong berkedok putaran slot" data-index="0" data-href="artikel/baca.blade.php?id=1&amp;slug=anatomi-jebakan-robot-trading-bodong" role="link" tabindex="0">
          <div class="relative overflow-hidden group" style="height:200px; background:linear-gradient(135deg,#065f46,#059669);">
            <div class="absolute inset-0 flex items-center justify-center opacity-10">
              <span class="material-symbols-outlined text-white" style="font-size:120px;">casino</span>
            </div>
            <img src="../images/artikel-1.jpg" alt="Anatomi Jebakan Robot Trading Bodong Berkedok Putaran Slot" class="relative z-10 w-full h-full object-cover transition-transform duration-500 hover:scale-105" onerror="this.onerror=null; this.remove();">
          </div>
          <div class="p-5 flex flex-col flex-grow">
            <div class="flex items-center justify-between gap-2 mb-2">
              <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">FIQIH FINANSIAL &bull; 07 NOV 2026</p>
            </div>
            <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white mb-3 leading-snug flex-grow">
              Anatomi Jebakan Robot Trading Bodong Berkedok Putaran Slot
            </h3>
            <p class="text-sm text-slate-600 dark:text-slate-300 line-clamp-3 leading-relaxed mb-4">
              Mengapa janji profit konsisten harian tanpa risiko adalah indikator mutlak skema Ponzi dan perjudian terselubung.
            </p>
            <a href="artikel/baca.blade.php?id=1&amp;slug=anatomi-jebakan-robot-trading-bodong" class="group/btn text-sm font-bold text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 inline-flex items-center gap-1.5 transition-all mt-auto">
              <span>Baca Selengkapnya</span>
              <span class="material-symbols-outlined text-[16px] group-hover/btn:translate-x-1 transition-transform duration-200">arrow_forward</span>
            </a>
          </div>
        </article>

        <!-- Card 2 (Database ID 2) -->
        <article class="module-item reveal-on-scroll card-hover-effect bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/90 dark:border-slate-700 overflow-hidden shadow-md hover:shadow-xl flex flex-col justify-between hover:border-emerald-300 dark:hover:border-emerald-600 transition-all" data-category="fiqih siber" data-title="kajian fiqih muamalah: batasan gharar dan maysir pada game berhadiah finansial" data-index="1" data-href="artikel/baca.blade.php?id=2&amp;slug=kajian-fiqih-muamalah-gharar-maysir" role="link" tabindex="0">
          <div class="relative overflow-hidden" style="height:200px; background:linear-gradient(135deg,#065f46,#059669);">
            <div class="absolute inset-0 flex items-center justify-center opacity-10">
              <span class="material-symbols-outlined text-white" style="font-size:120px;">balance</span>
            </div>
          </div>
          <div class="p-5 flex flex-col flex-grow">
            <div class="flex items-center justify-between gap-2 mb-2">
              <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">FIQIH SIBER &bull; 07 NOV 2026</p>
            </div>
            <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white mb-3 leading-snug flex-grow">
              Kajian Fiqih Muamalah: Batasan Gharar dan Maysir pada Game Berhadiah Finansial
            </h3>
            <p class="text-sm text-slate-600 dark:text-slate-300 line-clamp-3 leading-relaxed mb-4">
              Membedah unsur ketidakpastian (gharar) dan spekulasi untung-untungan (maysir) pada aplikasi permainan top-up koin.
            </p>
            <a href="artikel/baca.blade.php?id=2&amp;slug=kajian-fiqih-muamalah-gharar-maysir" class="group/btn text-sm font-bold text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 inline-flex items-center gap-1.5 transition-all mt-auto">
              <span>Baca Selengkapnya</span>
              <span class="material-symbols-outlined text-[16px] group-hover/btn:translate-x-1 transition-transform duration-200">arrow_forward</span>
            </a>
          </div>
        </article>

        <!-- Card 3 (Database ID 3) -->
        <article class="module-item reveal-on-scroll card-hover-effect bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/90 dark:border-slate-700 overflow-hidden shadow-md hover:shadow-xl flex flex-col justify-between hover:border-emerald-300 dark:hover:border-emerald-600 transition-all" data-category="keamanan digital" data-title="mitigasi phishing file apk surat undangan dan kurir paket" data-index="2" data-href="artikel/baca.blade.php?id=3&amp;slug=mitigasi-phishing-file-apk-kurir" role="link" tabindex="0">
          <div class="relative overflow-hidden" style="height:200px; background:linear-gradient(135deg,#065f46,#059669);">
            <div class="absolute inset-0 flex items-center justify-center opacity-10">
              <span class="material-symbols-outlined text-white" style="font-size:120px;">phishing</span>
            </div>
          </div>
          <div class="p-5 flex flex-col flex-grow">
            <div class="flex items-center justify-between gap-2 mb-2">
              <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">KEAMANAN DIGITAL &bull; 07 NOV 2026</p>
            </div>
            <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white mb-3 leading-snug flex-grow">
              Mitigasi Phishing File APK Surat Undangan dan Kurir Paket
            </h3>
            <p class="text-sm text-slate-600 dark:text-slate-300 line-clamp-3 leading-relaxed mb-4">
              Langkah teknis mengamankan ponsel Android dari pencurian kode OTP perbankan melalui berkas instalasi berbahaya.
            </p>
            <a href="artikel/baca.blade.php?id=3&amp;slug=mitigasi-phishing-file-apk-kurir" class="group/btn text-sm font-bold text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 inline-flex items-center gap-1.5 transition-all mt-auto">
              <span>Baca Selengkapnya</span>
              <span class="material-symbols-outlined text-[16px] group-hover/btn:translate-x-1 transition-transform duration-200">arrow_forward</span>
            </a>
          </div>
        </article>

      </div>

    </div>
  </div>

  
  <dialog id="modal-reader" class="backdrop:bg-slate-900/60 p-0 rounded-2xl shadow-2xl max-w-2xl w-full border border-slate-200">
    <div class="bg-white p-6 sm:p-8 space-y-4">
      <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <span id="modal-reader-badge" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">Materi</span>
        <button type="button" id="btn-close-reader" class="text-slate-400 hover:text-slate-600">
          <span class="material-symbols-outlined text-2xl">close</span>
        </button>
      </div>
      <h3 id="modal-reader-title" class="font-display font-bold text-xl sm:text-2xl text-slate-900">Judul Materi</h3>
      <div id="modal-reader-body" class="text-sm sm:text-base text-slate-600 leading-relaxed space-y-3">
        <!-- Content inserted dynamically -->
      </div>
      <div class="pt-4 border-t border-slate-100 flex justify-end">
        <button type="button" id="btn-done-reader" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white font-semibold text-sm hover:bg-slate-800 transition-colors">
          Selesai Membaca
        </button>
      </div>
    </div>
  </dialog>

  </main>

  <footer class="bg-gradient-to-b from-[#064e3b] via-[#022c22] to-[#011a14] dark:bg-slate-950 text-emerald-100/90 dark:text-slate-300 border-t border-emerald-900/40 dark:border-slate-800 mt-0">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-10">

      <!-- Column 1: Brand Info -->
      <div class="md:col-span-6 space-y-4">
        <div class="flex items-center gap-2 xl:gap-3 shrink-0">
          <img src="../logo-shield.png" alt="Logo Resmi Aswaja CyberShield" class="w-9 h-9 object-contain">
          <span class="font-display font-bold text-xl text-white tracking-tight">Aswaja CyberShield</span>
        </div>
        <p class="text-sm text-emerald-100/80 dark:text-slate-400 leading-relaxed max-w-md">
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
          <li><a href="etika.blade.php" class="text-emerald-200/90 hover:text-white dark:text-slate-300 dark:hover:text-emerald-400 transition-colors">Modul Etika &amp; Fiqih Siber</a></li>
          <li><a href="cyber-guard.blade.php" class="text-emerald-200/90 hover:text-white dark:text-slate-300 dark:hover:text-emerald-400 transition-colors">Cyber Guard Test (Kuis)</a></li>
        </ul>
      </div>

      <!-- Column 3: Layanan Aduan -->
      <div class="md:col-span-3 space-y-3">
        <h4 class="font-display font-semibold text-sm text-white uppercase tracking-wider">Layanan Terpadu</h4>
        <ul class="space-y-2 text-sm">
          <li><a href="lapor.blade.php" class="text-rose-300 hover:text-white dark:text-rose-400 dark:hover:text-rose-300 transition-colors">Portal Lapor</a></li>
          <li><a href="etika.blade.php" class="text-emerald-200/90 hover:text-white dark:text-slate-300 dark:hover:text-emerald-400 transition-colors">Pedoman Literasi</a></li>
          <li><span class="text-emerald-300/60 dark:text-slate-500">Protokol Fiqih Informasi Digital</span></li>
        </ul>
      </div>

    </div>
  </div>
</footer>
  

  <script src="../js/api.js"></script>
  <script src="../js/navbar.js"></script>
  <script src="../js/scroll-reveal.js"></script>
  <script src="../js/etika.js"></script>
  <script src="../js/animations.js"></script>
</body>
</html>








