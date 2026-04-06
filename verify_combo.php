<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = app(\App\Services\SmartLifeErpService::class);
$id = 153;
echo "Performing LIVE API check for ID $id..." . PHP_EOL;
$resp = $service->request('GET', "products/product/$id");
if ($resp && $resp->successful()) {
    $data = $resp->json();
    $product = $data['data'] ?? [];
    echo " - API Product Name: " . ($product['name'] ?? 'N/A') . PHP_EOL;
    echo " - API Product Type: " . ($product['type'] ?? 'N/A') . PHP_EOL;
    echo " - ALL RAW KEYS: " . json_encode(array_keys($product)) . PHP_EOL;
    echo " - OPTIONS CONTENT: " . json_encode($product['options'] ?? []) . PHP_EOL;
    
    // Check for nested combo items anywhere
    foreach ($product as $key => $val) {
        if (str_contains($key, 'combo') || str_contains($key, 'item')) {
            echo " >>> FOUND POTENTIAL KEY: $key = " . json_encode($val) . PHP_EOL;
        }
    }
} else {
    echo " - LIVE API CALL FAILED!" . PHP_EOL;
}

echo "-------------------" . PHP_EOL;

$ids = [153, 231, 234, 236, 238];
foreach ($ids as $id) {
    echo "Checking ID: $id" . PHP_EOL;
    $slp = \App\Models\SmartLifeProduct::where('smartlife_id', $id)->first();
    if ($slp) {
        echo " - SmartLife shadow record found: " . $slp->name . PHP_EOL;
        echo " - Raw combo_items field: " . json_encode($slp->combo_items) . PHP_EOL;
        echo " - Shadow record type: " . $slp->type . PHP_EOL;
    } else {
        echo " - SmartLife shadow record NOT FOUND!" . PHP_EOL;
    }
    
    $p = \App\Models\Admin\Product::where('smartlife_id', (string)$id)->first();
    if ($p) {
        echo " - Local product found: " . $p->en_Product_Name . PHP_EOL;
        echo " - Local product type: " . $p->product_type . PHP_EOL;
    }
    echo "-------------------" . PHP_EOL;
}
