<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
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
            ->where(function ($query) {
                // Show all non-thawani orders (like COD) OR paid Thawani orders
                $query->where('Payment_Method', '!=', THAWANI)
                    ->orWhere('is_paid', 1);
            })
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

        if ($order->collection_method == 'store_pickup') {
            return response()->json(['success' => false, 'message' => __('This order is for Warehouse Pickup and cannot be assigned to a delivery man')], 403);
        }

        // Normalize the input phone number
        $inputPhone = Order::normalizePhone($request->phone_number);

        // Get checkout phone from billing address (using the robust model accessor)
        $billing = $order->billing_address;
        $checkoutPhone = $billing['phone_number'] ?? '';
        $normalizedCheckoutPhone = Order::normalizePhone($checkoutPhone);

        // Fallback: Check the user's primary number if checkout phone doesn't match or is missing
        $userPhone = $order->user->Number ?? '';
        $normalizedUserPhone = Order::normalizePhone($userPhone);

        // Compare input phone with normalized checkout phone OR normalized user phone
        if ($inputPhone !== $normalizedCheckoutPhone && $inputPhone !== $normalizedUserPhone) {
            return response()->json([
                'success' => false,
                'message' => __('Phone number does not match the one in order details'),
                // Including hints only if helpful for debugging, but keeping it secure
                'debug_info' => 'Input suffix: ' . $inputPhone . ' | Exp suffix: ' . $normalizedCheckoutPhone
            ], 403);
        }

        if ($order->delivery_man_id) {
            return response()->json(['success' => false, 'message' => __('Order already picked by another driver')], 400);
        }

        // Get the authenticated user
        $user = $request->user();
        
        // Ensure the authenticated user is actually a DeliveryMan model
        // If not (e.g., if using a general user token), try to find the corresponding DeliveryMan record
        $driverId = null;
        if ($user instanceof \App\Models\Admin\DeliveryMan) {
            $driverId = $user->id;
        } else {
            // Fallback: This handles cases where Sanctum might return a generic User model
            // instead of the specified DeliveryMan model due to guard configuration issues
            $driver = \App\Models\Admin\DeliveryMan::where('email', $user->email)
                ->orWhere('phone', $user->Number)
                ->first();
            
            if (!$driver) {
                return response()->json(['success' => false, 'message' => __('You are not registered as a delivery man')], 403);
            }
            $driverId = $driver->id;
        }

        // Assign to the verified driver ID
        $order->delivery_man_id = $driverId;
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
            'collection_method' => 'nullable|in:cash,transfer,visa',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        // Get the correct driver ID for verification
        $user = $request->user();
        $driverId = ($user instanceof \App\Models\Admin\DeliveryMan) ? $user->id : 
                    (\App\Models\Admin\DeliveryMan::where('email', $user->email)->first()->id ?? null);

        if (!$driverId) {
            return response()->json(['success' => false, 'message' => __('Unauthorized')], 401);
        }

        $order = Order::with(['order_details', 'user'])
            ->where('id', $request->order_id)
            ->where('delivery_man_id', $driverId)
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

            // Check if it is a COD order to mark as paid and sync ERP
            if ($order->Payment_Method == COD) {
                $order->is_paid = 1;
                $order->Payment_Status = PAYMENT_SUCCESS;
                
                // Store collection method if provided
                if ($request->collection_method) {
                    $order->collection_method = $request->collection_method;
                }

                // Sync to SmartLife ERP as PAID
                if (config('smartlife.sync_enabled')) {
                    try {
                        $smartLifeService = new \App\Services\SmartLifeErpService();
                        $smartLifeService->submitOrder($order);
                        Log::info('SmartLife Sync: COD order marked as Paid and synced by Delivery Man', ['order' => $order->Order_Number]);
                    } catch (\Exception $e) {
                        Log::error('SmartLife Sync failed during Delivery App COD update', ['error' => $e->getMessage()]);
                    }
                }
            }
        } else {
            $order->Order_Status = ORDER_DELIVERED_FAILED;
            $order->delivery_status = 'failed';
        }

        $order->save();

        // Send WhatsApp Status Notification
        try {
            $url = "https://hispeed.om";
            if ($order->Order_Status == ORDER_DELIVERED) {
                $url = route('user.profile.track.my.order', ['id' => encrypt($order->id)]);
            }

            $phoneNumber = $order->user->Number ?? null;
            if (empty($phoneNumber)) {
                $billingAddress = $order->billing_address;
                $phoneNumber = $billingAddress['phone_number'] ?? '';
            }

            if ($phoneNumber) {
                $status_data = $order->getStatusLang()[$order->Order_Status] ?? null;
                $status_ar = $status_data['status_ar'] ?? 'N/A';

                $response = Http::asForm()->post('https://whatsapi.hispeed.om/api/v1/whatsapp/change_status', [
                    'phone_number' => $phoneNumber,
                    'name' => $order->user->name ?? $billingAddress['name'] ?? '',
                    'order_id' => $order->id,
                    'status' => $status_ar,
                ]);

                if ($response->failed()) {
                    Log::error('WhatsApp Status Change API Error (Delivery App): ' . $response->body());
                }
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed in Delivery App', ['error' => $e->getMessage()]);
        }

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