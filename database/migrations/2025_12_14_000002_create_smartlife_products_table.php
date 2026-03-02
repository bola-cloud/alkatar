<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmartlifeProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smartlife_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('smartlife_id')->unique();
            $table->string('barcode')->nullable();
            $table->string('name');
            $table->decimal('price', 10, 3)->default(0);
            $table->decimal('cost', 10, 3)->default(0);
            $table->integer('quantity')->default(0);
            $table->integer('alert_quantity')->default(0);
            $table->string('type')->nullable();
            $table->string('unit')->nullable();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->string('thumb')->nullable();
            $table->string('image')->nullable();
            $table->boolean('show_pos')->default(true);
            $table->timestamps();

            $table->index('barcode');
            $table->index('smartlife_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('smartlife_products');
    }
}
