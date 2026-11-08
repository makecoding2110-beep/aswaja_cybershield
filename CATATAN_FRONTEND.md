# Catatan Lengkap Arsitektur & Spesifikasi Frontend
## Platform Aswaja CyberShield (Antarmuka Publik, Panel Admin, Design System White Smoke, & Animasi)

Dokumen ini merupakan **gabungan terpadu seluruh catatan teknis Frontend** pada platform **Aswaja CyberShield**, mencakup struktur halaman Blade PHP (`.blade.php`), sistem tema warna *White Smoke* `#F5F5F5`, sistem animasi performan (*Scroll Reveal, Scanning Laser, & Floating Ambient*), siklus animasi loading analisis tautan, arsitektur skor 2-state (Aman/Berbahaya), komponen *Teknik yang Terdeteksi*, integrasi live API, serta tata letak responsif.

---

## 1. Arsitektur & Karakteristik Frontend

Frontend **Aswaja CyberShield** dibangun dengan pendekatan **Modular Blade PHP + Vanilla JavaScript (ES6+)** dan **Tailwind CSS Play CDN**:

- **Format Tampilan & Clean URLs (Tanpa `.php`):** Menggunakan berkas `.blade.php` yang terorganisasi di dalam direktori `frontend/`, `frontend/artikel/`, dan `frontend/admin/`, disajikan ke pengguna dengan **URL Bersih (*Clean URLs* tanpa ekstensi `.blade.php` atau `.php`)** melalui [`index.php`](file:///d:/cybershield/index.php) dan [`js/navbar.js`](file:///d:/cybershield/js/navbar.js).
- **Statistik Beranda Real-time Berbasis Data Nyata:**
  Strip metrik pada [`frontend/index.blade.php`](file:///d:/cybershield/frontend/index.blade.php) tidak lagi menggunakan angka placeholder dummy (`14200+`), melainkan secara live mengambil kalkulasi riil dari basis data Supabase PostgreSQL (`link_scans` + `reports`, saat ini 43+) melalui endpoint [`/api/stats/public`](file:///d:/cybershield/backend/routes/api.php) dan [`js/home.js`](file:///d:/cybershield/js/home.js).
- **Tema Light Mode White Smoke (`#F5F5F5`):**
  - Latar belakang utama light mode disetel ke `--bg-page: #F5F5F5` secara konsisten via variabel CSS tunggal di [`css/style.css`](file:///d:/cybershield/css/style.css) dan [`css/ui-kit.css`](file:///d:/cybershield/css/ui-kit.css).
  - Kartu konten menggunakan latar putih murni (`#FFFFFF`) dengan border halus agar kontras tetap tajam dan lolos standar aksesibilitas WCAG AA.
  - Dark mode tetap mempertahankan palet hijau emerald gelap yang elegan.
- **Dynamic API Client (`js/api.js`):**
  `API_BASE_URL` secara cerdas mengadopsi origin browser (`window.location.origin/api`). Hal ini memastikan panggilan API selalu tepat sasaran baik saat dijalankan di `http://localhost:3000`, `http://127.0.0.1:8000`, maupun domain produksi tanpa memicu CORS atau kendala lintas port.
- **Sistem Animasi Ringan & Elegan:**
  - Diterapkan di seluruh halaman publik (Beranda, Tabayyun, Etika Siber, Cyber Guard, Lapor) dan dinonaktifkan di area admin (`/admin/...`).
  - Menggunakan `IntersectionObserver` untuk animasi *fade-in* + *slide-up* berurutan (*stagger*) antar kartu.
  - Elemen dekoratif latar hero bergerak pelan (*floating ambient mesh*) dengan opacity rendah.
  - Sepenuhnya mematuhi kriteria aksesibilitas `@media (prefers-reduced-motion: reduce)`.

---

## 2. Struktur Modul Antarmuka Publik & Pemetaan Clean URLs

| No | Halaman Publik | URL Bersih | Berkas Blade | Skrip JS Terkait | Fungsi Utama |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **Beranda Utama** | **`/`** | [`frontend/index.blade.php`](file:///d:/cybershield/frontend/index.blade.php) | [`js/home.js`](file:///d:/cybershield/js/home.js), [`js/animations.js`](file:///d:/cybershield/js/animations.js) | Hero section interaktif, 3 pilar *Recognize-Learn-Act*, 4 Pilar Wasathiyah, dan counter statistik. |
| 2 | **AI Tabayyun Link Checker** | **`/tabayyun`** | [`frontend/tabayyun.blade.php`](file:///d:/cybershield/frontend/tabayyun.blade.php) | [`js/tabayyun.js`](file:///d:/cybershield/js/tabayyun.js) | Deteksi tautan Judol & Investasi Bodong dengan siklus loading bertahap, skor 2-state (Aman/Berbahaya), dan "Teknik yang Terdeteksi". |
| 3 | **Etika & Fiqih Siber (4T)** | **`/etika`** | [`frontend/etika.blade.php`](file:///d:/cybershield/frontend/etika.blade.php) | [`js/etika.js`](file:///d:/cybershield/js/etika.js) | Modul literasi **Prinsip 4T** (*Tawaqquf, Tastabbus, Tatsabbut, Ta'aqqul*) tersinkronisasi dinamis dari admin. |
| 4 | **Cyber Guard Interactive Test** | **`/cyber-guard`** | [`frontend/cyber-guard.blade.php`](file:///d:/cybershield/frontend/cyber-guard.blade.php) | [`js/cyber-guard.js`](file:///d:/cybershield/js/cyber-guard.js) | 4 Skenario studi kasus nyata tersinkronisasi dari database, evaluasi kuis, dan pembahasan fiqih instan. |
| 5 | **Portal Lapor Siber** | **`/lapor`** | [`frontend/lapor.blade.php`](file:///d:/cybershield/frontend/lapor.blade.php) | [`js/lapor.js`](file:///d:/cybershield/js/lapor.js) | Formulir aduan masyarakat, upload bukti, opsi anonim, tiket `LPRxxx`, serta tabel Rekapitulasi Publik live Supabase. |
| 6 | **10 Halaman Kajian Tematik** | **`/artikel/:slug`** | [`frontend/artikel/*.blade.php`](file:///d:/cybershield/frontend/artikel) | [`js/navbar.js`](file:///d:/cybershield/js/navbar.js) | Artikel edukasi mendalam untuk 4 Kaidah 4T dan 6 topik Fiqih Siber. |

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
- **Sistem Skor 2-State (Aman vs Berbahaya):**
  - **Jika Tautan AMAN:**
    - Label: **"Tingkat Keamanan"** dengan skor $0\% - 100\%$ (makin tinggi makin aman).
    - Status teks: **"Aman"**, warna kotak, angka, dan ikon perisai menggunakan `--color-primary` (emerald `#059669`).
  - **Jika Tautan BERBAHAYA:**
    - Label: **"Tingkat Bahaya"** dengan skor $0\% - 100\%$ (makin tinggi makin berbahaya).
    - Status teks: **"Berbahaya"**, warna token danger merah (`#DC2626`).
    - Label penegas **"Sangat Berbahaya"** HANYA disematkan jika skor bahaya $\ge 85\%$.
- **Komponen "Teknik yang Terdeteksi":**
  - Menampilkan badge per teknik (*Web Defacement*, *Open Redirect*, *Typosquatting*).
  - Dilengkapi penjelasan satu kalimat berbahasa awam dan bukti URL dalam format monospace non-aktif yang aman.

### 3.2. Tata Letak Halaman Lapor Siber (`frontend/lapor.blade.php`)
- **Jarak Responsif Antar Komponen:**
  Kartu formulir pelaporan aduan dan kartu tabel *Rekapitulasi Pelaporan Publik* dipisahkan dengan jarak vertikal lapang (`mt-10 sm:mt-12` dan `space-y-10 sm:space-y-12`) berjarak 40–48px yang rapi di layar ponsel maupun monitor desktop.
- **Integritas Hirarki Tag:**
  Struktur tag penutup `</div>` telah diseimbangkan dengan presisi (45 open / 45 close div), mencegah layout collapsing.
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
3. **Verifikasi Aduan (`/admin/laporan` — [`laporan.blade.php`](file:///d:/cybershield/frontend/admin/laporan.blade.php)):** Triase aduan masyarakat, penugasan analis, pengubahan status laporan, dan catatan verifikasi live Supabase.
4. **Kelola Studi Kasus (`/admin/studi-kasus` — [`studi-kasus.blade.php`](file:///d:/cybershield/frontend/admin/studi-kasus.blade.php)):** Pengelolaan 4 skenario simulasi Cyber Guard.
5. **Kelola Akun & Hak Akses (`/admin/akun` — [`akun.blade.php`](file:///d:/cybershield/frontend/admin/akun.blade.php)):** Manajemen pengguna admin (Admin Utama, Muhammad Ali Reza, Deby Cantika Putri) live dari tabel `users` Supabase tanpa kewajiban verifikasi email.
6. **Refaktorisasi Tata Letak & Tema Hijau Gradasi Sidebar Admin:**
   - Sidebar admin kini mengadopsi tema **Hijau Gradasi (*Islamic Cyber Green*)** menggunakan `bg-gradient-to-b from-[#064e3b] via-[#022c22] to-[#011a14]`, berpadu dengan aksen border halus `border-emerald-900/40`, teks menu `text-emerald-100`, dan tombol aktif `bg-emerald-500`.
   - Navigasi sidebar disederhanakan dan dibersihkan dari menu *Integrasi Dual AI* untuk memfokuskan alur kerja operasional admin.
   - Menggunakan arsitektur `lg:sticky lg:top-0 lg:h-screen flex flex-col` dengan `<nav class="flex-1 overflow-y-auto">` yang tertutup rapi.
   - Kotak profil admin (*Admin Utama / Super Admin*) terkunci permanen di bagian paling bawah sidebar menggunakan `mt-auto shrink-0`.
   - Footer panel admin telah dibersihkan dari teks berlebih (*Modul Verifikasi Laporan • Komdigi / AduanKonten Ready*) dan diselaraskan di tengah.
7. **Pop-up Konfirmasi Logout & Verifikasi Login:**
   - Dialog modal konfirmasi logout yang modern dan terpusat (`window.AuthService.confirmLogout()`).
   - Indikator verifikasi login berupa animasi spinner di tengah (*centered*) saat menekan tombol masuk dashboard.
8. **Desain Split-Screen Halaman Login Admin ([`login.blade.php`](file:///d:/cybershield/frontend/admin/login.blade.php)):**
   - Mengadopsi arsitektur *split-screen* modern:
     - **Panel Kiri (Identitas Brand):** Gradasi hijau emerald gelap (`from-[#064e3b] via-[#022c22] to-[#01140e]`), pill badge `ADMIN PANEL`, judul besar *"Kelola Platform CyberShield dengan Aman"*, deskripsi operasional, serta kartu kutipan etika Tabayyun berbingkai border emerald halus.
     - **Panel Kanan (Formulir Akses):** Berlatar *white smoke* dengan kartu putih mengambang (*floating card 3xl*), input email/username dan password dengan *focus ring* hijau emerald, opsi *Ingat saya*, tautan bantuan, tombol aksi hitam tegas beraksen *hover* emerald, dan catatan hak cipta *internal access only*.
9. **Halaman & Alur Pengiriman Email Reset Password ([`lupa-password.blade.php`](file:///d:/cybershield/frontend/admin/lupa-password.blade.php)):**
   - Halaman khusus pemulihan kata sandi admin dengan alur email interaktif yang realistis dan tangguh:
     - **Kartu Mengambang Minimalis:** Berisi logo Aswaja CyberShield, judul *"Lupa Password"*, deskripsi panduan, dan banner motivasi integritas kerja (*Workhard and show your dignity !*).
     - **Langkah 1 (Input Email & Pengiriman Tautan):** Admin menginput email terdaftar (misal: `aswajashield@gmail.com`). Sistem memicu backend `/api/admin/forgot-password` untuk mengirimkan email reset password resmi berformat HTML.
     - **Langkah 1.5 (Notifikasi Email Terkirim & Pratinjau Pesan Email Menarik):**
       - Menampilkan notifikasi visual sukses *"Email Berhasil Dikirim!"* dengan alamat tujuan yang ditargetkan.
       - Menyediakan tombol **"Buka Pratinjau Tampilan Pesan Email"** yang memunculkan modal *email client* autentik (*Aswaja Webmail/Inbox Preview*) dengan status enkripsi TLS, metadata pengirim, dan konten template email HTML bernuansa hijau gradasi emerald.
       - Tombol **"Reset Kata Sandi Sekarang"** pada pesan email dapat langsung diklik untuk melanjutkan ke pengisian kata sandi baru.
     - **Langkah 2 (Pembuatan Kata Sandi Baru):**
       - Dapat diakses via tombol email atau langsung saat membuka URL tautan bersandi: `/reset-password?token=...&email=...` (otomatis mendeteksi token dan email dari parameter URL).
       - Menampilkan badge *"Tautan Email Terverifikasi"*, form kata sandi baru dengan tombol intip sandi (*eye toggle*), dan validasi minimal 6 karakter.
     - **Pembaruan Live & Redirect:** Memperbarui data pengguna secara langsung di Supabase PostgreSQL lalu mengalihkan admin kembali ke halaman login.

---

## 5. Tata Letak Modul Baca Edukasi & Eliminasi Ruang Kosong (`frontend/artikel/*.blade.php`)

Pada seluruh 11 berkas artikel edukasi di direktori [`frontend/artikel/`](file:///d:/cybershield/frontend/artikel/):
- **Eliminasi Celah Gelap Di Atas Footer:** Margin atas `mt-20` (80px) pada footer telah dihapus menjadi `mt-0`, serta padding vertikal diperhalus (`py-12`), sehingga bilah navigasi bawah (*Kembali ke Modul Etika* & *Uji Pemahaman di Cyber Guard*) langsung tersambung mulus dengan footer tanpa menyisakan celah kosong yang mengganggu pandangan.
- **Pemuatan Gambar Cepat & Tanpa Error:** Gambar sampul artikel menggunakan berkas binary lokal (`images/artikel-1.jpg`) dan fallback visual otomatis tanpa risiko *broken image* atau galat panjang string.

---

## 🛡️ 7. Fitur Pembatasan Kuota Aduan Publik (Rate Limiting UX)

Pada formulir aduan publik ([`frontend/lapor.blade.php`](file:///d:/cybershield/frontend/lapor.blade.php) & [`js/lapor.js`](file:///d:/cybershield/js/lapor.js)):
- **Badge Kuota Interaktif (`#quota-badge`):** Menampilkan sisa kuota aduan secara *real-time* (contoh: *"Sisa Kuota: 2 / 2"*).
- **Banner Peringatan & Countdown Otomatis (`#quota-warning-box`):** Jika kuota habis (mencapai 2 laporan dalam 2 jam), tombol submit dinonaktifkan (`disabled`) dan muncul countdown dinamis waktu tunggu.
- **Pop-up Peringatan Kuota Terlampaui (`showQuotaExceededAlert()`):** Menampilkan peringatan transparan jika pelapor mencoba mengirimkan laporan lebih dari 2 kali dalam 2 jam, lengkap dengan 4 alasan keamanan:
  1. *Mencegah Serangan Spam dan DDoS*
  2. *Efisiensi Validasi Tim Penyelidik*
  3. *Optimalisasi Bandwidth dan Penyimpanan*
  4. *Mendorong Kualitas Laporan*
- **Pembersihan Tombol Redundan:** Menghilangkan tombol info manual yang tidak perlu demi antarmuka pelaporan yang lebih bersih dan intuitif.
- **Sinkronisasi Dua Lapisan:** Kuota dicek secara instan di klien (`localStorage`) dan divalidasi mutlak di server backend via Laravel `RateLimiter` (HTTP 429).

---

## 🔄 8. Sinkronisasi Data Real-time (User Publik vs Admin Dashboard)

Semua entitas data tersinkronisasi 100% mulus antara halaman publik dan panel admin melalui database Supabase PostgreSQL:
- **Pelaporan Publik:** Formulir di `/lapor` langsung tersimpan ke tabel `reports` dan detik itu juga muncul di tabel publik *"Rekapitulasi Pelaporan Publik"* serta halaman admin *"Riwayat Pelaporan"*. Perubahan status (`Pending` $\to$ `Diproses` $\to$ `Selesai`) oleh admin langsung ter-update di kedua sisi.
- **Studi Kasus & Kuis:** Bank soal kuis Cyber Guard di halaman edukasi publik bersumber dari tabel `case_studies` yang dikelola secara live di `/admin/kasus`.
- **Manajemen Akun:** Daftar staf di `/admin/akun` terhubung langsung dengan tabel `users` dengan pemisahan peran RBAC (*Super Admin* vs *Admin Basic*).
- **Proses Loading Analisis Tautan:** Tabayyun AI menyajikan animasi pemindaian 5-tahap visual berurutan (*Sanad Domain*, *Pola Judol*, *Struktur URL*, *Risiko Maysir*, *Laporan Heuristik*) sebelum kartu hasil ditampilkan.

---

## 🎯 9. Standarisasi 4 Tingkat Risiko Resmi (Unified Single Metric 0%–100%)

Pada antarmuka AI Tabayyun ([`tabayyun.blade.php`](file:///d:/cybershield/frontend/tabayyun.blade.php) & [`js/tabayyun.js`](file:///d:/cybershield/js/tabayyun.js)):
- **Skala Tunggal "Tingkat Risiko":** Menghilangkan dikotomi lama (*Tingkat Keamanan 98% vs Tingkat Bahaya 95%*) dan menyatukannya menjadi metrik tunggal universal **"Tingkat Risiko"** dari $0\%$ hingga $100\%$.
- **4 Tingkat Risiko Proporsional & Palet Warna Antarmuka:**
  1. 🟢 **Tingkat Risiko: Rendah (0%–25%) — Tema Hijau (`emerald`):**
     - Skor: `0% – 25%` (Contoh: `https://ojk.go.id/` atau `https://unmul.ac.id/` $\to$ Skor `0%`).
     - Badge & Status: `AMAN (RISIKO RENDAH)`.
     - Tampilan: Kartu berlatar hijau lembut dengan bingkai emerald halus, ikon perisai centang (`verified_user`), dan kutipan Al-Qur'an surah Al-Hujurat ayat 6 tentang prinsip dasar Tabayyun.
  2. 🟡 **Tingkat Risiko: Sedang (26%–50%) — Tema Kuning (`amber`):**
     - Skor: `26% – 50%` (Contoh: `https://komunitas-pecinta-kucing.club/forum` $\to$ Skor `40%`).
     - Badge & Status: `SEDANG (PERLU KEWASPADAAN)`.
     - Tampilan: Kartu berlatar kuning/amber hangat dengan bingkai kuning emas, ikon peringatan (`warning`), dan kutipan hadits riwayat Bukhari & Muslim tentang kehati-hatian menjauhi perkara syubhat (samar).
  3. 🟠 **Tingkat Risiko: Tinggi / Perlu Pemeriksaan (51%–84%) — Tema Oranye (`orange`):**
     - Skor: `51% – 84%` (Contoh: `https://maxwin.lc/` $\to$ Skor `69%`).
     - Badge & Status: `TINGGI (PERLU PEMERIKSAAN)`.
     - Tampilan: Kartu berlatar oranye dengan bingkai oranye tegas, ikon investigasi (`gpp_maybe`), dan peringatan deteksi kata kunci taruhan judi (maxwin, jackpot, scatter) atau anomali teknis.
  4. 🔴 **Tingkat Risiko: Berat / Kritis (85%–100%) — Tema Merah (`red`):**
     - Skor: `85% – 100%` (Contoh: `https://cuan-trading-harian-vip88.vip/deposit-slot` $\to$ Skor `95%` / `100%`).
     - Badge & Status: `BERAT (SANGAT BERBAHAYA)`.
     - Tampilan: Kartu berlatar merah pekat bahaya dengan bingkai merah marun, ikon bahaya (`gpp_bad`), kutipan surah An-Nisa ayat 29 tentang larangan memakan harta dengan jalan batil/maysir, serta rincian teknis pembajakan/defacement.
- **Penyelarasan Warna CSS Lengkap (`css/style.css`):**
  - Menyediakan kelas penyesuaian *light mode* dan *dark mode* lengkap untuk seluruh varian `data-alert`: `emerald`, `amber`, `orange`, `red`, dan `slate`.
  - Sinkronisasi otomatis antara warna kartu luar, divider, inner box, teks skor, ikon, dan tautan uji coba.

---

## 💡 10. Rangkuman Inti untuk Warga Awam Bebas Jargon IT (*Layman Summary*)

Pada komponen `#result-layman-box` di kartu hasil Tabayyun:
- **Prinsip Nol Istilah Teknis IT:** Rangkuman dilarang keras memuat istilah teknis asing seperti *TLD, URI, DNS Query, Sensor NIDS, Reverse Proxy, Subdomain, Database, ataupun Regex*. Seluruh narasi diubah ke dalam bahasa Indonesia awam yang mudah dipahami oleh orang tua maupun orang awam.
- **Pemisahan Narasi Proporsional (Bebas Tuduhan Palsu):**
  - **Tingkat Sedang Non-Judi (Contoh Forum Komunitas `.club`):** Rangkuman menjelaskan bahwa domain menggunakan akhiran murah/bebas verifikasi namun **bersih dari konten perjudian dan transaksi taruhan**. Tidak lagi menuduh sembarangan sebagai sarana slot ilegal.
  - **Tingkat Sedang Modus Finansial:** Menjelaskan adanya tawaran keuntungan instan atau arisan kilat yang belum memiliki izin pengawas keuangan resmi.
  - **Tingkat Tinggi:** Menjelaskan adanya indikasi istilah taruhan (seperti maxwin, jackpot) atau anomali reputasi yang berpotensi menjebak.
  - **Tingkat Berat:** Menegaskan bahaya langsung kehilangan uang pada sistem perjudian online atau pencurian data sandi/rekening.
  - **Tingkat Rendah / Aman:** Menegaskan situs resmi berizin sah yang aman dikunjungi dengan imbauan tetap menjaga kerahasiaan kata sandi.

---

## 🌐 11. Arsitektur Clean URL & Auto-Redirect (Bebas `.blade.php` / `.php`)

- **Pengetikan URL Bersih (Clean URLs):**
  - Seluruh halaman web dapat diketik dan dibuka langsung tanpa ekstensi berkas fisik:
    - `/tabayyun` (AI Tabayyun)
    - `/etika` (Modul Edukasi & Etika Fiqih Siber)
    - `/cyber-guard` (Simulasi & Kuis Refleks Siber)
    - `/lapor` (Portal Pengaduan Aduan Siber)
    - `/login` (Gerbang Akses Administrator)
    - `/lupa-password` (Pemulihan Kata Sandi)
    - `/admin` (Dashboard Monitoring)
    - `/admin/artikel`, `/admin/laporan`, `/admin/studi-kasus`, `/admin/integrasi-ai`, `/admin/akun`
- **Canonical Auto-Redirect (HTTP 301):**
  - Jika pengguna atau peramban membuka tautan lama yang masih membawa akhiran `.blade.php` atau `.php` (misal: `http://localhost:3000/lupa-password.blade.php`), server secara instan mengalihkan (HTTP 301) ke URL bersih (`http://localhost:3000/lupa-password`).
  - Tulisan `.php` atau `.blade.php` tidak akan pernah tampak di bilah alamat peramban.
- **Pembersihan Tautan Internal:** Seluruh tag `<a href="...">` dan navigasi javascript `window.location.href` diubah menuju rute bersih.

---

## 🗂️ 12. Manajemen Berkas Artikel Edukasi (Sinkronisasi Database & Admin)

- **Penghapusan Berkas Statis Usang (Legacy Articles):**
  - Berkas artikel statis lawas yang tidak terdaftar di database (`public.articles`) dan tidak dikelola melalui panel admin (`/admin/artikel`) telah dihapus secara permanen untuk mencegah duplikasi dan redudansi:
    1. `frontend/artikel/adab-bermedia-sosial.blade.php`
    2. `frontend/artikel/bahaya-ghibah-digital.blade.php`
    3. `frontend/artikel/fiqih-privasi-data.blade.php`
    4. `frontend/artikel/menghadapi-deepfake.blade.php`
    5. `frontend/artikel/mitigasi-phishing-apk.blade.php`
    6. `frontend/artikel/muamalah-finansial.blade.php`
- **Arsitektur Pembaca Dinamis Tunggal (`baca.blade.php`):**
  - Seluruh artikel edukasi diakses secara dinamis melalui pembaca tunggal [baca.blade.php](file:///d:/cybershield/frontend/artikel/baca.blade.php) melalui rute `/artikel/{slug}` atau `/artikel/baca.blade.php?id={id}&slug={slug}` yang langsung menarik data artikel terkini dari database / REST API (`ApiService.getArticles()`).
  - Elemen estimasi waktu baca (`⏱ 5 Menit Baca Baca`) pada header artikel telah dihapus agar tampilan informasi artikel lebih bersih, ringkas, dan fokus pada Kategori serta Tanggal Publikasi.
- **Pilar Metodologi Fiqih 4T yang Dipertahankan:**
  - Empat berkas pilar 4T tetap dipertahankan karena terdaftar resmi pada tabel database `public.prinsip_4t` dan dikelola di tab Prinsip 4T Admin:
    - [prinsip-tawaqquf.blade.php](file:///d:/cybershield/frontend/artikel/prinsip-tawaqquf.blade.php) (T1: Tahan Diri & Jeda Reflektif)
    - [prinsip-tastabbus.blade.php](file:///d:/cybershield/frontend/artikel/prinsip-tastabbus.blade.php) (T2: Telusuri Sanad & Fakta Digital)
    - [prinsip-tatsabbut.blade.php](file:///d:/cybershield/frontend/artikel/prinsip-tatsabbut.blade.php) (T3: Konfirmasi Otoritas & Fatwa)
    - [prinsip-taaqqul.blade.php](file:///d:/cybershield/frontend/artikel/prinsip-taaqqul.blade.php) (T4: Pertimbangan Maslahat & Mudharat)

---

## 🎨 13. Perbaikan Responsivitas & Lebar Form Modal Akun Pengguna (`/admin/akun`)

- **Pelebaran Modal (`max-w-xl md:max-w-2xl`):**
  - Lebar modal formulir Tambah/Edit Akun diperlebar dari `max-w-lg` (512px) menjadi `max-w-xl md:max-w-2xl` (576px–672px) agar seluruh kolom input memiliki ruang yang leluasa.
- **Normalisasi Grid Kolom Password:**
  - Memperbaiki tag penutup `</div>` grid pada baris *Role & Status Keaktifan* yang sebelumnya hilang, sehingga baris *Kata Sandi (Password)* dan *Konfirmasi Kata Sandi* kini menempati 1 baris penuh terpisah (50%–50%) dan tidak lagi terhimpit di 25% lebar modal.
  - Teks *placeholder* kata sandi (`Min. 8 karakter, huruf, angka, simbol` dan `Ulangi kata sandi...`) kini tampil utuh dan jelas tanpa terpotong.
- **Fitur Hide / Unhide Kata Sandi (Ikon Mata):**
  - Ditambahkan tombol toggle interaktif dengan ikon mata Bootstrap Icons (`bi bi-eye` / `bi bi-eye-slash`) pada input *Kata Sandi (Password)* dan *Konfirmasi Kata Sandi*.
  - Dilengkapi fungsi `togglePasswordVisibility(inputId, iconId)` untuk beralih antara tipe `password` dan `text`.
  - Fungsi otomatis me-reset visibilitas kembali ke mode tersembunyi (`password` & `bi bi-eye`) setiap kali modal dibuka atau ditutup demi keamanan kredensial.

---

## 🛡️ 14. Keamanan Sesi & Kebijakan Penguncian Akun (*Inactivity Timeout & Login Throttling*)

- **Preservasi Sesi saat Reload Halaman:**
  - Menghapus pembersihan sesi destruktif pada event `pagehide` di [admin-guard.js](file:///d:/cybershield/js/admin-guard.js), sehingga memuat ulang halaman (*reload* / refresh F5) **tidak lagi meminta login ulang**. Sesi tetap aktif dan stabil.
- **Tenggat Waktu Ketidakaktifan (*Inactivity Timeout* 5 Menit):**
  - Sistem memantau interaksi nyata pengguna secara kontinu melalui event: `mousemove`, `mousedown`, `keydown`, `touchstart`, `scroll`, dan `click`.
  - Timestamp aktivitas terakhir dicatat secara teroptimasi (*throttled* setiap 5 detik) di `localStorage['aswaja_admin_last_activity']`.
  - Jika pengguna tidak beraktivitas selama **5 menit (300 detik)**, sistem otomatis menghapus sesi aktif, mengalihkan pengguna ke halaman login (`/login?reason=timeout`), dan menampilkan banner peringatan:
    > *"Sesi Anda telah berakhir karena tidak ada aktivitas selama 5 menit. Silakan login kembali untuk memverifikasi identitas Anda."*
- **Kebijakan Penguncian Akun (*Account Lockout Policy* / Anti-Brute Force):**
  - Setiap kali terjadi kesalahan kata sandi atau username, sistem menampilkan sisa kesempatan login (*"Sisa percobaan: 2x / 1x"*).
  - Jika terjadi **3 kali kesalahan berturut-turut**, akun otomatis terkunci dan mengalami *cooldown* berjenjang:
    1. **Tingkat 1:** Terkunci selama **1 menit** (60 detik).
    2. **Tingkat 2:** Terkunci selama **3 menit** (180 detik).
    3. **Tingkat 3:** Terkunci selama **5 menit** (300 detik).
    4. **Tingkat 4:** Terkunci selama **15 menit** (900 detik).
    5. **Tingkat 5 & seterusnya:** Terkunci selama **1 jam** (3600 detik).
  - Selama masa cooldown, tombol login dinonaktifkan, kolom input dikunci, dan pengatur waktu mundur (*live countdown timer*) berdetik setiap detik menampilkan sisa waktu tunggu.
  - Setelah waktu cooldown habis, tombol login otomatis aktif kembali. Ketika login berhasil, seluruh riwayat kegagalan dan tier lockout di-reset menjadi 0.

---

## 🖥️ 15. Normalisasi Scrollbar (Menggunakan Scrollbar Bawaan Browser)

- **Penghapusan Kustomisasi Scrollbar:**
  - Menghapus seluruh aturan CSS kustom scrollbar (`::-webkit-scrollbar`, `::-webkit-scrollbar-track`, `::-webkit-scrollbar-thumb`, serta `scrollbar-color`) pada [style.css](file:///d:/cybershield/css/style.css).
  - Tampilan bilah gulir (*scrollbar*) kini 100% dikembalikan ke tampilan bawaan alami sistem operasi dan peramban (*native browser scrollbar* bawaan Chrome / Edge / Firefox) sehingga navigasi lebih familiar dan bersih.

---

## 👥 16. Dropdown List Dinamis Admin Penangani di Modal Laporan (`/admin/laporan`)

- **Transformasi Input Statis Menjadi Dropdown Dinamis:**
  - Pada modal *Detail & Tindak Lanjut Laporan* di [`frontend/admin/laporan.blade.php`](file:///d:/cybershield/frontend/admin/laporan.blade.php), kolom **Admin Penangani** yang sebelumnya berupa kotak input teks statis/readonly (`Belum Ditugaskan`) diubah menjadi elemen dropdown interaktif (`<select id="actionAdminAssigned">`).
- **Sinkronisasi Otomatis dengan Basis Data Pengguna / Admin:**
  - Mengambil daftar akun admin aktif secara dinamis dari API backend (`/api/admin/users`) atau dari repositori akun admin Supabase (`public.users`).
  - Opsi dropdown menyediakan:
    - `""` (Nilai kosong: *Belum Ditugaskan / Unassigned*)
    - Daftar seluruh staf admin aktif dengan format: `Nama Staf (Role)`, contoh: *Admin Utama (Super Admin)*, *Muhammad Ali Reza (Admin Basic)*.
- **Pengisian Nilai Otomatis (*Pre-Selection*):**
  - Saat modal dibuka melalui `openActionModal(id)`, sistem otomatis mencocokkan nama petugas penangan yang tercatat pada tiket laporan (`report.admin_name` atau `report.assigned_to`) dan mengaktifkan opsi tersebut.
- **Penyimpanan Terpadu:**
  - Saat formulir disimpan (`submitReportAction()`), data penugasan admin diteruskan ke endpoint update laporan (`PUT /api/admin/reports/{id}`) dan dicatat pada audit log aktivitas, memastikan akuntabilitas penanganan aduan masyarakat.

---

## 📚 17. Kategori Terpadu Judol (Umum, IT, & Agama) & Edit Artikel Cepat dari Kartu 4T (`/admin/artikel`)

- **Standarisasi 3 Rumpun Kategori Artikel Judol:**
  Kategori artikel edukasi di [`frontend/admin/artikel.blade.php`](file:///d:/cybershield/frontend/admin/artikel.blade.php) distandarisasi ke dalam 3 rumpun ilmiah yang relevan dengan penanggulangan judi online dan investasi ilegal:
  1. **Secara Umum (Sosial & Fenomena):**
     - `Dampak Sosial & Jeratan Finansial` (modus jeratan utang, pinjol ilegal, kehancuran ekonomi keluarga).
     - `Modus & Fenomena Judol Umum` (literasi bahaya judi online bagi masyarakat luas).
  2. **Secara IT (Forensik Digital & Teknik Penyamaran Siber):**
     - `Forensik Digital & Investigasi Judol` (analisis tautan, metadata DNS, WHOIS, cloaking).
     - `Teknik Penyamaran & Phishing Cyber` (typosquatting, web defacement, open redirect).
  3. **Secara Agama (Kajian Fatwa & Fiqih Siber Aswaja):**
     - `Kajian Fatwa & Maqashid Syariah` (hukum maysir digital, fatwa MUI/Bahtsul Masail).
     - `Metodologi Tabayyun & Kaidah 4T` (Tawaqquf, Tastabbus, Tatsabbut, Ta'aqqul).
- **Integrasi Tombol Aksi Ganda pada Kartu Prinsip 4T:**
  - Setiap kartu dari 4 Pilar Kaidah Fiqih Medsos (T1 *Tawaqquf*, T2 *Tastabbus*, T3 *Tatsabbut*, T4 *Ta'aqqul*) kini dilengkapi dua tombol aksi interaktif:
    - **Tombol "Edit Kaidah":** Membuka modal ringkas untuk mengubah judul, subjudul, dan deskripsi pilar (`openEdit4TModal(id)`).
    - **Tombol "Edit Artikel":** Membuka modal editor artikel lengkap (`openEditArticleForPillar(slug)`) dengan memuat konten artikel pilar terkait (`prinsip-tawaqquf`, `prinsip-tastabbus`, `prinsip-tatsabbut`, atau `prinsip-taaqqul`) secara langsung tanpa perlu mencarinya secara manual di tabel.

---

## 🌲 18. Penyelarasan Tema Light Mode Footer (Gradien Hijau Emerald Deep)

- **Harmonisasi Visual Light Mode Footer:**
  - Warna latar belakang footer halaman publik sebelumnya tampak gelap kebiruan/navy, kurang selaras dengan identitas hijau institusional Aswaja CyberShield.
  - Footer dirombak menjadi gradien hijau zamrud gelap (*Deep Emerald Gradient*) yang kaya:
    ```html
    <footer class="bg-gradient-to-b from-[#064e3b] via-[#022c22] to-[#011a14] dark:bg-slate-950 text-emerald-100/90 dark:text-slate-300 border-t border-emerald-900/40 dark:border-slate-800 transition-colors duration-300">
    ```
  - Warna teks tautan, ikon, dan garis pemisah footer disesuaikan menggunakan aksen `emerald-200/80` dengan hover `text-emerald-400` sehingga memiliki kontras tinggi dan kenyamanan visual prima.
- **Konsistensi Seragam di Seluruh Halaman:**
  - Diterapkan seragam di:
    - [`frontend/index.blade.php`](file:///d:/cybershield/frontend/index.blade.php) (Beranda)
    - [`frontend/tabayyun.blade.php`](file:///d:/cybershield/frontend/tabayyun.blade.php) (AI Tabayyun)
    - [`frontend/etika.blade.php`](file:///d:/cybershield/frontend/etika.blade.php) (Etika Fiqih Medsos)
    - [`frontend/cyber-guard.blade.php`](file:///d:/cybershield/frontend/cyber-guard.blade.php) (Cyber Guard Test)
    - [`frontend/lapor.blade.php`](file:///d:/cybershield/frontend/lapor.blade.php) (Portal Lapor)
    - Seluruh artikel edukasi di [`frontend/artikel/*.blade.php`](file:///d:/cybershield/frontend/artikel).

---

## ⚡ 19. Perbaikan Loading Zero-Latency & Perbaikan Sintaks Artikel Admin

- **Diagnosa & Pembersihan Rogue Syntax Error:**
  - Sebelumnya pada [`frontend/admin/artikel.blade.php`](file:///d:/cybershield/frontend/artikel.blade.php) baris 1241–1243 terdapat kurung kurawal ganda penutup sisa refactor (`} } };`) yang memicu fatal error parser browser (`SyntaxError: missing ) after argument list`).
  - Error tersebut menghentikan event `DOMContentLoaded`, menyebabkan tabel artikel mengalami *infinite loading spinner* ("Memuat daftar artikel...") dan kartu 4T gagal muncul.
  - Blok sintaks telah diperbaiki dan diverifikasi bebas error menggunakan engine validasi Node.js VM.
- **Strategi Zero-Latency Instant Fallback Rendering:**
  - Mengimplementasikan pemuatan data berbasis *offline-first / instant-render*:
    - Fungsi `getFallbackArticles()` dan `getFallback4T()` langsung merender data artikel dan kartu pilar seketika (0 ms) begitu skrip dieksekusi tanpa menunggu panggilan jaringan.
    - Sinkronisasi API Supabase dijalankan di latar belakang (*background refresh*) menggunakan `Promise.allSettled` dengan pengaman timeout 3.5 detik (`withTimeout(..., 3500)`).
  - Jika koneksi API Supabase berhasil, UI otomatis ter-update mulus tanpa flicker (*seamless re-render*); jika jaringan lambat/offline, pengguna tetap dapat bekerja tanpa hambatan.

---

## 🖱️ 20. Interaktivitas Penuh & Responsivitas Admin Dashboard (Semua Elemen Dapat Diklik)

Seluruh komponen kartu statistik, status breakdown, tabel aduan, dan riwayat aktivitas pada [`frontend/admin/index.blade.php`](file:///d:/cybershield/frontend/admin/index.blade.php) telah dibuat interaktif, responsif, dan dapat diklik (*clickable*):

1. **4 Kartu Metrik Utama (KPI Stat Cards):**
   - **Total Artikel:** Dilengkapi efek hover lift, kursor pointer, tombol badge *"Kelola &rarr;"*, dan klik langsung melompat ke [`/admin/artikel`](file:///d:/cybershield/frontend/admin/artikel.blade.php).
   - **Laporan Masuk:** Menampilkan counter aduan berstatus Pending dengan badge *"Tinjau &rarr;"*, klik langsung melompat ke [`/admin/laporan?status=Pending`](file:///d:/cybershield/frontend/admin/laporan.blade.php).
   - **Akun Aktif:** Menampilkan jumlah pengelola sistem dengan badge *"Kelola &rarr;"*, klik langsung melompat ke [`/admin/akun`](file:///d:/cybershield/frontend/admin/akun.blade.php).
   - **Studi Kasus:** Menampilkan jumlah skenario kuis aktif dengan badge *"Kelola &rarr;"*, klik langsung melompat ke [`/admin/studi-kasus`](file:///d:/cybershield/frontend/admin/studi-kasus.blade.php).
2. **Status Breakdown Laporan:**
   - Bar persentase status (*Selesai, Diproses, Pending*) memiliki interaksi klik yang langsung mengarahkan admin ke halaman Laporan terfilter sesuai status yang dipilih.
3. **Baris Tabel Aduan Terbaru (*Clickable Rows*):**
   - Setiap baris laporan aduan masyarakat pada tabel dashboard memiliki styling interaktif `cursor-pointer hover:bg-emerald-500/5`.
   - Mengklik baris laporan langsung membuka modal pratinjau cepat (*quick detail modal*) `showDashboardReportDetail(id)` untuk inspeksi instan tanpa harus berpindah halaman.
4. **Activity Feed (Aktivitas Sistem):**
   - Setiap item log aktivitas dapat diklik untuk melompat langsung ke modul terkait berdasarkan kategori aksi (*Laporan*, *Artikel*, *Akun*, *Studi Kasus*).

---

## 🔗 21. Absolute Routing Admin & Pencegahan Fallback ke Halaman Publik

- **Masalah Navigasi Relatif:**
  - Sebelumnya, navigasi admin menggunakan tautan relatif (contoh: `href="artikel.blade.php"` atau `window.location.href = 'artikel.blade.php'`).
  - Ketika admin berada pada rute `/admin` (tanpa garis miring penutup), peramban mengartikan tautan relatif sebagai `/artikel.blade.php` atau `/artikel`. Karena rute tersebut tidak terdaftar di router publik, server mengalihkan pengguna kembali ke halaman beranda publik (`frontend/index.blade.php`).
- **Standardisasi Rute Absolut Resmi:**
  - Seluruh tautan navigasi di seluruh 6 berkas admin diubah menjadi rute absolut bersih (*clean absolute paths*):
    - `/admin` (Dashboard)
    - `/admin/artikel` (Kelola Artikel & Pilar 4T)
    - `/admin/laporan` (Kelola & Verifikasi Laporan Aduan)
    - `/admin/studi-kasus` (Kelola Skenario Cyber Guard)
    - `/admin/akun` (Kelola Akun & Hak Akses Staf)
    - `/admin/integrasi-ai` (Status Sensor AI & Suricata)
- **Pengalihan Otomatis di Controller (`index.php`):**
  - Pada [`index.php`](file:///d:/cybershield/index.php), ditambahkan aturan penanganan rute jika ada permintaan yang masuk ke `/artikel`, `/laporan`, `/akun`, `/studi-kasus`, atau `/integrasi-ai`, server secara otomatis menerbitkan respon **HTTP 302 Redirect** menuju rute `/admin/*` yang sah.

---

## 📑 22. Restrukturisasi Urutan Menu Sidebar Admin (Kelola Akun di Posisi Paling Bawah)

- **Hierarki Navigasi Berbasis Alur Kerja Operasional:**
  Urutan menu navigasi sidebar admin ditata ulang secara terstruktur untuk memisahkan menu operasional harian (konten & aduan masyarakat) dengan menu administratif hak akses sistem:
  ```
  1. 🧭 Dashboard          (/admin)
  2. 📄 Artikel            (/admin/artikel)
  3. 🛡️ Laporan            (/admin/laporan)
  4. 📋 Studi Kasus        (/admin/studi-kasus)
  5. 👥 Kelola Akun        (/admin/akun)  <-- Berada di posisi paling bawah
  ```
- **Konsistensi Lintas Berkas:**
  Penataan urutan ini diterapkan seragam pada seluruh template panel admin:
  - [`frontend/admin/index.blade.php`](file:///d:/cybershield/frontend/admin/index.blade.php)
  - [`frontend/admin/artikel.blade.php`](file:///d:/cybershield/frontend/admin/artikel.blade.php)
  - [`frontend/admin/laporan.blade.php`](file:///d:/cybershield/frontend/admin/laporan.blade.php)
  - [`frontend/admin/studi-kasus.blade.php`](file:///d:/cybershield/frontend/admin/studi-kasus.blade.php)
  - [`frontend/admin/akun.blade.php`](file:///d:/cybershield/frontend/admin/akun.blade.php)
  - [`frontend/admin/integrasi-ai.blade.php`](file:///d:/cybershield/frontend/admin/integrasi-ai.blade.php)

---

## ⚡ 23. Optimasi Pengiriman Aset Frontend & Vercel Edge CDN

- **Penyajian Aset Tanpa Beban Komputasi Serverless:**
  - Konfigurasi [`vercel.json`](file:///d:/cybershield/vercel.json) memetakan direktori aset statis (`css/`, `js/`, `images/`, `logo-shield.png`, `favicon.svg`) langsung ke Vercel Edge CDN.
  - Setiap aset dilayani secara instan tanpa mengeksekusi runtime PHP serverless, memangkas latensi pemuatan halaman dan menghemat kuota serverless secara optimal.
- **Dukungan Clean URL Serverless:**
  - Permintaan halaman frontend publik dan admin (`/`, `/tabayyun`, `/etika`, `/cyber-guard`, `/lapor`, `/login`, `/admin/...`, `/artikel/...`) diteruskan ke [`api/index.php`](file:///d:/cybershield/api/index.php) yang mengaktifkan smart router [`index.php`](file:///d:/cybershield/index.php) secara terisolasi di memori sementara.

---

## 🏷️ 24. Standar Komentar Kode Ringkas (Header-Only Standard)

- **Kode Bersih Tanpa Paragraf Penjelasan:**
  - Seluruh berkas frontend (JavaScript, Blade template, dan konfigurasi) menerapkan gaya komentar judul ringkas (contoh: `// Theme Anti-FOUC Script`, `// Tailwind CSS Play CDN`, `// 1. Assets`, `// 2. Static Files`).
  - Dokumentasi naratif, panduan pengguna, dan spesifikasi arsitektur dialihkan secara terpusat ke berkas markdown proyek agar performa parsing browser dan keterbacaan kode tetap maksimal.







