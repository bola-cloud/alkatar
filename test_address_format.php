<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$order = \App\Models\Admin\Order::find(59);
if (!$order) {
    echo "Order not found\n";
    exit;
}

echo "Order ID: " . $order->id . "\n";
echo "Billing Address Type: " . gettype($order->billing_address) . "\n";
echo "Billing Address Content: " . json_encode($order->billing_address) . "\n";
echo "Formatted Billing: " . $order->formatted_billing_address . "\n";
