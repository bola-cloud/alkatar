<?php

namespace App\Observers;

use App\Models\Admin\Category;
use App\Models\Admin\Product;
use Illuminate\Support\Facades\Log;

class CategoryObserver
{
    /**
     * Handle the Category "deleting" event.
     * Prevent hard deletion if category has products with orders
     */
    public function deleting(Category $category)
    {
        // Check if this is a hard delete attempt
        if ($category->isForceDeleting()) {
            // Check if category has products with order history
            $hasProductsWithOrders = Product::withTrashed()
                ->where('Category_Id', $category->id)
                ->whereHas('order_details')
                ->exists();

            if ($hasProductsWithOrders) {
                Log::warning('Attempted to hard delete category with products that have orders', [
                    'category_id' => $category->id,
                    'category_name' => $category->en_Category_Name
                ]);

                throw new \Exception(
                    "Cannot permanently delete category '{$category->en_Category_Name}' (ID: {$category->id}) " .
                    "because it contains products with existing orders. Use soft delete instead."
                );
            }
        }

        // Log soft delete
        if (!$category->isForceDeleting()) {
            Log::info('Category soft-deleted', [
                'category_id' => $category->id,
                'category_name' => $category->en_Category_Name,
                'deleted_reason' => $category->deleted_reason ?? 'manual'
            ]);
        }

        return true;
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category)
    {
        if ($category->isForceDeleting()) {
            Log::info('Category hard-deleted (no order history)', [
                'category_id' => $category->id,
                'category_name' => $category->en_Category_Name
            ]);
        }
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category)
    {
        Log::info('Category restored', [
            'category_id' => $category->id,
            'category_name' => $category->en_Category_Name
        ]);

        // Clear deletion reason
        $category->deleted_reason = null;
        $category->saveQuietly();
    }
}
