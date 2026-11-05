# 🌐 Catatan Konsep & Penjelasan Web: Aswaja CyberShield
## Narasi, Latar Belakang, Perumusan Masalah, Indikator Deteksi Judol/Investasi Menyesatkan, dan Panduan Presentasi Kompetisi

Dokumen ini merangkum **narasi konsep utama, latar belakang, perumusan masalah, batasan masalah, arah pengembangan, target pengguna, indikator lengkap deteksi Judi Online & Investasi Menyesatkan, serta pemetaan fitur, naskah video demo, dan struktur slide presentasi** untuk kebutuhan penilaian dewan juri dan pelaporan kompetisi.

---

## 1. Latar Belakang, Masalah, dan Lahirnya Aswaja CyberShield

### A. Latar Belakang: Celah Psikologis & Maraknya Judol serta Investasi Bodong di Ruang Siber Indonesia
Perkembangan teknologi informasi dan penetrasi aplikasi pesan instan (WhatsApp, Telegram, dan media sosial) di Indonesia telah mengubah lanskap komunikasi secara drastis. Namun, kemudahan ini diiringi oleh gelombang kejahatan siber finansial yang semakin merusak, khususnya **tautan Judi Online (*Judol / Maysir Modern*)** dan **Investasi Menyesatkan (*Investasi Bodong / Skema Ponzi*)**.

Kejahatan digital masa kini tidak lagi semata-mata mengandalkan peretasan sistem yang rumit, melainkan memadukan **anomali jaringan tersembunyi** (*fast-flux domain*, pencatutan nama lembaga resmi/*typosquatting*, rotasi IP) dengan **rekayasa sosial (*social engineering*)** — sebuah metode manipulasi psikologis yang mengeksploitasi kecemasan ekonomi, harapan keuntungan instan (*cuan kilat*), hingga pencatutan simbol keagamaan dan otoritas negara (OJK/Bappebti).

### B. Beban Ganda Anak Muda di Pusaran Tipuan Digital
Dalam realitas tersebut, anak muda Indonesia (usia 18–25 tahun) berada pada posisi yang sangat rentan sekaligus menanggung **beban ganda**:
1. **Untuk Diri Sendiri:** Sebagai generasi yang sedang berjuang membangun kemandirian ekonomi, mereka menjadi target utama promosi **Judi Online berkedok game/slot gacor**, robot trading fiktif, titip dana investasi bodong, hingga lowongan kerja palsu berformat berkas berbahaya (`.apk`).
2. **Untuk Keluarga & Komunitasnya:** Mereka diposisikan sebagai *"orang yang paling melek teknologi"* di rumah, tempat orang tua dan kerabat bertanya saat menerima *broadcast* mencurigakan di grup WhatsApp keluarga — seperti tawaran arisan/investasi berkedok syariah, undangan palsu `.apk`, atau tautan investasi berhadiah instan.

Sayangnya, banyak anak muda sendiri belum memiliki **alat verifikasi teknis yang objektif** untuk memeriksa keaslian tautan secara aman, belum terlatih mengambil keputusan saat menghadapi situasi meragukan, serta bingung bagaimana menjelaskan bahaya tersebut kepada keluarga dengan bahasa yang santun dan beradab.

### C. Lahirnya Aswaja CyberShield: Menjembatani Keamanan Siber Modern dan Kearifan Kultural
Dari titik temu antara kebutuhan pertahanan teknis dan pendekatan kultural itulah **Aswaja CyberShield** lahir. Pendekatan keamanan siber konvensional selama ini cenderung kaku, elitis, atau hanya mengandalkan satu lapisan deteksi yang mudah dikelabui.

**Aswaja CyberShield** hadir menjembatani kesenjangan tersebut dengan memadukan:
1. **Teknologi Deteksi Berlapis (*Multi-Layer Hybrid Detection*):** Menggabungkan **Sensor Jaringan Suricata NIDS (`eve.json`)**, **Heuristik Struktur URL 3 Tingkat (*3-Tier Keyword Matching*)**, dan **Kecerdasan Buatan Ganda Paralel (Google Gemini + DeepSeek AI)**.
2. **Media Belajar & Dakwah Kultural Berbasis Ahlussunnah wal Jama'ah (Aswaja):** Mengontekstualisasikan perintah *Tabayyun* (QS. Al-Hujurat: 6), **4 Pilar Wasathiyah Aswaja** (*Tawasuth, Tawazun, I'tidal, Tasamuh*), serta metodologi kritik sanad **Prinsip 4T** (*Tawaqquf, Tastabbus, Tatsabbut, Ta'aqqul*) untuk menjaga harta (*hifzhul maal*) dan kehormatan (*hifzhul 'irdh*) masyarakat.

---

## 2. Perumusan Masalah, Batasan Masalah, dan Arah Pengembangan

### A. Perumusan Masalah
1. Bagaimana merancang sistem deteksi tautan **Judi Online (*Judol*)** dan **Investasi Menyesatkan (*Investasi Bodong*)** yang akurat dengan mengintegrasikan telemetri paket jaringan **Suricata NIDS (`eve.json`)** dan analisis kognitif paralel **Dual AI (Google Gemini + DeepSeek)**?
2. Bagaimana mencegah kesalahan deteksi (*false positive*) pada website umum/edukasi yang kebetulan memuat kata-kata populer/mitologi (seperti *zeus, thor, dewa, dragon*) agar tidak salah dituduh sebagai situs Judi Online?
3. Bagaimana menghitung skor risiko secara objektif, matematis, dan transparan, serta menangani kondisi ketika kedua model AI menghasilkan kesimpulan yang berbeda (*disparitas AI*)?
4. Bagaimana mengemas edukasi keamanan siber dan fiqih informasi **Ahlussunnah wal Jama'ah (4 Pilar Wasathiyah & Prinsip 4T)** ke dalam siklus **Recognize – Learn – Act** yang mudah dipahami oleh generasi muda dan masyarakat luas?

### B. Batasan Masalah
Agar sistem terfokus, mendalam, dan sesuai kaidah etika keamanan siber:
1. **Fokus Kategori Ancaman:** Sistem deteksi tautan pada modul **AI Tabayyun** difokuskan secara spesifik pada **2 domain ancaman utama**:
   - **Judi Online (*Judol / Maysir Modern*):** Mesin slot daring, togel online, kasino daring, taruhan bola/sbobet, dan gerbang deposit judi.
   - **Investasi Menyesatkan (*Investasi Bodong / Gharar*):** Robot trading ilegal, skema Ponzi/piramida, pencatutan nama OJK/Bappebti (*typosquatting*), dan janji profit pasti tanpa risiko.
2. **Keamanan Eksekusi (*Zero Remote Execution*):** Tautan yang dimasukkan pengguna diperlakukan sebagai **data tidak tepercaya (*untrusted input*)**; sistem tidak pernah mengeksekusi JavaScript halaman sasaran atau mengunduh malware ke perangkat pengguna.
3. **Kejujuran Data (*Zero Fabrication*):** Jika sensor Suricata (`eve.json`) atau salah satu API Key AI belum terhubung, sistem wajib melaporkan status jujur (`tidak_tersedia` / `unconfigured`) tanpa memalsukan output deteksi buatan.
4. **Perlindungan Privasi Pengguna (*Zero Sensitive Data*):** Aplikasi tidak meminta data sensitif seperti NIK KTP, PIN, atau password perbankan, serta menyediakan opsi pelaporan anonim (*Hamba Allah*).

### C. Arah Pengembangan Sistem
1. **Tahap Saat Ini (Telah Terimplementasi Penuh):**
   - Integrasi **9 Service Backend Laravel** dengan **Suricata NIDS (`eve.json` & `suricata.rules`)**, **Parallel Dual AI (`Http::pool` Gemini + DeepSeek)**, **Sistem Pencocokan 3 Tingkat (*3-Tier Matching*)**, dan rumus matematis ***Weighted Multi-Sensor Risk Synthesis***.
   - Penyimpanan riwayat audit forensik terpisah per-engine pada tabel **`link_scans`** (Supabase PostgreSQL).
   - Antarmuka publik lengkap (*AI Tabayyun, Etika 4T, Cyber Guard Test, Portal Lapor `LPRxxx`*) serta **Panel Admin** dengan proteksi sesi ketat (**`js/admin-guard.js`** — tanpa riwayat login tersimpan & wajib login ulang saat *reload* atau navigasi *Back/Forward*).
2. **Pengembangan Masa Depan (*Future Roadmap*):**
   - Integrasi *Chatbot* WhatsApp/Telegram Bot resmi sehingga warga cukup meneruskan (*forward*) pesan mencurigakan ke nomor bot untuk langsung mendapatkan hasil audit AI Tabayyun.
   - Sinkronisasi *real-time* daftar domain berbahaya terverifikasi dengan API publik **TrustPositif Komdigi** dan **Satgas PASTI OJK**.
   - Ekstensi peramban (*Browser Extension*) ringan yang memberi peringatan otomatis sebelum pengguna membuka situs yang terindikasi Judol atau Investasi Bodong.

---

## 3. Target Pengguna (Persona)

- **Kelompok Utama:** Pemuda usia **18–25 tahun** (mahasiswa, santri, *fresh graduate*, pengurus komunitas/remaja masjid).
- **Peran Strategis:** **Digital Guardian (Benteng Digital)** dengan dua fungsi:
  1. **Pertahanan Diri (*Self-Protection*):** Melindungi masa depan finansial dan data pribadinya dari jebakan judi online, investasi bodong, dan phishing.
  2. **Pertahanan Keluarga (*Community First-Responder*):** Menjadi rujukan verifikasi pertama di grup WhatsApp keluarga untuk mematahkan rantai penyebaran tautan penipuan secara santun berbasis kaidah **Tabayyun 4T**.

---

## 4. Pemetaan Nilai Aswaja & Fitur Aplikasi (Recognize – Learn – Act)

### A. Korelasi 4 Pilar Wasathiyah Aswaja dengan 4 Modul Utama
| Pilar Wasathiyah Aswaja | Makna Filosofis di Ruang Siber | Modul Fitur Terkait | Implementasi Fungsional |
| :--- | :--- | :--- | :--- |
| **1. Tawasuth** (*Moderat / Objektif*) | Sikap netral, tidak menghakimi secara ekstrem tanpa bukti, dan selalu berpegang pada verifikasi data silang yang objektif. | 🤖 **AI Tabayyun Checker** (`tabayyun.blade.php`) | Mesin deteksi tautan yang mengorelasikan bukti jaringan Suricata NIDS, struktur URL 3-Tier, dan Dual AI (Gemini + DeepSeek) secara transparan. |
| **2. Tawazun** (*Keseimbangan*) | Menyeimbangkan kecanggihan teknologi keamanan siber dengan panduan moral, adab bermedia sosial, dan fiqih informasi. | 📖 **Etika & Fiqih Siber** (`etika.blade.php`) | Modul literasi **Prinsip 4T** dan 6 kajian fiqih digital (privasi data, ghibah digital, mitigasi APK, deepfake, dan muamalah finansial). |
| **3. I'tidal** (*Keadilan & Ketegasan*) | Tegak lurus membela kebenaran serta berani bertindak tegas melaporkan dan memutus rantai kejahatan siber. | 📢 **Portal Lapor Siber** (`lapor.blade.php`) | Kanal aduan publik bertiket otomatis (`LPRxxx`) untuk melaporkan tautan judi online, investasi bodong, dan rekening penipu. |
| **4. Tasamuh** (*Kebijaksanaan & Edukatif*) | Menyikapi ketidaktahuan masyarakat dengan edukasi yang simpatik, interaktif, dan mendidik tanpa merendahkan. | 🎯 **Cyber Guard Test** (`cyber-guard.blade.php`) | Simulasi 5 skenario kasus nyata yang melatih refleks pengambilan keputusan aman disertai pembahasan langsung. |

### B. Siklus Operasional: Recognize – Learn – Act
1. **Recognize (Mengenali) — [`frontend/tabayyun.blade.php`](file:///d:/aswajaCyber/frontend/tabayyun.blade.php):** Memeriksa URL mencurigakan, melihat skor keamanan (5%–98%), bukti sensor Suricata (`SID`), perbandingan hasil Gemini vs DeepSeek, serta dalil fiqih terkait.
2. **Learn (Belajar) — [`frontend/etika.blade.php`](file:///d:/aswajaCyber/frontend/etika.blade.php) & [`frontend/cyber-guard.blade.php`](file:///d:/aswajaCyber/frontend/cyber-guard.blade.php):** Mempelajari **Prinsip 4T** (*Tawaqquf, Tastabbus, Tatsabbut, Ta'aqqul*) dan menguji kesiapan mental melalui kuis simulasi kasus nyata.
3. **Act (Bertindak) — [`frontend/lapor.blade.php`](file:///d:/aswajaCyber/frontend/lapor.blade.php):** Melaporkan tautan berbahaya agar diverifikasi oleh Staf Admin di [`frontend/admin/laporan.blade.php`](file:///d:/aswajaCyber/frontend/admin/laporan.blade.php).

---

## 5. Indikator Lengkap Deteksi Judi Online (Judol) & Investasi Menyesatkan

Bagian ini merupakan rujukan teknis utama bagi dewan juri mengenai **indikator yang digunakan sistem dalam memvonis suatu tautan sebagai Judi Online atau Investasi Menyesatkan**:

### A. Indikator Deteksi Judi Online (*Judol / Maysir Modern*)

#### 1. Indikator Sensor Jaringan Suricata NIDS ([`eve.json`](file:///d:/aswajaCyber/backend/storage/logs/suricata/eve.json) & [`suricata.rules`](file:///d:/aswajaCyber/suricata.rules))
- **DNS Query Signature (`event_type: dns`):** Mendeteksi permintaan resolusi domain yang memuat pola perjudian (`slot-gacor`, `situs-togel`, `casino-online`, `slot88`, `gacor777`) pada `SID: 1000001`.
- **TLS SNI Handshake (`event_type: tls`):** Membaca field `tls.sni` pada jabat tangan HTTPS sebelum enkripsi terjadi (`SID: 1000004`), sehingga domain judi tetap terdeteksi meskipun menggunakan HTTPS.
- **HTTP Host & URI Path (`event_type: http`):** Mendeteksi rute transaksi deposit judol (`/deposit-dana`, `/deposit-qris`, `/depo-pulsa`, `/depo-10k`) dan parameter afiliasi perekrutan pemain (`?ref=`, `?aff=`, `?agent=` pada `SID: 1000007`).
- **Anomali IP Mentah & Fast-Flux TLD (`SID: 1000005`):** Penggunaan alamat IP langsung (`http://185.220.101.5/slot`) untuk memotong blokir DNS pemerintah, serta pemakaian TLD murahan berisiko tinggi (`.top`, `.vip`, `.xyz`, `.cc`, `.icu`, `.fun`, `.click`, `.monster`).

#### 2. Indikator Tiga Tingkat Pencocokan Kata Kunci (*3-Tier Keyword Matching* — Anti False Positive)
Agar sistem **tidak salah mendeteksi website biasa/edukasi sebagai situs Judi Online**, pencocokan kata kunci dibagi menjadi 3 tingkat:

| Tingkat | Contoh Kata Kunci | Perlakuan Sistem | Hasil Jika Berdiri Sendiri | Hasil Jika Disertai Bukti Pendukung |
| :--- | :--- | :--- | :--- | :--- |
| **1. Kuat (`strong`)** | `slot gacor`, `situs togel`, `casino online`, `bandar togel` | **Indikator kuat** (Langsung memicu deteksi bahaya tinggi Priority 1) | 🔴 **`terindikasi_judol`** (`red` / Bahaya) | 🔴 **`terindikasi_judol`** (`red` / Bahaya) |
| **2. Menengah (`medium`)** | `maxwin`, `jackpot`, `scatter`, `free spin`, `rtp live` | **Perlu indikator pendukung** (TLD `.top`/`.xyz`, angka `777`/`88`, path `/deposit`, `?ref=`) | 🟡 **`mencurigakan`** (`amber` / Peringatan) | 🔴 **`terindikasi_judol`** (`red` / Bahaya) |
| **3. Kontekstual (`contextual`)** | `zeus`, `dewa`, `thor`, `dragon`, `olympus` | **Perlu pemeriksaan tambahan** (Mencegah salah deteksi pada situs mitologi/sejarah/umum) | 🟢 **`aman`** (`emerald` / Aman) | 🔴 **`terindikasi_judol`** (`red`) atau 🟡 **`mencurigakan`** (`amber`) |

#### 3. Indikator Kognitif Dual AI (Google Gemini & DeepSeek AI)
- **Google Gemini (`GeminiService.php` — Analisis Semantik & Evasion):**
  - Mendeteksi penyamaran huruf (*leetspeak*: `sl0t`, `g4c0r`, `m4xw1n`, `sc4tter`).
  - Mendeteksi bahasa promosi gamifikasi (*"bocoran pola gacor hari ini"*, *"RTP live 98% anti-rungkad"*, *"bonus new member 100%"*).
- **DeepSeek AI (`DeepSeekService.php` — Audit Forensik & Transaksi):**
  - Mengaudit penggunaan rekening penampung individu, e-wallet pribadi, atau QRIS dinamis yang berganti-ganti.
  - Membongkar klausul jebakan *Turnover (TO)* yang menahan penarikan saldo (*withdraw*) korban.
  - Membongkar klaim lisensi perjudian luar negeri palsu (*PAGCOR / Curacao eGaming*) yang tetap ilegal di wilayah hukum Indonesia.

---

### B. Indikator Deteksi Investasi Menyesatkan (*Investasi Bodong / Skema Ponzi*)

| Dimensi Indikator | Sensor Pendeteksi | Bukti Temuan (*Evidence*) | Penjelasan Bahaya |
| :--- | :--- | :--- | :--- |
| **1. Pencatutan Regulator (*Typosquatting*)** | `UrlValidationService` | `ojk-investasi.xyz`, `bappebti-trading.top`, `bi-dana.vip` | Meniru nama lembaga resmi negara pada domain tidak resmi (bukan `.go.id`) untuk menipu masyarakat awam. |
| **2. Janji Keuntungan Tidak Masuk Akal** | Suricata (`SID: 1000006`) & Dual AI | `robot-trading`, `profit-pasti`, `cuan-harian`, `auto-profit`, *"profit 20% per hari"* | Melanggar prinsip ekonomi wajar; menjanjikan keuntungan pasif tinggi secara instan. |
| **3. Klaim Anti-Rugi (*Zero Risk Fallacy*)** | Google Gemini & DeepSeek AI | *"Investasi aman 100% tanpa risiko"*, *"Pasti untung bebas rugi"* | Menyembunyikan risiko pasar (*gharar*) untuk memancing korban menyetorkan modal besar. |
| **4. Manipulasi Karakter & Angka Acak** | `UrlValidationService` | `investasii2026.com` (huruf `ii` ganda + tahun), `investasi-bonus-827391.com` | Menghindari pemblokiran domain otomatis dan menggunakan domain sekali buang (*disposable domain*). |
| **5. Skema Piramida / Ponzi** | Suricata (`SID: 1000007`) & DeepSeek | Parameter `?ref=`, `?referral=`, komisi perekrutan member berjenjang | Keuntungan anggota lama dibayar dari uang pendaftaran anggota baru (*member-get-member*). |

---

### C. Rumus Penilaian Risiko & Matriks Korelasi Keputusan
Sistem menggabungkan keempat sinyal di atas menggunakan rumus **Sintesis Risiko Terbobot**:
$$\text{RiskScore} = \text{round}\Big((0.35 \times S_{\text{Suricata}}) + (0.30 \times S_{\text{Gemini}}) + (0.30 \times S_{\text{DeepSeek}}) + (0.05 \times S_{\text{URL}})\Big)$$
Dan mengonversinya menjadi **Skor Keamanan Tampilan Publik** (`5%`–`98%`):
$$\text{DisplaySafetyScore} = \max\left(5, \, \min\left(98, \, 100 - \text{RiskScore}\right)\right)$$

---

## 6. Naskah Pitch Singkat / Video Demo

> *"Anak muda Indonesia hari ini menghadapi beban ganda di ruang siber: diri mereka terus digempur promosi Judi Online berkedok game slot dan robot trading bodong, sementara di rumah, orang tua mereka kerap bertanya saat menerima broadcast tautan investasi mencurigakan di grup WhatsApp keluarga.*
>
> *Aswaja CyberShield hadir memberdayakan pemuda sebagai Digital Guardian melalui siklus Recognize, Learn, dan Act:*
> 1. *Pertama (Recognize): Melalui fitur AI Tabayyun, pengguna dapat memeriksa tautan mencurigakan menggunakan kombinasi Sensor Jaringan Suricata NIDS (`eve.json`), Pencocokan Kata Kunci 3 Tingkat (Kuat, Menengah, Kontekstual), serta pemrosesan paralel Dual AI (Google Gemini & DeepSeek) yang menghitung skor risiko secara matematis dan bebas false-positive.*
> 2. *Kedua (Learn): Melalui modul Etika Fiqih Siber 4T dan simulasi interaktif Cyber Guard Test, pengguna tidak hanya tahu mana tautan berbahaya, tetapi terlatih mengambil keputusan aman berlandaskan nilai Ahlussunnah wal Jama'ah.*
> 3. *Ketiga (Act): Melalui Portal Lapor Siber, warga dapat langsung melaporkan tautan judol dan investasi bodong dengan nomor tiket resmi untuk ditindaklanjuti oleh tim admin.*
>
> *Aswaja CyberShield bukan sekadar alat pemindai teknis, melainkan perpaduan pertahanan jaringan modern dan dakwah literasi kultural untuk menjaga harta serta martabat masyarakat di era digital."*

---

## 7. Struktur Slide Presentasi (8 Slide untuk Penjurian)

### Slide 1: Cover / Judul Proyek
- **Judul:** Aswaja CyberShield
- **Sub-judul:** Sistem Deteksi Tautan Judi Online & Investasi Menyesatkan Berbasis Suricata NIDS, Parallel Dual AI (Gemini + DeepSeek), dan Prinsip Tabayyun 4T.

### Slide 2: Latar Belakang & Beban Ganda Pemuda (The Problem)
- Maraknya tautan Judi Online (*fast-flux domain*, *slot gacor*) dan Investasi Bodong (*typosquatting* OJK, *robot trading*).
- Beban ganda pemuda: melindungi masa depan finansial diri sendiri sekaligus menjadi filter informasi bagi keluarga.

### Slide 3: Nilai Pembeda — Integrasi Keamanan Siber Berlapis & Kearifan Aswaja
- Pemetaan **4 Pilar Wasathiyah Aswaja** (*Tawasuth, Tawazun, I'tidal, Tasamuh*) ke 4 modul utama aplikasi.
- Metodologi verifikasi operasional **Prinsip 4T** (*Tawaqquf, Tastabbus, Tatsabbut, Ta'aqqul*).

### Slide 4: Arsitektur Mesin Deteksi: Suricata NIDS + 3-Tier Matching + Parallel Dual AI
- **Layer 1 (Suricata NIDS & `eve.json` — Bobot 35%):** Inspeksi paket DNS Query, TLS SNI Handshake, dan HTTP URI (`SID: 1000000`–`1000008`).
- **Layer 2 (3-Tier Keyword Matching — Bobot 5%):** Tingkat **Kuat** (`slot gacor`), **Menengah** (`maxwin`, `jackpot`), dan **Kontekstual** (`zeus`, `thor`) untuk mencegah *false positive* pada website biasa.
- **Layer 3 (Parallel Dual AI via `Http::pool` — Bobot 30% + 30%):** Google Gemini (Semantik) + DeepSeek AI (Forensik).

### Slide 5: Rumus Matematika & Matriks Korelasi Keputusan
- Menampilkan rumus $\text{RiskScore} = 0.35 S_{\text{Suricata}} + 0.30 S_{\text{Gemini}} + 0.30 S_{\text{DeepSeek}} + 0.05 S_{\text{URL}}$.
- Menampilkan tabel Matriks 6 Skenario Keputusan (termasuk penanganan *Disparitas AI* menjadi `Belum Dapat Disimpulkan`).

### Slide 6: Live Demo Aplikasi (End-to-End Walkthrough)
1. **Uji AI Tabayyun:** Bandingkan URL resmi (`https://ojk.go.id` $\rightarrow$ Hijau/Aman), URL edukasi kontekstual (`https://ensiklopedia-mitologi-zeus-thor.com` $\rightarrow$ Hijau/Bebas False Positive), dan URL Judol (`https://slot-gacor777.top` $\rightarrow$ Merah/Terindikasi Judol).
2. **Uji Cyber Guard & Portal Lapor:** Menjawab kuis simulasi dan mengirim laporan hingga terbit tiket `LPRxxx`.
3. **Uji Panel Admin:** Login admin dan demonstrasi keamanan sesi ketat (`admin-guard.js` — wajib login ulang saat *reload* atau klik panah *Back*).

### Slide 7: Kualitas Kode, Kontrak Database & Kepatuhan Etika
- **Backend Modular:** 9 Service terpisah di Laravel + tabel audit forensik `link_scans` (JSONB terpisah untuk Suricata, Gemini, dan DeepSeek).
- **Kepatuhan Etika:** *Zero Remote Execution*, *Zero Fabrication* (jujur jika sensor belum terhubung), dan *Zero Sensitive Data* (pelaporan anonim).

### Slide 8: Dampak & Kesimpulan (Closing)
- Membentengi pemuda dan keluarga Indonesia dari jeratan Judi Online (*Maysir*) dan Investasi Bodong (*Gharar*).
- *"Literasi digital bukan sekadar kecanggihan algoritma, melainkan adab dan ikhtiar menjaga sesama."*
