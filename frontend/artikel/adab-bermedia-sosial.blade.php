<!DOCTYPE html>
<html lang="id" class="scroll-smooth" data-theme="aswaja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Adab Bermedia Sosial: Etika Berkomentar Menurut Nilai Ahlussunnah - Panduan literasi dan adab digital Aswaja CyberShield.">
  <link rel="icon" type="image/svg+xml" href="../../favicon.svg">
  <title>Adab Bermedia Sosial - Aswaja CyberShield</title>

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
  <link rel="stylesheet" href="../../css/style.css">
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col selection:bg-emerald-100 selection:text-emerald-900">

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

  <!-- Navigation Header -->
  <header class="sticky top-0 z-50 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 transition-all duration-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">
        <!-- Logo & Brand Name -->
        <div class="flex items-center gap-3 xl:gap-8 shrink-0">
          <a href="../index.blade.php" class="flex items-center gap-2.5 xl:gap-3 group focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 rounded-lg py-1 shrink-0">
            <img src="../../logo-shield.png" alt="Logo Resmi Aswaja CyberShield" class="w-10 h-10 object-contain group-hover:scale-105 transition-transform">
            <div class="flex flex-col">
              <span class="font-display font-bold text-lg text-slate-900 dark:text-white tracking-tight leading-tight group-hover:text-emerald-700 transition-colors">
                Aswaja <span class="text-emerald-600">CyberShield</span>
              </span>
              <span class="text-[10px] font-medium text-slate-500 dark:text-slate-400 tracking-wider uppercase">Literasi & Keamanan Siber</span>
            </div>
          </a>
          <nav class="hidden lg:flex items-center gap-1 xl:gap-1.5 shrink-0" aria-label="Navigasi Utama">
            <a href="../index.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition-all">Beranda</a>
            <a href="../tabayyun.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition-all">AI Tabayyun</a>
            <a href="../etika.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap text-emerald-700 bg-emerald-50 shadow-sm">Etika Siber</a>
            <a href="../cyber-guard.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition-all">Cyber Guard</a>
            <a href="../lapor.blade.php" class="px-2.5 xl:px-3.5 py-2 rounded-lg text-xs xl:text-sm font-semibold whitespace-nowrap text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition-all">Lapor</a>
          </nav>
        </div>

        <div class="flex items-center gap-2 xl:gap-3 shrink-0">
          <a href="../lapor.blade.php" class="inline-flex items-center gap-1.5 xl:gap-2 px-3 xl:px-4 py-2 rounded-xl bg-red-50 text-red-700 whitespace-nowrap shrink-0 hover:bg-red-100 border border-red-200/70 text-sm font-semibold transition-all">
            <span class="material-symbols-outlined text-[18px] text-red-600">emergency</span>
            <span class="hidden sm:inline whitespace-nowrap">Lapor Darurat</span>
          </a>
          <a href="../tabayyun.blade.php" class="hidden sm:inline-flex items-center gap-1.5 xl:gap-2 px-3 xl:px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs xl:text-sm font-semibold whitespace-nowrap shadow-sm shrink-0 transition-all">
            <span class="material-symbols-outlined text-[18px]">bolt</span>
            <span class="whitespace-nowrap">Cek Fakta</span>
          </a>
          <!-- Dark Mode Toggle -->
          <button type="button" id="darkmode-toggle" aria-label="Ganti Mode Tampilan" class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <span class="material-symbols-outlined text-xl" id="darkmode-icon">dark_mode</span>
          </button>
          <button type="button" id="mobile-menu-btn" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100">
            <span class="material-symbols-outlined text-2xl">menu</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Drawer -->
    <div id="mobile-menu" class="hidden lg:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-5 space-y-1.5 shadow-xl">
      <a href="../index.blade.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-base font-semibold text-slate-600 hover:bg-slate-100">Beranda</a>
      <a href="../tabayyun.blade.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-base font-semibold text-slate-600 hover:bg-slate-100">AI Tabayyun</a>
      <a href="../etika.blade.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-base font-semibold text-emerald-700 bg-emerald-50">Etika Siber</a>
      <a href="../cyber-guard.blade.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-base font-semibold text-slate-600 hover:bg-slate-100">Cyber Guard</a>
      <a href="../lapor.blade.php" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-base font-semibold text-slate-600 hover:bg-slate-100">Lapor</a>
      <div class="pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" class="darkmode-toggle-btn w-full inline-flex items-center justify-between px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-medium text-sm transition-colors">
          <span class="inline-flex items-center gap-2">
            <span class="material-symbols-outlined text-[20px] darkmode-icon">dark_mode</span>
            <span class="darkmode-text">Ganti Mode Tampilan</span>
          </span>
          <span class="text-xs text-slate-400">Terang / Gelap</span>
        </button>
      </div>
    </div>
  </header>

  <!-- Main Article Content -->
  <main class="flex-grow py-10 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Breadcrumbs -->
      <nav class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-6">
        <a href="../index.blade.php" class="hover:text-emerald-600 transition-colors">Beranda</a>
        <span>/</span>
        <a href="../etika.blade.php" class="hover:text-emerald-600 transition-colors">Etika & Fiqih Siber</a>
        <span>/</span>
        <span class="text-slate-800 font-semibold truncate">Adab Bermedia Sosial</span>
      </nav>

      <!-- Article Header -->
      <header class="mb-10 pb-8 border-b border-slate-200">
        <h1 class="font-display font-extrabold text-3xl sm:text-4xl lg:text-5xl text-slate-900 tracking-tight leading-tight mb-4">
          Adab Bermedia Sosial: Etika Berkomentar Menurut Nilai Ahlussunnah
        </h1>
        <p class="text-base sm:text-lg text-slate-600 leading-relaxed font-medium">
          Panduan menjaga lisan dan jempol digital dari caci maki di kolom komentar, menghindari provokasi adu domba siber, dan membiasakan tradisi Tabayyun sebelum bereaksi emosional.
        </p>
      </header>

      <!-- Article Body -->
      <article class="prose prose-slate max-w-none text-slate-700 leading-relaxed space-y-6 text-base">
        
        <div class="p-5 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 text-emerald-950 not-prose my-6">
          <div class="flex items-start gap-3">
            <span class="material-symbols-outlined text-emerald-600 text-2xl shrink-0 mt-0.5">format_quote</span>
            <div>
              <p class="font-serif italic text-base leading-relaxed mb-2">
                "Barangsiapa yang beriman kepada Allah dan hari akhir, hendaklah dia berkata yang baik atau diam."
              </p>
              <span class="text-xs font-bold text-emerald-800 block">— HR. Bukhari & Muslim</span>
            </div>
          </div>
        </div>

        <h2 class="font-display font-bold text-2xl text-slate-900 mt-8 mb-3">
          1. Ruang Publik Digital dan Tanggung Jawab Moral
        </h2>
        <p>
          Media sosial sering kali disalahpahami sebagai ruang tanpa batas di mana seseorang bebas mengetikkan apa saja atas nama kebebasan berekspresi. Di balik tabir layar gawai dan anonimitas akun, banyak pengguna kehilangan rasa malu dan empati yang biasanya mereka miliki saat bertatap muka langsung.
        </p>
        <p>
          Dalam pandangan <strong>Ahlussunnah wal Jama'ah (Aswaja)</strong>, setiap ketikan huruf, emoji sindiran, dan komentar di ruang siber dicatat oleh malaikat Raqib dan Atid. Status digital adalah representasi dari lisan (perkataan). Konsep <em>Hifzhul Lisan</em> (menjaga lisan) yang diajarkan para ulama salaf kini bertransformasi menjadi <strong>Hifzhul Ashabi'</strong> (menjaga jempol digital).
        </p>

        <h2 class="font-display font-bold text-2xl text-slate-900 mt-8 mb-3">
          2. Tiga Pilar Kritik Beradab dalam Tradisi Aswaja
        </h2>
        <p>
          Islam tidak melarang kritik, koreksi, atau diskusi kritis di media sosial. Namun, cara penyampaian menentukan apakah tindakan tersebut bernilai ibadah atau justru maksiat lisan. Tiga adab utama yang wajib dijaga:
        </p>
        <ul class="space-y-3 list-disc pl-5">
          <li>
            <strong>Bil-Hikmah (Dengan Kebijaksanaan):</strong> Mengetahui kapan waktu yang tepat berkomentar dan apakah kritik tersebut menyelesaikan masalah atau malah memperkeruh suasana.
          </li>
          <li>
            <strong>Al-Mau'izhah Al-Hasanah (Nasihat yang Santun):</strong> Menggunakan kalimat yang tidak mempermalukan orang lain di depan publik (*fadhlihah*), melainkan mengajak pada kebaikan.
          </li>
          <li>
            <strong>Al-Jidal bil-Lati Hiya Ahsan (Berdebat dengan Cara Terbaik):</strong> Mengedepankan argumentasi berbasis data dan fakta, bukan serangan pribadi (*ad hominem*) atau ejekan fisik.
          </li>
        </ul>

        <h2 class="font-display font-bold text-2xl text-slate-900 mt-8 mb-3">
          3. Bahaya Algoritma Emosi dan Adu Domba (Namimah Modern)
        </h2>
        <p>
          Algoritma platform digital dirancang untuk mempromosikan konten yang memicu kemarahan (*outrage economy*) karena menghasilkan *engagement* paling tinggi. Akun-akun provokatif sengaja memproduksi potongan video singkat atau judul provokatif agar warganet saling hujat di kolom komentar.
        </p>
        <p>
          Seorang Muslim yang berpegang teguh pada nilai moderasi (*tawassuth* dan *tawazun*) tidak boleh membiarkan jempolnya dijadikan pion algoritma adu domba. Menahan diri untuk tidak ikut meramaikan kolom komentar provokatif adalah bentuk nyata dari prinsip <strong>Tawaqquf</strong>.
        </p>

        <!-- Practical Checklist Box -->
        <div class="mt-8 p-6 rounded-2xl bg-white border border-slate-200 shadow-sm not-prose">
          <h3 class="font-display font-bold text-lg text-slate-900 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-emerald-600">checklist</span>
            Checklist 5 Detik Sebelum Menekan Tombol "Kirim Komentar"
          </h3>
          <ul class="space-y-3 text-sm text-slate-600">
            <li class="flex items-start gap-2.5">
              <span class="material-symbols-outlined text-emerald-600 text-lg shrink-0">check_circle</span>
              <span><strong>Apakah ini fakta yang sudah diverifikasi?</strong> Hindari berkomentar atas dasar spekulasi atau potongan klip viral.</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="material-symbols-outlined text-emerald-600 text-lg shrink-0">check_circle</span>
              <span><strong>Apakah membawa kemaslahatan?</strong> Apakah komentar ini menyelesaikan kebingungan atau menambah kebencian?</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="material-symbols-outlined text-emerald-600 text-lg shrink-0">check_circle</span>
              <span><strong>Apakah kata-katanya pantas dibaca keluarga sendiri?</strong> Jika Anda malu jika komentar tersebut dibaca orang tua atau anak Anda, jangan kirimkan.</span>
            </li>
          </ul>
        </div>

      </article>

      <!-- Bottom Action Navigation -->
      <div class="mt-12 pt-8 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
        <a href="../etika.blade.php" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm transition-all">
          <span class="material-symbols-outlined text-[18px]">arrow_back</span>
          <span>Kembali ke Modul Etika</span>
        </a>
        <div class="flex items-center gap-2 xl:gap-3 shrink-0">
          <a href="../cyber-guard.blade.php" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-sm transition-all">
            <span class="material-symbols-outlined text-[18px]">psychology</span>
            <span>Uji Pemahaman di Cyber Guard</span>
          </a>
        </div>
      </div>



    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
        <div class="md:col-span-6 space-y-4">
          <div class="flex items-center gap-2 xl:gap-3 shrink-0">
            <img src="../../logo-shield.png" alt="Logo Resmi Aswaja CyberShield" class="w-9 h-9 object-contain">
            <span class="font-display font-bold text-xl text-white tracking-tight">Aswaja CyberShield</span>
          </div>
          <p class="text-sm text-slate-400 leading-relaxed max-w-md">
            Inisiatif ketahanan digital berbasis prinsip Tabayyun dan nilai Ahlussunnah wal Jama'ah untuk mendeteksi serta membentengi masyarakat dari bahaya judi online, penipuan finansial, dan jebakan investasi ilegal.
          </p>
          <div class="pt-2 text-xs text-emerald-400 font-medium">Amanah &bull; Tabayyun &bull; Maslahah Ummah</div>
        </div>
        <div class="md:col-span-3 space-y-3">
          <h4 class="font-display font-semibold text-sm text-white uppercase tracking-wider">Navigasi Utama</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="../index.blade.php" class="hover:text-emerald-400 transition-colors">Beranda Utama</a></li>
            <li><a href="../tabayyun.blade.php" class="hover:text-emerald-400 transition-colors">Analisis Tautan Tabayyun</a></li>
            <li><a href="../etika.blade.php" class="hover:text-emerald-400 transition-colors">Modul Etika &amp; Fiqih Siber</a></li>
            <li><a href="../cyber-guard.blade.php" class="hover:text-emerald-400 transition-colors">Cyber Guard Test (Kuis)</a></li>
          </ul>
        </div>
        <div class="md:col-span-3 space-y-3">
          <h4 class="font-display font-semibold text-sm text-white uppercase tracking-wider">Layanan Terpadu</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="../lapor.blade.php" class="hover:text-red-400 transition-colors">Portal Lapor</a></li>
            <li><a href="../etika.blade.php" class="hover:text-emerald-400 transition-colors">Pedoman Literasi</a></li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <script src="../../js/navbar.js"></script>
</body>
</html>








