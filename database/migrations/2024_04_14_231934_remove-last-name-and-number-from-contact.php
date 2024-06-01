<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveLastNameAndNumberFromContact extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contactuses', function (Blueprint $table) {
            $table->dropColumn('LastName');
            $table->dropColumn('ContactNumber');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contactuses', function (Blueprint $table) {
            $table->text('LastName')->nullable();
            $table->text('ContactNumber')->nullable();
        });
    }
}
