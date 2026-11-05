<?php

namespace App\Repositories;

class MockDataRepository
{
    protected string $storagePath;

    public function __construct()
    {
        $this->storagePath = storage_path('app/mock_database.json');
        $this->ensureInitialized();
    }

    protected function ensureInitialized(): void
    {
        if (!file_exists($this->storagePath)) {
            $initialData = [
                'users' => [
                    [
                        'id' => 1,
                        'name' => 'Admin Utama',
                        'username' => 'admin',
                        'email' => 'admin@aswajacyber.id',
                        'password_hash' => password_hash('Aswaja2026!', PASSWORD_BCRYPT),
                        'role' => 'Super Admin',
                        'status' => 'Aktif',
                        'lastLogin' => '02 Nov 2026, 19:30 WIB'
                    ],
                    [
                        'id' => 2,
                        'name' => 'Ahmad Fauzi',
                        'username' => 'analis',
                        'email' => 'ahmad.fauzi@aswajacyber.id',
                        'password_hash' => password_hash('Aswaja2026!', PASSWORD_BCRYPT),
                        'role' => 'Admin Basic',
                        'status' => 'Aktif',
                        'lastLogin' => '02 Nov 2026, 17:10 WIB'
                    ],
                    [
                        'id' => 3,
                        'name' => 'Siti Nurhaliza',
                        'username' => 'siti',
                        'email' => 'siti.analis@aswajacyber.id',
                        'password_hash' => password_hash('Aswaja2026!', PASSWORD_BCRYPT),
                        'role' => 'Admin Basic',
                        'status' => 'Aktif',
                        'lastLogin' => '01 Nov 2026, 14:45 WIB'
                    ]
                ],
                'tokens' => [
                    // token => userId
                ],
                'reports' => [
                    [
                        'id' => 'LPR081',
                        'url' => 'https://cuan-trading-harian-vip88.vip/deposit-slot',
                        'date' => '02 Nov 2026, 14:20 WIB',
                        'reporter' => 'Hamba Allah',
                        'category' => 'Investasi Berkedok Trading / Crypto',
                        'description' => 'Grup Telegram mengklaim sinyal trading robot otomatis dengan profit 30% per hari, tetapi tautan pendaftarannya dialihkan ke deposit situs slot online luar negeri.',
                        'status' => 'Diproses',
                        'verificationNotes' => 'Domain terdaftar 4 hari lalu di luar yurisdiksi RI. Server merujuk pada jaringan situs judi slot tanpa izin OJK/Bappebti.',
                        'handledBy' => 'Ahmad Fauzi (Analisis Siber)'
                    ],
                    [
                        'id' => 'LPR080',
                        'url' => 'https://dana-investasi-amanah-syariah.top/login-member',
                        'date' => '02 Nov 2026, 11:15 WIB',
                        'reporter' => 'Rizki Ramadhan',
                        'category' => 'Iklan Cuan Kilat Bersyarat Deposit Judi',
                        'description' => 'Mencatut label "Investasi Syariah Bebas Riba" di Instagram, tetapi saat registrasi member diwajibkan membeli kupon spin roda keberuntungan yang merupakan bentuk judi berkedok undian.',
                        'status' => 'Selesai',
                        'verificationNotes' => 'Terindikasi maysir manipulatif. Tautan telah diteruskan ke rekomendasi pemblokiran domain Komdigi/BSSN.',
                        'handledBy' => 'Siti Nurhaliza (Tim Analis)'
                    ],
                    [
                        'id' => 'LPR079',
                        'url' => 'https://mining-usdt-passiveincome.cc/wallet-spin',
                        'date' => '01 Nov 2026, 19:40 WIB',
                        'reporter' => 'Anonim',
                        'category' => 'Tautan Promosi Slot / Kasino Berkedok Game Finansial',
                        'description' => 'Menjanjikan passive income dari mining kripto. Setelah transfer deposit pertama, dana dikunci dan baru bisa ditarik jika menyelesaikan taruhan ganjil-genap tebak angka.',
                        'status' => 'Selesai',
                        'verificationNotes' => 'Skema penahanan dana (withdrawal lock) dengan pemaksaan taruhan judi online.',
                        'handledBy' => 'Ahmad Fauzi (Analisis Siber)'
                    ],
                    [
                        'id' => 'LPR078',
                        'url' => 'https://arisan-digital-berkah-nusantara.site/gabung',
                        'date' => '01 Nov 2026, 09:10 WIB',
                        'reporter' => 'Warga Samarinda',
                        'category' => 'Tautan Undangan Grup Investasi / Arisan Bodong',
                        'description' => 'Arisan berantai via WhatsApp. Uang kloter pertama dibagikan, selanjutnya peserta disuruh memasang nomor tebak skor bola pada link tersembunyi.',
                        'status' => 'Masuk',
                        'verificationNotes' => 'Dalam antrean verifikasi bukti tangkapan layar chat WhatsApp.',
                        'handledBy' => 'Belum Ditugaskan'
                    ],
                    [
                        'id' => 'LPR077',
                        'url' => 'https://portal-bantuan-modal-umkm2026.online/klaim-bonus',
                        'date' => '31 Okt 2026, 16:30 WIB',
                        'reporter' => 'Dewi Lestari',
                        'category' => 'Iklan Cuan Kilat Bersyarat Deposit Judi',
                        'description' => 'Mengaku program subsidi usaha mikro dengan modal awal cuma-cuma, namun syarat aktivasi harus melakukan deposit taruhan di situs kasino daring.',
                        'status' => 'Selesai',
                        'verificationNotes' => 'Diverifikasi sebagai tautan jebakan deposit judol. Modus rekayasa sosial menyasar pelaku usaha kecil.',
                        'handledBy' => 'Ahmad Fauzi (Analisis Siber)'
                    ]
                ],
                'articles' => [
                    [
                        'id' => 1,
                        'title' => 'Anatomi Jebakan Robot Trading Bodong Berkedok Putaran Slot',
                        'slug' => 'anatomi-jebakan-robot-trading-bodong',
                        'category' => 'Investasi Bodong',
                        'excerpt' => 'Membongkar alur manipulasi psikologis penipuan berkedok trading algoritma yang mengalihkan dana korban ke situs deposit kasino ilegal.',
                        'content' => 'Praktik penipuan finansial digital kian canggih dengan mengadopsi narasi trading forex dan cryptocurrency berbasis AI...',
                        'author' => 'Dr. H. Muhammad Zaki, M.Kom',
                        'date' => '02 Nov 2026',
                        'readTime' => '6 Menit Baca',
                        'status' => 'Published',
                        'coverImage' => '../images/artikel-1.jpg'
                    ],
                    [
                        'id' => 2,
                        'title' => 'Kajian Fiqih Muamalah: Batasan Gharar dan Maysir pada Game Berhadiah Finansial',
                        'slug' => 'kajian-fiqih-muamalah-gharar-maysir',
                        'category' => 'Fiqih Siber',
                        'excerpt' => 'Telaah fatwa DSN-MUI dan kaidah turats Ahlussunnah terkait aplikasi penghasil uang yang menyaratkan pembelian kupon tebak angka.',
                        'content' => 'Islam memandang harta sebagai amanah yang harus diperoleh dengan cara yang thoyyib dan terbebas dari unsur spekulasi murni...',
                        'author' => 'K.H. Ahmad Shiddiq Al-Banjari',
                        'date' => '28 Okt 2026',
                        'readTime' => '8 Menit Baca',
                        'status' => 'Published',
                        'coverImage' => '../images/artikel-2.jpg'
                    ],
                    [
                        'id' => 3,
                        'title' => 'Mitigasi Phishing File APK Surat Undangan dan Kurir Paket',
                        'slug' => 'mitigasi-phishing-file-apk-kurir',
                        'category' => 'Keamanan Digital',
                        'excerpt' => 'Panduan teknis langkah pertolongan pertama saat tidak sengaja menginstal file APK modifikasi yang menyadap SMS OTP m-banking.',
                        'content' => 'Penyebaran tautan APK berbahaya melalui aplikasi pesan instan telah menjadi pintu masuk utama pembobolan rekening...',
                        'author' => 'Tim Tanggap Insiden CSIRT Aswaja',
                        'date' => '25 Okt 2026',
                        'readTime' => '5 Menit Baca',
                        'status' => 'Published',
                        'coverImage' => '../images/artikel-3.jpg'
                    ]
                ],
                'caseStudies' => [
                    [
                        'id' => 1,
                        'title' => 'Grup VIP Sinyal Kripto Berhadiah Saldo',
                        'scenario' => 'Anda diundang ke sebuah grup Telegram bernama "Trading Barakah VIP". Admin menawarkan robot sinyal dengan winrate 99%. Namun, untuk aktivasi robot, Anda diminta deposit $20 di link web yang memiliki menu "Live Spin Wheel".',
                        'sender' => 'Telegram: @admin_cuan_barakah',
                        'message' => 'Akhi, slot kuota robot trading tinggal 3 user! Klik link berikut, transfer deposit awal 300rb, lalu putar spin hoki pertama Anda untuk klaim saldo bonus $50!',
                        'riskLevel' => 'Kritis',
                        'fiqihPrinciple' => 'Tatsabbut & Ta\'aqqul',
                        'question' => 'Langkah mitigasi paling tepat sesuai prinsip Fiqih Siber Tabayyun adalah?',
                        'options' => [
                            ['key' => 'A', 'text' => 'Langsung transfer 300rb karena ada embel-embel berkah dan syariah.', 'correct' => false],
                            ['key' => 'B', 'text' => 'Terapkan Tawaqquf: Menolak deposit, cek legalitas di OJK/Bappebti, dan laporkan link ke portal aduan.', 'correct' => true],
                            ['key' => 'C', 'text' => 'Mencoba deposit kecil-kecilan dulu untuk membuktikan kebenaran spin wheel.', 'correct' => false],
                            ['key' => 'D', 'text' => 'Meneruskan pesan ajakan tersebut ke keluarga agar sama-sama dapat bonus.', 'correct' => false]
                        ],
                        'discussion' => 'Modus ini menggabungkan penipuan investasi palsu dengan perjudian online (maysir). Adanya syarat spin wheel mengindikasikan spekulasi untung-untungan terlarang tanpa underlying aset riil.'
                    ]
                ],
                'prinsip4T' => [
                    [
                        'id' => 1,
                        'code' => 'T1',
                        'title' => 'Tawaqquf',
                        'name' => 'Tawaqquf',
                        'arabic' => 'تَوَقُّف',
                        'subtitle' => 'Tahan Diri & Jeda Reflektif',
                        'tagline' => 'Tahan Diri & Jeda Reflektif',
                        'description' => 'Menahan jempol saat menerima tautan sensasional berbau cuan instan. Menghentikan penyebaran sebelum fakta teruji.',
                        'link' => 'artikel/prinsip-tawaqquf.blade.php',
                        'icon' => 'pause_circle'
                    ],
                    [
                        'id' => 2,
                        'code' => 'T2',
                        'title' => 'Tastabbus',
                        'name' => 'Tastabbus',
                        'arabic' => 'تَثَبُّت',
                        'subtitle' => 'Telusuri Sanad & Fakta Digital',
                        'tagline' => 'Telusuri Sanad & Fakta Digital',
                        'description' => 'Melacak jejak digital domain, memverifikasi perizinan regulator OJK/Bappebti, dan membongkar kedok samaran tautan.',
                        'link' => 'artikel/prinsip-tastabbus.blade.php',
                        'icon' => 'search_check'
                    ],
                    [
                        'id' => 3,
                        'code' => 'T3',
                        'title' => 'Tatsabbut',
                        'name' => 'Tatsabbut',
                        'arabic' => 'تَبَيُّن',
                        'subtitle' => 'Konfirmasi Otoritas & Fatwa',
                        'tagline' => 'Konfirmasi Otoritas & Fatwa',
                        'description' => 'Menguji kesesuaian muamalah dengan fatwa alim ulama dan hukum fiqih untuk memastikan tiadanya unsur judi (maysir).',
                        'link' => 'artikel/prinsip-tatsabbut.blade.php',
                        'icon' => 'verified_user'
                    ],
                    [
                        'id' => 4,
                        'code' => 'T4',
                        'title' => 'Ta\'aqqul',
                        'name' => 'Ta\'aqqul',
                        'arabic' => 'تَعَقُّل',
                        'subtitle' => 'Pertimbangan Maslahat & Mudharat',
                        'tagline' => 'Pertimbangan Maslahat & Mudharat',
                        'description' => 'Menimbang apakah konten valid tersebut jika disebarkan membawa kemaslahatan atau malah memicu kegaduhan.',
                        'link' => 'artikel/prinsip-taaqqul.blade.php',
                        'icon' => 'psychology'
                    ]
                ],
                'activities' => [
                    [
                        'user' => 'Ahmad Fauzi',
                        'action' => 'Memperbarui status laporan #LPR081 menjadi "Diproses"',
                        'time' => '10 menit lalu',
                        'icon' => 'arrow-repeat'
                    ],
                    [
                        'user' => 'Siti Nurhaliza',
                        'action' => 'Menyelesaikan verifikasi bukti laporan #LPR080 (Judol berkedok Syariah)',
                        'time' => '1 jam lalu',
                        'icon' => 'check-circle'
                    ]
                ]
            ];

            file_put_contents($this->storagePath, json_encode($initialData, JSON_PRETTY_PRINT));
        }
    }

    protected function getData(): array
    {
        $this->ensureInitialized();
        $content = file_get_contents($this->storagePath);
        return json_decode($content, true) ?: [];
    }

    protected function saveData(array $data): void
    {
        file_put_contents($this->storagePath, json_encode($data, JSON_PRETTY_PRINT));
    }

    // --- USERS & AUTH ---
    public function findUserByUsernameOrEmail(string $identifier): ?array
    {
        $data = $this->getData();
        foreach ($data['users'] as $user) {
            if ($user['username'] === $identifier || $user['email'] === $identifier) {
                return $user;
            }
        }
        return null;
    }

    public function findUserById(int $id): ?array
    {
        $data = $this->getData();
        foreach ($data['users'] as $user) {
            if ($user['id'] === $id) {
                return $user;
            }
        }
        return null;
    }

    public function storeToken(string $token, int $userId): void
    {
        $data = $this->getData();
        $data['tokens'][$token] = $userId;
        $this->saveData($data);
    }

    public function getUserByToken(string $token): ?array
    {
        $data = $this->getData();
        if (isset($data['tokens'][$token])) {
            return $this->findUserById($data['tokens'][$token]);
        }
        return null;
    }

    public function revokeToken(string $token): void
    {
        $data = $this->getData();
        unset($data['tokens'][$token]);
        $this->saveData($data);
    }

    public function getAdmins(): array
    {
        $data = $this->getData();
        return array_map(function ($u) {
            unset($u['password_hash']);
            return $u;
        }, $data['users']);
    }

    public function createAdmin(array $input): array
    {
        $data = $this->getData();
        $newId = count($data['users']) > 0 ? max(array_column($data['users'], 'id')) + 1 : 1;
        $newUser = [
            'id' => $newId,
            'name' => $input['name'] ?? 'Admin Staf',
            'username' => $input['username'] ?? 'staf' . $newId,
            'email' => $input['email'] ?? 'staf' . $newId . '@aswajacyber.id',
            'password_hash' => password_hash($input['password'] ?? 'Aswaja2026!', PASSWORD_BCRYPT),
            'role' => $input['role'] ?? 'Admin Basic',
            'status' => $input['status'] ?? 'Aktif',
            'lastLogin' => 'Belum pernah login'
        ];
        $data['users'][] = $newUser;
        $this->saveData($data);
        unset($newUser['password_hash']);
        return $newUser;
    }

    public function updateAdmin(int $id, array $input): ?array
    {
        $data = $this->getData();
        foreach ($data['users'] as &$user) {
            if ($user['id'] === $id) {
                $user['name'] = $input['name'] ?? $user['name'];
                $user['username'] = $input['username'] ?? $user['username'];
                $user['email'] = $input['email'] ?? $user['email'];
                $user['role'] = $input['role'] ?? $user['role'];
                $user['status'] = $input['status'] ?? $user['status'];
                if (!empty($input['password'])) {
                    $user['password_hash'] = password_hash($input['password'], PASSWORD_BCRYPT);
                }
                $this->saveData($data);
                $clean = $user;
                unset($clean['password_hash']);
                return $clean;
            }
        }
        return null;
    }

    public function deleteAdmin(int $id): bool
    {
        $data = $this->getData();
        $initialCount = count($data['users']);
        $data['users'] = array_values(array_filter($data['users'], fn($u) => $u['id'] !== $id));
        if (count($data['users']) < $initialCount) {
            $this->saveData($data);
            return true;
        }
        return false;
    }

    // --- REPORTS ---
    public function getReports(): array
    {
        return $this->getData()['reports'] ?? [];
    }

    public function createReport(array $report): array
    {
        $data = $this->getData();
        $count = count($data['reports']);
        $newId = 'LPR' . str_pad((string)($count + 82), 3, '0', STR_PAD_LEFT);
        
        $newReport = [
            'id' => $newId,
            'url' => $report['url'] ?? '',
            'date' => now()->translatedFormat('d M Y, H:i') . ' WIB',
            'reporter' => $report['reporter'] ?? 'Hamba Allah',
            'category' => $report['category'] ?? 'Investasi Berkedok Trading / Crypto',
            'description' => $report['description'] ?? '',
            'status' => 'Masuk',
            'verificationNotes' => $report['verificationNotes'] ?? 'Laporan baru diterima dari portal publik. Menunggu verifikasi awal.',
            'handledBy' => 'Belum Ditugaskan',
            'evidencePath' => $report['evidencePath'] ?? null,
            'aiAnalysis' => $report['aiAnalysis'] ?? null
        ];

        array_unshift($data['reports'], $newReport);
        
        // Add activity
        array_unshift($data['activities'], [
            'user' => $newReport['reporter'],
            'action' => "Mengirim laporan aduan siber #{$newId}",
            'time' => 'Baru saja',
            'icon' => 'inbox-fill'
        ]);

        $this->saveData($data);
        return $newReport;
    }

    public function updateReport(string $id, array $input): ?array
    {
        $data = $this->getData();
        foreach ($data['reports'] as &$report) {
            if ($report['id'] === $id) {
                $report['status'] = $input['status'] ?? $report['status'];
                $report['verificationNotes'] = $input['verificationNotes'] ?? $input['verification_notes'] ?? $report['verificationNotes'];
                $report['handledBy'] = $input['handledBy'] ?? $input['assigned_admin'] ?? $report['handledBy'];
                
                // Add activity
                array_unshift($data['activities'], [
                    'user' => $report['handledBy'],
                    'action' => "Memperbarui status laporan #{$id} menjadi \"{$report['status']}\"",
                    'time' => 'Baru saja',
                    'icon' => 'arrow-repeat'
                ]);

                $this->saveData($data);
                return $report;
            }
        }
        return null;
    }

    public function deleteReport(string $id): bool
    {
        $data = $this->getData();
        $initialCount = count($data['reports']);
        $data['reports'] = array_values(array_filter($data['reports'], fn($r) => $r['id'] !== $id));
        if (count($data['reports']) < $initialCount) {
            $this->saveData($data);
            return true;
        }
        return false;
    }

    // --- ARTICLES ---
    public function getArticles(): array
    {
        return $this->getData()['articles'] ?? [];
    }

    public function createArticle(array $input): array
    {
        $data = $this->getData();
        $newId = count($data['articles']) > 0 ? max(array_column($data['articles'], 'id')) + 1 : 1;
        $newArticle = [
            'id' => $newId,
            'title' => $input['title'] ?? 'Artikel Baru',
            'slug' => \Illuminate\Support\Str::slug($input['title'] ?? 'artikel-' . $newId),
            'category' => $input['category'] ?? 'Fiqih Siber',
            'excerpt' => $input['excerpt'] ?? substr(strip_tags($input['content'] ?? ''), 0, 150),
            'content' => $input['content'] ?? '',
            'author' => $input['author'] ?? 'Tim Edukasi Aswaja',
            'date' => now()->translatedFormat('d M Y'),
            'readTime' => $input['readTime'] ?? '5 Menit Baca',
            'status' => $input['status'] ?? 'Published',
            'coverImage' => $input['coverImage'] ?? '../images/default-article.jpg'
        ];
        array_unshift($data['articles'], $newArticle);
        $this->saveData($data);
        return $newArticle;
    }

    public function updateArticle(int $id, array $input): ?array
    {
        $data = $this->getData();
        foreach ($data['articles'] as &$article) {
            if ($article['id'] === $id) {
                $article['title'] = $input['title'] ?? $article['title'];
                $article['slug'] = isset($input['title']) ? \Illuminate\Support\Str::slug($input['title']) : $article['slug'];
                $article['category'] = $input['category'] ?? $article['category'];
                $article['excerpt'] = $input['excerpt'] ?? $article['excerpt'];
                $article['content'] = $input['content'] ?? $article['content'];
                $article['author'] = $input['author'] ?? $article['author'];
                $article['readTime'] = $input['readTime'] ?? $article['readTime'];
                $article['status'] = $input['status'] ?? $article['status'];
                if (!empty($input['coverImage'])) {
                    $article['coverImage'] = $input['coverImage'];
                }
                $this->saveData($data);
                return $article;
            }
        }
        return null;
    }

    public function deleteArticle(int $id): bool
    {
        $data = $this->getData();
        $initialCount = count($data['articles']);
        $data['articles'] = array_values(array_filter($data['articles'], fn($a) => $a['id'] !== $id));
        if (count($data['articles']) < $initialCount) {
            $this->saveData($data);
            return true;
        }
        return false;
    }

    // --- CASE STUDIES ---
    public function getCaseStudies(): array
    {
        return $this->getData()['caseStudies'] ?? [];
    }

    public function createCaseStudy(array $input): array
    {
        $data = $this->getData();
        $newId = count($data['caseStudies']) > 0 ? max(array_column($data['caseStudies'], 'id')) + 1 : 1;
        $newCase = [
            'id' => $newId,
            'title' => $input['title'] ?? 'Kasus Baru',
            'scenario' => $input['scenario'] ?? '',
            'sender' => $input['sender'] ?? 'Pengirim Tidak Dikenal',
            'message' => $input['message'] ?? '',
            'riskLevel' => $input['riskLevel'] ?? 'Sedang',
            'fiqihPrinciple' => $input['fiqihPrinciple'] ?? 'Tatsabbut',
            'question' => $input['question'] ?? 'Langkah apa yang harus diambil?',
            'options' => $input['options'] ?? [],
            'discussion' => $input['discussion'] ?? ''
        ];
        array_unshift($data['caseStudies'], $newCase);
        $this->saveData($data);
        return $newCase;
    }

    public function updateCaseStudy(int $id, array $input): ?array
    {
        $data = $this->getData();
        foreach ($data['caseStudies'] as &$case) {
            if ($case['id'] === $id) {
                $case['title'] = $input['title'] ?? $case['title'];
                $case['scenario'] = $input['scenario'] ?? $case['scenario'];
                $case['sender'] = $input['sender'] ?? $case['sender'];
                $case['message'] = $input['message'] ?? $case['message'];
                $case['riskLevel'] = $input['riskLevel'] ?? $case['riskLevel'];
                $case['fiqihPrinciple'] = $input['fiqihPrinciple'] ?? $case['fiqihPrinciple'];
                $case['question'] = $input['question'] ?? $case['question'];
                $case['options'] = $input['options'] ?? $case['options'];
                $case['discussion'] = $input['discussion'] ?? $case['discussion'];
                $this->saveData($data);
                return $case;
            }
        }
        return null;
    }

    public function deleteCaseStudy(int $id): bool
    {
        $data = $this->getData();
        $initialCount = count($data['caseStudies']);
        $data['caseStudies'] = array_values(array_filter($data['caseStudies'], fn($c) => $c['id'] !== $id));
        if (count($data['caseStudies']) < $initialCount) {
            $this->saveData($data);
            return true;
        }
        return false;
    }

    // --- PRINSIP 4T ---
    public function getPrinsip4T(): array
    {
        $items = $this->getData()['prinsip4T'] ?? [];
        return array_map(function ($item) {
            $title = $item['title'] ?? $item['name'] ?? 'Kaidah 4T';
            $subtitle = $item['subtitle'] ?? $item['tagline'] ?? $item['arabic'] ?? '';
            $item['title'] = $title;
            $item['name'] = $title;
            $item['subtitle'] = $subtitle;
            $item['tagline'] = $subtitle;
            return $item;
        }, $items);
    }

    public function updatePrinsip4T(int $id, array $input): ?array
    {
        $data = $this->getData();
        foreach ($data['prinsip4T'] as &$item) {
            if ($item['id'] === $id) {
                $title = $input['title'] ?? $input['name'] ?? $item['title'] ?? $item['name'] ?? 'Kaidah 4T';
                $subtitle = $input['subtitle'] ?? $input['tagline'] ?? $item['subtitle'] ?? $item['tagline'] ?? '';
                $item['title'] = $title;
                $item['name'] = $title;
                $item['subtitle'] = $subtitle;
                $item['tagline'] = $subtitle;
                $item['description'] = $input['description'] ?? $item['description'];
                $item['link'] = $input['link'] ?? $item['link'];
                $this->saveData($data);
                return $item;
            }
        }
        return null;
    }

    // --- ACTIVITIES & DASHBOARD STATS ---
    public function getActivities(): array
    {
        return array_slice($this->getData()['activities'] ?? [], 0, 10);
    }
}

