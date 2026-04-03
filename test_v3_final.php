<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$srv = app(App\Services\SmartLifeErpService::class);

echo "Final Verification of Corrected v3 Endpoints...\n";

$reflection = new \ReflectionMethod($srv, 'request');
$reflection->setAccessible(true);

// Test sales_list (GET)
echo "\n--- Testing endpoint: sales/sales_list ---\n";
$resp = $reflection->invoke($srv, 'GET', 'sales/sales_list');

if ($resp && $resp->status() == 200 && !str_contains($resp->body(), '<!DOCTYPE html>')) {
    echo "SUCCESS: ERP API returned JSON!\n";
    echo substr($resp->body(), 0, 100) . "...\n";
} else {
    echo "FAIL: Still getting HTML or Error. Status: " . ($resp ? $resp->status() : 'null') . "\n";
    if ($resp) echo substr($resp->body(), 0, 200) . "\n";
}

// Test add_payments simulation (POST)
echo "\n--- Testing add_payments simulation (Invoice 139) ---\n";
$result = $srv->addPayment(139, 17.39, 'Card', 'Final fix verification');
var_dump($result);
