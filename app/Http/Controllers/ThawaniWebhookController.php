<?php

namespace App\Http\Controllers;

use App\Models\Admin\Order;
use App\Models\Admin\Product;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Services\SmartLifeErpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ThawaniWebhookController extends Controller
{
    /**
     * Handle Thawani payment webhook notifications
     * This is called server-to-server by Thawani when payment status changes
     *
     * Thawani webhook payload example:
     * {
     *   "event_type": "payment.completed",
     *   "session_id": "checkout_xxxxx",
     *   "payment_status": "paid" | "cancelled" | "failed",
     *   "client_reference_id": "ORD-xxxxx"
     * }
     */
    public function handle(Request $request)
    {
        Log::info('=== THAWANI WEBHOOK RECEIVED ===', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'raw_body' => $request->getContent(),
            'ip' => $request->ip(),
        ]);

        try {
            // Verify webhook authenticity (optional: Thawani signature verification)
            // Thawani may send a signature in headers - implement if available
            $signature = $request->header('Thawani-Signature') ?? $request->header('X-Thawani-Signature');

            if ($signature && config('services.thawani.webhook_secret')) {
                $payload = $request->getContent();
                $expectedSignature = hash_hmac('sha256', $payload, config('services.thawani.webhook_secret'));

                if (!hash_equals($expectedSignature, $signature)) {
                    Log::error('Thawani webhook signature verification failed', [
                        'expected' => $expectedSignature,
                        'received' => $signature,
                    ]);
                    return response()->json(['error' => 'Invalid signature'], 401);
                }
                Log::info('Webhook signature verified successfully');
            }

            // Extract event data
            $eventType = $request->input('event_type');
            $sessionId = $request->input('session_id');
            $clientRef = $request->input('client_reference_id'); // Our Order_Number or pending_token
            $paymentStatus = $request->input('payment_status');

            if (!$sessionId && !$clientRef) {
                Log::error('Webhook missing session_id and client_reference_id');
                return response()->json(['error' => 'Missing payment identifiers'], 400);
            }

            Log::info('Processing Thawani webhook', [
                'event_type' => $eventType,
                'session_id' => $sessionId,
                'client_reference_id' => $clientRef,
                'payment_status' => $paymentStatus,
            ]);

            // Verify payment status with Thawani API (recommended for security)
            $verifiedPaymentData = $this->verifyPaymentWithThawani($sessionId);

            if (!$verifiedPaymentData) {
                Log::error('Failed to verify payment with Thawani API (Data null)', ['session_id' => $sessionId]);
                return response()->json(['error' => 'Payment verification failed'], 400);
            }

            // Use verified data from Thawani API
            $paymentStatus = $verifiedPaymentData['payment_status'] ?? $paymentStatus;
            $clientRef = $verifiedPaymentData['client_reference_id'] ?? $clientRef;

            Log::info('Verified Thawani Data', [
                'verified_status' => $paymentStatus,
                'verified_ref' => $clientRef
            ]);

            // Find order by multiple identifiers
            $order = $this->findOrder($clientRef, $sessionId);

            if (!$order) {
                Log::error('Order not found for Thawani webhook', [
                    'client_reference_id' => $clientRef,
                    'session_id' => $sessionId,
                ]);
                return response()->json(['error' => 'Order not found'], 404);
            }

            Log::info('Order found for webhook', [
                'order_id' => $order->id,
                'order_number' => $order->Order_Number,
                'current_is_paid' => $order->is_paid,
                'target_payment_status' => $paymentStatus,
            ]);

            // Handle payment status
            if ($paymentStatus === 'paid' || $paymentStatus === 'succeeded') {
                Log::info('Triggering Successful Payment Handler', ['order_id' => $order->id]);
                return $this->handleSuccessfulPayment($order, $sessionId);
            } elseif (in_array($paymentStatus, ['cancelled', 'failed', 'unpaid'])) {
                Log::info('Triggering Failed Payment Handler', ['order_id' => $order->id, 'status' => $paymentStatus]);
                return $this->handleFailedPayment($order, $paymentStatus);
            } else {
                Log::warning('Unhandled payment status in webhook', [
                    'payment_status' => $paymentStatus,
                    'order_id' => $order->id,
                ]);
                return response()->json(['status' => 'pending'], 200);
            }

        } catch (\Exception $e) {
            Log::error('Thawani webhook processing exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json(['error' => 'Internal error'], 500);
        }
    }

    /**
     * Verify payment with Thawani API for security
     */
    protected function verifyPaymentWithThawani($sessionId)
    {
        if (!$sessionId) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'thawani-api-key' => config('services.thawani.secret_key'),
            ])->get(config('services.thawani.checkout_url') . '/checkout/session/' . $sessionId);

            if ($response->successful() && isset($response['success']) && $response['success']) {
                Log::info('Thawani payment verification successful', [
                    'session_id' => $sessionId,
                    'data' => $response['data'],
                ]);
                return $response['data'];
            }

            Log::warning('Thawani payment verification returned non-success', [
                'session_id' => $sessionId,
                'response' => $response->json(),
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('Failed to verify payment with Thawani API', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Find order by various identifiers
     */
    protected function findOrder($clientRef, $sessionId)
    {
        $query = Order::query();

        if ($clientRef) {
            $query->where(function ($q) use ($clientRef) {
                $q->where('Order_Number', $clientRef)
                    ->orWhere('pending_token', $clientRef);
            });
        }

        if ($sessionId) {
            $query->orWhere('payment_session_id', $sessionId);
        }

        return $query->first();
    }

    /**
     * Handle successful payment confirmation
     */
    protected function handleSuccessfulPayment(Order $order, $sessionId = null)
    {
        // Check if already processed (idempotency - critical for webhooks)
        if ($order->is_paid) {
            Log::info('Order already marked as paid (idempotency)', [
                'order_id' => $order->id,
                'order_number' => $order->Order_Number,
            ]);
            return response()->json(['status' => 'already_processed'], 200);
        }

        DB::beginTransaction();
        try {
            // Update order payment status
            $order->update([
                'is_paid' => true,
                'Payment_Status' => PAYMENT_SUCCESS,
                'Is_Order_Successful' => true,
                'payment_session_id' => $sessionId ?? $order->payment_session_id,
                'Order_Status' => ORDER_PROCESSING,
            ]);

            // Decrement product stock
            foreach ($order->order_details as $detail) {
                $product = Product::find($detail->Product_Id);
                if ($product) {
                    $newQty = max(0, $product->Quantity - $detail->Quantity);
                    $product->update(['Quantity' => $newQty]);
                    Log::info('Stock decremented via Thawani webhook', [
                        'product_id' => $product->id,
                        'product_name' => $product->en_Product_Name,
                        'ordered_qty' => $detail->Quantity,
                        'old_stock' => $product->Quantity,
                        'new_stock' => $newQty,
                    ]);
                }
            }

            // Two-Step Sync: Update the existing SmartLife invoice to "Paid"
            if (class_exists(SmartLifeErpService::class) && config('smartlife.sync_enabled')) {
                try {
                    $smartLifeService = app(SmartLifeErpService::class);
                    $smartLifeInvoiceId = $smartLifeService->submitOrder($order);

                    if ($smartLifeInvoiceId && !$order->smartlife_invoice_id) {
                        $order->update([
                            'smartlife_invoice_id' => $smartLifeInvoiceId,
                            'smartlife_synced_at' => now(),
                        ]);
                    }
                    Log::info('SmartLife Sync via Webhook (Updated to Paid)', [
                        'order_id' => $order->id,
                        'smartlife_invoice_id' => $smartLifeInvoiceId,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to sync order to SmartLife via webhook', [
                        'error' => $e->getMessage(),
                        'order_id' => $order->id,
                    ]);
                }
            }

            DB::commit();

            Log::info('Order marked as paid successfully via Thawani webhook', [
                'order_id' => $order->id,
                'order_number' => $order->Order_Number,
                'grand_total' => $order->Grand_Total,
            ]);

            try {
                app(CheckoutController::class)->orderConfirmMail($order);
                Log::info('Confirmation email sent via webhook', [
                    'order_id' => $order->id,
                    'order_number' => $order->Order_Number,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send confirmation email via webhook', [
                    'error' => $e->getMessage(),
                    'order_id' => $order->id,
                ]);
                // Don't fail webhook - email can be resent manually
            }

            return response()->json([
                'status' => 'success',
                'order_id' => $order->id,
                'order_number' => $order->Order_Number,
                'message' => 'Payment processed successfully',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to process successful Thawani payment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'order_id' => $order->id,
            ]);
            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Handle failed or cancelled payment
     */
    protected function handleFailedPayment(Order $order, $paymentStatus)
    {
        try {
            $order->update([
                'Payment_Status' => PAYMENT_CANCELLED,
                'Order_Status' => ORDER_CANCELLED,
            ]);

            Log::info('Order marked as cancelled/failed via Thawani webhook', [
                'order_id' => $order->id,
                'order_number' => $order->Order_Number,
                'payment_status' => $paymentStatus,
            ]);

            return response()->json([
                'status' => 'cancelled',
                'order_id' => $order->id,
                'order_number' => $order->Order_Number,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to mark order as cancelled', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
            ]);
            return response()->json(['error' => 'Failed to process cancellation'], 500);
        }
    }
}
