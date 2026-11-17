<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Article;
use App\Models\CaseStudy;
use App\Models\LinkScan;
use App\Models\Prinsip4T;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Administrator Users
        $users = [
            [
                'name' => 'Admin Utama',
                'username' => 'admin',
                'email' => 'aswajashield@gmail.com',
                'password' => Hash::make('Aswaja2026!'),
                'role' => 'Super Admin',
                'status' => 'Aktif',
                'last_login_at' => now(),
            ],
            [
                'name' => 'Muhammad Ali Reza',
                'username' => 'ali',
                'email' => 'alikarim2110@gmail.com',
                'password' => Hash::make('Aswaja2026!'),
                'role' => 'Admin Basic',
                'status' => 'Aktif',
                'last_login_at' => now(),
            ],
            [
                'name' => 'Deby Cantika Putri',
                'username' => 'deby',
                'email' => 'debycantika@gmail.com',
                'password' => Hash::make('Aswaja2026!'),
                'role' => 'Admin Basic',
                'status' => 'Aktif',
                'last_login_at' => now(),
            ],
            [
                'name' => 'Muhammad Fauzan Dahlan',
                'username' => 'fauzan',
                'email' => 'fauzandahlan72@gmail.com',
                'password' => Hash::make('Aswaja2026!'),
                'role' => 'Admin Basic',
                'status' => 'Aktif',
                'last_login_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['username' => $user['username']], $user);
        }

        // 2. Seed Reports (Laporan Aduan)
        $reports = [
            [
                'id' => 'LPR081',
                'url' => 'https://cuan-trading-harian-vip88.vip/deposit-slot',
                'reporter' => 'Anonim',
                'category' => 'Investasi Bodong & Trading Ilegal',
                'description' => 'Grup Telegram mengklaim sinyal trading robot otomatis dengan profit 30% per hari, tetapi tautan pendaftarannya dialihkan ke deposit situs slot online luar negeri.',
                'status' => 'Diproses',
                'verification_notes' => 'Domain terdaftar 4 hari lalu di luar yurisdiksi RI. Server merujuk pada jaringan situs judi slot tanpa izin OJK/Bappebti.',
                'handled_by' => 'Ahmad Fauzi (Analisis Siber)',
            ],
            [
                'id' => 'LPR080',
                'url' => 'https://dana-investasi-amanah-syariah.top/login-member',
                'reporter' => 'Rizki Ramadhan',
                'category' => 'Iklan Cuan Kilat Bersyarat Deposit Judi',
                'description' => 'Mencatut label "Investasi Syariah Bebas Riba" di Instagram, tetapi saat registrasi member diwajibkan membeli kupon spin roda keberuntungan yang merupakan bentuk judi berkedok undian.',
                'status' => 'Selesai',
                'verification_notes' => 'Terindikasi maysir manipulatif. Tautan telah diteruskan ke rekomendasi pemblokiran domain Komdigi/BSSN.',
                'handled_by' => 'Siti Nurhaliza (Tim Analis)',
            ],
            [
                'id' => 'LPR079',
                'url' => 'https://mining-usdt-passiveincome.cc/wallet-spin',
                'reporter' => 'Anonim',
                'category' => 'Tautan Promosi Slot / Kasino Berkedok Game Finansial',
                'description' => 'Menjanjikan passive income dari mining kripto. Setelah transfer deposit pertama, dana dikunci dan baru bisa ditarik jika menyelesaikan taruhan ganjil-genap tebak angka.',
                'status' => 'Selesai',
                'verification_notes' => 'Skema penahanan dana (withdrawal lock) dengan pemaksaan taruhan judi online.',
                'handled_by' => 'Ahmad Fauzi (Analisis Siber)',
            ],
            [
                'id' => 'LPR078',
                'url' => 'https://arisan-digital-berkah-nusantara.site/gabung',
                'reporter' => 'Warga Samarinda',
                'category' => 'Tautan Undangan Grup Investasi / Arisan Bodong',
                'description' => 'Arisan berantai via WhatsApp. Uang kloter pertama dibagikan, selanjutnya peserta disuruh memasang nomor tebak skor bola pada link tersembunyi.',
                'status' => 'Masuk',
                'verification_notes' => 'Dalam antrean verifikasi bukti tangkapan layar chat WhatsApp.',
                'handled_by' => 'Belum Ditugaskan',
            ],
            [
                'id' => 'LPR077',
                'url' => 'https://portal-bantuan-modal-umkm2026.online/klaim-bonus',
                'reporter' => 'Dewi Lestari',
                'category' => 'Iklan Cuan Kilat Bersyarat Deposit Judi',
                'description' => 'Mengaku program subsidi usaha mikro dengan modal awal cuma-cuma, namun syarat aktivasi harus melakukan deposit taruhan di situs kasino daring.',
                'status' => 'Selesai',
                'verification_notes' => 'Diverifikasi sebagai tautan jebakan deposit judol. Modus rekayasa sosial menyasar pelaku usaha kecil.',
                'handled_by' => 'Ahmad Fauzi (Analisis Siber)',
            ],
        ];

        foreach ($reports as $report) {
            Report::updateOrCreate(['id' => $report['id']], $report);
        }

        // 3. Seed Articles (14 Modul Artikel Lengkap Sesuai Tampilan Admin & Pengguna)
        $articles = [
            [
                'title' => 'INVESTASI SAHAM SYARIAH VS SPEKULASI SAHAM RISIKO TINGGI',
                'slug' => 'investasi-saham-syariah-vs-spekulasi-saham-risiko-tinggi',
                'category' => 'Dampak Sosial & Jeratan Finansial',
                'excerpt' => 'Kepemilikan Perusahaan Riil vs Manipulasi Pasar',
                'content' => '<p>Investasi saham syariah berfokus pada kepemilikan riil atas instrumen saham emiten yang memenuhi kriteria halal, bebas dari utang ribawi berlebih, dan tidak bergerak di sektor non-halal. Sebaliknya, spekulasi saham berisiko tinggi (pom-pom saham gorengan) sarat dengan manipulasi pasar dan perjudian terselubung yang mengarah pada kerugian fatal bagi investor pemula.</p>',
                'author' => 'Deby Cantika Putri',
                'status' => 'Published',
                'cover_image' => null,
                'created_at' => '2026-11-09 14:30:00',
                'updated_at' => '2026-11-09 14:30:00',
            ],
            [
                'title' => 'ANALISIS FIQIH PERDAGANGAN FOREX (VALAS)',
                'slug' => 'analisis-fiqih-perdagangan-forex-valas',
                'category' => 'Dampak Sosial & Jeratan Finansial',
                'excerpt' => 'Uji Keabsahan Akad Spot vs Spekulasi Margin Trading',
                'content' => '<p>Perdagangan valuta asing (al-sharf) diperbolehkan dalam Islam selama memenuhi prinsip serah terima tunai (spot) tanpa penundaan. Namun, kebanyakan platform forex ritel daring menerapkan leverage tinggi, swap/bunga menginap, serta transaksi derivatif tanpa kepemilikan fisik yang menjerumuskannya ke dalam praktik gharar dan maysir.</p>',
                'author' => 'Deby Cantika Putri',
                'status' => 'Published',
                'cover_image' => null,
                'created_at' => '2026-11-09 14:20:00',
                'updated_at' => '2026-11-09 14:20:00',
            ],
            [
                'title' => 'JERATAN PENIPUAN SKEMA TITIP MODAL',
                'slug' => 'jeratan-penipuan-skema-titip-modal',
                'category' => 'Dampak Sosial & Jeratan Finansial',
                'excerpt' => 'Menolak Pengelolaan Harta Tanpa Transparansi Akad',
                'content' => '<p>Modus \'titip modal\' atau \'titip dana trading\' menjamur di grup media sosial dengan dalih dikelola oleh master trader profesional. Korban dijanjikan imbal hasil harian fantastis tanpa risiko. Dalam fiqih muamalah, akad mudharabah atau musyarakah mewajibkan pembagian risiko dan transparansi pembukuan riil, bukan jaminan untung sepihak.</p>',
                'author' => 'Deby Cantika Putri',
                'status' => 'Published',
                'cover_image' => null,
                'created_at' => '2026-11-09 14:10:00',
                'updated_at' => '2026-11-09 14:10:00',
            ],
            [
                'title' => 'INVESTASI BODONG VS INVESTASI REAL',
                'slug' => 'investasi-bodong-vs-investasi-real',
                'category' => 'Dampak Sosial & Jeratan Finansial',
                'excerpt' => 'Membedakan Bisnis Riil dan Skema Menyesatkan',
                'content' => '<p>Membedakan investasi legal dan ilegal memerlukan ketelitian dalam memeriksa aspek 2L: Legal dan Logis. Investasi bodong umumnya tidak memiliki izin edar/usaha dari OJK atau Bappebti, menggunakan skema piramida (Ponzi), dan tidak memiliki arus kas atau underlying aset riil.</p>',
                'author' => 'Deby Cantika Putri',
                'status' => 'Published',
                'cover_image' => null,
                'created_at' => '2026-11-09 14:00:00',
                'updated_at' => '2026-11-09 14:00:00',
            ],
            [
                'title' => 'Modul Edukasi dan Pengamanan Aset Digital',
                'slug' => 'modul-edukasi-dan-pengamanan-aset-digital',
                'category' => 'Literasi & Modus Judi Online',
                'excerpt' => 'Fiqih Perlindungan Diri dari Jeratan Skema Ponzi dan Deposito Bodong',
                'content' => '<p>Pengamanan aset digital dimulai dari kesadaran literasi keuangan syariah dan kebersihan perangkat elektronik. Membentengi diri dari godaan bunga pinjol dan janji deposit berlipat ganda adalah wujud nyata penerapan prinsip hifzhul mal (menjaga harta) dalam maqashid syariah.</p>',
                'author' => 'Deby Cantika Putri',
                'status' => 'Published',
                'cover_image' => null,
                'created_at' => '2026-11-09 13:50:00',
                'updated_at' => '2026-11-09 13:50:00',
            ],
            [
                'title' => 'Etika Siber dan Ketahanan Keuangan',
                'slug' => 'etika-siber-dan-ketahanan-keuangan',
                'category' => 'Literasi & Modus Judi Online',
                'excerpt' => 'Bahaya Harta Haram Judi Online Terhadap Keberkahan Finansial Keluarga',
                'content' => '<p>Penghasilan yang bersumber dari aktivitas perjudian daring (maysir) membawa kehancuran psikologis, sosial, dan spiritual. Dampak sistemik judol merusak ketahanan ekonomi rumah tangga, mendorong tindak kriminal, dan menghilangkan keberkahan dalam nafkah keluarga.</p>',
                'author' => 'Deby Cantika Putri',
                'status' => 'Published',
                'cover_image' => null,
                'created_at' => '2026-11-09 13:40:00',
                'updated_at' => '2026-11-09 13:40:00',
            ],
            [
                'title' => 'Analisis Fiqih Muamalah Digital',
                'slug' => 'analisis-fiqih-muamalah-digital',
                'category' => 'Literasi & Modus Judi Online',
                'excerpt' => 'Tatsabbut: Menguji Kebenaran Akad dari Unsur Maysir dan Gharar',
                'content' => '<p>Penerapan kaidah Tatsabbut mewajibkan konfirmasi otoritas fatwa dan regulasi resmi negara sebelum menyetujui transaksi digital. Menguji akad secara menyeluruh memastikan tidak adanya klausul tersembunyi yang merugikan salah satu pihak (gharar fahisy).</p>',
                'author' => 'Deby Cantika Putri',
                'status' => 'Published',
                'cover_image' => null,
                'created_at' => '2026-11-09 13:30:00',
                'updated_at' => '2026-11-09 13:30:00',
            ],
            [
                'title' => 'Forensik Digital dan Investigasi Judi Online',
                'slug' => 'forensik-digital-dan-investigasi-judi-online',
                'category' => 'Literasi & Modus Judi Online',
                'excerpt' => 'Tastabbus: Teliti Kebenaran Substansi, Legalitas, dan Konteks Informasi',
                'content' => '<p>Kaidah Tastabbus menuntut penelusuran sanad digital: memeriksa sertifikat SSL, usia domain, server hosting, hingga gateway pembayaran yang digunakan oleh situs-situs mencurigakan guna memastikan integritas dan legalitas platform.</p>',
                'author' => 'Deby Cantika Putri',
                'status' => 'Published',
                'cover_image' => null,
                'created_at' => '2026-11-09 13:20:00',
                'updated_at' => '2026-11-09 13:20:00',
            ],
            [
                'title' => 'Metodologi Tabayyun dan Kaidah',
                'slug' => 'metodologi-tabayyun-dan-kaidah',
                'category' => 'Literasi & Modus Judi Online',
                'excerpt' => 'Prinsip Tawaqquf: Tahan Diri Sebelum Menyebarkan Informasi dan Mengambil Tindakan',
                'content' => '<p>Mengambil jeda kritis (Tawaqquf) merupakan benteng awal dalam menghadapi banjir disinformasi dan tautan phishing di media sosial. Menahan jempol dari klik tergesa-gesa menyelamatkan data pribadi dan akses keuangan Anda.</p>',
                'author' => 'Deby Cantika Putri',
                'status' => 'Published',
                'cover_image' => null,
                'created_at' => '2026-11-09 13:10:00',
                'updated_at' => '2026-11-09 13:10:00',
            ],
            [
                'title' => 'Ta\'aqqul: Timbang Maslahat dan Dampak Sebelum Menyebarkan',
                'slug' => 'ta-aqqul-timbang-maslahat-dan-dampak-sebelum-menyebarkan',
                'category' => 'Literasi & Modus Judi Online',
                'excerpt' => 'Menimbang apakah konten valid tersebut jika disebarkan membawa kemaslahatan atau malah memicu kegaduhan',
                'content' => '<p>Tidak semua informasi yang benar patut disebarluaskan tanpa pertimbangan dampak. Kaidah Ta\'aqqul mengajarkan kita untuk selalu menimbang maslahat dan mafsadah (mudharat) sebelum menekan tombol share ke ruang publik digital.</p>',
                'author' => 'Deby Cantika Putri',
                'status' => 'Published',
                'cover_image' => null,
                'created_at' => '2026-11-09 13:00:00',
                'updated_at' => '2026-11-09 13:00:00',
            ],
            [
                'title' => 'Membongkar Modus Web Defacement Subdomain Pemerintah untuk Promosi Judi',
                'slug' => 'modus-web-defacement-subdomain-pemerintah',
                'category' => 'Manipulasi Algoritma & Script Judol',
                'excerpt' => 'Mengapa situs berekstensi .go.id dan .ac.id sering disusupi halaman judi aktif dan bagaimana masyarakat dapat menganalisisnya.',
                'content' => '<p>Sindikat judi online menargetkan situs web institusi pemerintah (.go.id) dan perguruan tinggi (.ac.id) yang memiliki celah kerentanan CMS untuk menyuntikkan backlink dan halaman pendaratan judol demi mendongkrak peringkat SEO Google secara instan.</p>',
                'author' => 'Tim Tanggap Insiden CSIRT Aswaja',
                'status' => 'Published',
                'cover_image' => null,
                'created_at' => '2026-11-09 12:00:00',
                'updated_at' => '2026-11-09 12:00:00',
            ],
            [
                'title' => 'Anatomi Jebakan Robot Trading Bodong Berkedok Putaran Slot',
                'slug' => 'anatomi-jebakan-robot-trading-bodong',
                'category' => 'Manipulasi Algoritma & Script Judol',
                'excerpt' => 'Mengapa janji profit konsisten harian tanpa risiko adalah indikator mutlak skema Ponzi dan perjudian terselubung.',
                'content' => '<p>Praktik penipuan berkedok investasi kian bermutasi menggunakan istilah teknologi mutakhir seperti AI Auto-Trading dan Smart Arbitrage. Padahal, di balik antarmuka grafik palsu yang ditampilkan, dana masyarakat sebenarnya diarahkan ke mesin perjudian daring (slot) yang telah diatur algoritmanya agar bandar selalu menang.</p>',
                'author' => 'Muhammad Ali Reza',
                'status' => 'Published',
                'cover_image' => '../images/artikel-1.jpg',
                'created_at' => '2026-11-07 10:00:00',
                'updated_at' => '2026-11-07 10:00:00',
            ],
            [
                'title' => 'Mitigasi Phishing File APK Surat Undangan dan Kurir Paket',
                'slug' => 'mitigasi-phishing-file-apk-kurir',
                'category' => 'Infiltrasi Web & Malware APK',
                'excerpt' => 'Panduan teknis langkah pertolongan pertama saat tidak sengaja menginstal file APK modifikasi yang menyadap SMS OTP m-banking.',
                'content' => '<p>Penyebaran tautan APK berbahaya melalui aplikasi pesan instan telah menjadi pintu masuk utama pembobolan rekening perbankan. Korban diminta memasang berkas berbahaya yang menyadap izin SMS untuk membaca One-Time Password (OTP).</p>',
                'author' => 'Muhammad Ali Reza',
                'status' => 'Published',
                'cover_image' => '../images/artikel-3.jpg',
                'created_at' => '2026-11-07 09:00:00',
                'updated_at' => '2026-11-07 09:00:00',
            ],
            [
                'title' => 'Kajian Fiqih Muamalah: Batasan Gharar dan Maysir pada Game Berhadiah Finansial',
                'slug' => 'kajian-fiqih-muamalah-gharar-maysir',
                'category' => 'Fiqih Muamalah & Larangan Maysir',
                'excerpt' => 'Telaah fatwa DSN-MUI dan kaidah turats Ahlussunnah terkait aplikasi penghasil uang yang menyaratkan pembelian kupon tebak angka.',
                'content' => '<p>Islam memandang harta sebagai amanah yang harus diperoleh dengan cara yang thoyyib dan terbebas dari unsur spekulasi murni (maysir) serta ketidakjelasan transaksi (gharar). Setiap permainan digital yang mensyaratkan pembelian koin dengan uang asli untuk memutar roda keberuntungan yang hasilnya dapat diuangkan kembali merupakan bentuk nyata dari Maysir (perjudian).</p>',
                'author' => 'Muhammad Fauzan Dahlan',
                'status' => 'Published',
                'cover_image' => '../images/artikel-2.jpg',
                'created_at' => '2026-11-07 08:00:00',
                'updated_at' => '2026-11-07 08:00:00',
            ],
        ];

        foreach ($articles as $article) {
            Article::updateOrCreate(['slug' => $article['slug']], $article);
        }

        // 4. Seed Case Studies (Cyber Guard)
        $caseStudies = [
            [
                'title' => 'Grup VIP Sinyal Kripto Berhadiah Saldo',
                'scenario' => 'Anda diundang ke sebuah grup Telegram bernama "Trading Barakah VIP". Admin menawarkan robot sinyal dengan winrate 99%. Namun, untuk aktivasi robot, Anda diminta deposit $20 di link web yang memiliki menu "Live Spin Wheel".',
                'sender' => 'Telegram: @admin_cuan_barakah',
                'message' => 'Akhi, slot kuota robot trading tinggal 3 user! Klik link berikut, transfer deposit awal 300rb, lalu putar spin hoki pertama Anda untuk klaim saldo bonus $50!',
                'risk_level' => 'Kritis',
                'fiqih_principle' => 'Tatsabbut & Ta\'aqqul',
                'question' => 'Langkah mitigasi paling tepat sesuai prinsip Fiqih Siber Tabayyun adalah?',
                'options' => [
                    ['key' => 'A', 'text' => 'Langsung transfer 300rb karena ada embel-embel berkah dan syariah.', 'correct' => false],
                    ['key' => 'B', 'text' => 'Terapkan Tawaqquf: Menolak deposit, cek legalitas di OJK/Bappebti, dan laporkan link ke portal aduan.', 'correct' => true],
                    ['key' => 'C', 'text' => 'Mencoba deposit kecil-kecilan dulu untuk membuktikan kebenaran spin wheel.', 'correct' => false],
                    ['key' => 'D', 'text' => 'Meneruskan pesan ajakan tersebut ke keluarga agar sama-sama dapat bonus.', 'correct' => false],
                ],
                'discussion' => 'Modus ini menggabungkan penipuan investasi palsu dengan perjudian online (maysir). Adanya syarat spin wheel mengindikasikan spekulasi untung-untungan terlarang tanpa underlying aset riil.',
            ],
        ];

        foreach ($caseStudies as $cs) {
            CaseStudy::updateOrCreate(['title' => $cs['title']], $cs);
        }

        // 5. Seed Prinsip 4T (Materi Utama Teks Edukasi Murni)
        $prinsip4T = [
            [
                'code' => 'T1',
                'name' => 'Tawaqquf',
                'arabic' => 'تَوَقُّف',
                'tagline' => 'Tahan Diri & Jeda Reflektif',
                'description' => 'Menahan jempol saat menerima tautan sensasional berbau cuan instan. Menghentikan penyebaran sebelum fakta teruji.',
                'link' => null,
                'icon' => 'pause_circle',
            ],
            [
                'code' => 'T2',
                'name' => 'Tastabbus',
                'arabic' => 'تَثَبُّت',
                'tagline' => 'Telusuri Sanad & Fakta Digital',
                'description' => 'Melacak jejak digital domain, memverifikasi perizinan regulator OJK/Bappebti, dan membongkar kedok samaran tautan.',
                'link' => null,
                'icon' => 'search',
            ],
            [
                'code' => 'T3',
                'name' => 'Tatsabbut',
                'arabic' => 'تَبَيُّن',
                'tagline' => 'Konfirmasi Otoritas & Fatwa',
                'description' => 'Menguji kesesuaian muamalah dengan fatwa alim ulama dan hukum fiqih untuk memastikan tiadanya unsur judi (maysir).',
                'link' => null,
                'icon' => 'verified_user',
            ],
            [
                'code' => 'T4',
                'name' => 'Ta\'aqqul',
                'arabic' => 'تَعَقُّل',
                'tagline' => 'Pertimbangan Maslahat & Mudharat',
                'description' => 'Menimbang apakah konten valid tersebut jika disebarkan membawa kemaslahatan atau malah memicu kegaduhan.',
                'link' => null,
                'icon' => 'psychology',
            ],
        ];

        foreach ($prinsip4T as $p) {
            Prinsip4T::updateOrCreate(['code' => $p['code']], $p);
        }

        // 6. Seed Activities
        $activities = [
            [
                'user_name' => 'Ahmad Fauzi',
                'action' => 'Memperbarui status laporan #LPR081 menjadi "Diproses"',
                'icon' => 'arrow-repeat',
            ],
            [
                'user_name' => 'Siti Nurhaliza',
                'action' => 'Menyelesaikan verifikasi bukti laporan #LPR080 (Judol berkedok Syariah)',
                'icon' => 'check-circle',
            ],
        ];

        foreach ($activities as $act) {
            Activity::create($act);
        }

        // 7. Seed Riwayat Audit Tautan (Link Scans - 38 items baseline)
        $mockJson = @file_get_contents(storage_path('app/mock_database.json'));
        if ($mockJson) {
            $mockData = json_decode($mockJson, true);
            foreach ($mockData['link_scans'] ?? [] as $scan) {
                LinkScan::updateOrCreate(['id' => $scan['id']], $scan);
            }
        }
    }
}
