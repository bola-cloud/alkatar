<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\Order;
use App\Events\OrderCreated;

class TestDeliveryNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:test-delivery {order_id?} {--player=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trigger a test OneSignal notification for delivery personnel';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');
        $playerId = $this->option('player');
        
        if ($orderId) {
            $order = Order::find($orderId);
        } else {
            $order = Order::orderBy('id', 'desc')->first();
        }

        if (!$order) {
            $this->error('No order found to test with.');
            return 0;
        }

        $this->info("App ID: " . config('services.onesignal.app_id'));
        if ($playerId) {
            $this->info("Targeting Player ID: {$playerId}");
        } else {
            $this->info("Targeting Segment: Subscribed Users");
        }

        $this->info("Triggering OrderCreated event for Order #{$order->Order_Number}...");
        
        // Dispatch the event which triggers the SendDeliveryNotification listener
        event(new OrderCreated($order, $playerId));

        $this->info('Event dispatched. Check storage/logs/laravel.log for OneSignal API response.');
        
        return 0;
    }
}
