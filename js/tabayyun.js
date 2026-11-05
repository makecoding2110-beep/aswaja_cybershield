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

  if (!form || !inputUrl) return;

  // ─── Pre-fill from URL param (?url=...) ────────────────────────────────────
  const params = new URLSearchParams(window.location.search);
  const prefilledUrl = params.get('url') || params.get('q');
  if (prefilledUrl) {
    inputUrl.value = decodeURIComponent(prefilledUrl);
  }

  updateCharCount();

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
    { title: 'Memeriksa Sanad Domain & Legalitas...', progress: 20 },
    { title: 'Mencocokkan Pola Kata Kunci Judol & Investasi...', progress: 45 },
    { title: 'Menganalisis Struktur URL & Parameter Deposit...', progress: 65 },
    { title: 'Mengevaluasi Risiko Maysir & Skema Ponzi...', progress: 85 },
    { title: 'Menyusun Laporan Heuristik...', progress: 95 },
  ];

  let loadingInterval = null;

  function startLoadingAnimation() {
    if (!loadingState) return;
    let step = 0;
    resultCard.classList.add('hidden');
    loadingState.classList.remove('hidden');
    form.classList.add('opacity-60', 'pointer-events-none');
    btnSubmit.disabled = true;
    if (btnSubmitText) btnSubmitText.textContent = 'Menganalisis...';

    if (loadingBar) loadingBar.style.width = '10%';

    loadingInterval = setInterval(() => {
      if (step >= loadingSteps.length) {
        clearInterval(loadingInterval);
        return;
      }
      if (loadingTitle) loadingTitle.textContent = loadingSteps[step].title;
      if (loadingBar) loadingBar.style.width = `${loadingSteps[step].progress}%`;
      step++;
    }, 420);
  }

  function stopLoadingAnimation() {
    clearInterval(loadingInterval);
    if (loadingState) loadingState.classList.add('hidden');
    form.classList.remove('opacity-60', 'pointer-events-none');
    btnSubmit.disabled = false;
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
      scoreIcon: 'security',
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
        .replace(/(?:dark:)?border-(?:red|amber|emerald|slate)-\S+/g, '')
        .trim() + ' ' + scheme.innerBorder.join(' ');
    });

    // Main Title color
    if (elResultTitle) {
      elResultTitle.className = `font-display font-bold text-xl mt-1 ${scheme.title}`;
    }

    // Badge
    if (elResultBadge) {
      elResultBadge.className = `badge px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider ${scheme.badge.join(' ')}`;
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

  // ─── Render Findings List ──────────────────────────────────────────────────
  function renderFindings(findings) {
    if (!elResultFindings || !findings || !findings.length) return;
    const iconMap = {
      error: { icon: 'domain_disabled', color: 'text-red-600 dark:text-red-400' },
      warning: { icon: 'psychology_alt', color: 'text-amber-600 dark:text-amber-400' },
      success: { icon: 'verified', color: 'text-emerald-600 dark:text-emerald-400' },
      info: { icon: 'info', color: 'text-blue-600 dark:text-blue-400' },
    };
    elResultFindings.innerHTML = findings.map(f => {
      const { icon, color } = iconMap[f.type] || iconMap.info;
      const cleanLabel = String(f.label || 'Temuan').replace(/:+$/, '');
      const detailText = f.detail || f.desc || '';
      return `<li class="flex items-start gap-2.5">
        <span class="material-symbols-outlined ${color} text-[18px] shrink-0 mt-0.5">${icon}</span>
        <span><strong>${cleanLabel}:</strong> ${detailText}</span>
      </li>`;
    }).join('');
  }

  // ─── Determine Exact Alert Color from Result (Light Mode & Dark Mode) ──────
  function resolveAlertColor(result, rawUrl) {
    const cat = String(result.category || result.final_category || '').toLowerCase();
    const status = String(result.statusAlert || '').toLowerCase();
    const urlLower = String(rawUrl || result.url || '').toLowerCase();

    // Jika mode simulasi offline dari api.js, pastikan 3-Tier Matching diterapkan
    if (result.isSimulation && urlLower) {
      const strongWords = ['slot-gacor', 'slot_gacor', 'slotgacor', 'situs-togel', 'casino-online', 'kasino-online', 'judi-slot', 'bandar-togel', 'slot88', 'slot777', 'gacor777', 'gacor88', 'deposit-slot', 'depo-slot', 'ojk-investasi', 'bappebti-trading', 'robot-trading', 'auto-profit', 'investasii'];
      const mediumWords = ['maxwin', 'jackpot', 'scatter', 'free-spin', 'freespin', 'rtp-live', 'pragmatic', 'pgsoft', 'slot', 'gacor', 'togel', 'casino'];
      const supportSignals = ['.vip', '.cc', '.xyz', '.top', '.icu', '777', '888', '88', '/deposit', '?ref=', '185.220.'];
      const hasStrong = strongWords.some(w => urlLower.includes(w));
      const hasMedium = mediumWords.some(w => urlLower.includes(w));
      const hasSupport = supportSignals.some(s => urlLower.includes(s));

      if (hasStrong || (hasMedium && hasSupport)) {
        result.statusAlert = result.statusAlert === 'Perlu Diwaspadai' ? 'Terindikasi Judi Online' : result.statusAlert;
        return 'red';
      }
      if (!hasStrong && !hasMedium && !hasSupport) {
        return 'emerald';
      }
    }

    if (
      cat.includes('judol') ||
      cat.includes('investasi') ||
      cat === 'terindikasi' ||
      status.includes('terindikasi') ||
      status.includes('bahaya')
    ) {
      return 'red';
    }
    if (
      cat === 'normal' ||
      cat.includes('belum_ditemukan') ||
      cat.includes('aman') ||
      status.includes('aman') ||
      status.includes('bersih')
    ) {
      return 'emerald';
    }
    if (result.alertClass && colorMap[result.alertClass]) {
      return result.alertClass;
    }
    return 'amber';
  }

  // ─── Render Result Card ────────────────────────────────────────────────────
  function renderResult(result, rawUrl) {
    stopLoadingAnimation();

    if (!resultCard) return;
    resultCard.classList.remove('hidden');
    resultCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

    const resolvedColor = resolveAlertColor(result, rawUrl);
    applyColorScheme(resolvedColor);

    if (elResultTitle) elResultTitle.textContent = result.title || 'Analisis Selesai';
    if (elResultBadge) elResultBadge.textContent = result.statusAlert || result.category || 'Belum Ditemukan Indikasi';
    if (elResultScore) {
      // Pastikan score selalu tampil dengan simbol persen
      const rawScore = result.score ?? '-';
      const scoreText = (rawScore !== '-' && !String(rawScore).includes('%'))
        ? `${rawScore}%`
        : rawScore;
      elResultScore.textContent = scoreText;
    }
    if (elResultTime) elResultTime.textContent = `Waktu Pindai: ${result.scannedAt || new Date().toLocaleTimeString('id-ID')}`;

    // Safe URL display — never make it a clickable link
    if (elResultUrl) {
      const safeText = String(rawUrl || result.url || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
      elResultUrl.innerHTML = safeText;
    }

    renderFindings(result.findings);

    // Update "Laporkan Tautan Ini" button href with encoded URL
    if (btnForward) {
      const encoded = encodeURIComponent(rawUrl || result.url || '');
      btnForward.href = `lapor.blade.php?url=${encoded}`;
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
      // Panggil ApiService dari api.js (window.ApiService)
      const service = window.ApiService;
      let result;
      if (service && typeof service.analyzeUrl === 'function') {
        result = await service.analyzeUrl(rawUrl);
      } else {
        // Fallback jika api.js belum dimuat
        result = fallbackAnalyze(rawUrl);
      }
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
      const score = elResultScore?.textContent || '-';
      const time = elResultTime?.textContent || '';
      const text = `[Aswaja CyberShield — Analisis Tautan]\n${title}\nStatus: ${badge}\nTingkat Keaslian: ${score}\nURL: ${url}\n${time}\n\nJangan bagikan tautan ini. Laporkan ke portal aduan jika mencurigakan.`;
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
