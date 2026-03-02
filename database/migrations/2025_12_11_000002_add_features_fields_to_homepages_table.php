<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('homepages')) {
            Schema::table('homepages', function (Blueprint $table) {
                // Four feature items (title + small description) for en and fr
                $table->string('en_feature_1_title')->nullable();
                $table->string('en_feature_1_desc')->nullable();
                $table->string('en_feature_2_title')->nullable();
                $table->string('en_feature_2_desc')->nullable();
                $table->string('en_feature_3_title')->nullable();
                $table->string('en_feature_3_desc')->nullable();
                $table->string('en_feature_4_title')->nullable();
                $table->string('en_feature_4_desc')->nullable();

                $table->string('fr_feature_1_title')->nullable();
                $table->string('fr_feature_1_desc')->nullable();
                $table->string('fr_feature_2_title')->nullable();
                $table->string('fr_feature_2_desc')->nullable();
                $table->string('fr_feature_3_title')->nullable();
                $table->string('fr_feature_3_desc')->nullable();
                $table->string('fr_feature_4_title')->nullable();
                $table->string('fr_feature_4_desc')->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('homepages')) {
            Schema::table('homepages', function (Blueprint $table) {
                $table->dropColumn([
                    'en_feature_1_title','en_feature_1_desc','en_feature_2_title','en_feature_2_desc',
                    'en_feature_3_title','en_feature_3_desc','en_feature_4_title','en_feature_4_desc',
                    'fr_feature_1_title','fr_feature_1_desc','fr_feature_2_title','fr_feature_2_desc',
                    'fr_feature_3_title','fr_feature_3_desc','fr_feature_4_title','fr_feature_4_desc'
                ]);
            });
        }
    }
};
