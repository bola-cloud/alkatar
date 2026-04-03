<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendDeliveryNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;

        // Idempotency check: Ensure we only send one OneSignal notification per order lifecycle stage
        // Uses a 10-minute lock to prevent duplicate sends due to race conditions or multiple event triggers
        $cacheKey = 'onesignal_sent_' . $order->id;
        if (!\Illuminate\Support\Facades\Cache::add($cacheKey, true, 600)) {
            \Illuminate\Support\Facades\Log::info('OneSignal: Skipping duplicate notification (idempotency)', [
                'order_number' => $order->Order_Number,
                'order_id' => $order->id
            ]);
            return;
        }

        try {
            // The CHANNEL_KEY provided by the user for OneSignal
            $channelKey = config('services.onesignal.android_channel_id');

            $payload = [
                'app_id' => config('services.onesignal.app_id'),
                'android_channel_id' => $channelKey,
                'headings' => [
                    'en' => 'New Order Alert!',
                    'ar' => 'تنبيه طلب جديد!'
                ],
                'contents' => [
                    'en' => "Order #{$order->Order_Number} is ready for delivery.",
                    'ar' => "الطلب رقم {$order->Order_Number} جاهز للتوصيل."
                ],
                'data' => [
                    'order_number' => $order->Order_Number,
                    'order_id' => $order->id,
                    'type' => 'new_order'
                ]
            ];

            // Only include player_id if it's a valid UUID to avoid API errors
            $isValidUuid = $event->player_id && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $event->player_id);

            if ($isValidUuid) {
                $payload['include_player_ids'] = [$event->player_id];
            } else {
                // Fallback to all subscribed users if player_id is missing or invalid
                $payload['included_segments'] = ['Total Subscriptions'];
            }

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.onesignal.rest_api_key'),
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
            ])->post('https://onesignal.com/api/v1/notifications', $payload);

            if ($response->successful()) {
                \Illuminate\Support\Facades\Log::info('OneSignal Delivery Notification Sent', [
                    'order_number' => $order->Order_Number,
                    'response' => $response->json()
                ]);
            } else {
                \Illuminate\Support\Facades\Log::error('OneSignal Delivery Notification Failed', [
                    'order_number' => $order->Order_Number,
                    'status' => $response->status(),
                    'error' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Exception sending OneSignal Delivery Notification', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
