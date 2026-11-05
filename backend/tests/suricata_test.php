<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = app(\App\Services\AiOrchestratorService::class);

$urls = [
    'https://cuan-trading-vip88.vip/slot-gacor/deposit',
    'https://singapore4d-toto-jackpot.cc/register?ref=agen123',
    'https://ojk.go.id/id/berita-dan-kegiatan/siaran-pers/default.aspx'
];

foreach ($urls as $url) {
    $result = $service->analyzeLink($url);
    echo "URL      : {$url}\n";
    echo "Verdict  : {$result->statusAlert} (Class: {$result->alertClass}, Score: {$result->score})\n";
    echo "Source   : {$result->source}\n";
    echo "Findings : " . count($result->findings) . " poin\n";
    foreach ($result->findings as $f) {
        echo "   • [{$f['type']}] {$f['label']} {$f['detail']}\n";
    }
    echo "------------------------------------------------------------------\n";
}
