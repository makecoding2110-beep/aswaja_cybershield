/**
 * Aswaja CyberShield - API Client & Integration Layer for Laravel REST API */

// Konfigurasi Dasar URL API Laravel
// Menggunakan relative origin (/api) jika diakses lewat browser (misal port 3000 atau port apa pun), fallback ke http://127.0.0.1:8000/api
const API_BASE_URL = window.API_BASE_URL || (
  typeof window !== 'undefined' && window.location && window.location.origin && window.location.origin.startsWith('http')
    ? `${window.location.origin}/api`
    : 'http://127.0.0.1:8000/api'
);

// Kunci penyimpanan sesi
const AUTH_TOKEN_KEY = 'aswaja_admin_token';
const AUTH_USER_KEY = 'aswaja_admin_user';

/**
 * Pengelolaan Autentikasi & Sesi
 */
const AuthService = {
  getToken() {
    try {
      return sessionStorage.getItem(AUTH_TOKEN_KEY) || localStorage.getItem(AUTH_TOKEN_KEY);
    } catch {
      return null;
    }
  },
  setToken(token) {
    try {
      if (token) {
        sessionStorage.setItem(AUTH_TOKEN_KEY, token);
        localStorage.setItem(AUTH_TOKEN_KEY, token);
      } else {
        sessionStorage.removeItem(AUTH_TOKEN_KEY);
        localStorage.removeItem(AUTH_TOKEN_KEY);
      }
    } catch (e) {}
  },
  getUser() {
    try {
      const u = sessionStorage.getItem(AUTH_USER_KEY) || localStorage.getItem(AUTH_USER_KEY);
      return u ? JSON.parse(u) : null;
    } catch {
      return null;
    }
  },
  setUser(user) {
    try {
      if (user) {
        const serialized = JSON.stringify(user);
        sessionStorage.setItem(AUTH_USER_KEY, serialized);
        localStorage.setItem(AUTH_USER_KEY, serialized);
      } else {
        sessionStorage.removeItem(AUTH_USER_KEY);
        localStorage.removeItem(AUTH_USER_KEY);
      }
    } catch (e) {}
  },
  isAuthenticated() {
    return Boolean(this.getToken());
  },
  isSuperAdmin() {
    const user = this.getUser();
    return user && (user.role === 'Superadmin' || user.role === 'Super Admin');
  },
  logout() {
    try {
      sessionStorage.removeItem(AUTH_TOKEN_KEY);
      sessionStorage.removeItem(AUTH_USER_KEY);
      sessionStorage.removeItem('aswaja_admin_navigating');
      localStorage.removeItem(AUTH_TOKEN_KEY);
      localStorage.removeItem(AUTH_USER_KEY);
      localStorage.removeItem('aswaja_admin_last_activity');
    } catch (e) {}

    window.location.replace('/login');
  },
  confirmLogout() {
    if (window.AswajaUI && typeof window.AswajaUI.confirm === 'function') {
      window.AswajaUI.confirm({
        role: 'alertdialog',
        icon: 'danger',
        variant: 'danger',
        title: 'Konfirmasi Keluar Sesi',
        message: 'Apakah Anda yakin ingin keluar dari Panel Administrator Aswaja CyberShield? Sesi aktif Anda akan segera diakhiri demi keamanan data.',
        confirmText: 'Ya, Keluar Sekarang',
        cancelText: 'Tetap di Dashboard',
        onConfirm: () => {
          return new Promise((resolve) => {
            setTimeout(() => {
              AuthService.logout();
              resolve(true);
            }, 300);
          });
        }
      });
      return;
    }

    // Modern Modal Fallback (jika ui-kit belum siap)
    let modalEl = document.getElementById('aswaja-logout-modal');
    if (!modalEl) {
      modalEl = document.createElement('div');
      modalEl.id = 'aswaja-logout-modal';
      modalEl.style.cssText = 'position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;background:rgba(15,23,42,0.65);backdrop-filter:blur(4px);padding:1rem;opacity:0;transition:opacity .2s ease;';
      modalEl.innerHTML = `
        <div style="background:#fff;border-radius:1.25rem;max-width:26rem;width:100%;padding:1.5rem;box-shadow:0 25px 50px -12px rgba(0,0,0,0.35);text-align:center;border:1px solid #e2e8f0;transform:scale(0.95);transition:transform .2s ease;" id="aswaja-logout-card">
          <div style="width:3.25rem;height:3.25rem;border-radius:1rem;background:#fee2e2;color:#dc2626;display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;font-size:1.5rem;">
            <i class="bi bi-box-arrow-right"></i>
          </div>
          <h3 style="font-size:1.125rem;font-weight:700;color:#0f172a;margin:0 0 0.5rem 0;font-family:'Plus Jakarta Sans',sans-serif;">Konfirmasi Keluar Sesi</h3>
          <p style="font-size:0.875rem;color:#64748b;line-height:1.5;margin:0 0 1.25rem 0;">Apakah Anda yakin ingin keluar dari Panel Administrator? Seluruh sesi aktif akan segera ditutup demi keamanan data.</p>
          <div style="display:flex;gap:0.75rem;justify-content:center;">
            <button type="button" id="aswaja-btn-cancel-logout" style="flex:1;padding:0.625rem 1rem;border-radius:0.75rem;border:1px solid #cbd5e1;background:#f8fafc;color:#334155;font-weight:600;font-size:0.875rem;cursor:pointer;">Batal</button>
            <button type="button" id="aswaja-btn-exec-logout" style="flex:1;padding:0.625rem 1rem;border-radius:0.75rem;border:none;background:#dc2626;color:#ffffff;font-weight:600;font-size:0.875rem;cursor:pointer;">Keluar</button>
          </div>
        </div>
      `;
      document.body.appendChild(modalEl);
      
      const closeModal = () => {
        modalEl.style.opacity = '0';
        const c = document.getElementById('aswaja-logout-card');
        if (c) c.style.transform = 'scale(0.95)';
        setTimeout(() => modalEl.remove(), 200);
      };

      document.getElementById('aswaja-btn-cancel-logout')?.addEventListener('click', closeModal);
      modalEl.addEventListener('click', (e) => { if (e.target === modalEl) closeModal(); });
      document.getElementById('aswaja-btn-exec-logout')?.addEventListener('click', () => {
        const btn = document.getElementById('aswaja-btn-exec-logout');
        if (btn) {
          btn.disabled = true;
          btn.innerHTML = 'Memproses...';
        }
        setTimeout(() => AuthService.logout(), 250);
      });
    }

    requestAnimationFrame(() => {
      modalEl.style.opacity = '1';
      const c = document.getElementById('aswaja-logout-card');
      if (c) c.style.transform = 'scale(1)';
    });
  }
};

/**
 * HTTP Client Wrapper terpusat
 */
function buildHttpError(res, data, fallbackMessage) {
  const msg = (data && (data.message || data.error)) || fallbackMessage;
  const err = new Error(msg);
  err.status = res.status;
  err.errors = (data && data.errors) || null; // detail validasi per-field dari Laravel (422)
  err.quota = (data && data.quota) || null;
  err.reasons = (data && data.reasons) || null;
  err.retry_after_minutes = (data && data.retry_after_minutes) || null;
  err.time_string = (data && data.time_string) || null;
  return err;
}

async function apiFetch(endpoint, options = {}) {
  const url = endpoint.startsWith('http') ? endpoint : `${API_BASE_URL}${endpoint}`;
  const token = AuthService.getToken();

  const headers = {
    'Accept': 'application/json',
    // Penanda permintaan AJAX; autentikasi memakai Bearer token (bukan cookie)
    // sehingga permintaan lintas-situs tidak membawa kredensial otomatis (kebal CSRF).
    'X-Requested-With': 'XMLHttpRequest',
    ...(options.headers || {})
  };

  if (!(options.body instanceof FormData)) {
    headers['Content-Type'] = 'application/json';
  }

  if (token) {
    headers['Authorization'] = `Bearer ${token}`;
    headers['X-Admin-Token'] = token;
  }

  try {
    const res = await fetch(url, {
      ...options,
      headers
    });

    // Handle Unauthorized / Session Expired
    if (res.status === 401) {
      console.warn('[API] Sesi berakhir atau tidak diotorisasi (401).');
      if (window.location.pathname.startsWith('/admin') && !window.location.pathname.includes('/login')) {
        AuthService.logout();
      }
      const errorData = await res.json().catch(() => ({}));
      throw buildHttpError(res, errorData, 'Sesi telah berakhir, silakan login kembali.');
    }

    // Handle Forbidden (403)
    if (res.status === 403) {
      const errorData = await res.json().catch(() => ({}));
      throw buildHttpError(res, errorData, 'Anda tidak memiliki hak akses untuk tindakan ini.');
    }

    if (!res.ok) {
      const errorData = await res.json().catch(() => ({}));
      throw buildHttpError(res, errorData, `Permintaan gagal dengan status ${res.status}`);
    }

    return await res.json();
  } catch (error) {
    // Hanya kegagalan jaringan (server tidak terjangkau) yang boleh memicu data simulasi.
    // Error validasi/otorisasi dari server harus sampai ke pengguna apa adanya.
    error.isNetworkError = error instanceof TypeError || /Failed to fetch|NetworkError|Load failed/i.test(error.message || '');
    throw error;
  }
}

/**
 * Validasi Tautan URL Helper
 */
function isValidUrl(string) {
  try {
    const url = new URL(string.startsWith('http') ? string : `https://${string}`);
    return url.hostname.includes('.');
  } catch {
    return false;
  }
}

/**
 * Sanitasi tampilan URL agar aman ditampilkan (tidak langsung dieksekusi)
 */
function sanitizeUrlDisplay(rawUrl) {
  if (!rawUrl) return '';
  return rawUrl.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

// Data Dummy Terstruktur untuk Simulasi Frontend (Fokus Judol & Investasi Menyesatkan)
const MockDatabase = {
  // 1. Database Laporan Masuk
  reports: [
    {
      id: 'LPR081',
      url: 'https://cuan-trading-harian-vip88.vip/deposit-slot',
      date: '02 Nov 2026, 14:20 WITA',
      reporter: 'Hamba Allah',
      category: 'Investasi Berkedok Trading / Crypto',
      description: 'Grup Telegram mengklaim sinyal trading robot otomatis dengan profit 30% per hari, tetapi tautan pendaftarannya dialihkan ke deposit situs slot online luar negeri.',
      status: 'Diproses', // Masuk | Diproses | Selesai
      verificationNotes: 'Domain terdaftar 4 hari lalu di luar yurisdiksi RI. Server merujuk pada jaringan situs judi slot tanpa izin OJK/Bappebti.',
      handledBy: 'Ahmad Fauzi (Analisis Siber)'
    },
    {
      id: 'LPR080',
      url: 'https://dana-investasi-amanah-syariah.top/login-member',
      date: '02 Nov 2026, 11:15 WITA',
      reporter: 'Rizki Ramadhan',
      category: 'Iklan Cuan Kilat Bersyarat Deposit Judi',
      description: 'Mencatut label "Investasi Syariah Bebas Riba" di Instagram, tetapi saat registrasi member diwajibkan membeli kupon spin roda keberuntungan yang merupakan bentuk judi berkedok undian.',
      status: 'Selesai',
      verificationNotes: 'Terindikasi maysir manipulatif. Tautan telah diteruskan ke rekomendasi pemblokiran domain Komdigi/BSSN.',
      handledBy: 'Siti Nurhaliza (Tim Analis)'
    },
    {
      id: 'LPR079',
      url: 'https://mining-usdt-passiveincome.cc/wallet-spin',
      date: '01 Nov 2026, 19:40 WITA',
      reporter: 'Anonim',
      category: 'Tautan Promosi Slot / Kasino Berkedok Game Finansial',
      description: 'Menjanjikan passive income dari mining kripto. Setelah transfer deposit pertama, dana dikunci dan baru bisa ditarik jika menyelesaikan taruhan ganjil-genap tebak angka.',
      status: 'Selesai',
      verificationNotes: 'Skema penahanan dana (withdrawal lock) dengan pemaksaan taruhan judi online.',
      handledBy: 'Ahmad Fauzi (Analisis Siber)'
    },
    {
      id: 'LPR078',
      url: 'https://arisan-digital-berkah-nusantara.site/gabung',
      date: '01 Nov 2026, 09:10 WITA',
      reporter: 'Warga Samarinda',
      category: 'Tautan Undangan Grup Investasi / Arisan Bodong',
      description: 'Arisan berantai via WhatsApp. Uang kloter pertama dibagikan, selanjutnya peserta disuruh memasang nomor tebak skor bola pada link tersembunyi.',
      status: 'Masuk',
      verificationNotes: 'Dalam antrean verifikasi bukti tangkapan layar chat WhatsApp.',
      handledBy: 'Belum Ditugaskan'
    },
    {
      id: 'LPR077',
      url: 'https://portal-bantuan-modal-umkm2026.online/klaim-bonus',
      date: '31 Okt 2026, 16:30 WITA',
      reporter: 'Dewi Lestari',
      category: 'Iklan Cuan Kilat Bersyarat Deposit Judi',
      description: 'Mengaku program subsidi usaha mikro dengan modal awal cuma-cuma, namun syarat aktivasi harus melakukan deposit taruhan di situs kasino daring.',
      status: 'Selesai',
      verificationNotes: 'Diverifikasi sebagai tautan jebakan deposit judol. Modus rekayasa sosial menyasar pelaku usaha kecil.',
      handledBy: 'Super Admin'
    }
  ],

  // 2. Database Artikel Edukasi (Sinkron dengan public.articles)
  articles: [
    {
      id: 1,
      title: 'Anatomi Jebakan Robot Trading Bodong Berkedok Putaran Slot',
      category: 'Fiqih Finansial',
      author: 'Muhammad Ali Reza, S.Kom',
      date: '07 Nov 2026',
      readTime: '6 Menit',
      status: 'Published',
      slug: 'anatomi-jebakan-robot-trading-bodong',
      cover_image: '../images/artikel-1.jpg',
      image: '../images/artikel-1.jpg',
      coverImage: '../images/artikel-1.jpg',
      excerpt: 'Mengapa janji profit konsisten harian tanpa risiko adalah indikator mutlak skema Ponzi dan perjudian terselubung.',
      summary: 'Mengapa janji profit konsisten harian tanpa risiko adalah indikator mutlak skema Ponzi dan perjudian terselubung.',
      content: '<p>Praktik penipuan berkedok investasi kian bermutasi menggunakan istilah teknologi mutakhir seperti <em>AI Auto-Trading</em> dan <em>Smart Arbitrage</em>. Padahal, di balik antarmuka grafik palsu yang ditampilkan, dana masyarakat sebenarnya diarahkan ke mesin perjudian daring (slot) yang telah diatur algoritmanya agar bandar selalu menang.</p>'
    },
    {
      id: 2,
      title: 'Kajian Fiqih Muamalah: Batasan Gharar dan Maysir pada Game Berhadiah Finansial',
      category: 'Fiqih Siber',
      author: 'Dewan Syariah Aswaja',
      date: '07 Nov 2026',
      readTime: '8 Menit',
      status: 'Published',
      slug: 'kajian-fiqih-muamalah-gharar-maysir',
      excerpt: 'Membedah unsur ketidakpastian (gharar) dan spekulasi untung-untungan (maysir) pada aplikasi permainan top-up koin.',
      summary: 'Membedah unsur ketidakpastian (gharar) dan spekulasi untung-untungan (maysir) pada aplikasi permainan top-up koin.',
      content: '<p>Setiap permainan digital yang mensyaratkan pembelian koin dengan uang asli untuk memutar roda keberuntungan yang hasilnya dapat diuangkan kembali merupakan bentuk nyata dari <strong>Maysir</strong> (perjudian) yang diharamkan secara qath\'i dalam syariat Islam.</p>'
    },
    {
      id: 3,
      title: 'Mitigasi Phishing File APK Surat Undangan dan Kurir Paket',
      category: 'Keamanan Digital',
      author: 'Tim CSIRT Aswaja',
      date: '07 Nov 2026',
      readTime: '5 Menit',
      status: 'Published',
      slug: 'mitigasi-phishing-file-apk-kurir',
      excerpt: 'Langkah teknis mengamankan ponsel Android dari pencurian kode OTP perbankan melalui berkas instalasi berbahaya.',
      summary: 'Langkah teknis mengamankan ponsel Android dari pencurian kode OTP perbankan melalui berkas instalasi berbahaya.',
      content: '<p>Modus pengiriman file berekstensi <code>.apk</code> melalui WhatsApp memanfaatkan kelengahan psikologis korban (rekayasa sosial). Begitu diinstal, aplikasi berbahaya ini meminta izin akses baca SMS untuk meneruskan kode OTP mobile banking langsung ke server pelaku.</p>'
    }
  ],

  // 3. Database Studi Kasus & Soal Latihan (Sinkron dengan public.case_studies)
  caseStudies: [
    {
      id: 1,
      title: 'Jebakan Grup VIP "Titip Dana" & Robot Slot Otomatis',
      riskLevel: 'Tinggi',
      fiqihPrinciple: 'Tawaqquf & Tastabbus',
      date: '07 Nov 2026',
      scenario: 'Anda dimasukkan secara tiba-tiba ke dalam grup Telegram "Komunitas Cuan Berkah AI". Di dalamnya banyak akun mengirimkan bukti transfer pencairan uang jutaan rupiah hanya dalam waktu 2 jam.',
      sender: 'Admin VIP Arbitrase Syariah (+62 812-XXXX-9981)',
      context: 'Admin VIP Arbitrase Syariah (+62 812-XXXX-9981)',
      message: 'Assalamualaikum kak, slot titip dana batch ke-14 tinggal 2 kuota lagi! Modal Rp 500rb cair Rp 2.500.000 sore ini via algoritma AI Auto-Spin. Daftar di: https://vip-arbitrase-berkah88.site/join',
      question: 'Berdasarkan kaidah Fiqih Informasi (Prinsip 4T) dan literasi keamanan siber, langkah manakah yang paling tepat?',
      options: [
        { key: 'A', text: 'Mencoba transfer nominal terkecil terlebih dahulu untuk menguji kebenarannya.', correct: false },
        { key: 'B', text: 'Menahan diri (Tawaqquf), tidak mengeklik tautan karena klaim profit pasti dan kata kunci spin/slot adalah ciri mutlak judi online & penipuan.', correct: true },
        { key: 'C', text: 'Membagikan tautan tersebut ke grup keluarga agar ikut mendapat keuntungan.', correct: false },
        { key: 'D', text: 'Membalas pesan admin dengan mengirimkan foto KTP untuk verifikasi anggota.', correct: false }
      ],
      discussion: 'Dalam Fiqih Muamalah, janji keuntungan tetap tanpa risiko pada skema spekulatif adalah haram (mengandung unsur Gharar & Maysir/perjudian). Dari sisi keamanan siber, domain .site dengan iming-iming pelipatgandaan uang instan adalah modus investasi bodong berkedok slot.',
      explanation: 'Dalam Fiqih Muamalah, janji keuntungan tetap tanpa risiko pada skema spekulatif adalah haram (mengandung unsur Gharar & Maysir/perjudian). Dari sisi keamanan siber, domain .site dengan iming-iming pelipatgandaan uang instan adalah modus investasi bodong berkedok slot.'
    },
    {
      id: 2,
      title: 'Situs Pinjol Kilat Tanpa Verifikasi KTP Berujung Judi',
      riskLevel: 'Tinggi',
      fiqihPrinciple: 'Tatsabbut (Konfirmasi Otoritas)',
      date: '07 Nov 2026',
      scenario: 'Sebuah iklan di media sosial menawarkan pinjaman modal usaha tanpa syarat dokumen apa pun, namun saat diklik mengarahkan ke halaman registrasi akun taruhan.',
      sender: 'DanaCepat-SyariahOfficial',
      context: 'DanaCepat-SyariahOfficial',
      message: 'Butuh dana darurat mendesak? Cair Rp 10 Juta dalam 3 menit tanpa BI Checking & tanpa bunga! Cukup daftar akun member dan klaim saldo awal di: https://danacepat-cair88.xyz/klaim-bonus',
      question: 'Mengapa tautan di atas sangat berbahaya dan bagaimana cara melakukan Tatsabbut (konfirmasi otoritas)?',
      options: [
        { key: 'A', text: 'Aman dicoba selama tidak meminta PIN ATM.', correct: false },
        { key: 'B', text: 'Berbahaya karena mengarahkan pengguna membuat akun deposit judi online berkedok pencairan pinjaman; wajib dicek di daftar resmi OJK.', correct: true },
        { key: 'C', text: 'Langsung mendaftar menggunakan email utama dan nomor telepon aktif.', correct: false },
        { key: 'D', text: 'Mengunduh aplikasi yang disarankan di dalam halaman web tersebut.', correct: false }
      ],
      discussion: 'Banyak situs judi online menyamar sebagai layanan pinjaman kilat untuk menjebak warga yang sedang kesulitan ekonomi agar melakukan deposit awal ("biaya admin pencairan" yang ternyata top-up saldo taruhan). Selalu lakukan Tatsabbut melalui kanal resmi OJK (Kontak 157).',
      explanation: 'Banyak situs judi online menyamar sebagai layanan pinjaman kilat untuk menjebak warga yang sedang kesulitan ekonomi agar melakukan deposit awal ("biaya admin pencairan" yang ternyata top-up saldo taruhan). Selalu lakukan Tatsabbut melalui kanal resmi OJK (Kontak 157).'
    },
    {
      id: 3,
      title: 'Undangan Pernikahan Digital Berformat .APK di WhatsApp',
      riskLevel: 'Tinggi',
      fiqihPrinciple: "Ta'aqqul (Menimbang Mudharat)",
      date: '07 Nov 2026',
      scenario: 'Anda menerima pesan WhatsApp dari nomor tidak dikenal yang mengirimkan file bernama "Surat-Undangan-VIP.apk" tanpa menyebutkan nama mempelai.',
      sender: '+62 857-XXXX-3310 (Tidak Dikenal)',
      context: '+62 857-XXXX-3310 (Tidak Dikenal)',
      message: 'Kami mengharap kehadiran Bapak/Ibu pada acara resepsi pernikahan putra-putri kami. Silakan buka file undangan terlampir untuk melihat lokasi dan jadwal acara. [File: Surat-Undangan-VIP.apk - 6.4 MB]',
      question: 'Tindakan mitigasi apa yang wajib dilakukan saat menerima file dengan ekstensi .apk melalui pesan singkat?',
      options: [
        { key: 'A', text: 'Membuka dan menginstal file tersebut karena penasaran siapa yang menikah.', correct: false },
        { key: 'B', text: 'Menolak mengunduh/menginstal file .apk, segera menghapus pesan, dan memblokir pengirim karena file .apk adalah aplikasi berbahaya pencuri SMS OTP.', correct: true },
        { key: 'C', text: 'Meneruskan file .apk tersebut ke teman lain untuk menanyakan isinya.', correct: false },
        { key: 'D', text: 'Mengizinkan akses baca SMS ketika aplikasi meminta izin instalasi.', correct: false }
      ],
      discussion: 'Undangan digital resmi selalu berupa tautan web atau dokumen gambar/PDF, bukan file instalasi aplikasi (.apk). Menginstal .apk dari luar toko aplikasi resmi berisiko menyusupkan malware pencuri SMS OTP yang dapat menguras rekening mobile banking.',
      explanation: 'Undangan digital resmi selalu berupa tautan web atau dokumen gambar/PDF, bukan file instalasi aplikasi (.apk). Menginstal .apk dari luar toko aplikasi resmi berisiko menyusupkan malware pencuri SMS OTP yang dapat menguras rekening mobile banking.'
    },
    {
      id: 4,
      title: 'Giveaway Saldo DANA Kaget Palsu Mengarah ke Situs Phishing',
      riskLevel: 'Sedang',
      fiqihPrinciple: 'Tastabbus (Teliti Sanad Domain)',
      date: '07 Nov 2026',
      scenario: 'Beredar siaran langsung di media sosial yang menampilkan tangkapan layar bagi-bagi saldo dompet digital dengan syarat mengisi data akun di sebuah tautan.',
      sender: 'Berbagi Berkah Ramadhan Official',
      context: 'Berbagi Berkah Ramadhan Official',
      message: 'Alhamdulillah rezeki hari Jumat! Ambil saldo DANA Kaget Rp 750.000 gratis khusus 50 orang tercepat. Masukkan nomor HP dan PIN DANA Anda di: https://link-dana-kaget-berkah.cc/klaim',
      question: 'Indikator utama apa yang membuktikan bahwa tautan tersebut adalah jebakan Phishing?',
      options: [
        { key: 'A', text: 'Karena nominal hadiahnya kurang besar.', correct: false },
        { key: 'B', text: 'Domain bukan milik resmi dana.id (link.dana.id) dan meminta input PIN serta kode OTP secara langsung di halaman web luar aplikasi.', correct: true },
        { key: 'C', text: 'Karena dibagikan pada hari Jumat.', correct: false },
        { key: 'D', text: 'Tautan menggunakan protokol HTTPS sehingga pasti aman 100%.', correct: false }
      ],
      discussion: 'Fitur DANA Kaget yang asli hanya dapat diklaim langsung di dalam aplikasi resmi DANA melalui domain link.dana.id dan tidak pernah meminta pengguna mengetikkan PIN atau kode OTP pada situs web eksternal. Protokol HTTPS tidak menjamin sebuah situs bebas dari penipuan phishing.',
      explanation: 'Fitur DANA Kaget yang asli hanya dapat diklaim langsung di dalam aplikasi resmi DANA melalui domain link.dana.id dan tidak pernah meminta pengguna mengetikkan PIN atau kode OTP pada situs web eksternal. Protokol HTTPS tidak menjamin sebuah situs bebas dari penipuan phishing.'
    }
  ],

  // 4. Database Akun Admin
  admins: [
    {
      id: 1,
      name: 'Admin Utama',
      username: 'admin',
      email: 'aswajashield@gmail.com',
      role: 'Super Admin',
      status: 'Aktif',
      lastLogin: '07 Nov 2026, 20:51 WITA'
    },
    {
      id: 2,
      name: 'Muhammad Ali Reza',
      username: 'ali',
      email: 'alikarim2110.aswajacyber@gmail.com',
      role: 'Admin Basic',
      status: 'Aktif',
      lastLogin: '07 Nov 2026, 09:24 WITA'
    },
    {
      id: 3,
      name: 'Deby Cantika Putri',
      username: 'deby',
      email: 'debycantika.aswajashield@gmail.com',
      role: 'Admin Basic',
      status: 'Aktif',
      lastLogin: '07 Nov 2026, 09:25 WITA'
    }
  ],

  // 5. Riwayat Aktivitas Dashboard
  activities: [
    {
      user: 'Ahmad Fauzi',
      action: 'Memperbarui status laporan #LPR081 menjadi "Diproses"',
      time: '10 menit lalu',
      icon: 'arrow-repeat',
      badgeClass: 'text-warning bg-warning-subtle'
    },
    {
      user: 'Siti Nurhaliza',
      action: 'Menyelesaikan verifikasi bukti laporan #LPR080 (Judol berkedok Syariah)',
      time: '1 jam lalu',
      icon: 'check-circle',
      badgeClass: 'text-success bg-success-subtle'
    },
    {
      user: 'Super Admin',
      action: 'Menerbitkan artikel baru: "Muamalah Finansial: Bebas Scam Judi Online"',
      time: '3 jam lalu',
      icon: 'file-earmark-text',
      badgeClass: 'text-primary bg-primary-subtle'
    },
    {
      user: 'Warga Publik',
      action: 'Mengirimkan aduan baru mengenai tautan "arisan-digital-berkah-nusantara.site"',
      time: '5 jam lalu',
      icon: 'inbox',
      badgeClass: 'text-danger bg-danger-subtle'
    }
  ],

  // 6. Prinsip 4T Fiqih Siber (Sinkron dengan public.prinsip_4t)
  prinsip4T: [
    {
      id: 'T1',
      code: 'T1',
      name: 'Tawaqquf',
      title: 'Tawaqquf',
      arabic: 'Tahan Diri & Jeda Reflektif',
      subtitle: 'Tahan Diri & Jeda Reflektif',
      description: 'Menahan jempol saat menerima tautan sensasional berbau cuan instan. Menghentikan penyebaran sebelum fakta teruji.',
      link: 'artikel/prinsip-tawaqquf.blade.php',
      icon: 'pause_circle'
    },
    {
      id: 'T2',
      code: 'T2',
      name: 'Tastabbus',
      title: 'Tastabbus',
      arabic: 'Telusuri Sanad & Fakta Digital',
      subtitle: 'Telusuri Sanad & Fakta Digital',
      description: 'Melacak jejak digital domain, memverifikasi perizinan regulator OJK/Bappebti, dan membongkar kedok samaran tautan.',
      link: 'artikel/prinsip-tastabbus.blade.php',
      icon: 'search'
    },
    {
      id: 'T3',
      code: 'T3',
      name: 'Tatsabbut',
      title: 'Tatsabbut',
      arabic: 'Konfirmasi Otoritas & Fatwa',
      subtitle: 'Konfirmasi Otoritas & Fatwa',
      description: 'Menguji kesesuaian muamalah dengan fatwa alim ulama dan hukum fiqih untuk memastikan tiadanya unsur judi (maysir).',
      link: 'artikel/prinsip-tatsabbut.blade.php',
      icon: 'verified_user'
    },
    {
      id: 'T4',
      code: 'T4',
      name: "Ta'aqqul",
      title: "Ta'aqqul",
      arabic: 'Pertimbangan Maslahat & Mudharat',
      subtitle: 'Pertimbangan Maslahat & Mudharat',
      description: 'Menimbang apakah konten valid tersebut jika disebarkan membawa kemaslahatan atau malah memicu kegaduhan.',
      link: 'artikel/prinsip-taaqqul.blade.php',
      icon: 'psychology'
    }
  ]
};

// Inisialisasi data localStorage jika belum ada
function initLocalMockData() {
  const storedReports = localStorage.getItem('aswaja_mock_reports');
  if (!storedReports) {
    localStorage.setItem('aswaja_mock_reports', JSON.stringify(MockDatabase.reports));
  } else {
    try {
      const parsed = JSON.parse(storedReports);
      let changed = false;
      parsed.forEach(r => {
        if (r.id && r.id.startsWith('LPR-2026-')) {
          r.id = r.id.replace('LPR-2026-', 'LPR');
          changed = true;
        }
      });
      if (changed) {
        localStorage.setItem('aswaja_mock_reports', JSON.stringify(parsed));
      }
    } catch (e) {}
  }
  // Selalu pastikan cache mock articles terisi artikel aktif
  const storedArticles = localStorage.getItem('aswaja_mock_articles');
  if (!storedArticles) {
    localStorage.setItem('aswaja_mock_articles', JSON.stringify(MockDatabase.articles));
  } else {
    try {
      const parsedArt = JSON.parse(storedArticles);
      const art1 = parsedArt.find(a => Number(a.id) === 1);
      if (art1 && (!art1.image && !art1.cover_image && !art1.coverImage)) {
        art1.cover_image = '../images/artikel-1.jpg';
        art1.image = '../images/artikel-1.jpg';
        art1.coverImage = '../images/artikel-1.jpg';
        localStorage.setItem('aswaja_mock_articles', JSON.stringify(parsedArt));
      }
    } catch (e) {}
  }
  if (!localStorage.getItem('aswaja_mock_cases')) {
    localStorage.setItem('aswaja_mock_cases', JSON.stringify(MockDatabase.caseStudies));
  }
  if (!localStorage.getItem('aswaja_mock_admins')) {
    localStorage.setItem('aswaja_mock_admins', JSON.stringify(MockDatabase.admins));
  }
  if (!localStorage.getItem('aswaja_mock_prinsip4t')) {
    localStorage.setItem('aswaja_mock_prinsip4t', JSON.stringify(MockDatabase.prinsip4T));
  }
}
initLocalMockData();

function getStoredMock(key) {
  try {
    return JSON.parse(localStorage.getItem(key)) || [];
  } catch {
    return [];
  }
}

function setStoredMock(key, data) {
  localStorage.setItem(key, JSON.stringify(data));
}

/**
 * Soft delete untuk mode simulasi: data dipindah ke "tong sampah" dengan
 * deleted_at + deleted_by, lalu dicatat di log aktivitas admin (siapa, kapan, data apa).
 */
function mockSoftDelete(type, items, labelFn) {
  const actor = (AuthService.getUser() || {}).username || (AuthService.getUser() || {}).name || 'admin';
  const now = new Date().toISOString();
  const trash = getStoredMock('aswaja_mock_trash');
  const audit = getStoredMock('aswaja_mock_audit');
  items.forEach((item) => {
    trash.push({ type, snapshot: item, deleted_at: now, deleted_by: actor });
    audit.unshift({ action: 'delete', type, target: labelFn ? labelFn(item) : String(item.id), by: actor, at: now });
  });
  setStoredMock('aswaja_mock_trash', trash);
  setStoredMock('aswaja_mock_audit', audit.slice(0, 500));
}

/**
 * Service API Endpoints yang terisolasi dan siap terhubung ke Laravel
 */
const ApiService = {
  // 1. Analisis Tautan (Recognize)
  async analyzeUrl(targetUrl) {
    if (!isValidUrl(targetUrl)) {
      throw new Error('Format URL tidak valid. Masukkan alamat web yang lengkap, misalnya https://contoh-investasi.com');
    }

    try {
      // Panggil backend Laravel jika tersedia dengan timeout aman (maksimal 45 detik untuk model LLM)
      const controller = typeof AbortController !== 'undefined' ? new AbortController() : null;
      const timeoutId = controller ? setTimeout(() => controller.abort(), 45000) : null;

      try {
        const res = await apiFetch('/analyze-link', {
          method: 'POST',
          signal: controller ? controller.signal : undefined,
          body: JSON.stringify({ url: targetUrl })
        });
        if (timeoutId) clearTimeout(timeoutId);
        return res;
      } catch (fetchErr) {
        if (timeoutId) clearTimeout(timeoutId);
        throw fetchErr;
      }
    } catch (err) {
      // Fallback simulasi cerdas berbasis deteksi heuristik judol & investasi mencurigakan
      console.info('[Simulation Mode] Analisis link menggunakan mesin heuristik frontend.', err);
      const lower = targetUrl.toLowerCase();

      const judolKeywords = ['slot', 'gacor', 'spin', 'bet', 'judi', 'taruhan', 'casino', 'kasino', 'maxwin', 'deposit', 'vip88', 'hoki', 'toto', 'togel', 'parlay', 'hokibet'];
      const investKeywords = ['trading', 'cuan', 'mining', 'invest', 'profit', 'syariah', 'dana-kaget', 'hibah', 'arisan', 'passiveincome'];
      const cloakedPatterns = ['northridgeautowrecking.com', 'globalhearthospital.com', 'cancercarecoaching.com', 'shopmoisturetesters.com', 'aaucauniversity.com', 'hibiscus-turkey-yzas.squarespace.com', 'hkapekkk.mssg.me'];

      // Pembelajaran dari basis data laporan lokal (localStorage / MockDatabase)
      const storedReports = getStoredMock('aswaja_mock_reports');
      let targetHost = '';
      try {
        targetHost = new URL(targetUrl.startsWith('http') ? targetUrl : `https://${targetUrl}`).hostname.replace(/^www\./i, '').toLowerCase();
      } catch (e) {}

      const matchedReport = storedReports.find(r => {
        if (!r || !r.url || String(r.status || '').toLowerCase() === 'ditolak') return false;
        const rLower = String(r.url).toLowerCase();
        if (rLower === lower) return true;
        try {
          const rHost = new URL(rLower.startsWith('http') ? rLower : `https://${rLower}`).hostname.replace(/^www\./i, '').toLowerCase();
          return targetHost && rHost === targetHost;
        } catch (e) {
          return false;
        }
      });

      const hasJudol = judolKeywords.some(k => lower.includes(k)) || cloakedPatterns.some(p => lower.includes(p)) || Boolean(matchedReport);
      const hasInvest = investKeywords.some(k => lower.includes(k));

      let resultCategory = 'Belum Ditemukan Indikasi';
      let finalCategory = 'normal';
      let score = 98;
      let safetyScore = 98;
      let riskScore = 2;
      let statusAlert = 'Aman';
      let alertClass = 'emerald';
      let title = 'Bersih: Tidak Ditemukan Pola Signature Berbahaya';
      let findings = [
        { type: 'success', label: 'Audit Domain', desc: 'Domain tidak terdaftar dalam basis data pemblokiran judol atau kloning finansial yang diketahui.' },
        { type: 'success', label: 'Analisis Muatan', desc: 'Tidak ditemukan skema setoran taruhan, roda putar acak, atau janji keuntungan instan berkedok judi.' },
        { type: 'info', label: 'Saran Tabayyun', desc: 'Tetap waspada. Pastikan entitas finansial telah berizin dan terdaftar resmi di OJK atau Bappebti sebelum melakukan transaksi.' }
      ];

      if (hasJudol || matchedReport || cloakedPatterns.some(p => lower.includes(p))) {
        resultCategory = 'Terindikasi';
        finalCategory = 'terindikasi_judol';
        score = 85;
        riskScore = 85;
        safetyScore = 15;
        statusAlert = 'Waspada: Terindikasi Judi Online';
        alertClass = 'red';
        title = 'Waspada: Terindikasi Judi Online';
        findings = [
          matchedReport
            ? { type: 'error', label: 'Pembelajaran Basis Data Aduan', desc: `Domain ini telah tercatat dalam basis data laporan masyarakat Aswaja CyberShield (Tiket #${matchedReport.id}: ${matchedReport.category}).` }
            : { type: 'error', label: 'Inspeksi Konten & Penyusupan', desc: 'Meskipun nama domain tampak normal, halaman ini menyembunyikan antarmuka situs judi online aktif (Slot Gacor / Togel / Taruhan).' },
          { type: 'error', label: 'Pemblokiran & Deteksi Maysir', desc: 'Terdeteksi pola promosi taruhan uang asli dan penyusupan halaman (Web Defacement / Cloaking) yang melanggar hukum.' },
          { type: 'warning', label: 'Rekomendasi Tindakan', desc: 'Jangan lakukan registrasi atau transfer dana. Laporkan tautan ini ke portal aduan Aswaja CyberShield untuk ditindaklanjuti.' }
        ];
      } else if (hasInvest) {
        resultCategory = 'Mencurigakan';
        finalCategory = 'mencurigakan';
        score = 75;
        riskScore = 75;
        safetyScore = 25;
        statusAlert = 'Perlu Diwaspadai';
        alertClass = 'orange';
        title = 'Perlu Diwaspadai: Indikasi Skema Finansial Berisiko Tinggi Tanpa Izin';
        findings = [
          { label: 'Legalitas Belum Terverifikasi', desc: 'Tautan tidak mencantumkan legalitas perizinan OJK/Bappebti yang valid dan menggunakan domain tingkat atas tidak resmi.' },
          { label: 'Potensi Maysir & Gharar', desc: 'Terdapat indikasi spekulasi tinggi atau pengalihan deposit ke pihak ketiga yang tidak dapat dipertanggungjawabkan.' },
          { label: 'Rekomendasi Tindakan', desc: 'Terapkan prinsip Tawaqquf (tahan diri). Teliti keabsahan sanad pengelola sebelum menyetorkan dana apa pun.' }
        ];
      }

      return {
        isSimulation: true,
        url: targetUrl,
        category: resultCategory,
        final_category: finalCategory,
        score: score,
        risk_score: riskScore,
        safety_score: safetyScore,
        statusAlert: statusAlert,
        alertClass: alertClass,
        title: title,
        findings: findings,
        scannedAt: new Date().toLocaleTimeString('id-ID', { timeZone: 'Asia/Makassar' }) + ' WITA'
      };
    }
  },

  // 2. Laporan Pengaduan (Act)
  async submitReport(data) {
    // Menerima field berbahasa Indonesia (form Lapor) maupun Inggris (API).
    const payload = {
      url: String(data.url || '').trim(),
      reporter: String(data.reporter || data.nama || '').trim() || 'Hamba Allah',
      category: String(data.category || data.kategori || '').trim(),
      description: String(data.description || data.deskripsi || '').trim()
    };
    const evidence = (typeof File !== 'undefined' && data.evidence instanceof File) ? data.evidence : null;

    if (!payload.url || !isValidUrl(payload.url)) {
      throw new Error('Harap masukkan URL tautan yang dilaporkan dengan format yang benar.');
    }
    if (!payload.category) {
      throw new Error('Kategori insiden wajib dipilih.');
    }

    try {
      let body;
      if (evidence) {
        body = new FormData();
        Object.keys(payload).forEach((k) => body.append(k, payload[k]));
        if (data.status) body.append('status', data.status);
        if (data.source) body.append('source', data.source);
        body.append('evidence', evidence);
      } else {
        body = JSON.stringify({ ...payload, status: data.status, source: data.source });
      }
      return await apiFetch('/reports', { method: 'POST', body });
    } catch (err) {
      // Error validasi/otorisasi dari server ditampilkan apa adanya (jangan disamarkan sebagai sukses)
      if (!err.isNetworkError) throw err;

      // Server tidak terjangkau → simpan ke local mock data (mode simulasi)
      const reports = getStoredMock('aswaja_mock_reports');
      const nextNum = reports.reduce((max, r) => {
        const n = parseInt(String(r.id || '').replace(/[^0-9]/g, ''), 10);
        return !isNaN(n) && n > max ? n : max;
      }, 81) + 1;
      const newId = `LPR${String(nextNum).padStart(3, '0')}`;
      const initialStatus = data.status || 'Pending';
      const currentUser = AuthService.getUser();
      const newReport = {
        id: newId,
        url: payload.url,
        date: new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + ', ' + new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Makassar' }) + ' WITA',
        reporter: payload.reporter,
        category: payload.category,
        description: payload.description,
        evidenceName: evidence ? evidence.name : (data.evidenceName || null),
        status: initialStatus,
        source: data.source || (AuthService.isAuthenticated() ? 'Manual Admin' : 'Publik'),
        verificationNotes: data.verificationNotes || (data.source === 'Manual Admin' || AuthService.isAuthenticated() ? 'Aduan dicatat langsung oleh administrator Aswaja CyberShield.' : 'Laporan baru diterima dari portal publik. Menunggu verifikasi awal.'),
        handledBy: data.handledBy || (currentUser?.name || 'Admin Utama')
      };
      reports.unshift(newReport);
      setStoredMock('aswaja_mock_reports', reports);

      return {
        isSimulation: true,
        success: true,
        ticketId: newId,
        id: newId,
        data: newReport,
        message: 'Laporan Anda telah berhasil dicatat dan masuk antrean verifikasi analisis siber Aswaja.'
      };
    }
  },

  async createAdminReport(data) {
    const payload = {
      url: String(data.url || '').trim(),
      reporter: String(data.reporter || data.nama || '').trim() || 'Hamba Allah',
      category: String(data.category || data.kategori || '').trim(),
      description: String(data.description || data.deskripsi || '').trim(),
      status: data.status || 'Pending',
      source: 'Manual Admin',
      handledBy: data.handledBy || (AuthService.getUser()?.name || 'Admin Utama'),
      evidence: data.evidence || null
    };
    return await this.submitReport(payload);
  },

  async getReportQuota() {
    try {
      return await apiFetch('/reports/quota');
    } catch {
      return { success: true, max: 2, used: 0, remaining: 2, retry_after_minutes: 0 };
    }
  },

  async getPendingReportsCount() {
    try {
      const reports = await this.getReports();
      const list = Array.isArray(reports) ? reports : (reports?.data || []);
      return list.filter(r => r.status === 'Pending' || r.status === 'Masuk').length;
    } catch {
      return 0;
    }
  },

  async updateSidebarReportBadges() {
    try {
      const count = await this.getPendingReportsCount();
      document.querySelectorAll('#badge-sidebar-reports, .badge-sidebar-reports').forEach(el => {
        el.textContent = count;
      });
      return count;
    } catch (e) {
      console.warn('Gagal memperbarui badge sidebar:', e);
      return 0;
    }
  },

  async getReports() {
    try {
      const res = await apiFetch('/reports');
      const list = Array.isArray(res) ? res : (res?.data || []);
      if (list.length > 0) setStoredMock('aswaja_mock_reports', list);
      list.data = list;
      return list;
    } catch {
      const mock = getStoredMock('aswaja_mock_reports');
      mock.data = mock;
      mock.isSimulation = true;
      return mock;
    }
  },

  async updateReportStatus(id, updateData, maybeNotes, maybeAdmin) {
    let payload = updateData;
    if (typeof updateData === 'string') {
      payload = {
        status: updateData,
        verificationNotes: maybeNotes || '',
        verification_note: maybeNotes || '',
        handledBy: maybeAdmin || 'Admin',
        assigned_admin: maybeAdmin || 'Admin'
      };
    }
    try {
      return await apiFetch(`/reports/${id}`, {
        method: 'PUT',
        body: JSON.stringify(payload)
      });
    } catch (err) {
      if (!err.isNetworkError) throw err;
      const reports = getStoredMock('aswaja_mock_reports');
      const idx = reports.findIndex(r => String(r.id) === String(id));
      if (idx !== -1) {
        reports[idx] = { ...reports[idx], ...payload };
        setStoredMock('aswaja_mock_reports', reports);
        return { isSimulation: true, success: true, data: reports[idx] };
      }
      throw new Error('Laporan tidak ditemukan.');
    }
  },

  async deleteReport(id) {
    if (!AuthService.isSuperAdmin()) {
      throw new Error('Hanya Super Admin yang berwenang menghapus data laporan.');
    }
    try {
      return await apiFetch(`/reports/${id}`, { method: 'DELETE' });
    } catch (err) {
      if (!err.isNetworkError) throw err;
      const reports = getStoredMock('aswaja_mock_reports');
      const removed = reports.filter(r => String(r.id) === String(id));
      mockSoftDelete('laporan', removed, r => `#${r.id}`);
      setStoredMock('aswaja_mock_reports', reports.filter(r => String(r.id) !== String(id)));
      return { isSimulation: true, success: true };
    }
  },

  async deleteReportsBulk(ids) {
    if (!AuthService.isSuperAdmin()) {
      throw new Error('Hanya Super Admin yang berwenang menghapus data laporan.');
    }
    try {
      return await apiFetch('/reports/bulk-delete', { method: 'POST', body: JSON.stringify({ ids }) });
    } catch (err) {
      if (!err.isNetworkError) throw err;
      const set = new Set(ids.map(String));
      const reports = getStoredMock('aswaja_mock_reports');
      mockSoftDelete('laporan', reports.filter(r => set.has(String(r.id))), r => `#${r.id}`);
      setStoredMock('aswaja_mock_reports', reports.filter(r => !set.has(String(r.id))));
      return { isSimulation: true, success: true, deleted: ids.length };
    }
  },

  // 3. Modul Artikel Edukasi
  async getArticles() {
    try {
      const res = await apiFetch('/articles');
      const list = Array.isArray(res) ? res : (res?.data || []);
      if (list.length > 0) setStoredMock('aswaja_mock_articles', list);
      list.data = list;
      return list;
    } catch {
      const mock = getStoredMock('aswaja_mock_articles');
      mock.data = mock;
      mock.isSimulation = true;
      return mock;
    }
  },

  async createArticle(data) {
    try {
      return await apiFetch('/articles', {
        method: 'POST',
        body: JSON.stringify(data)
      });
    } catch (err) {
      if (!err.isNetworkError) throw err;
      const articles = getStoredMock('aswaja_mock_articles');
      const newArticle = {
        id: Date.now(),
        ...data,
        date: new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })
      };
      articles.unshift(newArticle);
      setStoredMock('aswaja_mock_articles', articles);
      return { isSimulation: true, success: true, data: newArticle };
    }
  },

  async updateArticle(id, data) {
    try {
      return await apiFetch(`/articles/${id}`, {
        method: 'PUT',
        body: JSON.stringify(data)
      });
    } catch (err) {
      if (!err.isNetworkError) throw err;
      const articles = getStoredMock('aswaja_mock_articles');
      const idx = articles.findIndex(a => Number(a.id) === Number(id));
      if (idx !== -1) {
        articles[idx] = { ...articles[idx], ...data };
        setStoredMock('aswaja_mock_articles', articles);
        return { isSimulation: true, success: true, data: articles[idx] };
      }
      throw new Error('Artikel tidak ditemukan.');
    }
  },

  async deleteArticle(id) {
    if (!AuthService.isSuperAdmin()) {
      throw new Error('Hanya Super Admin yang berwenang menghapus artikel.');
    }
    try {
      return await apiFetch(`/articles/${id}`, { method: 'DELETE' });
    } catch (err) {
      if (!err.isNetworkError) throw err;
      const articles = getStoredMock('aswaja_mock_articles');
      mockSoftDelete('artikel', articles.filter(a => Number(a.id) === Number(id)), a => a.title);
      setStoredMock('aswaja_mock_articles', articles.filter(a => Number(a.id) !== Number(id)));
      return { isSimulation: true, success: true };
    }
  },

  async deleteArticlesBulk(ids) {
    if (!AuthService.isSuperAdmin()) {
      throw new Error('Hanya Super Admin yang berwenang menghapus artikel.');
    }
    try {
      return await apiFetch('/articles/bulk-delete', { method: 'POST', body: JSON.stringify({ ids }) });
    } catch (err) {
      if (!err.isNetworkError) throw err;
      const set = new Set(ids.map(Number));
      const articles = getStoredMock('aswaja_mock_articles');
      mockSoftDelete('artikel', articles.filter(a => set.has(Number(a.id))), a => a.title);
      setStoredMock('aswaja_mock_articles', articles.filter(a => !set.has(Number(a.id))));
      return { isSimulation: true, success: true, deleted: ids.length };
    }
  },

  // 4. Studi Kasus & Soal Latihan (Cyber Guard)
  async getCaseStudies() {
    try {
      const res = await apiFetch('/case-studies');
      const list = Array.isArray(res) ? res : (res?.data || []);
      if (list.length > 0) setStoredMock('aswaja_mock_cases', list);
      list.data = list;
      return list;
    } catch {
      const mock = getStoredMock('aswaja_mock_cases');
      mock.data = mock;
      mock.isSimulation = true;
      return mock;
    }
  },

  async createCaseStudy(data) {
    try {
      return await apiFetch('/case-studies', {
        method: 'POST',
        body: JSON.stringify(data)
      });
    } catch (err) {
      if (!err.isNetworkError) throw err;
      const cases = getStoredMock('aswaja_mock_cases');
      const newCase = {
        id: Date.now(),
        date: new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }),
        riskLevel: data.riskLevel || data.risk_level || 'Sedang',
        ...data
      };
      cases.unshift(newCase);
      setStoredMock('aswaja_mock_cases', cases);
      return { isSimulation: true, success: true, data: newCase };
    }
  },

  async updateCaseStudy(id, data) {
    try {
      return await apiFetch(`/case-studies/${id}`, {
        method: 'PUT',
        body: JSON.stringify(data)
      });
    } catch (err) {
      if (!err.isNetworkError) throw err;
      const cases = getStoredMock('aswaja_mock_cases');
      const idx = cases.findIndex(c => Number(c.id) === Number(id));
      if (idx !== -1) {
        cases[idx] = { ...cases[idx], ...data };
        setStoredMock('aswaja_mock_cases', cases);
        return { isSimulation: true, success: true, data: cases[idx] };
      }
      throw new Error('Studi kasus tidak ditemukan.');
    }
  },

  async deleteCaseStudy(id) {
    if (!AuthService.isSuperAdmin()) {
      throw new Error('Hanya Super Admin yang berwenang menghapus studi kasus.');
    }
    try {
      return await apiFetch(`/case-studies/${id}`, { method: 'DELETE' });
    } catch (err) {
      if (!err.isNetworkError) throw err;
      const cases = getStoredMock('aswaja_mock_cases');
      mockSoftDelete('studi kasus', cases.filter(c => Number(c.id) === Number(id)), c => c.title || String(c.id));
      setStoredMock('aswaja_mock_cases', cases.filter(c => Number(c.id) !== Number(id)));
      return { isSimulation: true, success: true };
    }
  },

  // 5. Akun Admin
  async getAdmins() {
    try {
      const res = await apiFetch('/admins');
      const list = Array.isArray(res) ? res : (res?.data || []);
      if (list.length > 0) setStoredMock('aswaja_mock_admins', list);
      list.data = list;
      return list;
    } catch {
      const mock = getStoredMock('aswaja_mock_admins');
      mock.data = mock;
      mock.isSimulation = true;
      return mock;
    }
  },

  async createAdmin(data) {
    if (!AuthService.isSuperAdmin()) {
      throw new Error('Hanya Super Admin yang berwenang menambah akun administrator.');
    }
    try {
      return await apiFetch('/admins', {
        method: 'POST',
        body: JSON.stringify(data)
      });
    } catch (err) {
      if (!err.isNetworkError) throw err;
      const admins = getStoredMock('aswaja_mock_admins');
      const norm = (v) => String(v || '').trim().toLowerCase();
      const dupErrors = {};
      if (admins.some(a => norm(a.email) === norm(data.email))) dupErrors.email = ['Email sudah terdaftar.'];
      if (admins.some(a => norm(a.username) === norm(data.username))) dupErrors.username = ['Username sudah terdaftar.'];
      if (Object.keys(dupErrors).length) {
        const dupErr = new Error(dupErrors.email ? 'Email sudah terdaftar.' : 'Username sudah terdaftar.');
        dupErr.status = 422;
        dupErr.errors = dupErrors;
        throw dupErr;
      }
      // Mode simulasi tidak pernah menyimpan kata sandi mentah di localStorage
      const { password, password_confirmation, ...safeData } = data;
      const newAdmin = {
        id: Date.now(),
        ...safeData,
        lastLogin: 'Baru Dibuat'
      };
      admins.push(newAdmin);
      setStoredMock('aswaja_mock_admins', admins);
      return { isSimulation: true, success: true, data: newAdmin };
    }
  },

  async updateAdmin(id, data) {
    if (!AuthService.isSuperAdmin()) {
      throw new Error('Hanya Super Admin yang berwenang mengedit akun administrator.');
    }
    try {
      return await apiFetch(`/admins/${id}`, {
        method: 'PUT',
        body: JSON.stringify(data)
      });
    } catch (err) {
      if (!err.isNetworkError) throw err;
      const admins = getStoredMock('aswaja_mock_admins');
      const idx = admins.findIndex(a => Number(a.id) === Number(id));
      if (idx !== -1) {
        const { password, password_confirmation, ...safeData } = data;
        admins[idx] = { ...admins[idx], ...safeData };
        setStoredMock('aswaja_mock_admins', admins);
        return { isSimulation: true, success: true, data: admins[idx] };
      }
      throw new Error('Akun admin tidak ditemukan.');
    }
  },

  async deleteAdmin(id) {
    if (!AuthService.isSuperAdmin()) {
      throw new Error('Hanya Super Admin yang berwenang menghapus akun administrator.');
    }
    try {
      return await apiFetch(`/admins/${id}`, { method: 'DELETE' });
    } catch (err) {
      if (!err.isNetworkError) throw err;
      const admins = getStoredMock('aswaja_mock_admins');
      const me = AuthService.getUser() || {};
      if (me.id !== undefined && Number(me.id) === Number(id)) {
        throw new Error('Anda tidak dapat menghapus akun Anda sendiri.');
      }
      if (admins.length <= 1) {
        throw new Error('Admin terakhir tidak boleh dihapus.');
      }
      const target = admins.find(a => Number(a.id) === Number(id));
      const isSuper = (a) => a && (a.role === 'Super Admin' || a.role === 'Superadmin');
      if (isSuper(target) && admins.filter(isSuper).length <= 1) {
        throw new Error('Super Admin terakhir tidak boleh dihapus.');
      }
      mockSoftDelete('akun admin', admins.filter(a => Number(a.id) === Number(id)), a => a.username || a.name);
      setStoredMock('aswaja_mock_admins', admins.filter(a => Number(a.id) !== Number(id)));
      return { isSimulation: true, success: true };
    }
  },

  // 6. Prinsip 4T Fiqih Siber (Materi Utama Web)
  async getPrinsip4T() {
    try {
      const res = await apiFetch('/prinsip-4t');
      const list = Array.isArray(res) ? res : (res?.data || []);
      if (list.length > 0) setStoredMock('aswaja_mock_prinsip4t', list);
      return res;
    } catch {
      return { isSimulation: true, data: getStoredMock('aswaja_mock_prinsip4t') };
    }
  },

  async updatePrinsip4T(id, data) {
    try {
      return await apiFetch(`/prinsip-4t/${id}`, {
        method: 'PUT',
        body: JSON.stringify(data)
      });
    } catch (err) {
      const items = getStoredMock('aswaja_mock_prinsip4t') || [];
      const idx = items.findIndex(item => item.id == id || item.code == id || (item.code && id && String(item.code).toUpperCase() === String(id).toUpperCase()));
      if (idx !== -1) {
        items[idx] = { ...items[idx], ...data };
        setStoredMock('aswaja_mock_prinsip4t', items);
        return { isSimulation: true, success: true, data: items[idx] };
      }
      return { isSimulation: true, success: true, data };
    }
  },

  async getPublicStats() {
    try {
      return await apiFetch('/stats/public');
    } catch (e) {
      return { success: true, data: { accuracy: 99.4, totalAnalyzed: 43, scansCount: 38, reportsCount: 5, educationAccess: 100 } };
    }
  }
};

// Export to window global for modular access across all pages
window.API_BASE_URL = API_BASE_URL;
window.AuthService = AuthService;
window.ApiService = ApiService;
window.isValidUrl = isValidUrl;
window.sanitizeUrlDisplay = sanitizeUrlDisplay;
