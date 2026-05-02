<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Frontend\CheckoutController;
use App\Models\Admin\Order;
use App\Models\Admin\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentCallbackController extends Controller
{
    /**
     * Handle successful payment callback (user redirect from payment gateway)
     * This is where the user lands after completing payment
     */
    public function success(Request $request)
    {
        Log::info('=== PAYMENT CALLBACK SUCCESS ===', [
            'all_request' => $request->all(),
            'query_string' => $request->getQueryString(),
        ]);

        // Get order reference from request (payment gateway sends this back)
        $orderReference = $request->query('order_reference')
                       ?? $request->query('reference')
                       ?? $request->query('pending_token')
                       ?? $request->session()->get('pending_token');

        $paymentId = $request->query('payment_id')
                  ?? $request->query('transaction_id');

        Log::info('Callback parameters', [
            'order_reference' => $orderReference,
            'payment_id' => $paymentId,
        ]);

        if (!$orderReference && !$paymentId) {
            Log::warning('No order reference in callback');
            return redirect()->route('checkout')->with('error', 'Payment session not found. Please contact support if payment was deducted.');
        }

        // Find order
        $order = null;
        if ($orderReference) {
            $order = Order::where('pending_token', $orderReference)
                ->orWhere('Order_Number', $orderReference)
                ->first();
        }

        if (!$order && $paymentId) {
            $order = Order::where('payment_session_id', $paymentId)->first();
        }

        if (!$order) {
            Log::error('Order not found in callback', [
                'order_reference' => $orderReference,
                'payment_id' => $paymentId,
            ]);
            return redirect()->route('checkout')->with('error', 'Order not found. Please contact support.');
        }

        Log::info('Order found in callback', [
            'order_id' => $order->id,
            'order_number' => $order->Order_Number,
            'is_paid' => $order->is_paid,
        ]);

        // Check if already marked as paid (webhook may have processed it first)
        if ($order->is_paid) {
            Log::info('Order already marked as paid (webhook processed first)', ['order_id' => $order->id]);
            Cart::destroy();
            session()->forget(['pending_token', 'payment_session_id']);
            return redirect()->route('checkout.thankyou_page')->with('success', 'Order successfully created!');
        }

        // If not already paid, try to verify immediately (Fallback to Webhook)
        if (!$order->is_paid && $paymentId) {
            Log::info('Attempting immediate verification fallback in callback', ['order_id' => $order->id, 'session_id' => $paymentId]);
            
            try {
                // Reuse the same verification logic as the webhook controller if possible
                // For simplicity and to avoid dependency issues in this specific controller, we'll do it directly
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'thawani-api-key' => config('services.thawani.secret_key'),
                ])->get(config('services.thawani.checkout_url') . '/checkout/session/' . $paymentId);

                if ($response->successful() && isset($response['success']) && $response['success']) {
                    $verifiedData = $response['data'];
                    $status = $verifiedData['payment_status'] ?? '';

                    if ($status === 'paid' || $status === 'succeeded') {
                        Log::info('Payment verified via callback fallback. Syncing to ERP.', ['order_id' => $order->id]);
                        
                        // Mark as paid locally first
                        $order->update([
                            'is_paid' => true,
                            'Payment_Status' => PAYMENT_SUCCESS,
                            'Is_Order_Successful' => true,
                            'Order_Status' => ORDER_PROCESSING,
                        ]);

                        // Sync to ERP
                        if (class_exists(\App\Services\SmartLifeErpService::class) && config('smartlife.sync_enabled')) {
                            $smartLifeService = app(\App\Services\SmartLifeErpService::class);
                            $smartLifeService->submitOrder($order);
                        }

                        try {
                            app(CheckoutController::class)->sendOrderNotification($order->id);
                        } catch (\Exception $e) {
                            Log::error('Fallback callback: WhatsApp notification failed', ['error' => $e->getMessage()]);
                        }

                        
                        Cart::destroy();
                        session()->forget(['pending_token', 'payment_session_id', 'thawani_session_id']);
                        return redirect()->route('checkout.thankyou_page')->with('success', 'Order successfully created and paid!');
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Fallback verification failed', ['error' => $e->getMessage()]);
            }
        }

        // Payment not yet confirmed via webhook or fallback - show pending message
        Log::info('Payment still pending confirmation after fallback check', ['order_id' => $order->id]);

        Cart::destroy();
        session()->forget(['pending_token', 'payment_session_id', 'thawani_session_id']);

        return redirect()->route('checkout.thankyou_page')
            ->with('info', 'Your payment is being processed. You will receive a confirmation email shortly.');
    }

    /**
     * Handle cancelled payment callback
     */
    public function cancel(Request $request)
    {
        Log::info('=== PAYMENT CALLBACK CANCEL ===', [
            'all_request' => $request->all(),
        ]);

        $orderReference = $request->query('order_reference')
                       ?? $request->query('reference')
                       ?? session()->get('pending_token');

        if ($orderReference) {
            $order = Order::where('pending_token', $orderReference)->first();
            if ($order && !$order->is_paid) {
                $order->update([
                    'Payment_Status' => PAYMENT_CANCELLED,
                    'Order_Status' => 'cancelled',
                ]);
                Log::info('Order cancelled via callback', ['order_id' => $order->id]);
            }
        }

        return redirect()->route('checkout')->with('error', 'Payment was cancelled.');
    }
}
