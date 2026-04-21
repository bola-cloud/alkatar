<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function order_print(Request $request)
    {
        // Printing should always be in English
        app()->setLocale('en');

        $order = Order::query()
            ->with('order_details', 'user', 'coupon', 'order_details.product', 'billing', 'shipping')
            ->find($request->id);

        if (!$order) {
            abort(404, 'Order not found');
        }

        $order['billing_address'] = $order->billing_address;

        return view('admin.pages.orders.invoice', compact('order'));
    }
}
