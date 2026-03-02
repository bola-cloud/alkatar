<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (Schema::hasTable('advertises')) {
            Schema::table('advertises', function (Blueprint $table) {
                if (!Schema::hasColumn('advertises', 'en_small_description')) {
                    $table->text('en_small_description')->nullable();
                }
                if (!Schema::hasColumn('advertises', 'ar_small_description')) {
                    $table->text('ar_small_description')->nullable();
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('advertises')) {
            Schema::table('advertises', function (Blueprint $table) {
                if (Schema::hasColumn('advertises', 'en_small_description')) {
                    $table->dropColumn('en_small_description');
                }
                if (Schema::hasColumn('advertises', 'ar_small_description')) {
                    $table->dropColumn('ar_small_description');
                }
            });
        }
    }
};
