<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSmartlifeFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add SmartLife ERP fields
            $table->string('smartlife_id')->nullable()->unique()->after('id');
            $table->string('barcode')->nullable()->index()->after('smartlife_id');
            $table->string('unit')->nullable()->after('Quantity'); // KG, PC, etc
            $table->decimal('cost', 10, 3)->nullable()->after('Price'); // Cost price from SmartLife
            $table->integer('alert_quantity')->nullable()->after('Quantity'); // Low stock alert
            $table->string('product_type')->nullable()->after('type'); // Standard, Combo from SmartLife
            $table->boolean('show_pos')->default(true)->after('Status'); // Show in POS
            $table->boolean('synced_from_smartlife')->default(false)->after('show_pos'); // Track SmartLife synced products
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'smartlife_id',
                'barcode',
                'unit',
                'cost',
                'alert_quantity',
                'product_type',
                'show_pos',
                'synced_from_smartlife'
            ]);
        });
    }
}
