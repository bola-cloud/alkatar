<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Services\SmartLifeErpService;
use Illuminate\Support\Facades\Http;

$service = app(SmartLifeErpService::class);
$id = 153;

echo "--- Testing V2 endpoint with V3 Authentication ---\n";
// Manually construct the V2 URL but use the service's request method to handle headers/cookies
$resp = $service->request('GET', 'https://smarterp.top/api/v2/products/product/' . $id);

if ($resp && $resp->successful()) {
    $data = $resp->json();
    echo "SUCCESS! V2 returned data.\n";
    echo "Keys: " . json_encode(array_keys($data['data'] ?? [])) . "\n";
    if (isset($data['data']['combo_items'])) {
        echo ">>> FOUND COMBO ITEMS IN V2 RESPONSE! <<<\n";
        echo "Count: " . count($data['data']['combo_items']) . "\n";
        print_r($data['data']['combo_items']);
    } else {
        echo "V2 also does not have combo_items key.\n";
    }
} else {
    echo "FAILED! V2 returned status: " . ($resp ? $resp->status() : 'No response') . "\n";
    echo "Body: " . ($resp ? substr($resp->body(), 0, 200) : '') . "...\n";
}
