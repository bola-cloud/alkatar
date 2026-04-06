<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = app(\App\Services\SmartLifeErpService::class);
$id = 153; // رمان هندي كرتون (Combo)

$endpoints = [
    'products/product/' . $id,
    'products/get_combo_items/' . $id,
    'products/get_combo_products/' . $id,
    'products/combo/' . $id,
    'products/combo_items/' . $id,
    'products/view_combo/' . $id,
    'products/product_details/' . $id,
    'stocks/get_combo_items/' . $id,
    'sales/get_combo_items/' . $id,
    'products/get_products_details/' . $id,
];

echo "Brute-forcing Combo Items for ID $id..." . PHP_EOL;

foreach ($endpoints as $ep) {
    try {
        echo "Testing: $ep ... ";
        $resp = $service->request('GET', $ep);
        if ($resp && $resp->successful()) {
            $body = $resp->body();
            $data = $resp->json();
            
            if ($data === null) {
                echo "SUCCESS! (But not JSON) Raw Body Preview: " . substr($body, 0, 200) . PHP_EOL;
                continue;
            }

            echo "SUCCESS! Keys: " . json_encode(array_keys($data)) . PHP_EOL;
            
            $mainData = $data['data'] ?? $data;
            if (is_array($mainData)) {
                echo " >>> Main Keys: " . json_encode(array_keys($mainData)) . PHP_EOL;
            }

            // Check for clues in data
            if (!empty($mainData['combo_items']) || !empty($mainData['combo_products']) || !empty($mainData['items'])) {
                echo " >>> FOUND COMBO ITEMS IN RESPONSE! <<<" . PHP_EOL;
                echo json_encode($data, JSON_PRETTY_PRINT) . PHP_EOL;
                break;
            }
        } else {
            echo "FAILED (" . ($resp ? $resp->status() : 'No Response') . ")" . PHP_EOL;
        }
    } catch (\Exception $e) {
        echo "ERROR: " . $e->getMessage() . PHP_EOL;
    }
}
