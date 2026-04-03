<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$srv = app(App\Services\SmartLifeErpService::class);

echo "TESTING Form-URL-Encoded (asForm) for add_payment...\n";

// 1. Fresh login
$jar = new \GuzzleHttp\Cookie\CookieJar();
$client = new \GuzzleHttp\Client(['cookies' => $jar, 'verify' => false]);
$creds = config('smartlife.credentials');

$loginResp = $client->post("https://smarterp.top/api/v3/user/login", [
    'form_params' => $creds,
    'headers' => ['Accept' => 'application/json']
]);

$loginData = json_decode((string)$loginResp->getBody(), true);
$token = $loginData['access_token'] ?? null;
$cookies = [];
foreach ($jar->toArray() as $cookie) {
    $cookies[$cookie['Name']] = $cookie['Value'];
}
$cookieString = collect($cookies)->map(fn($v, $k) => "$k=$v")->join('; ');

$url = "https://smarterp.top/api/v3/sales/add_payment";
$payload = [
    'sale_id' => '139',
    'sell_id' => '139',
    'transaction_id' => '139',
    'amount' => 17.39,
    'account_id' => '1020100001',
    'paid_by' => 'Card',
    'method' => 'card',
    'payment_status' => 'Paid',
];

echo "Sending asForm() request with raw token and cookies...\n";

$resp = Http::withoutRedirecting()
->asForm() // CRITICAL TEST
->withHeaders([
    'Authorization' => $token,
    'Cookie' => $cookieString,
    'Accept' => 'application/json',
    'X-Requested-With' => 'XMLHttpRequest'
])->post($url, $payload);

echo "Status: " . $resp->status() . "\n";
echo "Body starts with: " . substr($resp->body(), 0, 500) . "\n";
