<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToAdditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('additions', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1)->comment('1: Active, 0: Inactive')->after('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('additions', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
