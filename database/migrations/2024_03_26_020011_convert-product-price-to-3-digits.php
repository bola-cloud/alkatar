<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConvertProductPriceTo3Digits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('Price', 8, 3)->change();
            $table->decimal('Discount', 8, 3)->change();
            $table->decimal('Discount_Price', 8, 3)->change();
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
            $table->decimal('Price', 8, 2)->change();
            $table->decimal('Discount', 8, 2)->change();
            $table->decimal('Discount_Price', 8, 2)->change();
        });
    }
}
