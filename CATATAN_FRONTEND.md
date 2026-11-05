# Catatan Lengkap Arsitektur & Spesifikasi Frontend
## Platform Aswaja CyberShield (Antarmuka Publik, Panel Admin, JavaScript Modular & Design System)

Dokumen ini merupakan **gabungan terpadu seluruh catatan teknis Frontend** pada platform **Aswaja CyberShield**, mencakup struktur halaman Blade PHP (`.blade.php`), modularitas skrip JavaScript (`js/`), mekanisme proteksi sesi ketat admin (`js/admin-guard.js`), sistem desain Tailwind CSS Emerald, serta standar keamanan sisi klien.

---

## 1. Arsitektur & Karakteristik Frontend

Frontend **Aswaja CyberShield** dibangun dengan pendekatan **Modular Blade PHP + Vanilla JavaScript (ES6+)** dan **Tailwind CSS Play CDN**:

- **Format Tampilan & Clean URLs (Tanpa `.php`):** Menggunakan berkas `.blade.php` yang terorganisasi di dalam direktori `frontend/`, `frontend/artikel/`, dan `frontend/admin/`, namun disajikan ke pengguna dengan **URL Bersih (*Clean URLs* tanpa ekstensi `.blade.php` atau `.php`)** melalui kombinasi [`vercel.json`](file:///d:/aswajaCyber/vercel.json), [`.htaccess`](file:///d:/aswajaCyber/.htaccess), [`js/navbar.js`](file:///d:/aswajaCyber/js/navbar.js), dan [`js/admin-guard.js`](file:///d:/aswajaCyber/js/admin-guard.js).
- **Komunikasi API Terpusat:** Seluruh pertukaran data dengan Backend Laravel dikelola oleh satu layer layanan di [`js/api.js`](file:///d:/aswajaCyber/js/api.js) (`ApiService` dan `AuthService`).
- **Responsif & Ringan (Light Mode & Dark Mode):** Menggunakan konfigurasi *Zero-Build* Tailwind CSS dengan waktu muat awal (*First Contentful Paint*) di bawah `0.8 detik` serta sinkronisasi tema terang/gelap penuh.

---

## 2. Struktur Modul Antarmuka Publik & Pemetaan Clean URLs (`frontend/`)

| No | Halaman Publik | URL Bersih (Clean URL) | Berkas Blade | Skrip JS Terkait | Fungsi Utama |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **Beranda Utama** | **`/`** | [`frontend/index.blade.php`](file:///d:/aswajaCyber/frontend/index.blade.php) | [`js/home.js`](file:///d:/aswajaCyber/js/home.js) | Hero section interaktif, pengenalan pilar *Recognize-Learn-Act*, statistik ringkas, dan navigasi cepat. |
| 2 | **AI Tabayyun Link Checker** | **`/tabayyun`** | [`frontend/tabayyun.blade.php`](file:///d:/aswajaCyber/frontend/tabayyun.blade.php) | [`js/tabayyun.js`](file:///d:/aswajaCyber/js/tabayyun.js) | Pemeriksa tautan Judi Online & Investasi Menyesatkan berbasis Suricata NIDS + Parallel Dual AI (Gemini & DeepSeek). |
| 3 | **Etika & Fiqih Siber (4T)** | **`/etika`** | [`frontend/etika.blade.php`](file:///d:/aswajaCyber/frontend/etika.blade.php) | [`js/etika.js`](file:///d:/aswajaCyber/js/etika.js) | Modul literasi **Prinsip 4T** (*Tawaqquf, Tastabbus, Tatsabbut, Ta'aqqul*) yang tersinkronisasi dinamis dari panel admin, dilengkapi pencarian dan filter 6 kajian fiqih digital. |
| 4 | **Cyber Guard Interactive Test** | **`/cyber-guard`** | [`frontend/cyber-guard.blade.php`](file:///d:/aswajaCyber/frontend/cyber-guard.blade.php) | [`js/cyber-guard.js`](file:///d:/aswajaCyber/js/cyber-guard.js) | Simulasi 5 skenario rekayasa sosial interaktif dengan pembahasan etis/teknis seketika dan kalkulasi skor literasi (0–100). |
| 5 | **Portal Lapor Siber** | **`/lapor`** | [`frontend/lapor.blade.php`](file:///d:/aswajaCyber/frontend/lapor.blade.php) | [`js/lapor.js`](file:///d:/aswajaCyber/js/lapor.js) | Formulir pengaduan tautan/rekening penipu dengan sanitasi PHP `clean_input()`, *drag-and-drop* bukti gambar, opsi anonim, dan penerbitan tiket otomatis (`LPRxxx`). |
| 6 | **10 Halaman Kajian Tematik** | **`/artikel/:slug`** | [`frontend/artikel/*.blade.php`](file:///d:/aswajaCyber/frontend/artikel) | [`js/navbar.js`](file:///d:/aswajaCyber/js/navbar.js) | Halaman bacaan mendalam untuk 4 Kaidah 4T dan 6 topik Fiqih Siber (Adab Medsos, Ghibah Digital, Privasi Data, Mitigasi APK, Deepfake, dan Muamalah Finansial). |

---

## 3. Rincian Teknis Modul Utama Frontend

### 3.1. Modul AI Tabayyun (`frontend/tabayyun.blade.php` & `js/tabayyun.js`)
- **Perlakuan Input Tidak Tepercaya (*Zero Remote Execution*):** URL yang dimasukkan pengguna tidak pernah dibuka atau dirender di dalam `<iframe>`, melainkan dikirim sebagai teks aman ke `ApiService.analyzeUrl(url)`.
- **Visualisasi Hasil Multi-Engine & Warna Adaptif (`Light Mode` & `Dark Mode`):**
  - Kartu hasil analisis (`#tabayyun-result-card`) menggunakan atribut dinamis `data-alert` yang disetel oleh fungsi `applyColorScheme()` dan `resolveAlertColor()` di [`js/tabayyun.js`](file:///d:/aswajaCyber/js/tabayyun.js#L119-L295) serta aturan CSS di [`css/style.css`](file:///d:/aswajaCyber/css/style.css#L4391-L4601) agar warna latar, garis tepi (*border*), *badge*, kotak skor, kotak URL, daftar temuan, dan kotak kutipan selalu konsisten sesuai vonis analisis di **Light Mode** maupun **Dark Mode**:
    - **Merah (`data-alert="red"`):** Terindikasi Judi Online / Investasi Menyesatkan (latar `#fef2f2` di Light Mode, `#2d1619` dengan teks `#fecaca` di Dark Mode).
    - **Kuning (`data-alert="amber"`):** Mencurigakan / Perlu Indikator Pendukung / Disparitas AI (latar `#fffbeb` di Light Mode, `#2C2820` dengan teks `#fde68a` di Dark Mode).
    - **Hijau (`data-alert="emerald"`):** Aman / Normal Terverifikasi (latar `#ecfdf5` di Light Mode, `#122b24` dengan teks `#a7f3d0` di Dark Mode).
  - Menampilkan rincian bukti dari **Struktur URL (3-Tier Matching)**, **Sensor Jaringan Suricata NIDS (`SID`)**, serta perbandingan berdampingan antara **Google Gemini** dan **DeepSeek AI**.
  - Menyediakan tombol **Salin Ringkasan** dan tombol **Laporkan Tautan** yang langsung meneruskan URL ke `/lapor?url=...`.

### 3.2. Modul Cyber Guard Interactive Test (`frontend/cyber-guard.blade.php` & `js/cyber-guard.js`)
- **Kalkulasi Skor Kuis:**
  $$\text{Skor Akhir} = \left( \frac{\sum \text{Jawaban Benar}}{\text{Total Skenario}} \right) \times 100$$
- **Auto-Scroll Adaptif:** Setiap kali pengguna berpindah soal, layar bergulir halus ke atas dengan memperhitungkan tinggi *sticky navbar* agar pertanyaan baru langsung terlihat penuh.

### 3.3. Modul Portal Lapor Siber (`frontend/lapor.blade.php` & `js/lapor.js`)
- **Sanitasi Server-Side & Client-Side:** Menggunakan fungsi PHP `clean_input()` (`htmlspecialchars`, `strip_tags`, `trim`) pada parameter URL bawaan serta sanitasi DOM `textContent` di JavaScript.
- **Dropzone Bukti Screenshot:** Mendukung klik maupun *drag-and-drop* berkas gambar dengan validasi ukuran dan pratinjau nama berkas.

---

## 4. Panel Administrator & Keamanan Sesi Ketat (`frontend/admin/` & `js/admin-guard.js`)

### 4.1. Daftar Halaman Panel Admin & Rute Clean URL
1. **Login Admin (`https://aswajacybershield.vercel.app/login` — [`login.blade.php`](file:///d:/aswajaCyber/frontend/admin/login.blade.php)):** Gerbang masuk staf tanpa nilai *default* tersimpan (`autocomplete="off"`).
   - Akun **Super Admin**: Username `admin` | Password `Aswaja2026!`
   - Akun **Admin Basic**: Username `analis` | Password `Aswaja2026!`
2. **Dashboard KPI (`/admin` — [`index.blade.php`](file:///d:/aswajaCyber/frontend/admin/index.blade.php)):** Statistik jumlah artikel, laporan masuk, diproses, selesai, studi kasus, dan umpan aktivitas terbaru.
3. **Kelola Artikel & Kaidah 4T (`/admin/artikel` — [`artikel.blade.php`](file:///d:/aswajaCyber/frontend/admin/artikel.blade.php)):** CRUD artikel edukasi (dengan multi-image upload preview) serta editor sinkronisasi Kaidah 4T (`T1`–`T4`).
4. **Verifikasi Laporan Warga (`/admin/laporan` — [`laporan.blade.php`](file:///d:/aswajaCyber/frontend/admin/laporan.blade.php)):** Triase tiket aduan (`Masuk`, `Diproses`, `Selesai`, `Ditolak`), input catatan verifikasi, dan penugasan analis.
5. **Kelola Studi Kasus (`/admin/studi-kasus` — [`studi-kasus.blade.php`](file:///d:/aswajaCyber/frontend/admin/studi-kasus.blade.php)):** CRUD skenario soal kuis Cyber Guard beserta opsi jawaban A–D dan pembahasan fiqih.
6. **Integrasi Dual AI (`/admin/integrasi-ai` — [`integrasi-ai.blade.php`](file:///d:/aswajaCyber/frontend/admin/integrasi-ai.blade.php)):** Pemantauan status koneksi Google Gemini, DeepSeek AI, dan sensor Suricata beserta tombol uji koneksi (*health-check probe*).
7. **Kelola Akun Staf (`/admin/akun` — [`akun.blade.php`](file:///d:/aswajaCyber/frontend/admin/akun.blade.php)):** Manajemen akun administrator berbasis peran (*Super Admin* vs *Admin Basic*).

### 4.2. Sistem Routing Clean URL Tanpa Ekstensi `.php`
Agar ekstensi `.blade.php` maupun `.php` tidak pernah terlihat di *address bar* browser maupun saat mengarahkan kursor ke tautan:
1. **Server Rewrites di Vercel ([`vercel.json`](file:///d:/aswajaCyber/vercel.json)):** Memetakan rute bersih (`/`, `/login`, `/tabayyun`, `/etika`, `/cyber-guard`, `/lapor`, `/artikel/:slug`, `/admin`, dan `/admin/:page`) langsung ke berkas `.blade.php` di folder `frontend/`.
2. **Server Rewrites di Lokal Apache/Laragon/XAMPP ([`.htaccess`](file:///d:/aswajaCyber/.htaccess)):** Menggunakan `mod_rewrite` untuk mengarahkan URL tanpa ekstensi ke berkas fisik di `frontend/`.
3. **Client-Side URL Cleaner & Link Rewriter ([`js/navbar.js`](file:///d:/aswajaCyber/js/navbar.js#L1-L94) & [`js/admin-guard.js`](file:///d:/aswajaCyber/js/admin-guard.js#L1-L85)):**
   - Menjalankan `window.history.replaceState()` seketika saat halaman dimuat untuk menghapus `/frontend`, `.blade.php`, dan `.php` dari *address bar* tanpa melakukan *reload*.
   - Memasang `MutationObserver` untuk mengubah seluruh atribut `<a href="...blade.php">` (termasuk kartu artikel yang dirender secara dinamis) menjadi URL bersih.

### 4.2. Proteksi Sesi Ketat & Zero-History Guard ([`js/admin-guard.js`](file:///d:/aswajaCyber/js/admin-guard.js))
Seluruh 6 halaman internal admin memasang [`js/admin-guard.js`](file:///d:/aswajaCyber/js/admin-guard.js) di dalam tag `<head>` dengan mekanisme keamanan ketat:
1. **Zero Persistent Login History:** Token autentikasi hanya disimpan sementara di `sessionStorage` (bukan `localStorage`), dan seluruh jejak login lama di `localStorage` otomatis dibersihkan.
2. **Wajib Login Ulang Saat *Reload* atau Klik Panah *Back/Forward* Browser:**
   - Mendeteksi tipe navigasi browser (`performance.getEntriesByType('navigation')[0].type === 'reload'` atau `'back_forward'`) serta event `pageshow` (*bfcache*).
   - Begitu halaman admin di-*reload* atau pengguna keluar lalu menekan tombol panah *Back/Forward* browser, sesi langsung dihapus dan pengguna diarahkan paksa (`window.location.replace`) kembali ke halaman `login.blade.php`.
3. **One-Time Navigation Lock:** Perpindahan antar-menu di dalam sidebar admin menggunakan kunci satu-kali-pakai (`aswaja_admin_nav_ok`) yang langsung hangus begitu halaman selesai dimuat.

---

## 5. Design System & Palet Warna Tailwind CSS (`css/style.css`)

Frontend menggunakan identitas visual **Aswaja Emerald** yang konsisten di seluruh komponen:

- **Warna Utama (Brand Primary):** Emerald (`#10b981` / `#059669` / `#047857`).
- **Warna Indikator Risiko:**
  - Aman (`emerald-500` `#10b981`)
  - Waspada / Mencurigakan (`amber-500` `#f59e0b`)
  - Bahaya Tinggi / Terindikasi Judol & Investasi Bodong (`red-500` `#ef4444`)
- **Tipografi:** *Plus Jakarta Sans* (Heading/Display) & *Inter* (Body/UI).
- **Efek Interaktif ([`css/style.css`](file:///d:/aswajaCyber/css/style.css) & [`js/scroll-reveal.js`](file:///d:/aswajaCyber/js/scroll-reveal.js)):**
  - `.reveal-on-scroll`: Animasi masuk saat elemen digulir menggunakan `IntersectionObserver`.
  - `.card-hover-effect`: Elevasi kartu halus saat kursor diarahkan.
  - Pencegahan XSS menyeluruh: seluruh teks input dinamis dirender menggunakan `textContent` atau fungsi sanitasi `escapeHtml()`.
