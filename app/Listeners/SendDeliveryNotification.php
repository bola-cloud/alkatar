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
        try {
            $order = $event->order;
            // The CHANNEL_KEY provided by the user for OneSignal
            $channelKey = '48459b3d-81ba-4858-8d13-fc241b087f69';

            $payload = [
                'app_id' => config('services.onesignal.app_id'),
                'included_segments' => ['Subscribed Users'],
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

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Basic ' . config('services.onesignal.rest_api_key'),
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
