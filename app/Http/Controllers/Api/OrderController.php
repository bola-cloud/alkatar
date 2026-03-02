<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\OrderConfirmMail;
use App\Models\Admin\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;

class OrderController extends Controller
{
    public function order_print(Request $request)
    {
        $order = Order::query()
            ->with('order_details', 'user', 'coupon', 'order_details.product', 'billing', 'shipping')
            ->find($request->id);
        $order['billing_address'] = $order->billing_address;


        $currentLocale = app()->getLocale();
        app()->setLocale('en');

        $pdf = PDF::loadView('admin.pages.orders.api-invoice', compact('order'), [], [
            'title' => 'Order #' . $order->id,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'autoArabic' => true,
        ]);

        return $pdf->download($order->id . '.pdf');
    }



}
