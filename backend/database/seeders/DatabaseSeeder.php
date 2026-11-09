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

        // 3. Seed Articles
        $articles = [
            [
                'title' => 'Anatomi Jebakan Robot Trading Bodong Berkedok Putaran Slot',
                'slug' => 'anatomi-jebakan-robot-trading-bodong',
                'category' => 'Investasi Bodong',
                'excerpt' => 'Membongkar alur manipulasi psikologis penipuan berkedok trading algoritma yang mengalihkan dana korban ke situs deposit kasino ilegal.',
                'content' => 'Praktik penipuan finansial digital kian canggih dengan mengadopsi narasi trading forex dan cryptocurrency berbasis AI. Korban awalnya diiming-imingi profit stabil 1-2% per hari tanpa risiko. Namun saat dana sudah disetorkan, akun dialihkan ke platform judi berkedok game finansial.',
                'author' => 'Dr. H. Muhammad Zaki, M.Kom',
                'read_time' => '6 Menit Baca',
                'status' => 'Published',
                'cover_image' => '../images/artikel-1.jpg',
            ],
            [
                'title' => 'Kajian Fiqih Muamalah: Batasan Gharar dan Maysir pada Game Berhadiah Finansial',
                'slug' => 'kajian-fiqih-muamalah-gharar-maysir',
                'category' => 'Fiqih Siber',
                'excerpt' => 'Telaah fatwa DSN-MUI dan kaidah turats Ahlussunnah terkait aplikasi penghasil uang yang menyaratkan pembelian kupon tebak angka.',
                'content' => 'Islam memandang harta sebagai amanah yang harus diperoleh dengan cara yang thoyyib dan terbebas dari unsur spekulasi murni (maysir) serta ketidakjelasan transaksi (gharar). Setiap skema yang mempertaruhkan modal dengan hasil yang murni bergantung pada peluang undian masuk dalam kategori judi.',
                'author' => 'K.H. Ahmad Shiddiq Al-Banjari',
                'read_time' => '8 Menit Baca',
                'status' => 'Published',
                'cover_image' => '../images/artikel-2.jpg',
            ],
            [
                'title' => 'Mitigasi Phishing File APK Surat Undangan dan Kurir Paket',
                'slug' => 'mitigasi-phishing-file-apk-kurir',
                'category' => 'Keamanan Digital',
                'excerpt' => 'Panduan teknis langkah pertolongan pertama saat tidak sengaja menginstal file APK modifikasi yang menyadap SMS OTP m-banking.',
                'content' => 'Penyebaran tautan APK berbahaya melalui aplikasi pesan instan telah menjadi pintu masuk utama pembobolan rekening perbankan. Korban diminta memasang berkas berbahaya yang menyadap izin SMS untuk membaca One-Time Password (OTP).',
                'author' => 'Tim Tanggap Insiden CSIRT Aswaja',
                'read_time' => '5 Menit Baca',
                'status' => 'Published',
                'cover_image' => '../images/artikel-3.jpg',
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

        // 5. Seed Prinsip 4T
        $prinsip4T = [
            [
                'code' => 'T1',
                'name' => 'Tawaqquf',
                'arabic' => 'تَوَقُّف',
                'tagline' => 'Tahan Diri & Jeda Reflektif',
                'description' => 'Menahan jempol saat menerima tautan sensasional berbau cuan instan. Menghentikan penyebaran sebelum fakta teruji.',
                'link' => 'artikel/prinsip-tawaqquf.blade.php',
                'icon' => 'pause_circle',
            ],
            [
                'code' => 'T2',
                'name' => 'Tastabbus',
                'arabic' => 'تَثَبُّت',
                'tagline' => 'Telusuri Sanad & Fakta Digital',
                'description' => 'Melacak jejak digital domain, memverifikasi perizinan regulator OJK/Bappebti, dan membongkar kedok samaran tautan.',
                'link' => 'artikel/prinsip-tastabbus.blade.php',
                'icon' => 'search_check',
            ],
            [
                'code' => 'T3',
                'name' => 'Tatsabbut',
                'arabic' => 'تَبَيُّن',
                'tagline' => 'Konfirmasi Otoritas & Fatwa',
                'description' => 'Menguji kesesuaian muamalah dengan fatwa alim ulama dan hukum fiqih untuk memastikan tiadanya unsur judi (maysir).',
                'link' => 'artikel/prinsip-tatsabbut.blade.php',
                'icon' => 'verified_user',
            ],
            [
                'code' => 'T4',
                'name' => 'Ta\'aqqul',
                'arabic' => 'تَعَقُّل',
                'tagline' => 'Pertimbangan Maslahat & Mudharat',
                'description' => 'Menimbang apakah konten valid tersebut jika disebarkan membawa kemaslahatan atau malah memicu kegaduhan.',
                'link' => 'artikel/prinsip-taaqqul.blade.php',
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
