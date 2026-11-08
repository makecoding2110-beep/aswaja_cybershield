<!DOCTYPE html>
<html lang="id" class="scroll-smooth" data-theme="aswaja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Aswaja CyberShield - Platform edukasi literasi digital, verifikasi hoaks berbasis prinsip Tabayyun, dan penguatan keamanan siber masyarakat moderat.">
  <link rel="icon" type="image/svg+xml" href="../favicon.svg">
  <title>Cyber Guard Test - Uji Kewaspadaan Investasi Bodong &amp; Judol</title>
  

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
              </a><a href="etika.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap transition-all duration-150 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800">
                Etika Siber
              </a><a href="cyber-guard.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap transition-all duration-150 text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 shadow-sm">
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
        </a><a href="etika.blade.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-base font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800">
          <span class="material-symbols-outlined text-[20px]">menu_book</span>
          <span>Etika Siber</span>
        </a><a href="cyber-guard.blade.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-base font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60">
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
    <div class="absolute top-10 left-1/2 -translate-x-1/2 w-[600px] h-[350px] bg-emerald-400/15 blur-[100px] rounded-full pointer-events-none -z-10 animate-float"></div>
    <div class="absolute -top-20 right-10 w-96 h-96 bg-blue-400/15 blur-[90px] rounded-full pointer-events-none -z-10 animate-float-delayed"></div>
    
  <div class="pt-6 pb-12 sm:pt-8 sm:pb-14 relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Page Header -->
      <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-10">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-100 dark:bg-emerald-950/70 border border-emerald-200/80 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-semibold uppercase tracking-wider mb-4 shadow-2xs">
          <span class="material-symbols-outlined text-[16px] text-emerald-600 dark:text-emerald-400">security</span>
          <span>Simulasi Interaktif Refleks Siber</span>
        </div>
        <h1 class="font-display font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white tracking-tight mb-3">
          Cyber Guard Test
        </h1>
        <p class="text-slate-600 dark:text-slate-300 text-base leading-relaxed">
          Ukur refleks dan kewaspadaan Tabayyun Anda dalam mengenali modus investasi menyesatkan, skema robot trading fiktif, arisan berkedok ponzi, dan tautan judol terselubung di dunia nyata.
        </p>
      </div>

      <!-- Quiz Section (Terpusat & Rapi Responsif) -->
      <div class="max-w-4xl mx-auto space-y-6">

          <!-- Main Quiz Card -->
          <div id="quiz-container" class="bg-white rounded-2xl shadow-md border border-slate-200/90 p-6 sm:p-8 space-y-8">

            <!-- Progress Bar Header -->
            <div class="space-y-2">
              <div class="flex items-center justify-between text-xs font-semibold text-slate-600">
                <span id="quiz-progress-text" class="text-emerald-700">Pertanyaan 1 dari 5</span>
                <span id="quiz-percentage-text">20% Selesai</span>
              </div>
              <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                <div id="quiz-progress-bar" class="h-full bg-emerald-600 rounded-full transition-all duration-300 w-1/5"></div>
              </div>
            </div>

            <!-- Question Body -->
            <div class="space-y-6">

              <!-- Question Scenario Title -->
              <div class="space-y-2">
                <div class="flex items-center gap-2">
                  <span id="quiz-scenario-badge" class="px-2.5 py-0.5 rounded text-xs font-bold bg-slate-100 text-slate-700">Studi Kasus 01: Phishing WhatsApp</span>
                  <span class="text-xs text-emerald-700 font-medium">&bull; Tingkat: Realistis</span>
                </div>
                <h2 id="quiz-question-heading" class="font-display font-bold text-lg sm:text-xl text-slate-900 leading-snug">
                  <!-- Question dynamic -->
                </h2>
              </div>

              <!-- Inset Chat Mockup -->
              <div id="quiz-mockup-box" class="p-4 sm:p-5 rounded-xl bg-slate-100/80 border border-slate-200 flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-300 flex items-center justify-center text-slate-600 shrink-0">
                  <span class="material-symbols-outlined text-xl" id="mockup-icon">local_shipping</span>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="flex items-center justify-between mb-1">
                    <span class="font-bold text-xs text-slate-800 dark:text-slate-200" id="mockup-sender">+62 821-9981-XXXX (Ekspedisi Kilat)</span>
                    <span class="text-[11px] text-slate-400 dark:text-slate-400">10:14 WITA</span>
                  </div>
                  <div class="bg-white dark:bg-slate-800 p-3 rounded-xl rounded-tl-none shadow-sm border border-slate-200/80 dark:border-slate-700 max-w-sm space-y-2">
                    <p class="text-xs text-slate-700 dark:text-slate-200" id="mockup-message">Paket tertahan di gudang pusat. Mohon segera unduh foto resi untuk konfirmasi penerima:</p>
                    <div class="p-2 rounded-lg bg-red-50 dark:bg-red-950/40 border border-red-100 dark:border-red-900/60 flex items-center gap-2.5" id="mockup-attachment">
                      <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-2xl">android</span>
                      <div class="truncate text-left">
                        <span class="font-mono text-xs font-bold text-slate-900 dark:text-white block truncate" id="mockup-filename">Lihat_Foto_Resi_Paket.apk</span>
                        <span class="text-[10px] text-slate-400 dark:text-slate-400">8.4 MB &bull; Paket Aplikasi Android</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Interactive Radio Options -->
              <div class="space-y-3 stagger-children" id="quiz-options-group">
                <!-- Options dynamically populated -->
              </div>

              <!-- Feedback Explanation Box -->
              <div id="quiz-feedback-box" class="hidden p-4 rounded-xl text-sm transition-all"></div>

              <!-- Kaidah Aswaja Callout -->
              <aside class="p-4 rounded-xl bg-emerald-50/70 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-800/60 text-xs text-slate-700 dark:text-slate-200 flex items-start gap-3">
                <span class="material-symbols-outlined text-emerald-700 dark:text-emerald-400 text-lg shrink-0 mt-0.5">balance</span>
                <div>
                  <strong class="font-bold text-emerald-950 dark:text-emerald-300 block mb-0.5">Kaidah Fiqih Keamanan:</strong>
                  <em>“Dar'ul mafasid muqaddamun 'ala jalbil mashalih”</em> (Menolak potensi bahaya dan kejahatan harus lebih didahulukan daripada mengejar rasa penasaran).
                </div>
              </aside>

            </div>

            <!-- Navigation Footer -->
            <div class="flex items-center justify-between pt-4 border-t border-slate-200 dark:border-slate-700">
              <button type="button" id="btn-quiz-prev" disabled class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-500 font-semibold text-sm cursor-not-allowed">
                &larr; Soal Sebelumnya
              </button>
              <button type="button" id="btn-quiz-next" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-sm transition-all active:scale-95 flex items-center gap-1.5">
                <span>Soal Selanjutnya</span>
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
              </button>
            </div>

          </div>

          <!-- Quiz Completed Result Card (Initially Hidden) -->
          <div id="quiz-result-card" class="hidden bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-8 text-center space-y-6">
            <div class="w-20 h-20 rounded-full bg-emerald-100 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto text-4xl shadow-inner">
              <span class="material-symbols-outlined text-5xl">military_tech</span>
            </div>
            <div>
              <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300">
                Hasil Evaluasi Kesiapsiagaan
              </span>
              <h2 class="font-display font-extrabold text-3xl text-slate-900 dark:text-white mt-2">
                Skor Anda: <span id="final-score" class="text-emerald-600 dark:text-emerald-400">100 / 100</span>
              </h2>
              <p id="score-predicate" class="text-slate-600 dark:text-slate-300 max-w-md mx-auto mt-2 text-sm leading-relaxed">
                Luar biasa! Refleks Tabayyun Anda sangat tajam dalam mengidentifikasi berbagai modus rekayasa sosial dan serangan siber modern.
              </p>
            </div>

            <div class="p-6 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 max-w-md mx-auto space-y-2 text-left">
              <div class="flex items-center justify-between text-xs">
                <span class="text-slate-500 dark:text-slate-400">Tingkat Ketahanan:</span>
                <span class="font-bold text-emerald-700 dark:text-emerald-400">Cyber Shield Warrior (Amanah & Waspada)</span>
              </div>
              <div class="flex items-center justify-between text-xs">
                <span class="text-slate-500">Prinsip 4T:</span>
                <span class="font-bold text-slate-800">Tawaqquf &amp; Tastabbus Teruji</span>
              </div>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
              <button type="button" id="btn-restart-quiz" class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm transition-all">
                Ulangi Simulasi
              </button>
              <a href="etika.blade.php" class="px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-sm transition-all">
                Pelajari Modul Tambahan
              </a>
            </div>
          </div>

      </div>

    </div>
  </div>

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
  <script src="../js/cyber-guard.js"></script>
  <script src="../js/animations.js"></script>
</body>
</html>









