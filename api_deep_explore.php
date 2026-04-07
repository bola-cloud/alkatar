<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Services\SmartLifeErpService;

$service = app(SmartLifeErpService::class);

echo "=== PRICE-BASED QUANTITY INFERENCE TEST ===\n\n";

// Get all products from API
$resp = $service->request('GET', 'products/get_products_list', ['offset' => 0, 'limit' => 500]);
$allProducts = $resp->json()['data'] ?? [];

$standards = array_filter($allProducts, fn($p) => strtolower($p['type'] ?? '') !== 'combo' && ($p['type'] ?? '') !== 'تجميعي');
$combos = array_filter($allProducts, fn($p) => strtolower($p['type'] ?? '') === 'combo' || ($p['type'] ?? '') === 'تجميعي');

echo "Standards: " . count($standards) . " | Combos: " . count($combos) . "\n\n";

// For each combo, find the best standard match and infer quantity from price
foreach ($combos as $combo) {
    $comboName = $combo['name'];
    $comboPrice = (float)$combo['price'];
    
    // Find best matching standard product by name similarity
    $bestMatch = null;
    $bestScore = 0;
    
    foreach ($standards as $std) {
        $stdName = $std['name'];
        $stdPrice = (float)$std['price'];
        
        if ($stdPrice <= 0) continue;
        
        // Calculate name similarity
        $comboWords = preg_split('/\s+/u', $comboName);
        $stdWords = preg_split('/\s+/u', $stdName);
        $commonWords = 0;
        foreach ($comboWords as $w) {
            if (in_array($w, $stdWords)) $commonWords++;
        }
        
        // Need at least 2 common words
        if ($commonWords < 2) continue;
        
        // Score based on common words ratio
        $score = $commonWords / max(count($comboWords), count($stdWords));
        
        // Bonus: if the standard product is in a consecutive ID (likely related)
        if (abs($combo['id'] - $std['id']) <= 2) {
            $score += 0.3;
        }
        
        if ($score > $bestScore) {
            $bestScore = $score;
            $bestMatch = $std;
        }
    }
    
    if ($bestMatch) {
        $stdPrice = (float)$bestMatch['price'];
        
        // Infer quantity: combo_price / standard_price → rounded to nearest integer
        $inferredQty = round($comboPrice / $stdPrice);
        
        // Validate: inferred price should be close to combo price
        $inferredTotal = $inferredQty * $stdPrice;
        $priceDiff = abs($inferredTotal - $comboPrice);
        $priceMatch = $priceDiff < 0.5; // Allow small rounding differences
        
        $status = $priceMatch ? '✅' : '⚠️';
        
        echo "{$status} COMBO [{$combo['id']}] {$comboName} (price: {$comboPrice})\n";
        echo "   → STD [{$bestMatch['id']}] {$bestMatch['name']} (price: {$stdPrice})\n";
        echo "   → Inferred Qty: {$inferredQty} × {$stdPrice} = {$inferredTotal} (diff: {$priceDiff})\n";
        echo "   → API Stock: std={$bestMatch['quantity']}, possible combos=" . floor($bestMatch['quantity'] / max(1, $inferredQty)) . "\n\n";
    } else {
        echo "❌ COMBO [{$combo['id']}] {$comboName} - NO MATCH FOUND\n\n";
    }
}
