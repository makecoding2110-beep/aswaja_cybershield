# 🛡️ Catatan Khusus: Integrasi Suricata NIDS, UrlThreatAnalyzer & Google Gemini AI
## Sistem Deteksi Tautan Judi Online (Judol) & Investasi Menyesatkan — Aswaja CyberShield

Dokumen ini disusun khusus sebagai **panduan teknis bagi Penguji dan Dewan Juri** mengenai cara kerja kolaborasi **Sensor Jaringan Suricata NIDS (`eve.json` & `suricata.rules`)**, **UrlThreatAnalyzer (Deteksi 3 Teknik Penyusupan Tautan)**, dan **Google Gemini AI** dalam mendeteksi ancaman kejahatan siber berbasis **Judi Online (*Judol*)** dan **Investasi Menyesatkan (*Investasi Bodong*)**.

---

## 🗺️ 1. Diagram Arsitektur Multi-Layer Detection

```mermaid
flowchart TD
    URL(["🔗 Input URL Target (Teks Murni)"]) --> ZeroFetch["1. Zero-Fetch Security Guard\n(Tanpa Membuka / Mengunduh Konten Remote)"]

    ZeroFetch --> Normalizer["2. Normalisasi & URL Validation\n• Dekode Percent-Encoding Bertingkat (Maks 3x)\n• Deteksi Punycode / IDN (xn--)\n• SSRF Guard (Blokir Private/Loopback IP)"]

    Normalizer --> ThreatEngine["3. UrlThreatAnalyzer\n(Deteksi 3 Teknik Penyusupan Judol)"]
    Normalizer --> SuricataEngine["4. Sensor Jaringan: Suricata NIDS\n(SuricataIntegrationService & aswaja-local.rules)"]

    subgraph ThreatBox["🔍 Analisis Struktur URL: UrlThreatAnalyzer (Bobot 35%)"]
        ThreatEngine --> Tech1["• Web Defacement & Subdomain Hijacking\n(.go.id, .ac.id, .desa.id + /wp-content/uploads/ + Angka Hoki 88/138/777)"]
        ThreatEngine --> Tech2["• Open Redirect / Cloaking\n(?url=, ?redirect=, multi-host, encoding berlapis, redirect ke TLD murah)"]
        ThreatEngine --> Tech3["• Typosquatting & Homoglif Lembaga Resmi\n(0→o, 1→l, rn→m, vv→w, pencatutan nama instansi di domain komersial)"]
    end

    subgraph SuricataBox["📡 Sensor Jaringan: Suricata NIDS (Bobot 30%)"]
        SuricataEngine --> EveLog["Baca Telemetri Jaringan (storage/logs/suricata/eve.json):\n• DNS Query (rrname)\n• TLS SNI Handshake (sni)\n• HTTP Host & URI\n• Alert Signature (SID 1000000 - 1000103)"]
        EveLog --> LocalRules["Aturan Signature Khusus (infra/suricata/rules/aswaja-local.rules):\n• SID 1000101: Kata kunci judol pada URI\n• SID 1000102: Parameter open redirect ke URL eksternal\n• SID 1000103: Query DNS domain kata kunci judol"]
    end

    ThreatBox --> Gemini["5. Kognitif AI: Google Gemini AI (gemini-1.5-flash) (Bobot 30%)\n• Menerima Bukti Awal dari Threat Analyzer & Suricata\n• Analisis Semantik & Penyamaran Leetspeak (sl0t, g4c0r)\n• Evaluasi Kaidah Fiqih Siber 4T\n• Keluaran JSON Terstruktur (techniques, indicators, confidence)"]
    SuricataBox --> Gemini

    Gemini --> RiskEngine["6. RiskAssessmentService & Synthesis\nKalkulasi Terbobot: 35% Threat + 30% Suricata + 30% Gemini + 5% URL"]

    RiskEngine --> OutRendah["🟢 TINGKAT RISIKO: RENDAH (0%–25%)\n• Tema Hijau Emerald & Ikon Perisai Centang\n• Tautan bersih resmi atau indikasi awal promosi luar"]
    RiskEngine --> OutSedang["🟡 TINGKAT RISIKO: SEDANG (26%–50%)\n• Tema Kuning Amber & Ikon Warning\n• Menggunakan kedok investasi, trading, atau bonus pendaftaran"]
    RiskEngine --> OutTinggi["🟠 TINGKAT RISIKO: TINGGI (51%–84%)\n• Tema Oranye & Ikon Investigasi\n• Terdeteksi kata kunci fitur taruhan judi & anomali teknis"]
    RiskEngine --> OutBerat["🔴 TINGKAT RISIKO: BERAT (85%–100%)\n• Tema Merah Danger & Ikon Bahaya Kritis\n• Mencatut instansi resmi, defacement, phishing & pencurian data"]
```

---

## 📡 2. Suricata NIDS & Berkas Telemetri `eve.json`

### 2.1. Peran Suricata NIDS dalam Arsitektur
**Suricata** adalah mesin pendeteksi intrusi jaringan (*Network Intrusion Detection System* — NIDS) berstandar industri. Suricata bertindak sebagai **sensor deterministik garis depan** yang memeriksa paket pada level protokol jaringan:
1. **Kecepatan Mikrodetik:** Mencocokkan tanda ancaman secara instan berdasarkan nomor *Signature ID* (`SID`).
2. **Inspeksi TLS SNI:** Tetap mampu membaca domain tujuan pada sambungan HTTPS (`tls.sni` saat *ClientHello*) sebelum aliran data dienkripsi.
3. **Log EVE JSON (`eve.json`):** Berkas log standar yang diekstraksi oleh [`SuricataIntegrationService.php`](file:///d:/cybershield/backend/app/Services/SuricataIntegrationService.php).

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

## 🔎 3. Deteksi 3 Teknik Penyusupan Tautan (`UrlThreatAnalyzer.php`)

Menanggapi evolusi kejahatan siber di mana sindikat judol tidak lagi membuat domain baru melainkan menunggangi situs bereputasi tinggi, sistem dilengkapi analis khusus:

1. **Web Defacement & Subdomain Hijacking:**
   - Menyasar domain instansi bereputasi tinggi (`.go.id`, `.ac.id`, `.sch.id`, `.mil.id`, `.desa.id`).
   - Menyusup lewat direktori upload CMS yang rentan (`/wp-content/uploads/`, `/uploads/`, `/journal/`).
   - Membuat subdomain liar yang menyematkan angka hoki judol (`88`, `138`, `777`).

2. **Open Redirect (Pembelokan URL Otomatis):**
   - Memanfaatkan parameter redirect portal resmi untuk menyamarkan tautan judol.
   - Mendeteksi parameter manipulasi (`url`, `redirect`, `to`, `dest`, `link`, `next`, dll.).
   - Mendeteksi multi-layer percent-encoding dan target pengalihan ke TLD murah.

3. **Typosquatting & Serangan Homoglif:**
   - Meniru nama lembaga regulator/bank resmi (OJK, Bappebti, Kominfo, BI, BCA, BRI, Mandiri, dll.).
   - Mengganti huruf latin dengan angka atau karakter yang menyerupai (misal: `0` $\to$ `o`, `1` $\to$ `l/i`, `rn` $\to$ `m`, `vv` $\to$ `w`).
   - Mendaftarkan nama instansi di TLD komersial dengan embel-embel tipuan (`-resmi`, `-login`, `-bansos`, `-daftar`).

---

## 🤖 4. Integrasi Google Gemini AI (`GeminiService.php`)

Google Gemini AI bertindak sebagai mesin kognitif semantik utama:
- **Model:** `gemini-1.5-flash` (latensi ultra-rendah di bawah 1 detik).
- **Prompt Injection Defense:** URL diperlakukan sebagai **data teks pasif murni**, bukan instruksi komputasi.
- **Analisis Bukti Awal:** Menerima data temuan dari `UrlThreatAnalyzer` dan `Suricata NIDS` untuk diverifikasi secara semantik.
- **Keluaran Terstruktur (JSON):**
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
    "reasoning": "Ditemukan indikasi kuat penyusupan halaman slot pada domain institusi.",
    "confidence": 0.95
  }
  ```

---

## ⚖️ 5. Sistem Skor 2-State (Aman vs Berbahaya)

Mengacu pada standardisasi terbaru, sistem menghilangkan kategori perantara ("Waspada"):
1. **Keadaan AMAN:**
   - Menampilkan label **"Tingkat Keamanan"** dengan skor $0\% - 100\%$.
   - Warna token: Emerald (`#059669`).
   - Penjelasan verifikasi: Bebas dari rekam jejak pemblokiran, bebas skema taruhan/maysir, dan rekomendasi tabayyun.
2. **Keadaan BERBAHAYA:**
   - Menampilkan label **"Tingkat Bahaya"** dengan skor $0\% - 100\%$.
   - Warna token: Danger Merah (`#DC2626`).
   - Menampilkan bagian khusus **"Teknik yang Terdeteksi"** (*Web Defacement*, *Open Redirect*, *Typosquatting*) dengan bukti non-aktif (bukan link yang dapat diklik).
   - Label penegas **"Sangat Berbahaya"** disematkan bila skor bahaya $\ge 85\%$.

---

## 🗄️ 6. Catatan Skema Database Audit (`link_scans`)

Pada implementasi database Supabase PostgreSQL:
- Hasil audit disimpan secara terpisah pada kolom JSONB: `suricata_result` (Layer Sensor NIDS) dan `gemini_result` (Layer Kognitif AI).
- Seluruh teknik yang terdeteksi disimpan pada kolom `techniques` (array JSONB) dan kompilasi bukti teknis disimpan pada kolom `indicators`.
- Nilai vonis akhir disimpan pada `final_category`, `risk_level`, dan `risk_score`.
