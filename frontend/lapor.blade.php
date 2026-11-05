<!DOCTYPE html>
<?php
  /**
   * Formulir Portal Lapor Siber - Aswaja CyberShield */
  $prefilledUrl = htmlspecialchars(trim($_GET['url'] ?? $_GET['q'] ?? ''), ENT_QUOTES, 'UTF-8');
  
  $kategoriList = [
      'Investasi Berkedok Trading',
      'Iklan Cuan Kilat Bersyarat Deposit Judi',
      'Tautan Promosi Slot/Kasino Berkedok Game Finansial',
      'Tautan Undangan Grup Investasi/Arisan Bodong',
      'Situs Afiliasi & Referral Judi Online',
      'Lainnya'
  ];
?>

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



  <main class="flex-grow">
    
  <div class="py-12 md:py-16">
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

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">

        <!-- Form Column -->
        <div class="lg:col-span-7 bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-slate-200/90 dark:border-slate-700 p-6 sm:p-8 space-y-6">
          <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-700">
            <div>
              <h2 class="font-display font-bold text-lg text-slate-900 dark:text-white">Formulir Aduan Insiden</h2>
              <p class="text-xs text-slate-500 dark:text-slate-400">Identitas pelapor dijamin aman dan terlindungi secara kriptografis.</p>
            </div>
            <span class="px-2.5 py-1 rounded-lg text-xs font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-100 dark:border-emerald-800 flex items-center gap-1">
              <span class="material-symbols-outlined text-sm">lock</span>
              <span>Kerahasiaan Aman</span>
            </span>
          </div>

          <form id="form-lapor-siber" class="space-y-5">
            <div>
              <label for="lapor-nama" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                Nama Pelapor (Opsional / Boleh Samaran):
              </label>
              <input type="text" id="lapor-nama" placeholder="Hamba Allah / Muhammad / Anonim" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white dark:focus:bg-slate-900 transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 stagger-children">
              <div>
                <label for="lapor-kategori" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                  Kategori Insiden: <span class="text-red-500">*</span>
                </label>
                <select id="lapor-kategori" name="kategori" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white dark:focus:bg-slate-900 transition-all">
                  <option value="">-- Pilih Kategori Laporan --</option>
                  <?php foreach ($kategoriList as $kat): ?>
                    <option value="<?= $kat ?>"><?= $kat ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div>
                <label for="lapor-target" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                  URL Tautan Mencurigakan yang Dilaporkan: <span class="text-red-500">*</span>
                </label>
                <input type="url" id="lapor-target" name="url" required value="<?= $prefilledUrl ?>" placeholder="https://contoh-investasi-judol.xyz/slot" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white dark:focus:bg-slate-900 transition-all">
              </div>
            </div>

            <div>
              <label for="lapor-deskripsi" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                Kronologi Kejadian (Opsional):
              </label>
              <textarea id="lapor-deskripsi" rows="4" placeholder="Ceritakan ringkas bagaimana Anda menemukan tautan ini, apakah ada tawaran profit/cuan, desakan deposit, atau rekrutmen referal yang mencurigakan..." class="w-full p-4 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white dark:focus:bg-slate-900 transition-all resize-none"></textarea>
            </div>

            <!-- Upload Area -->
            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                Unggah Bukti Tangkapan Layar atau Dokumen (Opsional):
              </label>
              <div id="upload-dropzone" class="border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-emerald-500 rounded-2xl p-6 text-center cursor-pointer bg-slate-50/50 dark:bg-slate-900/60 hover:bg-slate-50 dark:hover:bg-slate-900 transition-colors">
                <span class="material-symbols-outlined text-3xl text-slate-400 mb-1">upload_file</span>
                <p class="text-xs text-slate-600 dark:text-slate-300 font-medium">
                  Klik untuk memilih file tangkapan layar atau seret ke area ini
                </p>
                <span class="text-[11px] text-slate-400 dark:text-slate-500 block mt-1">Format gambar JPG, PNG, atau PDF (Maks. 5MB)</span>
                <input type="file" id="file-upload" class="hidden" accept="image/*,application/pdf">
              </div>
              <div id="file-preview" class="hidden mt-2 p-3 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 rounded-xl items-center justify-between text-xs text-emerald-800 dark:text-emerald-300">
                <span id="file-name" class="font-medium truncate max-w-xs">bukti_screenshot.png</span>
                <button type="button" id="btn-remove-file" class="text-red-600 dark:text-red-400 hover:underline">Hapus</button>
              </div>
            </div>

            <div class="pt-2">
              <button type="submit" id="btn-submit-lapor" class="w-full py-3.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm shadow-md shadow-red-600/20 transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[18px]">send</span>
                <span id="btn-submit-lapor-text">Kirim Laporan Tabayyun</span>
              </button>
            </div>

            <!-- Transparansi Penggunaan Layanan Pihak Ketiga -->
            <div class="mt-3 p-3 rounded-xl bg-blue-50/70 dark:bg-blue-950/40 border border-blue-200/80 dark:border-blue-800 flex items-start gap-2.5">
              <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-lg shrink-0 mt-0.5">info</span>
              <p class="text-[11px] text-blue-900/80 dark:text-blue-200 leading-relaxed">
                <strong class="font-semibold text-blue-900 dark:text-blue-200">Transparansi Penggunaan:</strong> Laporan yang Anda kirimkan akan diteruskan ke <strong>sistem penanganan aduan</strong> untuk diproses dan ditindaklanjuti. Identitas pelapor <strong>tetap aman dan terjaga (anonim)</strong>. Informasi ini semata-mata digunakan untuk membantu mencegah penipuan agar tidak memakan korban lain.
              </p>
            </div>
          </form>
        </div>

        <!-- Sidebar Guidelines Column -->
        <div class="lg:col-span-5 space-y-6">

          <!-- Alur Penanganan -->
          <div class="p-6 rounded-2xl bg-white border border-slate-200/90 shadow-sm space-y-4">
            <h3 class="font-display font-bold text-base text-slate-900 flex items-center gap-2">
              <span class="material-symbols-outlined text-emerald-600">route</span>
              <span>Protokol Verifikasi Aduan</span>
            </h3>
            <ol class="space-y-4 text-xs text-slate-600 stagger-children">
              <li class="flex items-start gap-3">
                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center shrink-0">1</span>
                <div>
                  <strong class="text-slate-800 block mb-0.5">Penerimaan & Verifikasi</strong>
                  Sistem mengamankan bukti dan menyamarkan identitas pelapor.
                </div>
              </li>
              <li class="flex items-start gap-3">
                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center shrink-0">2</span>
                <div>
                  <strong class="text-slate-800 block mb-0.5">Audit Sanad Siber</strong>
                  Tim analis mencocokkan indikator ancaman ke database CSIRT / BSSN.
                </div>
              </li>
              <li class="flex items-start gap-3">
                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center shrink-0">3</span>
                <div>
                  <strong class="text-slate-800 block mb-0.5">Tindakan & Eskalasi</strong>
                  Rekomendasi blokir domain dan peringatan publik jika terbukti bahaya masif.
                </div>
              </li>
            </ol>
          </div>

          <!-- Card Bantuan Cepat Darurat -->
          <div class="p-6 rounded-2xl bg-gradient-to-br from-red-50 to-orange-50 border border-red-200 text-slate-800">
            <div class="flex items-center gap-2 text-red-700 font-bold text-sm mb-2">
              <span class="material-symbols-outlined text-xl">support_agent</span>
              <span>Bantuan Cepat Darurat</span>
            </div>
            <p class="text-xs text-slate-600 leading-relaxed mb-4">
              Untuk insiden pembobolan rekening atau pencurian identitas WhatsApp yang sedang berlangsung:
            </p>
            <div class="space-y-1.5 text-xs font-semibold text-slate-900">
              <div class="flex items-center justify-between p-2.5 rounded-xl bg-white/90 border border-red-100">
                <span>Hotline Bebas Pulsa:</span>
                <span class="text-red-700 font-mono font-bold">1500-xxx</span>
              </div>
              <div class="flex items-center justify-between p-2.5 rounded-xl bg-white/90 border border-red-100">
                <span>Email Respon Cepat:</span>
                <span class="text-red-700 font-mono">aduan@aswajacybershield.id</span>
              </div>
            </div>
          </div>

        </div>

      </div>

      <!-- Tabel Rekapitulasi Laporan Publik -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
          <div>
            <h2 class="font-display font-bold text-lg text-slate-900">Rekapitulasi Pelaporan Publik</h2>
            <p class="text-xs text-slate-500">Transparansi penanganan aduan masyarakat yang telah disamarkan demi privasi.</p>
          </div>
          <div class="w-full sm:w-64">
            <input type="text" id="table-search-input" placeholder="Cari status atau tiket..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs sm:text-sm">
            <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
              <tr>
                <th class="py-3 px-4">No. Tiket</th>
                <th class="py-3 px-4">Kategori &amp; Objek Laporan</th>
                <th class="py-3 px-4">Status Penanganan</th>
                <th class="py-3 px-4">Waktu</th>
                <th class="py-3 px-4 text-right">Tindakan</th>
              </tr>
            </thead>
            <tbody id="table-reports-body" class="divide-y divide-slate-100 text-slate-700">
              <tr class="hover:bg-slate-50/70 transition-colors">
                  <td class="py-3.5 px-4 font-mono font-semibold text-slate-900">#CY-8821</td>
                  <td class="py-3.5 px-4">
                    <strong class="font-semibold text-slate-900 block">Phishing WhatsApp</strong>
                    <span class="text-xs text-slate-400 truncate max-w-[200px] block">Mengatasnamakan Kurir J&amp;T Resi .APK</span>
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Terblokir &amp; Eskalasi
                    </span>
                  </td>
                  <td class="py-3.5 px-4 text-slate-400 text-xs">10 menit lalu</td>
                  <td class="py-3.5 px-4 text-right">
                    <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-xs font-medium">Peringatan Publik</span>
                  </td>
                </tr><tr class="hover:bg-slate-50/70 transition-colors">
                  <td class="py-3.5 px-4 font-mono font-semibold text-slate-900">#CY-8819</td>
                  <td class="py-3.5 px-4">
                    <strong class="font-semibold text-slate-900 block">Fitnah &amp; Hoaks SARA</strong>
                    <span class="text-xs text-slate-400 truncate max-w-[200px] block">Broadcast manipulasi fatwa ormas</span>
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                      <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span> Validasi Sanad
                    </span>
                  </td>
                  <td class="py-3.5 px-4 text-slate-400 text-xs">25 menit lalu</td>
                  <td class="py-3.5 px-4 text-right">
                    <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-xs font-medium">Klarifikasi Tim</span>
                  </td>
                </tr><tr class="hover:bg-slate-50/70 transition-colors">
                  <td class="py-3.5 px-4 font-mono font-semibold text-slate-900">#CY-8815</td>
                  <td class="py-3.5 px-4">
                    <strong class="font-semibold text-slate-900 block">Promosi Judi Daring</strong>
                    <span class="text-xs text-slate-400 truncate max-w-[200px] block">Injeksi situs domain institusi pendidikan</span>
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                      <span class="w-1.5 h-1.5 rounded-full bg-purple-600"></span> Takedown Diajukan
                    </span>
                  </td>
                  <td class="py-3.5 px-4 text-slate-400 text-xs">2 jam lalu</td>
                  <td class="py-3.5 px-4 text-right">
                    <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-xs font-medium">Eskalasi Kominfo</span>
                  </td>
                </tr><tr class="hover:bg-slate-50/70 transition-colors">
                  <td class="py-3.5 px-4 font-mono font-semibold text-slate-900">#CY-8809</td>
                  <td class="py-3.5 px-4">
                    <strong class="font-semibold text-slate-900 block">Pencurian Akun &amp; OTP</strong>
                    <span class="text-xs text-slate-400 truncate max-w-[200px] block">Modus call center palsu perbankan</span>
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                      <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span> Selesai Ditangani
                    </span>
                  </td>
                  <td class="py-3.5 px-4 text-slate-400 text-xs">5 jam lalu</td>
                  <td class="py-3.5 px-4 text-right">
                    <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-xs font-medium">Pemulihan Akun</span>
                  </td>
                </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

  
  <dialog id="modal-sukses-lapor" class="backdrop:bg-slate-900/60 p-0 rounded-2xl shadow-2xl max-w-md w-full border border-slate-200">
    <div class="bg-white p-6 sm:p-8 text-center space-y-4">
      <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto">
        <span class="material-symbols-outlined text-4xl">task_alt</span>
      </div>
      <h3 class="font-display font-bold text-2xl text-slate-900">Laporan Berhasil Terkirim!</h3>
      <p class="text-sm text-slate-600 leading-relaxed">
        Jazakumullahu khairan atas peran aktif Anda dalam menjaga kebersihan ruang siber nasional. Laporan Anda telah masuk ke dalam antrean verifikasi tim Tabayyun.
      </p>
      <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-center">
        <span class="text-xs text-slate-500 uppercase tracking-wider block mb-1">Nomor Tiket Anda:</span>
        <span id="modal-ticket-id" class="font-mono font-extrabold text-2xl text-emerald-700 tracking-wider">#CY-8822</span>
        <span class="text-[11px] text-slate-400 block mt-1">Simpan nomor tiket ini untuk pengecekan status.</span>
      </div>
      <button type="button" id="btn-close-dialog" class="w-full py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm transition-all">
        Tutup &amp; Kembali
      </button>
    </div>
  </dialog>

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
          <li><a href="etika.blade.php" class="hover:text-emerald-400 transition-colors">Modul Etika &amp; Fiqih Siber</a></li>
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
  

  <script src="../js/navbar.js"></script>
  <script src="../js/scroll-reveal.js"></script>
  <script src="../js/api.js"></script>
  <script src="../js/lapor.js"></script>
  <script src="../js/animations.js"></script>
</body>
</html>










