<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = app(\App\Services\SmartLifeErpService::class);
$token = $service->getAccessToken();
$baseUrl = 'https://smarterp.top';

$endpoints = [
    '/connector/api/taxonomy',
    '/api/v1/taxonomy',
    '/api/taxonomy',
    '/api/categories',
    '/connector/api/categories'
];
foreach($endpoints as $ep) {
    echo "Testing $ep\n";
    $res = \Illuminate\Support\Facades\Http::withHeaders([
        'Authorization' => $token,
        'Accept' => 'application/json'
    ])->get($baseUrl . $ep . '?type=product');
    echo "Status: " . $res->status() . "\n";
    echo "Body: " . substr($res->body(), 0, 500) . "\n\n";
}
