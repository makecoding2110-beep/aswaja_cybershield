# Catatan Lengkap Arsitektur & Spesifikasi Frontend
## Platform Aswaja CyberShield (Antarmuka Publik, Panel Admin, Design System White Smoke, & Animasi)

Dokumen ini merupakan **gabungan terpadu seluruh catatan teknis Frontend** pada platform **Aswaja CyberShield**, mencakup struktur halaman Blade PHP (`.blade.php`), sistem tema warna *White Smoke* `#F5F5F5`, sistem animasi performan (*Scroll Reveal, Scanning Laser, & Floating Ambient*), siklus animasi loading analisis tautan, arsitektur skor 4 tingkat risiko resmi (Rendah, Sedang, Tinggi, Berat), komponen *Teknik yang Terdeteksi*, integrasi live API, serta tata letak responsif mobile (HP).

---

## 1. Arsitektur & Karakteristik Frontend

Frontend **Aswaja CyberShield** dibangun dengan pendekatan **Modular Blade PHP + Vanilla JavaScript (ES6+)** dan **Tailwind CSS Play CDN**:

- **Format Tampilan & Clean URLs (Tanpa `.php`):** Menggunakan berkas `.blade.php` yang terorganisasi di dalam direktori `frontend/`, `frontend/artikel/`, dan `frontend/admin/`, disajikan ke pengguna dengan **URL Bersih (*Clean URLs* tanpa ekstensi `.blade.php` atau `.php`)** melalui [`index.php`](file:///d:/cybershield/index.php) dan [`js/navbar.js`](file:///d:/cybershield/js/navbar.js).
- **Statistik Beranda Real-time Berbasis Data Nyata:**
  Strip metrik pada [`frontend/index.blade.php`](file:///d:/cybershield/frontend/index.blade.php) tidak lagi menggunakan angka placeholder dummy (`14200+`), melainkan secara live mengambil kalkulasi riil dari basis data Supabase PostgreSQL (`link_scans` + `reports`) melalui endpoint [`/api/stats/public`](file:///d:/cybershield/backend/routes/api.php) dan [`js/home.js`](file:///d:/cybershield/js/home.js).
- **Tema Light Mode White Smoke (`#F5F5F5`):**
  - Latar belakang utama light mode disetel ke `--bg-page: #F5F5F5` secara konsisten via variabel CSS tunggal di [`css/style.css`](file:///d:/cybershield/css/style.css) dan [`css/ui-kit.css`](file:///d:/cybershield/css/ui-kit.css).
  - Kartu konten menggunakan latar putih murni (`#FFFFFF`) dengan border halus agar kontras tetap tajam dan lolos standar aksesibilitas WCAG AA.
  - Dark mode tetap mempertahankan palet hijau emerald gelap yang elegan.
- **Dynamic API Client (`js/api.js`):**
  `API_BASE_URL` secara cerdas mengadopsi origin browser (`window.location.origin/api`). Hal ini memastikan panggilan API selalu tepat sasaran baik saat dijalankan di `http://localhost:3000`, container Docker, maupun domain produksi Vercel tanpa memicu kendala CORS.
- **Tata Letak Responsif Mobile (HP Optimized):**
  - Header adaptif dengan logo flex `min-w-0` dan font teks brand bertingkat (`text-base sm:text-lg`) agar tombol aksi dan hamburger menu tidak terdesak keluar layar pada perangkat layar sempit (360px–420px).
  - Badge hasil analisis dan waktu pindai tersusun rapi menggunakan `flex-wrap gap-x-2.5 gap-y-1` dengan `whitespace-nowrap inline-flex` sehingga tidak saling tumpang tindih.
  - Kotak skor di layar HP melebar proporsional penuh (`self-stretch sm:self-auto`) dengan perataan teks yang nyaman dilihat.
- **Sistem Animasi Ringan & Elegan:**
  - Diterapkan di seluruh halaman publik (Beranda, Tabayyun, Etika Siber, Cyber Guard, Lapor) dan dinonaktifkan di area admin (`/admin/...`).
  - Menggunakan `IntersectionObserver` untuk animasi *fade-in* + *slide-up* berurutan (*stagger*) antar kartu.
  - Elemen dekoratif latar hero bergerak pelan (*floating ambient mesh*) dengan opacity rendah.
  - Sepenuhnya mematuhi kriteria aksesibilitas `@media (prefers-reduced-motion: reduce)`.

---

## 2. Struktur Modul Antarmuka Publik & Pemetaan Clean URLs

| No | Halaman Publik | URL Bersih | Berkas Blade | Skrip JS Terkait | Fungsi Utama |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **Beranda Utama** | **`/`** | [`frontend/index.blade.php`](file:///d:/cybershield/frontend/index.blade.php) | [`js/home.js`](file:///d:/cybershield/js/home.js), [`js/animations.js`](file:///d:/cybershield/js/animations.js) | Hero section interaktif, 3 pilar *Recognize-Learn-Act*, 4 Pilar Wasathiyah, dan counter statistik live database. |
| 2 | **AI Tabayyun Link Checker** | **`/tabayyun`** | [`frontend/tabayyun.blade.php`](file:///d:/cybershield/frontend/tabayyun.blade.php) | [`js/tabayyun.js`](file:///d:/cybershield/js/tabayyun.js) | Deteksi tautan Judol & Investasi Bodong dengan siklus loading bertahap, Matriks 4 Tingkat Risiko Resmi, Rangkuman Sederhana (Awam), dan "Teknik yang Terdeteksi". |
| 3 | **Etika & Fiqih Siber (4T)** | **`/etika`** | [`frontend/etika.blade.php`](file:///d:/cybershield/frontend/etika.blade.php) | [`js/etika.js`](file:///d:/cybershield/js/etika.js) | Modul literasi **Prinsip 4T** (*Tawaqquf, Tastabbus, Tatsabbut, Ta'aqqul*) tersinkronisasi dinamis dari admin. |
| 4 | **Cyber Guard Interactive Test** | **`/cyber-guard`** | [`frontend/cyber-guard.blade.php`](file:///d:/cybershield/frontend/cyber-guard.blade.php) | [`js/cyber-guard.js`](file:///d:/cybershield/js/cyber-guard.js) | 4 Skenario studi kasus nyata tersinkronisasi dari database, evaluasi kuis, dan pembahasan fiqih instan. |
| 5 | **Portal Lapor Siber** | **`/lapor`** | [`frontend/lapor.blade.php`](file:///d:/cybershield/frontend/lapor.blade.php) | [`js/lapor.js`](file:///d:/cybershield/js/lapor.js) | Formulir aduan masyarakat, upload bukti, opsi pelapor anonim, tiket `LPRxxx`, serta tabel Rekapitulasi Publik live Supabase. |
| 6 | **Pembaca Artikel Edukasi** | **`/artikel/:slug`** | [`frontend/artikel/baca.blade.php`](file:///d:/cybershield/frontend/artikel/baca.blade.php) | [`js/navbar.js`](file:///d:/cybershield/js/navbar.js) | Pembaca dinamis untuk seluruh artikel edukasi dan 4 Kaidah 4T langsung dari basis data. |

---

## 3. Rincian Teknis Modul Utama Frontend

### 3.1. Modul AI Tabayyun (`frontend/tabayyun.blade.php` & `js/tabayyun.js`)
- **Prinsip Zero Remote Execution:** URL target diperlakukan sebagai string data pasif. Browser tidak pernah merender atau mengeksekusi konten target.
- **Siklus Loading Analisis Multi-Tahap (~2 Detik):**
  Saat tombol analisis ditekan, sistem menjamin proses loading visual berjalan mulus secara bertahap sehingga masyarakat memahami tahapan audit yang sedang berlangsung:
  1. **Langkah 1 (20%):** *"Memeriksa Sanad Domain & Legalitas..."*
  2. **Langkah 2 (45%):** *"Mencocokkan Pola Kata Kunci Judol & Investasi..."*
  3. **Langkah 3 (65%):** *"Menganalisis Struktur URL & Parameter Deposit..."*
  4. **Langkah 4 (85%):** *"Mengevaluasi Risiko Maysir & Skema Ponzi..."*
  5. **Langkah 5 (95% - 100%):** *"Menyusun Laporan Heuristik..."*
- **Scanning Laser Beam:** Berkas sinar laser hijau (`.scanning-laser-beam`) bergerak memindai kolom input teks tautan secara vertikal.
- **Auto-Scroll Mulus:** Menggunakan `window.AswajaUI.scrollTo` untuk memposisikan kartu loading tepat di tengah layar peramban tanpa terpotong di tepi bawah.
- **Matriks 4 Tingkat Risiko Resmi (Unified Metric 0%–100%):**
  - **🟢 Rendah (Aman) [0%–25%]:**
    - Label skor: **"Tingkat Keamanan"** ($75\% - 100\%$, emerald `#059669`).
    - Status teks: **"Aman (Risiko Rendah)"**, ikon perisai centang (`verified_user`), dilengkapi 3 poin verifikasi (Audit Sanad, Pola Bersih, Rekomendasi Tabayyun).
  - **🟡 Sedang [26%–50%]:**
    - Label skor: **"Tingkat Bahaya"** ($26\% - 50\%$, amber `#D97706`).
    - Status teks: **"Sedang (Perlu Kewaspadaan)"**, ikon peringatan (`warning`), menjelaskan adanya kedok trading atau TLD promosi.
  - **🟠 Tinggi [51%–84%]:**
    - Label skor: **"Tingkat Bahaya"** ($51\% - 84\%$, orange `#EA580C`).
    - Status teks: **"Tinggi (Perlu Pemeriksaan)"**, ikon investigasi (`gpp_maybe`), mendeteksi kata kunci taruhan judi atau anomali URL.
  - **🔴 Berat / Kritis [85%–100%]:**
    - Label skor: **"Tingkat Bahaya"** ($85\% - 100\%$, crimson red `#DC2626`).
    - Status teks: **"Berat (Sangat Berbahaya)"**, ikon bahaya (`gpp_bad`), mengidentifikasi jaringan judol aktif, web defacement instansi, atau pencurian data.
- **Komponen "Rangkuman Sederhana" (*Layman Summary*):**
  - Menyajikan penjelasan ramah warga awam bebas jargon teknis asing (tanpa istilah *TLD, DNS, URI, NIDS, Regex*).
- **Komponen "Teknik yang Terdeteksi":**
  - Menampilkan badge per teknik (*Web Defacement*, *Open Redirect*, *Typosquatting*).
  - Dilengkapi penjelasan satu kalimat berbahasa awam dan bukti URL dalam format monospace non-aktif yang aman.

### 3.2. Tata Letak Halaman Lapor Siber (`frontend/lapor.blade.php`)
- **Daftar Kategori Laporan Baru (Terstandarisasi):**
  1. *Promosi Situs Judi Online / Slot*
  2. *Investasi Bodong & Trading Ilegal*
  3. *Web Defacement / Subdomain Instansi Diretas*
  4. *Phishing & Pencurian Data (APK/Formulir Palsu)*
  5. *Grup Arisan & Undangan Investasi Menyesatkan*
  6. *Iklan Cuan Kilat Bersyarat Deposit*
  7. *Lainnya*
- **Opsi Pelapor Anonim Bersih:**
  - Placeholder nama pelapor menggunakan `Anonim / Boleh Samaran`.
  - Jika nama tidak diisi, sistem otomatis mencatat nama sebagai `Anonim` pada modal konfirmasi, basis data, dan antarmuka admin.
- **Jarak Responsif Antar Komponen:**
  Kartu formulir pelaporan aduan dan kartu tabel *Rekapitulasi Pelaporan Publik* dipisahkan dengan jarak vertikal lapang (`mt-10 sm:mt-12` dan `space-y-10 sm:space-y-12`) berjarak 40–48px yang rapi di layar ponsel maupun monitor desktop.
- **Sinkronisasi Tabel Rekapitulasi Publik:**
  Tabel rekapitulasi memuat tiket terbaru secara live dari Supabase PostgreSQL (`GET /api/reports`) dengan nama domain yang disamarkan demi privasi pelapor.

### 3.3. Suite Animasi Menarik, Dinamis & Elegan (Public Animation Engine)
- **Ambient Hero Orbs & Cyber Mesh (`.ambient-glow-orb`, `.cyber-mesh-grid`):** Latar hero memiliki orbs emerald bernapas lembut (`orbBreathe`) berpadu dengan pola cyber security grid mesh bergradasi halus.
- **Interactive Card Glow Border (`.card-hover-effect::after`):** Efek border berkilau halus mengitari kartu saat kursor melintas.
- **Button Shimmer Beam (`.btn-shimmer-sweep`):** Kemilau cahaya meluncur periodik (setiap 5.5 detik) melintasi tombol CTA utama.
- **Live Status Pulse Radar (`.live-status-dot`):** Titik status hijau memiliki radar pulsa bergelombang lembut yang menandakan sistem aktif.
- **Icon Micro-Pop on Hover (`.icon-micro-pop`):** Ikon fitur terangkat setinggi `-4px` dengan skala 1.12x dan transisi *spring physics* saat kartu disorot.
- **Hero Title Gradient Shimmer (`.hero-brand-shimmer`):** Shimmer gradien emerald berjalan lembut pada teks judul utama beranda.

---

## 4. Panel Administrator (`frontend/admin/`)

1. **Dashboard KPI (`/admin` — [`index.blade.php`](file:///d:/cybershield/frontend/admin/index.blade.php)):** Statistik operasional, aduan masuk, diproses, selesai, dan log aktivitas staf live dari database.
2. **Kelola Artikel & Editor Kaidah 4T (`/admin/artikel` — [`artikel.blade.php`](file:///d:/cybershield/frontend/admin/artikel.blade.php)):**
   - CRUD artikel edukasi lengkap dengan manajemen gambar sampul aman (dukungan binary file lokal & data URL).
   - **Editor Interaktif Kaidah 4T (`#modal-4t`):** Memungkinkan admin memperbarui nama kaidah, subjudul, dan deskripsi keempat pilar (*T1 Tawaqquf, T2 Tastabbus, T3 Tatsabbut, T4 Ta'aqqul*) secara instan dengan *optimistic UI rendering* dan sinkronisasi live ke Supabase.
3. **Verifikasi Aduan (`/admin/laporan` — [`laporan.blade.php`](file:///d:/cybershield/frontend/admin/laporan.blade.php)):** Triase aduan masyarakat, penugasan analis via dropdown dinamis akun staf, pengubahan status laporan, dan catatan verifikasi live Supabase.
4. **Kelola Studi Kasus (`/admin/studi-kasus` — [`studi-kasus.blade.php`](file:///d:/cybershield/frontend/admin/studi-kasus.blade.php)):** Pengelolaan 4 skenario simulasi Cyber Guard.
5. **Kelola Akun & Hak Akses (`/admin/akun` — [`akun.blade.php`](file:///d:/cybershield/frontend/admin/akun.blade.php)):** Manajemen pengguna admin (Admin Utama, Muhammad Ali Reza, Deby Cantika Putri) live dari tabel `users` Supabase tanpa kewajiban verifikasi email.
6. **Refaktorisasi Tata Letak & Tema Hijau Gradasi Sidebar Admin:**
   - Sidebar admin kini mengadopsi tema **Hijau Gradasi (*Islamic Cyber Green*)** menggunakan `bg-gradient-to-b from-[#064e3b] via-[#022c22] to-[#011a14]`, berpadu dengan aksen border halus `border-emerald-900/40`, teks menu `text-emerald-100`, dan tombol aktif `bg-emerald-500`.
   - Menggunakan arsitektur `lg:sticky lg:top-0 lg:h-screen flex flex-col` dengan `<nav class="flex-1 overflow-y-auto">` yang tertutup rapi.
   - Kotak profil admin (*Admin Utama / Super Admin*) terkunci permanen di bagian paling bawah sidebar menggunakan `mt-auto shrink-0`.
7. **Pop-up Konfirmasi Logout & Verifikasi Login:**
   - Dialog modal konfirmasi logout yang modern dan terpusat (`window.AuthService.confirmLogout()`).
   - Indikator verifikasi login berupa animasi spinner di tengah (*centered*) saat menekan tombol masuk dashboard.
8. **Desain Split-Screen Halaman Login Admin ([`login.blade.php`](file:///d:/cybershield/frontend/admin/login.blade.php)):**
   - Mengadopsi arsitektur *split-screen* modern:
     - **Panel Kiri (Identitas Brand):** Gradasi hijau emerald gelap (`from-[#064e3b] via-[#022c22] to-[#01140e]`), pill badge `ADMIN PANEL`, judul besar *"Kelola Platform CyberShield dengan Aman"*, deskripsi operasional, serta kartu kutipan etika Tabayyun berbingkai border emerald halus.
     - **Panel Kanan (Formulir Akses):** Berlatar *white smoke* dengan kartu putih mengambang (*floating card 3xl*), input email/username dan password dengan *focus ring* hijau emerald, tombol intip sandi, tombol aksi tegas beraksen *hover* emerald, dan catatan hak cipta *internal access only*.
9. **Halaman & Alur Pengiriman Email Reset Password ([`lupa-password.blade.php`](file:///d:/cybershield/frontend/admin/lupa-password.blade.php)):**
   - Halaman khusus pemulihan kata sandi admin dengan alur email interaktif yang realistis dan tangguh:
     - **Langkah 1 (Input Email & Pengiriman Tautan):** Admin menginput email terdaftar. Sistem memicu backend `/api/admin/forgot-password` untuk mengirimkan email reset password resmi berformat HTML.
     - **Langkah 1.5 (Notifikasi Email Terkirim & Pratinjau Pesan Email Menarik):** Menampilkan modal *email client* autentik (*Aswaja Webmail/Inbox Preview*) dengan status TLS dan tombol reset sandi langsung.
     - **Langkah 2 (Pembuatan Kata Sandi Baru):** Menggunakan token bersandi `/reset-password?token=...&email=...` dengan validasi minimal 8 karakter kombinasi huruf, angka, dan simbol.

---

## 5. Tata Letak Modul Baca Edukasi & Eliminasi Ruang Kosong (`frontend/artikel/*.blade.php`)

Pada seluruh berkas artikel edukasi di direktori [`frontend/artikel/`](file:///d:/cybershield/frontend/artikel/):
- **Eliminasi Celah Gelap Di Atas Footer:** Margin atas `mt-20` (80px) pada footer telah dihapus menjadi `mt-0`, serta padding vertikal diperhalus (`py-12`), sehingga bilah navigasi bawah (*Kembali ke Modul Etika* & *Uji Pemahaman di Cyber Guard*) langsung tersambung mulus dengan footer tanpa menyisakan celah kosong yang mengganggu pandangan.
- **Pemuatan Gambar Cepat & Tanpa Error:** Gambar sampul artikel menggunakan berkas binary lokal (`images/artikel-1.jpg`) dan fallback visual otomatis tanpa risiko *broken image* atau galat panjang string.

---

## 🛡️ 6. Fitur Pembatasan Kuota Aduan Publik (Rate Limiting UX)

Pada formulir aduan publik ([`frontend/lapor.blade.php`](file:///d:/cybershield/frontend/lapor.blade.php) & [`js/lapor.js`](file:///d:/cybershield/js/lapor.js)):
- **Badge Kuota Interaktif (`#quota-badge`):** Menampilkan sisa kuota aduan secara *real-time* (contoh: *"Sisa Kuota: 2 / 2"*).
- **Banner Peringatan & Countdown Otomatis (`#quota-warning-box`):** Jika kuota habis (mencapai 2 laporan dalam 2 jam), tombol submit dinonaktifkan (`disabled`) dan muncul countdown dinamis waktu tunggu.
- **Pop-up Peringatan Kuota Terlampaui (`showQuotaExceededAlert()`):** Menampilkan peringatan transparan jika pelapor mencoba mengirimkan laporan lebih dari 2 kali dalam 2 jam, lengkap dengan 4 alasan keamanan:
  1. *Mencegah Serangan Spam dan DDoS*
  2. *Efisiensi Validasi Tim Penyelidik*
  3. *Optimalisasi Bandwidth dan Penyimpanan*
  4. *Mendorong Kualitas Laporan*
- **Sinkronisasi Dua Lapisan:** Kuota dicek secara instan di klien (`localStorage`) dan divalidasi mutlak di server backend via Laravel `RateLimiter` (HTTP 429).

---

## 🔄 7. Sinkronisasi Data Real-time (User Publik vs Admin Dashboard)

Semua entitas data tersinkronisasi 100% mulus antara halaman publik dan panel admin melalui database Supabase PostgreSQL:
- **Pelaporan Publik:** Formulir di `/lapor` langsung tersimpan ke tabel `reports` dan detik itu juga muncul di tabel publik *"Rekapitulasi Pelaporan Publik"* serta halaman admin *"Riwayat Pelaporan"*. Perubahan status (`Pending` $\to$ `Diproses` $\to$ `Selesai`) oleh admin langsung ter-update di kedua sisi.
- **Studi Kasus & Kuis:** Bank soal kuis Cyber Guard di halaman edukasi publik bersumber dari tabel `case_studies` yang dikelola secara live di `/admin/studi-kasus`.
- **Manajemen Akun:** Daftar staf di `/admin/akun` terhubung langsung dengan tabel `users` dengan pemisahan peran RBAC (*Super Admin* vs *Admin Basic*).
- **Proses Loading Analisis Tautan:** Tabayyun AI menyajikan animasi pemindaian 5-tahap visual berurutan (*Sanad Domain*, *Pola Judol*, *Struktur URL*, *Risiko Maysir*, *Laporan Heuristik*) sebelum kartu hasil ditampilkan.

---

## 🌐 8. Arsitektur Clean URL & Auto-Redirect (Bebas `.blade.php` / `.php`)

- **Pengetikan URL Bersih (Clean URLs):**
  - Seluruh halaman web dapat diketik dan dibuka langsung tanpa ekstensi berkas fisik:
    - `/tabayyun` (AI Tabayyun)
    - `/etika` (Modul Edukasi & Etika Fiqih Siber)
    - `/cyber-guard` (Simulasi & Kuis Refleks Siber)
    - `/lapor` (Portal Pengaduan Aduan Siber)
    - `/login` (Gerbang Akses Administrator)
    - `/lupa-password` (Pemulihan Kata Sandi)
    - `/admin` (Dashboard Monitoring)
    - `/admin/artikel`, `/admin/laporan`, `/admin/studi-kasus`, `/admin/akun`
- **Canonical Auto-Redirect (HTTP 301):**
  - Jika pengguna atau peramban membuka tautan lama yang masih membawa akhiran `.blade.php` atau `.php`, server secara instan mengalihkan (HTTP 301) ke URL bersih (`http://localhost:3000/lapor`).
  - Tulisan `.php` atau `.blade.php` tidak akan pernah tampak di bilah alamat peramban.

---

## 🎨 9. Responsivitas & Fitur Modal Akun Pengguna (`/admin/akun`)

- **Pelebaran Modal (`max-w-xl md:max-w-2xl`):** Lebar modal formulir Tambah/Edit Akun diperlebar agar seluruh kolom input memiliki ruang yang leluasa.
- **Normalisasi Grid Kolom Password:** Baris *Kata Sandi* dan *Konfirmasi Kata Sandi* menempati 1 baris penuh terpisah (50%–50%) dengan teks placeholder utuh.
- **Fitur Hide / Unhide Kata Sandi (Ikon Mata):** Ditambahkan tombol toggle interaktif dengan ikon mata Bootstrap Icons (`bi bi-eye` / `bi bi-eye-slash`) dengan reset otomatis saat modal dibuka/ditutup demi privasi.

---

## 🛡️ 10. Keamanan Sesi & Kebijakan Penguncian Akun (*Inactivity Timeout & Login Throttling*)

- **Preservasi Sesi saat Reload Halaman:** Memuat ulang halaman (*reload* / refresh F5) tidak membatalkan sesi. Sesi administrator tetap aktif dan stabil.
- **Tenggat Waktu Ketidakaktifan (*Inactivity Timeout* 5 Menit):** Sistem memantau interaksi nyata pengguna secara kontinu (`mousemove`, `keydown`, `touchstart`, `scroll`, `click`). Jika tidak beraktivitas selama 5 menit, sesi otomatis berakhir demi keamanan data.
- **Kebijakan Penguncian Akun (*Anti-Brute Force*):**
  - Jika terjadi **3 kali kesalahan berturut-turut**, akun terkunci dan mengalami *cooldown* berjenjang (1m, 3m, 5m, 15m, 1h).
  - Terdapat live countdown timer yang mengunci tombol submit hingga masa cooldown selesai.

---

## 🌲 11. Penyelarasan Tema Light Mode Footer (Gradien Hijau Emerald Deep)

- **Harmonisasi Visual Footer (Emerald di Light Mode & Hitam Pekat di Dark Mode):**
  - Pada **Light Mode**, latar belakang footer menggunakan gradien hijau zamrud mendalam (*Deep Emerald Gradient*):
    `from-[#064e3b] via-[#022c22] to-[#011a14]`
  - Pada **Dark Mode**, gradien hijau di-reset menjadi **hitam pekat solid (`#000000`)**:
    `dark:bg-none dark:bg-black dark:from-black dark:via-black dark:to-black`
  - Diterapkan konsisten pada seluruh halaman publik dan artikel edukasi.

---

## ⚡ 12. Pemuatan Cepat Zero-Latency pada Artikel & Pilar 4T Admin

- **Offline-First / Instant-Render Strategy:**
  - Fungsi `getFallbackArticles()` dan `getFallback4T()` langsung merender data artikel dan kartu pilar seketika (0 ms) tanpa menunggu jaringan.
  - Sinkronisasi API Supabase dijalankan di latar belakang (*background refresh*) menggunakan `Promise.allSettled` dengan pengaman timeout 3.5 detik.

---

## 🖱️ 13. Interaktivitas Penuh Dashboard Admin

- **4 Kartu Metrik Utama (KPI):** Total Artikel, Laporan Masuk, Akun Pengelola, dan Studi Kasus dilengkapi badge aksi dan tautan langsung ke modul terkait.
- **Status Breakdown Bar:** Bar persentase (*Selesai, Diproses, Pending*) dapat diklik untuk membuka daftar laporan terfilter.
- **Baris Tabel Interaktif:** Setiap baris laporan aduan masyarakat pada dashboard dapat diklik untuk membuka modal pratinjau cepat (*quick detail modal*).

---

## 📑 14. Restrukturisasi Urutan Menu Sidebar Admin

Urutan navigasi sidebar admin ditata terstruktur memisahkan operasional harian dengan manajemen hak akses sistem:
```
1. 🧭 Dashboard          (/admin)
2. 📄 Artikel            (/admin/artikel)
3. 🛡️ Laporan            (/admin/laporan)
4. 📋 Studi Kasus        (/admin/studi-kasus)
5. 👥 Kelola Akun        (/admin/akun)  <-- Berada di posisi paling bawah
```

---

## ⚡ 15. Infrastruktur Vercel Docker & FrankenPHP

- Menggunakan base image container `dunglas/frankenphp:1-php8.4` di atas Vercel Container Runtime.
- Melayani aset statis (`css/`, `js/`, `images/`, `logo-shield.png`, `favicon.svg`) secara instan melalui Caddy web server dengan kompresi `zstd`/`gzip`.
- Menambahkan proteksi header `Cache-Control: no-cache, no-store, must-revalidate` pada file skrip JavaScript dan parameter versi `?v=...` sehingga perangkat mobile selalu mendapatkan kode terbaru.
