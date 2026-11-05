# ⚙️ Catatan Lengkap Arsitektur & Spesifikasi Backend
## Platform Aswaja CyberShield (Laravel REST API + Suricata NIDS + Parallel Dual AI)

Dokumen ini merupakan **gabungan terpadu seluruh catatan teknis Backend** (mencakup hasil audit frontend-to-backend, perancangan RESTful API Laravel, spesifikasi Suricata NIDS & `eve.json`, orkestrasi paralel Dual AI Google Gemini + DeepSeek, sistem *3-Tier Keyword Matching*, rumus matematis penilaian risiko, hingga cetak biru deployment Vercel FrankenPHP).

---

## 📌 1. Gambaran Umum Arsitektur Backend

Sistem backend **Aswaja CyberShield** dibangun menggunakan **PHP 8.4 & Framework Laravel** yang terintegrasi dengan **Sensor Jaringan Suricata NIDS** dan **Mesin Kecerdasan Buatan Ganda (Google Gemini + DeepSeek)**:

- **Backend Framework:** Laravel 13.x (PHP 8.4) RESTful API.
- **Modularitas Layanan (9 Service Utama):**
  1. [`UrlValidationService.php`](file:///d:/aswajaCyber/backend/app/Services/UrlValidationService.php): Validasi skema, normalisasi URL, proteksi SSRF, deteksi anomali struktur domain, dan **Pencocokan Kata Kunci 3 Tingkat (*3-Tier Keyword Matching*)**.
  2. [`SuricataIntegrationService.php`](file:///d:/aswajaCyber/backend/app/Services/SuricataIntegrationService.php): Ingestion dan parsing log telemetri jaringan [`eve.json`](file:///d:/aswajaCyber/backend/storage/logs/suricata/eve.json) (DNS Query, TLS SNI, HTTP URI, dan Alert Signature).
  3. [`SuricataService.php`](file:///d:/aswajaCyber/backend/app/Services/SuricataService.php): Mesin inspeksi signature deterministik (`SID: 1000000` – `1000008`) yang selaras dengan [`suricata.rules`](file:///d:/aswajaCyber/suricata.rules) dan [`suricata.py`](file:///d:/aswajaCyber/suricata.py).
  4. [`GeminiService.php`](file:///d:/aswajaCyber/backend/app/Services/GeminiService.php): Integrasi resmi Google Gemini API untuk analisis semantik, bahasa persuasif, dan konteks Fiqih 4T.
  5. [`DeepSeekService.php`](file:///d:/aswajaCyber/backend/app/Services/DeepSeekService.php): Integrasi resmi DeepSeek API untuk audit forensik, *de-obfuscation*, alur transaksi rekening penampung, dan skema Ponzi.
  6. [`AiComparisonService.php`](file:///d:/aswajaCyber/backend/app/Services/AiComparisonService.php): Pembanding independen konsensus kategori, selisih skor ($\Delta_{\text{AI}}$), dan deduplikasi bukti antara Gemini dan DeepSeek.
  7. [`RiskAssessmentService.php`](file:///d:/aswajaCyber/backend/app/Services/RiskAssessmentService.php): Mesin korelasi matriks 6 skenario dan kalkulasi rumus *Weighted Multi-Sensor Risk Synthesis*.
  8. [`ScanHistoryService.php`](file:///d:/aswajaCyber/backend/app/Services/ScanHistoryService.php): Pencatatan riwayat audit ke tabel `link_scans` dengan pemisahan hasil Suricata, Gemini, dan DeepSeek.
  9. [`AiOrchestratorService.php`](file:///d:/aswajaCyber/backend/app/Services/AiOrchestratorService.php): Orkestrator utama yang mengeksekusi seluruh layer di atas dan menjalankan panggilan paralel Dual AI melalui `Http::pool()`.

---

## 🛡️ 2. Penjelasan Suricata NIDS, Berkas `eve.json`, dan Indikator Tautan Berbahaya (Rujukan Dewan Juri)

### 2.1. Apa Itu Suricata NIDS dan Mengapa Dipadukan dengan Dual AI?
**Suricata** adalah mesin pendeteksi intrusi jaringan (*Network Intrusion Detection System* - NIDS) *open-source* berstandar internasional yang dikembangkan oleh *Open Information Security Foundation* (OISF).

Dalam **Aswaja CyberShield**, Suricata berperan sebagai **Sensor Keamanan Garis Depan (Layer Jaringan)** yang melengkapi **Dual AI (Layer Kognitif)**:
- **Suricata NIDS (Deterministik — Layer 3/4/7):** Menyadap dan memeriksa metadata paket jaringan (`DNS`, `TLS SNI`, `HTTP URI`) secara presisi dalam hitungan mikrodetik menggunakan aturan signature (`SID`).
- **Dual AI Gemini & DeepSeek (Kognitif — Layer 7):** Memahami konteks kalimat, membongkar manipulasi psikologis, dan menganalisis modus operandi penipuan yang ambigu.

### 2.2. Apa Itu Berkas `eve.json` dan Fungsinya di Dalam Sistem?
**[eve.json](file:///d:/aswajaCyber/backend/storage/logs/suricata/eve.json)** adalah berkas log utama keluaran Suricata NIDS (*Extensible Event Format* — JSON).
- **Lokasi Berkas:** [`backend/storage/logs/suricata/eve.json`](file:///d:/aswajaCyber/backend/storage/logs/suricata/eve.json)
- **Lokasi Aturan Signature:** [`suricata.rules`](file:///d:/aswajaCyber/suricata.rules)
- **Konfigurasi Laravel:** [`backend/config/services.php`](file:///d:/aswajaCyber/backend/config/services.php#L43-L47)

**4 Fungsi Utama `eve.json`:**
1. **Perekam Bukti Forensik Jaringan:** Mencatat 4 jenis *event* (`dns` query domain, `tls` SNI handshake HTTPS, `http` hostname/URI, dan `alert` pelanggaran signature).
2. **Jembatan Data (*Data Bridge*) ke Laravel:** Dibaca oleh [`SuricataIntegrationService.php`](file:///d:/aswajaCyber/backend/app/Services/SuricataIntegrationService.php) dengan dukungan *Dual-Format* (mendukung format **JSON Array `[...]`** yang bebas error di IDE maupun format **JSON Lines / NDJSON** bawaan daemon Suricata di Linux).
3. **Penyuplai Bobot Empiris Terbesar ($35\%$):** Menjadi komponen bobot tertinggi dalam rumus penilaian risiko akhir.
4. **Bukti Audit Transparan:** Menyimpan nomor `SID` dan detail paket ke kolom `suricata_result` pada tabel `link_scans`.

> **Kepatuhan Anti-Simulasi (Zero Fabrication):** Jika file `eve.json` atau API key AI belum tersedia, sistem melaporkan status jujur `tidak_tersedia` / `unconfigured` tanpa memalsukan data deteksi.

---

### 2.3. Indikator Deteksi Judi Online (*Judol*) & Sistem Tiga Tingkat Pencocokan (*3-Tier Matching*)

#### A. Indikator Jaringan Suricata NIDS (`SID: 1000000` – `1000008`)
1. **SID `1000000` (PASS / Whitelist):** Domain resmi lembaga negara (`.ojk.go.id`, `.bappebti.go.id`, `.bi.go.id`, `.kominfo.go.id`).
2. **SID `1000001` (Priority 1 — Tingkat Kuat):** Frasa eksplisit judi online (`slot-gacor`, `situs-togel`, `casino-online`, `bandar-togel`, `slot88`, `slot777`, `gacor777`).
3. **SID `1000002` (Priority 2 — Tingkat Menengah):** Istilah fitur taruhan (`maxwin`, `jackpot`, `scatter`, `free-spin`, `rtp-live`, `pragmatic`, `pgsoft`).
4. **SID `1000003` (Priority 3 — Tingkat Kontekstual):** Nama tema/mitologi (`zeus`, `dewa`, `thor`, `dragon`, `olympus`).
5. **SID `1000004` (Priority 1):** Parameter deposit cepat (`deposit-dana`, `deposit-qris`, `depo-pulsa`, `depo-10k`) & TLS SNI `gacor`.
6. **SID `1000005` (Priority 2):** TLD reputasi buruk yang kerap dipakai sindikat (*fast-flux*: `.vip`, `.cc`, `.xyz`, `.top`, `.fun`, `.click`, `.site`, `.live`, `.club`, `.icu`, `.monster`).
7. **SID `1000006` (Priority 1):** Skema investasi bodong (`robot-trading`, `profit-pasti`, `cuan-harian`, `auto-profit`).
8. **SID `1000007` & `1000008`:** Unduhan berkas berbahaya (`.apk`, `.exe`) dan parameter afiliasi perekrutan pemain (`?ref=`, `?aff=`, `?agent=`).

#### B. Tabel Tiga Tingkat Pencocokan Kata Kunci (*3-Tier Keyword Matching*)
Untuk mencegah *false positive* (salah menuduh website edukasi/berita biasa sebagai situs judi online), pencocokan kata kunci dibagi menjadi 3 tingkat:

| Tingkat | Contoh Kata Kunci | Perlakuan Sistem | Keputusan Akhir (Jika Mandiri) | Keputusan Akhir (Dengan Bukti Pendukung) |
| :--- | :--- | :--- | :--- | :--- |
| **Kuat (`strong`)** | `slot gacor`, `situs togel`, `casino online`, `bandar togel` | **Indikator kuat** (Langsung memicu deteksi ancaman tinggi Priority 1) | `terindikasi_judol` (`red` / Bahaya) | `terindikasi_judol` (`red` / Bahaya) |
| **Menengah (`medium`)** | `maxwin`, `jackpot`, `scatter`, `free spin`, `rtp live` | **Perlu indikator pendukung** (TLD mencurigakan, angka `777`/`88`, path `/deposit`, `?ref=`) | `mencurigakan` (`amber` / Peringatan) | `terindikasi_judol` (`red` / Bahaya) |
| **Kontekstual (`contextual`)** | `zeus`, `dewa`, `thor`, `dragon`, `olympus` | **Perlu pemeriksaan tambahan** (Mencegah salah deteksi pada situs mitologi/sejarah/umum) | `aman` (`emerald` / Aman) | `terindikasi_judol` (`red`) atau `mencurigakan` (`amber`) |

#### C. Indikator Deteksi Investasi Menyesatkan (*Investasi Bodong*)
1. **Typosquatting Lembaga Resmi:** Pencatutan nama regulator pada domain tidak resmi (contoh: `ojk-investasi.xyz`, `bappebti-trading.top`).
2. **Janji Keuntungan Tidak Masuk Akal:** Klaim *"Profit pasti 20% harian"*, *"Auto-profit tanpa risiko"*, *"Balik modal kilat"*.
3. **Anomali Karakter & Tahun:** Huruf ganda berulang (`investasii2026.com`) dan deretan angka acak panjang (`investasi-bonus-827391.com`).
4. **Skema Piramida / Ponzi:** Parameter perekrutan anggota berjenjang (`?ref=`, `?referral=`) dan pengumpulan dana ke rekening/e-wallet pribadi tanpa izin OJK/Bappebti.

---

## 🧮 3. Formulasi Matematika & Matriks Korelasi Keputusan

### 3.1. Skor Anomali Struktur URL ($S_{\text{url}}$)
$$S_{\text{url}} = \min\left(100, \sum_{k=1}^{m} C_k\right)$$
Dengan bobot penalti ($C_k$):
- Typosquatting regulator resmi: $+50$
- Kata kunci Judol Tingkat Kuat (`strong`): $+35$
- Alamat IP langsung (*Direct IP*): $+30$
- Pemendek tautan (*URL Shortener*) / TLD berisiko tinggi: $+25$
- Kata kunci Judol Tingkat Menengah (`medium`) / Angka hoki (`777`, `888`) / Rute deposit: $+20$
- Karakter berulang / Tanda hubung berlebih ($\ge 2$): $+15$
- Kata kunci Tingkat Kontekstual (`contextual`): $+5$ (tidak memicu tuduhan jika berdiri sendiri)

### 3.2. Skor Sinyal Jaringan Suricata ($S_{\text{suricata}}$)
$$S_{\text{suricata}} = \begin{cases} 
95 & \text{jika Alert Prioritas 1 terpicu (Tingkat Kuat / Deposit Judol / Robot Trading)} \\
75 & \text{jika Alert Prioritas 2 atau 3 terpicu (TLD Mencurigakan / Tingkat Menengah / Afiliasi)} \\
0  & \text{jika Whitelist Regulator atau lalu lintas bersih dari alert}
\end{cases}$$

### 3.3. Rumus Sintesis Skor Risiko Terbobot (*Weighted Multi-Sensor Synthesis*)
$$\text{RawScore} = \frac{W_{\text{suricata}} \cdot S_{\text{suricata}} + W_{\text{gemini}} \cdot S_{\text{gemini}} + W_{\text{deepseek}} \cdot S_{\text{deepseek}} + W_{\text{url}} \cdot S_{\text{url}}}{\sum_{i \in \text{Aktif}} W_i}$$
Dengan bobot nominal:
- $W_{\text{suricata}} = 0.35$ ($35\%$)
- $W_{\text{gemini}} = 0.30$ ($30\%$)
- $W_{\text{deepseek}} = 0.30$ ($30\%$)
- $W_{\text{url}} = 0.05$ ($5\%$)

Konversi ke **Skor Keamanan Tampilan Publik** (`DisplaySafetyScore` 5%–98%):
$$\text{DisplaySafetyScore} = \max\left(5, \min\left(98, 100 - \text{RiskScore}\right)\right)$$

### 3.4. Matriks Korelasi Keputusan (6 Skenario)
| Kondisi Suricata | Kondisi Gemini | Kondisi DeepSeek | Keputusan Akhir Sistem | Tingkat Risiko | Warna Badge |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Alert** | Judol | Judol | **Terindikasi Judi Online** | Kritis ($86-100$) | `red` |
| **Alert** | Investasi Menyesatkan | Investasi Menyesatkan | **Terindikasi Investasi Menyesatkan** | Kritis ($86-100$) | `red` |
| **Normal** | Judol / Investasi | Judol / Investasi | **Mencurigakan (Perlu Bukti Tambahan)** | Tinggi ($61-85$) | `amber` |
| **Alert** | Normal | Normal | **Mencurigakan (Anomali Jaringan)** | Sedang ($31-60$) | `amber` |
| **Normal** | Normal | Normal | **Normal (Aman Terverifikasi)** | Rendah ($0-30$) | `emerald` |
| **Bebas / Alert** | **Judol** | **Investasi Menyesatkan** | **Belum Dapat Disimpulkan (Disparitas AI)** | Sedang ($40-65$) | `amber` |

---

## 🔌 4. Pemetaan Endpoint REST API & Hak Akses (RBAC)

### 4.1. Daftar Endpoint Utama (`backend/routes/api.php`)
| Metode | URI Endpoint | Fungsi di `js/api.js` | Hak Akses | Deskripsi |
| :--- | :--- | :--- | :--- | :--- |
| `POST` | `/api/tabayyun/analyze` & `/api/analyze-link` | `analyzeUrl(url)` | Publik | Analisis URL dengan Suricata + Parallel Dual AI |
| `GET` | `/api/tabayyun/history` | - | Publik / Staf | Mengambil riwayat pemeriksaan tautan (`link_scans`) |
| `POST` | `/api/login` | `login(u, p)` | Publik | Otentikasi staf admin & penerbitan Bearer token |
| `POST` | `/api/logout` | `logout()` | Staf Admin | pencabutan token sesi aktif |
| `POST` | `/api/reports` | `submitReport(data)` | Publik | Pengiriman laporan aduan warga (Tiket `LPRxxx`) |
| `GET` | `/api/reports` | `getReports()` | Staf Admin | Daftar riwayat laporan aduan masyarakat |
| `PUT` | `/api/reports/{id}` | `updateReportStatus()` | Staf Admin | Verifikasi status & catatan laporan aduan |
| `DELETE` | `/api/reports/{id}` | `deleteReport(id)` | Super Admin | Hapus permanen laporan aduan |
| `GET` | `/api/articles` | `getArticles()` | Publik | Daftar artikel edukasi Fiqih Siber |
| `POST/PUT`| `/api/articles` | `create/updateArticle`| Staf Admin | Tambah & perbarui artikel edukasi |
| `DELETE` | `/api/articles/{id}` | `deleteArticle(id)` | Super Admin | Hapus artikel edukasi |
| `GET` | `/api/prinsip-4t` | `getPrinsip4T()` | Publik | Ambil konfigurasi dinamis Kaidah 4T |
| `PUT` | `/api/prinsip-4t/{id}` | `updatePrinsip4T()` | Staf Admin | Perbarui isi Kaidah 4T |
| `GET` | `/api/case-studies` | `getCaseStudies()` | Publik | Ambil soal simulasi Cyber Guard Test |
| `POST/PUT`| `/api/case-studies` | `create/updateCaseStudy`| Staf Admin | Kelola soal simulasi Cyber Guard |
| `DELETE` | `/api/case-studies/{id}` | `deleteCaseStudy(id)` | Super Admin | Hapus soal simulasi Cyber Guard |
| `GET/POST/PUT/DELETE` | `/api/admins` | `get/create/update/deleteAdmin` | Super Admin | Manajemen akun staf admin |

### 4.2. Matriks Role-Based Access Control (RBAC)
- **Super Admin (`admin`):** Akses penuh ke seluruh fitur, termasuk menghapus data permanen (`DELETE`), mengelola akun staf admin (`akun.blade.php`), dan menguji koneksi Dual AI (`integrasi-ai.blade.php`).
- **Admin Basic (`analis`):** Dapat menulis/mengedit artikel, mengelola soal studi kasus, dan memverifikasi status laporan warga, tetapi **ditolak (`403 Forbidden`)** jika mencoba menghapus data permanen atau mengelola akun staf.

---

## 🐳 5. Arsitektur Deployment Modern (Vercel FrankenPHP)

Proyek dilengkapi konfigurasi kontainerisasi modern (`Dockerfile`, `Caddyfile`, dan `vercel.json`):
1. **Unified Hosting:** Frontend Blade dan Backend Laravel berjalan dalam satu kesatuan domain tanpa masalah CORS.
2. **FrankenPHP Runtime (PHP 8.4 + Caddy):** Berjalan secara *stateless* dan *scale-to-zero* di jaringan edge.
3. **Konfigurasi `.env` Utama:**
   ```env
   GEMINI_API_KEY=AIzaSyXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
   GEMINI_MODEL=gemini-1.5-flash
   DEEPSEEK_API_KEY=sk-XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
   DEEPSEEK_MODEL=deepseek-chat
   SURICATA_ENABLED=true
   SURICATA_EVE_PATH=storage/logs/suricata/eve.json
   ```

