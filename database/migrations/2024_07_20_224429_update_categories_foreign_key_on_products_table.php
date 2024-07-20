<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCategoriesForeignKeyOnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(["Category_Id"]);
            $table->foreign('Category_Id')->references('id')->on('categories')->onDelete('set null');
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
            $table->dropForeign(['Category_Id']);
            $table->foreign('Category_Id')->references('id')->on('categories')->onDelete('cascade');
        });
    }
}
