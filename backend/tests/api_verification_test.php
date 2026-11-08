<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;

echo "=== TEST SUITE REST API ASWAJA CYBERSHIELD ===\n\n";

function callRoute($method, $uri, $data = [], $token = null) {
    $server = [
        'REQUEST_METHOD' => $method,
        'CONTENT_TYPE' => 'application/json',
        'HTTP_ACCEPT' => 'application/json'
    ];
    if ($token) {
        $server['HTTP_AUTHORIZATION'] = "Bearer {$token}";
    }

    $request = Request::create(
        $uri,
        $method,
        [],
        [],
        [],
        $server,
        !empty($data) ? json_encode($data) : null
    );

    return app()->handle($request);
}

function assertTest($name, $condition, $details = '') {
    if ($condition) {
        echo "✅ PASS: {$name}\n";
    } else {
        echo "❌ FAIL: {$name} - Details: {$details}\n";
    }
}

// 1. Health Check
$res = callRoute('GET', '/api/ping');
assertTest('Ping Endpoint', $res->getStatusCode() === 200, $res->getContent());

// 2. Analisis Tautan Publik (Multi-Agent Dual AI / Heuristic)
$res = callRoute('POST', '/api/analyze-link', ['url' => 'https://cuan-trading-vip88.vip/slot-deposit']);
$json = json_decode($res->getContent(), true);
assertTest('Analyze Link (Judol Suspicious)', $res->getStatusCode() === 200 && ($json['alertClass'] ?? '') === 'red', $res->getContent());

// 2b. Analisis Tautan Bersih / Legal (Kompas.com)
$resClean = callRoute('POST', '/api/analyze-link', ['url' => 'https://www.kompas.com/']);
$jsonClean = json_decode($resClean->getContent(), true);
assertTest('Analyze Link Clean (Kompas.com - Emerald & Valid Findings)', 
    $resClean->getStatusCode() === 200 && 
    ($jsonClean['alertClass'] ?? '') === 'emerald' && 
    !empty($jsonClean['findings']) && 
    count($jsonClean['findings']) >= 2, 
    $resClean->getContent()
);

// 3. Kirim Laporan Publik
\Illuminate\Support\Facades\RateLimiter::clear('report_submission:127.0.0.1');
$res = callRoute('POST', '/api/reports', [
    'url' => 'https://investasi-bodong-palsu.cc/claim',
    'reporter' => 'Warga Kaltim',
    'category' => 'Tautan Promosi Slot / Kasino Berkedok Game Finansial',
    'description' => 'Tautan penipuan berkedok arisan syariah dengan kupon putaran slot.'
]);
$json = json_decode($res->getContent(), true);
assertTest('Submit Report Public', $res->getStatusCode() === 201 && isset($json['ticketId']), $res->getContent());
$newTicketId = $json['ticketId'] ?? 'LPR082';

// 4. Get Public Articles
$res = callRoute('GET', '/api/articles');
assertTest('Get Public Articles', $res->getStatusCode() === 200, $res->getContent());

// 5. Get Public Case Studies & Evaluate Quiz
$res = callRoute('GET', '/api/case-studies');
assertTest('Get Public Case Studies', $res->getStatusCode() === 200, $res->getContent());

$res = callRoute('POST', '/api/cyber-guard/evaluate', [
    'answers' => [
        ['caseId' => 1, 'selectedKey' => 'A']
    ]
]);
$json = json_decode($res->getContent(), true);
assertTest('Evaluate Cyber Guard Quiz', $res->getStatusCode() === 200 && ($json['score'] ?? 0) === 100, $res->getContent());

// 6. Get Public Prinsip 4T
$res = callRoute('GET', '/api/prinsip-4t');
assertTest('Get Prinsip 4T', $res->getStatusCode() === 200, $res->getContent());

// 7. Login Super Admin
$res = callRoute('POST', '/api/admin/login', [
    'username' => 'admin',
    'password' => 'Aswaja2026!'
]);
$json = json_decode($res->getContent(), true);
$superToken = $json['token'] ?? null;
assertTest('Login Super Admin', $res->getStatusCode() === 200 && !empty($superToken), $res->getContent());

// 8. Protected: Get Dashboard Stats
$res = callRoute('GET', '/api/dashboard/stats', [], $superToken);
$json = json_decode($res->getContent(), true);
assertTest('Get Dashboard Stats', $res->getStatusCode() === 200 && isset($json['data']['totalArticles']), $res->getContent());

// 9. Protected: Update Report Status
$res = callRoute('PUT', "/api/reports/{$newTicketId}", [
    'status' => 'Diproses',
    'verificationNotes' => 'Sedang dalam penelusuran sanad domain dan registrasi Bappebti.',
    'assignedAdmin' => 'Admin Utama'
], $superToken);
assertTest('Update Report Status', $res->getStatusCode() === 200, $res->getContent());

// 10. Dual AI Status & Test Connection (Super Admin Only)
$res = callRoute('GET', '/api/admin/ai/status', [], $superToken);
assertTest('Dual AI Status Endpoint (/api/admin/ai/status)', $res->getStatusCode() === 200, $res->getContent());

$res = callRoute('POST', '/api/admin/ai/test', [], $superToken);
assertTest('Dual AI Safe Test Probe (/api/admin/ai/test)', $res->getStatusCode() === 200, $res->getContent());

// 11. RBAC Check: Login as Admin Basic (analis)
$res = callRoute('POST', '/api/admin/login', [
    'username' => 'analis',
    'password' => 'Aswaja2026!'
]);
$json = json_decode($res->getContent(), true);
$basicToken = $json['token'] ?? null;
assertTest('Login Admin Basic', $res->getStatusCode() === 200 && !empty($basicToken), $res->getContent());

// Admin Basic cannot access AI status (Expect 403 Forbidden)
$res = callRoute('GET', '/api/admin/ai/status', [], $basicToken);
assertTest('RBAC: Admin Basic Access to AI Status Denied (403)', $res->getStatusCode() === 403, "Status: " . $res->getStatusCode());

// Admin Basic cannot delete reports (Expect 403 Forbidden)
$res = callRoute('DELETE', "/api/reports/{$newTicketId}", [], $basicToken);
assertTest('RBAC: Admin Basic Delete Report Denied (403)', $res->getStatusCode() === 403, "Status: " . $res->getStatusCode());

// Super Admin CAN delete reports (Expect 200 OK)
$res = callRoute('DELETE', "/api/reports/{$newTicketId}", [], $superToken);
assertTest('RBAC: Super Admin Delete Report Permitted (200)', $res->getStatusCode() === 200, $res->getContent());

// 12. Logout
$res = callRoute('POST', '/api/admin/logout', [], $superToken);
assertTest('Logout Super Admin', $res->getStatusCode() === 200, $res->getContent());

echo "\n=== ALL 15 AUTOMATED TESTS COMPLETED ===\n";

