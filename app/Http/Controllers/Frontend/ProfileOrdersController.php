<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use App\Models\Admin\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

class ProfileOrdersController extends Controller
{
    /**
     * Retrieve the user's order history.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $orders = Order::with(['order_details', 'order_details.product'])
            ->where('User_Id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }

    /**
     * Reorder items from a previous order.
     */
    public function reorder(Request $request, $orderNumber)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => __('Please login first')], 401);
        }

        $order = Order::with('order_details', 'order_details.product')
            ->where('Order_Number', $orderNumber)
            ->where('User_Id', $user->id)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => __('Order not found')], 404);
        }

        $addedCount = 0;
        foreach ($order->order_details as $detail) {
            $product = $detail->product;
            if (!$product || $product->Status != 1) {
                continue; // Skip products that don't exist or are inactive
            }

            // Check if exact same item already in cart
            $alreadyInCart = false;
            foreach (Cart::content() as $cartItem) {
                if ($cartItem->id == $product->id) {
                    $alreadyInCart = true;
                    // Increase quantity in cart
                    Cart::update($cartItem->rowId, $cartItem->qty + $detail->Quantity);
                    $addedCount++;
                    break;
                }
            }

            if (!$alreadyInCart) {
                // Calculate item price based on product discount
                $price = $product->Discount > 0 
                    ? ($product->Price - ($product->Price * $product->Discount / 100)) 
                    : $product->Price;

                Cart::add([
                    'id' => $product->id,
                    'name' => $product->en_Product_Name,
                    'qty' => $detail->Quantity,
                    'price' => $price,
                    'weight' => 0,
                    'options' => [
                        'name_ar' => $product->fr_Product_Name,
                        'additions' => [],
                        'size' => $detail->Size,
                        'color' => $detail->Color,
                        'image' => $product->Primary_Image,
                        'slug' => $product->en_Product_Slug,
                    ]
                ]);
                $addedCount++;
            }
        }

        if ($addedCount > 0) {
            return response()->json([
                'success' => true,
                'message' => __('Order items added to cart!'),
                'cart_count' => Cart::count(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('No items could be added to the cart.')
        ], 400);
    }
}
