<?php

namespace App\Jobs;

use App\Models\Admin\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendPendingThawaniReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $orderId;
    protected string $paymentUrl;

    /**
     * How many times to retry if the job fails.
     */
    public int $tries = 3;

    /**
     * @param int    $orderId    The order's database ID
     * @param string $paymentUrl The Thawani payment URL to include in the notification
     */
    public function __construct(int $orderId, string $paymentUrl)
    {
        $this->orderId    = $orderId;
        $this->paymentUrl = $paymentUrl;
    }

    /**
     * Execute the job.
     * Fires only if the order is STILL in PENDING state after the delay.
     */
    public function handle(): void
    {
        $order = Order::find($this->orderId);

        if (!$order) {
            Log::info("SendPendingThawaniReminderJob: order #{$this->orderId} not found — skipping.");
            return;
        }

        // ✅ Order already paid or cancelled — do nothing
        if (
            strtoupper($order->Payment_Method) !== 'THAWANI' ||
            $order->Payment_Status != PAYMENT_PENDING
        ) {
            Log::info("SendPendingThawaniReminderJob: order #{$this->orderId} is no longer pending ({$order->Payment_Status}) — skipping notification.");
            return;
        }

        // 🔔 Order is still unpaid — send the pending notification now
        Log::info("SendPendingThawaniReminderJob: order #{$this->orderId} is still PENDING — sending WhatsApp reminder.");

        try {
            app(\App\Http\Controllers\Frontend\CheckoutController::class)
                ->sendPendingThawaniNotification($this->orderId, $this->paymentUrl);
        } catch (\Exception $e) {
            Log::error("SendPendingThawaniReminderJob: failed to send notification for order #{$this->orderId}: " . $e->getMessage());
        }
    }
}
