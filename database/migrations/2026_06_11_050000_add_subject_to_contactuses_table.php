<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubjectToContactusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contactuses', function (Blueprint $table) {
            if (!Schema::hasColumn('contactuses', 'subject')) {
                $table->string('subject')->nullable()->after('Email');
            }
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
            if (Schema::hasColumn('contactuses', 'subject')) {
                $table->dropColumn('subject');
            }
        });
    }
}
