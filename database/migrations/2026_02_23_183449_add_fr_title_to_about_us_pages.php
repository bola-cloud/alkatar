<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFrTitleToAboutUsPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('about_us_pages', function (Blueprint $table) {
            $table->string('fr_Title')->nullable()->after('en_Title');
        });
    }

    public function down()
    {
        Schema::table('about_us_pages', function (Blueprint $table) {
            $table->dropColumn('fr_Title');
        });
    }
}
