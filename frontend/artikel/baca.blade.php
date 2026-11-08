<!DOCTYPE html>
<html lang="id" class="scroll-smooth" data-theme="aswaja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Artikel Literasi & Fiqih Siber - Aswaja CyberShield">
  <link rel="icon" type="image/svg+xml" href="../../favicon.svg">
  <title id="page-title">Artikel Edukasi - Aswaja CyberShield</title>

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
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/ui-kit.css">
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans antialiased min-h-screen flex flex-col selection:bg-emerald-100 selection:text-emerald-900">

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
              <span class="text-[10px] font-medium text-slate-500 dark:text-slate-400 tracking-wider uppercase">Literasi &amp; Keamanan Siber</span>
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
    </div>
  </header>

  <!-- Main Article Content -->
  <main class="flex-grow pt-8 md:pt-12 pb-6 md:pb-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Breadcrumbs -->
      <nav class="flex items-center gap-2 text-xs font-medium text-slate-500 dark:text-slate-400 mb-6">
        <a href="../index.blade.php" class="hover:text-emerald-600 transition-colors">Beranda</a>
        <span>/</span>
        <a href="../etika.blade.php" class="hover:text-emerald-600 transition-colors">Etika &amp; Fiqih Siber</a>
        <span>/</span>
        <span id="article-breadcrumb" class="text-slate-800 dark:text-slate-200 font-semibold truncate">Memuat Artikel...</span>
      </nav>

      <!-- Cover Image Container -->
      <div id="article-cover-wrap" class="hidden mb-8 rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-lg bg-slate-900">
        <img id="article-cover-img" src="" alt="Sampul Artikel" class="w-full max-h-[420px] object-cover">
      </div>

      <!-- Article Header -->
      <header class="mb-8 pb-8 border-b border-slate-200 dark:border-slate-800">
        <div class="flex flex-wrap items-center gap-2.5 mb-4">
          <span id="article-category" class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
            Literasi Siber
          </span>
          <span class="text-slate-300 dark:text-slate-700">&bull;</span>
          <span id="article-date" class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
            -
          </span>
        </div>

        <h1 id="article-title" class="font-display font-extrabold text-3xl sm:text-4xl lg:text-5xl text-slate-900 dark:text-white tracking-tight leading-tight mb-4">
          Memuat Artikel...
        </h1>

        <p id="article-summary" class="text-base sm:text-lg text-slate-600 dark:text-slate-300 leading-relaxed font-medium mb-4">
        </p>

        <div class="flex items-center gap-2.5 pt-2 text-xs text-slate-500 dark:text-slate-400">
          <span class="material-symbols-outlined text-emerald-600 text-[18px]">verified_user</span>
          <span>Ditulis oleh <strong id="article-author" class="text-slate-800 dark:text-slate-200 font-semibold">Tim Literasi Aswaja</strong></span>
        </div>
      </header>

      <!-- Article Body -->
      <article id="article-body" class="prose prose-slate dark:prose-invert max-w-none text-slate-700 dark:text-slate-200 leading-relaxed space-y-5 text-base">
        <p class="text-slate-400 italic">Sedang memuat isi materi dari basis data...</p>
      </article>

      <!-- Bottom Action Navigation -->
      <div class="mt-12 pt-8 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
        <a href="../etika.blade.php" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold text-sm transition-all">
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
  <footer class="bg-gradient-to-b from-[#064e3b] via-[#022c22] to-[#011a14] dark:bg-none dark:bg-black dark:from-black dark:via-black dark:to-black text-emerald-100/90 dark:text-slate-300 border-t border-emerald-900/40 dark:border-slate-800 mt-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
        <div class="md:col-span-6 space-y-4">
          <div class="flex items-center gap-2 xl:gap-3 shrink-0">
            <img src="../../logo-shield.png" alt="Logo Resmi Aswaja CyberShield" class="w-9 h-9 object-contain">
            <span class="font-display font-bold text-xl text-white tracking-tight">Aswaja CyberShield</span>
          </div>
          <p class="text-sm text-emerald-100/80 dark:text-slate-400 leading-relaxed max-w-md">
            Inisiatif ketahanan digital berbasis prinsip Tabayyun dan nilai Ahlussunnah wal Jama'ah untuk mendeteksi serta membentengi masyarakat dari bahaya judi online, penipuan finansial, dan jebakan investasi ilegal.
          </p>
          <div class="pt-2 text-xs text-emerald-300 dark:text-emerald-400 font-medium">Amanah &bull; Tabayyun &bull; Maslahah Ummah</div>
        </div>
        <div class="md:col-span-3 space-y-3">
          <h4 class="font-display font-semibold text-sm text-white uppercase tracking-wider">Navigasi Utama</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="../index.blade.php" class="text-emerald-200/90 hover:text-white dark:text-slate-300 dark:hover:text-emerald-400 transition-colors">Beranda Utama</a></li>
            <li><a href="../tabayyun.blade.php" class="text-emerald-200/90 hover:text-white dark:text-slate-300 dark:hover:text-emerald-400 transition-colors">Analisis Tautan Tabayyun</a></li>
            <li><a href="../etika.blade.php" class="text-emerald-200/90 hover:text-white dark:text-slate-300 dark:hover:text-emerald-400 transition-colors">Modul Etika &amp; Fiqih Siber</a></li>
            <li><a href="../cyber-guard.blade.php" class="text-emerald-200/90 hover:text-white dark:text-slate-300 dark:hover:text-emerald-400 transition-colors">Cyber Guard Test (Kuis)</a></li>
          </ul>
        </div>
        <div class="md:col-span-3 space-y-3">
          <h4 class="font-display font-semibold text-sm text-white uppercase tracking-wider">Layanan</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="../lapor.blade.php" class="text-rose-300 hover:text-white dark:text-rose-400 dark:hover:text-rose-300 transition-colors">Lapor</a></li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <script src="../../js/api.js"></script>
  <script src="../../js/navbar.js"></script>
  <script src="../../js/scroll-reveal.js"></script>
  <script src="../../js/animations.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', async () => {
      const params = new URLSearchParams(window.location.search);
      const targetId = params.get('id');
      let targetSlug = params.get('slug');

      if (!targetSlug) {
        const pathParts = window.location.pathname.split('/').filter(Boolean);
        const lastPart = pathParts[pathParts.length - 1] || '';
        const cleanSlug = lastPart.replace(/\.blade\.php$/i, '').replace(/\.php$/i, '');
        if (cleanSlug && cleanSlug !== 'baca') {
          targetSlug = cleanSlug;
        }
      }

      try {
        const res = await window.ApiService.getArticles();
        const articles = Array.isArray(res) ? res : (res?.data || []);
        let article = null;

        if (targetId) {
          article = articles.find(a => String(a.id) === String(targetId));
        }
        if (!article && targetSlug) {
          article = articles.find(a => String(a.slug || '').toLowerCase() === String(targetSlug).toLowerCase());
        }
        if (!article && articles.length > 0) {
          article = articles[0];
        }

        if (!article) {
          document.getElementById('article-title').textContent = 'Artikel Tidak Ditemukan';
          document.getElementById('article-body').innerHTML = '<p>Maaf, materi artikel yang Anda cari tidak tersedia atau telah dihapus dari basis data.</p>';
          return;
        }

        document.title = `${article.title} - Aswaja CyberShield`;
        document.getElementById('article-breadcrumb').textContent = article.title;
        document.getElementById('article-category').textContent = article.category || 'Literasi Siber';
        document.getElementById('article-date').textContent = article.date || 'Terbaru';
        document.getElementById('article-title').textContent = article.title;
        document.getElementById('article-summary').textContent = article.summary || article.excerpt || '';
        document.getElementById('article-author').textContent = article.author || 'Tim Literasi Aswaja';

        const imgSrc = article.cover_image || article.coverImage || article.image || '';
        const isRealImage = imgSrc && (
          imgSrc.startsWith('data:image/') ||
          imgSrc.startsWith('http://') ||
          imgSrc.startsWith('https://') ||
          imgSrc.startsWith('/')
        ) && !imgSrc.includes('../images/');

        const coverWrap = document.getElementById('article-cover-wrap');
        const coverImg = document.getElementById('article-cover-img');
        if (isRealImage && coverWrap && coverImg) {
          coverImg.src = imgSrc;
          coverImg.alt = article.title;
          coverWrap.classList.remove('hidden');
          coverImg.onerror = () => {
            coverWrap.classList.add('hidden');
            coverImg.removeAttribute('src');
          };
        } else if (coverWrap) {
          coverWrap.classList.add('hidden');
          if (coverImg) coverImg.removeAttribute('src');
        }

        const rawContent = article.content || article.summary || article.excerpt || '';
        const bodyEl = document.getElementById('article-body');
        if (rawContent.includes('<p>') || rawContent.includes('<h') || rawContent.includes('<ul') || rawContent.includes('<div')) {
          bodyEl.innerHTML = rawContent;
        } else {
          bodyEl.innerHTML = rawContent
            .split(/\n\s*\n/)
            .map(p => `<p>${p.trim().replace(/\n/g, '<br>')}</p>`)
            .join('');
        }
      } catch (e) {
        console.error('Gagal memuat detail artikel:', e);
      }
    });
  </script>
</body>
</html>

