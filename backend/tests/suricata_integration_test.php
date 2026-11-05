<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\SuricataIntegrationService;

echo "=== UNIT TEST: SURICATA NIDS & EVE JSON INGESTION (TAHAP 3) ===\n\n";

$passed = 0;
$failed = 0;

function runTest(string $description, callable $fn, int &$passed, int &$failed): void
{
    try {
        $result = $fn();
        if ($result === true) {
            echo "✅ PASS: {$description}\n";
            $passed++;
        } else {
            echo "❌ FAIL: {$description}\n";
            $failed++;
        }
    } catch (\Throwable $e) {
        echo "❌ ERROR: {$description} -> " . $e->getMessage() . "\n";
        $failed++;
    }
}

// 1. Pengujian jika file eve.json tidak ada (Anti-Simulasi / Aturan 7 & 8)
runTest("Suricata NIDS melaporkan 'tidak_tersedia' jika file eve.json tidak ada", function () {
    $service = new SuricataIntegrationService(storage_path('framework/testing/nonexistent_eve.json'));
    $result = $service->inspectNetworkEvents('slot-gacor777.top');

    return !$service->isAvailable()
        && $result['status'] === 'tidak_tersedia'
        && $result['available'] === false
        && $result['matched'] === false
        && $result['alerts_count'] === 0
        && str_contains($result['message'], 'belum aktif');
}, $passed, $failed);

// 2. Pengujian Ingestion EVE JSON Nyata dengan Sampel Event Lengkap
$testingDir = storage_path('framework/testing');
if (!is_dir($testingDir)) {
    @mkdir($testingDir, 0755, true);
}
$tempEveFile = $testingDir . '/test_eve_' . time() . '.json';

$sampleEvents = [
    // Event 1: DNS Query
    json_encode([
        'timestamp' => '2026-11-04T08:30:00.123456+0800',
        'event_type' => 'dns',
        'src_ip' => '192.168.1.100',
        'dest_ip' => '8.8.8.8',
        'proto' => 'UDP',
        'dns' => [
            'type' => 'query',
            'id' => 1234,
            'rrname' => 'slot-gacor777.top',
            'rrtype' => 'A'
        ]
    ]),
    // Event 2: Alert DNS Judi Online (SID: 1000001)
    json_encode([
        'timestamp' => '2026-11-04T08:30:00.124000+0800',
        'event_type' => 'alert',
        'src_ip' => '192.168.1.100',
        'dest_ip' => '8.8.8.8',
        'proto' => 'UDP',
        'dns' => [
            'rrname' => 'slot-gacor777.top'
        ],
        'alert' => [
            'action' => 'allowed',
            'gid' => 1,
            'signature_id' => 1000001,
            'rev' => 1,
            'signature' => 'SUSPICIOUS DOMAIN - JUDI ONLINE',
            'category' => 'Policy Violation',
            'severity' => 1
        ]
    ]),
    // Event 3: TLS SNI Handshake
    json_encode([
        'timestamp' => '2026-11-04T08:30:01.555000+0800',
        'event_type' => 'tls',
        'src_ip' => '192.168.1.100',
        'dest_ip' => '104.21.50.1',
        'proto' => 'TCP',
        'tls' => [
            'subject' => 'CN=slot-gacor777.top',
            'sni' => 'slot-gacor777.top',
            'version' => 'TLS 1.3'
        ]
    ]),
    // Event 4: Alert TLS SNI (SID: 1000004)
    json_encode([
        'timestamp' => '2026-11-04T08:30:01.556000+0800',
        'event_type' => 'alert',
        'src_ip' => '192.168.1.100',
        'dest_ip' => '104.21.50.1',
        'proto' => 'TCP',
        'tls' => [
            'sni' => 'slot-gacor777.top'
        ],
        'alert' => [
            'action' => 'allowed',
            'gid' => 1,
            'signature_id' => 1000004,
            'rev' => 1,
            'signature' => 'SUSPICIOUS TLS DOMAIN - JUDI ONLINE',
            'category' => 'Policy Violation',
            'severity' => 1
        ]
    ]),
    // Event 5: Lalu lintas domain lain (tidak boleh bocor/false positive)
    json_encode([
        'timestamp' => '2026-11-04T08:30:02.000000+0800',
        'event_type' => 'dns',
        'src_ip' => '192.168.1.100',
        'dest_ip' => '8.8.8.8',
        'proto' => 'UDP',
        'dns' => [
            'rrname' => 'google.com'
        ]
    ])
];

file_put_contents($tempEveFile, implode("\n", $sampleEvents));

runTest("Suricata NIDS mendeteksi keberadaan file eve.json aktif", function () use ($tempEveFile) {
    $service = new SuricataIntegrationService($tempEveFile);
    return $service->isAvailable();
}, $passed, $failed);

runTest("Parsing dan ekstraksi event DNS, TLS SNI, dan Alert spesifik target", function () use ($tempEveFile) {
    $service = new SuricataIntegrationService($tempEveFile);
    $result = $service->inspectNetworkEvents('slot-gacor777.top');

    return $result['status'] === 'tersedia'
        && $result['matched'] === true
        && $result['alerts_count'] === 2
        && count($result['dns_events']) === 1
        && count($result['tls_events']) === 1
        && count($result['indicators']) === 2;
}, $passed, $failed);

runTest("Isolasi target: domain bersih (ojk.go.id) tidak tertuduh alert log target lain", function () use ($tempEveFile) {
    $service = new SuricataIntegrationService($tempEveFile);
    $result = $service->inspectNetworkEvents('ojk.go.id');

    return $result['status'] === 'tersedia'
        && $result['matched'] === false
        && $result['alerts_count'] === 0
        && count($result['indicators']) === 0;
}, $passed, $failed);

// 3. Pengujian Ekspor Rule Resmi Suricata
runTest("Ekspor berkas rules resmi Suricata (.rules)", function () use ($testingDir) {
    $service = new SuricataIntegrationService();
    $tempRulesFile = $testingDir . '/test_export_' . time() . '.rules';
    $exported = $service->exportRules($tempRulesFile);

    $hasContent = false;
    if ($exported && file_exists($tempRulesFile)) {
        $content = file_get_contents($tempRulesFile);
        $hasContent = str_contains($content, 'sid:1000001') && str_contains($content, 'SUSPICIOUS DOMAIN - JUDI ONLINE');
        @unlink($tempRulesFile);
    }

    return $exported && $hasContent;
}, $passed, $failed);

// Cleanup
@unlink($tempEveFile);

echo "\nHASIL PENGUJIAN TAHAP 3: {$passed} PASS, {$failed} FAIL\n";
exit($failed > 0 ? 1 : 0);
