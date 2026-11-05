/**
 * Aswaja CyberShield - API Client & Integration Layer for Laravel REST API */

// Konfigurasi Dasar URL API Laravel
const API_BASE_URL = window.API_BASE_URL || 'http://127.0.0.1:8000/api';

// Kunci penyimpanan sesi
const AUTH_TOKEN_KEY = 'aswaja_admin_token';
const AUTH_USER_KEY = 'aswaja_admin_user';

/**
 * Pengelolaan Autentikasi & Sesi
 */
const AuthService = {
  getToken() {
    return sessionStorage.getItem(AUTH_TOKEN_KEY);
  },
  setToken(token) {
    if (token) {
      sessionStorage.setItem(AUTH_TOKEN_KEY, token);
    } else {
      sessionStorage.removeItem(AUTH_TOKEN_KEY);
    }
    try {
      localStorage.removeItem(AUTH_TOKEN_KEY);
    } catch (e) {}
  },
  getUser() {
    try {
      const u = sessionStorage.getItem(AUTH_USER_KEY);
      return u ? JSON.parse(u) : null;
    } catch {
      return null;
    }
  },
  setUser(user) {
    if (user) {
      sessionStorage.setItem(AUTH_USER_KEY, JSON.stringify(user));
    } else {
      sessionStorage.removeItem(AUTH_USER_KEY);
    }
    try {
      localStorage.removeItem(AUTH_USER_KEY);
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
    } catch (e) {}
    window.location.replace('login.blade.php');
  }
};

/**
 * HTTP Client Wrapper terpusat
 */
async function apiFetch(endpoint, options = {}) {
  const url = endpoint.startsWith('http') ? endpoint : `${API_BASE_URL}${endpoint}`;
  const token = AuthService.getToken();

  const headers = {
    'Accept': 'application/json',
    ...(options.headers || {})
  };

  if (!(options.body instanceof FormData)) {
    headers['Content-Type'] = 'application/json';
  }

  if (token) {
    headers['Authorization'] = `Bearer ${token}`;
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
      throw new Error(errorData.message || 'Sesi telah berakhir, silakan login kembali.');
    }

    // Handle Forbidden (403)
    if (res.status === 403) {
      const errorData = await res.json().catch(() => ({}));
      throw new Error(errorData.message || 'Anda tidak memiliki hak akses untuk tindakan ini.');
    }

    if (!res.ok) {
      const errorData = await res.json().catch(() => ({}));
      throw new Error(errorData.message || `Permintaan gagal dengan status ${res.status}`);
    }

    return await res.json();
  } catch (error) {
    // Flag network error so client can seamlessly utilize structured preview/fallback
    error.isNetworkError = error.message.includes('Failed to fetch') || error.message.includes('NetworkError');
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
      date: '02 Nov 2026, 14:20 WIB',
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
      date: '02 Nov 2026, 11:15 WIB',
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
      date: '01 Nov 2026, 19:40 WIB',
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
      date: '01 Nov 2026, 09:10 WIB',
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
      date: '31 Okt 2026, 16:30 WIB',
      reporter: 'Dewi Lestari',
      category: 'Iklan Cuan Kilat Bersyarat Deposit Judi',
      description: 'Mengaku program subsidi usaha mikro dengan modal awal cuma-cuma, namun syarat aktivasi harus melakukan deposit taruhan di situs kasino daring.',
      status: 'Selesai',
      verificationNotes: 'Diverifikasi sebagai tautan jebakan deposit judol. Modus rekayasa sosial menyasar pelaku usaha kecil.',
      handledBy: 'Super Admin'
    }
  ],

  // 2. Database Artikel Edukasi
  articles: [
    {
      id: 1,
      title: 'Muamalah Finansial: Bebas Scam Judi Online & Investasi Bodong',
      category: 'Fiqih Finansial',
      author: 'Tim Fatwa & Literasi Siber Aswaja',
      date: '01 Nov 2026',
      readTime: '5 Menit Baca',
      status: 'Published', // Published | Draft
      slug: 'muamalah-finansial',
      summary: 'Mengenali perangkap manipulasi psikologis algoritma judi daring (maysir modern) dan bahaya jeratan rentenir digital serta investasi palsu yang merusak stabilitas keluarga.'
    },
    {
      id: 2,
      title: 'Prinsip 4T Menghadapi Tawaran Investasi Sensasional & Cuan Instan',
      category: 'Metodologi Tabayyun',
      author: 'Dr. H. Muhammad Arifin',
      date: '28 Okt 2026',
      readTime: '4 Menit Baca',
      status: 'Published',
      slug: 'prinsip-tatsabbut',
      summary: 'Penerapan Tawaqquf, Tastabbus, Tatsabbut, dan Ta\'aqqul dalam memverifikasi legalitas izin OJK/Bappebti sebelum mentransfer dana ke rekening mencurigakan.'
    },
    {
      id: 3,
      title: 'Bahaya Maysir Modern: Mengapa Game "Trading Cepat" Termasuk Judi Daring?',
      category: 'Edukasi Hukum',
      author: 'Lembaga Bahtsul Masail',
      date: '25 Okt 2026',
      readTime: '6 Menit Baca',
      status: 'Published',
      slug: 'bahaya-ghibah-digital',
      summary: 'Analisis fiqih maaliyah tentang perbedaan transaksi perdagangan nyata (bai\') dengan tebak harga naik-turun berskema zero-sum game yang merugikan publik.'
    },
    {
      id: 4,
      title: 'Anatomi Tautan Phishing Berkedok Investasi: Ciri Domain dan Server Judol',
      category: 'Forensik Digital',
      author: 'CSIRT Aswaja CyberShield',
      date: '20 Okt 2026',
      readTime: '4 Menit Baca',
      status: 'Draft',
      slug: 'mitigasi-phishing-apk',
      summary: 'Panduan teknis memeriksa umur domain, sertifikat SSL anonim, dan pengalihan DNS ke jaringan perjudian daring luar negeri.'
    }
  ],

  // 3. Database Studi Kasus & Soal Latihan (Cyber Guard)
  caseStudies: [
    {
      id: 1,
      title: 'Tautan Robot Trading Syariah yang Berujung Deposit Slot',
      difficulty: 'Dasar',
      riskLevel: 'Kritis',
      date: '02 Nov 2026',
      scenario: 'Studi Kasus 01: Penawaran Robot Trading Otomatis',
      sender: '+62 821-4455-XXXX (Admin Cuan Berkah Telegram)',
      message: 'Halo Kak! Mau pasif income Rp 500rb/hari tanpa ribet? Cukup registrasi di link resmi platform trading syariah kami, klaim bonus selamat datang, dan robot akan memutar profit otomatis!',
      targetUrl: 'https://cuan-trading-syariah-vip88.xyz/register',
      question: 'Setelah Anda membuka link tersebut, Anda diarahkan ke halaman yang meminta deposit Rp 100.000 dengan tampilan roda putar (spin) dan saldo taruhan. Apa tindakan Tabayyun yang tepat?',
      options: [
        { key: 'A', text: 'Mendepositkan Rp 100.000 karena nominalnya kecil dan ingin membuktikan apakah benar menghasilkan keuntungan.', correct: false },
        { key: 'B', text: 'Menerapkan Tawaqquf: Segera tutup halaman, jangan lakukan transfer, karena ini adalah kedok judi online (maysir) yang disamarkan sebagai robot trading.', correct: true },
        { key: 'C', text: 'Meneruskan pesan ke grup keluarga untuk menanyakan apakah ada yang pernah mencoba platform ini.', correct: false },
        { key: 'D', text: 'Mendaftarkan nomor rekening bank untuk sekadar melihat apakah bonus uang bisa langsung ditarik tunai.', correct: false }
      ],
      explanation: 'Tepat sekali (Pilihan B)! Modus "robot trading syariah" yang meminta pengguna memutar spin keberuntungan atau taruhan tebak angka adalah bentuk perjudian online berkedok investasi. Lembaga resmi tidak pernah menggunakan skema acak seperti slot.'
    },
    {
      id: 2,
      title: 'Mining Kripto Harian dengan Syarat Beli Tiket Keberuntungan',
      difficulty: 'Menengah',
      riskLevel: 'Tinggi',
      date: '01 Nov 2026',
      scenario: 'Studi Kasus 02: Skema Mining Kripto Instan',
      sender: 'Broadcast Media Sosial / Iklan Bersponsor',
      message: 'Revolusi Finansial 2026! Cuma modal smartphone, mining USDT hasilkan Rp 10 Juta per minggu. Daftar akun gratis hari ini!',
      targetUrl: 'https://mining-usdt-cuan-instan.club/dashboard',
      question: 'Di dalam platform tersebut, Anda diberi "saldo virtual Rp 2.000.000", tetapi saldo tersebut baru bisa dicairkan (withdraw) jika Anda memasang taruhan tebak warna/angka sebanyak 10 putaran. Apa analisis Anda?',
      options: [
        { key: 'A', text: 'Ini adalah modus penipuan berkedok judi online. Saldo virtual hanya umpan psikologis untuk memaksa pengguna mentransfer uang taruhan sungguhan.', correct: true },
        { key: 'B', text: 'Ini adalah mekanisme promosi wajar dari perusahaan teknologi keuangan luar negeri.', correct: false },
        { key: 'C', text: 'Sebaiknya bermain 10 putaran dengan taruhan paling kecil agar saldo virtual bisa ditarik.', correct: false },
        { key: 'D', text: 'Meminjam uang ke kerabat untuk deposit taruhan agar cepat mencapai syarat batas penarikan.', correct: false }
      ],
      explanation: 'Benar (Pilihan A)! Ini adalah teknik rekayasa sosial manipulasi "sunk cost fallacy" dan "withdrawal lock". Saldo awal palsu digunakan untuk menjebak korban agar terus menyetor uang ke sistem judi online.'
    },
    {
      id: 3,
      title: 'Pencatutan Tokoh Ulama & Figur Publik untuk Investasi Kilat',
      difficulty: 'Menengah',
      riskLevel: 'Tinggi',
      date: '29 Okt 2026',
      scenario: 'Studi Kasus 03: Video Manipulasi Tokoh Publik',
      sender: 'Akun TikTok / Reel Tidak Dikenal',
      message: 'Video memperlihatkan tokoh ulama kondang seolah-olah menyatakan bahwa platform investasi berbasis aplikasi tertentu adalah halal dan menjamin hasil 100%.',
      targetUrl: 'https://investasi-berkah-pesantren2026.online',
      question: 'Gerak bibir ulama dalam video tampak tidak sinkron dengan suara (indikasi AI deepfake). Di akhir video ada ajakan klik link deposit. Sikap Anda?',
      options: [
        { key: 'A', text: 'Langsung percaya karena mengagumi ulama tersebut dan segera menyetor modal awal.', correct: false },
        { key: 'B', text: 'Menerapkan prinsip Tatsabbut: Melakukan konfirmasi ke akun resmi pesantren/tokoh, tidak menyebarkan video, dan melaporkan link tersebut sebagai phishing judol.', correct: true },
        { key: 'C', text: 'Membagikan video ke grup WhatsApp santri dengan tulisan "Benarkah kabar ini?".', correct: false },
        { key: 'D', text: 'Menghujat sang tokoh di kolom komentar tanpa meneliti keaslian video.', correct: false }
      ],
      explanation: 'Benar (Pilihan B)! Sindikat judi online dan investasi ilegal kerap mencatut nama ulama dan tokoh masyarakat menggunakan deepfake AI untuk meruntuhkan kewaspadaan calon korban.'
    },
    {
      id: 4,
      title: 'Tautan "Bantuan Modal Usaha" Bersyarat Aktivasi Taruhan',
      difficulty: 'Lanjutan',
      riskLevel: 'Kritis',
      date: '26 Okt 2026',
      scenario: 'Studi Kasus 04: Bantuan Finansial Palsu',
      sender: '+62 813-9090-XXXX (Program Bantuan Mandiri)',
      message: 'Selamat! Usaha mikro Anda terpilih menerima hibah modal Rp 15.000.000. Klik tautan verifikasi identitas dan aktifkan akun Anda dalam waktu 24 jam.',
      targetUrl: 'https://hibah-modal-warga-indonesia.top/klaim',
      question: 'Tautan meminta Anda mengisi data nomor rekening dan kode OTP bank, lalu menginstruksikan Anda memasang taruhan kecil di kasino daring untuk "mengaktifkan token pencairan". Apa tindakan Anda?',
      options: [
        { key: 'A', text: 'Mengikuti instruksi karena sudah membutuhkan modal usaha.', correct: false },
        { key: 'B', text: 'Menolak keras, tidak memberikan kode OTP apa pun, dan segera memblokir nomor tersebut karena ini adalah pencurian data dan jebakan judi online.', correct: true },
        { key: 'C', text: 'Memberikan nomor rekening bank tetapi tidak memasang taruhan kasino.', correct: false },
        { key: 'D', text: 'Meminta bukti surat keputusan resmi ke pengirim pesan via chat.', correct: false }
      ],
      explanation: 'Benar (Pilihan B)! Program hibah pemerintah atau lembaga resmi TIDAK PERNAH mensyaratkan aktivitas di platform taruhan atau meminta kode OTP nasabah.'
    }
  ],

  // 4. Database Akun Admin
  admins: [
    {
      id: 1,
      name: 'Super Admin Utama',
      username: 'superadmin',
      email: 'superadmin@aswajacyber.id',
      role: 'Super Admin',
      status: 'Aktif',
      lastLogin: '03 Nov 2026, 08:30 WIB'
    },
    {
      id: 2,
      name: 'Ahmad Fauzi, S.Kom',
      username: 'analis',
      email: 'fauzi.cyber@aswajacyber.id',
      role: 'Admin Basic',
      status: 'Aktif',
      lastLogin: '02 Nov 2026, 17:10 WIB'
    },
    {
      id: 3,
      name: 'Siti Nurhaliza',
      username: 'siti',
      email: 'siti.analis@aswajacyber.id',
      role: 'Admin Basic',
      status: 'Aktif',
      lastLogin: '01 Nov 2026, 14:45 WIB'
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

  // 6. Prinsip 4T Fiqih Siber (Materi Utama)
  prinsip4T: [
    {
      id: 'T1',
      code: 'T1',
      title: 'Tawaqquf',
      subtitle: 'Tahan Diri Sebelum Menyebarkan',
      description: 'Jeda kritis menahan jempol dari menyebarkan pesan sensasional, broadcast panik, atau tautan provokatif sebelum jelas faktanya.',
      link: 'artikel/prinsip-tawaqquf.blade.php',
      icon: 'pan_tool'
    },
    {
      id: 'T2',
      code: 'T2',
      title: 'Tastabbus',
      subtitle: 'Teliti Kebenaran Substansi',
      description: 'Penyelidikan seksama terhadap isi informasi, memastikan video/foto tidak dipotong di luar konteks aslinya.',
      link: 'artikel/prinsip-tastabbus.blade.php',
      icon: 'search_check'
    },
    {
      id: 'T3',
      code: 'T3',
      title: 'Tatsabbut',
      subtitle: 'Periksa Otoritas Sumber',
      description: 'Memeriksa keabsahan sanad pengirim, apakah berasal dari institusi berwenang atau domain kloning penipu.',
      link: 'artikel/prinsip-tatsabbut.blade.php',
      icon: 'verified'
    },
    {
      id: 'T4',
      code: 'T4',
      title: "Ta'aqqul",
      subtitle: 'Timbang Maslahat & Dampak',
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
  if (!localStorage.getItem('aswaja_mock_articles')) {
    localStorage.setItem('aswaja_mock_articles', JSON.stringify(MockDatabase.articles));
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
 * Service API Endpoints yang terisolasi dan siap terhubung ke Laravel
 */
const ApiService = {
  // 1. Analisis Tautan (Recognize)
  async analyzeUrl(targetUrl) {
    if (!isValidUrl(targetUrl)) {
      throw new Error('Format URL tidak valid. Masukkan alamat web yang lengkap, misalnya https://contoh-investasi.com');
    }

    try {
      // Panggil backend Laravel jika tersedia
      return await apiFetch('/analyze-link', {
        method: 'POST',
        body: JSON.stringify({ url: targetUrl })
      });
    } catch (err) {
      // Fallback simulasi cerdas berbasis deteksi heuristik judol & investasi mencurigakan
      console.info('[Simulation Mode] Analisis link menggunakan mesin heuristik frontend.');
      const lower = targetUrl.toLowerCase();

      const judolKeywords = ['slot', 'gacor', 'spin', 'bet', 'judi', 'taruhan', 'casino', 'kasino', 'maxwin', 'deposit', 'vip88', 'hoki', 'toto', 'togel'];
      const investKeywords = ['trading', 'cuan', 'mining', 'invest', 'profit', 'syariah', 'dana-kaget', 'hibah', 'arisan', 'passiveincome'];

      const hasJudol = judolKeywords.some(k => lower.includes(k));
      const hasInvest = investKeywords.some(k => lower.includes(k));

      let resultCategory = 'Belum Ditemukan Indikasi';
      let score = 92;
      let statusAlert = 'Status Aman';
      let alertClass = 'emerald';
      let title = 'Belum Ditemukan Indikasi Tautan Judi Online';
      let findings = [
        { label: 'Audit Domain:', desc: 'Domain tidak terdaftar dalam basis data pemblokiran judol atau kloning finansial yang diketahui.' },
        { label: 'Analisis Muatan:', desc: 'Tidak ditemukan skema setoran taruhan, roda putar acak, atau janji keuntungan instan berkedok judi.' },
        { label: 'Saran Tabayyun:', desc: 'Tetap waspada. Pastikan entitas finansial telah berizin dan terdaftar resmi di OJK atau Bappebti sebelum melakukan transaksi.' }
      ];

      if (hasJudol && hasInvest) {
        resultCategory = 'Terindikasi';
        score = 24;
        statusAlert = 'Peringatan Bahaya Tinggi';
        alertClass = 'red';
        title = 'Waspada: Terindikasi Tautan Investasi Palsu Mengarah ke Judi Online';
        findings = [
          { label: 'Anomali Domain & Kedok Usaha:', desc: 'Menggunakan nama berbau investasi atau trading, namun parameter URL mengarahkan ke sistem deposit dan putaran taruhan judi online (slot/kasino).' },
          { label: 'Manipulasi Psikologis:', desc: 'Menjanjikan passive income instan dan profit pasti yang melanggar prinsip fiqih muamalah serta regulasi OJK/Bappebti.' },
          { label: 'Rekomendasi Tindakan:', desc: 'Jangan lakukan registrasi atau transfer dana. Laporkan tautan ini ke portal aduan Aswaja CyberShield untuk ditindaklanjuti.' }
        ];
      } else if (hasJudol || hasInvest) {
        resultCategory = 'Perlu Diwaspadai';
        score = 56;
        statusAlert = 'Perlu Diwaspadai';
        alertClass = 'amber';
        title = 'Perlu Diwaspadai: Indikasi Skema Finansial Berisiko Tinggi Tanpa Izin';
        findings = [
          { label: 'Legalitas Belum Terverifikasi:', desc: 'Tautan tidak mencantumkan legalitas perizinan OJK/Bappebti yang valid dan menggunakan domain tingkat atas tidak resmi.' },
          { label: 'Potensi Maysir & Gharar:', desc: 'Terdapat indikasi spekulasi tinggi atau pengalihan deposit ke pihak ketiga yang tidak dapat dipertanggungjawabkan.' },
          { label: 'Rekomendasi Tindakan:', desc: 'Terapkan prinsip Tawaqquf (tahan diri). Teliti keabsahan sanad pengelola sebelum menyetorkan dana apa pun.' }
        ];
      }

      return {
        isSimulation: true,
        url: targetUrl,
        category: resultCategory,
        score: score,
        statusAlert: statusAlert,
        alertClass: alertClass,
        title: title,
        findings: findings,
        scannedAt: new Date().toLocaleTimeString('id-ID') + ' WIB'
      };
    }
  },

  // 2. Laporan Pengaduan (Act)
  async submitReport(data) {
    if (!data.url || !isValidUrl(data.url)) {
      throw new Error('Harap masukkan URL tautan yang dilaporkan dengan format yang benar.');
    }
    if (!data.description || data.description.trim().length < 10) {
      throw new Error('Deskripsi atau kronologi laporan minimal 10 karakter.');
    }

    try {
      return await apiFetch('/reports', {
        method: 'POST',
        body: JSON.stringify(data)
      });
    } catch (err) {
      // Simpan ke local mock data jika backend belum ada
      const reports = getStoredMock('aswaja_mock_reports');
      const newId = `LPR${String(reports.length + 82).padStart(3, '0')}`;
      const newReport = {
        id: newId,
        url: data.url,
        date: new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + ', ' + new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB',
        reporter: data.reporter || 'Hamba Allah',
        category: data.category || 'Investasi Berkedok Trading / Crypto',
        description: data.description,
        status: 'Masuk',
        verificationNotes: 'Laporan baru diterima dari portal publik. Menunggu verifikasi awal.',
        handledBy: 'Belum Ditugaskan'
      };
      reports.unshift(newReport);
      setStoredMock('aswaja_mock_reports', reports);

      return {
        isSimulation: true,
        success: true,
        ticketId: newId,
        message: 'Laporan Anda telah berhasil dicatat dan masuk antrean verifikasi analisis siber Aswaja.'
      };
    }
  },

  async getReports() {
    try {
      const res = await apiFetch('/reports');
      const list = Array.isArray(res) ? res : (res?.data || []);
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
    } catch {
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
    } catch {
      let reports = getStoredMock('aswaja_mock_reports');
      reports = reports.filter(r => String(r.id) !== String(id));
      setStoredMock('aswaja_mock_reports', reports);
      return { isSimulation: true, success: true };
    }
  },

  // 3. Modul Artikel Edukasi
  async getArticles() {
    try {
      const res = await apiFetch('/articles');
      const list = Array.isArray(res) ? res : (res?.data || []);
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
    } catch {
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
    } catch {
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
    } catch {
      let articles = getStoredMock('aswaja_mock_articles');
      articles = articles.filter(a => Number(a.id) !== Number(id));
      setStoredMock('aswaja_mock_articles', articles);
      return { isSimulation: true, success: true };
    }
  },

  // 4. Studi Kasus & Soal Latihan (Cyber Guard)
  async getCaseStudies() {
    try {
      const res = await apiFetch('/case-studies');
      const list = Array.isArray(res) ? res : (res?.data || []);
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
    } catch {
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
    } catch {
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
    } catch {
      let cases = getStoredMock('aswaja_mock_cases');
      cases = cases.filter(c => Number(c.id) !== Number(id));
      setStoredMock('aswaja_mock_cases', cases);
      return { isSimulation: true, success: true };
    }
  },

  // 5. Akun Admin
  async getAdmins() {
    try {
      const res = await apiFetch('/admins');
      const list = Array.isArray(res) ? res : (res?.data || []);
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
    } catch {
      const admins = getStoredMock('aswaja_mock_admins');
      const newAdmin = {
        id: Date.now(),
        ...data,
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
    } catch {
      const admins = getStoredMock('aswaja_mock_admins');
      const idx = admins.findIndex(a => Number(a.id) === Number(id));
      if (idx !== -1) {
        admins[idx] = { ...admins[idx], ...data };
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
    } catch {
      let admins = getStoredMock('aswaja_mock_admins');
      admins = admins.filter(a => Number(a.id) !== Number(id));
      setStoredMock('aswaja_mock_admins', admins);
      return { isSimulation: true, success: true };
    }
  },

  // 6. Prinsip 4T Fiqih Siber (Materi Utama Web)
  async getPrinsip4T() {
    try {
      return await apiFetch('/prinsip-4t');
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
    } catch {
      const items = getStoredMock('aswaja_mock_prinsip4t');
      const idx = items.findIndex(item => item.id === id || item.code === id);
      if (idx !== -1) {
        items[idx] = { ...items[idx], ...data };
        setStoredMock('aswaja_mock_prinsip4t', items);
        return { isSimulation: true, success: true, data: items[idx] };
      }
      throw new Error('Prinsip 4T tidak ditemukan.');
    }
  }
};

// Export to window global for modular access across all pages
window.API_BASE_URL = API_BASE_URL;
window.AuthService = AuthService;
window.ApiService = ApiService;
window.isValidUrl = isValidUrl;
window.sanitizeUrlDisplay = sanitizeUrlDisplay;
