<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('homepages')) {
            Schema::table('homepages', function (Blueprint $table) {
                $table->string('en_button_text')->nullable();
                $table->string('en_button_url')->nullable();
                $table->string('fr_button_text')->nullable();
                $table->string('fr_button_url')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('homepages')) {
            Schema::table('homepages', function (Blueprint $table) {
                $table->dropColumn(['en_button_text','en_button_url','fr_button_text','fr_button_url']);
            });
        }
    }
};
