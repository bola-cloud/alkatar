<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$srv = app(App\Services\SmartLifeErpService::class);

echo "Testing SYNCED requests with different endpoints...\n";

$reflection = new \ReflectionMethod($srv, 'request');
$reflection->setAccessible(true);

$endpoints = ['products', 'taxonomy', 'sales'];

foreach ($endpoints as $ep) {
    echo "\n--- Testing endpoint: $ep ---\n";
    $resp = $reflection->invoke($srv, 'GET', $ep);
    if ($resp) {
        echo "Status: " . $resp->status() . "\n";
        $body = $resp->body();
        if (str_contains($body, '<!DOCTYPE html>')) {
            echo "RESULT: HTML REDIRECT (Fail)\n";
        } else {
            echo "RESULT: SUCCESS (JSON)\n";
            echo substr($body, 0, 100) . "...\n";
        }
    } else {
        echo "RESULT: NULL\n";
    }
}
