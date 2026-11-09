document.addEventListener('DOMContentLoaded', () => {
  // ── Accordion 4T Logic ──────────────────────────────────────────
  const accHeader = document.getElementById('accordion-4t-header');
  const accBody = document.getElementById('accordion-4t-body');
  const accChevron = document.getElementById('accordion-chevron');

  if (accHeader && accBody) {
    accHeader.addEventListener('click', () => {
      accBody.classList.toggle('hidden');
      if (accChevron) accChevron.classList.toggle('rotate-180');
    });
  }

  // ── Filter & Search Logic ────────────────────────────────────────
  const searchInput = document.getElementById('module-search-input');
  const filterBtns = document.querySelectorAll('.filter-btn');

  let currentCategory = 'all';

  function applyFilters() {
    const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
    const moduleItems = document.querySelectorAll('.module-item');
    moduleItems.forEach((item) => {
      const cat = (item.getAttribute('data-category') || '').toLowerCase();
      const title = (item.getAttribute('data-title') || '').toLowerCase();

      const matchCat = currentCategory === 'all' || cat.includes(currentCategory);
      const matchSearch = !query || title.includes(query) || (item.textContent || '').toLowerCase().includes(query);

      item.classList.toggle('hidden', !(matchCat && matchSearch));
    });
  }

  if (searchInput) {
    searchInput.addEventListener('input', applyFilters);
  }

  filterBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
      filterBtns.forEach(b => {
        b.className = 'filter-btn px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all bg-white text-slate-600 border border-slate-200 hover:bg-slate-100';
      });
      btn.className = 'filter-btn px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all bg-emerald-600 text-white shadow-sm';
      currentCategory = btn.getAttribute('data-filter') || 'all';
      applyFilters();
    });
  });

  // ── Modal Reader Logic ───────────────────────────────────────────
  const modalReader = document.getElementById('modal-reader');
  const readerBadge = document.getElementById('modal-reader-badge');
  const readerTitle = document.getElementById('modal-reader-title');
  const readerBody = document.getElementById('modal-reader-body');
  const btnCloseReader = document.getElementById('btn-close-reader');
  const btnDoneReader = document.getElementById('btn-done-reader');

  function closeModal() {
    if (!modalReader) return;
    modalReader.style.opacity = '0';
    modalReader.style.transform = 'scale(0.95)';
    setTimeout(() => {
      if (typeof modalReader.close === 'function') {
        modalReader.close();
      }
      modalReader.style.opacity = '';
      modalReader.style.transform = '';
    }, 180);
  }

  if (btnCloseReader) btnCloseReader.addEventListener('click', closeModal);
  if (btnDoneReader) btnDoneReader.addEventListener('click', closeModal);

  if (modalReader) {
    modalReader.addEventListener('click', (e) => {
      if (e.target === modalReader) closeModal();
    });
  }

  function escapeHtml(str) {
    return String(str || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function getCategoryIcon(category) {
    const c = String(category || '').toLowerCase();
    if (c.includes('forensik') || c.includes('investigasi')) return 'search_check';
    if (c.includes('algoritma') || c.includes('script')) return 'code';
    if (c.includes('infiltrasi') || c.includes('malware') || c.includes('phishing')) return 'phishing';
    if (c.includes('finansial') || c.includes('maysir') || c.includes('judi')) return 'casino';
    if (c.includes('fatwa') || c.includes('fiqih') || c.includes('syariah')) return 'balance';
    if (c.includes('tabayyun') || c.includes('4t')) return 'verified';
    if (c.includes('regulasi') || c.includes('hukum')) return 'gavel';
    return 'menu_book';
  }

  function bindCardInteractions(container) {
    if (!container) return;
    container.querySelectorAll('.module-item[data-href]').forEach((card) => {
      card.style.cursor = 'pointer';
      card.addEventListener('click', (e) => {
        if (e.target.closest('a, button')) return;
        const href = card.getAttribute('data-href');
        if (href) window.location.href = href;
      });
      card.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          const href = card.getAttribute('data-href');
          if (href) window.location.href = href;
        }
      });
    });
  }

  // ── 1. Sinkronisasi Prinsip 4T dari Database (public.prinsip_4t) ──
  async function syncPrinsip4T() {
    const grid = document.getElementById('prinsip-4t-public-grid');
    if (!grid || !window.ApiService) return;

    try {
      const res = await window.ApiService.getPrinsip4T();
      const list = Array.isArray(res) ? res : (res?.data || []);
      if (!list || !list.length) return;

      const defaultLinks = {
        'T1': 'artikel/prinsip-tawaqquf.blade.php',
        'T2': 'artikel/prinsip-tastabbus.blade.php',
        'T3': 'artikel/prinsip-tatsabbut.blade.php',
        'T4': 'artikel/prinsip-taaqqul.blade.php'
      };

      const defaultIcons = {
        'T1': 'pause_circle',
        'T2': 'search',
        'T3': 'verified_user',
        'T4': 'psychology'
      };

      grid.innerHTML = list.map(item => {
        const code = item.code || item.id || 'T1';
        const icon = item.icon || defaultIcons[code] || 'shield';
        const title = item.name || item.title || 'Kaidah 4T';
        const subtitle = item.arabic || item.subtitle || item.tagline || '';
        const desc = item.description || '';

        return `
          <div class="card-hover-effect p-4 sm:p-5 rounded-2xl bg-slate-50 dark:bg-slate-750 border border-slate-200/80 dark:border-slate-700 space-y-2.5 shadow-2xs">
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400 bg-white dark:bg-slate-800 px-2.5 py-0.5 rounded-md border border-emerald-200/80 dark:border-emerald-800 shadow-2xs">${escapeHtml(code)}</span>
              <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-xl">${escapeHtml(icon)}</span>
            </div>
            <div class="flex items-baseline justify-between gap-2">
              <h3 class="font-display font-bold text-base text-slate-900 dark:text-white">${escapeHtml(title)}</h3>
              <span class="font-serif text-base font-semibold text-emerald-700 dark:text-emerald-400" dir="rtl">${escapeHtml(subtitle)}</span>
            </div>
            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
              ${escapeHtml(desc)}
            </p>
          </div>
        `;
      }).join('');
    } catch (e) {
      console.warn('Fallback static 4T digunakan:', e);
    }
  }

  // ── 2. Sinkronisasi Artikel Edukasi dari Database (public.articles) ──
  async function syncArticles() {
    const grid = document.getElementById('module-cards-grid');
    if (!grid || !window.ApiService || typeof window.ApiService.getArticles !== 'function') return;

    try {
      const res = await window.ApiService.getArticles();
      const allArticles = Array.isArray(res) ? res : (res?.data || []);
      if (!allArticles || !allArticles.length) return;

      const published = allArticles.filter(item => {
        const st = String(item.status || 'Published').toLowerCase();
        return st === 'published' || st === 'tayang';
      });

      const listToRender = published.length > 0 ? published : allArticles;
      const thematicArticles = listToRender;

      const countBadge = document.getElementById('etika-module-count-label');
      if (countBadge) {
        countBadge.textContent = `${thematicArticles.length} Modul Literasi Praktis`;
      }

      grid.innerHTML = thematicArticles.map((item, idx) => {
        const title = item.title || 'Modul Literasi Siber';
        const category = item.category || 'Literasi Siber';
        const dateStr = item.date || 'Terbaru';
        const summary = item.summary || item.excerpt || '';
        const slug = item.slug || '';
        const href = `artikel/baca.blade.php?id=${encodeURIComponent(item.id)}&slug=${encodeURIComponent(slug)}`;
        const icon = getCategoryIcon(category);

        const rawImg = item.cover_image || item.coverImage || item.image || (Number(item.id) === 1 ? '../images/artikel-1.jpg' : '');
        const hasUploadedImg = rawImg && (
          rawImg.startsWith('data:image/') ||
          rawImg.startsWith('http://') ||
          rawImg.startsWith('https://') ||
          rawImg.startsWith('/') ||
          rawImg.includes('images/')
        );

        const thumbnailHtml = hasUploadedImg
          ? `
            <div class="relative overflow-hidden group" style="height:200px; background:linear-gradient(135deg,#065f46,#059669);">
              <div class="absolute inset-0 flex items-center justify-center opacity-10">
                <span class="material-symbols-outlined text-white" style="font-size:120px;">${escapeHtml(icon)}</span>
              </div>
              <img src="${escapeHtml(rawImg)}" alt="${escapeHtml(title)}" class="relative z-10 w-full h-full object-cover transition-transform duration-500 hover:scale-105" onerror="this.onerror=null; this.remove();">
            </div>
          `
          : `
            <div class="relative overflow-hidden" style="height:200px; background:linear-gradient(135deg,#065f46,#059669);">
              <div class="absolute inset-0 flex items-center justify-center opacity-10">
                <span class="material-symbols-outlined text-white" style="font-size:120px;">${escapeHtml(icon)}</span>
              </div>
            </div>
          `;

        return `
          <article class="module-item card-hover-effect bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/90 dark:border-slate-700 overflow-hidden shadow-md hover:shadow-xl flex flex-col justify-between hover:border-emerald-300 dark:hover:border-emerald-600 transition-all" data-category="${escapeHtml(category.toLowerCase())}" data-title="${escapeHtml(title.toLowerCase())}" data-index="${idx}" data-href="${escapeHtml(href)}" role="link" tabindex="0">
            ${thumbnailHtml}
            <div class="p-5 flex flex-col flex-grow">
              <div class="flex items-center justify-between gap-2 mb-2">
                <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">${escapeHtml(category)} &bull; ${escapeHtml(dateStr)}</p>
              </div>
              <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white mb-3 leading-snug flex-grow">
                ${escapeHtml(title)}
              </h3>
              <p class="text-sm text-slate-600 dark:text-slate-300 line-clamp-3 leading-relaxed mb-4">
                ${escapeHtml(summary)}
              </p>
              <a href="${escapeHtml(href)}" class="group/btn text-sm font-bold text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 inline-flex items-center gap-1.5 transition-all mt-auto">
                <span>Baca Selengkapnya</span>
                <span class="material-symbols-outlined text-[16px] group-hover/btn:translate-x-1 transition-transform duration-200">arrow_forward</span>
              </a>
            </div>
          </article>
        `;
      }).join('');

      bindCardInteractions(grid);
      applyFilters();
    } catch (e) {
      console.warn('Gagal menyinkronkan artikel dari basis data:', e);
    }
  }

  bindCardInteractions(document.getElementById('module-cards-grid'));
  syncPrinsip4T();
  syncArticles();
});
