<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function order_print(Request $request)
    {
        $order = Order::query()
            ->with('order_details', 'user', 'coupon', 'order_details.product', 'billing', 'shipping')
            ->find($request->id);
        $order['billing_address'] = json_decode($order->billing_address, true);

        return view('admin.pages.orders.invoice', compact('order'));
    }

}
