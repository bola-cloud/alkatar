<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

echo "Testing WhatsApp API endpoint...\n";

try {
    $response = Http::asForm()->post('https://whatsapi.hispeed.om/api/v1/whatsapp/send_otp', [
        'phone_number' => '96812345678',
        'otp' => '123456',
        'language' => 'en'
    ]);

    echo "HTTP Status: " . $response->status() . "\n";
    echo "Response Body: " . $response->body() . "\n";
    echo "Successful: " . ($response->successful() ? 'YES' : 'NO') . "\n";
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
