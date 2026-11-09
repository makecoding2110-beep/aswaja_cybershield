<?php

namespace App\Repositories;

use App\Models\Activity;
use App\Models\Article;
use App\Models\CaseStudy;
use App\Models\Prinsip4T;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
                        'email' => 'aswajashield@gmail.com',
                        'password_hash' => password_hash('Aswaja2026!', PASSWORD_BCRYPT),
                        'role' => 'Super Admin',
                        'status' => 'Aktif',
                        'lastLogin' => '02 Nov 2026, 19:30 WITA'
                    ],
                    [
                        'id' => 2,
                        'name' => 'Muhammad Ali Reza',
                        'username' => 'ali',
                        'email' => 'alikarim2110.aswajacyber@gmail.com',
                        'password_hash' => password_hash('Aswaja2026!', PASSWORD_BCRYPT),
                        'role' => 'Admin Basic',
                        'status' => 'Aktif',
                        'lastLogin' => '02 Nov 2026, 17:10 WITA'
                    ],
                    [
                        'id' => 3,
                        'name' => 'Deby Cantika Putri',
                        'username' => 'deby',
                        'email' => 'debycantika.aswajashield@gmail.com',
                        'password_hash' => password_hash('Aswaja2026!', PASSWORD_BCRYPT),
                        'role' => 'Admin Basic',
                        'status' => 'Aktif',
                        'lastLogin' => '01 Nov 2026, 14:45 WITA'
                    ]
                ],
                'tokens' => [],
                'reports' => [],
                'articles' => [],
                'caseStudies' => [],
                'prinsip4T' => [],
                'activities' => []
            ];

            file_put_contents($this->storagePath, json_encode($initialData, JSON_PRETTY_PRINT));
        }
    }

    protected function getData(): array
    {
        $this->ensureInitialized();
        $content = @file_get_contents($this->storagePath);
        return json_decode($content, true) ?: [];
    }

    protected function saveData(array $data): void
    {
        @file_put_contents($this->storagePath, json_encode($data, JSON_PRETTY_PRINT));
    }

    // =========================================================================
    // 1. PENGELOLAAN PENGGUNA & AUTENTIKASI (USERS & AUTH)
    // =========================================================================

    public function findUserByUsernameOrEmail(string $identifier): ?array
    {
        $identifier = trim($identifier);
        $cleanId = strtolower($identifier);

        try {
            $user = User::whereRaw('LOWER(username) = ?', [$cleanId])
                ->orWhereRaw('LOWER(email) = ?', [$cleanId])
                ->first();

            if ($user) {
                return [
                    'id' => (int) $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status ?? 'Aktif',
                    'password_hash' => $user->password,
                    'lastLogin' => $user->last_login_at ? $user->last_login_at->translatedFormat('d M Y, H:i') . ' WITA' : 'Baru saja'
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('[DB User] Fallback to JSON: ' . $e->getMessage());
        }

        $data = $this->getData();
        foreach ($data['users'] ?? [] as $user) {
            $userUname = strtolower(trim($user['username'] ?? ''));
            $userMail = strtolower(trim($user['email'] ?? ''));
            if ($userUname === $cleanId || $userMail === $cleanId) {
                return $user;
            }
        }
        return null;
    }

    public function findUserById(int $id): ?array
    {
        try {
            $user = User::find($id);
            if ($user) {
                return [
                    'id' => (int) $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status ?? 'Aktif',
                    'password_hash' => $user->password,
                    'lastLogin' => $user->last_login_at ? $user->last_login_at->translatedFormat('d M Y, H:i') . ' WITA' : 'Baru saja'
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('[DB User] Fallback to JSON: ' . $e->getMessage());
        }

        $data = $this->getData();
        foreach ($data['users'] ?? [] as $user) {
            if ((int) ($user['id'] ?? 0) === $id) {
                return $user;
            }
        }
        return null;
    }

    public function createSignedToken(int $userId): string
    {
        $issuedAt = time();
        $secret = (string) (config('app.key') ?: env('APP_KEY', 'aswaja_cybershield_default_secret'));
        $sig = hash_hmac('sha256', "{$userId}|{$issuedAt}", $secret);
        $token = "aswaja_{$userId}_{$issuedAt}_{$sig}";
        $this->storeToken($token, $userId);
        return $token;
    }

    public function storeToken(string $token, int $userId): void
    {
        $data = $this->getData();
        $data['tokens'][$token] = [
            'user_id' => $userId,
            'last_activity' => time()
        ];
        $this->saveData($data);
    }

    public function getUserByToken(string $token): ?array
    {
        $data = $this->getData();
        if (!empty($data['revoked_tokens'][$token])) {
            return null;
        }

        if (isset($data['tokens'][$token])) {
            $entry = $data['tokens'][$token];
            $userId = is_array($entry) ? (int) ($entry['user_id'] ?? 0) : (int) $entry;
            $lastActivity = is_array($entry) ? (int) ($entry['last_activity'] ?? 0) : 0;

            // Inactivity timeout: 5 menit (300 detik)
            if ($lastActivity > 0 && (time() - $lastActivity > 300)) {
                unset($data['tokens'][$token]);
                $this->saveData($data);
                return null;
            }

            // Perbarui waktu aktivitas terakhir
            if (is_array($entry)) {
                $data['tokens'][$token]['last_activity'] = time();
                $this->saveData($data);
            }

            return $this->findUserById($userId);
        }

        // Verifikasi Stateless Signed Token (kompatibel lintas container Vercel)
        if (preg_match('/^aswaja_(\d+)_(\d+)_([a-f0-9]{64})$/', $token, $matches)) {
            $userId = (int) $matches[1];
            $issuedAt = (int) $matches[2];
            $sig = $matches[3];
            $secret = (string) (config('app.key') ?: env('APP_KEY', 'aswaja_cybershield_default_secret'));
            $expectedSig = hash_hmac('sha256', "{$userId}|{$issuedAt}", $secret);

            if (hash_equals($expectedSig, $sig) && (time() - $issuedAt <= 86400)) {
                return $this->findUserById($userId);
            }
        }

        return null;
    }

    public function revokeToken(string $token): void
    {
        $data = $this->getData();
        unset($data['tokens'][$token]);
        $data['revoked_tokens'][$token] = time();
        $this->saveData($data);
    }

    public function getLoginThrottle(string $key): ?array
    {
        $data = $this->getData();
        return $data['login_throttle'][$key] ?? null;
    }

    public function setLoginThrottle(string $key, array $throttle): void
    {
        $data = $this->getData();
        if (!isset($data['login_throttle'])) {
            $data['login_throttle'] = [];
        }
        $data['login_throttle'][$key] = $throttle;
        $this->saveData($data);
    }

    public function clearLoginThrottle(string $key): void
    {
        $data = $this->getData();
        if (isset($data['login_throttle'][$key])) {
            unset($data['login_throttle'][$key]);
            $this->saveData($data);
        }
    }

    public function getAdmins(): array
    {
        try {
            $users = User::orderBy('id', 'asc')->get();
            if ($users->isNotEmpty()) {
                return $users->map(function ($u) {
                    return [
                        'id' => (int) $u->id,
                        'name' => $u->name,
                        'username' => $u->username,
                        'email' => $u->email,
                        'role' => $u->role,
                        'status' => $u->status ?? 'Aktif',
                        'lastLogin' => $u->last_login_at ? $u->last_login_at->translatedFormat('d M Y, H:i') . ' WITA' : 'Aktif'
                    ];
                })->toArray();
            }
        } catch (\Throwable $e) {
            Log::warning('[DB Admins] Fallback to JSON: ' . $e->getMessage());
        }

        $data = $this->getData();
        return array_map(function ($u) {
            unset($u['password_hash']);
            return $u;
        }, $data['users'] ?? []);
    }

    public function createAdmin(array $input): array
    {
        $newUser = [
            'name' => $input['name'] ?? 'Admin Staf',
            'username' => $input['username'] ?? 'staf_' . time(),
            'email' => $input['email'] ?? '',
            'role' => $input['role'] ?? 'Admin Basic',
            'status' => $input['status'] ?? 'Aktif',
            'lastLogin' => 'Baru saja'
        ];

        try {
            $created = User::create([
                'name' => $newUser['name'],
                'username' => $newUser['username'],
                'email' => $newUser['email'],
                'password' => Hash::make($input['password'] ?? 'Aswaja2026!'),
                'role' => $newUser['role'],
                'status' => $newUser['status'],
                'last_login_at' => now(),
            ]);
            $newUser['id'] = (int) $created->id;
            return $newUser;
        } catch (\Throwable $e) {
            Log::warning('[DB CreateAdmin] Fallback to JSON: ' . $e->getMessage());
        }

        $data = $this->getData();
        $newId = count($data['users'] ?? []) > 0 ? max(array_column($data['users'], 'id')) + 1 : 1;
        $newUser['id'] = $newId;
        $newUser['password_hash'] = password_hash($input['password'] ?? 'Aswaja2026!', PASSWORD_BCRYPT);
        $data['users'][] = $newUser;
        $this->saveData($data);
        unset($newUser['password_hash']);
        return $newUser;
    }

    public function updateAdmin(int $id, array $input): ?array
    {
        try {
            $user = User::find($id);
            if ($user) {
                if (isset($input['name'])) $user->name = $input['name'];
                if (isset($input['username'])) $user->username = $input['username'];
                if (isset($input['email'])) $user->email = $input['email'];
                if (isset($input['role'])) $user->role = $input['role'];
                if (isset($input['status'])) $user->status = $input['status'];
                if (!empty($input['password'])) {
                    $user->password = Hash::make($input['password']);
                }
                $user->save();

                return [
                    'id' => (int) $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status ?? 'Aktif',
                    'lastLogin' => $user->last_login_at ? $user->last_login_at->translatedFormat('d M Y, H:i') . ' WITA' : 'Baru saja'
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('[DB UpdateAdmin] Fallback to JSON: ' . $e->getMessage());
        }

        $data = $this->getData();
        foreach ($data['users'] ?? [] as &$u) {
            if ((int) ($u['id'] ?? 0) === $id) {
                if (isset($input['name'])) $u['name'] = $input['name'];
                if (isset($input['username'])) $u['username'] = $input['username'];
                if (isset($input['email'])) $u['email'] = $input['email'];
                if (isset($input['role'])) $u['role'] = $input['role'];
                if (isset($input['status'])) $u['status'] = $input['status'];
                if (!empty($input['password'])) {
                    $u['password_hash'] = password_hash($input['password'], PASSWORD_BCRYPT);
                }
                $this->saveData($data);
                $clean = $u;
                unset($clean['password_hash']);
                return $clean;
            }
        }
        return null;
    }

    public function deleteAdmin(int $id): bool
    {
        try {
            $user = User::find($id);
            if ($user) {
                $user->delete();
                return true;
            }
        } catch (\Throwable $e) {
            Log::warning('[DB DeleteAdmin] Fallback to JSON: ' . $e->getMessage());
        }

        $data = $this->getData();
        $initialCount = count($data['users'] ?? []);
        $data['users'] = array_values(array_filter($data['users'] ?? [], fn($u) => (int) ($u['id'] ?? 0) !== $id));
        if (count($data['users']) < $initialCount) {
            $this->saveData($data);
            return true;
        }
        return false;
    }

    public function resetUserPassword(string $email, string $newPassword): bool
    {
        $updated = false;
        $cleanEmail = strtolower(trim($email));

        try {
            $user = User::whereRaw('LOWER(email) = ?', [$cleanEmail])
                ->orWhereRaw('LOWER(username) = ?', [$cleanEmail])
                ->first();
            if ($user) {
                $user->password = Hash::make($newPassword);
                $user->save();
                $updated = true;
            }
        } catch (\Throwable $e) {
            Log::warning('[DB ResetPassword] Fallback to JSON: ' . $e->getMessage());
        }

        $data = $this->getData();
        if (isset($data['users'])) {
            foreach ($data['users'] as &$u) {
                if (strcasecmp($u['email'] ?? '', $cleanEmail) === 0 || strcasecmp($u['username'] ?? '', $cleanEmail) === 0) {
                    $u['password_hash'] = password_hash($newPassword, PASSWORD_BCRYPT);
                    $this->saveData($data);
                    $updated = true;
                    break;
                }
            }
        }

        return $updated;
    }

    // =========================================================================
    // 2. PENGELOLAAN LAPORAN ADUAN (REPORTS)
    // =========================================================================

    public function getReports(): array
    {
        try {
            $reports = Report::orderBy('created_at', 'desc')->get();
            if ($reports->isNotEmpty()) {
                return $reports->map(function ($r) {
                    return [
                        'id' => (string) $r->id,
                        'url' => $r->url,
                        'date' => $r->created_at ? $r->created_at->timezone('Asia/Makassar')->translatedFormat('d M Y, H:i') . ' WITA' : 'Hari ini',
                        'reporter' => $r->reporter ?? 'Anonim',
                        'category' => $r->category,
                        'description' => $r->description,
                        'status' => $r->status ?? 'Masuk',
                        'verificationNotes' => $r->verification_notes ?? '',
                        'handledBy' => $r->handled_by ?? 'Belum Ditugaskan',
                        'evidencePath' => $r->evidence_path,
                        'aiAnalysis' => $r->ai_analysis,
                    ];
                })->toArray();
            }
        } catch (\Throwable $e) {
            Log::warning('[DB Reports] Fallback to JSON: ' . $e->getMessage());
        }

        return $this->getData()['reports'] ?? [];
    }

    public function createReport(array $report): array
    {
        $newId = $report['id'] ?? null;
        if (!$newId) {
            try {
                $latest = Report::orderBy('id', 'desc')->first();
                $num = 82;
                if ($latest && preg_match('/LPR(\d+)/', (string) $latest->id, $m)) {
                    $num = intval($m[1]) + 1;
                }
                $newId = 'LPR' . str_pad((string) $num, 3, '0', STR_PAD_LEFT);
            } catch (\Throwable $e) {
                $newId = 'LPR' . str_pad((string) (count($this->getData()['reports'] ?? []) + 82), 3, '0', STR_PAD_LEFT);
            }
        }

        $record = [
            'id' => $newId,
            'url' => $report['url'] ?? '',
            'date' => now()->timezone('Asia/Makassar')->translatedFormat('d M Y, H:i') . ' WITA',
            'reporter' => $report['reporter'] ?? 'Anonim',
            'category' => $report['category'] ?? 'Promosi Situs Judi Online / Slot',
            'description' => $report['description'] ?? '',
            'status' => $report['status'] ?? 'Masuk',
            'verificationNotes' => $report['verificationNotes'] ?? 'Laporan baru diterima dari portal publik. Menunggu verifikasi awal.',
            'handledBy' => $report['handledBy'] ?? 'Belum Ditugaskan',
            'evidencePath' => $report['evidencePath'] ?? null,
            'aiAnalysis' => $report['aiAnalysis'] ?? null
        ];

        try {
            $payload = [
                'id' => $newId,
                'url' => $record['url'],
                'reporter' => $record['reporter'],
                'category' => $record['category'],
                'description' => $record['description'],
                'status' => $record['status'],
                'verification_notes' => $record['verificationNotes'],
                'handled_by' => $record['handledBy'],
                'evidence_path' => $record['evidencePath'],
            ];
            if (\Illuminate\Support\Facades\Schema::hasColumn('reports', 'ai_analysis')) {
                $payload['ai_analysis'] = $record['aiAnalysis'];
            }
            Report::create($payload);

            $this->addActivity([
                'user' => $record['reporter'],
                'action' => "Mengirim laporan aduan siber #{$newId}",
                'icon' => 'inbox-fill'
            ]);

            return $record;
        } catch (\Throwable $e) {
            Log::warning('[DB CreateReport] Fallback to JSON: ' . $e->getMessage());
        }

        $data = $this->getData();
        array_unshift($data['reports'], $record);
        $this->saveData($data);
        return $record;
    }

    public function updateReport(string $id, array $input): ?array
    {
        try {
            $report = Report::find($id);
            if ($report) {
                if (isset($input['status'])) $report->status = $input['status'];
                if (isset($input['verificationNotes'])) $report->verification_notes = $input['verificationNotes'];
                if (isset($input['verification_notes'])) $report->verification_notes = $input['verification_notes'];
                if (isset($input['handledBy'])) $report->handled_by = $input['handledBy'];
                if (isset($input['assigned_admin'])) $report->handled_by = $input['assigned_admin'];
                $report->save();

                $this->addActivity([
                    'user' => $report->handled_by ?? 'Admin',
                    'action' => "Memperbarui status laporan #{$id} menjadi \"{$report->status}\"",
                    'icon' => 'arrow-repeat'
                ]);

                return [
                    'id' => (string) $report->id,
                    'url' => $report->url,
                    'date' => $report->created_at ? $report->created_at->timezone('Asia/Makassar')->translatedFormat('d M Y, H:i') . ' WITA' : 'Hari ini',
                    'reporter' => $report->reporter ?? 'Anonim',
                    'category' => $report->category,
                    'description' => $report->description,
                    'status' => $report->status,
                    'verificationNotes' => $report->verification_notes ?? '',
                    'handledBy' => $report->handled_by ?? 'Belum Ditugaskan',
                    'evidencePath' => $report->evidence_path,
                    'aiAnalysis' => $report->ai_analysis,
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('[DB UpdateReport] Fallback to JSON: ' . $e->getMessage());
        }

        $data = $this->getData();
        foreach ($data['reports'] ?? [] as &$r) {
            if (($r['id'] ?? '') === $id) {
                if (isset($input['status'])) $r['status'] = $input['status'];
                if (isset($input['verificationNotes'])) $r['verificationNotes'] = $input['verificationNotes'];
                if (isset($input['handledBy'])) $r['handledBy'] = $input['handledBy'];
                $this->saveData($data);
                return $r;
            }
        }
        return null;
    }

    public function deleteReport(string $id): bool
    {
        try {
            $report = Report::find($id);
            if ($report) {
                $report->delete();
                return true;
            }
        } catch (\Throwable $e) {
            Log::warning('[DB DeleteReport] Fallback to JSON: ' . $e->getMessage());
        }

        $data = $this->getData();
        $initialCount = count($data['reports'] ?? []);
        $data['reports'] = array_values(array_filter($data['reports'] ?? [], fn($r) => ($r['id'] ?? '') !== $id));
        if (count($data['reports']) < $initialCount) {
            $this->saveData($data);
            return true;
        }
        return false;
    }

    // =========================================================================
    // 3. PENGELOLAAN ARTIKEL EDUKASI (ARTICLES)
    // =========================================================================

    public function getArticles(): array
    {
        try {
            $articles = Article::orderBy('created_at', 'desc')->get();
            if ($articles->isNotEmpty()) {
                return $articles->map(function ($a) {
                    $img = $a->cover_image ?? '../images/default-article.jpg';
                    $summaryText = $a->excerpt ?? '';
                    return [
                        'id' => (int) $a->id,
                        'title' => $a->title,
                        'slug' => $a->slug,
                        'category' => $a->category,
                        'excerpt' => $summaryText,
                        'summary' => $summaryText,
                        'content' => $a->content,
                        'author' => $a->author ?? 'Tim Edukasi Aswaja',
                        'date' => $a->created_at ? $a->created_at->translatedFormat('d M Y') : 'Hari ini',
                        'readTime' => $a->read_time ?? '5 Menit Baca',
                        'status' => $a->status ?? 'Published',
                        'image' => $img,
                        'cover_image' => $img,
                        'coverImage' => $img
                    ];
                })->toArray();
            }
        } catch (\Throwable $e) {
            Log::warning('[DB Articles] Fallback to JSON: ' . $e->getMessage());
        }

        return $this->getData()['articles'] ?? [];
    }

    public function createArticle(array $input): array
    {
        $coverImg = $input['coverImage'] ?? $input['cover_image'] ?? $input['image'] ?? '../images/default-article.jpg';
        $excerptText = $input['excerpt'] ?? $input['summary'] ?? substr(strip_tags($input['content'] ?? ''), 0, 150);
        $slugText = !empty($input['slug']) ? Str::slug($input['slug']) : Str::slug($input['title'] ?? 'artikel-' . time());

        $newArticle = [
            'title' => $input['title'] ?? 'Artikel Baru',
            'slug' => $slugText,
            'category' => $input['category'] ?? 'Fiqih Siber',
            'excerpt' => $excerptText,
            'summary' => $excerptText,
            'content' => $input['content'] ?? '',
            'author' => $input['author'] ?? 'Tim Edukasi Aswaja',
            'date' => now()->translatedFormat('d M Y'),
            'readTime' => $input['readTime'] ?? '5 Menit Baca',
            'status' => $input['status'] ?? 'Published',
            'image' => $coverImg,
            'cover_image' => $coverImg,
            'coverImage' => $coverImg
        ];

        try {
            $created = Article::create([
                'title' => $newArticle['title'],
                'slug' => $newArticle['slug'],
                'category' => $newArticle['category'],
                'excerpt' => $newArticle['excerpt'],
                'content' => $newArticle['content'],
                'author' => $newArticle['author'],
                'read_time' => $newArticle['readTime'],
                'status' => $newArticle['status'],
                'cover_image' => $newArticle['coverImage'],
            ]);
            $newArticle['id'] = (int) $created->id;

            $this->addActivity([
                'user' => $newArticle['author'],
                'action' => 'Menerbitkan artikel edukasi: "' . $newArticle['title'] . '"',
                'icon' => 'file-earmark-text'
            ]);

            return $newArticle;
        } catch (\Throwable $e) {
            Log::warning('[DB CreateArticle] Fallback to JSON: ' . $e->getMessage());
        }

        $data = $this->getData();
        $newId = count($data['articles'] ?? []) > 0 ? max(array_column($data['articles'], 'id')) + 1 : 1;
        $newArticle['id'] = $newId;
        array_unshift($data['articles'], $newArticle);
        $this->saveData($data);
        return $newArticle;
    }

    public function updateArticle(int $id, array $input): ?array
    {
        try {
            $article = Article::find($id);
            if ($article) {
                if (isset($input['title'])) {
                    $article->title = $input['title'];
                    $article->slug = !empty($input['slug']) ? Str::slug($input['slug']) : Str::slug($input['title']);
                } elseif (!empty($input['slug'])) {
                    $article->slug = Str::slug($input['slug']);
                }
                if (isset($input['category'])) $article->category = $input['category'];
                if (isset($input['summary'])) $article->excerpt = $input['summary'];
                if (isset($input['excerpt'])) $article->excerpt = $input['excerpt'];
                if (isset($input['content'])) $article->content = $input['content'];
                if (isset($input['author'])) $article->author = $input['author'];
                if (isset($input['readTime'])) $article->read_time = $input['readTime'];
                if (isset($input['status'])) $article->status = $input['status'];
                if (array_key_exists('image', $input)) $article->cover_image = $input['image'];
                if (array_key_exists('coverImage', $input)) $article->cover_image = $input['coverImage'];
                if (array_key_exists('cover_image', $input)) $article->cover_image = $input['cover_image'];
                $article->save();

                $this->addActivity([
                    'user' => $article->author ?? 'Admin',
                    'action' => 'Memperbarui artikel edukasi: "' . $article->title . '"',
                    'icon' => 'pencil-square'
                ]);

                return [
                    'id' => (int) $article->id,
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'category' => $article->category,
                    'excerpt' => $article->excerpt,
                    'summary' => $article->excerpt,
                    'content' => $article->content,
                    'author' => $article->author,
                    'date' => $article->created_at ? $article->created_at->translatedFormat('d M Y') : 'Hari ini',
                    'readTime' => $article->read_time,
                    'status' => $article->status,
                    'image' => $article->cover_image,
                    'cover_image' => $article->cover_image,
                    'coverImage' => $article->cover_image
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('[DB UpdateArticle] Fallback to JSON: ' . $e->getMessage());
        }

        $data = $this->getData();
        foreach ($data['articles'] ?? [] as &$article) {
            if ((int) ($article['id'] ?? 0) === $id) {
                $article['title'] = $input['title'] ?? $article['title'];
                $article['slug'] = !empty($input['slug']) ? Str::slug($input['slug']) : ($article['slug'] ?? Str::slug($article['title']));
                $article['category'] = $input['category'] ?? $article['category'];
                $article['excerpt'] = $input['excerpt'] ?? $input['summary'] ?? ($article['excerpt'] ?? '');
                $article['summary'] = $article['excerpt'];
                $article['content'] = $input['content'] ?? $article['content'];
                $article['author'] = $input['author'] ?? $article['author'];
                $article['status'] = $input['status'] ?? $article['status'];
                if (array_key_exists('image', $input) || array_key_exists('cover_image', $input) || array_key_exists('coverImage', $input)) {
                    $imgVal = $input['coverImage'] ?? $input['cover_image'] ?? $input['image'] ?? null;
                    $article['image'] = $imgVal;
                    $article['cover_image'] = $imgVal;
                    $article['coverImage'] = $imgVal;
                }
                $this->saveData($data);
                return $article;
            }
        }
        return null;
    }

    public function deleteArticle(int $id): bool
    {
        try {
            $article = Article::find($id);
            if ($article) {
                $article->delete();
                return true;
            }
        } catch (\Throwable $e) {
            Log::warning('[DB DeleteArticle] Fallback to JSON: ' . $e->getMessage());
        }

        $data = $this->getData();
        $initialCount = count($data['articles'] ?? []);
        $data['articles'] = array_values(array_filter($data['articles'] ?? [], fn($a) => (int) ($a['id'] ?? 0) !== $id));
        if (count($data['articles']) < $initialCount) {
            $this->saveData($data);
            return true;
        }
        return false;
    }

    // =========================================================================
    // 4. PENGELOLAAN STUDI KASUS CYBER GUARD (CASE STUDIES)
    // =========================================================================

    public function getCaseStudies(): array
    {
        try {
            $cases = CaseStudy::orderBy('id', 'asc')->get();
            if ($cases->isNotEmpty()) {
                return $cases->map(function ($c) {
                    return [
                        'id' => (int) $c->id,
                        'title' => $c->title,
                        'scenario' => $c->scenario,
                        'sender' => $c->sender,
                        'context' => $c->sender,
                        'message' => $c->message,
                        'riskLevel' => $c->risk_level,
                        'fiqihPrinciple' => $c->fiqih_principle,
                        'question' => $c->question,
                        'options' => $c->options,
                        'discussion' => $c->discussion,
                        'explanation' => $c->discussion,
                        'date' => $c->created_at ? $c->created_at->translatedFormat('d M Y') : '01 Nov 2026'
                    ];
                })->toArray();
            }
        } catch (\Throwable $e) {
            Log::warning('[DB CaseStudies] Fallback to JSON: ' . $e->getMessage());
        }

        return $this->getData()['case_studies'] ?? $this->getData()['caseStudies'] ?? [];
    }

    public function createCaseStudy(array $input): array
    {
        $newCase = [
            'title' => $input['title'] ?? 'Kasus Baru',
            'scenario' => $input['scenario'] ?? '',
            'sender' => $input['sender'] ?? 'Pengirim Tidak Dikenal',
            'message' => $input['message'] ?? '',
            'riskLevel' => $input['riskLevel'] ?? $input['risk_level'] ?? 'Sedang',
            'fiqihPrinciple' => $input['fiqihPrinciple'] ?? $input['fiqih_principle'] ?? 'Tatsabbut',
            'question' => $input['question'] ?? 'Langkah apa yang harus diambil?',
            'options' => $input['options'] ?? [],
            'discussion' => $input['discussion'] ?? ''
        ];

        try {
            $created = CaseStudy::create([
                'title' => $newCase['title'],
                'scenario' => $newCase['scenario'],
                'sender' => $newCase['sender'],
                'message' => $newCase['message'],
                'risk_level' => $newCase['riskLevel'],
                'fiqih_principle' => $newCase['fiqihPrinciple'],
                'question' => $newCase['question'],
                'options' => $newCase['options'],
                'discussion' => $newCase['discussion'],
            ]);
            $newCase['id'] = (int) $created->id;
            return $newCase;
        } catch (\Throwable $e) {
            Log::warning('[DB CreateCaseStudy] Fallback to JSON: ' . $e->getMessage());
        }

        $data = $this->getData();
        $newId = count($data['caseStudies'] ?? []) > 0 ? max(array_column($data['caseStudies'], 'id')) + 1 : 1;
        $newCase['id'] = $newId;
        array_unshift($data['caseStudies'], $newCase);
        $this->saveData($data);
        return $newCase;
    }

    public function updateCaseStudy(int $id, array $input): ?array
    {
        try {
            $case = CaseStudy::find($id);
            if ($case) {
                if (isset($input['title'])) $case->title = $input['title'];
                if (isset($input['scenario'])) $case->scenario = $input['scenario'];
                if (isset($input['sender'])) $case->sender = $input['sender'];
                if (isset($input['message'])) $case->message = $input['message'];
                if (isset($input['riskLevel'])) $case->risk_level = $input['riskLevel'];
                if (isset($input['risk_level'])) $case->risk_level = $input['risk_level'];
                if (isset($input['fiqihPrinciple'])) $case->fiqih_principle = $input['fiqihPrinciple'];
                if (isset($input['fiqih_principle'])) $case->fiqih_principle = $input['fiqih_principle'];
                if (isset($input['question'])) $case->question = $input['question'];
                if (isset($input['options'])) $case->options = $input['options'];
                if (isset($input['discussion'])) $case->discussion = $input['discussion'];
                $case->save();

                return [
                    'id' => (int) $case->id,
                    'title' => $case->title,
                    'scenario' => $case->scenario,
                    'sender' => $case->sender,
                    'message' => $case->message,
                    'riskLevel' => $case->risk_level,
                    'fiqihPrinciple' => $case->fiqih_principle,
                    'question' => $case->question,
                    'options' => $case->options,
                    'discussion' => $case->discussion
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('[DB UpdateCaseStudy] Fallback to JSON: ' . $e->getMessage());
        }

        $data = $this->getData();
        foreach ($data['caseStudies'] ?? [] as &$case) {
            if ((int) ($case['id'] ?? 0) === $id) {
                $case['title'] = $input['title'] ?? $case['title'];
                $case['scenario'] = $input['scenario'] ?? $case['scenario'];
                $case['question'] = $input['question'] ?? $case['question'];
                $this->saveData($data);
                return $case;
            }
        }
        return null;
    }

    public function deleteCaseStudy(int $id): bool
    {
        try {
            $case = CaseStudy::find($id);
            if ($case) {
                $case->delete();
                return true;
            }
        } catch (\Throwable $e) {
            Log::warning('[DB DeleteCaseStudy] Fallback to JSON: ' . $e->getMessage());
        }

        $data = $this->getData();
        $initialCount = count($data['caseStudies'] ?? []);
        $data['caseStudies'] = array_values(array_filter($data['caseStudies'] ?? [], fn($c) => (int) ($c['id'] ?? 0) !== $id));
        if (count($data['caseStudies']) < $initialCount) {
            $this->saveData($data);
            return true;
        }
        return false;
    }

    // =========================================================================
    // 5. PENGELOLAAN PRINSIP 4T
    // =========================================================================

    public function getPrinsip4T(): array
    {
        try {
            $items = Prinsip4T::orderBy('id', 'asc')->get();
            if ($items->isNotEmpty()) {
                return $items->map(function ($p) {
                    return [
                        'id' => (int) $p->id,
                        'code' => $p->code,
                        'title' => $p->name,
                        'name' => $p->name,
                        'arabic' => $p->arabic,
                        'subtitle' => $p->tagline,
                        'tagline' => $p->tagline,
                        'description' => $p->description,
                        'link' => $p->link,
                        'icon' => $p->icon,
                    ];
                })->toArray();
            }
        } catch (\Throwable $e) {
            Log::warning('[DB Prinsip4T] Fallback to JSON: ' . $e->getMessage());
        }

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

    public function updatePrinsip4T(string|int $id, array $input): ?array
    {
        try {
            $item = is_numeric($id) ? Prinsip4T::find((int) $id) : null;
            if (!$item && is_string($id)) {
                $item = Prinsip4T::where('code', strtoupper($id))->first();
            }
            if ($item) {
                $name = $input['title'] ?? $input['name'] ?? $item->name;
                $tagline = $input['subtitle'] ?? $input['tagline'] ?? $item->tagline;
                $item->name = $name;
                $item->tagline = $tagline;
                if (isset($input['arabic'])) $item->arabic = $input['arabic'];
                if (isset($input['description'])) $item->description = $input['description'];
                if (isset($input['link'])) $item->link = $input['link'];
                if (isset($input['icon'])) $item->icon = $input['icon'];
                $item->save();

                return [
                    'id' => (int) $item->id,
                    'code' => $item->code,
                    'title' => $item->name,
                    'name' => $item->name,
                    'arabic' => $item->arabic,
                    'subtitle' => $item->tagline,
                    'tagline' => $item->tagline,
                    'description' => $item->description,
                    'link' => $item->link,
                    'icon' => $item->icon
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('[DB UpdatePrinsip4T] Fallback to JSON: ' . $e->getMessage());
        }

        $data = $this->getData();
        foreach ($data['prinsip4T'] ?? [] as &$item) {
            if ((int) ($item['id'] ?? 0) === (int) $id || strtolower($item['code'] ?? '') === strtolower((string) $id)) {
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

    // =========================================================================
    // 6. AKTIVITAS & DASHBOARD STATS (ACTIVITIES)
    // =========================================================================

    public function getActivities(): array
    {
        try {
            $acts = Activity::orderBy('created_at', 'desc')->limit(10)->get();
            if ($acts->isNotEmpty()) {
                return $acts->map(function ($a) {
                    return [
                        'user' => $a->user_name,
                        'action' => $a->action,
                        'time' => $a->created_at ? $a->created_at->diffForHumans() : 'Baru saja',
                        'icon' => $a->icon ?? 'activity',
                        'badgeClass' => $a->badge_class ?? 'text-emerald-600 bg-emerald-50'
                    ];
                })->toArray();
            }
        } catch (\Throwable $e) {
            Log::warning('[DB Activities] Fallback to JSON: ' . $e->getMessage());
        }

        return array_slice($this->getData()['activities'] ?? [], 0, 10);
    }

    public function addActivity(array $data): void
    {
        try {
            Activity::create([
                'user_name' => $data['user'] ?? 'Sistem',
                'action' => $data['action'] ?? '',
                'icon' => $data['icon'] ?? 'check-circle',
                'badge_class' => $data['badgeClass'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::warning('[DB AddActivity] Fallback to JSON: ' . $e->getMessage());
        }

        $allData = $this->getData();
        array_unshift($allData['activities'], [
            'user' => $data['user'] ?? 'Sistem',
            'action' => $data['action'] ?? '',
            'time' => 'Baru saja',
            'icon' => $data['icon'] ?? 'check-circle'
        ]);
        $this->saveData($allData);
    }

    // 7. RIWAYAT AUDIT LINK SCANS
    public function getLinkScans(): array
    {
        try {
            $scans = \App\Models\LinkScan::orderBy('scanned_at', 'desc')->get();
            if ($scans->isNotEmpty()) {
                return $scans->toArray();
            }
        } catch (\Throwable $e) {
            Log::warning('[DB LinkScans] Fallback to JSON: ' . $e->getMessage());
        }

        return $this->getData()['link_scans'] ?? [];
    }
}
