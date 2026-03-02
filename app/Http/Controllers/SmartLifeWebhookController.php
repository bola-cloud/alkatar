<?php

namespace App\Http\Controllers;

use App\Models\Admin\Order;
use App\Models\Admin\Product;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Services\SmartLifeErpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SmartLifeWebhookController extends Controller
{
    /**
     * Handle SmartLife payment webhook notifications
     * This is called server-to-server by SmartLife (or payment gateway) when payment status changes
     *
     * Expected webhook payload:
     * {
     *   "event_type": "payment.completed" | "payment.failed" | "payment.cancelled",
     *   "payment_id": "PAY12345",
     *   "order_reference": "ORD-xxxxx" // our pending_token or Order_Number
     *   "payment_status": "paid" | "failed" | "cancelled",
     *   "amount": 100.00,
     *   "currency": "OMR",
     *   "transaction_id": "TXN-xxxxx"
     * }
     */
    public function handle(Request $request)
    {
        Log::info('=== SMARTLIFE PAYMENT WEBHOOK RECEIVED ===', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'ip' => $request->ip(),
        ]);

        try {
            // Verify webhook authenticity (check signature if SmartLife provides one)
            $signature = $request->header('X-SmartLife-Signature');
            if ($signature) {
                // Verify signature with webhook secret from config
                $webhookSecret = config('services.smartlife.webhook_secret');
                $payload = $request->getContent();
                $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);

                if (!hash_equals($expectedSignature, $signature)) {
                    Log::error('Webhook signature verification failed', [
                        'expected' => $expectedSignature,
                        'received' => $signature,
                    ]);
                    return response()->json(['error' => 'Invalid signature'], 401);
                }
            }

            // Extract event data
            $eventType = $request->input('event_type');
            $paymentStatus = $request->input('payment_status');
            $orderReference = $request->input('order_reference'); // pending_token or Order_Number
            $paymentId = $request->input('payment_id');
            $transactionId = $request->input('transaction_id');
            $amount = $request->input('amount');

            if (!$orderReference) {
                Log::error('Webhook missing order_reference');
                return response()->json(['error' => 'Missing order_reference'], 400);
            }

            Log::info('Processing payment webhook', [
                'event_type' => $eventType,
                'payment_status' => $paymentStatus,
                'order_reference' => $orderReference,
                'payment_id' => $paymentId,
            ]);

            // Find order by pending_token or payment_session_id or Order_Number
            $order = Order::where('pending_token', $orderReference)
                ->orWhere('payment_session_id', $paymentId)
                ->orWhere('Order_Number', $orderReference)
                ->first();

            if (!$order) {
                Log::error('Order not found for webhook', [
                    'order_reference' => $orderReference,
                    'payment_id' => $paymentId,
                ]);
                return response()->json(['error' => 'Order not found'], 404);
            }

            Log::info('Order found for webhook', [
                'order_id' => $order->id,
                'order_number' => $order->Order_Number,
                'is_paid' => $order->is_paid,
                'payment_status' => $paymentStatus,
            ]);

            // Handle payment status
            if ($paymentStatus === 'paid' || $paymentStatus === 'completed' || $paymentStatus === 'success') {
                return $this->handleSuccessfulPayment($order, $transactionId, $amount);
            } elseif ($paymentStatus === 'failed' || $paymentStatus === 'cancelled') {
                return $this->handleFailedPayment($order, $paymentStatus);
            } else {
                Log::warning('Unhandled payment status in webhook', [
                    'payment_status' => $paymentStatus,
                    'order_id' => $order->id,
                ]);
                return response()->json(['status' => 'pending'], 200);
            }

        } catch (\Exception $e) {
            Log::error('Webhook processing exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Internal error'], 500);
        }
    }

    /**
     * Handle successful payment confirmation
     */
    protected function handleSuccessfulPayment(Order $order, $transactionId = null, $amount = null)
    {
        // Check if already processed (idempotency)
        if ($order->is_paid) {
            Log::info('Order already marked as paid (idempotency)', ['order_id' => $order->id]);
            return response()->json(['status' => 'already_processed'], 200);
        }

        DB::beginTransaction();
        try {
            // Mark order as paid
            $order->update([
                'is_paid' => true,
                'Payment_Status' => PAYMENT_SUCCESS,
                'Is_Order_Successful' => true,
                'txn' => $transactionId ?? $order->txn,
            ]);

            // Decrement product stock
            foreach ($order->order_details as $detail) {
                $product = Product::find($detail->Product_Id);
                if ($product) {
                    $newQty = max(0, $product->Quantity - $detail->Quantity);
                    $product->update(['Quantity' => $newQty]);
                    Log::info('Stock decremented via webhook', [
                        'product_id' => $product->id,
                        'ordered_qty' => $detail->Quantity,
                        'new_qty' => $newQty,
                    ]);
                }
            }

            // Sync order to SmartLife ERP
            try {
                $smartLifeService = app(SmartLifeErpService::class);
                $smartLifeInvoiceId = $smartLifeService->submitOrder($order);

                if ($smartLifeInvoiceId) {
                    $order->update([
                        'smartlife_invoice_id' => $smartLifeInvoiceId,
                        'smartlife_synced_at' => now(),
                    ]);
                    Log::info('Order synced to SmartLife ERP', [
                        'order_id' => $order->id,
                        'smartlife_invoice_id' => $smartLifeInvoiceId,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to sync order to SmartLife (non-critical)', [
                    'error' => $e->getMessage(),
                    'order_id' => $order->id,
                ]);
                // Don't fail the webhook - order is paid, SmartLife sync can be retried later
            }

            DB::commit();

            Log::info('Order marked as paid successfully via webhook', [
                'order_id' => $order->id,
                'order_number' => $order->Order_Number,
            ]);

            // Send confirmation email
            try {
                (new CheckoutController())->orderConfirmMail($order);
                Log::info('Confirmation email sent via webhook', ['order_id' => $order->id]);
            } catch (\Exception $e) {
                Log::error('Failed to send email via webhook', [
                    'error' => $e->getMessage(),
                    'order_id' => $order->id,
                ]);
            }

            return response()->json([
                'status' => 'success',
                'order_id' => $order->id,
                'order_number' => $order->Order_Number,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to process webhook payment', [
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
    protected function handleFailedPayment(Order $order, $status)
    {
        try {
            $order->update([
                'Payment_Status' => PAYMENT_CANCELLED,
                'Order_Status' => 'cancelled',
            ]);

            Log::info('Order marked as cancelled via webhook', [
                'order_id' => $order->id,
                'payment_status' => $status,
            ]);

            return response()->json(['status' => 'cancelled'], 200);

        } catch (\Exception $e) {
            Log::error('Failed to mark order as cancelled', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
            ]);
            return response()->json(['error' => 'Failed to update order'], 500);
        }
    }
}
