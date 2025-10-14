<?php

namespace Database\Seeders;

use App\Models\Admin\Order;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        if (Order::query()->count() > 0) {
            // Skip if real data exists
            return;
        }

        // Ensure at least one user exists
        $user = User::query()->first();
        if (!$user) {
            $user = User::factory()->create([
                'email' => 'demo@example.com',
                'name' => 'Demo User',
                'password' => bcrypt('password'),
            ]);
        }

        // Create sample orders
        for ($i = 1; $i <= 30; $i++) {
            $subTotal = random_int(1000, 10000) / 100; // 10.00 - 100.00
            $delivery = [0, 1][array_rand([0,1])] ? random_int(300, 800) / 100 : 0; // 0 or 3.00 - 8.00
            $coupon = [0,1][array_rand([0,1])] ? random_int(100, 500) / 100 : 0;
            $grand = max(0, $subTotal - $coupon + $delivery);

            Order::query()->create([
                'Order_Number' => 'ORD-' . Str::upper(Str::random(8)),
                'User_Id' => $user->id,
                'Coupon_Amount' => $coupon,
                'Delivery_Charge' => $delivery,
                'Sub_Total' => $subTotal,
                'Tax' => 0,
                'Grand_Total' => $grand,
                'Is_Free_Delivery' => $delivery == 0 ? 1 : 0,
                'Is_Order_Successful' => 1,
                'Is_Order_Completed' => 0,
                'Payment_Method' => 'COD',
                'Payment_Status' => 1,
                'Order_Status' => [ORDER_PENDING, ORDER_PROCESSING, ORDER_SHIPPED, ORDER_DELIVERED][array_rand([1,2,3,4])],
                'Delivery_At' => now()->copy()->addDays(random_int(0,7)),
                'txn' => null,
                'order_source' => 'seed',
                'billing_address' => json_encode(['name' => $user->name, 'phone_number' => $user->Number ?? '']),
                'shipping_address' => json_encode(['name' => $user->name, 'phone_number' => $user->Number ?? '']),
                'admin_id' => null,
                'created_at' => now()->copy()->subDays(random_int(0, 40))->setTime(random_int(8,18), random_int(0,59)),
                'updated_at' => now(),
            ]);
        }
    }
}
