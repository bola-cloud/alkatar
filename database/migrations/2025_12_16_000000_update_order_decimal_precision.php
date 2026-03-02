<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Increase decimal precision to support 3 decimal places for monetary amounts
        // Use raw statements for compatibility across MySQL versions
        DB::statement("ALTER TABLE `orders` MODIFY `Delivery_Charge` DECIMAL(12,3) NOT NULL DEFAULT '0.000'");
        DB::statement("ALTER TABLE `orders` MODIFY `Sub_Total` DECIMAL(12,3) NOT NULL DEFAULT '0.000'");
        DB::statement("ALTER TABLE `orders` MODIFY `Tax` DECIMAL(12,3) NOT NULL DEFAULT '0.000'");
        DB::statement("ALTER TABLE `orders` MODIFY `Coupon_Amount` DECIMAL(12,3) NOT NULL DEFAULT '0.000'");
        DB::statement("ALTER TABLE `orders` MODIFY `Grand_Total` DECIMAL(12,3) NOT NULL DEFAULT '0.000'");

        // Update order details table prices to preserve precision
        if (Schema::hasTable('order_details')) {
            try {
                DB::statement("ALTER TABLE `order_details` MODIFY `Price` DECIMAL(12,3) NOT NULL DEFAULT '0.000'");
                DB::statement("ALTER TABLE `order_details` MODIFY `Total_Price` DECIMAL(12,3) NOT NULL DEFAULT '0.000'");
            } catch (\Exception $e) {
                // ignore if columns do not exist or already modified
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `orders` MODIFY `Delivery_Charge` DECIMAL(8,2) NOT NULL DEFAULT '0.00'");
        DB::statement("ALTER TABLE `orders` MODIFY `Sub_Total` DECIMAL(8,2) NOT NULL DEFAULT '0.00'");
        DB::statement("ALTER TABLE `orders` MODIFY `Tax` DECIMAL(8,2) NOT NULL DEFAULT '0.00'");
        DB::statement("ALTER TABLE `orders` MODIFY `Coupon_Amount` DECIMAL(8,2) NOT NULL DEFAULT '0.00'");
        DB::statement("ALTER TABLE `orders` MODIFY `Grand_Total` DECIMAL(8,2) NOT NULL DEFAULT '0.00'");

        if (Schema::hasTable('order_details')) {
            try {
                DB::statement("ALTER TABLE `order_details` MODIFY `Price` DECIMAL(8,2) NOT NULL DEFAULT '0.00'");
                DB::statement("ALTER TABLE `order_details` MODIFY `Total_Price` DECIMAL(8,2) NOT NULL DEFAULT '0.00'");
            } catch (\Exception $e) {
                // ignore
            }
        }
    }
};
