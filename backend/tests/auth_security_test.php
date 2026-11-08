<?php
/**
 * Test Otomatis Keamanan Autentikasi:
 * 1. Session Inactivity Timeout (5 menit)
 * 2. Login Throttling & Account Lockout Escalation (3x salah -> 1m, 3m, 5m, 15m, 1h)
 */

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Repositories\MockDataRepository;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;

$repository = new MockDataRepository();
$controller = new AuthController($repository);

echo "=== 1. Test Login Gagal & Eskalasi Lockout (Anti-Brute Force) ===\n";

$testUser = 'admin_security_test';
$ip = '127.0.0.1';
$throttleKey = 'throttle_' . md5($ip . '_' . strtolower($testUser));
$repository->clearLoginThrottle($throttleKey);

// Percobaan 1: Salah password
$req1 = Request::create('/api/admin/login', 'POST', ['username' => $testUser, 'password' => 'wrongpass1']);
$req1->server->set('REMOTE_ADDR', $ip);
$res1 = $controller->login($req1);
$data1 = json_decode($res1->getContent(), true);
assert($res1->getStatusCode() === 401, "Status code harus 401");
assert($data1['remaining_attempts'] === 2, "Sisa percobaan harus 2");
echo "✓ Percobaan 1 gagal: Sisa {$data1['remaining_attempts']}x\n";

// Percobaan 2: Salah password
$req2 = Request::create('/api/admin/login', 'POST', ['username' => $testUser, 'password' => 'wrongpass2']);
$req2->server->set('REMOTE_ADDR', $ip);
$res2 = $controller->login($req2);
$data2 = json_decode($res2->getContent(), true);
assert($res2->getStatusCode() === 401, "Status code harus 401");
assert($data2['remaining_attempts'] === 1, "Sisa percobaan harus 1");
echo "✓ Percobaan 2 gagal: Sisa {$data2['remaining_attempts']}x\n";

// Percobaan 3: Salah password -> Harus Terkunci Tier 1 (1 menit / 60 detik)
$req3 = Request::create('/api/admin/login', 'POST', ['username' => $testUser, 'password' => 'wrongpass3']);
$req3->server->set('REMOTE_ADDR', $ip);
$res3 = $controller->login($req3);
$data3 = json_decode($res3->getContent(), true);
assert($res3->getStatusCode() === 429, "Status code harus 429 (Too Many Requests)");
assert($data3['locked'] === true, "Harus terkunci");
assert($data3['cooldown_seconds'] === 60, "Tier 1 cooldown harus 60 detik (1 menit)");
echo "✓ Percobaan 3 gagal: AKUN TERKUNCI Tier 1 selama {$data3['cooldown_seconds']} detik (1 menit)\n";

// Coba login saat terkunci -> harus langsung ditolak dengan 429
$reqLock = Request::create('/api/admin/login', 'POST', ['username' => $testUser, 'password' => 'anypass']);
$reqLock->server->set('REMOTE_ADDR', $ip);
$resLock = $controller->login($reqLock);
$dataLock = json_decode($resLock->getContent(), true);
assert($resLock->getStatusCode() === 429, "Status code harus 429 saat dalam cooldown");
echo "✓ Login saat cooldown: DITOLAK seketika (429 Too Many Requests)\n";

// Simulasi cooldown tier 1 selesai, lalu gagal 3x lagi -> harus terkunci Tier 2 (3 menit / 180 detik)
$t = $repository->getLoginThrottle($throttleKey);
$t['locked_until'] = time() - 1; // simulasikan waktu cooldown habis
$repository->setLoginThrottle($throttleKey, $t);

for ($i = 1; $i <= 3; $i++) {
    $r = Request::create('/api/admin/login', 'POST', ['username' => $testUser, 'password' => 'wrongpass_tier2_' . $i]);
    $r->server->set('REMOTE_ADDR', $ip);
    $resTier2 = $controller->login($r);
}
$dataTier2 = json_decode($resTier2->getContent(), true);
assert($resTier2->getStatusCode() === 429, "Status code harus 429");
assert($dataTier2['cooldown_seconds'] === 180, "Tier 2 cooldown harus 180 detik (3 menit)");
echo "✓ Gagal 3x lagi: AKUN TERKUNCI Tier 2 selama {$dataTier2['cooldown_seconds']} detik (3 menit)\n";

// Bersihkan throttle test
$repository->clearLoginThrottle($throttleKey);

echo "\n=== 2. Test Session Inactivity Timeout (5 Menit) ===\n";

$testToken = 'test_token_' . bin2hex(random_bytes(8));
$repository->storeToken($testToken, 1);

// Cek token baru dibuat (aktivitas baru saja terjadi) -> harus valid
$userNow = $repository->getUserByToken($testToken);
assert($userNow !== null, "Token baru harus valid");
echo "✓ Token aktif terverifikasi valid\n";

// Simulasikan tidak ada aktivitas selama 301 detik (> 5 menit)
$jsonFile = storage_path('app/mock_database.json');
$dataMock = json_decode(file_get_contents($jsonFile), true);
$dataMock['tokens'][$testToken]['last_activity'] = time() - 301;
file_put_contents($jsonFile, json_encode($dataMock, JSON_PRETTY_PRINT));

// Cek kembali -> harus hangus (null) karena melebihi 5 menit
$userExpired = $repository->getUserByToken($testToken);
assert($userExpired === null, "Token harus hangus setelah 5 menit tidak aktif");
echo "✓ Token hangus otomatis setelah 5 menit tidak ada aktivitas (Inactivity Timeout)\n";

echo "\nSEMUA TEST KEAMANAN DAN AUTENTIKASI BERHASIL (100% PASS)!\n";
