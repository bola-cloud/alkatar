<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = app(\App\Services\SmartLifeErpService::class);

echo "Fetching raw get_products_list from v3..." . PHP_EOL;

$params = [
    'limit' => 20,
    'include' => 'combo_items,options,category,combo_products'
];

$resp = $service->request('GET', 'products/get_products_list', $params);

if ($resp && $resp->successful()) {
    $data = $resp->json();
    $products = $data['data'] ?? [];
    
    echo "Total products fetched in this page: " . count($products) . PHP_EOL;
    
    foreach ($products as $p) {
        if (isset($p['type']) && (strtolower($p['type']) == 'combo' || $p['type'] == 'تجميعي')) {
            echo " >>> FOUND COMBO PRODUCT IN LIST: " . $p['name'] . " (ID: " . $p['id'] . ")" . PHP_EOL;
            echo " --- ALL KEYS: " . json_encode(array_keys($p)) . PHP_EOL;
            echo " --- RAW COMBO DATA: " . json_encode($p['combo_items'] ?? $p['combo_products'] ?? $p['items'] ?? 'NOT FOUND') . PHP_EOL;
            
            // Check for any other keys that might contain arrays
            foreach ($p as $k => $v) {
                if (is_array($v) && !empty($v)) {
                    echo " --- Potential Data in Key [$k]: " . json_encode($v) . PHP_EOL;
                }
            }
            break;
        }
    }
} else {
    echo "FAILED to fetch products list!" . PHP_EOL;
}
