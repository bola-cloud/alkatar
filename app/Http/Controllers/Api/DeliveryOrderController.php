<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryOrderController extends Controller
{
    // List orders ready to be picked up (e.g., Processing status)
    public function index(Request $request)
    {
        // Assuming ORDER_PROCESSING (2) is 'Ready for Pickup'
        // And excluding orders already assigned (though logic implies unassigned)
        $orders = Order::with(['order_details', 'user'])
            ->where('Order_Status', ORDER_PROCESSING)
            ->whereNull('delivery_man_id')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json(['success' => true, 'data' => $orders]);
    }

    // Assign order to delivery man via ID and Phone verification
    public function pickOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'phone_number' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $order = Order::with(['order_details', 'user'])->find($request->order_id);

        if (!$order) {
            return response()->json(['success' => false, 'message' => __('Order not found')], 404);
        }

        // Decode billing address to get the phone number provided at checkout
        $billing = $order->billing_address;
        if (is_string($billing)) {
            $billing = json_decode($billing, true);
        }
        $checkoutPhone = $billing['phone_number'] ?? '';

        // Compare input phone with checkout phone
        if (trim($request->phone_number) !== trim($checkoutPhone)) {
            return response()->json(['success' => false, 'message' => __('Phone number does not match the one in order details')], 403);
        }

        if ($order->delivery_man_id) {
            return response()->json(['success' => false, 'message' => __('Order already picked by another driver')], 400);
        }

        // Assign to current user
        $order->delivery_man_id = $request->user()->id;
        $order->Order_Status = ORDER_SHIPPED; // Mark as "On the Way"
        $order->delivery_status = 'picked_up';
        $order->save();

        return response()->json(['success' => true, 'message' => __('Order picked successfully'), 'data' => $order]);
    }

    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|in:delivered,failed',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $order = Order::with(['order_details', 'user'])
            ->where('id', $request->order_id)
            ->where('delivery_man_id', $request->user()->id)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => __('Order not found or not assigned to you')], 404);
        }

        if ($request->status === 'delivered') {
            $order->Order_Status = ORDER_DELIVERED;
            $order->Is_Order_Completed = 1;
            $order->Is_Order_Successful = 1;
            $order->delivery_status = 'delivered';
            $order->Delivery_At = now();
        } else {
            $order->Order_Status = ORDER_DELIVERED_FAILED;
            $order->delivery_status = 'failed';
        }

        $order->save();

        return response()->json(['success' => true, 'message' => __('Order status updated'), 'data' => $order]);
    }

    // List all orders assigned to this delivery man (Picked or Delivered)
    public function myOrders(Request $request)
    {
        $orders = Order::with(['order_details', 'user'])
            ->where('delivery_man_id', $request->user()->id)
            ->whereIn('Order_Status', [ORDER_SHIPPED, ORDER_DELIVERED, ORDER_DELIVERED_FAILED])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return response()->json(['success' => true, 'data' => $orders]);
    }

    public function history(Request $request)
    {
        $orders = Order::with(['order_details', 'user'])
            ->where('delivery_man_id', $request->user()->id)
            ->whereIn('Order_Status', [ORDER_DELIVERED, ORDER_DELIVERED_FAILED])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return response()->json(['success' => true, 'data' => $orders]);
    }
}