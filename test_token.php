<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Admin\Order;

$user = User::has('orders')->latest()->first();

if ($user) {
    $token = $user->createToken('test-token')->plainTextToken;
    $order = Order::where('User_Id', $user->id)->latest()->first();
    echo "USER_PHONE: " . $user->Number . "\n";
    echo "TOKEN: " . $token . "\n";
    echo "ORDER_ID: " . $order->id . "\n";
} else {
    echo "ERROR: No user with orders found.\n";
}
