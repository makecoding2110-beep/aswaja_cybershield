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
  const moduleItems = document.querySelectorAll('.module-item');

  let currentCategory = 'all';

  function applyFilters() {
    const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
    moduleItems.forEach((item) => {
      const cat = item.getAttribute('data-category') || '';
      const title = item.getAttribute('data-title') || '';

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

  document.querySelectorAll('.btn-read-more').forEach((btn) => {
    btn.addEventListener('click', () => {
      const title = btn.getAttribute('data-title') || '';
      const badge = btn.getAttribute('data-badge') || 'Materi';
      const content = btn.getAttribute('data-content') || '';

      if (readerTitle) readerTitle.textContent = title;
      if (readerBadge) readerBadge.textContent = badge;
      if (readerBody) readerBody.innerHTML = `<p class="leading-relaxed">${content}</p>`;

      if (modalReader && typeof modalReader.showModal === 'function') {
        modalReader.showModal();
        modalReader.style.opacity = '0';
        modalReader.style.transform = 'scale(0.95)';
        modalReader.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
        requestAnimationFrame(() => {
          requestAnimationFrame(() => {
            modalReader.style.opacity = '1';
            modalReader.style.transform = 'scale(1)';
          });
        });
      }
    });
  });

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

  // ── Render Dynamic Prinsip 4T from ApiService / Storage ─────────
  async function syncPrinsip4T() {
    const grid = document.getElementById('prinsip-4t-public-grid');
    if (!grid || !window.ApiService) return;

    try {
      const res = await window.ApiService.getPrinsip4T();
      const list = res.data || res;
      if (!list || !list.length) return;

      const icons = {
        'T1': 'pan_tool',
        'T2': 'search_check',
        'T3': 'verified',
        'T4': 'psychology'
      };

      grid.innerHTML = list.map(item => `
        <a href="${item.link || '#'}" class="card-hover-effect group p-4 rounded-xl bg-slate-50 hover:bg-emerald-50/60 border border-slate-200/70 hover:border-emerald-300 space-y-2 transition-all block shadow-xs hover:shadow-sm">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-emerald-700 bg-white px-2 py-0.5 rounded border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-colors">${item.code || item.id}</span>
            <span class="material-symbols-outlined text-emerald-600 text-lg group-hover:scale-110 transition-transform">${item.icon || icons[item.code] || 'shield'}</span>
          </div>
          <h3 class="font-display font-bold text-base text-slate-900 group-hover:text-emerald-700 transition-colors">${item.title || item.name || 'Kaidah 4T'}</h3>
          <span class="text-xs font-semibold text-emerald-700 block">${item.subtitle || item.tagline || item.arabic || ''}</span>
          <p class="text-xs text-slate-600 leading-relaxed">
            ${item.description || ''}
          </p>
          <div class="pt-2 flex items-center gap-1 text-[11px] font-bold text-emerald-600 group-hover:text-emerald-700">
            <span>Kaji Selengkapnya</span>
            <span class="material-symbols-outlined text-[14px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
          </div>
        </a>
      `).join('');
    } catch (e) {
      console.warn('Fallback static 4T digunakan:', e);
    }
  }

  syncPrinsip4T();
});
