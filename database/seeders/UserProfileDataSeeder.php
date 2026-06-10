<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;
use App\Models\Admin\Order;
use App\Models\Admin\OrderDetails;
use App\Models\ProductReview;
use App\Models\Admin\Product;
use Illuminate\Support\Str;

class UserProfileDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'user1@gmail.com')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'William Smith',
                'email' => 'user1@gmail.com',
                'Number' => '96812345678',
                'is_admin' => 0,
                'password' => bcrypt('123456'),
            ]);
        }

        // 1. Seed Addresses
        if ($user->addresses()->count() === 0) {
            Address::create([
                'user_id' => $user->id,
                'label' => 'Home',
                'recipient_name' => $user->name,
                'phone' => $user->Number ?? '96812345678',
                'address_line1' => '123 Al-Maha Street',
                'address_line2' => json_encode([
                    'building' => 'Building 45',
                    'apartment' => 'Apt 302',
                    'notes' => 'Ring the bell twice, drop at door',
                    'type' => 'home'
                ]),
                'city' => 'Muscat',
                'state' => 'Muscat Governorate',
                'country' => 'Oman',
                'is_default' => true,
                'address_type' => 'both'
            ]);

            Address::create([
                'user_id' => $user->id,
                'label' => 'Office',
                'recipient_name' => $user->name,
                'phone' => $user->Number ?? '96812345678',
                'address_line1' => 'Knowledge Oasis Muscat',
                'address_line2' => json_encode([
                    'building' => 'KOM 4',
                    'apartment' => 'Floor 2, Office 204',
                    'notes' => 'Deliver to reception desk',
                    'type' => 'work'
                ]),
                'city' => 'Muscat',
                'state' => 'Muscat Governorate',
                'country' => 'Oman',
                'is_default' => false,
                'address_type' => 'both'
            ]);
        }

        // Find products to associate with orders/reviews
        $products = Product::take(2)->get();
        if ($products->isEmpty()) {
            // Create fallback products if none exist
            $prod1 = Product::create([
                'en_Product_Name' => 'Premium El Katar Blend',
                'fr_Product_Name' => 'بن القطار الفاخر',
                'en_Product_Slug' => 'premium-el-katar-blend',
                'Price' => 15.00,
                'Status' => 1,
            ]);
            $prod2 = Product::create([
                'en_Product_Name' => 'Colombia Special Roast',
                'fr_Product_Name' => 'قهوة مختصة كولومبيا',
                'en_Product_Slug' => 'colombia-special-roast',
                'Price' => 12.50,
                'Status' => 1,
            ]);
            $products = collect([$prod1, $prod2]);
        }

        // 2. Seed Orders & OrderDetails
        if (Order::where('User_Id', $user->id)->count() === 0) {
            // Order 1 (Delivered)
            $order1 = Order::create([
                'Order_Number' => 'ORD-' . strtoupper(Str::random(6)),
                'User_Id' => $user->id,
                'billing_address' => json_encode([
                    'street' => '123 Al-Maha Street',
                    'city' => 'Muscat',
                    'state' => 'Muscat Governorate',
                ]),
                'shipping_address' => json_encode([
                    'street' => '123 Al-Maha Street',
                    'city' => 'Muscat',
                    'state' => 'Muscat Governorate',
                ]),
                'Sub_Total' => 27.50,
                'Tax' => 0.00,
                'Grand_Total' => 30.00,
                'Payment_Method' => 'Card',
                'Payment_Status' => 1, // PAYMENT_SUCCESS
                'Order_Status' => 4, // ORDER_DELIVERED
                'collection_method' => 'delivery',
                'Is_Order_Successful' => true,
                'Is_Order_Completed' => true
            ]);

            OrderDetails::create([
                'Order_Id' => $order1->id,
                'Product_Id' => $products->first()->id,
                'Product_Name' => $products->first()->en_Product_Name,
                'Size' => '250g',
                'Color' => 'Default',
                'Price' => $products->first()->Price,
                'Quantity' => 1,
                'Total_Price' => $products->first()->Price
            ]);

            if ($products->count() > 1) {
                OrderDetails::create([
                    'Order_Id' => $order1->id,
                    'Product_Id' => $products->last()->id,
                    'Product_Name' => $products->last()->en_Product_Name,
                    'Size' => '250g',
                    'Color' => 'Default',
                    'Price' => $products->last()->Price,
                    'Quantity' => 1,
                    'Total_Price' => $products->last()->Price
                ]);
            }

            // Order 2 (Processing)
            $order2 = Order::create([
                'Order_Number' => 'ORD-' . strtoupper(Str::random(6)),
                'User_Id' => $user->id,
                'billing_address' => json_encode([
                    'street' => 'Knowledge Oasis Muscat',
                    'city' => 'Muscat',
                    'state' => 'Muscat Governorate',
                ]),
                'shipping_address' => json_encode([
                    'street' => 'Knowledge Oasis Muscat',
                    'city' => 'Muscat',
                    'state' => 'Muscat Governorate',
                ]),
                'Sub_Total' => 15.00,
                'Tax' => 0.00,
                'Grand_Total' => 15.00,
                'Payment_Method' => 'Cash',
                'Payment_Status' => 5, // PAYMENT_PENDING
                'Order_Status' => 2, // ORDER_PROCESSING
                'collection_method' => 'pickup',
                'Is_Order_Successful' => true,
                'Is_Order_Completed' => false
            ]);

            OrderDetails::create([
                'Order_Id' => $order2->id,
                'Product_Id' => $products->first()->id,
                'Product_Name' => $products->first()->en_Product_Name,
                'Size' => '250g',
                'Color' => 'Default',
                'Price' => $products->first()->Price,
                'Quantity' => 1,
                'Total_Price' => $products->first()->Price
            ]);
        }

        // 3. Seed Product Reviews
        if (ProductReview::where('user_id', $user->id)->count() === 0) {
            ProductReview::create([
                'rating' => 5,
                'feedback' => 'Incredible aroma and very balanced taste. Will buy again!',
                'product_id' => $products->first()->id,
                'user_id' => $user->id,
                'is_visible' => true
            ]);
        }
    }
}
