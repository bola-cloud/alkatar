<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add indexes if not exists. We keep names unique and check to avoid duplicates.
            $schema = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $schema->listTableIndexes('orders');

            $ensureIndex = function (string $column, string $indexName) use ($table, $indexes) {
                if (!array_key_exists(strtolower($indexName), array_change_key_case($indexes))) {
                    if (Schema::hasColumn('orders', $column)) {
                        $table->index($column, $indexName);
                    }
                }
            };

            // Based on observed schema in this project
            $ensureIndex('created_at', 'orders_created_at_idx');
            $ensureIndex('Delivery_At', 'orders_delivery_at_idx');
            $ensureIndex('Order_Status', 'orders_order_status_idx');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop indexes if they exist
            $dropIfExists = function (string $index) use ($table) {
                try { $table->dropIndex($index); } catch (Throwable $e) {}
            };
            $dropIfExists('orders_created_at_idx');
            $dropIfExists('orders_delivery_at_idx');
            $dropIfExists('orders_order_status_idx');
        });
    }
};
