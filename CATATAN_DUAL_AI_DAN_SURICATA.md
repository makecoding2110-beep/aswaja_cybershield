# 🛡️ Catatan Khusus: Integrasi Suricata NIDS, UrlThreatAnalyzer & Google Gemini AI
## Sistem Deteksi Tautan Judi Online (Judol) & Investasi Menyesatkan — Aswaja CyberShield

Dokumen ini disusun khusus sebagai **panduan teknis bagi Penguji dan Dewan Juri** mengenai cara kerja kolaborasi **Sensor Jaringan Suricata NIDS (`eve.json` & `suricata.rules`)**, **UrlThreatAnalyzer (Deteksi 3 Teknik Penyusupan Tautan)**, dan **Google Gemini AI (`gemini-3.5-flash-lite`)** dalam mendeteksi ancaman kejahatan siber berbasis **Judi Online (*Judol*)** dan **Investasi Menyesatkan (*Investasi Bodong*)**.

---

## 🗺️ 1. Diagram Arsitektur Multi-Layer Detection

```mermaid
flowchart TD
    URL(["🔗 Input URL Target (Teks Murni)"]) --> ZeroFetch["1. Zero-Fetch Security Guard\n(Inspeksi Pasif Tanpa Mengeksekusi Skrip Berbahaya Remote)"]

    ZeroFetch --> Normalizer["2. Normalisasi & URL Validation\n• Dekode Percent-Encoding Bertingkat (Maks 3x)\n• Deteksi Punycode / IDN (xn--)\n• SSRF Guard (Blokir Private/Loopback IP)"]

    Normalizer --> Unmasker["3. Unmasking & Rebouncer Resolver\n• Buka kedok URL Masking (share.google, bit.ly, s.id, shortener)\n• Ekstraksi URL Tujuan Akhir secara aman (HTTP Head/Trace)\n• Audit menyeluruh pada situs tujuan akhir"]

    Unmasker --> ThreatEngine["4. UrlThreatAnalyzer\n(Deteksi 6 Teknik Manipulasi & Penyusupan Judol)"]
    Unmasker --> SuricataEngine["5. Sensor Jaringan: Suricata NIDS\n(SuricataIntegrationService & aswaja-local.rules)"]

    subgraph ThreatBox["🔍 Analisis Struktur URL: UrlThreatAnalyzer (Bobot 35%)"]
        ThreatEngine --> Tech1["• Web Defacement & Subdomain Hijacking\n(.go.id, .ac.id + /wp-content/uploads/ + Angka Hoki 88/138/777)"]
        ThreatEngine --> Tech2["• Open Redirect (Cloaking)\n(?url=, ?redirect=, multi-host, encoding berlapis)"]
        ThreatEngine --> Tech3["• Typosquatting & Homoglif Lembaga Resmi\n(0→o, 1→l, rn→m, vv→w, pencatutan nama instansi di domain komersial)"]
        ThreatEngine --> Tech4["• URL Masking & Domain Rebouncer\n(Penyembunyian tujuan di balik domain tepercaya Google/Shortener)"]
        ThreatEngine --> Tech5["• Dual-Persona SEO Cloaking\n(Komparasi Pengunjung Normal vs Mesin Pencari Googlebot)"]
        ThreatEngine --> Tech6["• Kamuflase Finansial\n(Nama domain investasi/trading namun setoran mengarah ke slot judol)"]
    end

    subgraph SuricataBox["📡 Sensor Jaringan: Suricata NIDS (Bobot 30%)"]
        SuricataEngine --> EveLog["Baca Telemetri Jaringan (storage/logs/suricata/eve.json):\n• DNS Query (rrname)\n• TLS SNI Handshake (sni)\n• HTTP Host & URI\n• Alert Signature (SID 1000000 - 1000103)"]
        EveLog --> LocalRules["Aturan Signature Khusus (infra/suricata/rules/aswaja-local.rules):\n• SID 1000101: Kata kunci judol pada URI\n• SID 1000102: Parameter open redirect ke URL eksternal\n• SID 1000103: Query DNS domain kata kunci judol"]
    end

    ThreatBox --> Gemini["6. Kognitif AI: Google Gemini AI (gemini-3.5-flash-lite) (Bobot 30%)\n• Menerima Bukti Awal dari Threat Analyzer & Suricata\n• Menerapkan 3 Aturan Ketat Analisis Keamanan Siber\n• Analisis Semantik & Penyamaran Leetspeak (sl0t, g4c0r)\n• Evaluasi Kaidah Fiqih Siber 4T\n• Keluaran JSON Terstruktur (techniques, indicators, confidence)"]
    SuricataBox --> Gemini

    Gemini --> RiskEngine["7. RiskAssessmentService & Synthesis\nKalkulasi Terbobot: 35% Threat + 30% Suricata + 30% Gemini + 5% URL"]

    RiskEngine --> OutRendah["🟢 TINGKAT RISIKO: RENDAH (0%–25%)\n• Tema Hijau Emerald & Ikon Perisai Centang\n• Tautan bersih resmi atau indikasi awal promosi luar"]
    RiskEngine --> OutSedang["🟡 TINGKAT RISIKO: SEDANG (26%–50%)\n• Tema Kuning Amber & Ikon Warning\n• Menggunakan kedok investasi, trading, atau bonus pendaftaran"]
    RiskEngine --> OutTinggi["🟠 TINGKAT RISIKO: TINGGI (51%–84%)\n• Tema Oranye & Ikon Investigasi\n• Terdeteksi kata kunci fitur taruhan judi & anomali teknis"]
    RiskEngine --> OutBerat["🔴 TINGKAT RISIKO: BERAT (85%–100%)\n• Tema Merah Danger & Ikon Bahaya Kritis\n• Mencatut instansi resmi, defacement, cloaking & pencurian data"]
```

---

## 📡 2. Suricata NIDS & Berkas Telemetri `eve.json`

### 2.1. Peran Suricata NIDS dalam Arsitektur
**Suricata** adalah mesin pendeteksi intrusi jaringan (*Network Intrusion Detection System* — NIDS) berstandar industri. Suricata bertindak sebagai **sensor deterministik garis depan** yang memeriksa paket pada level protokol jaringan:
1. **Kecepatan Mikrodetik:** Mencocokkan tanda ancaman secara instan berdasarkan nomor *Signature ID* (`SID`).
2. **Inspeksi TLS SNI:** Tetap mampu membaca domain tujuan pada sambungan HTTPS (`tls.sni` saat *ClientHello*) sebelum aliran data dienkripsi.
3. **Log EVE JSON (`eve.json`):** Berkas log telemetri standar yang diekstraksi dan diparsing oleh [`SuricataIntegrationService.php`](file:///d:/cybershield/backend/app/Services/SuricataIntegrationService.php).

### 2.2. Aturan Signature Suricata Resmi
Aturan signature disimpan di [`suricata.rules`](file:///d:/cybershield/suricata.rules) dan [`infra/suricata/rules/aswaja-local.rules`](file:///d:/cybershield/infra/suricata/rules/aswaja-local.rules):

| Nomor SID | Target & Prioritas | Deskripsi Signature |
| :--- | :--- | :--- |
| **`SID: 1000000`** | `PASS` (Priority 4) | **Whitelist Regulator:** Domain resmi `.ojk.go.id`, `.bappebti.go.id`, `.bi.go.id`, `.kominfo.go.id`. |
| **`SID: 1000001`** | `ALERT` (Priority 1) | **[Tingkat Kuat]** Frasa eksplisit judol (`slot-gacor`, `situs-togel`, `casino-online`, `slot88`, `slot777`). |
| **`SID: 1000002`** | `ALERT` (Priority 2) | **[Tingkat Menengah]** Fitur taruhan (`maxwin`, `jackpot`, `scatter`, `free-spin`, `rtp-live`). |
| **`SID: 1000003`** | `ALERT` (Priority 3) | **[Kontekstual]** Istilah mitologi (`zeus`, `dewa`, `thor`, `dragon`, `olympus`). |
| **`SID: 1000004`** | `ALERT` (Priority 1) | **Transaksi Judol:** Rute deposit kilat (`deposit-dana`, `deposit-qris`, `depo-pulsa`) & TLS SNI gacor. |
| **`SID: 1000005`** | `ALERT` (Priority 2) | **TLD Murahan:** Ekstensi domain murah/sindikat (`.vip`, `.cc`, `.xyz`, `.top`, `.fun`, `.click`, `.icu`). |
| **`SID: 1000006`** | `ALERT` (Priority 1) | **Investasi Bodong:** Janji keuntungan fiktif (`robot-trading`, `profit-pasti`, `cuan-harian`, `auto-profit`). |
| **`SID: 1000007`** | `ALERT` (Priority 3) | **Afiliasi Perekrutan:** Parameter member sindikat (`?ref=`, `?aff=`, `?agent=`, `?referral=`). |
| **`SID: 1000008`** | `ALERT` (Priority 1) | **Berkas Berbahaya:** Unduhan APK/EXE mencurigakan yang disebarkan lewat tautan. |
| **`SID: 1000101`** | `ALERT` (Priority 1) | **URI Judol Baru:** `http.uri` memuat kata kunci `/(slot\|gacor\|maxwin\|togel\|sbobet\|scatter\|rtp)/i`. |
| **`SID: 1000102`** | `ALERT` (Priority 1) | **Open Redirect:** Parameter redirect mengarah ke URL eksternal `https?://` atau URL-encoded. |
| **`SID: 1000103`** | `ALERT` (Priority 1) | **DNS Judol:** Query DNS domain yang mengandung kata kunci judol. |

### 2.3. Pengujian Sintaks & Tuning False Positive
1. **Pengujian Rule (`suricata -T`):**
   ```bash
   suricata -T -c /etc/suricata/suricata.yaml -S infra/suricata/rules/aswaja-local.rules
   ```
2. **Menyesuaikan False Positive:**
   - Parameter `return=` atau `url=` internal aplikasi sah dapat ditangani dengan mengecualikan subnet lokal (`!$HOME_NET`) atau menerapkan *thresholding* frekuensi alert pada `threshold.config`.

---

## 🔎 3. Deteksi 6 Teknik Manipulasi & Penyusupan Tautan (`UrlThreatAnalyzer.php`)

Menanggapi evolusi kejahatan siber di mana sindikat judol tidak lagi hanya membuat domain baru melainkan menyusup dan menyamar di balik ekosistem tepercaya, sistem dilengkapi analis ancaman khusus ([`UrlThreatAnalyzer.php`](file:///d:/cybershield/backend/app/Services/UrlThreatAnalyzer.php)) yang mendeteksi **6 Teknik Manipulasi Tautan**:

1. **Web Defacement & Subdomain Hijacking:**
   - Menyasar domain instansi bereputasi tinggi (`.go.id`, `.ac.id`, `.sch.id`, `.mil.id`, `.desa.id`).
   - Menyusup lewat direktori upload CMS yang rentan (`/wp-content/uploads/`, `/uploads/`, `/journal/`, `/modules/`).
   - Membuat subdomain liar yang menyematkan angka hoki judol (`88`, `138`, `777`, `vip`, dll.).

2. **Open Redirect (Pembelokan URL Otomatis):**
   - Memanfaatkan parameter redirect portal resmi untuk menyamarkan tautan judol.
   - Mendeteksi parameter manipulasi (`url`, `redirect`, `to`, `dest`, `link`, `next`, dll.).
   - Mendeteksi multi-layer percent-encoding dan target pengalihan ke TLD murah sindikat.

3. **Typosquatting & Serangan Homoglif:**
   - Meniru nama lembaga regulator/bank resmi (OJK, Bappebti, Kominfo, BI, BCA, BRI, Mandiri, dll.).
   - Mengganti huruf latin dengan angka atau karakter yang menyerupai (misal: `0` $\to$ `o`, `1` $\to$ `l/i`, `rn` $\to$ `m`, `vv` $\to$ `w`).
   - Mendaftarkan nama instansi di TLD komersial dengan embel-embel tipuan (`-resmi`, `-login`, `-bansos`, `-daftar`).

4. **URL Masking / Link Masking (Penyembunyian Tautan):**
   - Menyembunyikan alamat URL asli (tujuan akhir) di balik domain atau layanan yang terlihat tepercaya (seperti ekosistem resmi Google `https://share.google/...`, pemendek link `bit.ly`, `s.id`, `tinyurl.com`, `t.co`, atau `linktr.ee`).
   - Pengguna dikelabui oleh nama domain aman yang bersih saat dibagikan di media sosial/pesan instan, sebelum dialihkan ke situs tujuan aslinya.

5. **Domain Rebouncer / Proxy Link (Jembatan Pengalihan):**
   - Memanfaatkan fitur berbagi bawaan dari ekosistem tepercaya sebagai jembatan perantara (*redirector/proxy*).
   - Mesin unmasking Aswaja CyberShield secara otomatis menelusuri rantai pengalihan HTTP (301/302) secara aman untuk mengekstrak alamat tujuan akhir (`https://olymptrade.com/`).
   - Melakukan evaluasi mendalam (*deep unmasked threat analysis*) pada muatan situs tujuan akhir dan mewariskan tingkat risiko serta kategori yang sesuai (misal: *Waspada: Terindikasi Investasi Menyesatkan* untuk platform *Binary Options* ilegal).

6. **SEO Cloaking / Conditional Defacement (Manipulasi Mesin Pencari Googlebot):**
   - Menyusupi server web situs resmi pemerintah (`.go.id`) atau akademik (`.ac.id`) menggunakan skrip bersyarat (*conditional response script*).
   - **Tampilan Berbeda (*Cloaking*):** Jika dibuka oleh manusia/browser normal, server menampilkan berita resmi instansi. Namun jika dirayapi oleh *crawler* mesin pencari (*Googlebot*), server menyajikan muatan promosi judi online secara masif untuk mendominasi hasil pencarian masyarakat.

---

### 🔬 3.1. Mesin Dual-Persona Fetching: Mengungkap Bukti Kasus Kemendagri RI

Kasus nyata pengujian pada domain `https://bbpkapdn1.kemendagri.go.id/` membuktikan efektivitas mesin **Dual-Persona Fetching** Aswaja CyberShield:

| Parameter Uji | Persona 1: Pengunjung Manusia (Chrome) | Persona 2: Robot Mesin Pencari (Googlebot) |
| :--- | :--- | :--- |
| **HTTP User-Agent** | `Mozilla/5.0 ... Chrome/125.0` | `Mozilla/5.0 (compatible; Googlebot/2.1)` |
| **Ukuran Respon** | **30 KB** | **523 KB** (Muatan backdoor judol 17x lebih besar) |
| **Judul Halaman (`<title>`)** | `Berita & Peristiwa` | `CUMI787 : Asli Paten Situs Slot Gacor Hari Ini Dengan Permainan Slot88 Resmi Pasti Kasih JP Maxwin` |
| **Kata Kunci Terdeteksi** | Berita kedinasan ASN | `slot`, `gacor`, `maxwin`, `cumi787`, `slot88`, `deposit pulsa` |
| **Status Forensik** | Halaman resmi terlihat bersih | **Positif Terinfeksi Skrip SEO Cloaking Sindikat Judol** |
| **Keputusan Sistem** | **Tingkat Risiko 95% (Kritis)** — *Waspada: Terindikasi Penyusupan (SEO Cloaking)* |

---

### 🛡️ 3.2. Tiga Aturan Ketat Analisis Keamanan Siber

Sistem deteksi Aswaja CyberShield dipandu oleh 3 aturan ketat operasional:

1. **Aturan 1 — Deteksi URL Masking & Rebouncer:**
   - Dilarang memberikan status Aman (0%) hanya karena domain pembungkusnya tepercaya (`share.google`, `bit.ly`, `s.id`, dll.).
   - Telusuri URL Tujuan Akhir dan audit konten tujuannya. Jika tujuan akhirnya judol/binary options, tetapkan skor tinggi (70% - 100%) dengan teknik `url_masking`.
   - Jika tujuan akhir tidak dapat diselesaikan (*unresolved wrapper*), berikan status **"Perlu Pemeriksaan"** dengan risiko sedang (45% - 60%), **dilarang memvonis Aman (0%)**.
2. **Aturan 2 — Deteksi SEO Cloaking pada Domain Resmi (.go.id / .ac.id):**
   - Jangan langsung memvonis domain resmi sebagai Aman (0%) tanpa uji dual-persona.
   - Komparasikan tampilan normal vs tampilan Googlebot. Jika ditemukan indikator judol pada tampilan crawler, tetapkan vonis **"Waspada: Terindikasi Penyusupan (SEO Cloaking)"** dengan risiko tinggi (75% - 95%).
3. **Aturan 3 — Kewaspadaan Ekstensi Domain Berisiko (TLD):**
   - TLD murah/bebas (`.cc`, `.xyz`, `.top`, `.vip`, `.site`) diperlakukan dengan kewaspadaan tinggi, dan struktur path serta kontennya diaudit ketat sebelum skor diputuskan.

---

## 🤖 4. Integrasi Google Gemini AI (`GeminiService.php`)

Google Gemini AI bertindak sebagai mesin kognitif semantik utama:
- **Model:** `gemini-3.5-flash-lite` (latensi ultra-rendah di bawah 1 detik).
- **Prompt Injection Defense:** URL diperlakukan sebagai **data teks pasif murni**, bukan instruksi komputasi yang dapat dieksekusi.
- **Zero Remote Execution (Keamanan Tinggi):** Sistem tidak menyuruh Gemini untuk menjelajahi (*browse*) atau mengeksekusi tautan target di internet secara sembarangan, demi mencegah risiko infeksi malware, eksekusi kode berbahaya jarak jauh, maupun pelacakan IP server oleh sindikat.

### 4.1. Dari Mana Gemini Mengambil Sumber Datanya? (*Data Sourcing Mechanism*)

Gemini tidak mencari data mentah sendiri ke internet, melainkan mengkorelasikan **3 lapis sumber data terpadu** yang disuplai oleh arsitektur platform:

```
[ Pengguna Memasukkan URL ]
            │
            ▼
┌─────────────────────────────────────────────────────────────┐
│ 1. LAYER BUKTI FORENSIK TEKNIS (UrlThreatAnalyzer & Suricata)│
│    - Struktur URL: Domain, TLD, Subpath, Parameter Query    │
│    - Indikasi 4 Modus: Defacement, Hijacking, Redirect, dll.│
│    - Sinyal TrustPositif Komdigi & Signature NIDS           │
└─────────────────────────────┬───────────────────────────────┘
                              │
                              ▼  (Dikemas dalam JSON $technicalContext)
┌─────────────────────────────────────────────────────────────┐
│ 2. GOOGLE GEMINI AI ENGINE (GeminiService.php)              │
│    Mengolah & menghubungkan 3 Lapis Sumber:                 │
│    a. Data Forensik Nyata (Hasil Ekstraksi Layer 1)         │
│    b. System Prompt Pakar (Kamus Kata Kunci & Aturan Fiqih) │
│    c. Pre-trained Knowledge (Kamus Slang & Logika Muamalah) │
└─────────────────────────────┬───────────────────────────────┘
                              │
                              ▼
[ Output Terstruktur: Kategori, Skor Risiko, & Justifikasi Fiqih 4T ]
```

1. **Lapis 1: Bukti Forensik Lokal Nyata (`$technicalContext`):**
   Sebelum request dikirim ke Google API, sistem lokal (`UrlThreatAnalyzer.php` dan `SuricataIntegrationService.php`) membedah URL secara terisolasi:
   - Ekstraksi domain, ekstensi TLD (.go.id, .vip, .xyz), subpath direktori (seperti `/wp-content/uploads/slot/`), dan parameter query (`?url=`, `?dest=`, `?deposit=`).
   - Deteksi 4 modus manipulasi tautan (Web Defacement, Subdomain Hijacking, Open Redirect, Typosquatting).
   - Status pemblokiran pada database DNS TrustPositif Komdigi RI serta *signature alert* NIDS Suricata.
   Seluruh temuan faktual ini diserahkan ke Gemini sebagai data bukti awal (*initial evidence*).

2. **Lapis 2: Pedoman Pakar & Kamus Domain (`System Prompt` di `GeminiService.php`):**
   Sistem menyuntikkan instruksi khusus sebagai "aturan berpikir" bagi Gemini:
   - **Kamus Kata Kunci 3 Tingkat:**
     - *Tingkat Kuat:* `slot gacor`, `maxwin`, `casino online`, `bandar togel`, `depo slot`.
     - *Tingkat Menengah:* `jackpot`, `scatter`, `free spin`, `rtp live`.
     - *Tingkat Kontekstual:* `zeus`, `olympus`, `mahjong` (harus dicek hati-hati agar tidak salah memvonis situs sejarah/game wajar).
   - **Aturan Pengecualian Institusi:** Membedakan dengan tegas antara domain institusi resmi yang disusupi (*defacement*) dengan domain sindikat murni (*kamuflase investasi*).

3. **Lapis 3: Pengetahuan Semantik Bawaan (*Pre-trained Knowledge Base* Gemini):**
   Sebagai LLM mutakhir, Gemini memiliki pemahaman bahasa yang luas mengenai:
   - **Bahasa Gaul & Slang Sindikat Indonesia:** Memahami istilah bujukan cuan di media sosial (*"titip dana cair 5 juta"*, *"arisan amanah"*, *"robot trading auto-cuan"*).
   - **Logika Fiqih Muamalah Syariah:** Memahami batasan akad perniagaan yang sah (*al-bai'*) vs penipuan (*al-gharar*) dan perjudian (*al-maysir*).

---

### 4.2. Penjelasan Konkret: Apa Maksud "Memverifikasi Kesesuaian Prinsip 4T dalam Muatan Penawaran"?

Maksudnya adalah: Gemini mengevaluasi bujukan atau teks penawaran yang ada pada tautan menggunakan **standar etika dan hukum Fiqih Siber (Kaidah 4T Aswaja)**:

| Kaidah 4T | Konsep Fiqih | Yang Diverifikasi oleh Gemini pada Muatan Penawaran | Indikasi Pelanggaran yang Dideteksi |
| :--- | :--- | :--- | :--- |
| **1. Tawaqquf** <br>*(Menahan Diri)* | Mengambil jeda reflektif dan tidak tergesa-gesa mengikuti bujukan emosional. | **Apakah penawaran memakai manipulasi psikologis / urgensi palsu (*FOMO*)?** Penawaran wajar tidak memaksa calon pengguna bertindak impulsif. | Iming-iming: *"Daftar 5 menit lagi modal berlipat 10x"*, *"Pola room hari ini pasti maxwin"*, *"Slot khusus pagi ini jangan sampai kehabisan"*. |
| **2. Tastabbus** <br>*(Meneliti Sanad/Fakta)* | Melacak silsilah sumber (*sanad*), legalitas hukum, dan rekam jejak fakta digital. | **Apakah sanad domain, izin regulator OJK/Bappebti, dan alur transaksinya jelas?** Memastikan transparansi identitas penyedia penawaran. | Mengaku *"investasi legal OJK"*, namun domain disamarkan di TLD murah, rekening deposit memakai akun perseorangan atau bot e-wallet yang tidak berizin. |
| **3. Tatsabbut** <br>*(Konfirmasi Fiqih/Otoritas)* | Memastikan substansi akad bebas dari unsur judi (*maysir*) dan ketidakjelasan (*gharar*). | **Apakah model bisnis penawaran halal secara muamalah atau murni taruhan untung-untungan?** Memastikan tidak ada pengorbanan uang sesama pengguna. | Penawaran berkedok *"game simulasi profit"* atau *"trading kilat"*, padahal alur transaksinya mengarah ke deposit mesin slot judi online (*zero-sum game*). |
| **4. Ta'aqqul** <br>*(Menimbang Maslahat)* | Menimbang dampak maslahat (manfaat) dan mudharat (kerusakan) sebelum menyebarkan informasi. | **Apa dampak akhir jika tautan ini disebarkan ke keluarga/masyarakat?** Menilai risiko sosial dan potensi kehancuran ekonomi korban. | Menggunakan skema berantai (*referral/member-get-member*) yang berujung pada kecanduan judi, jeratan pinjol ilegal, dan keretakan ekonomi keluarga. |

### 4.3. Format Keluaran Terstruktur (JSON)

Gemini memformulasikan seluruh hasil analisis teknis dan evaluasi 4T ke dalam format JSON standar industri:
```json
{
  "category": "judol",
  "risk_level": "critical",
  "risk_score": 92,
  "techniques": ["web_defacement", "open_redirect"],
  "indicators": [
    {
      "name": "defacement_path",
      "evidence": "/wp-content/uploads/slot-gacor/",
      "severity": "critical",
      "explanation": "Penyusupan direktori upload CMS dengan konten judol."
    }
  ],
  "reasoning": "Ditemukan indikasi kuat penyusupan halaman slot pada domain institusi pemerintah. Melanggar prinsip Tastabbus (sanad tidak sah) dan Tatsabbut (mengandung maysir).",
  "confidence": 0.95
}
```

---

## ⚖️ 5. Standardisasi 4 Tingkat Risiko Resmi (Risk Level & Color Matrix)

Berdasarkan implementasi produksi di [`RiskAssessmentService.php`](file:///d:/cybershield/backend/app/Services/RiskAssessmentService.php) dan antarmuka [`frontend/tabayyun.blade.php`](file:///d:/cybershield/frontend/tabayyun.blade.php):

| Tingkat Risiko | Rentang Skor | Warna Token / UI | Ikon Perisai | Keterangan & Tindakan |
| :--- | :---: | :---: | :---: | :--- |
| **Rendah (Aman)** | **0% – 25%** | **Emerald Green** (`#059669`) | `verified_user` | Domain resmi/legal, bebas signature bahaya. Tautan aman diakses. |
| **Sedang** | **26% – 50%** | **Amber Yellow** (`#D97706`) | `warning` | Menggunakan kedok investasi/trading atau TLD promosi. Perlu kewaspadaan. |
| **Tinggi** | **51% – 84%** | **Orange Alert** (`#EA580C`) | `gpp_maybe` | Terdeteksi kata kunci fitur taruhan judi (maxwin, scatter) atau anomali URL. |
| **Berat (Kritis)** | **85% – 100%** | **Crimson Red** (`#DC2626`) | `gpp_bad` | Terindikasi kuat jaringan judi online, defacement web instansi, atau phishing. |

### Tampilan Kartu Hasil Analisis:
1. **Untuk Hasil Aman (0% – 25%):**
   - Menampilkan label skor **"Tingkat Keamanan"** (Skor Keamanan $75\% - 100\%$).
   - Dilengkapi 3 poin verifikasi: Audit Sanad Domain, Analisis Muatan & Pola, serta Rekomendasi Tabayyun.
2. **Untuk Hasil Berbahaya (26% – 100%):**
   - Menampilkan label skor **"Tingkat Bahaya"** (Skor Bahaya $26\% - 100\%$).
   - Menampilkan modul **"Rangkuman Sederhana"** dalam bahasa awam non-teknis.
   - Menampilkan modul **"Teknik Penyusupan yang Terdeteksi"** (*Web Defacement*, *Open Redirect*, *Typosquatting*, *Kamuflase Finansial*, *URL Masking*, *Domain Rebouncer*, *SEO Cloaking*).
   - Menampilkan rincian **"Temuan Analisis Tautan & Alasan Verifikasi"** dengan bukti teks aman (dinonaktifkan agar tidak dapat diklik).

---

## 🗄️ 6. Skema Basis Data Forensik (`link_scans`)

Pada implementasi Supabase PostgreSQL:
- **Konsistensi Riwayat Scan (*Forensic Cache*):** URL identik yang diperiksa berulang akan merujuk pada rekam jejak audit yang sudah ada untuk menjamin konsistensi vonis 100%.
- **Kolom JSONB Hasil Analisis:**
  - `suricata_result`: Menyimpan data mentah temuan sensor NIDS dan signature yang terpicu.
  - `gemini_result`: Menyimpan respon kognitif AI, alasan klasifikasi, dan skor risiko dari Google Gemini.
- **Katalog Bukti:**
  - `techniques`: Array JSONB teknik penyusupan yang teridentifikasi (`web_defacement`, `open_redirect`, `typosquatting`, `kamuflase_investasi`, `url_masking`, `domain_rebouncer`, `seo_cloaking`).
  - `indicators`: Array JSONB bukti terperinci (`name`, `evidence`, `severity`, `explanation`).
- **Keputusan Akhir:**
  - `final_category`: Kategori terstandardisasi (`terindikasi_judol`, `terindikasi_investasi`, `mencurigakan`, `normal`).
  - `risk_level`: Tingkat keparahan (`rendah`, `sedang`, `tinggi`, `berat`).
  - `risk_score`: Skor numerik $0 - 100$.

---

## 🧪 7. Hasil Pengujian Unit Test Komprehensif (10 Dari 10 Lulus 100%)

Seluruh logika deteksi telah diverifikasi secara ketat melalui test suite otomatis [`backend/tests/url_threat_analyzer_test.php`](file:///d:/cybershield/backend/tests/url_threat_analyzer_test.php):

| No | Kasus Pengujian Unit Test | Target Sampel / Payload (Defanged) | Hasil & Indikator Verifikasi | Status |
| :-: | :--- | :--- | :--- | :-: |
| **1** | **Web Defacement Path (.go.id)** | `hxxps://pemda-contoh[.]go[.]id/wp-content/uploads/2026/05/slot-gacor/` | Terdeteksi `web_defacement`, path upload CMS disusupi judol | ✅ **PASS** |
| **2** | **Subdomain Hijacking (.desa.id)** | `hxxps://slot88[.]desaku[.]desa[.]id/` | Terdeteksi `web_defacement`, subdomain memuat angka hoki 88 | ✅ **PASS** |
| **3** | **Open Redirect ke TLD Sindikat (.vip)** | `hxxps://portal-resmi[.]go[.]id/redirect?url=hxxps%3A%2F%2Fjudol-gacor[.]vip%2Fdaftar` | Terdeteksi `open_redirect`, redirect ke domain disposable judol | ✅ **PASS** |
| **4** | **Typosquatting Lembaga Negara** | `hxxps://ojk-klaim-bansos[.]com/login` | Terdeteksi `typosquatting`, pencatutan OJK di domain komersial | ✅ **PASS** |
| **5** | **Zero False Positive Otoritas (OJK)** | `https://ojk.go.id/` | Bersih 100%, skor ancaman 0% Aman | ✅ **PASS** |
| **6** | **Zero False Positive Kampus (UGM)** | `https://ugm.ac.id/` | Bersih 100%, skor ancaman 0% Aman | ✅ **PASS** |
| **7** | **Kamuflase Finansial / Trading Judol** | `hxxps://cuan-trading-harian-vip88[.]vip/deposit-slot` | Terdeteksi `kamuflase_investasi`, bukan defacement | ✅ **PASS** |
| **8** | **URL Masking & Rebouncer (Google Share)** | `hxxps://share.google/dZPBsDvJTBpwcuDLo` $\to$ `olymptrade[.]com` | Terdeteksi `url_masking` & `domain_rebouncer`, skor tinggi 80%+ | ✅ **PASS** |
| **9** | **Zero False Positive Edukasi (Codepolitan)**| `https://www.codepolitan.com/bootcamp/` | Bersih 100%, skor ancaman 0% Aman | ✅ **PASS** |
| **10**| **Dual-Persona SEO Cloaking (Kemendagri)** | `hxxps://bbpkapdn1.kemendagri[.]go[.]id/` | Terdeteksi `seo_cloaking` & `web_defacement` (CUMI787 Slot), skor 95% | ✅ **PASS** |

> **Kesimpulan Pengujian:** 10 dari 10 pengujian berhasil lolos (100% akurasi) tanpa adanya *false positive* pada domain resmi dan tanpa kebocoran *false negative* pada teknik penyusupan tersembunyi.
