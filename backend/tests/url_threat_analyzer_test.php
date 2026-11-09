<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\UrlThreatAnalyzer;

echo "=== UNIT TEST: URL THREAT ANALYZER (DETEKSI 3 TEKNIK PENYUSUPAN JUDOL) ===\n\n";

$analyzer = new UrlThreatAnalyzer();
$testsPassed = 0;
$totalTests = 0;

function runTest(string $testName, bool $condition, string $detail = '') {
    global $testsPassed, $totalTests;
    $totalTests++;
    if ($condition) {
        $testsPassed++;
        echo "✅ PASS: {$testName}\n";
    } else {
        echo "❌ FAIL: {$testName} -> {$detail}\n";
    }
}

// -----------------------------------------------------------------------------
// Test Case 1: Web Defacement pada Domain .go.id (CMS Upload Folder Disusupi Judol)
// -----------------------------------------------------------------------------
$urlDefacement = 'https://pemda-contoh.go.id/wp-content/uploads/2026/05/slot-gacor/';
$resDefacement = $analyzer->analyze($urlDefacement);

$hasDefacementTechnique = in_array('web_defacement', $resDefacement['techniques'], true);
$hasDefacementIndicator = false;
foreach ($resDefacement['indicators'] as $ind) {
    if ($ind['name'] === 'defacement_path') {
        $hasDefacementIndicator = true;
        break;
    }
}
runTest(
    "1. Web Defacement Path pada domain .go.id terdeteksi",
    $hasDefacementTechnique && $hasDefacementIndicator && $resDefacement['threat_score'] >= 70,
    "Techniques: " . json_encode($resDefacement['techniques']) . ", Score: {$resDefacement['threat_score']}"
);

// -----------------------------------------------------------------------------
// Test Case 2: Subdomain Hijacking pada Portal .desa.id
// -----------------------------------------------------------------------------
$urlSubdomain = 'https://slot88.desa-contoh.desa.id/register';
$resSubdomain = $analyzer->analyze($urlSubdomain);

$hasSubdomainIndicator = false;
foreach ($resSubdomain['indicators'] as $ind) {
    if ($ind['name'] === 'subdomain_asing') {
        $hasSubdomainIndicator = true;
        break;
    }
}
runTest(
    "2. Subdomain Hijacking bertema judol pada domain .desa.id terdeteksi",
    in_array('web_defacement', $resSubdomain['techniques'], true) && $hasSubdomainIndicator,
    "Techniques: " . json_encode($resSubdomain['techniques'])
);

// -----------------------------------------------------------------------------
// Test Case 3: Open Redirect dengan URL Absolut & Encoding Berlapis
// -----------------------------------------------------------------------------
$urlOpenRedirect = 'https://portal-resmi.ac.id/redirect?url=https%3A%2F%2Fjudi-slot-online.vip%2Fdeposit';
$resOpenRedirect = $analyzer->analyze($urlOpenRedirect);

$hasOpenRedirectTechnique = in_array('open_redirect', $resOpenRedirect['techniques'], true);
$hasOpenRedirectParam = false;
$hasDuaHost = false;
$hasJudolDest = false;
foreach ($resOpenRedirect['indicators'] as $ind) {
    if ($ind['name'] === 'open_redirect_param') $hasOpenRedirectParam = true;
    if ($ind['name'] === 'dua_host_dalam_url') $hasDuaHost = true;
    if ($ind['name'] === 'open_redirect_judol_dest') $hasJudolDest = true;
}
runTest(
    "3. Open Redirect mengarah ke target judol (.vip) terdeteksi dengan kombinasi skor tinggi",
    $hasOpenRedirectTechnique && $hasOpenRedirectParam && $hasDuaHost && $resOpenRedirect['threat_score'] >= 80,
    "Techniques: " . json_encode($resOpenRedirect['techniques']) . ", Score: {$resOpenRedirect['threat_score']}"
);

// -----------------------------------------------------------------------------
// Test Case 4: Typosquatting Lembaga Resmi (OJK) pada Domain Komersial
// -----------------------------------------------------------------------------
$urlTyposquatting = 'https://ojk-resmi-bantuan.com/login';
$resTyposquatting = $analyzer->analyze($urlTyposquatting);

$hasTyposquattingTechnique = in_array('typosquatting', $resTyposquatting['techniques'], true);
$hasTyposquattingIndicator = false;
$hasNonGovTld = false;
foreach ($resTyposquatting['indicators'] as $ind) {
    if ($ind['name'] === 'typosquatting_mirip_instansi') $hasTyposquattingIndicator = true;
    if ($ind['name'] === 'tld_bukan_go_id') $hasNonGovTld = true;
}
runTest(
    "4. Typosquatting nama instansi resmi di domain komersial (.com) terdeteksi",
    $hasTyposquattingTechnique && $hasTyposquattingIndicator && $hasNonGovTld && $resTyposquatting['threat_score'] >= 75,
    "Techniques: " . json_encode($resTyposquatting['techniques']) . ", Score: {$resTyposquatting['threat_score']}"
);

// -----------------------------------------------------------------------------
// Test Case 5: URL Resmi .go.id Normal (Mencegah False Positive)
// -----------------------------------------------------------------------------
$urlOfficialGov = 'https://ojk.go.id/id/berita-dan-kegiatan/siaran-pers/default.aspx';
$resOfficialGov = $analyzer->analyze($urlOfficialGov);

$isGovClean = empty($resOfficialGov['indicators']) &&
              empty($resOfficialGov['techniques']) &&
              $resOfficialGov['threat_score'] === 0 &&
              !$resOfficialGov['has_threat'];

runTest(
    "5. Portal resmi OJK (ojk.go.id) normal TIDAK DITANDAI (False Positive = 0)",
    $isGovClean,
    "Indicators count: " . count($resOfficialGov['indicators']) . ", Score: {$resOfficialGov['threat_score']}"
);

// -----------------------------------------------------------------------------
// Test Case 6: URL Universitas .ac.id Normal (Mencegah False Positive)
// -----------------------------------------------------------------------------
$urlOfficialUniv = 'https://ugm.ac.id/id/akademik/informasi-kuliah/';
$resOfficialUniv = $analyzer->analyze($urlOfficialUniv);

$isUnivClean = empty($resOfficialUniv['indicators']) &&
               empty($resOfficialUniv['techniques']) &&
               $resOfficialUniv['threat_score'] === 0 &&
               !$resOfficialUniv['has_threat'];

runTest(
    "6. Portal resmi perguruan tinggi (ugm.ac.id) normal TIDAK DITANDAI (False Positive = 0)",
    $isUnivClean,
    "Indicators count: " . count($resOfficialUniv['indicators']) . ", Score: {$resOfficialUniv['threat_score']}"
);

// -----------------------------------------------------------------------------
// Test Case 7: Modus Kamuflase Finansial (Trading/Investasi Berkedok Judol)
// -----------------------------------------------------------------------------
$urlKamuflase = 'https://cuan-trading-harian-vip88.vip/deposit-slot';
$resKamuflase = $analyzer->analyze($urlKamuflase);

$hasKamuflaseTech = in_array('kamuflase_investasi', $resKamuflase['techniques'], true);
$hasNoDefacement = !in_array('web_defacement', $resKamuflase['techniques'], true);

runTest(
    "7. Modus Kamuflase Investasi berkedok Judol terdeteksi 'kamuflase_investasi' dan BUKAN 'web_defacement'",
    $hasKamuflaseTech && $hasNoDefacement && $resKamuflase['threat_score'] >= 80,
    "Techniques: " . json_encode($resKamuflase['techniques']) . ", Score: {$resKamuflase['threat_score']}"
);

// -----------------------------------------------------------------------------
// Test Case 8: Deteksi URL Masking & Domain Rebouncer (share.google -> olymp trade)
// -----------------------------------------------------------------------------
$urlMasked = 'https://share.google/dZPBsDvJTBpwcuDLo';
$resMasked = $analyzer->analyze($urlMasked);

$hasMaskingTech = in_array('url_masking', $resMasked['techniques'], true);
$hasRebouncerTech = in_array('domain_rebouncer', $resMasked['techniques'], true);
$hasOpenRedirectTech = in_array('open_redirect', $resMasked['techniques'], true);
$hasInvestTech = in_array('kamuflase_investasi', $resMasked['techniques'], true);
$isUnmaskedTargetExposed = !empty($resMasked['unmasked_url']) && str_contains($resMasked['unmasked_url'], 'olymptrade.com');

runTest(
    "8. URL Masking (share.google) & Domain Rebouncer ke situs trading ilegal terdeteksi dengan skor tinggi",
    $hasMaskingTech && $hasRebouncerTech && $hasOpenRedirectTech && $hasInvestTech && $isUnmaskedTargetExposed && $resMasked['threat_score'] >= 80,
    "Techniques: " . json_encode($resMasked['techniques']) . ", Target: {$resMasked['unmasked_url']}, Score: {$resMasked['threat_score']}"
);

echo "\n" . str_repeat("=", 60) . "\n";
echo "HASIL PENGUJIAN THREAT ANALYZER: {$testsPassed} DARI {$totalTests} PENGUJIAN LULUS (" . round(($testsPassed / $totalTests) * 100) . "%)\n";
echo str_repeat("=", 60) . "\n";

exit($testsPassed === $totalTests ? 0 : 1);

