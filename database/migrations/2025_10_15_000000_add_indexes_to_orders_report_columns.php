<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add safe indexes on likely filtered/sorted columns.
        Schema::table('orders', function (Blueprint $table) {
            // created_at index for date filters
            if (!$this->indexExists('orders', 'orders_created_at_index')) {
                $table->index('created_at');
            }
            // Delivery_At index (if column exists)
            if (Schema::hasColumn('orders', 'Delivery_At') && !$this->indexExists('orders', 'orders_delivery_at_index')) {
                $table->index('Delivery_At');
            }
            // Status index
            if (Schema::hasColumn('orders', 'Order_Status') && !$this->indexExists('orders', 'orders_order_status_index')) {
                $table->index('Order_Status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if ($this->indexExists('orders', 'orders_created_at_index')) {
                $table->dropIndex('orders_created_at_index');
            }
            if ($this->indexExists('orders', 'orders_delivery_at_index')) {
                $table->dropIndex('orders_delivery_at_index');
            }
            if ($this->indexExists('orders', 'orders_order_status_index')) {
                $table->dropIndex('orders_order_status_index');
            }
        });
    }

    private function indexExists(string $table, string $index): bool
    {
        $connection = Schema::getConnection()->getDoctrineSchemaManager();
        $indexes = $connection->listTableIndexes($table);
        return array_key_exists($index, $indexes);
    }
};
