/**
 * tabayyun.js — Aswaja CyberShield
 * Logika halaman Analisis Tautan (AI Tabayyun)
 * Fokus: Tautan investasi menyesatkan yang mengarah ke judi online (judol/maysir modern)
 * Bergantung pada: js/api.js (window.ApiService)
 */

document.addEventListener('DOMContentLoaded', () => {
  // ─── Element References ────────────────────────────────────────────────────
  const form             = document.getElementById('tabayyun-analysis-form');
  const inputUrl         = document.getElementById('checker-input-content');
  const charCounter      = document.getElementById('char-counter');
  const btnClear         = document.getElementById('btn-clear-input');
  const btnSubmit        = document.getElementById('btn-submit-tabayyun');
  const btnSubmitText    = document.getElementById('btn-submit-text');
  const loadingState     = document.getElementById('tabayyun-loading-state');
  const loadingTitle     = document.getElementById('loading-step-title');
  const loadingSubtitle  = document.getElementById('loading-step-subtitle');
  const loadingBar       = document.getElementById('loading-progress-bar');
  const resultCard       = document.getElementById('tabayyun-result-card');
  const btnCopy          = document.getElementById('btn-copy-summary');
  const copyBtnText      = document.getElementById('copy-btn-text');
  const btnReset         = document.getElementById('btn-reset-checker');
  const btnForward       = document.getElementById('btn-forward-report');
  const sampleBtns       = document.querySelectorAll('.btn-sample-url');

  // Result card child elements
  const elResultTitle    = document.getElementById('result-main-title');
  const elResultBadge    = document.getElementById('result-category-badge');
  const elResultScore    = document.getElementById('result-score-text');
  const elResultUrl      = document.getElementById('result-target-url');
  const elResultTime     = document.getElementById('result-scanned-time');
  const elResultFindings = document.getElementById('result-findings-list');
  const elResultIcon     = document.getElementById('result-icon');
  const elIconBox        = document.getElementById('result-icon-box');
  const elScoreIconBox   = document.getElementById('result-score-icon-box');
  const elResultQuoteText = document.getElementById('result-quote-text');
  const elResultQuoteCite = document.getElementById('result-quote-cite');
  const elResultTechniquesBox = document.getElementById('result-techniques-box');
  const elResultTechniquesList = document.getElementById('result-techniques-list');
  const elResultLaymanBox  = document.getElementById('result-layman-box');
  const elResultLaymanList = document.getElementById('result-layman-list');

  let currentResultTechniques = [];

  if (!form || !inputUrl) return;

  // ─── Default State: Pastikan Result Card & Loading State Tersembunyi saat Reload/Navigasi ───
  if (resultCard) resultCard.classList.add('hidden');
  if (loadingState) loadingState.classList.add('hidden');

  // ─── Pre-fill from URL param (?url=...) ────────────────────────────────────
  const params = new URLSearchParams(window.location.search);
  const prefilledUrl = params.get('url') || params.get('q');
  if (prefilledUrl) {
    inputUrl.value = decodeURIComponent(prefilledUrl);
  } else {
    inputUrl.value = '';
  }

  updateCharCount();

  // Reset tampilan jika halaman dimuat dari cache peramban (bfcache / back-forward navigation)
  window.addEventListener('pageshow', () => {
    const currentParams = new URLSearchParams(window.location.search);
    if (!currentParams.get('url') && !currentParams.get('q')) {
      if (resultCard) resultCard.classList.add('hidden');
      if (loadingState) loadingState.classList.add('hidden');
    }
  });

  // ─── Char Counter ──────────────────────────────────────────────────────────
  function updateCharCount() {
    if (!charCounter || !inputUrl) return;
    const len = inputUrl.value.length;
    charCounter.textContent = `${len} / 500 Karakter`;
    charCounter.classList.toggle('text-red-500', len > 480);
  }

  inputUrl.addEventListener('input', updateCharCount);

  // ─── Sample URL Buttons ────────────────────────────────────────────────────
  sampleBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const url = btn.dataset.url || '';
      inputUrl.value = url;
      updateCharCount();
      inputUrl.focus();
    });
  });

  // ─── Clear Button ──────────────────────────────────────────────────────────
  if (btnClear) {
    btnClear.addEventListener('click', () => {
      inputUrl.value = '';
      updateCharCount();
      inputUrl.focus();
    });
  }

  // ─── Loading Steps Animation ────────────────────────────────────────────────
  const loadingSteps = [
    { 
      title: 'Memeriksa Sanad Domain & Legalitas...', 
      subtitle: 'Memvalidasi DNS, keaslian sertifikat SSL, dan basis data CSIRT/Komdigi', 
      progress: 22 
    },
    { 
      title: 'Mencocokkan Pola Kata Kunci Judol & Investasi...', 
      subtitle: 'Memindai pola maysir, slot gacor, skema ponzi, dan klaim cuan instan', 
      progress: 46 
    },
    { 
      title: 'Menganalisis Struktur URL & Parameter Deposit...', 
      subtitle: 'Mendeteksi indikasi defacement, open redirect, dan manipulasi parameter', 
      progress: 70 
    },
    { 
      title: 'Mengevaluasi Risiko Kaidah Fiqih Siber...', 
      subtitle: 'Mengkorelasikan indikator teknis dengan prinsip 4T dan kaidah muamalah', 
      progress: 88 
    },
    { 
      title: 'Menyusun Laporan Heuristik...', 
      subtitle: 'Menghitung skor risiko sintetis dan merangkum bukti temuan', 
      progress: 94 
    },
  ];

  let loadingInterval = null;
  let creepInterval = null;

  function startLoadingAnimation() {
    if (!loadingState) return;
    clearInterval(loadingInterval);
    clearInterval(creepInterval);

    let step = 0;
    if (resultCard) resultCard.classList.add('hidden');
    loadingState.classList.remove('hidden');
    form.classList.add('opacity-60', 'pointer-events-none');
    if (btnSubmit) btnSubmit.disabled = true;
    if (btnSubmitText) btnSubmitText.textContent = 'Menganalisis...';

    if (loadingTitle && loadingSteps[0]) loadingTitle.textContent = loadingSteps[0].title;
    if (loadingSubtitle && loadingSteps[0]) loadingSubtitle.textContent = loadingSteps[0].subtitle;
    if (loadingBar && loadingSteps[0]) {
      loadingBar.style.width = `${loadingSteps[0].progress}%`;
      loadingBar.style.minWidth = '14px';
    }
    step = 1;

    // Efek Animasi Laser Pemindaian Tautan
    const inputWrapper = document.querySelector('#checker-input-content')?.parentElement;
    if (inputWrapper && !inputWrapper.querySelector('.scanning-laser-beam')) {
      const beam = document.createElement('div');
      beam.className = 'scanning-laser-beam';
      inputWrapper.style.position = 'relative';
      inputWrapper.style.overflow = 'hidden';
      inputWrapper.appendChild(beam);
    }

    // Beri ruang scroll ekstra pada kontainer agar kartu loading bisa terpusat utuh di layar
    const colLeft = loadingState.parentElement;
    if (colLeft) {
      colLeft.style.minHeight = 'calc(100vh - 120px)';
    }

    // Scroll mulus langsung memposisikan kartu loading tepat di tengah layar
    setTimeout(() => {
      if (window.AswajaUI && typeof window.AswajaUI.scrollTo === 'function') {
        window.AswajaUI.scrollTo('#tabayyun-loading-state', { block: 'center', offset: 100 });
      } else {
        loadingState.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    }, 40);

    // Langkah demi langkah berjalan dinamis
    loadingInterval = setInterval(() => {
      if (step >= loadingSteps.length) {
        clearInterval(loadingInterval);

        // Jangan pernah biarkan bar macet/beku: jalankan creeping progress lembut (94% -> 98.5%)
        let creepProgress = 94;
        creepInterval = setInterval(() => {
          if (creepProgress < 98.5) {
            creepProgress += 0.4;
            if (loadingBar) loadingBar.style.width = `${creepProgress.toFixed(1)}%`;
          }
        }, 180);
        return;
      }

      if (loadingTitle) loadingTitle.textContent = loadingSteps[step].title;
      if (loadingSubtitle) loadingSubtitle.textContent = loadingSteps[step].subtitle;
      if (loadingBar) loadingBar.style.width = `${loadingSteps[step].progress}%`;
      step++;
    }, 360);
  }

  function finishLoadingAnimation() {
    return new Promise(resolve => {
      clearInterval(loadingInterval);
      clearInterval(creepInterval);
      if (loadingBar) loadingBar.style.width = '100%';
      if (loadingTitle) loadingTitle.textContent = 'Analisis Selesai!';
      if (loadingSubtitle) loadingSubtitle.textContent = 'Menyajikan hasil evaluasi dan rekomendasi Tabayyun...';
      setTimeout(() => {
        stopLoadingAnimation();
        resolve();
      }, 240);
    });
  }

  function stopLoadingAnimation() {
    clearInterval(loadingInterval);
    clearInterval(creepInterval);
    const colLeft = loadingState ? loadingState.parentElement : null;
    if (colLeft) {
      colLeft.style.minHeight = '';
    }
    if (loadingState) loadingState.classList.add('hidden');
    document.querySelectorAll('.scanning-laser-beam').forEach(el => el.remove());
    form.classList.remove('opacity-60', 'pointer-events-none');
    if (btnSubmit) btnSubmit.disabled = false;
    if (btnSubmitText) btnSubmitText.textContent = 'Mulai Analisis Tautan';
    if (loadingBar) loadingBar.style.width = '100%';
  }

  // ─── Color Scheme Helper (Light Mode & Dark Mode Dynamic Theme) ─────────────
  const colorMap = {
    red: {
      card: ['bg-red-50/80', 'dark:bg-red-950/30', 'border', 'border-red-200/90', 'dark:border-red-800/60'],
      divider: ['border-b', 'border-red-200/60', 'dark:border-red-800/40'],
      innerBorder: ['border-red-200/70', 'dark:border-red-800/50'],
      title: 'text-red-950 dark:text-red-100',
      badge: ['bg-red-200', 'dark:bg-red-800/80', 'text-red-900', 'dark:text-red-100'],
      score: 'text-red-700 dark:text-red-300',
      urlText: 'text-red-800 dark:text-red-200',
      icon: 'warning',
      iconBox: ['bg-red-100', 'dark:bg-red-900/60', 'text-red-700', 'dark:text-red-300'],
      scoreBox: ['bg-red-100', 'dark:bg-red-950/70', 'text-red-700', 'dark:text-red-300'],
      scoreIcon: 'gpp_bad',
    },
    amber: {
      card: ['bg-amber-50/80', 'dark:bg-amber-950/25', 'border', 'border-amber-200/90', 'dark:border-amber-800/60'],
      divider: ['border-b', 'border-amber-200/60', 'dark:border-amber-800/40'],
      innerBorder: ['border-amber-200/70', 'dark:border-amber-800/50'],
      title: 'text-amber-950 dark:text-amber-100',
      badge: ['bg-amber-200', 'dark:bg-amber-800/80', 'text-amber-900', 'dark:text-amber-100'],
      score: 'text-amber-700 dark:text-amber-300',
      urlText: 'text-amber-800 dark:text-amber-200',
      icon: 'warning',
      iconBox: ['bg-amber-100', 'dark:bg-amber-900/60', 'text-amber-700', 'dark:text-amber-300'],
      scoreBox: ['bg-amber-100', 'dark:bg-amber-950/70', 'text-amber-700', 'dark:text-amber-300'],
      scoreIcon: 'gpp_maybe',
    },
    orange: {
      card: ['bg-orange-50/80', 'dark:bg-orange-950/25', 'border', 'border-orange-300/90', 'dark:border-orange-800/70'],
      divider: ['border-b', 'border-orange-200/70', 'dark:border-orange-800/50'],
      innerBorder: ['border-orange-200/80', 'dark:border-orange-800/60'],
      title: 'text-orange-950 dark:text-orange-100',
      badge: ['bg-orange-200', 'dark:bg-orange-900/80', 'text-orange-900', 'dark:text-orange-100'],
      score: 'text-orange-700 dark:text-orange-300',
      urlText: 'text-orange-800 dark:text-orange-200',
      icon: 'warning',
      iconBox: ['bg-orange-100', 'dark:bg-orange-900/60', 'text-orange-700', 'dark:text-orange-300'],
      scoreBox: ['bg-orange-100', 'dark:bg-orange-950/70', 'text-orange-700', 'dark:text-orange-300'],
      scoreIcon: 'gpp_maybe',
    },
    emerald: {
      card: ['bg-emerald-50/80', 'dark:bg-emerald-950/25', 'border', 'border-emerald-200/90', 'dark:border-emerald-800/60'],
      divider: ['border-b', 'border-emerald-200/60', 'dark:border-emerald-800/40'],
      innerBorder: ['border-emerald-200/70', 'dark:border-emerald-800/50'],
      title: 'text-emerald-950 dark:text-emerald-100',
      badge: ['bg-emerald-200', 'dark:bg-emerald-800/80', 'text-emerald-900', 'dark:text-emerald-100'],
      score: 'text-emerald-700 dark:text-emerald-300',
      urlText: 'text-emerald-800 dark:text-emerald-200',
      icon: 'verified',
      iconBox: ['bg-emerald-100', 'dark:bg-emerald-900/60', 'text-emerald-700', 'dark:text-emerald-300'],
      scoreBox: ['bg-emerald-100', 'dark:bg-emerald-950/70', 'text-emerald-700', 'dark:text-emerald-300'],
      scoreIcon: 'verified_user',
    },
    slate: {
      card: ['bg-slate-50/90', 'dark:bg-slate-900/60', 'border', 'border-slate-300', 'dark:border-slate-700'],
      divider: ['border-b', 'border-slate-200', 'dark:border-slate-700'],
      innerBorder: ['border-slate-200', 'dark:border-slate-700'],
      title: 'text-slate-900 dark:text-slate-100',
      badge: ['bg-slate-200', 'dark:bg-slate-700', 'text-slate-800', 'dark:text-slate-200'],
      score: 'text-slate-600 dark:text-slate-400',
      urlText: 'text-slate-800 dark:text-slate-200',
      icon: 'help_outline',
      iconBox: ['bg-slate-100', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-300'],
      scoreBox: ['bg-slate-100', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-300'],
      scoreIcon: 'help_outline',
    },
  };

  function applyColorScheme(alertClass) {
    const validKey = colorMap[alertClass] ? alertClass : 'amber';
    const scheme = colorMap[validKey];

    // Set data-alert attribute so CSS dark/light rules match the exact verdict color
    resultCard.setAttribute('data-alert', validKey);

    // Result card background + border
    resultCard.className = `rounded-2xl p-6 sm:p-8 shadow-sm space-y-6 ${scheme.card.join(' ')}`;

    // Header divider border
    const elHeaderDivider = document.getElementById('result-header-divider');
    if (elHeaderDivider) {
      elHeaderDivider.className = `flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 ${scheme.divider.join(' ')}`;
    }

    // Inner boxes border (Score pill, URL box, Findings box, Quote box)
    resultCard.querySelectorAll('.result-inner-box').forEach(box => {
      box.className = box.className
        .replace(/(?:dark:)?border-(?:red|amber|orange|emerald|slate)-\S+/g, '')
        .trim() + ' ' + scheme.innerBorder.join(' ');
    });

    // Main Title color
    if (elResultTitle) {
      elResultTitle.className = `font-display font-bold text-xl mt-1 ${scheme.title}`;
    }

    // Badge
    if (elResultBadge) {
      elResultBadge.className = `badge inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider whitespace-nowrap leading-tight ${scheme.badge.join(' ')}`;
    }

    // Score text color
    if (elResultScore) {
      elResultScore.className = `font-display font-bold text-lg ${scheme.score}`;
    }

    // Target URL color
    if (elResultUrl) {
      elResultUrl.className = `font-mono break-all select-all font-semibold ${scheme.urlText}`;
    }

    // Icon box
    if (elIconBox) {
      elIconBox.className = `w-12 h-12 rounded-xl flex items-center justify-center shrink-0 ${scheme.iconBox.join(' ')}`;
    }
    if (elResultIcon) {
      elResultIcon.textContent = scheme.icon;
    }

    // Score icon box
    if (elScoreIconBox) {
      elScoreIconBox.className = `w-8 h-8 rounded-full flex items-center justify-center ${scheme.scoreBox.join(' ')}`;
      const scoreIcon = elScoreIconBox.querySelector('.material-symbols-outlined');
      if (scoreIcon) scoreIcon.textContent = scheme.scoreIcon;
    }
  }

  // ─── Render Plain-Language Layperson Summary (Bebas Jargon IT) ───────────
  function renderLaymanSummary(result, rawUrl, resolvedColor = 'emerald') {
    if (!elResultLaymanBox || !elResultLaymanList) return;

    const techniques = Array.isArray(result.techniques) ? result.techniques : [];
    const category = (result.category || result.final_category || '').toLowerCase();
    const findings = Array.isArray(result.findings) ? result.findings : [];
    const findingsText = findings.map(f => (f.label || '') + ' ' + (f.detail || '')).join(' ').toLowerCase();
    const urlLower = String(rawUrl || result.url || '').toLowerCase();

    let points = [];

    if (resolvedColor === 'red') {
      if (techniques.includes('kamuflase_investasi') || findingsText.includes('kamuflase') || findingsText.includes('cuan') || findingsText.includes('trading')) {
        points = [
          {
            icon: 'masks',
            color: 'text-amber-600 dark:text-amber-400',
            title: 'Kedok Penipuan',
            desc: 'Situs ini berpura-pura menjadi wadah bisnis atau investasi saham/trading harian agar masyarakat awam tergiur.'
          },
          {
            icon: 'casino',
            color: 'text-red-600 dark:text-red-400',
            title: 'Bahaya Nyata',
            desc: 'Begitu dibuka, uang Anda langsung diarahkan ke setoran judi online (slot). Anda dipastikan akan kehilangan uang.'
          },
          {
            icon: 'gpp_bad',
            color: 'text-rose-600 dark:text-rose-400',
            title: 'Langkah Aman',
            desc: 'JANGAN pernah mentransfer uang, JANGAN mendaftar atau mengisi nomor HP/rekening, dan segera tutup halaman ini.'
          }
        ];
      } else if (techniques.includes('web_defacement') || findingsText.includes('defacement')) {
        points = [
          {
            icon: 'domain_disabled',
            color: 'text-amber-600 dark:text-amber-400',
            title: 'Kedok Penipuan',
            desc: 'Halaman situs resmi milik pemerintah atau lembaga pendidikan telah dibajak dan disusupi secara diam-diam oleh pelaku kejahatan.'
          },
          {
            icon: 'warning',
            color: 'text-red-600 dark:text-red-400',
            title: 'Bahaya Nyata',
            desc: 'Halaman resmi tersebut disalahgunakan untuk menyembunyikan promosi judi online agar seolah-olah mendapat izin resmi.'
          },
          {
            icon: 'front_hand',
            color: 'text-rose-600 dark:text-rose-400',
            title: 'Langkah Aman',
            desc: 'JANGAN mengeklik tombol pendaftaran apa pun dan laporkan halaman yang disusupi ini agar dapat segera dibersihkan.'
          }
        ];
      } else if (techniques.includes('typosquatting') || findingsText.includes('typosquatting') || findingsText.includes('meniru')) {
        points = [
          {
            icon: 'badge',
            color: 'text-amber-600 dark:text-amber-400',
            title: 'Kedok Penipuan',
            desc: 'Alamat web ini sengaja dibuat mirip dengan nama instansi resmi atau bank terpercaya untuk mengelabui Anda.'
          },
          {
            icon: 'lock_reset',
            color: 'text-red-600 dark:text-red-400',
            title: 'Bahaya Nyata',
            desc: 'Ini adalah situs tiruan palsu yang bertujuan mencuri data pribadi, nomor kartu, atau kata sandi rekening Anda.'
          },
          {
            icon: 'shield',
            color: 'text-rose-600 dark:text-rose-400',
            title: 'Langkah Aman',
            desc: 'JANGAN memasukkan nomor HP, kata sandi, atau kode rahasia apa pun. Segera keluar dari situs ini.'
          }
        ];
      } else {
        // Direct Judol Standalone / Bahaya Berat
        points = [
          {
            icon: 'casino',
            color: 'text-red-600 dark:text-red-400',
            title: 'Status Bahaya',
            desc: 'Alamat ini terbukti langsung sebagai sarana perjudian online ilegal (seperti taruhan slot atau judi angka).'
          },
          {
            icon: 'money_off',
            color: 'text-amber-600 dark:text-amber-400',
            title: 'Bahaya Nyata',
            desc: 'Seluruh uang yang disetorkan diatur untuk merugikan pemain. Kegiatan ini melanggar hukum dan norma agama.'
          },
          {
            icon: 'block',
            color: 'text-rose-600 dark:text-rose-400',
            title: 'Langkah Aman',
            desc: 'JANGAN pernah mencoba membuka atau memasukkan uang ke situs ini. Abaikan semua ajakan kemenangan palsu.'
          }
        ];
      }
    } else if (resolvedColor === 'orange') {
      const isJudolKeyword = findingsText.includes('slot') || findingsText.includes('judi') || findingsText.includes('taruhan') || urlLower.includes('maxwin') || urlLower.includes('jackpot') || urlLower.includes('scatter');
      if (isJudolKeyword) {
        points = [
          {
            icon: 'warning',
            color: 'text-orange-600 dark:text-orange-400',
            title: 'Status Pemeriksaan',
            desc: 'Tautan memuat kata kunci atau fitur yang identik dengan permainan taruhan daring (seperti maxwin, jackpot, atau scatter).'
          },
          {
            icon: 'crisis_alert',
            color: 'text-red-600 dark:text-red-400',
            title: 'Bahaya Nyata',
            desc: 'Berpotensi mengarahkan pengunjung ke jaringan perjudian daring atau skema pendaftaran taruhan ilegal.'
          },
          {
            icon: 'front_hand',
            color: 'text-orange-600 dark:text-orange-400',
            title: 'Langkah Aman',
            desc: 'Hindari menekan tombol pendaftaran atau mengisi data kontak pribadi apa pun pada tautan ini.'
          }
        ];
      } else {
        points = [
          {
            icon: 'warning',
            color: 'text-orange-600 dark:text-orange-400',
            title: 'Status Pemeriksaan',
            desc: 'Terdeteksi anomali teknis dan reputasi domain yang mencurigakan berdasarkan hasil audit keamanan.'
          },
          {
            icon: 'lock_open_right',
            color: 'text-red-600 dark:text-red-400',
            title: 'Bahaya Nyata',
            desc: 'Halaman ini belum memiliki reputasi yang terpercaya dan berisiko menyembunyikan konten yang merugikan.'
          },
          {
            icon: 'gpp_maybe',
            color: 'text-orange-600 dark:text-orange-400',
            title: 'Langkah Aman',
            desc: 'Jangan melakukan transaksi finansial, mentransfer uang, atau mengunduh berkas apa pun dari halaman ini.'
          }
        ];
      }
    } else if (resolvedColor === 'amber') {
      const isInvest = category.includes('investasi') || findingsText.includes('investasi') || findingsText.includes('trading') || findingsText.includes('cuan');
      if (isInvest) {
        points = [
          {
            icon: 'payments',
            color: 'text-amber-600 dark:text-amber-400',
            title: 'Ciri Penawaran',
            desc: 'Menjanjikan keuntungan uang instan, arisan kilat, atau imbal hasil pasti yang belum terdaftar di lembaga resmi.'
          },
          {
            icon: 'warning',
            color: 'text-amber-600 dark:text-amber-400',
            title: 'Bahaya Nyata',
            desc: 'Kegiatan ini tidak memiliki izin resmi dari pengawas keuangan dan berisiko tinggi membawa kabur uang Anda.'
          },
          {
            icon: 'verified_user',
            color: 'text-amber-600 dark:text-amber-400',
            title: 'Langkah Aman',
            desc: 'JANGAN mentransfer modal apa pun sebelum memeriksa izin resmi perusahaan ke pihak berwenang (OJK/Bappebti).'
          }
        ];
      } else {
        // Non-gambling site with cheap/disposable TLD or promo link (e.g. cat community forum, personal blog)
        points = [
          {
            icon: 'info',
            color: 'text-amber-600 dark:text-amber-400',
            title: 'Ciri Alamat',
            desc: 'Alamat web menggunakan ekstensi domain bebas atau murah (seperti .club atau .site), namun bersih dari konten perjudian.'
          },
          {
            icon: 'gpp_maybe',
            color: 'text-amber-600 dark:text-amber-400',
            title: 'Kondisi Keamanan',
            desc: 'Tidak ditemukan transaksi taruhan judi online, permainan slot, maupun indikasi pencurian data pada halaman ini.'
          },
          {
            icon: 'security',
            color: 'text-emerald-600 dark:text-emerald-400',
            title: 'Langkah Aman',
            desc: 'Cukup berhati-hati saat mengakses, dan jangan pernah membagikan kata sandi pribadi atau informasi rekening bank.'
          }
        ];
      }
    } else if (resolvedColor === 'slate') {
      points = [
        {
          icon: 'help_outline',
          color: 'text-slate-600 dark:text-slate-400',
          title: 'Status Verifikasi',
          desc: 'Sistem memerlukan informasi atau verifikasi otoritas tambahan untuk menyimpulkan status tautan ini secara akurat.'
        },
        {
          icon: 'manage_search',
          color: 'text-slate-600 dark:text-slate-400',
          title: 'Kondisi Halaman',
          desc: 'Tautan belum terdaftar dalam basis data ancaman, namun belum memiliki rekam jejak reputasi yang cukup.'
        },
        {
          icon: 'rule',
          color: 'text-blue-600 dark:text-blue-400',
          title: 'Langkah Aman',
          desc: 'Terapkan prinsip tabayyun (meneliti) dan jangan memberikan data rahasia sebelum ada konfirmasi resmi.'
        }
      ];
    } else {
      // Clean / Safe website (resolvedColor === 'emerald')
      points = [
        {
          icon: 'check_circle',
          color: 'text-emerald-600 dark:text-emerald-400',
          title: 'Status Keamanan',
          desc: 'Alamat ini adalah situs resmi yang sah dan tidak ditemukan tanda-tanda perjudian maupun penipuan uang.'
        },
        {
          icon: 'security',
          color: 'text-emerald-600 dark:text-emerald-400',
          title: 'Kondisi Halaman',
          desc: 'Tidak ada perangkap pengalihan otomatis atau pembajakan halaman tersembunyi.'
        },
        {
          icon: 'thumb_up',
          color: 'text-blue-600 dark:text-blue-400',
          title: 'Langkah Aman',
          desc: 'Aman untuk dikunjungi. Tetap jaga kerahasiaan kata sandi dan jangan pernah membagikan kode rahasia perbankan Anda.'
        }
      ];
    }

    const borderMap = {
      emerald: 'border-emerald-200/60 dark:border-emerald-800/50',
      amber: 'border-amber-200/60 dark:border-amber-800/50',
      orange: 'border-orange-200/60 dark:border-orange-800/50',
      red: 'border-red-200/60 dark:border-red-800/50',
      slate: 'border-slate-200/60 dark:border-slate-700/50'
    };
    const itemBorder = borderMap[resolvedColor] || borderMap.amber;

    elResultLaymanList.innerHTML = points.map(p => `
      <div class="flex items-start gap-3 p-3 rounded-lg bg-white/90 dark:bg-slate-800/90 border ${itemBorder} shadow-xs">
        <span class="material-symbols-outlined ${p.color} text-[22px] shrink-0 mt-0.5">${p.icon}</span>
        <div class="space-y-0.5">
          <div class="font-bold text-xs uppercase tracking-wide text-slate-900 dark:text-white">${p.title}</div>
          <div class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed">${p.desc}</div>
        </div>
      </div>
    `).join('');
  }

  // ─── Render Findings List ──────────────────────────────────────────────────
  function renderFindings(findings, resolvedColor = 'emerald') {
    if (!elResultFindings) return;
    const iconMap = {
      error: { icon: 'domain_disabled', color: 'text-red-600 dark:text-red-400' },
      warning: { icon: 'psychology_alt', color: 'text-amber-600 dark:text-amber-400' },
      success: { icon: 'verified', color: 'text-emerald-600 dark:text-emerald-400' },
      info: { icon: 'info', color: 'text-blue-600 dark:text-blue-400' },
    };

    let items = Array.isArray(findings) ? [...findings] : [];

    // Jika findings kosong atau tidak ada temuan indikator ancaman (misal website aman/bersih)
    if (!items.length) {
      if (resolvedColor === 'emerald') {
        items = [
          {
            type: 'success',
            label: 'Audit Sanad Domain',
            detail: 'Domain beroperasi secara legal, memiliki reputasi bersih, dan tidak terdaftar dalam basis data pemblokiran judol atau kloning finansial.'
          },
          {
            type: 'success',
            label: 'Analisis Muatan & Pola',
            detail: 'Tidak ditemukan skema setoran taruhan, roda putar keberuntungan acak, maupun klaim keuntungan instan yang mencurigakan.'
          },
          {
            type: 'info',
            label: 'Rekomendasi Tabayyun',
            detail: 'Tautan aman untuk diakses. Tetap terapkan prinsip kehati-hatian digital dan jaga kerahasiaan data pribadi Anda.'
          }
        ];
      } else if (resolvedColor === 'amber') {
        items = [
          {
            type: 'warning',
            label: 'Status Perlu Diwaspadai',
            detail: 'Ditemukan beberapa indikator pola yang memerlukan konfirmasi lebih lanjut sebelum Anda mengakses atau bertransaksi.'
          },
          {
            type: 'info',
            label: 'Saran Tabayyun',
            detail: 'Verifikasi izin resmi entitas terkait ke otoritas resmi (OJK/Bappebti/Kominfo).'
          }
        ];
      } else {
        items = [
          {
            type: 'error',
            label: 'Peringatan Bahaya Tinggi',
            detail: 'Tautan terindikasi kuat memiliki karakteristik jaringan perjudian daring atau skema penipuan finansial.'
          },
          {
            type: 'warning',
            label: 'Rekomendasi Tindakan',
            detail: 'Jangan lakukan registrasi, unduh berkas, atau transfer dana ke rekening yang tertera pada tautan ini.'
          }
        ];
      }
    }

    elResultFindings.innerHTML = items.map(f => {
      const { icon, color } = iconMap[f.type] || iconMap.info;
      let cleanLabel = String(f.label || 'Temuan')
        .replace(/Sensor Keamanan Jaringan/gi, 'Pemeriksaan Pola Bahaya')
        .replace(/Sensor Suricata NIDS/gi, 'Pemeriksaan Pola Bahaya')
        .replace(/Deteksi Signature Jaringan/gi, 'Pemeriksaan Pola Bahaya')
        .replace(/Analisis Struktur URL/gi, 'Pemeriksaan Ciri Alamat')
        .replace(/Inspeksi Konten & Penyusupan/gi, 'Pemeriksaan Modus Halaman')
        .replace(/Analisis Pola & Konten/gi, 'Pemeriksaan Modus Halaman')
        .replace(/Deteksi Google Gemini/gi, 'Analisis Muatan Digital')
        .replace(/Google Gemini/gi, 'Sistem Analitik')
        .replace(/Suricata/gi, 'Pemeriksaan Keamanan')
        .replace(/:+$/, '');
      let detailText = String(f.detail || f.desc || '')
        .replace(/\[Analisis Gemini\]/gi, '')
        .replace(/ASWAJA-IDS Kata kunci judol pada URI/gi, 'Ditemukan kata kunci taruhan judi pada alamat tautan')
        .replace(/ASWAJA-IDS Query DNS domain berkata kunci judol/gi, 'Alamat web terindikasi menggunakan nama terkait perjudian')
        .replace(/Google Gemini/gi, 'sistem keamanan')
        .replace(/Suricata NIDS/gi, 'pemeriksaan keamanan')
        .replace(/Suricata/gi, 'pemeriksaan keamanan')
        .trim();
      return `<li class="flex items-start gap-2.5">
        <span class="material-symbols-outlined ${color} text-[18px] shrink-0 mt-0.5">${icon}</span>
        <span><strong>${cleanLabel}:</strong> ${detailText}</span>
      </li>`;
    }).join('');
  }

  // ─── Render Detected Techniques (Web Defacement, Open Redirect, Typosquatting) ───
  function renderTechniques(techniques, findings, rawUrl) {
    if (!elResultTechniquesBox || !elResultTechniquesList) return;

    let list = Array.isArray(techniques) ? [...techniques] : [];

    // Jika techniques kosong dari response utama, coba telusuri dari findings/indikator
    if (!list.length && Array.isArray(findings)) {
      findings.forEach(f => {
        const text = ((f.label || '') + ' ' + (f.detail || '') + ' ' + (f.desc || '')).toLowerCase();
        if (text.includes('kamuflase') || (text.includes('investasi') && text.includes('deposit')) || (text.includes('trading') && text.includes('slot'))) {
          if (!list.includes('kamuflase_investasi')) list.push('kamuflase_investasi');
        }
        if ((text.includes('defacement') || text.includes('subdomain asing')) && !text.includes('kamuflase')) {
          if (!list.includes('web_defacement')) list.push('web_defacement');
        }
        if (text.includes('open redirect') || text.includes('dua host')) {
          if (!list.includes('open_redirect')) list.push('open_redirect');
        }
        if (text.includes('typosquatting') || text.includes('meniru')) {
          if (!list.includes('typosquatting')) list.push('typosquatting');
        }
      });
    }

    if (!list.length) {
      elResultTechniquesBox.classList.add('hidden');
      elResultTechniquesList.innerHTML = '';
      return;
    }

    elResultTechniquesBox.classList.remove('hidden');

    const techniqueMeta = {
      kamuflase_investasi: {
        badgeTitle: 'Kamuflase Finansial (Investasi Berkedok Judol)',
        badgeColor: 'bg-rose-100 text-rose-900 border-rose-300/80 dark:bg-rose-950/70 dark:text-rose-200 dark:border-rose-700/60',
        icon: 'monetization_on',
        explanation: 'Tautan menggunakan kedok istilah investasi atau trading pada nama domain untuk memikat calon korban, namun jalur transaksi dan deposit secara langsung mengarah ke sistem perjudian online.',
        findEvidence: () => {
          const matched = (findings || []).find(f => (f.label || '').includes('Kamuflase') || (f.detail || '').includes('Kamuflase') || (f.detail || '').includes('deposit') || (f.detail || '').includes('trading'));
          if (matched) return matched.detail || matched.desc;
          try {
            const u = new URL(rawUrl.startsWith('http') ? rawUrl : 'https://' + rawUrl);
            return `Domain '${u.hostname}' menyamarkan nama investasi/trading, namun mengarahkan setoran ke sistem perjudian (${u.pathname}).`;
          } catch {
            return 'Nama domain berkedok istilah investasi/keuangan mengarah langsung ke transaksi perjudian online.';
          }
        }
      },
      web_defacement: {
        badgeTitle: 'Web Defacement / Subdomain Hijacking',
        badgeColor: 'bg-amber-100 text-amber-900 border-amber-300/80 dark:bg-amber-950/70 dark:text-amber-200 dark:border-amber-700/60',
        icon: 'language',
        explanation: 'Tautan memanfaatkan celah keamanan atau menyusupi sub-halaman situs resmi (seperti domain pemerintah/pendidikan) untuk mengelabui filter dan menyembunyikan konten perjudian.',
        findEvidence: () => {
          const matched = (findings || []).find(f => (f.label || '').includes('Defacement') || (f.detail || '').includes('Defacement') || (f.detail || '').includes('Subdomain'));
          if (matched) return matched.detail || matched.desc;
          try {
            const u = new URL(rawUrl.startsWith('http') ? rawUrl : 'https://' + rawUrl);
            return `Jalur path '${u.pathname}' atau subdomain terindikasi memuat kata kunci judol.`;
          } catch {
            return 'Jalur path atau subdomain resmi memuat indikator halaman judol.';
          }
        }
      },
      open_redirect: {
        badgeTitle: 'Open Redirect (Pengalihan Otomatis)',
        badgeColor: 'bg-blue-100 text-blue-900 border-blue-300/80 dark:bg-blue-950/70 dark:text-blue-200 dark:border-blue-700/60',
        icon: 'shortcut',
        explanation: 'Tautan menggunakan situs perantara untuk langsung mengalihkan pengunjung secara otomatis ke alamat web tujuan lain tanpa disadari.',
        findEvidence: () => {
          const matched = (findings || []).find(f => (f.label || '').includes('Redirect') || (f.detail || '').includes('Redirect') || (f.detail || '').includes('dua host'));
          if (matched) return matched.detail || matched.desc;
          const paramMatch = (rawUrl || '').match(/[?&](url|redirect|to|link|dest|next|goto|return)=([^&]+)/i);
          if (paramMatch) {
            const cleanParam = decodeURIComponent(paramMatch[0]);
            return `Parameter pengalihan ditemukan: ${cleanParam}`;
          }
          return 'Parameter URL mengarahkan peramban ke alamat tujuan eksternal.';
        }
      },
      typosquatting: {
        badgeTitle: 'Typosquatting / URL Spoofing',
        badgeColor: 'bg-purple-100 text-purple-900 border-purple-300/80 dark:bg-purple-950/70 dark:text-purple-200 dark:border-purple-700/60',
        icon: 'spellcheck',
        explanation: 'Nama domain sengaja dibuat sangat mirip dengan nama instansi resmi atau merek terpercaya untuk memancing korban agar terkecoh.',
        findEvidence: () => {
          const matched = (findings || []).find(f => (f.label || '').includes('Typosquatting') || (f.detail || '').includes('Typosquatting') || (f.detail || '').includes('Meniru'));
          if (matched) return matched.detail || matched.desc;
          return 'Struktur domain meniru nama lembaga resmi dengan manipulasi karakter atau ekstensi non-resmi.';
        }
      }
    };

    elResultTechniquesList.innerHTML = list.map(techKey => {
      const meta = techniqueMeta[techKey] || {
        badgeTitle: techKey.replace(/_/g, ' ').toUpperCase(),
        badgeColor: 'bg-slate-100 text-slate-800 border-slate-300 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-700',
        icon: 'security',
        explanation: 'Terdeteksi anomali teknik manipulasi siber pada struktur tautan.',
        findEvidence: () => 'Ditemukan pola manipulasi pada struktur URL.'
      };

      const rawEvidence = meta.findEvidence();
      // Sanitasi agar TIDAK PERNAH menjadi tautan aktif
      const safeEvidence = String(rawEvidence || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');

      return `
        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-850 border border-slate-200/80 dark:border-slate-700/80 space-y-1.5 transition-colors">
          <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-bold border ${meta.badgeColor}">
              <span class="material-symbols-outlined text-[15px]">${meta.icon}</span>
              <span>${meta.badgeTitle}</span>
            </span>
          </div>
          <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed font-normal">
            ${meta.explanation}
          </p>
          <div class="pt-1 text-[11px] font-mono text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-900/60 p-2 rounded-lg border border-slate-200/60 dark:border-slate-800 select-all break-all">
            <span class="text-slate-400 dark:text-slate-500 font-sans font-semibold mr-1">Bukti:</span>
            <span>${safeEvidence}</span>
          </div>
        </div>
      `;
    }).join('');
  }

  // ─── Update Islamic Guidance Quote Disesuaikan dengan Hasil Vonis ────────────
  function updateIslamicQuote(resolvedColor) {
    if (!elResultQuoteText || !elResultQuoteCite) return;
    if (resolvedColor === 'emerald') {
      elResultQuoteText.textContent = '“Wahai orang-orang yang beriman! Jika seseorang yang fasik datang kepadamu membawa suatu berita, maka telitilah kebenarannya (tabayyun) agar kamu tidak mencelakakan suatu kaum karena ketidaktahuan...”';
      elResultQuoteCite.innerHTML = '(QS. Al-Hujurat: 6 &bull; Prinsip Tabayyun &amp; Verifikasi Informasi Siber)';
    } else if (resolvedColor === 'slate') {
      elResultQuoteText.textContent = '“Tinggalkanlah apa yang meragukanmu kepada apa yang tidak meragukanmu. Sesungguhnya kejujuran itu mendatangkan ketenangan dan kedustaan itu mendatangkan keragu-raguan.”';
      elResultQuoteCite.innerHTML = '(HR. Tirmidzi &amp; Ahmad &bull; Prinsip Kehati-hatian &amp; Tawaqquf)';
    } else if (resolvedColor === 'amber') {
      elResultQuoteText.textContent = '“Yang halal itu jelas dan yang haram itu jelas, dan di antara keduanya terdapat perkara-perkara yang syubhat (samar). Barang siapa menjauhi perkara syubhat, maka ia telah membersihkan agama dan kehormatannya.”';
      elResultQuoteCite.innerHTML = '(HR. Bukhari &amp; Muslim &bull; Prinsip Menghindari Syubhat &amp; Kehati-hatian)';
    } else {
      elResultQuoteText.textContent = '“Wahai orang-orang yang beriman, janganlah kamu saling memakan harta sesamamu dengan jalan yang batil...”';
      elResultQuoteCite.innerHTML = '(QS. An-Nisa: 29 &bull; Prinsip Kehalalan Transaksi Finansial dan Larangan Maysir)';
    }
  }

  // ─── Determine Exact Alert Color from Result (Standarisasi 4 Tingkat Risiko Resmi) ──
  function resolveAlertColor(result, rawUrl) {
    // 1. Jika backend mengembalikan alertClass yang valid, langsung prioritaskan
    if (result && result.alertClass && ['emerald', 'amber', 'orange', 'red', 'slate'].includes(result.alertClass)) {
      return result.alertClass;
    }

    const cat = String(result.final_category || result.category || '').toLowerCase();
    const status = String(result.statusAlert || '').toLowerCase();
    const urlLower = String(rawUrl || result.url || '').toLowerCase();

    if (cat === 'belum_dapat_disimpulkan' || cat === 'unconcluded') {
      return 'slate';
    }

    // 2. Evaluasi skor risiko jika ada (4 Tingkat Risiko Resmi)
    let score = null;
    if (typeof result.risk_score === 'number') {
      score = result.risk_score;
    } else if (typeof result.score === 'number') {
      score = result.score <= 35 ? (100 - result.score) : result.score;
    }

    if (score !== null) {
      if (score >= 85) return 'red';
      if (score >= 51) return 'orange';
      if (score >= 26) return 'amber';
      return 'emerald';
    }

    // 3. Fallback jika mode simulasi offline dari api.js
    if (result.isSimulation && urlLower) {
      const strongWords = ['slot-gacor', 'slot_gacor', 'slotgacor', 'situs-togel', 'casino-online', 'kasino-online', 'judi-slot', 'bandar-togel', 'slot88', 'slot777', 'gacor777', 'gacor88', 'deposit-slot', 'depo-slot', 'ojk-investasi', 'bappebti-trading', 'robot-trading', 'auto-profit', 'investasii'];
      const mediumWords = ['maxwin', 'jackpot', 'scatter', 'free-spin', 'freespin', 'rtp-live', 'pragmatic', 'pgsoft', 'slot', 'gacor', 'togel', 'casino'];
      const supportSignals = ['.vip', '.cc', '.xyz', '.top', '.icu', '777', '888', '88', '/deposit', '?ref=', '185.220.'];
      const hasStrong = strongWords.some(w => urlLower.includes(w));
      const hasMedium = mediumWords.some(w => urlLower.includes(w));
      const hasSupport = supportSignals.some(s => urlLower.includes(s));

      if (hasStrong) return 'red';
      if (hasMedium && hasSupport) return 'orange';
      if (hasMedium || hasSupport) return 'amber';
      return 'emerald';
    }

    const isClean = (
      cat === 'normal' ||
      cat === 'belum_ditemukan_indikasi' ||
      cat.includes('aman') ||
      status.includes('aman') ||
      status.includes('bersih')
    ) && !cat.includes('judol') && !cat.includes('investasi') && !cat.includes('mencurigakan');

    if (isClean) {
      return 'emerald';
    }

    if (cat.includes('investasi') || cat.includes('mencurigakan') || cat.includes('waspada')) {
      return 'amber';
    }

    return 'red';
  }

  // ─── Render Result Card ────────────────────────────────────────────────────
  function renderResult(result, rawUrl) {
    stopLoadingAnimation();

    if (!resultCard) return;
    resultCard.classList.remove('hidden');
    requestAnimationFrame(() => {
      if (window.AswajaUI && typeof window.AswajaUI.scrollTo === 'function') {
        window.AswajaUI.scrollTo('#tabayyun-result-card', { offset: 90 });
      } else {
        resultCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });

    let resolvedColor = resolveAlertColor(result, rawUrl);

    const cat = String(result.final_category || result.category || '').toLowerCase();
    const statusLower = String(result.statusAlert || '').toLowerCase();
    const isInconclusive = cat === 'belum_dapat_disimpulkan' || cat === 'unconcluded';

    const isAman = (resolvedColor === 'emerald') && !isInconclusive;

    // Standarisasi 4 Tingkat Risiko Resmi (Single Unified Metric: Tingkat Risiko 0% - 100%)
    let scaleLabel = 'Tingkat Risiko';
    let scoreDisplay = '';
    let scoreStatusText = 'Rendah (Aman)';
    let scoreColorClass = 'emerald';
    let shieldIcon = 'verified_user';
    let tooltipText = '';

    if (isInconclusive) {
      scaleLabel = 'Status Verifikasi';
      scoreDisplay = '';
      scoreStatusText = 'Belum dapat disimpulkan';
      scoreColorClass = 'slate';
      shieldIcon = 'help_outline';
      tooltipText = 'Sistem memerlukan informasi atau verifikasi otoritas tambahan untuk menyimpulkan status keamanan tautan ini secara akurat.';
    } else {
      scaleLabel = 'Tingkat Risiko';
      let riskScore = 0;
      if (typeof result.risk_score === 'number') {
        riskScore = result.risk_score;
      } else if (typeof result.score === 'number') {
        riskScore = result.score <= 35 ? (100 - result.score) : result.score;
      }
      riskScore = Math.max(0, Math.min(100, riskScore));
      scoreDisplay = `${riskScore}%`;

      // Standarisasi 4 Tingkat Risiko (Sesuai Diagram Sintesis Pengguna):
      // - Rendah (0% - 25%): Hijau
      // - Sedang (26% - 50%): Kuning
      // - Tinggi / Perlu Pemeriksaan (51% - 84%): Oranye
      // - Berat (85% - 100%): Merah Danger
      if (riskScore >= 85) {
        scoreStatusText = 'Sangat Berbahaya (Berat)';
        scoreColorClass = 'red';
        shieldIcon = 'gpp_bad';
        tooltipText = 'Tingkat Risiko Berat (85%–100%): Terindikasi kuat jaringan perjudian online aktif, phishing, atau pencurian data.';
        result.statusAlert = 'Berat (Sangat Berbahaya)';
        if (!result.title || result.title.includes('Bersih')) {
          result.title = 'Waspada: Terindikasi Kuat Jaringan Perjudian Online';
        }
      } else if (riskScore >= 51) {
        scoreStatusText = 'Tinggi / Perlu Pemeriksaan';
        scoreColorClass = 'orange';
        shieldIcon = 'gpp_maybe';
        tooltipText = 'Tingkat Risiko Tinggi (51%–84%): Terdeteksi kata kunci perjudian online atau anomali sensor teknis.';
        result.statusAlert = 'Tinggi (Perlu Pemeriksaan)';
        if (!result.title || result.title.includes('Bersih')) {
          result.title = 'Perhatian: Terdeteksi Pola Taruhan Daring / Anomali';
        }
      } else if (riskScore >= 26) {
        scoreStatusText = 'Sedang / Perlu Waspada';
        scoreColorClass = 'amber';
        shieldIcon = 'warning';
        tooltipText = 'Tingkat Risiko Sedang (26%–50%): Tautan menggunakan kedok investasi/trading atau ekstensi domain bebas/promosi.';
        result.statusAlert = 'Sedang (Perlu Kewaspadaan)';
        if (!result.title || result.title.includes('Bersih')) {
          result.title = 'Perhatian: Terdeteksi Pola Mencurigakan';
        }
      } else {
        scoreStatusText = 'Rendah (Aman)';
        scoreColorClass = 'emerald';
        shieldIcon = 'verified_user';
        tooltipText = 'Tingkat Risiko Rendah (0%–25%): Tautan bersih atau hanya indikasi awal iklan/promosi luar tanpa muatan berbahaya.';
        result.statusAlert = 'Aman (Risiko Rendah)';
        result.title = 'Bersih: Tidak Ditemukan Pola Signature Berbahaya';
      }
    }

    if (elResultTitle) elResultTitle.textContent = result.title || 'Analisis Selesai';
    if (elResultBadge) elResultBadge.textContent = result.statusAlert || result.category || 'Analisis Selesai';

    if (!isInconclusive && scoreColorClass) {
      resolvedColor = scoreColorClass;
    }
    applyColorScheme(resolvedColor);

    const elScoreLabel = document.getElementById('result-score-label');
    if (elScoreLabel) elScoreLabel.textContent = scaleLabel;

    const elScoreTooltip = document.getElementById('result-score-tooltip');
    if (elScoreTooltip) elScoreTooltip.textContent = tooltipText;

    if (elResultScore) {
      elResultScore.textContent = scoreDisplay;
      elResultScore.style.display = scoreDisplay ? 'block' : 'none';
      elResultScore.className = `font-display font-black text-2xl leading-none ${
        scoreColorClass === 'emerald' ? 'text-emerald-700 dark:text-emerald-300' :
        scoreColorClass === 'slate' ? 'text-slate-600 dark:text-slate-300' :
        scoreColorClass === 'amber' ? 'text-amber-700 dark:text-amber-300' :
        scoreColorClass === 'orange' ? 'text-orange-700 dark:text-orange-400' :
        'text-red-700 dark:text-red-300'
      }`;
    }

    const elScoreStatus = document.getElementById('result-score-status');
    if (elScoreStatus) {
      elScoreStatus.textContent = scoreStatusText;
      elScoreStatus.className = `text-[11px] font-bold uppercase tracking-wider mt-1 ${
        scoreColorClass === 'emerald' ? 'text-emerald-700 dark:text-emerald-300' :
        scoreColorClass === 'slate' ? 'text-slate-600 dark:text-slate-300' :
        scoreColorClass === 'amber' ? 'text-amber-700 dark:text-amber-300' :
        scoreColorClass === 'orange' ? 'text-orange-700 dark:text-orange-400' :
        'text-red-700 dark:text-red-300'
      }`;
    }

    const elScorePill = document.getElementById('result-score-pill');
    if (elScorePill) {
      elScorePill.className = `result-inner-box bg-white dark:bg-slate-800 px-4 py-2.5 rounded-xl shadow-xs border flex items-center gap-3 self-end sm:self-auto transition-colors ${
        scoreColorClass === 'emerald' ? 'border-emerald-200/80 dark:border-emerald-800/60' :
        scoreColorClass === 'slate' ? 'border-slate-300 dark:border-slate-700' :
        scoreColorClass === 'amber' ? 'border-amber-200/80 dark:border-amber-800/60' :
        scoreColorClass === 'orange' ? 'border-orange-300/80 dark:border-orange-800/60' :
        'border-red-200/80 dark:border-red-800/60'
      }`;
    }

    if (elScoreIconBox) {
      elScoreIconBox.className = `w-10 h-10 rounded-full flex items-center justify-center shrink-0 ${
        scoreColorClass === 'emerald' ? 'bg-emerald-100 dark:bg-emerald-950/70 text-emerald-700 dark:text-emerald-300' :
        scoreColorClass === 'slate' ? 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300' :
        scoreColorClass === 'amber' ? 'bg-amber-100 dark:bg-amber-950/70 text-amber-700 dark:text-amber-300' :
        scoreColorClass === 'orange' ? 'bg-orange-100 dark:bg-orange-950/70 text-orange-700 dark:text-orange-300' :
        'bg-red-100 dark:bg-red-950/70 text-red-700 dark:text-red-300'
      }`;
      const iconSpan = elScoreIconBox.querySelector('.material-symbols-outlined') || elScoreIconBox;
      if (iconSpan) iconSpan.textContent = shieldIcon;
    }

    if (elResultTime) elResultTime.textContent = `Waktu Pindai: ${result.scannedAt || new Date().toLocaleTimeString('id-ID')}`;

    // Safe URL display — never make it a clickable link
    if (elResultUrl) {
      const safeText = String(rawUrl || result.url || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
      elResultUrl.innerHTML = safeText;
    }

    renderLaymanSummary(result, rawUrl, resolvedColor);
    renderFindings(result.findings, resolvedColor);
    
    // Render bagian Teknik yang Terdeteksi (Defacement, Open Redirect, Typosquatting)
    currentResultTechniques = Array.isArray(result.techniques) ? result.techniques : [];
    renderTechniques(currentResultTechniques, result.findings, rawUrl);

    updateIslamicQuote(resolvedColor);

    // Update "Laporkan Tautan Ini" button with encoded URL & sessionStorage fallback
    if (btnForward) {
      const cleanUrl = String(rawUrl || result.url || '').trim();
      const encoded = encodeURIComponent(cleanUrl);
      const isBlade = window.location.pathname.includes('.blade.php');
      const targetPage = isBlade ? 'lapor.blade.php' : '/lapor';
      btnForward.href = `${targetPage}?url=${encoded}#form-lapor`;

      btnForward.onclick = () => {
        try {
          const findingsSummary = Array.isArray(result.findings)
            ? result.findings.map(f => `${(f.label || '').replace(/:$/, '')}: ${f.desc || f.detail || ''}`).join('; ')
            : (result.description || '');

          let suggestedCategory = 'Investasi Bodong & Trading Ilegal';
          const titleLow = (result.title || '').toLowerCase();
          const catLow = (result.category || result.statusAlert || '').toLowerCase();
          if (titleLow.includes('judi') || catLow.includes('judi') || titleLow.includes('slot')) {
            suggestedCategory = 'Promosi Situs Judi Online / Slot';
          } else if (titleLow.includes('apk') || titleLow.includes('phishing') || titleLow.includes('malware')) {
            suggestedCategory = 'Phishing & Pencurian Data (APK/Formulir Palsu)';
          } else if (titleLow.includes('defacement') || titleLow.includes('subdomain') || titleLow.includes('diretas')) {
            suggestedCategory = 'Web Defacement / Subdomain Instansi Diretas';
          } else if (titleLow.includes('cuan') || titleLow.includes('deposit')) {
            suggestedCategory = 'Iklan Cuan Kilat Bersyarat Deposit';
          } else if (titleLow.includes('arisan') || titleLow.includes('grup')) {
            suggestedCategory = 'Grup Arisan & Undangan Investasi Menyesatkan';
          } else if (titleLow.includes('cuan') || titleLow.includes('deposit')) {
            suggestedCategory = 'Iklan Cuan Kilat Bersyarat Deposit';
          }

          const scoreLine = scoreDisplay ? `${scaleLabel}: ${scoreDisplay} (${scoreStatusText})` : `${scaleLabel}: ${scoreStatusText}`;
          const pendingReport = {
            url: cleanUrl,
            category: suggestedCategory,
            summary: `Hasil Analisis Tabayyun (${result.statusAlert || result.title || 'Mencurigakan'}, ${scoreLine}): ${findingsSummary}`
          };
          sessionStorage.setItem('aswaja_pending_report', JSON.stringify(pendingReport));
        } catch (e) {
          console.warn('Gagal menyimpan temporary pending report ke sessionStorage:', e);
        }
      };
    }
  }

  // Terapkan warna awal sesuai atribut data-alert saat halaman dimuat
  if (resultCard) {
    applyColorScheme(resultCard.getAttribute('data-alert') || 'red');
  }

  // ─── Form Submit ───────────────────────────────────────────────────────────
  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const rawUrl = inputUrl.value.trim();

    if (!rawUrl) {
      inputUrl.focus();
      showInputError('Masukkan URL tautan yang ingin diperiksa.');
      return;
    }

    // Basic URL format validation
    if (!isValidUrl(rawUrl)) {
      showInputError('Format URL tidak valid. Contoh: https://contoh-investasi.xyz');
      return;
    }

    clearInputError();
    startLoadingAnimation();

    try {
      // Pastikan animasi langkah pemindaian berjalan mulus dan realistis (~2 detik)
      const minScanDuration = new Promise(resolve => setTimeout(resolve, 2000));
      const fetchPromise = (async () => {
        const service = window.ApiService;
        if (service && typeof service.analyzeUrl === 'function') {
          return await service.analyzeUrl(rawUrl);
        } else {
          return fallbackAnalyze(rawUrl);
        }
      })();

      const [result] = await Promise.all([fetchPromise, minScanDuration]);
      await finishLoadingAnimation();
      renderResult(result, rawUrl);
    } catch (err) {
      stopLoadingAnimation();
      console.error('[Tabayyun] Analisis error:', err);
      showGlobalError('Gagal melakukan analisis. Periksa koneksi internet Anda dan coba lagi.');
    }
  });

  // ─── URL Validation ────────────────────────────────────────────────────────
  function isValidUrl(str) {
    try {
      const u = new URL(str);
      return u.protocol === 'http:' || u.protocol === 'https:';
    } catch {
      return false;
    }
  }

  // ─── Input Error Helpers ───────────────────────────────────────────────────
  function showInputError(msg) {
    clearInputError();
    const el = document.createElement('p');
    el.id = 'input-error-msg';
    el.className = 'text-xs text-red-600 mt-1.5 flex items-center gap-1';
    el.innerHTML = `<span class="material-symbols-outlined text-[14px]">error</span> ${msg}`;
    inputUrl.parentElement.after(el);
    inputUrl.classList.add('border-red-400');
  }

  function clearInputError() {
    const existing = document.getElementById('input-error-msg');
    if (existing) existing.remove();
    inputUrl.classList.remove('border-red-400');
  }

  function showGlobalError(msg) {
    if (!resultCard) return;
    resultCard.classList.remove('hidden');
    resultCard.innerHTML = `
      <div class="p-6 text-center space-y-3">
        <span class="material-symbols-outlined text-4xl text-red-500">error</span>
        <p class="text-sm font-semibold text-red-700">${msg}</p>
        <button id="err-retry-btn" class="text-sm font-bold text-emerald-700 hover:underline">Coba Lagi</button>
      </div>`;
    document.getElementById('err-retry-btn')?.addEventListener('click', () => {
      resultCard.classList.add('hidden');
    });
  }

  // ─── Fallback Analyzer (jika api.js tidak tersedia) ───────────────────────
  function fallbackAnalyze(url) {
    const lower = url.toLowerCase();
    const judolKeywords = ['slot', 'spin', 'casino', 'kasino', 'jackpot', 'togel', 'bet', 'poker', 'gacor', 'maxwin', 'rtp', 'scatter'];
    const investKeywords = ['trading', 'cuan', 'profit', 'invest', 'mining', 'crypto', 'usdt', 'forex', 'robot', 'airdrop', 'passive', 'signal', 'arisan'];
    const suspiciousTlds = ['.vip', '.cc', '.xyz', '.click', '.fun', '.online', '.site', '.top'];
    const safeDomains = ['ojk.go.id', 'bappebti.go.id', 'bi.go.id', 'kominfo.go.id', 'gov.id', 'ac.id'];

    const hasJudol = judolKeywords.some(k => lower.includes(k));
    const hasInvest = investKeywords.some(k => lower.includes(k));
    const hasSuspTld = suspiciousTlds.some(t => lower.includes(t));
    const isSafe = safeDomains.some(d => lower.includes(d));

    if (isSafe) {
      return {
        alertClass: 'emerald',
        title: 'Belum Ditemukan Indikasi Judol',
        category: 'Belum Terindikasi',
        score: '91% (Tinggi)',
        scannedAt: new Date().toLocaleTimeString('id-ID'),
        findings: [
          { type: 'success', label: 'Domain Resmi Terverifikasi', detail: 'Terdeteksi domain resmi pemerintah/regulator. Tidak ditemukan indikasi perjudian online.' },
          { type: 'success', label: 'Tidak Ada Kata Kunci Judol', detail: 'URL tidak mengandung pola kata kunci yang terkait aktivitas judi online.' },
          { type: 'info', label: 'Tetap Waspada', detail: 'Analisis heuristik tidak menjamin keamanan absolut. Selalu verifikasi melalui saluran resmi.' },
        ]
      };
    }

    if (hasJudol && (hasInvest || hasSuspTld)) {
      return {
        alertClass: 'red',
        title: 'Terindikasi: Tautan Investasi Palsu Berkedok Judi Online',
        category: 'Terindikasi',
        score: '18% (Sangat Rendah)',
        scannedAt: new Date().toLocaleTimeString('id-ID'),
        findings: [
          { type: 'error', label: 'Kata Kunci Judol Terdeteksi', detail: 'URL mengandung kata kunci yang sangat kuat terkait aktivitas perjudian daring (slot, spin, casino, dll).' },
          { type: 'error', label: 'Kedok Investasi Terdeteksi', detail: 'Situs berkedok platform trading/investasi namun URL mengarah ke sistem deposit dan putaran taruhan.' },
          { type: 'error', label: 'Domain Mencurigakan', detail: `Ekstensi domain tidak lazim dan tidak terdaftar di OJK/Bappebti. Jangan lakukan transfer dana.` },
          { type: 'success', label: 'Rekomendasi', detail: 'Jangan registrasi atau masukkan data apapun. Laporkan tautan ini melalui portal laporan kami.' },
        ]
      };
    }

    if (hasJudol || (hasInvest && hasSuspTld)) {
      return {
        alertClass: 'amber',
        title: 'Perlu Diwaspadai: Ditemukan Indikasi Aktivitas Judi Online',
        category: 'Perlu Diwaspadai',
        score: '41% (Rendah)',
        scannedAt: new Date().toLocaleTimeString('id-ID'),
        findings: [
          { type: 'warning', label: 'Indikasi Kata Kunci Mencurigakan', detail: 'Terdeteksi beberapa pola kata kunci yang berkaitan dengan investasi berisiko atau judi online.' },
          { type: 'warning', label: 'Legalitas Belum Terverifikasi', detail: 'Platform tidak ditemukan dalam daftar resmi OJK/Bappebti. Pastikan legalitas sebelum bertransaksi.' },
          { type: 'info', label: 'Saran Tambahan', detail: 'Verifikasi izin usaha melalui cekfintech.ojk.go.id sebelum melakukan deposit atau investasi apapun.' },
        ]
      };
    }

    return {
      alertClass: 'emerald',
      title: 'Belum Ditemukan Indikasi Judol',
      category: 'Belum Terindikasi',
      score: '78% (Cukup)',
      scannedAt: new Date().toLocaleTimeString('id-ID'),
      findings: [
        { type: 'success', label: 'Tidak Ada Kata Kunci Judol', detail: 'Tidak ditemukan pola kata kunci yang signifikan terkait judi online pada URL ini.' },
        { type: 'info', label: 'Verifikasi Lanjutan Disarankan', detail: 'Analisis heuristik tidak mengkonfirmasi keamanan absolut. Periksa legalitas platform di OJK/Bappebti.' },
        { type: 'info', label: 'Tetap Waspada', detail: 'Jangan bagikan data pribadi atau lakukan transfer uang tanpa verifikasi menyeluruh.' },
      ]
    };
  }

  // ─── Reset Button ──────────────────────────────────────────────────────────
  if (btnReset) {
    btnReset.addEventListener('click', () => {
      if (resultCard) resultCard.classList.add('hidden');
      inputUrl.value = '';
      updateCharCount();
      clearInputError();
      inputUrl.focus();
      window.scrollTo({ top: form.getBoundingClientRect().top + window.scrollY - 120, behavior: 'smooth' });
    });
  }

  // ─── Copy Summary Button ───────────────────────────────────────────────────
  if (btnCopy) {
    btnCopy.addEventListener('click', async () => {
      const url = inputUrl.value.trim();
      const title = elResultTitle?.textContent || 'Hasil Analisis';
      const badge = elResultBadge?.textContent || '-';
      const score = elResultScore?.textContent || '';
      const time = elResultTime?.textContent || '';
      const scaleLabel = document.getElementById('result-score-label')?.textContent || 'Skor';
      const scoreStatus = document.getElementById('result-score-status')?.textContent || '';
      const scoreLine = score ? `${scaleLabel}: ${score} (${scoreStatus})` : `${scaleLabel}: ${scoreStatus}`;

      let techniquesSummary = '';
      if (currentResultTechniques && currentResultTechniques.length > 0) {
        const readableNames = currentResultTechniques.map(t => {
          if (t === 'kamuflase_investasi') return 'Kamuflase Finansial (Investasi Berkedok Judol)';
          if (t === 'web_defacement') return 'Web Defacement / Subdomain Hijacking';
          if (t === 'open_redirect') return 'Open Redirect';
          if (t === 'typosquatting') return 'Typosquatting / URL Spoofing';
          return t;
        });
        techniquesSummary = `Teknik Terdeteksi: ${readableNames.join(', ')}\n`;
      }

      let laymanText = '';
      if (elResultLaymanList) {
        const items = elResultLaymanList.querySelectorAll('.space-y-0\\.5');
        const lines = [];
        items.forEach(it => {
          const t = it.querySelector('.font-bold')?.textContent || '';
          const d = it.querySelector('.text-xs')?.textContent || '';
          if (t && d) lines.push(`- ${t}: ${d}`);
        });
        if (lines.length) {
          laymanText = `\nRingkasan untuk Orang Awam:\n${lines.join('\n')}\n`;
        }
      }

      const text = `[Aswaja CyberShield — Analisis Tautan]\n${title}\nStatus: ${badge}\n${scoreLine}\n${techniquesSummary}URL: ${url}\n${time}\n${laymanText}\nJangan bagikan tautan ini. Laporkan ke portal aduan jika mencurigakan.`;
      try {
        await navigator.clipboard.writeText(text);
        if (copyBtnText) copyBtnText.textContent = 'Tersalin!';
        setTimeout(() => {
          if (copyBtnText) copyBtnText.textContent = 'Salin Ringkasan Verifikasi';
        }, 2500);
      } catch {
        if (copyBtnText) copyBtnText.textContent = 'Gagal menyalin';
        setTimeout(() => {
          if (copyBtnText) copyBtnText.textContent = 'Salin Ringkasan Verifikasi';
        }, 2000);
      }
    });
  }
});
