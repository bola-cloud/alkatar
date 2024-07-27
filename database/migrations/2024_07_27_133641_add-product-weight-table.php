<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductWeightTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weight_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Product_Id');
            $table->foreign('Product_Id')->references('id')->on('products')->onDelete('cascade');
            $table->decimal('price',10,3)->default(0)->comment('Product weight additional price');
            $table->string('weight', 10)->comment('Product weight in grams');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weight_product');
    }
}
