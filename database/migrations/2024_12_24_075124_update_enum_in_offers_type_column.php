<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEnumInOffersTypeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE offers MODIFY COLUMN type ENUM('percentage_discount', 'fixed_discount', 'free_shipping', 'buy_x_get_z', 'total_bill_discount', 'free_shipping_with_total_bill') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE offers MODIFY COLUMN type ENUM('percentage_discount', 'fixed_discount', 'free_shipping', 'buy_x_get_z', 'total_bill_discount') NOT NULL");
    }
}
