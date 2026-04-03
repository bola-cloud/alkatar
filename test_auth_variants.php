<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$srv = app(App\Services\SmartLifeErpService::class);

echo "COMPREHENSIVE AUTH TEST...\n";

$url = "https://smarterp.top/api/v3/taxonomy";

// 1. Fresh login to get a clean session
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

echo "Login successful. Cookies: $cookieString\n";

$variants = [
    'Raw Token' => ['Authorization' => $token],
    'Bearer Token' => ['Authorization' => 'Bearer ' . $token],
    'JWT Token' => ['Authorization' => 'JWT ' . $token],
    'Token Header' => ['Token' => $token],
    'Auth Header + Cookies' => ['Authorization' => $token, 'Cookie' => $cookieString],
    'Bearer + Cookies' => ['Authorization' => 'Bearer ' . $token, 'Cookie' => $cookieString],
];

foreach ($variants as $name => $headers) {
    echo "\nTesting: $name\n";
    $resp = Http::withoutRedirecting()->withHeaders(array_merge($headers, [
        'Accept' => 'application/json',
        'X-Requested-With' => 'XMLHttpRequest',
        'User-Agent' => 'Mozilla/5.0'
    ]))->get($url);
    
    echo "Status: " . $resp->status() . "\n";
    if ($resp->status() == 200 && !str_contains($resp->body(), '<!DOCTYPE html>')) {
        echo "WINNER! Body starts with: " . substr($resp->body(), 0, 50) . "\n";
    } elseif ($resp->status() == 401 || $resp->status() == 403) {
        echo "JSON ERROR: " . $resp->body() . "\n";
    } else {
        echo "FAILED (HTML or Redirect)\n";
    }
}
