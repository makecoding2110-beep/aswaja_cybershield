/**
 * lapor.js — Aswaja CyberShield
 * Handler form "Portal Lapor Siber"
 * Fokus: Tautan investasi menyesatkan yang mengarah ke judi online (judol/maysir)
 * Bergantung pada: js/api.js (window.ApiService) dan js/ui-kit.js (window.AswajaUI)
 *
 * Alur pop up:
 *  1. Validasi gagal  → pop up error yang menyebutkan field bermasalah
 *  2. Validasi lolos  → pop up konfirmasi "Kirim laporan ini?" + ringkasan data
 *  3. Terkirim        → pop up sukses berisi nomor tiket + "Lihat Status Laporan"
 *  4. Gagal kirim     → pop up error dengan tombol "Coba Lagi"
 */

document.addEventListener('DOMContentLoaded', () => {

  // ─── Elemen DOM ─────────────────────────────────────────────────────────────
  const uploadDropzone     = document.getElementById('upload-dropzone');
  const fileUpload         = document.getElementById('file-upload');
  const filePreview        = document.getElementById('file-preview');
  const fileName           = document.getElementById('file-name');
  const btnRemoveFile      = document.getElementById('btn-remove-file');
  const formLaporSiber     = document.getElementById('form-lapor-siber');
  const tableReportsBody   = document.getElementById('table-reports-body');
  const tableSearchInput   = document.getElementById('table-search-input');

  const UI = window.AswajaUI;

  if (!formLaporSiber && !tableReportsBody) return;

  // Aturan berkas bukti: JPG/PNG/PDF, maksimal 5 MB
  const FILE_RULES = {
    types: ['image/jpeg', 'image/png', 'application/pdf'],
    exts: ['jpg', 'jpeg', 'png', 'pdf'],
    maxBytes: 5 * 1024 * 1024,
    typeMessage: 'Format berkas tidak didukung. Gunakan JPG, PNG, atau PDF.',
    sizeMessage: 'Ukuran berkas terlalu besar. Maksimal 5 MB.'
  };

  let selectedFile = null;

  // ─── Prefill URL dari query param (?url=...) & sessionStorage cadangan ──────
  let pendingData = null;
  try {
    const rawStorage = sessionStorage.getItem('aswaja_pending_report');
    if (rawStorage) {
      pendingData = JSON.parse(rawStorage);
      sessionStorage.removeItem('aswaja_pending_report');
    }
  } catch (e) {}

  const params = new URLSearchParams(window.location.search);
  const rawParamUrl = params.get('url') || params.get('q') || (pendingData && pendingData.url) || '';

  if (rawParamUrl) {
    let cleanUrl = '';
    try {
      cleanUrl = decodeURIComponent(rawParamUrl).trim();
    } catch (e) {
      cleanUrl = String(rawParamUrl).trim();
    }

    // Validasi ketat: hanya skema http/https, maksimal 500 karakter
    const isValidHttpScheme = /^https?:\/\/[^\s/$.?#].[^\s]*$/i.test(cleanUrl) && cleanUrl.length <= 500;

    if (!isValidHttpScheme) {
      // Jika parameter tidak valid, abaikan dan tampilkan toast "Tautan tidak valid", form tetap kosong
      if (UI && typeof UI.toast === 'function') {
        UI.toast('Tautan tidak valid.', { type: 'error' });
      }
    } else {
      const inputTarget = document.getElementById('lapor-target');
      if (inputTarget) {
        // Tampilkan sebagai teks biasa murni (bukan dieksekusi atau tautan aktif)
        inputTarget.value = cleanUrl;
      }

      // Pra-pilih Kategori sesuai hasil analisis jika tersedia
      const selectKategori = document.getElementById('lapor-kategori');
      if (selectKategori) {
        const cat = (pendingData && pendingData.category) || params.get('category') || '';
        if (cat) {
          for (let opt of selectKategori.options) {
            if (opt.value && (opt.value.toLowerCase().includes(cat.toLowerCase()) || cat.toLowerCase().includes(opt.value.toLowerCase()))) {
              selectKategori.value = opt.value;
              break;
            }
          }
        }
        if (!selectKategori.value) {
          selectKategori.value = 'Investasi Berkedok Trading';
        }
      }

      // Isi Kronologi dengan ringkasan hasil analisis yang bisa diedit user
      const inputDesk = document.getElementById('lapor-deskripsi');
      if (inputDesk && !inputDesk.value && pendingData && pendingData.summary) {
        inputDesk.value = pendingData.summary;
      }

      // Scroll halus ke form dan fokuskan field berikutnya (Kategori)
      setTimeout(() => {
        if (UI && typeof UI.scrollTo === 'function') {
          UI.scrollTo('#form-lapor', { focusTarget: false, offset: 80 });
        } else {
          const formEl = document.getElementById('form-lapor') || document.getElementById('form-lapor-siber');
          if (formEl) formEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        const selectKategori = document.getElementById('lapor-kategori');
        if (selectKategori) selectKategori.focus();
      }, 180);
    }
  }

  // ─── Upload file ────────────────────────────────────────────────────────────
  function setSelectedFile(file) {
    selectedFile = file;
    if (fileName) fileName.textContent = file ? file.name : '';
    if (filePreview) filePreview.classList.toggle('hidden', !file);
    if (uploadDropzone) uploadDropzone.classList.toggle('hidden', !!file);
    if (!file && fileUpload) fileUpload.value = '';
  }

  function handleFileSelect(file) {
    const problem = UI ? UI.form.validateFile(file, FILE_RULES) : null;
    if (problem) {
      if (fileUpload) fileUpload.value = '';
      UI.toast(problem, { type: 'error', title: 'Berkas ditolak' });
      return;
    }
    setSelectedFile(file);
  }

  if (uploadDropzone && fileUpload) {
    uploadDropzone.addEventListener('click', () => fileUpload.click());
    uploadDropzone.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        fileUpload.click();
      }
    });
    uploadDropzone.addEventListener('dragover', (e) => {
      e.preventDefault();
      uploadDropzone.classList.add('is-dragover');
    });
    uploadDropzone.addEventListener('dragleave', () => uploadDropzone.classList.remove('is-dragover'));
    uploadDropzone.addEventListener('drop', (e) => {
      e.preventDefault();
      uploadDropzone.classList.remove('is-dragover');
      const files = e.dataTransfer && e.dataTransfer.files;
      if (files && files.length > 0) handleFileSelect(files[0]);
    });
    fileUpload.addEventListener('change', (e) => {
      if (e.target.files.length > 0) handleFileSelect(e.target.files[0]);
    });
  }

  if (btnRemoveFile) {
    btnRemoveFile.addEventListener('click', () => {
      setSelectedFile(null);
      if (uploadDropzone) uploadDropzone.focus();
    });
  }

  // ─── Tabel rekap laporan publik (data disamarkan) ───────────────────────────
  let allReports = [];

  function h(tag, cls, text) {
    const n = document.createElement(tag);
    if (cls) n.className = cls;
    if (text !== undefined) n.textContent = text;
    return n;
  }

  /** Samarkan objek laporan: hanya tampilkan nama domain, bukan URL lengkap. */
  function maskTarget(raw) {
    try {
      const u = new URL(/^https?:\/\//i.test(raw) ? raw : 'https://' + raw);
      return u.hostname;
    } catch (e) {
      return 'Tautan disamarkan';
    }
  }

  function statusClass(status) {
    if (status === 'Selesai') return 'ui-status ui-status--ok';
    if (status === 'Diproses' || status === 'Pending') return 'ui-status ui-status--warn';
    return 'ui-status';
  }

  async function loadReportsTable() {
    if (!tableReportsBody) return;
    try {
      const service = window.ApiService;
      allReports = (service && typeof service.getReports === 'function')
        ? await service.getReports()
        : getFallbackReports();
    } catch (err) {
      console.error('[lapor.js] Gagal memuat laporan:', err);
      allReports = getFallbackReports();
    }
    allReports = Array.from(allReports || []);
    renderTable(applySearch());
  }

  function getFallbackReports() {
    return [
      { id: 'RPT-001', url: 'https://cuan-trading-vip88.vip/deposit-slot', category: 'Investasi Berkedok Trading', status: 'Selesai', date: '01 Nov 2026' },
      { id: 'RPT-002', url: 'https://mining-usdt-spin.cc/wallet', category: 'Tautan Promosi Slot/Kasino Berkedok Game Finansial', status: 'Diproses', date: '02 Nov 2026' },
      { id: 'RPT-003', url: 'https://arisan-kilat-group.xyz/daftar', category: 'Tautan Undangan Grup Investasi/Arisan Bodong', status: 'Masuk', date: '03 Nov 2026' }
    ];
  }

  function renderTable(reports) {
    if (!tableReportsBody) return;
    tableReportsBody.textContent = '';

    if (!reports || reports.length === 0) {
      const tr = h('tr');
      const td = h('td', 'py-10 px-4 text-center text-sm ui-muted', 'Belum ada laporan yang cocok.');
      td.colSpan = 4;
      tr.appendChild(td);
      tableReportsBody.appendChild(tr);
      return;
    }

    reports.slice(0, 20).forEach((r) => {
      const tr = h('tr', 'ui-table-row');
      tr.dataset.ticket = r.id || '';

      tr.appendChild(h('td', 'py-3.5 px-4 font-mono font-semibold ui-heading', r.id ? '#' + String(r.id).replace(/^#/, '') : '-'));

      const tdObj = h('td', 'py-3.5 px-4');
      tdObj.appendChild(h('strong', 'font-semibold ui-heading block', r.category || '-'));
      tdObj.appendChild(h('span', 'text-xs ui-muted block truncate max-w-[240px]', maskTarget(r.target || r.url || '')));
      tr.appendChild(tdObj);

      const tdStatus = h('td', 'py-3.5 px-4');
      tdStatus.appendChild(h('span', statusClass(r.status), r.status || 'Masuk'));
      tr.appendChild(tdStatus);

      tr.appendChild(h('td', 'py-3.5 px-4 text-xs ui-muted', r.date || '-'));
      tableReportsBody.appendChild(tr);
    });
  }

  function applySearch() {
    const q = tableSearchInput ? tableSearchInput.value.trim().toLowerCase() : '';
    if (!q) return allReports;
    return allReports.filter((r) =>
      String(r.id || '').toLowerCase().includes(q) ||
      String(r.category || '').toLowerCase().includes(q) ||
      String(r.status || '').toLowerCase().includes(q) ||
      maskTarget(r.target || r.url || '').toLowerCase().includes(q)
    );
  }

  if (tableSearchInput) {
    tableSearchInput.addEventListener('input', () => renderTable(applySearch()));
  }

  loadReportsTable();

  /** Tampilkan tiket baru di tabel (server publik tidak mengembalikan daftar tanpa login). */
  function ensureTicketRow(ticketId, payload) {
    if (!allReports.some((r) => String(r.id) === String(ticketId))) {
      allReports.unshift({
        id: ticketId,
        url: payload.url,
        category: payload.kategori,
        status: 'Masuk',
        date: new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })
      });
    }
  }

  /** Aksi "Lihat Status Laporan": scroll ke tabel, filter ke tiket, sorot barisnya. */
  function viewTicketStatus(ticketId) {
    if (tableSearchInput) tableSearchInput.value = String(ticketId);
    renderTable(applySearch());
    const row = tableReportsBody && tableReportsBody.querySelector('tr[data-ticket="' + String(ticketId).replace(/"/g, '') + '"]');
    if (row) row.classList.add('is-highlight');
    UI.scrollTo('#rekap-laporan', { focusTarget: true });
  }

  // ─── Manajemen Batas Kuota Pelaporan (Maks. 2x per 2 Jam) ───────────────────
  const QuotaManager = {
    MAX_REPORTS: 2,
    WINDOW_MS: 2 * 60 * 60 * 1000, // 2 jam = 7.200.000 ms
    STORAGE_KEY: 'aswaja_user_reports',

    getSubmissions() {
      try {
        const raw = localStorage.getItem(this.STORAGE_KEY);
        if (!raw) return [];
        const list = JSON.parse(raw);
        if (!Array.isArray(list)) return [];
        const now = Date.now();
        // Hanya simpan waktu pengiriman dalam kurun 2 jam terakhir
        const valid = list.filter(ts => typeof ts === 'number' && (now - ts) < this.WINDOW_MS);
        if (valid.length !== list.length) {
          localStorage.setItem(this.STORAGE_KEY, JSON.stringify(valid));
        }
        return valid;
      } catch (e) {
        return [];
      }
    },

    recordSubmission() {
      try {
        const list = this.getSubmissions();
        list.push(Date.now());
        localStorage.setItem(this.STORAGE_KEY, JSON.stringify(list));
      } catch (e) {}
      this.updateUI();
    },

    forceExhausted(retryAfterMinutes) {
      try {
        const now = Date.now();
        const mins = retryAfterMinutes || 120;
        const fakeOldest = now - (this.WINDOW_MS - (mins * 60 * 1000));
        localStorage.setItem(this.STORAGE_KEY, JSON.stringify([fakeOldest, now]));
      } catch (e) {}
      this.updateUI();
    },

    getStatus() {
      const submissions = this.getSubmissions();
      const used = submissions.length;
      const remaining = Math.max(0, this.MAX_REPORTS - used);
      const oldest = submissions[0] || 0;
      const msLeft = oldest ? Math.max(0, (oldest + this.WINDOW_MS) - Date.now()) : 0;
      const minutesLeft = Math.max(1, Math.ceil(msLeft / 60000));
      const hoursLeft = Math.floor(minutesLeft / 60);
      const remMins = minutesLeft % 60;
      const timeString = hoursLeft > 0 ? `${hoursLeft} jam ${remMins} menit` : `${minutesLeft} menit`;

      return {
        used,
        remaining,
        isExhausted: remaining <= 0,
        minutesLeft,
        timeString
      };
    },

    canSubmit() {
      return this.getStatus().remaining > 0;
    },

    updateUI(serverData) {
      let status = this.getStatus();
      if (serverData && typeof serverData.remaining === 'number') {
        if (serverData.remaining < status.remaining) {
          status.remaining = serverData.remaining;
          status.used = serverData.used || (this.MAX_REPORTS - serverData.remaining);
          status.isExhausted = serverData.remaining <= 0;
          if (serverData.retry_in) status.timeString = serverData.retry_in;
        }
      }

      const container = document.getElementById('quota-container');
      const badge = document.getElementById('quota-badge');
      const warningBox = document.getElementById('quota-warning-box');
      const countdownText = document.getElementById('quota-countdown-text');
      const btnSubmit = document.getElementById('btn-submit-lapor');
      const btnSubmitText = document.getElementById('btn-submit-lapor-text');

      if (badge) {
        badge.textContent = `Sisa Kuota: ${status.remaining} / ${this.MAX_REPORTS}`;
        if (status.remaining === 2) {
          badge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-emerald-600 text-white shadow-xs tracking-wide';
        } else if (status.remaining === 1) {
          badge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-amber-500 text-white shadow-xs tracking-wide';
        } else {
          badge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-rose-600 text-white shadow-xs tracking-wide';
        }
      }

      if (container) {
        if (status.isExhausted) {
          container.className = 'rounded-2xl border p-4 transition-all duration-300 bg-rose-50/80 border-rose-200 text-rose-950 dark:bg-rose-950/25 dark:border-rose-900/60 dark:text-rose-200';
        } else if (status.remaining === 1) {
          container.className = 'rounded-2xl border p-4 transition-all duration-300 bg-amber-50/80 border-amber-200 text-amber-950 dark:bg-amber-950/25 dark:border-amber-900/60 dark:text-amber-200';
        } else {
          container.className = 'rounded-2xl border p-4 transition-all duration-300 bg-emerald-50/70 border-emerald-200/80 text-emerald-950 dark:bg-emerald-950/20 dark:border-emerald-800/60 dark:text-emerald-200';
        }
      }

      if (warningBox && countdownText) {
        if (status.isExhausted) {
          warningBox.classList.remove('hidden');
          countdownText.innerHTML = `Batas kuota pelaporan tercapai. Anda dapat mengirimkan laporan kembali dalam <strong>${status.timeString}</strong>.`;
        } else {
          warningBox.classList.add('hidden');
        }
      }

      if (btnSubmit) {
        if (status.isExhausted) {
          btnSubmit.disabled = true;
          btnSubmit.classList.add('opacity-60', 'cursor-not-allowed');
          if (btnSubmitText) btnSubmitText.textContent = `Batas Kuota Tercapai (Tunggu ${status.timeString})`;
        } else {
          btnSubmit.disabled = false;
          btnSubmit.classList.remove('opacity-60', 'cursor-not-allowed');
          if (btnSubmitText) btnSubmitText.textContent = 'Kirim Laporan Tabayyun';
        }
      }
    },

    showReasonsModal() {
      const modal = document.getElementById('modal-quota-reasons');
      if (modal) modal.classList.remove('hidden');
    },

    closeReasonsModal() {
      const modal = document.getElementById('modal-quota-reasons');
      if (modal) modal.classList.add('hidden');
    },

    showQuotaExceededAlert(timeString) {
      const st = this.getStatus();
      const waitStr = timeString || st.timeString || '2 jam';

      if (window.AswajaUI && typeof window.AswajaUI.notice === 'function') {
        window.AswajaUI.notice({
          role: 'alertdialog',
          icon: 'warning',
          variant: 'warning',
          title: 'Batas Kuota Pelaporan Tercapai',
          message: `Demi menjaga integritas penyelidikan dan mencegah spam server, setiap pengguna dibatasi maksimal 2 laporan setiap 2 jam. Anda dapat mengirimkan laporan baru kembali dalam ${waitStr}.`,
          buttonText: 'Saya Mengerti'
        });
      } else {
        this.showReasonsModal();
      }
    },

    async init() {
      this.updateUI();

      // Pasang Event Listeners Modal
      const btnShow = document.getElementById('btn-show-quota-reasons');
      const btnClose = document.getElementById('btn-close-quota-modal');
      const btnDismiss = document.getElementById('btn-dismiss-quota-modal');
      const modal = document.getElementById('modal-quota-reasons');

      if (btnShow) btnShow.addEventListener('click', () => this.showReasonsModal());
      if (btnClose) btnClose.addEventListener('click', () => this.closeReasonsModal());
      if (btnDismiss) btnDismiss.addEventListener('click', () => this.closeReasonsModal());
      if (modal) {
        modal.addEventListener('click', (e) => {
          if (e.target === modal) this.closeReasonsModal();
        });
      }

      // Sinkronkan sisa kuota dengan server backend jika tersedia
      try {
        if (window.ApiService && typeof window.ApiService.getReportQuota === 'function') {
          const quota = await window.ApiService.getReportQuota();
          if (quota && quota.success) {
            this.updateUI(quota);
          }
        }
      } catch (e) {}

      // Interval countdown pembaruan teks sisa waktu setiap 30 detik
      setInterval(() => this.updateUI(), 30000);
    }
  };

  // ─── Form Submit ─────────────────────────────────────────────────────────────
  function isHttpUrl(value) {
    try {
      const u = new URL(value);
      return (u.protocol === 'http:' || u.protocol === 'https:') && u.hostname.includes('.');
    } catch (e) {
      return false;
    }
  }

  function clearOnEdit(input) {
    if (!input) return;
    const clear = () => UI && UI.form.clearError(input);
    input.addEventListener('input', clear);
    input.addEventListener('change', clear);
  }
  ['lapor-kategori', 'lapor-target'].forEach((id) => clearOnEdit(document.getElementById(id)));

  if (formLaporSiber) {
    formLaporSiber.addEventListener('submit', async (e) => {
      e.preventDefault();

      // Cek Batasan Kuota Pelaporan (Maks. 2x per 2 Jam)
      if (!QuotaManager.canSubmit()) {
        const st = QuotaManager.getStatus();
        QuotaManager.showQuotaExceededAlert(st.timeString);
        return;
      }

      const inputNama   = document.getElementById('lapor-nama');
      const inputKat    = document.getElementById('lapor-kategori');
      const inputTarget = document.getElementById('lapor-target');
      const inputDesk   = document.getElementById('lapor-deskripsi');

      const nama      = inputNama.value.trim();
      const kategori  = inputKat.value.trim();
      const target    = inputTarget.value.trim();
      const deskripsi = inputDesk.value.trim();

      // 1) Validasi sisi klien (server memvalidasi ulang)
      const errors = [];
      if (!kategori) {
        errors.push({ input: inputKat, label: 'Kategori Insiden', message: 'Silakan pilih kategori laporan.' });
      }
      if (!target) {
        errors.push({ input: inputTarget, label: 'URL Tautan', message: 'URL tautan yang dilaporkan wajib diisi.' });
      } else if (!isHttpUrl(target)) {
        errors.push({ input: inputTarget, label: 'URL Tautan', message: 'Format URL tidak valid. Contoh: https://contoh-investasi.xyz/slot' });
      }
      if (selectedFile) {
        const fileProblem = UI.form.validateFile(selectedFile, FILE_RULES);
        if (fileProblem) errors.push({ input: uploadDropzone, label: 'Bukti', message: fileProblem });
      }
      if (errors.length) {
        UI.form.showErrors(formLaporSiber, errors);
        return;
      }
      UI.form.clearErrors(formLaporSiber);

      // 2) Konfirmasi → 3) kirim → sukses / 4) error + coba lagi
      const payload = { nama, kategori, url: target, deskripsi, evidence: selectedFile };
      let ticketId = '';

      const outcome = await UI.confirm({
        icon: 'info',
        title: 'Kirim laporan ini?',
        message: 'Pastikan data berikut sudah benar. Laporan tidak dapat diubah setelah dikirim.',
        summary: [
          { label: 'Pelapor', value: nama || 'Anonim (Hamba Allah)' },
          { label: 'Kategori', value: kategori },
          { label: 'URL', value: target },
          { label: 'Kronologi', value: deskripsi ? (deskripsi.length > 140 ? deskripsi.slice(0, 140) + '…' : deskripsi) : 'Tidak diisi' },
          { label: 'Bukti', value: selectedFile ? selectedFile.name : 'Tidak ada' }
        ],
        confirmText: 'Ya, Kirim Laporan',
        cancelText: 'Periksa Lagi',
        loadingTitle: 'Mengirim laporan…',
        loadingText: 'Laporan Anda sedang dikirim secara aman. Mohon tunggu.',
        errorTitle: 'Laporan gagal dikirim',
        onConfirm: async () => {
          const service = window.ApiService;
          if (!service || typeof service.submitReport !== 'function') {
            throw new Error('Layanan pengiriman laporan belum tersedia. Muat ulang halaman lalu coba lagi.');
          }
          try {
            const result = await service.submitReport(payload);
            if (!result || result.success === false) {
              throw new Error((result && (result.error || result.message)) || 'Pengiriman laporan gagal.');
            }
            ticketId = result.ticketId || result.id || (result.data && result.data.id) || '-';
            return result;
          } catch (err) {
            if (err.status === 429) {
              QuotaManager.forceExhausted(err.retry_after_minutes);
              QuotaManager.showQuotaExceededAlert(err.time_string);
            }
            throw err;
          }
        },
        success: () => {
          const cyberGuardUrl = window.location.pathname.includes('.blade.php') ? 'cyber-guard.blade.php' : '/cyber-guard';
          const ticketBox = document.createElement('div');
          ticketBox.className = 'ui-ticket';
          ticketBox.appendChild(h('span', 'ui-ticket__label', 'Nomor tiket Anda'));
          ticketBox.appendChild(h('span', 'ui-ticket__id', '#' + String(ticketId).replace(/^#/, '')));
          ticketBox.appendChild(h('span', 'ui-muted', 'Simpan nomor ini untuk memeriksa status laporan.'));
          ticketBox.lastChild.style.cssText = 'display:block;margin-top:.25rem;font-size:.6875rem';

          const ctaNote = document.createElement('p');
          ctaNote.className = 'text-xs text-slate-500 mt-3 pt-2 border-t border-slate-100 flex items-center gap-1.5';
          ctaNote.innerHTML = '<span class="material-symbols-outlined text-sm text-emerald-600">verified_user</span><span>Asah kewaspadaanmu lewat simulasi kasus siber.</span>';
          ticketBox.appendChild(ctaNote);

          return {
            title: 'Laporan berhasil terkirim',
            message: 'Jazakumullahu khairan atas peran aktif Anda menjaga ruang siber. Laporan masuk antrean verifikasi tim Tabayyun.',
            hideCloseButton: true,
            content: ticketBox,
            actions: [
              { id: 'close', label: 'Tutup', variant: 'outline' },
              {
                id: 'cyberguard',
                label: 'Uji Pemahamanmu di Cyber Guard',
                variant: 'primary',
                autofocus: true,
                onClick: () => {
                  window.location.href = cyberGuardUrl;
                }
              }
            ]
          };
        }
      });

      if (outcome.status === 'success') {
        QuotaManager.recordSubmission();
        formLaporSiber.reset();
        setSelectedFile(null);
        ensureTicketRow(ticketId, payload);
        await loadReportsTable().catch(() => {});
        ensureTicketRow(ticketId, payload);
        renderTable(applySearch());
        if (outcome.action === 'cyberguard') {
          const cyberGuardUrl = window.location.pathname.includes('.blade.php') ? 'cyber-guard.blade.php' : '/cyber-guard';
          window.location.href = cyberGuardUrl;
        } else if (outcome.action === 'view') {
          viewTicketStatus(ticketId);
        }
      }
    });
  }

  // Inisialisasi Manajemen Kuota Pelaporan
  QuotaManager.init();

});
