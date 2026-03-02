<?php

namespace App\Observers;

use App\Models\Admin\Product;
use App\Models\Admin\OrderDetails;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    /**
     * Handle the Product "deleting" event.
     * Prevent hard deletion if product has orders
     */
    public function deleting(Product $product)
    {
        // Check if this is a hard delete attempt
        if ($product->isForceDeleting()) {
            // Check if product has any order history
            $hasOrders = OrderDetails::where('Product_Id', $product->id)->exists();

            if ($hasOrders) {
                Log::warning('Attempted to hard delete product with orders', [
                    'product_id' => $product->id,
                    'product_name' => $product->en_Product_Name
                ]);

                // Prevent hard deletion - throw exception or return false
                throw new \Exception(
                    "Cannot permanently delete product '{$product->en_Product_Name}' (ID: {$product->id}) " .
                    "because it has existing orders. Use soft delete instead to preserve order history."
                );
            }
        }

        // Soft delete is allowed - log it
        if (!$product->isForceDeleting()) {
            Log::info('Product soft-deleted', [
                'product_id' => $product->id,
                'product_name' => $product->en_Product_Name,
                'deleted_reason' => $product->deleted_reason ?? 'manual'
            ]);
        }

        return true;
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product)
    {
        // Log successful deletion
        if ($product->isForceDeleting()) {
            Log::info('Product hard-deleted (no orders)', [
                'product_id' => $product->id,
                'product_name' => $product->en_Product_Name
            ]);
        }
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product)
    {
        Log::info('Product restored', [
            'product_id' => $product->id,
            'product_name' => $product->en_Product_Name
        ]);

        // Clear deletion reason on restore
        $product->deleted_reason = null;
        $product->saveQuietly(); // Save without triggering events
    }
}
