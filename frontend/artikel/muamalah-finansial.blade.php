<!DOCTYPE html>
<html lang="id" class="scroll-smooth" data-theme="aswaja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Muamalah Finansial: Bebas Scam Judi Online & Pinjaman Ilegal - Edukasi Fiqih Keuangan Digital Aswaja CyberShield.">
  <link rel="icon" type="image/svg+xml" href="../../favicon.svg">
  <title>Muamalah Finansial Digital - Aswaja CyberShield</title>

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
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
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
  <main class="flex-grow py-10 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Breadcrumbs -->
      <nav class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-6">
        <a href="../index.blade.php" class="hover:text-emerald-600 transition-colors">Beranda</a>
        <span>/</span>
        <a href="../etika.blade.php" class="hover:text-emerald-600 transition-colors">Etika & Fiqih Siber</a>
        <span>/</span>
        <span class="text-slate-800 font-semibold truncate">Muamalah Finansial Bebas Scam</span>
      </nav>

      <!-- Article Header -->
      <header class="mb-10 pb-8 border-b border-slate-200">
        <h1 class="font-display font-extrabold text-3xl sm:text-4xl lg:text-5xl text-slate-900 tracking-tight leading-tight mb-4">
          Muamalah Finansial: Bebas Scam Judi Online & Pinjaman Ilegal
        </h1>
        <p class="text-base sm:text-lg text-slate-600 leading-relaxed font-medium">
          Mengenali perangkap manipulasi psikologis algoritma judi daring (*maysir* modern) dan bahaya jeratan rentenir digital pinjol ilegal yang merusak stabilitas keluarga.
        </p>
      </header>

      <!-- Article Body -->
      <article class="prose prose-slate max-w-none text-slate-700 leading-relaxed space-y-6 text-base">
        
        <div class="p-5 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 text-emerald-950 not-prose my-6">
          <div class="flex items-start gap-3">
            <span class="material-symbols-outlined text-emerald-600 text-2xl shrink-0 mt-0.5">balance</span>
            <div>
              <p class="font-serif italic text-base leading-relaxed mb-2">
                "Wahai orang-orang yang beriman! Sesungguhnya minuman keras, berjudi, (berkurban untuk) berhala, dan mengundi nasib dengan panah, adalah perbuatan keji dan termasuk perbuatan setan. Maka jauhilah perbuatan-perbuatan itu agar kamu beruntung."
              </p>
              <span class="text-xs font-bold text-emerald-800 block">— QS. Al-Ma'idah: 90</span>
            </div>
          </div>
        </div>

        <h2 class="font-display font-bold text-2xl text-slate-900 mt-8 mb-3">
          1. Tipu Muslihat Algoritma Judi Online (Maysir Modern)
        </h2>
        <p>
          Banyak anak muda dan masyarakat awam mengira bahwa mesin judi slot atau taruhan daring bekerja berdasarkan keberuntungan (*luck*). Kenyataannya, seluruh sistem judi daring dikendalikan oleh kode algoritma yang sudah diprogram pasti memenangkan bandar (*the house always wins*).
        </p>
        <p>
          Pada beberapa putaran awal, sistem sengaja memberikan kemenangan kecil (*dopamine trap*) untuk menimbulkan ilusi bahwa korban bisa menang besar. Setelah korban kecanduan dan menaikkan deposit, algoritma secara matematis akan menguras seluruh modal korban.
        </p>

        <h2 class="font-display font-bold text-2xl text-slate-900 mt-8 mb-3">
          2. Lingkaran Setan Pinjol Ilegal (Riba & Pemerasan)
        </h2>
        <p>
          Ketika saldo tabungan habis akibat judi daring atau kebutuhan mendesak, korban sering kali lari ke aplikasi pinjaman online (pinjol) ilegal. Modus pinjol ilegal ini sangat merusak:
        </p>
        <ul class="space-y-3 list-disc pl-5">
          <li>
            <strong>Bunga dan Biaya Administrasi Fiktif:</strong> Meminjam Rp 1.000.000, dana cair hanya Rp 600.000, namun dalam waktu 7 hari wajib mengembalikan Rp 1.500.000 (riba berlipat ganda).
          </li>
          <li>
            <strong>Penyedotan Seluruh Kontak Ponsel:</strong> Saat aplikasi diinstal, ia meminta izin kontak darurat. Ketika jatuh tempo, penagih (*debt collector*) meneror seluruh kerabat, atasan kantor, dan keluarga dengan pesan fitnah serta caci maki.
          </li>
          <li>
            <strong>Modus Gestun (Gesek Tunai) dan Paylater Ilegal:</strong> Menggadaikan limit kredit dengan imbalan uang tunai potongan tinggi yang melanggar aturan perbankan dan syariat.
          </li>
        </ul>

        <h2 class="font-display font-bold text-2xl text-slate-900 mt-8 mb-3">
          3. Solusi Fiqih dan Hukum Menghadapi Jeratan
        </h2>
        <p>
          Jika Anda atau orang terdekat sedang terjebak dalam lingkaran ini, berikut panduan langkah penyelesaiannya:
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 not-prose my-6">
          <div class="p-4 rounded-xl bg-slate-100 border border-slate-200">
            <span class="font-bold text-sm text-slate-900 block mb-1">1. Putus Akses Total</span>
            <p class="text-xs text-slate-600">Blokir situs/aplikasi judi, hapus akun dompet digital yang tertaut, dan ganti nomor WhatsApp jika diteror penagih ilegal.</p>
          </div>
          <div class="p-4 rounded-xl bg-slate-100 border border-slate-200">
            <span class="font-bold text-sm text-slate-900 block mb-1">2. Terbuka kepada Keluarga</span>
            <p class="text-xs text-slate-600">Jangan menutupi hutang dengan berhutang lagi (*gali lubang tutup lubang*). Kejujuran kepada keluarga adalah langkah awal pemulihan.</p>
          </div>
          <div class="p-4 rounded-xl bg-slate-100 border border-slate-200">
            <span class="font-bold text-sm text-slate-900 block mb-1">3. Cek Legalitas OJK</span>
            <p class="text-xs text-slate-600">Hanya bertransaksi dengan lembaga keuangan yang terdaftar resmi di Otoritas Jasa Keuangan (OJK). Pinjol ilegal tidak memiliki kekuatan hukum penagihan paksa.</p>
          </div>
          <div class="p-4 rounded-xl bg-slate-100 border border-slate-200">
            <span class="font-bold text-sm text-slate-900 block mb-1">4. Laporkan Tautan / Rekening</span>
            <p class="text-xs text-slate-600">Gunakan Portal Lapor Judol & Siber untuk melaporkan nomor rekening penampung judi dan nomor kontak penagih teror agar diblokir bersama BSSN/Kominfo.</p>
          </div>
        </div>

      </article>

      <!-- Bottom Action Navigation -->
      <div class="mt-12 pt-8 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
        <a href="../etika.blade.php" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm transition-all">
          <span class="material-symbols-outlined text-[18px]">arrow_back</span>
          <span>Kembali ke Modul Etika</span>
        </a>
        <div class="flex items-center gap-2 xl:gap-3 shrink-0">
          <a href="../lapor.blade.php" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm shadow-sm transition-all">
            <span class="material-symbols-outlined text-[18px]">campaign</span>
            <span>Laporkan Situs Judi / Pinjol Ilegal</span>
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








