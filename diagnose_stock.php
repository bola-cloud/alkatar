<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Admin\Product;
use Illuminate\Support\Facades\DB;

$product = Product::with('comboItems')->find(297);

echo "=== PRODUCT DIAGNOSIS: ID 297 ===\n";
echo "Name (EN): {$product->en_Product_Name}\n";
echo "Name (AR): {$product->fr_Product_Name}\n";
echo "SmartLife ID: {$product->smartlife_id}\n";
echo "Barcode: {$product->barcode}\n";
echo "Product Type: '{$product->product_type}'\n";
echo "DB Quantity (raw): {$product->Quantity}\n";
echo "Status: " . ($product->Status ? 'Active' : 'Inactive') . "\n";

echo "\n--- Combo Items from Relationship ---\n";
echo "comboItems count: " . $product->comboItems->count() . "\n";

if ($product->comboItems->isNotEmpty()) {
    foreach ($product->comboItems as $item) {
        echo "  Component ID={$item->id} | Name={$item->en_Product_Name} | Type={$item->product_type} | DB Qty={$item->Quantity} | Pivot Qty={$item->pivot->quantity} | Virtual Stock={$item->virtual_stock}\n";
    }
} else {
    echo "  >>> NO COMBO ITEMS LINKED IN product_combos TABLE! <<<\n";
}

echo "\n--- Checking product_combos Table Directly ---\n";
$combos = DB::table('product_combos')->where('product_id', 297)->get();
echo "Rows in product_combos for product_id=297: " . $combos->count() . "\n";
foreach ($combos as $row) {
    echo "  product_id={$row->product_id} | combo_product_id={$row->combo_product_id} | quantity={$row->quantity}\n";
}

// Also check reverse (maybe linked wrong direction)
$reverseCombos = DB::table('product_combos')->where('combo_product_id', 297)->get();
echo "\nRows in product_combos for combo_product_id=297: " . $reverseCombos->count() . "\n";
foreach ($reverseCombos as $row) {
    echo "  product_id={$row->product_id} | combo_product_id={$row->combo_product_id} | quantity={$row->quantity}\n";
}

echo "\n--- Virtual Stock Calculation Trace ---\n";
$type = trim($product->product_type);
echo "1. Product type: '{$type}'\n";
$isCombo = ($type === 'Combo' || $type === 'تجميعي' || $type === 'combo');
echo "2. Is identified as Combo? " . ($isCombo ? 'YES' : 'NO') . "\n";

if ($isCombo && $product->comboItems->isEmpty()) {
    echo "3. Combo but NO ITEMS linked -> Falls back to DB Quantity = {$product->Quantity}\n";
    echo "4. Since Quantity = {$product->Quantity}, virtual_stock = {$product->Quantity}\n";
}

echo "\n--- FINAL RESULT ---\n";
echo "Virtual Stock: {$product->virtual_stock}\n";
echo "Would show 'Out of stock'? " . ($product->virtual_stock <= 0 ? 'YES ❌' : 'NO ✅') . "\n";

// Check what ID 296 looks like (the standard capsicum green 500g)
echo "\n\n=== COMPARISON: Product 296 (Standard capsicum) ===\n";
$standard = Product::find(296);
echo "Name: {$standard->en_Product_Name}\n";
echo "Type: {$standard->product_type}\n";
echo "DB Qty: {$standard->Quantity}\n";
echo "Virtual Stock: {$standard->virtual_stock}\n";

echo "\n\n=== The Logic Chain ===\n";
echo "Product 297 (فليفلة اخضر ٢.٥كج) is a COMBO with type='{$product->product_type}'\n";
echo "It has {$product->comboItems->count()} combo items linked\n";
echo "It has DB Quantity of {$product->Quantity}\n";
echo "So virtual_stock = {$product->virtual_stock}\n";
echo "\nROOT CAUSE: ";
if ($product->comboItems->isEmpty() && $product->Quantity <= 0) {
    echo "Combo has NO linked components AND DB Quantity is 0. The Sync never linked the component products.\n";
    echo "EXPECTED: Product 297 should be composed of 5x Product 296 (فليفلة عمان اخضر ٥٠٠ج), giving virtual_stock = floor(29/5) = 5\n";
} elseif ($product->comboItems->isEmpty() && $product->Quantity > 0) {
    echo "Combo has no linked components but has Quantity {$product->Quantity}, so it falls back to that.\n";
} else {
    echo "Need more investigation.\n";
}
