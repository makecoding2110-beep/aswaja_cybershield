# ⚙️ Catatan Lengkap Arsitektur & Spesifikasi Backend
## Platform Aswaja CyberShield (Laravel REST API + Suricata NIDS + UrlThreatAnalyzer + Google Gemini AI)

Dokumen ini merupakan **gabungan terpadu seluruh catatan teknis Backend** (mencakup hasil audit frontend-to-backend, perancangan RESTful API Laravel, spesifikasi Suricata NIDS & `eve.json`, deteksi 3 teknik penyusupan tautan judol via `UrlThreatAnalyzer`, orkestrasi Google Gemini AI, sistem *3-Tier Keyword Matching*, rumus matematis penilaian risiko 2-state (Aman/Berbahaya), koneksi live Supabase PostgreSQL, hingga unified single-server router `index.php`).

---

## 📌 1. Gambaran Umum Arsitektur Backend

Sistem backend **Aswaja CyberShield** dibangun menggunakan **PHP 8.5.12 & Framework Laravel** yang terintegrasi dengan **Sensor Jaringan Suricata NIDS**, **UrlThreatAnalyzer**, dan **Google Gemini AI**:

- **Backend Runtime:** PHP 8.5.12 (ZTS Visual C++ 2022 x64 dengan Zend OPcache) & Laravel 11.x RESTful API.
- **Database Utama:** Supabase PostgreSQL 17.11 (Session Pooler IPv4 `aws-0-ap-southeast-1.pooler.supabase.com:5432`).
- **Prinsip Keamanan Zero-Fetch:** Analisis tautan dilakukan murni berbasis pengujian teks URL tanpa pernah membuka, mengunduh, atau mengeksekusi konten dari tautan target di server.
- **Unified Front Controller (`index.php`):**
  Menggunakan file [`index.php`](file:///d:/cybershield/index.php) di root proyek sebagai pengontrol tunggal yang melayani rute frontend halaman publik/admin (`/`, `/tabayyun`, `/admin/...`) sekaligus meneruskan panggilan `/api/*` langsung ke bootstrap Laravel (`backend/public/index.php`). Cukup menjalankan satu perintah:
  ```powershell
  php -S localhost:3000 index.php
  ```
  Seluruh aplikasi frontend dan REST API backend otomatis aktif bersamaan tanpa kendala beda port maupun isu CORS.

- **Modularitas Layanan (Service Utama):**
  1. [`UrlValidationService.php`](file:///d:/cybershield/backend/app/Services/UrlValidationService.php): Validasi skema, normalisasi URL, proteksi SSRF, deteksi anomali struktur domain, dan **Pencocokan Kata Kunci 3 Tingkat (*3-Tier Keyword Matching*)**.
  2. [`UrlThreatAnalyzer.php`](file:///d:/cybershield/backend/app/Services/UrlThreatAnalyzer.php): Analis ancaman URL tingkat lanjut yang mendeteksi **6 Teknik Manipulasi & Penyusupan Tautan**: *Web Defacement & Subdomain Hijacking*, *Open Redirect*, *Typosquatting/Homoglyph*, *Kamuflase Finansial/Trading Ilegal*, *URL Masking & Domain Rebouncer* (`share.google`/shortener), dan *SEO Cloaking* via mesin *Dual-Persona Fetching*.
  3. [`SuricataIntegrationService.php`](file:///d:/cybershield/backend/app/Services/SuricataIntegrationService.php): Ingestion dan parsing log telemetri jaringan [`eve.json`](file:///d:/cybershield/backend/storage/logs/suricata/eve.json) (DNS Query, TLS SNI, HTTP URI, dan Alert Signature) serta mapping SID ke nama teknik ancaman.
  4. [`SuricataService.php`](file:///d:/cybershield/backend/app/Services/SuricataService.php): Mesin inspeksi signature deterministik (`SID: 1000000` – `1000008` serta SID baru `1000101` – `1000103`) yang selaras dengan [`suricata.rules`](file:///d:/cybershield/suricata.rules) dan [`infra/suricata/rules/aswaja-local.rules`](file:///d:/cybershield/infra/suricata/rules/aswaja-local.rules).
  5. [`GeminiService.php`](file:///d:/cybershield/backend/app/Services/GeminiService.php): Integrasi resmi Google Gemini AI (`gemini-3.5-flash-lite`) dengan prompt injection guard, evaluasi semantik, deteksi leetspeak, penilaian 3 teknik penyusupan judol, dan pemetaan konteks Fiqih 4T.
  6. [`AiComparisonService.php`](file:///d:/cybershield/backend/app/Services/AiComparisonService.php): Verifikasi dan standardisasi keluaran analisis AI dan konsistensi temuan indikator.
  7. [`RiskAssessmentService.php`](file:///d:/cybershield/backend/app/Services/RiskAssessmentService.php): Mesin korelasi matriks evaluasi dan kalkulasi rumus *Weighted Multi-Sensor Risk Synthesis* menuju sistem skor 2-state (Aman atau Berbahaya).
  8. [`ScanHistoryService.php`](file:///d:/cybershield/backend/app/Services/ScanHistoryService.php): Pencatatan riwayat audit ke tabel `link_scans` dengan pemisahan hasil Suricata, Gemini, indikator teknis, serta array teknik terdeteksi (`techniques`).
  9. [`MockDataRepository.php`](file:///d:/cybershield/backend/app/Repositories/MockDataRepository.php): Repository terpadu berarsitektur **Database-First** yang langsung melakukan query dan mutasi data ke model-model Eloquent Supabase (`User`, `Report`, `Article`, `CaseStudy`, `Prinsip4T`, `Activity`) dengan mekanisme failover mandiri.
  10. [`AiOrchestratorService.php`](file:///d:/cybershield/backend/app/Services/AiOrchestratorService.php): Orkestrator utama yang mengeksekusi pipeline: Validasi URL & SSRF $\to$ UrlThreatAnalyzer $\to$ Suricata NIDS $\to$ Google Gemini AI $\to$ Risk Assessment $\to$ Scan History.

---

## 🛡️ 2. Penjelasan Suricata NIDS, Berkas `eve.json`, dan Indikator Tautan Berbahaya

### 2.1. Peran Suricata NIDS dan Google Gemini AI
- **Suricata NIDS (Deterministik — Layer 3/4/7):** Menyadap dan memeriksa metadata paket jaringan (`DNS`, `TLS SNI`, `HTTP URI`) secara presisi dalam hitungan mikrodetik menggunakan aturan signature (`SID`).
- **UrlThreatAnalyzer (Struktur Teks URL):** Membongkar manipulasi URL seperti defacement subdomain instansi, open redirect parameter, dan typosquatting homoglif.
- **Google Gemini AI (Kognitif — Layer 7):** Memahami konteks kalimat, membongkar manipulasi psikologis, menganalisis modus operandi penipuan, dan menilai kaidah Fiqih Siber.

### 2.2. Berkas `eve.json` dan `suricata.rules`
- **Lokasi Berkas Log Telemetri:** [`backend/storage/logs/suricata/eve.json`](file:///d:/cybershield/backend/storage/logs/suricata/eve.json)
- **Lokasi Aturan Signature:** [`suricata.rules`](file:///d:/cybershield/suricata.rules) & [`infra/suricata/rules/aswaja-local.rules`](file:///d:/cybershield/infra/suricata/rules/aswaja-local.rules)

**Aturan Signature Suricata yang Terdaftar:**
1. **SID `1000000` (PASS / Whitelist):** Domain resmi lembaga negara (`.ojk.go.id`, `.bappebti.go.id`, `.bi.go.id`, `.kominfo.go.id`).
2. **SID `1000001` (Priority 1 — Tingkat Kuat):** Frasa eksplisit judi online (`slot-gacor`, `situs-togel`, `casino-online`, `slot88`, `slot777`).
3. **SID `1000002` (Priority 2 — Tingkat Menengah):** Istilah fitur taruhan (`maxwin`, `jackpot`, `scatter`, `free-spin`, `rtp-live`).
4. **SID `1000003` (Priority 3 — Tingkat Kontekstual):** Nama tema/mitologi (`zeus`, `dewa`, `thor`, `dragon`, `olympus`).
5. **SID `1000004` (Priority 1):** Parameter deposit cepat (`deposit-dana`, `deposit-qris`, `depo-pulsa`) & TLS SNI `gacor`.
6. **SID `1000005` (Priority 2):** TLD reputasi buruk yang kerap dipakai sindikat (`.vip`, `.cc`, `.xyz`, `.top`, `.fun`, `.click`, `.site`).
7. **SID `1000006` (Priority 1):** Skema investasi bodong (`robot-trading`, `profit-pasti`, `cuan-harian`, `auto-profit`).
8. **SID `1000007` & `1000008`:** Unduhan berkas berbahaya (`.apk`, `.exe`) dan parameter afiliasi (`?ref=`, `?aff=`, `?agent=`).
9. **SID `1000101` (Priority 1 — HTTP URI):** Kata kunci judol pada URI (`slot`, `gacor`, `maxwin`, `togel`, `sbobet`, `scatter`, `rtp`).
10. **SID `1000102` (Priority 1 — Open Redirect):** Parameter redirect ke URL eksternal (`?url=`, `?redirect=`, `?to=`).
11. **SID `1000103` (Priority 1 — DNS Query):** Query DNS domain yang memuat kata kunci judol.

---

## 🔍 3. Deteksi 6 Teknik Penyusupan & Manipulasi Tautan (`UrlThreatAnalyzer.php`)

Modul ini mengidentifikasi modus operandi sindikat modern tanpa mengeksekusi skrip berbahaya:
1. **Web Defacement & Subdomain Hijacking:**
   - Menyasar domain berakhiran `.go.id`, `.ac.id`, `.sch.id`, `.mil.id`, `.desa.id`.
   - Mendeteksi path CMS rentan (`/wp-content/uploads/`, `/uploads/`, `/journal/`) yang dikombinasikan dengan kata kunci judol.
   - Mendeteksi subdomain tidak lazim yang menyematkan angka hoki judol (`88`, `138`, `777`) atau kata kunci slot.
2. **Open Redirect (Cloaking / Pembelokan URL):**
   - Mendeteksi parameter query pengalihan: `url`, `redirect`, `redirect_uri`, `to`, `link`, `dest`, `next`, `goto`, `return`, `target`.
   - Mendeteksi multi-host dalam 1 URL string dan percent-encoding bertingkat ganda.
   - Menandai target redirect yang beralih ke TLD murah atau domain judol.
3. **Typosquatting & Homoglyph URL Spoofing:**
   - Mencocokkan nama domain dengan daftar instansi resmi di [`config/threat/official_domains.php`](file:///d:/cybershield/backend/config/threat/official_domains.php) menggunakan jarak Levenshtein dan substitusi karakter homoglif (`0` $\to$ `o`, `1` $\to$ `l/i`, `rn` $\to$ `m`, `vv` $\to$ `w`).
   - Menandai nama instansi pemerintah yang didaftarkan di TLD komersial non-resmi (`.com`, `.net`, `.info`).
   - Menandai embel-embel tipuan (`-resmi`, `-login`, `-bansos`, `-bantuan`, `-daftar`).
4. **URL Masking / Link Masking:**
   - Menyembunyikan alamat situs sebenarnya di balik domain tepercaya Google (`share.google`, `app.goo.gl`) atau pemendek tautan (`bit.ly`, `s.id`, `tinyurl.com`, `t.co`, `linktr.ee`).
   - Membongkar rantai redirect dan mengaudit penuh URL tujuan akhir.
5. **Domain Rebouncer / Proxy Link:**
   - Menelusuri jembatan pengalihan otomatis untuk mendeteksi situs investasi berisiko tinggi / platform *Binary Options* tanpa izin pengawas keuangan.
6. **SEO Cloaking / Conditional Defacement (Mesin Dual-Persona):**
   - Membandingkan respon halaman antara **Pengunjung Normal** vs **Googlebot Crawler**.
   - Mengungkap manipulasi bersyarat di mana pengunjung melihat berita wajar, sementara crawler Googlebot disajikan backdoor judi online (`CUMI787 Slot Gacor Maxwin`). Memberikan skor bahaya 95% Kritis.

---

## 🧮 4. Formulasi Matematika & Penilaian Risiko

### 4.1. Sintesis Skor Risiko Terbobot (*Weighted Multi-Sensor Synthesis*)
$$\text{RiskScore} = \frac{W_{\text{threat}} \cdot S_{\text{threat}} + W_{\text{suricata}} \cdot S_{\text{suricata}} + W_{\text{gemini}} \cdot S_{\text{gemini}} + W_{\text{url}} \cdot S_{\text{url}}}{\sum W_i}$$
Dengan distribusi bobot:
- **$W_{\text{threat}} = 0.35$ ($35\%$)**: Hasil evaluasi `UrlThreatAnalyzer` (Defacement, Open Redirect, Typosquatting).
- **$W_{\text{suricata}} = 0.30$ ($30\%$)**: Deteksi signature jaringan Suricata NIDS.
- **$W_{\text{gemini}} = 0.30$ ($30\%$)**: Analisis kognitif Google Gemini AI.
- **$W_{\text{url}} = 0.05$ ($5\%$)**: Anomali struktural dasar URL.

### 4.2. Sistem Skor 2-State (Aman vs Berbahaya)
- **Jika Vonis AMAN (`normal` / `clean`):**
  - Tampilan: **"Tingkat Keamanan"** ($0\% - 100\%$, hijau emerald `#059669`).
- **Jika Vonis BERBAHAYA (`terindikasi_judol` / `terindikasi_investasi` / `mencurigakan`):**
  - Tampilan: **"Tingkat Bahaya"** ($0\% - 100\%$, merah danger `#DC2626`).
  - Label penegas **"Sangat Berbahaya"** HANYA disematkan jika skor $\ge 85\%$.

---

## 🧪 5. Hasil Pengujian Unit Test & Integrasi (PHP 8.5)

Semua pengujian pada PHP 8.5.12 telah lulus 100%:
1. **Threat Analyzer Test (`backend/tests/url_threat_analyzer_test.php`):**
   * ✅ 1. Web Defacement Path pada domain `.go.id` terdeteksi
   * ✅ 2. Subdomain Hijacking bertema judol pada domain `.desa.id` terdeteksi
   * ✅ 3. Open Redirect mengarah ke target judol (`.vip`) terdeteksi
   * ✅ 4. Typosquatting nama instansi resmi di domain komersial (`.com`) terdeteksi
   * ✅ 5. Portal resmi OJK (`ojk.go.id`) normal TIDAK DITANDAI (False Positive = 0)
   * ✅ 6. Portal resmi perguruan tinggi (`ugm.ac.id`) normal TIDAK DITANDAI (False Positive = 0)
   * ✅ 7. Modus Kamuflase Finansial / Trading Judol terdeteksi `kamuflase_investasi`
   * ✅ 8. URL Masking & Rebouncer (`share.google` $\to$ `olymptrade.com`) terdeteksi skor tinggi
   * ✅ 9. Portal edukasi resmi (`codepolitan.com`) normal TIDAK DITANDAI (False Positive = 0)
   * ✅ 10. Deteksi Dual-Persona SEO Cloaking pada domain resmi pemerintah (`kemendagri.go.id`) terdeteksi skor 95%
   * **Hasil:** `10 dari 10 Pengujian Lulus (100%)`

2. **REST API & RBAC Test Suite (`backend/tests/api_verification_test.php`):**
   * ✅ PASS: Ping Endpoint
   * ✅ PASS: Analyze Link (Judol Suspicious)
   * ✅ PASS: Analyze Link Clean (Kompas.com - Emerald & Valid Findings)
   * ✅ PASS: Submit Report Public
   * ✅ PASS: Get Public Articles
   * ✅ PASS: Get Public Case Studies
   * ✅ PASS: Evaluate Cyber Guard Quiz
   * ✅ PASS: Get Prinsip 4T
   * ✅ PASS: Login Super Admin
   * ✅ PASS: Get Dashboard Stats
   * ✅ PASS: Update Report Status
   * ✅ PASS: Dual AI Status Endpoint (`/api/admin/ai/status`)
   * ✅ PASS: Dual AI Safe Test Probe (`/api/admin/ai/test`)
   * ✅ PASS: Login Admin Basic
   * ✅ PASS: RBAC: Admin Basic Access to AI Status Denied (403)
   * ✅ PASS: RBAC: Admin Basic Delete Report Denied (403)
   * ✅ PASS: RBAC: Super Admin Delete Report Permitted (200)
   * ✅ PASS: Logout Super Admin
   * **Hasil:** `15 dari 15 Pengujian Lulus (100%)`

3. **Rate Limiting Pelaporan Publik Test (`backend/tests/rate_limit_report_test.php`):**
   * ✅ Attempt 1: HTTP 201 Created (Sisa Kuota: 1)
   * ✅ Attempt 2: HTTP 201 Created (Sisa Kuota: 0)
   * ✅ Attempt 3: HTTP 429 Too Many Requests (Kuota Habis, mengembalikan 4 alasan keamanan & estimasi waktu tunggu)
   * **Hasil:** `3 dari 3 Skenario Lulus (100%)`

---

## ⏱️ 6. Spesifikasi Rate Limiting Pelaporan Publik (Max 2x per 2 Jam)

Untuk menjaga ketahanan server dan integritas penyelidikan, sistem menerapkan pembatasan frekuensi pengiriman laporan publik:
- **Aturan Utama:** Maksimal 2 laporan per 7.200 detik (2 jam) per IP Address (`RateLimiter::tooManyAttempts($key, 2)`).
- **Pengecualian:** Administrator terautentikasi (Bearer token) dibebaskan dari pembatasan untuk memudahkan penanganan operasional.
- **Response HTTP 429 Too Many Requests:** Mengembalikan payload JSON berstruktur lengkap:
  - `error`: Pesan kesalahan kuota.
  - `message`: Narasi estimasi waktu tunggu dalam jam dan menit.
  - `retry_after_minutes`: Jumlah menit hingga jendela kuota terbuka kembali.
  - `reasons`: 4 Alasan Keamanan dan Operasional:
    1. **Mencegah Serangan Spam dan DDoS:** Membatasi frekuensi kiriman mencegah bot otomatis membanjiri server dengan ribuan laporan palsu yang bisa melumpuhkan server.
    2. **Efisiensi Validasi Tim Penyelidik:** Menghindari penumpukan laporan duplikat agar tim investigator fokus memproses aduan yang valid dan unik.
    3. **Optimalisasi Bandwidth dan Penyimpanan:** Menghemat ruang penyimpanan basis data dari sampah atau spam tidak berguna.
    4. **Mendorong Kualitas Laporan:** Memastikan pelapor lebih berhati-hati, detail, dan serius saat menyusun bukti aduan.

---

## 🔐 7. Penyederhanaan Autentikasi & Model Pengguna (`User.php`)

- **Autentikasi Langsung Aktif (Tanpa Verifikasi Email):**
  - Kolom `email_verified_at` telah dihapus dari pemodelan `$casts` di [`backend/app/Models/User.php`](file:///d:/cybershield/backend/app/Models/User.php).
  - Model `User` tidak mengimplementasikan interface `MustVerifyEmail`, sehingga akun administrator dan pengguna langsung berstatus aktif dan dapat langsung masuk tanpa hambatan verifikasi email.
- **Modernisasi Input Controller:**
  - Pemanggilan request pada [`AuthController.php`](file:///d:/cybershield/backend/app/Http/Controllers/Auth/AuthController.php) telah diperbarui dari fungsi usang `$request->get('param')` menjadi `$request->input('param')` sesuai standar modern Laravel 11.

---

## 📝 8. Penanganan Fleksibel Kaidah 4T & Konten Artikel

- **Dukungan Multi-Identifier Kaidah 4T:**
  - [`Prinsip4TController.php`](file:///d:/cybershield/backend/app/Http/Controllers/Api/Prinsip4TController.php) dan [`MockDataRepository.php`](file:///d:/cybershield/backend/app/Repositories/MockDataRepository.php) telah diperbarui agar mendukung pembaruan data menggunakan parameter string kode (`T1`, `T2`, `T3`, `T4`) maupun ID numerik (`1, 2, 3, 4`).
  - Menyediakan *failover* mandiri ke database PostgreSQL Supabase dengan pemetaan langsung ke model Eloquent `Prinsip4T`.
- **Penanganan Gambar Sampul Artikel Tanpa Galat Panjang:**
  - Validasi dan penyimpanan gambar sampul artikel pada [`ArticleController.php`](file:///d:/cybershield/backend/app/Http/Controllers/Api/ArticleController.php) diperluas untuk menerima data URL Base64 dan berkas binary tanpa batasan kaku 500 karakter, menghindari *validation error* saat pengunggahan gambar lokal.

---

## 🔑 9. Sistem Pengiriman Email & Pemulihan Kata Sandi (*Reset Password API*)

- **Endpoint Permintaan Reset Sandi (`POST /api/admin/forgot-password` & `POST /api/forgot-password`):**
  - Menerima parameter `email` (dapat berupa alamat email terdaftar atau username admin).
  - Melakukan validasi dan pencarian akun pada basis data Supabase PostgreSQL / Repository.
  - Menerbitkan token keamanan unik `RST-[hex16]` yang dicatat ke tabel `password_reset_tokens` dengan masa kedaluwarsa 60 menit.
  - Membangun tautan URL reset: `http://localhost:3000/reset-password?token=RST-...&email=...`
  - Merender template email Blade modern: [`backend/resources/views/emails/reset_password.blade.php`](file:///d:/cybershield/backend/resources/views/emails/reset_password.blade.php).
  - Mengirimkan pesan email instruksi reset password menggunakan facade `Illuminate\Support\Facades\Mail::html()` dengan subjek *"Instruksi Reset Kata Sandi Akun Administrator - Aswaja CyberShield"*.
  - Mengembalikan payload JSON yang mencakup `success: true`, `message`, `email`, `token`, `reset_url`, dan `html_preview` untuk pratinjau tampilan email secara langsung di peramban.

- **Desain & Spesifikasi Template Email Blade (`reset_password.blade.php`):**
  - **Identitas Visual:** Header bertema hijau gradasi emerald khas Aswaja CyberShield (`#064e3b` ke `#022c22`), ikon tameng 🛡️, dan judul *"ASWAJA CYBERSHIELD - Sistem Pengawasan Siber & Edukasi Ummah"*.
  - **Sapaan Personal & Islami:** *"Assalamu'alaikum Warahmatullahi Wabarakatuh, Yth. Rekan [Nama Admin]"*.
  - **Tombol Aksi Utama (CTA Button):** Tombol kontras tinggi *"🔐 Reset Kata Sandi Sekarang"* yang mengarahkan penerima langsung ke rute reset password.
  - **Kotak Tautan Cadangan (Direct Fallback URL):** Wadah teks monospace untuk menyalin URL jika klien email menonaktifkan tombol tautan.
  - **Peringatan Keamanan & Kedaluwarsa:** Penegasan masa aktif 60 menit, token sekali pakai, dan imbauan keamanan jika tidak merasa mengajukan permintaan.
  - **Footer Resmi:** Slogan Prinsip 4T (*Tawaqquf • Tastabbus • Tatsabbut • Ta'aqqul*) dan catatan hak cipta.

- **Endpoint Eksekusi Pembaruan Sandi (`POST /api/admin/reset-password` & `POST /api/reset-password`):**
  - Menerima parameter `email`, `token`, `password`, dan `password_confirmation` (minimal 6 karakter).
  - Mengenkripsi sandi baru dengan algoritma BCRYPT/Argon2 (`Hash::make()` / `password_hash()`).
  - Memperbarui data pengguna secara live di tabel `users` Supabase PostgreSQL dan repository fallback.
  - Menghapus token dari tabel `password_reset_tokens` setelah pembaruan berhasil untuk menjamin keamanan satu kali pakai (*one-time use*).

---

## 🎯 10. Standarisasi 4 Tingkat Risiko Resmi & Penyelarasan AlertClass (`RiskAssessmentService.php`)

- **Formula Sintesis Terbobot Konsisten:**
  - `RiskAssessmentService.php` mensintesis bukti ancaman dari 4 sumber:
    $$\text{Risk Score} = (0.35 \times \text{Threat}) + (0.30 \times \text{Suricata}) + (0.30 \times \text{Gemini}) + (0.05 \times \text{URL})$$
  - Menghasilkan nilai tunggal integer $0 - 100$ yang dipetakan ke dalam 4 tingkat risiko resmi:
    - **`riskScore >= 85` $\to$ `riskLevel: 'berat'`**, `alertClass: 'red'`
    - **`riskScore >= 51` $\to$ `riskLevel: 'tinggi'`**, `alertClass: 'orange'`
    - **`riskScore >= 26` $\to$ `riskLevel: 'sedang'`**, `alertClass: 'amber'`
    - **`riskScore < 26` $\to$ `riskLevel: 'rendah'`**, `alertClass: 'emerald'`
- **Penyelarasan Kontrak DTO (`RiskAssessmentDTO.php` & `AnalyzeLinkController.php`):**
  - Backend selalu mengembalikan atribut `alertClass` dan `risk_score` yang valid, memandu frontend secara presisi untuk menampilkan tema warna yang sinkron tanpa tebakan (*no guesswork*).
- **Perlindungan Whitelist Domain Pendidikan Tinggi & Pemerintah (`UrlThreatAnalyzer.php`):**
  - Memperluas daftar domain aman resmi (`$trustedSafeDomains`) dengan menambahkan `unmul.ac.id` (Universitas Mulawarman) dan `unukaltim.ac.id` (Universitas Nahdlatul Ulama Kalimantan Timur), mendampingi `ojk.go.id`, `bi.go.id`, `bappebti.go.id`, `ugm.ac.id`, `ui.ac.id`, dan `itb.ac.id`.
  - Mencegah *false positive* pada portal akademik terverifikasi sehingga selalu menghasilkan skor risiko $0\%$ (Aman).

---

## 🚀 11. Arsitektur Router Front-Controller & Canonical Redirect (`index.php` & `.htaccess`)

- **Router Utama Mandiri (`index.php`):**
  - Bertindak sebagai *Smart Front Controller* saat server dijalankan menggunakan PHP Built-in Server (`php -S 127.0.0.1:3000 index.php`), Apache, maupun Vercel.
  - Meneruskan semua panggilan `/api/*` secara transparan ke kernel Laravel (`backend/public/index.php`).
  - Memetakan rute publik dan admin langsung ke berkas template Blade terkait tanpa perlu mengekspos struktur direktori internal `frontend/`.
- **Mekanisme Canonical Auto-Redirect (HTTP 301):**
  - Mencegat seluruh permintaan berakhiran `.blade.php` atau `.php` pada metode GET, lalu secara otomatis mengalihkan browser dengan status 301 ke Clean URL:
    - `/lupa-password.blade.php` $\to$ `/lupa-password`
    - `/tabayyun.blade.php` $\to$ `/tabayyun`
    - `/admin/index.blade.php` $\to$ `/admin`
  - Memastikan bilah alamat peramban selalu bersih, rapi, dan profesional.
- **Konfigurasi Mod_Rewrite Apache (`.htaccess`):**
  - Menyediakan aturan rewrite eksternal `%{THE_REQUEST}` untuk menjamin URL bersih tetap bekerja sempurna saat dipindahkan ke lingkungan server Apache/XAMPP/cPanel.

---

## 🗄️ 12. Fungsi Strategis Basis Data dalam Penyimpanan Data Tautan (`link_scans` & `reports`)

Penyimpanan riwayat pemeriksaan tautan ke dalam basis data (tabel `link_scans` dan `reports`) memiliki peran vital dalam operasional sistem:

1. **Mesin Pembelajaran Mandiri (*Self-Learning Threat Intelligence*):**
   - **Belajar dari Laporan Masyarakat (`reports`):** Tautan dan nomor rekening yang diadukan publik ke `/lapor` disimpan ke database. Saat pengguna lain menguji tautan atau domain yang sama di `/tabayyun`, sistem langsung mengenali:
     > *"Pembelajaran Basis Data Aduan: Tautan/domain telah tercatat dalam basis data laporan masyarakat (Tiket #LPRxxx) sebagai situs berbahaya."*
   - **Belajar dari Riwayat Pemindaian (`link_scans`):** Menyimpan riwayat audit pemindaian lengkap (`#SCN-YYYYMMDD-XXXXXX`). Pemindaian berulang terhadap domain sindikat yang sama langsung mengaitkan rekam jejak vonis sebelumnya tanpa mulai dari nol.
   - **Ekstraksi Kata Kunci Dinamis:** Mengekstraksi kata kunci atau nama merek sindikat judol baru dari nama domain lama yang pernah dianalisis untuk memperkaya kamus heuristik secara mandiri (*self-evolving intelligence*).

2. **Akuntabilitas Hukum & Jejak Forensik Siber (*Audit Trail & Non-Repudiation*):**
   - Menyimpan seluruh berkas pembuktian teknis secara terpisah dalam format JSONB:
     - Log sensor telemetri jaringan Suricata NIDS (`eve.json`, SID rule, waktu deteksi).
     - Hasil analisis kognitif semantik dan pertimbangan Google Gemini AI.
     - Rincian teknik manipulasi siber yang terbukti (*Web Defacement, Open Redirect, atau Typosquatting*).
     - Waktu pemindaian terverifikasi dalam format ISO 8601.
   - Menyediakan bukti digital sah jika pihak berwenang (Komdigi, OJK, Bappebti, atau Kepolisian) memerlukan berkas verifikasi untuk tindakan pemblokiran (*takedown*) domain atau rekening penampung.

3. **Efisiensi Biaya & Penghematan Kuota AI (*Performance & Resource Conservation*):**
   - Pemanggilan API Google Gemini AI memerlukan alokasi token dan latensi jaringan.
   - Melalui pencocokan rekam jejak di basis data, tautan identik yang diuji berulang kali dapat diverifikasi lebih cepat (*instant verification*) dan menghemat konsumsi kuota API secara signifikan.

4. **Bahan Metrik & Dashboard Pemantauan Admin (`/admin`):**
   - Mengagregasikan data pemindaian secara *real-time* ke panel dashboard admin:
     - Tren persentase ancaman (Judi Online vs Investasi Menyesatkan vs Aman).
     - Statistik teknik penyusupan yang sedang marak digunakan pelaku kejahatan.
     - Rasio efektivitas sensor Suricata NIDS dan kecerdasan Google Gemini AI.

5. **Transparansi bagi Masyarakat (*Sesuai Nilai Tabayyun & Ta'aqqul*):**
   - Setiap pemindaian menerbitkan ID audit unik (misal `#SCN-20261108-V9RKCX`) yang dapat disalin melalui tombol *"Salin Ringkasan Verifikasi"*.
   - Membuktikan kepada publik bahwa vonis risiko merupakan hasil pembuktian data nyata yang tersimpan di sistem, bukan perkiraan acak.

---

## 🗂️ 13. Rute Dinamis & Konsistensi Artikel Database

- **Penyederhanaan Rute Artikel (`/artikel/{slug}`):**
  - Mesin routing [index.php](file:///d:/cybershield/index.php) menangani rute `/artikel/{slug}` dengan memanggil template pembaca dinamis [baca.blade.php](file:///d:/cybershield/frontend/artikel/baca.blade.php) yang merender data artikel secara langsung dari tabel `public.articles` atau `ApiService.getArticles()`.
- **Pembersihan Berkas Statis Duplikat:**
  - Menghapus 6 berkas statis lawas yang tidak terdaftar di database `articles` (`adab-bermedia-sosial.blade.php`, `bahaya-ghibah-digital.blade.php`, `fiqih-privasi-data.blade.php`, `menghadapi-deepfake.blade.php`, `mitigasi-phishing-apk.blade.php`, `muamalah-finansial.blade.php`).
- **Integritas Berkas Prinsip 4T:**
  - 4 berkas prinsip metodologi fiqih (`prinsip-tawaqquf.blade.php`, `prinsip-tastabbus.blade.php`, `prinsip-tatsabbut.blade.php`, `prinsip-taaqqul.blade.php`) tetap aktif dan terhubung ke tabel `public.prinsip_4t`.

---

## 🔒 14. Pertahanan Autentikasi: Inactivity Timeout & Login Throttling

- **Backend Inactivity Timeout (5 Menit / 300 Detik):**
  - Pada [MockDataRepository.php](file:///d:/cybershield/backend/app/Repositories/MockDataRepository.php#L150-L180), penyimpanan token sesi menyertakan timestamp `last_activity`.
  - Pada metode `getUserByToken()`, jika jeda waktu antara aktivitas saat ini dan `last_activity` melebihi 300 detik, token otomatis dihapus dari basis data dan mengembalikan status tidak sah (HTTP 401: *Sesi telah berakhir karena tidak ada aktivitas*).
- **Backend Login Throttling (Anti-Brute Force Protection):**
  - Diimplementasikan pada `AuthController::login()` di [AuthController.php](file:///d:/cybershield/backend/app/Http/Controllers/Auth/AuthController.php#L16-L95).
  - Melacak kegagalan percobaan login per kombinasi IP dan username/email.
  - Setiap kegagalan mengurangi kuota percobaan (maksimal 3x).
  - Jika gagal 3 kali berturut-turut, backend mengembalikan status **HTTP 429 (Too Many Requests)** dengan muatan `locked: true` dan durasi `cooldown_seconds` berjenjang:
    - Tingkat 1: 60 detik (1 menit)
    - Tingkat 2: 180 detik (3 menit)
    - Tingkat 3: 300 detik (5 menit)
    - Tingkat 4: 900 detik (15 menit)
    - Tingkat 5+: 3600 detik (1 jam)
  - Otentikasi yang berhasil seketika membersihkan rekam jejak kegagalan (*clears throttle*) untuk akun bersangkutan.

---

## 🔀 15. Penyempurnaan Router & Proteksi Fallback di `index.php`

Berkas [`index.php`](file:///d:/cybershield/index.php) bertindak sebagai unified front-controller server. Untuk mencegah kesalahan navigasi dari panel admin terlempar ke halaman beranda publik, diterapkan sistem proteksi rute ganda:

1. **Pemetaan Rute Admin Resmi:**
   - Permintaan ke `/admin` atau `/admin/` melayani [`frontend/admin/index.blade.php`](file:///d:/cybershield/frontend/admin/index.blade.php).
   - Permintaan ke `/admin/{subpage}` melayani berkas terkait di direktori `frontend/admin/{subpage}.blade.php` (seperti `/admin/artikel`, `/admin/laporan`, `/admin/studi-kasus`, `/admin/akun`, `/admin/integrasi-ai`).
2. **Auto-Redirect 302 untuk Rute Admin Tanpa Prefix `/admin`:**
   - Jika pengguna atau peramban mengakses rute root yang menyerupai subhalaman admin (misal `/artikel`, `/laporan`, `/akun`, `/studi-kasus`, `/integrasi-ai`):
     ```php
     $adminAliases = ['artikel', 'laporan', 'akun', 'studi-kasus', 'integrasi-ai'];
     if (in_array($cleanPath, $adminAliases, true)) {
         header("Location: /admin/{$cleanPath}", true, 302);
         exit;
     }
     ```
   - Tindakan ini memastikan sesi kerja admin tidak terputus dan pengguna tidak salah masuk ke halaman beranda publik.
3. **Penyaluran REST API Bebas CORS:**
   - Semua permintaan berawalan `/api/*` secara otomatis diteruskan langsung ke file bootstrap Laravel [`backend/public/index.php`](file:///d:/cybershield/backend/public/index.php) dengan mempertahankan variabel server `SCRIPT_FILENAME`, `SCRIPT_NAME`, dan `REQUEST_URI`.

---

## 📡 16. Spesifikasi & Lokasi File Log `eve.json` (Suricata NIDS) & Konfigurasi Runtime

### A. Lokasi dan Konfigurasi Berkas
- **Path Penyimpanan Standar:**
  ```
  backend/storage/logs/suricata/eve.json
  ```
- **Konfigurasi Environment (`.env` & `config/services.php`):**
  ```php
  // config/services.php
  'suricata' => [
      'eve_path' => env('SURICATA_EVE_PATH', storage_path('logs/suricata/eve.json')),
      'rules_path' => env('SURICATA_RULES_PATH', base_path('../suricata.rules')),
  ],
  ```

### B. Format Data Telemetri Jaringan
Berkas `eve.json` (*Extensible Event Format*) menampung stream telemetri JSON Lines (`jsonl`). Setiap baris mewakili 1 event paket yang diamati Suricata:
- **Event `alert`:** Ditulis saat lalu lintas memicu aturan signature (SID 1000000 – 1000103). Memuat detail signature, kategori bahaya, tingkat keparahan (*severity*), dan payload paket.
- **Event `tls`:** Menangkap proses handshake TLS, termasuk informasi `sni` (*Server Name Indication*), versi SSL/TLS, dan fingerprint sertifikat untuk mendeteksi penyamaran domain judi.
- **Event `dns`:** Mencatat seluruh query resolusi domain (misal lookup domain `.vip`, `.top`, atau domain typosquatting).
- **Event `http`:** Menangkap method, hostname, URI path, parameter query string, user-agent, dan referer.

### C. Alur Ingestion & Pemrosesan di Backend
- **`SuricataIntegrationService.php`:**
  Membaca entri terbaru dari `eve.json` menggunakan cursor baca efisien (tailing log), memfilter event berdasarkan timestamp jendela audit, dan memetakan metadata ke model deteksi.
- **`SuricataService.php`:**
  Menyediakan pencocokan deterministik berbasis aturan lokal (`aswaja-local.rules`), sehingga meskipun daemon Suricata belum berjalan di mesin lokal, backend tetap mampu melakukan simulasi inspeksi signature secara akurat dan konsisten.

---

## ☁️ 17. Arsitektur & Panduan Deployment Vercel (Frontend & Backend)

Platform **Aswaja CyberShield** telah dikonfigurasi penuh untuk mendukung arsitektur deployment serverless di Vercel:

### Opsi 1 (Telah Diterapkan & Aktif): Full-Stack Monorepo Serverless di Vercel
Mengintegrasikan antarmuka frontend Blade PHP dan REST API backend Laravel ke dalam satu repositori tunggal:

1. **Serverless Entrypoint Adaptor ([`api/index.php`](file:///d:/cybershield/api/index.php)):**
   - Mengalihkan penyimpanan framework storage (`views`, `cache`, `sessions`, `logs`) ke direktori sementara `/tmp/storage` guna mengatasi batasan *read-only filesystem* lingkungan serverless Vercel.
   - Mengarahkan request `/api/*` ke bootstrap Laravel (`backend/public/index.php`) dan request web lainnya ke smart front controller [`index.php`](file:///d:/cybershield/index.php).
2. **Konfigurasi Vercel ([`vercel.json`](file:///d:/cybershield/vercel.json)):**
   - Runtime: `vercel-php@0.7.3`.
   - `includeFiles`: Memaketkan `backend/**`, `frontend/**`, `index.php`, dan aset ke dalam fungsi serverless.
   - Perutean statis langsung melayani direktori `css/`, `js/`, `images/`, `logo-shield.png`, dan `favicon.svg` melalui Vercel Edge CDN.
3. **Automasi Build Composer ([`package.json`](file:///d:/cybershield/package.json)):**
   - Menjalankan script `vercel-build`: `cd backend && composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction`.
4. **Storage Override ([`backend/bootstrap/app.php`](file:///d:/cybershield/backend/bootstrap/app.php)):**
   - Mendeteksi `env('APP_STORAGE')` dan memanggil `$app->useStoragePath($storagePath)` secara dinamis.

### Opsi 2: Split Architecture (Alternatif Produksi Terpisah)
- **Frontend:** Dideploy di Vercel Edge Network dengan `API_BASE_URL` mengarah ke host backend.
- **Backend:** Dideploy di Railway / Render / Fly.io / VPS Ubuntu dengan Nginx + PHP 8.2+ untuk beban kerja kontinu (background worker & Suricata live daemon).
- **Database:** Supabase PostgreSQL (`aws-0-ap-southeast-1.pooler.supabase.com:5432`).

---

## 👥 18. Sinkronisasi Dropdown Staf/Admin Penangan via API & Supabase

- **Endpoint REST API Pengguna Admin:**
  - `GET /api/admin/users`: Mengembalikan daftar seluruh akun pengguna pengelola dengan filter role `super_admin` dan `admin_basic`.
  - Berbasis model `User` Eloquent yang langsung terhubung ke tabel `public.users` Supabase.
- **Integrasi Penugasan Laporan (`Report Assignment`):**
  - Tabel `public.reports` dilengkapi kolom relasional `assigned_to` (UUID user pengelola) dan `admin_name` (nama tampilan admin penangan).
  - Saat staf admin memilih nama penangan dari dropdown modal laporan dan menekan tombol *Simpan Pembaruan*, backend memvalidasi keberadaan akun di tabel `users` dan memperbarui status tiket.
  - Setiap perubahan penugasan otomatis memicu pencatatan audit trail pada tabel `public.activities`.

---

## 🏷️ 19. Standar Komentar Kode Ringkas (Header-Only Standard)

Sesuai arahan teknis sistem, seluruh berkas kode (PHP, JS, Apache Config, JSON) menerapkan prinsip **komentar ringkas berbasis judul fungsi**:
- **Tidak Memuat Narasi Panjang:** Menghilangkan penjelasan paragraf atau blok teks naratif yang berlebihan di dalam kode sumber.
- **Format Judul Terstandarisasi:** Menggunakan penanda ringkas 2–4 kata per blok logika fungsional (contoh: `// 1. Assets`, `// 2. Static Files`, `// Storage Setup`, `// API Router`).
- **Dokumentasi Lengkap Dialihkan ke Markdown:** Seluruh rincian filosofi, arsitektur, dan penjelasan naratif dipusatkan di berkas dokumentasi proyek (`CATATAN_*.md`, `PENJELASAN_WEB.md`, `README.md`) agar kode produksi tetap bersih, ringan, dan mudah dipelihara (*clean & readable*).




