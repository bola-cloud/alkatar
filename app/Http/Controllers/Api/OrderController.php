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
        $order['billing_address'] = json_decode($order->billing_address, true);


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

    public function changeStatus(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'booking_id' => 'required|exists:orders,id',
            'status' => ['required', 'integer', Rule::in([2, 3,4,5,6,7,8])],
            'url' => 'required|url',
        ]);
        $order = Order::find($request->booking_id);
        $update = $order->update([
            'Order_Status' => $request->status,
        ]);
        if (!$update) {
            return response()->json(['error' => __('Something went wrong!')], 500);
        }
        $this->statusChangeEmail($order, $request->status);

        return response()->json(['success' => __('Status successfully changed!')], 200);
    }

    public function statusChangeEmail($order, $order_status)
    {
        $ship = json_decode($order->shipping_address, true);
        $data['userName'] = $ship['name'] ?? null;
        $data['userEmail'] = $ship['email'] ?? null;
        $data['order'] = $order;
        $data['companyName'] = isset(allsetting()['app_title']) && !empty(allsetting()['app_title']) ? allsetting()['app_title'] : __('Company Name');
        $data['subject'] = __('Shipment Process');
        $data['data'] = $order_status;
        $data['template'] = 'email.order-status-change';
        dispatch(new OrderConfirmMail($data))->onQueue('email-send');
    }



}
