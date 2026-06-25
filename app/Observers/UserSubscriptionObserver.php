<?php

namespace App\Observers;

use App\Models\UserSubscription;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserSubscriptionObserver
{
    /**
     * Handle the UserSubscription "created" event.
     *
     * @param  \App\Models\UserSubscription  $userSubscription
     * @return void
     */
    public function created(UserSubscription $userSubscription)
    {
        $this->processWalletTopUp($userSubscription);
    }

    /**
     * Handle the UserSubscription "updated" event.
     *
     * @param  \App\Models\UserSubscription  $userSubscription
     * @return void
     */
    public function updated(UserSubscription $userSubscription)
    {
        // If status changed to active (1), process top up
        if ($userSubscription->wasChanged('status') && $userSubscription->status == 1) {
            $this->processWalletTopUp($userSubscription);
        }
    }

    protected function processWalletTopUp(UserSubscription $userSubscription)
    {
        // Wallet top-up disabled: Subscription price is treated as a membership fee (Amazon Prime model)
        return;

        // Only proceed if subscription is active
        if ($userSubscription->status != 1) {
            return;
        }

        try {
            // Load subscription details
            $userSubscription->load('subscription');
            $subscription = $userSubscription->subscription;

            if (!$subscription) {
                return;
            }

            $user = User::find($userSubscription->user_id);
            if (!$user) {
                return;
            }

            // The value to add to wallet is the price of the subscription
            $amount = $subscription->price;

            if ($amount > 0) {
                $user->increment('balance', $amount);
                Log::info("Wallet Top-Up: Added {$amount} OMR to User ID {$user->id} from Subscription ID {$subscription->id}");
            }

        } catch (\Exception $e) {
            Log::error("Wallet Top-Up Error: " . $e->getMessage());
        }
    }
}
