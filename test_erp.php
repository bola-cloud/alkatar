<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$srv = app(App\Services\SmartLifeErpService::class);

echo "Testing SESSION SERIALIZATION...\n";

// 1. Fresh login
$session1 = $srv->refreshSessionAtomically();
echo "Login 1: sess=" . ($session1['cookies']['sess'] ?? 'miss') . "\n";

// 2. Perform another login (Simulate concurrent process)
$session2 = $srv->refreshSessionAtomically();
echo "Login 2: sess=" . ($session2['cookies']['sess'] ?? 'miss') . "\n";

// 3. Try to use Session 1 again
$url = "https://smarterp.top/api/v3/taxonomy";
$cookie1 = collect($session1['cookies'])->map(fn($v, $k) => "$k=$v")->join('; ');

$resp = Http::withoutRedirecting()->withHeaders([
    'Authorization' => $session1['token'],
    'Cookie' => $cookie1,
    'Accept' => 'application/json'
])->get($url);

echo "Request using Session 1 AFTER Session 2 login: Status " . $resp->status() . "\n";
if ($resp->status() == 303 || $resp->status() == 200 && str_contains($resp->body(), '<!DOCTYPE html>')) {
    echo "CONFIRMED: New login killed the old session!\n";
} else {
    echo "Session was NOT killed. Body preview: " . substr($resp->body(), 0, 50) . "\n";
}
