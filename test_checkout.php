<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\Api\Whatsapp\WhatsappStoreController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// 1. Find/Create User
$user = User::first() ?: User::factory()->create();
Auth::login($user);

echo "User ID: " . $user->id . "\n";

// 2. Prepare Data
$data = [
    'billing_name' => 'John Doe',
    'Payment_Method' => 'COD',
    'billing_email' => 'john@example.com',
    'order_source' => 'whatsapp',
    'billing_street_address' => 'Test Street',
    'billing_zipcode' => '123456',
    'billing_country' => 'Oman',
    'billing_state' => 13, // Muscat
    'billing_city' => 46,  // Seeb
    'billing_area_id' => 2, // Mawaleh
    'cart_items' => [
        [
            'product_id' => 480,
            'quantity' => 1
        ]
    ]
];

// 3. Create Request
$request = new \App\Http\Requests\StoreOrderRequest($data);
// Set the container and redirector for validation to work
$request->setContainer($app)->setRedirector($app->make(Illuminate\Routing\Redirector::class));

try {
    echo "Running Checkout...\n";
    $controller = new WhatsappStoreController();
    $response = $controller->checkout($request);
    echo "Response Code: " . $response->getStatusCode() . "\n";
    echo "Response Body: " . $response->getContent() . "\n";
} catch (\Illuminate\Validation\ValidationException $e) {
    echo "Validation Failed: " . json_encode($e->errors()) . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
