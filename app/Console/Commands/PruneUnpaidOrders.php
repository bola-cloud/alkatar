<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\Order;
use Illuminate\Support\Facades\Log;

class PruneUnpaidOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // Define a clear signature for your command
    protected $signature = 'orders:prune-unpaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unpaid orders that are more than 20 minutes old from the last 30 days';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Starting unpaid order pruning task.'); // Log start

        $statusToDelete = PAYMENT_PENDING;
        $thirtyDaysAgo = now()->subDays(30);
        $twentyMinutesAgo = now()->subMinutes(20);

        try {
            // Find and delete orders that are more than 20 minutes old but within last 30 days
            // $deletedCount = Order::where('Payment_Status', $statusToDelete)
            //     ->where('created_at', '>=', $thirtyDaysAgo)
            //     ->where('created_at', '<=', $twentyMinutesAgo)
            //     ->delete();
      
            // if ($deletedCount > 0) {
            //     $this->info("Successfully deleted {$deletedCount} unpaid orders older than 20 minutes.");
            //     Log::info("Deleted {$deletedCount} unpaid orders older than 20 minutes."); // Log success count
            // } else {
            //     $this->info("No eligible unpaid orders found to delete.");
            //     Log::info("No eligible unpaid orders found to delete."); // Log no action needed
            // }

            Log::info('Finished unpaid order pruning task successfully.'); // Log successful finish
            return Command::SUCCESS; 

        } catch (\Exception $e) {
            $this->error('An error occurred while pruning unpaid orders.');
            Log::error('Error during unpaid order pruning task: ' . $e->getMessage(), [
                'exception' => $e // Log the full exception details for debugging
            ]);
            return Command::FAILURE; // Indicate failure
        }
    }
}