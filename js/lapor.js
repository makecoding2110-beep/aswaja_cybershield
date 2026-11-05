/**
 * lapor.js — Aswaja CyberShield
 * Handler form "Portal Lapor Siber"
 * Fokus: Tautan investasi menyesatkan yang mengarah ke judi online (judol/maysir)
 * Bergantung pada: js/api.js (window.ApiService)
 *
 * Alur:
 *  1. Prefill kolom URL jika ada ?url=... dari halaman tabayyun
 *  2. User isi form (kategori, URL target, kronologi)
 *  3. Submit → ApiService.submitReport(data)
 *  4. Tampilkan modal sukses dengan nomor tiket
 *  5. Tambahkan baris baru ke tabel laporan terbaru
 */

document.addEventListener('DOMContentLoaded', () => {

  // ─── Elemen DOM ─────────────────────────────────────────────────────────────
  const uploadDropzone     = document.getElementById('upload-dropzone');
  const fileUpload         = document.getElementById('file-upload');
  const filePreview        = document.getElementById('file-preview');
  const fileName           = document.getElementById('file-name');
  const btnRemoveFile      = document.getElementById('btn-remove-file');
  const formLaporSiber     = document.getElementById('form-lapor-siber');
  const btnSubmitLapor     = document.getElementById('btn-submit-lapor');
  const btnSubmitLaporText = document.getElementById('btn-submit-lapor-text');
  const modalSuksesLapor   = document.getElementById('modal-sukses-lapor');
  const btnCloseDialog     = document.getElementById('btn-close-dialog');
  const tableReportsBody   = document.getElementById('table-reports-body');
  const tableSearchInput   = document.getElementById('table-search-input');

  if (!formLaporSiber && !tableReportsBody) return;

  // ─── Prefill URL dari query param (?url=...) ────────────────────────────────
  const params = new URLSearchParams(window.location.search);
  const prefilledUrl = params.get('url') || params.get('q') || '';
  if (prefilledUrl) {
    const inputTarget = document.getElementById('lapor-target');
    if (inputTarget) {
      inputTarget.value = decodeURIComponent(prefilledUrl);
    }
    // Jika ada, pre-select kategori yang paling umum
    const selectKategori = document.getElementById('lapor-kategori');
    if (selectKategori && !selectKategori.value) {
      selectKategori.value = 'Investasi Berkedok Trading / Crypto';
    }
  }

  // ─── Upload file: klik area dropzone ────────────────────────────────────────
  if (uploadDropzone && fileUpload) {
    uploadDropzone.addEventListener('click', () => fileUpload.click());
    uploadDropzone.addEventListener('dragover', (e) => {
      e.preventDefault();
      uploadDropzone.classList.add('border-emerald-500', 'bg-emerald-50/30');
    });
    uploadDropzone.addEventListener('dragleave', () => {
      uploadDropzone.classList.remove('border-emerald-500', 'bg-emerald-50/30');
    });
    uploadDropzone.addEventListener('drop', (e) => {
      e.preventDefault();
      uploadDropzone.classList.remove('border-emerald-500', 'bg-emerald-50/30');
      const files = e.dataTransfer.files;
      if (files.length > 0) handleFileSelect(files[0]);
    });
    fileUpload.addEventListener('change', (e) => {
      if (e.target.files.length > 0) handleFileSelect(e.target.files[0]);
    });
  }

  function handleFileSelect(file) {
    const allowedTypes = ['image/png', 'image/jpeg', 'image/webp', 'image/gif'];
    const maxSize = 5 * 1024 * 1024; // 5MB
    if (!allowedTypes.includes(file.type)) {
      alert('Format file tidak didukung. Gunakan PNG, JPG, WEBP, atau GIF.');
      return;
    }
    if (file.size > maxSize) {
      alert('Ukuran file terlalu besar. Maksimal 5MB.');
      return;
    }
    if (fileName) fileName.textContent = file.name;
    if (filePreview) filePreview.classList.remove('hidden');
    if (uploadDropzone) uploadDropzone.classList.add('hidden');
  }

  if (btnRemoveFile) {
    btnRemoveFile.addEventListener('click', () => {
      if (fileUpload) fileUpload.value = '';
      if (filePreview) filePreview.classList.add('hidden');
      if (uploadDropzone) uploadDropzone.classList.remove('hidden');
    });
  }

  // ─── Load Recent Reports ke Tabel ───────────────────────────────────────────
  let allReports = [];

  async function loadReportsTable() {
    if (!tableReportsBody) return;
    try {
      const service = window.ApiService;
      if (service && typeof service.getReports === 'function') {
        allReports = await service.getReports();
      } else {
        // Fallback data dummy judol
        allReports = getFallbackReports();
      }
      renderTable(allReports);
    } catch (err) {
      console.error('[lapor.js] Gagal memuat laporan:', err);
      allReports = getFallbackReports();
      renderTable(allReports);
    }
  }

  function getFallbackReports() {
    return [
      {
        id: 'RPT-001',
        target: 'https://cuan-trading-vip88.vip/deposit-slot',
        category: 'Investasi Berkedok Trading / Crypto',
        description: 'Menawarkan passive income 30%/hari dengan robot trading, ujungnya deposit ke slot casino.',
        status: 'Selesai',
        date: '2026-11-01',
      },
      {
        id: 'RPT-002',
        target: 'https://mining-usdt-spin.cc/wallet',
        category: 'Tautan Promosi Slot / Kasino Berkedok Game Finansial',
        description: 'Mining kripto palsu yang sebenarnya sistem taruhan spin berkedok dompet digital.',
        status: 'Diproses',
        date: '2026-11-02',
      },
      {
        id: 'RPT-003',
        target: 'https://arisan-kilat-group.xyz/daftar',
        category: 'Tautan Undangan Grup Investasi / Arisan Bodong',
        description: 'Grup WhatsApp arisan dengan sistem perputaran uang yang ujungnya mengarah ke platform judi.',
        status: 'Masuk',
        date: '2026-11-03',
      },
    ];
  }

  const statusStyles = {
    'Masuk':    'bg-slate-100 text-slate-700',
    'Diproses': 'bg-amber-100 text-amber-800',
    'Selesai':  'bg-emerald-100 text-emerald-800',
  };

  function renderTable(reports) {
    if (!tableReportsBody) return;
    if (!reports || reports.length === 0) {
      tableReportsBody.innerHTML = `
        <tr>
          <td colspan="5" class="py-10 text-center text-sm text-slate-400">
            <span class="material-symbols-outlined text-3xl block mb-2">inbox</span>
            Belum ada laporan yang tersimpan.
          </td>
        </tr>`;
      return;
    }
    tableReportsBody.innerHTML = reports.map(r => {
      const style = statusStyles[r.status] || 'bg-slate-100 text-slate-700';
      const safeUrl = String(r.target || r.url || '-').replace(/</g, '&lt;').replace(/>/g, '&gt;');
      const truncUrl = safeUrl.length > 50 ? safeUrl.substring(0, 50) + '...' : safeUrl;
      return `
        <tr class="border-b border-slate-100 hover:bg-slate-50/60 transition-colors">
          <td class="py-3 px-4 text-xs font-mono text-slate-500">${r.id || '-'}</td>
          <td class="py-3 px-4 text-xs font-mono text-slate-700 max-w-[180px]" title="${safeUrl}">${truncUrl}</td>
          <td class="py-3 px-4 text-xs text-slate-600">${r.category || '-'}</td>
          <td class="py-3 px-4 text-xs text-slate-500">${r.date || '-'}</td>
          <td class="py-3 px-4">
            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold ${style}">${r.status || 'Masuk'}</span>
          </td>
        </tr>`;
    }).join('');
  }

  // Search filter tabel
  if (tableSearchInput) {
    tableSearchInput.addEventListener('input', () => {
      const q = tableSearchInput.value.toLowerCase();
      const filtered = allReports.filter(r =>
        (r.target || r.url || '').toLowerCase().includes(q) ||
        (r.category || '').toLowerCase().includes(q) ||
        (r.status || '').toLowerCase().includes(q) ||
        (r.id || '').toLowerCase().includes(q)
      );
      renderTable(filtered);
    });
  }

  loadReportsTable();

  // ─── Form Submit ─────────────────────────────────────────────────────────────
  if (formLaporSiber) {
    formLaporSiber.addEventListener('submit', async (e) => {
      e.preventDefault();

      const nama       = document.getElementById('lapor-nama')?.value.trim() || 'Anonim';
      const kategori   = document.getElementById('lapor-kategori')?.value.trim() || '';
      const target     = document.getElementById('lapor-target')?.value.trim() || '';
      const deskripsi  = document.getElementById('lapor-deskripsi')?.value.trim() || '';

      // Validasi wajib (Kategori & URL Target)
      if (!kategori) {
        alert('Silakan pilih kategori laporan terlebih dahulu.');
        document.getElementById('lapor-kategori')?.focus();
        return;
      }
      if (!target) {
        alert('URL tautan yang dilaporkan wajib diisi.');
        document.getElementById('lapor-target')?.focus();
        return;
      }

      // Set loading state
      if (btnSubmitLapor) btnSubmitLapor.disabled = true;
      if (btnSubmitLaporText) btnSubmitLaporText.textContent = 'Mengirim Laporan...';

      const payload = { 
        nama, 
        kategori, 
        url: target, 
        deskripsi: deskripsi || 'Tidak ada catatan kronologi tambahan.' 
      };

      try {
        const service = window.ApiService;
        let result;
        if (service && typeof service.submitReport === 'function') {
          result = await service.submitReport(payload);
        } else {
          // Fallback: simulasi sukses
          result = {
            success: true,
            ticketId: 'RPT-' + String(Date.now()).slice(-6),
            message: 'Laporan berhasil diterima (mode simulasi frontend).'
          };
        }

        if (result && result.success !== false) {
          // Tampilkan modal sukses
          const ticketEl = document.getElementById('modal-ticket-id');
          if (ticketEl) ticketEl.textContent = result.ticketId || result.id || '-';
          if (modalSuksesLapor) {
            modalSuksesLapor.classList.remove('hidden');
            modalSuksesLapor.classList.add('flex');
          }
          // Reset form
          formLaporSiber.reset();
          // Reload tabel
          loadReportsTable();
        } else {
          throw new Error(result?.message || 'Pengiriman laporan gagal.');
        }
      } catch (err) {
        console.error('[lapor.js] Submit error:', err);
        alert('Gagal mengirim laporan: ' + (err.message || 'Coba lagi beberapa saat.'));
      } finally {
        if (btnSubmitLapor) btnSubmitLapor.disabled = false;
        if (btnSubmitLaporText) btnSubmitLaporText.textContent = 'Kirim Laporan Sekarang';
      }
    });
  }

  // ─── Tutup Modal Sukses ──────────────────────────────────────────────────────
  if (btnCloseDialog) {
    btnCloseDialog.addEventListener('click', () => {
      if (modalSuksesLapor) {
        modalSuksesLapor.classList.add('hidden');
        modalSuksesLapor.classList.remove('flex');
      }
    });
  }

  // Klik di luar modal = tutup
  if (modalSuksesLapor) {
    modalSuksesLapor.addEventListener('click', (e) => {
      if (e.target === modalSuksesLapor) {
        modalSuksesLapor.classList.add('hidden');
        modalSuksesLapor.classList.remove('flex');
      }
    });
  }

});
