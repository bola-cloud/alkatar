<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Admin\Order;

function testMatch($label, $input, $stored) {
    $normInput = Order::normalizePhone($input);
    $normStored = Order::normalizePhone($stored);
    $match = ($normInput === $normStored);
    echo "[$label] Input: $input | Stored: $stored | Match: " . ($match ? "YES" : "NO") . " (Suffixes: $normInput vs $normStored)\n";
}

echo "Testing Phone Group Match Criteria (Oman/General):\n";
testMatch("Standard match", "98765432", "98765432");
testMatch("Prefix match", "0096898765432", "98765432");
testMatch("Plus prefix match", "+96898765432", "98765432");
testMatch("Input with zero match", "01118232384", "18232384");
testMatch("Stored with plus match", "91111111", "+96891111111");
testMatch("Mismatch case", "91111111", "92222222");
