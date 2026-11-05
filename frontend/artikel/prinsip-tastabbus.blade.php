<!DOCTYPE html>
<html lang="id" class="scroll-smooth" data-theme="aswaja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Prinsip Tastabbus: Teliti Kebenaran Substansi dan Konteks Informasi - Kajian Metodologi 4T Aswaja CyberShield.">
  <link rel="icon" type="image/svg+xml" href="../../favicon.svg">
  <title>Prinsip Tastabbus (T2) - Aswaja CyberShield</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">
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
  <main class="flex-grow py-8 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Breadcrumbs -->
      <nav class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-6">
        <a href="../index.blade.php" class="hover:text-emerald-600 transition-colors">Beranda</a>
        <span>/</span>
        <a href="../etika.blade.php" class="hover:text-emerald-600 transition-colors">Etika &amp; Fiqih Siber</a>
        <span>/</span>
        <span class="text-slate-800 font-semibold truncate">Prinsip Tastabbus (T2)</span>
      </nav>

      <!-- 2-Column Responsive Layout (Penuh & Seimbang) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">

        <!-- Kolom Kiri: Naskah Utama Artikel (lg:col-span-8) -->
        <div class="lg:col-span-8 bg-white p-6 sm:p-10 rounded-3xl border border-slate-200/90 shadow-sm">
          
          <!-- Article Header -->
          <header class="mb-8 pb-8 border-b border-slate-200">
            <h1 class="font-display font-extrabold text-3xl sm:text-4xl lg:text-5xl text-slate-900 tracking-tight leading-tight mb-4">
              Tastabbus (T2): Teliti Kebenaran Substansi dan Konteks Informasi
            </h1>
            <p class="text-base sm:text-lg text-slate-600 leading-relaxed font-medium">
              Penyelidikan seksama terhadap materi informasi (kritik matan), memastikan potongan video atau foto tidak dipelintir di luar konteks aslinya untuk tujuan disinformasi.
            </p>
          </header>

          <!-- Article Body -->
          <article class="prose prose-slate max-w-none text-slate-700 leading-relaxed space-y-6 text-base">
            
            <div class="p-6 rounded-2xl bg-emerald-50/80 border border-emerald-200 text-emerald-950 not-prose my-6">
              <div class="flex items-start gap-3.5">
                <span class="material-symbols-outlined text-emerald-600 text-3xl shrink-0 mt-0.5">format_quote</span>
                <div>
                  <p class="font-serif italic text-base sm:text-lg leading-relaxed mb-2">
                    "Cukuplah seseorang dianggap berdusta apabila dia menceritakan kembali segala hal yang didengarnya (tanpa menyaringnya lebih dulu)."
                  </p>
                  <span class="text-xs font-bold text-emerald-800 tracking-wider block uppercase">— HR. Muslim</span>
                </div>
              </div>
            </div>

            <h2 class="font-display font-bold text-2xl text-slate-900 mt-8 mb-3">
              1. Hakikat Tastabbus: Meneliti Isi Pesan (Kritik Matan)
            </h2>
            <p>
              Setelah seorang pengguna berhasil mengambil jeda (T1: Tawaqquf), langkah berikutnya adalah melakukan <strong>Tastabbus</strong> (??????? / ???? ?????). Dalam kaidah ushul fiqih dan ilmu hadits, Tastabbus bermakna menyelidiki isi redaksi dan substansi kabar (*naqdul matn*) apakah masuk akal, utuh, ataukah sarat rekayasa.
            </p>
            <p>
              Banyak informasi hoaks yang sebenarnya runtuh dengan sendirinya jika kita membaca isi pesan secara menyeluruh dan kritis. Sering kali judul (*headline*) dibuat sangat bombastis dan mencengangkan, namun ketika dibaca kalimat demi kalimat di dalamnya, tidak ada data valid atau argumen logis yang mendukung judul tersebut.
            </p>

            <h2 class="font-display font-bold text-2xl text-slate-900 mt-8 mb-3">
              2. Waspadai Manipulasi "Konteks Hilang" (Out-of-Context)
            </h2>
            <p>
              Disinformasi paling berbahaya bukanlah kabar bohong yang 100% palsu, melainkan **fakta yang dipotong di luar konteksnya**. Modus ini sangat sering menyerang tokoh ulama dan pejabat publik:
            </p>
            <ul class="space-y-3 list-disc pl-5">
              <li>
                <strong>Video Ceramah 10 Detik:</strong> Mengambil potongan kalimat di mana seorang ustadz sedang menceritakan analogi kelompok sesat, lalu memotongnya seolah-olah ustadz tersebut sendiri yang berfatwa demikian.
              </li>
              <li>
                <strong>Foto Lama yang Didaur Ulang:</strong> Menggunakan foto bencana alam atau kerusuhan 5 tahun lalu, lalu diberi keterangan seakan-akan baru saja terjadi kemarin sore untuk memicu kepanikan warga.
              </li>
              <li>
                <strong>Kutipan Palsu Tokoh (Fake Quotes):</strong> Menggabungkan foto tokoh terhormat dengan kalimat rasis atau provokatif yang tidak pernah diucapkannya sama sekali.
              </li>
            </ul>

            <h2 class="font-display font-bold text-2xl text-slate-900 mt-8 mb-3">
              3. Tiga Uji Kritis dalam Prinsip Tastabbus
            </h2>
            <p>
              Ketika Anda membaca narasi atau tawaran mencurigakan, ajukan 3 pertanyaan kunci ini:
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 not-prose my-6">
              <div class="p-4 rounded-xl bg-slate-100 border border-slate-200">
                <span class="font-bold text-sm text-slate-900 block mb-1">1. Uji Kelogisan (Logic Check)</span>
                <p class="text-xs text-slate-600">Apakah masuk akal ada lowongan kerja hanya dengan likes YouTube bisa digaji Rp 500.000 per hari?</p>
              </div>
              <div class="p-4 rounded-xl bg-slate-100 border border-slate-200">
                <span class="font-bold text-sm text-slate-900 block mb-1">2. Uji Keutuhan (Context Check)</span>
                <p class="text-xs text-slate-600">Cari video atau pidato versi lengkapnya untuk melihat apa kalimat sebelum dan sesudah potongan viral tersebut.</p>
              </div>
              <div class="p-4 rounded-xl bg-slate-100 border border-slate-200">
                <span class="font-bold text-sm text-slate-900 block mb-1">3. Uji Konsistensi (Data Check)</span>
                <p class="text-xs text-slate-600">Apakah angka, tanggal, dan nama lokasi di dalam teks sesuai dengan laporan resmi badan statistik atau berita resmi?</p>
              </div>
            </div>

          </article>

          <!-- Bottom Action Navigation -->
          <div class="mt-10 pt-8 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
            <a href="prinsip-tawaqquf.blade.php" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm transition-all w-full sm:w-auto justify-center">
              <span class="material-symbols-outlined text-[18px]">arrow_back</span>
              <span>Kembali ke T1: Tawaqquf</span>
            </a>
            <a href="prinsip-tatsabbut.blade.php" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-sm transition-all w-full sm:w-auto justify-center">
              <span>Lanjut ke T3: Tatsabbut</span>
              <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
          </div>

        </div>

        <!-- Kolom Kanan: Sidebar Interaktif & Widget Pendukung (lg:col-span-4) -->
        <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-28">

          <!-- Widget 1: Rangkaian Metodologi 4T -->
          <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
              <h3 class="font-display font-bold text-base text-slate-900 flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600 text-xl">route</span>
                Alur Prinsip 4T
              </h3>
              <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">Metodologi</span>
            </div>
            
            <nav class="space-y-2 text-xs">
              <a href="prinsip-tawaqquf.blade.php" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 border border-slate-100 font-medium text-slate-700 hover:text-emerald-700 transition-all">
                <div class="flex items-center gap-2.5">
                  <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-600 font-bold flex items-center justify-center text-[11px]">T1</span>
                  <span>Tawaqquf (Tahan Diri)</span>
                </div>
                <span class="material-symbols-outlined text-slate-400 text-[18px]">chevron_right</span>
              </a>

              <!-- T2 (Active) -->
              <a href="prinsip-tastabbus.blade.php" class="flex items-center justify-between p-3 rounded-xl bg-emerald-50 border border-emerald-200 font-semibold text-emerald-900 transition-all">
                <div class="flex items-center gap-2.5">
                  <span class="w-6 h-6 rounded-lg bg-emerald-600 text-white font-bold flex items-center justify-center text-[11px]">T2</span>
                  <span>Tastabbus (Teliti Isi)</span>
                </div>
                <span class="material-symbols-outlined text-emerald-700 text-[18px]">check_circle</span>
              </a>

              <a href="prinsip-tatsabbut.blade.php" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 border border-slate-100 font-medium text-slate-700 hover:text-emerald-700 transition-all">
                <div class="flex items-center gap-2.5">
                  <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-600 font-bold flex items-center justify-center text-[11px]">T3</span>
                  <span>Tatsabbut (Cek Sumber)</span>
                </div>
                <span class="material-symbols-outlined text-slate-400 text-[18px]">chevron_right</span>
              </a>

              <a href="prinsip-taaqqul.blade.php" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 border border-slate-100 font-medium text-slate-700 hover:text-emerald-700 transition-all">
                <div class="flex items-center gap-2.5">
                  <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-600 font-bold flex items-center justify-center text-[11px]">T4</span>
                  <span>Ta'aqqul (Timbang Dampak)</span>
                </div>
                <span class="material-symbols-outlined text-slate-400 text-[18px]">chevron_right</span>
              </a>
            </nav>
          </div>

          <!-- Widget 2: Uji Langsung Pesan di AI Tabayyun -->
          <div class="card-hover-effect cursor-pointer p-6 rounded-3xl bg-gradient-to-br from-emerald-600 to-emerald-800 text-white shadow-md shadow-emerald-700/20 space-y-4">
            <div class="w-10 h-10 rounded-2xl bg-white/15 flex items-center justify-center text-white">
              <span class="material-symbols-outlined text-2xl">bolt</span>
            </div>
            <div>
              <h3 class="font-display font-bold text-lg leading-tight mb-1">Dapat Tautan Mencurigakan?</h3>
              <p class="text-xs text-emerald-100 leading-relaxed">
                Uji tautan investasi bodong, robot trading, atau skema deposit mencurigakan langsung dengan mesin detektor AI Tabayyun.
              </p>
            </div>
            <a href="../tabayyun.blade.php" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-white text-emerald-900 font-bold text-xs hover:bg-emerald-50 transition-all shadow-sm">
              <span>Buka AI Tabayyun Checker</span>
              <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
          </div>

        </aside>

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







