# 🌐 Catatan Konsep & Penjelasan Web: Aswaja CyberShield
## Ringkasan Eksekutif, Latar Belakang Masalah, dan Spesifikasi Teknis Proyek

Dokumen ini disusun sebagai panduan menyeluruh mengenai landasan konseptual, perumusan masalah, arsitektur teknis, naskah demo, dan pembagian peran fitur pada platform **Aswaja CyberShield**.

---

## 1. Latar Belakang & Urgensi Masalah

### A. Maraknya Tautan Judi Online (*Judol*) dan Investasi Menyesatkan
Ruang digital Indonesia saat ini berada dalam kondisi darurat penipuan siber dan perjudian daring (*maysir modern*). Berdasarkan data Kementerian Komunikasi dan Digital (Komdigi) serta Otoritas Jasa Keuangan (OJK), ratusan ribu konten dan tautan perjudian daring serta entitas keuangan ilegal diblokir setiap tahunnya.

Sindikat kejahatan siber modern telah berevolusi dengan menerapkan **3 Teknik Penyusupan Tautan**:
1. **Web Defacement & Subdomain Hijacking:** Menunggangi otoritas domain resmi (`.go.id`, `.ac.id`, `.desa.id`) dengan menyusupkan halaman judi pada direktori CMS rentan atau membuat subdomain liar berangka hoki judol (`88`, `138`, `777`).
2. **Open Redirect (Cloaking):** Memanfaatkan parameter pengalihan resmi (`?url=`, `?redirect=`) untuk membelokkan pengunjung ke situs taruhan secara otomatis.
3. **Typosquatting & Homoglif:** Meniru nama instansi resmi negara dan perbankan menggunakan karakter serupa (`0` $\to$ `o`, `1` $\to$ `l/i`, `rn` $\to$ `m`) dan embel-embel tipuan (`-resmi`, `-login`, `-bansos`).

### B. Beban Ganda Generasi Muda di Era Digital
Generasi muda menghadapi dilema ganda:
1. Sebagai target pemasaran agresif sindikat melalui media sosial, influencer, dan grup percakapan.
2. Sebagai rujukan keluarga saat orang tua menerima broadcast tautan mencurigakan di grup WhatsApp.

### C. Lahirnya Aswaja CyberShield: Menjembatani Keamanan Siber & Kearifan Aswaja
**Aswaja CyberShield** hadir menjembatani kesenjangan tersebut dengan memadukan:
1. **Teknologi Deteksi Berlapis (*Multi-Layer Hybrid Detection*):** Menggabungkan **Sensor Jaringan Suricata NIDS (`eve.json`)**, **UrlThreatAnalyzer (Zero-Fetch)**, **Heuristik Struktur URL 3 Tingkat**, dan **Google Gemini AI**.
2. **Media Belajar & Dakwah Kultural Berbasis Ahlussunnah wal Jama'ah (Aswaja):** Mengontekstualisasikan perintah *Tabayyun* (QS. Al-Hujurat: 6), **4 Pilar Wasathiyah Aswaja** (*Tawasuth, Tawazun, I'tidal, Tasamuh*), serta metodologi kritik sanad **Prinsip 4T** (*Tawaqquf, Tastabbus, Tatsabbut, Ta'aqqul*).

---

## 2. Perumusan Masalah, Batasan Masalah, dan Arah Pengembangan

### A. Perumusan Masalah
1. Bagaimana mendeteksi 3 teknik penyusupan tautan judol (*Defacement*, *Open Redirect*, *Typosquatting*) secara aman murni dari teks URL tanpa membuka target (*Zero-Fetch*)?
2. Bagaimana memadukan deteksi deterministik **Suricata NIDS** dengan kecerdasan kognitif **Google Gemini AI** untuk menganalisis manipulasi psikologis penipuan?
3. Bagaimana menyajikan standarisasi 4 tingkat risiko resmi (**Rendah, Sedang, Tinggi, Berat**) dan rangkuman sederhana bebas istilah teknis (*Layman Summary*) agar mudah dipahami masyarakat awam tanpa salah tuduh?
4. Bagaimana mengemas literasi siber ke dalam siklus **Recognize – Learn – Act** yang aplikatif?

### B. Batasan Masalah
1. **Fokus Ancaman:** Judi Online (*Judol / Maysir Modern*) dan Investasi Menyesatkan (*Investasi Bodong / Gharar*).
2. **Keamanan Eksekusi (*Zero-Fetch & Zero Remote Execution*):** Server dan browser tidak pernah membuka, mengeksekusi, atau mengunduh konten dari tautan target.
3. **Kejujuran Data (*Zero Fabrication*):** Jika sensor jaringan atau API AI belum terhubung, sistem melaporkan status jujur tanpa memalsukan data deteksi.

---

## 3. Matriks Nilai Karakter Islam Aswaja dalam Fitur Platform

| Pilar Wasathiyah | Nilai Karakter | Implementasi Fitur Web | Deskripsi Operasional |
| :--- | :--- | :--- | :--- |
| **1. Tawasuth** (*Moderat*) | Sikap objektif, verifikasi data silang tanpa tuduhan membabi buta. | 🤖 **AI Tabayyun Checker** (`/tabayyun`) | Korelasi bukti Suricata NIDS, UrlThreatAnalyzer, dan Google Gemini AI secara transparan. |
| **2. Tawazun** (*Keseimbangan*) | Keseimbangan antara kewaspadaan teknis dan ketenangan batin. | 📖 **Etika Fiqih Siber (4T)** (`/etika`) | Edukasi 4 Kaidah Tabayyun (*Tawaqquf, Tastabbus, Tatsabbut, Ta'aqqul*) dan kajian muamalah digital. |
| **3. I'tidal** (*Keadilan*) | Bertindak adil, proporsional, dan menempatkan sesuatu pada haknya. | 📢 **Portal Lapor Siber** (`/lapor`) | Saluran partisipasi masyarakat untuk melaporkan tautan berbahaya dengan penerbitan tiket aduan `LPRxxx`. |
| **4. Tasamuh** (*Toleransi*) | Edukasi ramah tanpa mempermalukan korban penipuan. | 🛡️ **Cyber Guard Test** (`/cyber-guard`) | Simulasi studi kasus interaktif untuk mengasah refleks dengan pembahasan fiqih yang santun. |

---

## 4. Indikator Deteksi Tautan Bahaya

### A. Tiga Tingkat Pencocokan Kata Kunci (*3-Tier Keyword Matching*)
| Tingkat | Contoh Kata Kunci | Perlakuan Sistem | Hasil Mandiri | Hasil dengan Bukti Pendukung |
| :--- | :--- | :--- | :--- | :--- |
| **1. Kuat (`strong`)** | `slot gacor`, `situs togel`, `casino online`, `bandar togel` | **Indikator kuat** (Priority 1) | 🔴 **`terindikasi_judol`** | 🔴 **`terindikasi_judol`** |
| **2. Menengah (`medium`)** | `maxwin`, `jackpot`, `scatter`, `free spin`, `rtp live` | **Perlu indikator pendukung** | 🟡 **`mencurigakan`** | 🔴 **`terindikasi_judol`** |
| **3. Kontekstual (`contextual`)** | `zeus`, `dewa`, `thor`, `dragon`, `olympus` | **Pemeriksaan kontekstual** | 🟢 **`aman`** | 🔴 **`terindikasi_judol`** / 🟡 **`mencurigakan`** |

### B. Indikator Kognitif Google Gemini AI (`GeminiService.php`)
- Mendeteksi penyamaran huruf (*leetspeak*: `sl0t`, `g4c0r`, `m4xw1n`).
- Mendeteksi bahasa manipulasi gamifikasi (*"bocoran pola gacor"*, *"RTP live 98% anti-rungkad"*, *"bonus new member"*).
- Menilai klaim palsu *"resmi OJK/Bappebti"* dan skema Ponzi.

---

## 5. Rumus Sintesis Risiko & Sistem Skor 2-State

$$\text{RiskScore} = \text{round}\Big((0.35 \times S_{\text{Threat}}) + (0.30 \times S_{\text{Suricata}}) + (0.30 \times S_{\text{Gemini}}) + (0.05 \times S_{\text{URL}})\Big)$$

Sistem menghasilkan **panduan biner 2-state**:
- **Aman:** Menampilkan **"Tingkat Keamanan"** ($0\% - 100\%$, hijau emerald).
- **Berbahaya:** Menampilkan **"Tingkat Bahaya"** ($0\% - 100\%$, merah danger). Label penegas *"Sangat Berbahaya"* disematkan hanya bila skor bahaya $\ge 85\%$.

---

## 6. Naskah Video Demo / Pitch Singkat

> *"Anak muda Indonesia hari ini menghadapi beban ganda di ruang siber: diri mereka digempur promosi Judi Online berkedok game slot dan robot trading bodong, sementara orang tua mereka kerap bertanya saat menerima broadcast tautan mencurigakan di grup WhatsApp keluarga.*
>
> *Aswaja CyberShield hadir memberdayakan pemuda sebagai Digital Guardian melalui siklus Recognize, Learn, dan Act:*
> 1. *Recognize: Fitur AI Tabayyun memeriksa tautan berbasis Zero-Fetch, memadukan UrlThreatAnalyzer (deteksi defacement, open redirect, typosquatting), Sensor Suricata NIDS, dan Google Gemini AI dengan sistem skor tegas: Aman atau Berbahaya.*
> 2. *Learn: Modul Etika 4T dan Cyber Guard melatih refleks ketahanan digital berlandaskan nilai moderasi Ahlussunnah wal Jama'ah.*
> 3. *Act: Portal Lapor Siber memfasilitasi aduan masyarakat dengan nomor tiket resmi untuk ditindaklanjuti tim analis.*
>
> *Aswaja CyberShield memadukan pertahanan jaringan modern dan dakwah literasi kultural untuk menjaga harta serta martabat masyarakat di era digital."*

---

## 7. Struktur Slide Presentasi (8 Slide Penjurian)

- **Slide 1:** Cover & Identitas — Aswaja CyberShield (Suricata NIDS, UrlThreatAnalyzer, Google Gemini AI, & Kaidah Tabayyun 4T).
- **Slide 2:** Latar Belakang & 3 Modus Penyusupan Judol Modern.
- **Slide 3:** Nilai Pembeda — 4 Pilar Wasathiyah & Kaidah 4T.
- **Slide 4:** Arsitektur Mesin Deteksi Zero-Fetch (UrlThreatAnalyzer + Suricata NIDS + Gemini AI).
- **Slide 5:** Rumus Matematika Terbobot & Sistem Skor 2-State (Aman vs Berbahaya).
- **Slide 6:** Live Demo Aplikasi (AI Tabayyun, Cyber Guard, Portal Lapor, & Panel Admin Terpadu).
- **Slide 7:** Kualitas Kode, Kontrak Database PostgreSQL Supabase, & Keamanan Sesi Admin.
- **Slide 8:** Dampak Nyata & Penutup: *"Literasi digital adalah adab dan ikhtiar menjaga sesama."*

---

## 8. Ekosistem Panel Admin & Akuntabilitas Penanganan Laporan

1. **Dashboard KPI & Tinjauan Interaktif (`/admin`):**
   - Menghubungkan metrik analitik (Total Artikel, Laporan Masuk, Akun Pengelola, Skenario Studi Kasus) dengan routing absolut instan.
   - Tabel aduan terkini yang dapat diklik langsung per baris untuk pratinjau cepat modal investigasi tanpa berpindah halaman.
2. **Triase & Penugasan Laporan Aduan Warga (`/admin/laporan`):**
   - Fitur penetapan staf analis melalui dropdown dinamis terhubung database `public.users` Supabase.
   - Verifikasi status aduan (*Pending $\to$ Diproses $\to$ Selesai / Ditolak*) disertai catatan rekomendasi penindakan ke pihak berwenang (Komdigi/AduanKonten).
3. **Manajemen Konten Edukasi & Kaidah 4T (`/admin/artikel`):**
   - Kategorisasi 3 rumpun ilmiah (Umum, IT, dan Agama).
   - Tombol ganda *Edit Kaidah* dan *Edit Artikel* langsung dari kartu pilar T1–T4.
4. **Keamanan Sesi & Hak Akses Bertingkat (`/admin/akun`):**
   - Pemisahan peran Super Admin & Admin Basic.
   - Proteksi sesi otomatis: *Inactivity Timeout* 5 menit dan *Account Lockout Cooldown* berjenjang anti-brute force.

---

## 9. Kesiapan Infrastruktur Cloud Vercel (Opsi 1 Full-Stack)

1. **Monorepo Serverless Modern:** Menggabungkan antarmuka Blade PHP dan backend Laravel ke dalam satu repositori terpadu via runtime `vercel-php@0.7.3`.
2. **Serverless Bridge (`api/index.php`):** Mengatasi sistem berkas read-only serverless dengan mengalihkan cache dan storage Laravel ke `/tmp/storage`.
3. **Koneksi Pooler Berkecepatan Tinggi:** Terhubung ke basis data Supabase PostgreSQL Singapore via pooler IPv4 (`aws-0-ap-southeast-1.pooler.supabase.com:5432`) untuk latensi minimal.
4. **Global Edge CDN:** Aset antarmuka (`css/`, `js/`, gambar) disajikan langsung dari jaringan tepi Vercel secara instan dan bebas latensi.


