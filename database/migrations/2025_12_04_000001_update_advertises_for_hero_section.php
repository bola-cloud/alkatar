<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateAdvertisesForHeroSection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('advertises')) {
            // Add new columns if they don't exist
            Schema::table('advertises', function (Blueprint $table) {
                // keep backward compatibility with existing Image_One/Image_Two
                if (!Schema::hasColumn('advertises', 'image')) {
                    $table->string('image')->nullable()->after('Image_Two');
                }
                if (!Schema::hasColumn('advertises', 'en_title')) {
                    $table->string('en_title')->nullable()->after('image');
                }
                if (!Schema::hasColumn('advertises', 'en_subtitle')) {
                    $table->text('en_subtitle')->nullable()->after('en_title');
                }
                // create arabic columns for localization (ar_*)
                if (!Schema::hasColumn('advertises', 'ar_title')) {
                    $table->string('ar_title')->nullable()->after('en_subtitle');
                }
                if (!Schema::hasColumn('advertises', 'ar_subtitle')) {
                    $table->text('ar_subtitle')->nullable()->after('ar_title');
                }
                if (!Schema::hasColumn('advertises', 'link')) {
                    $table->string('link')->nullable()->after('ar_subtitle');
                }
                if (!Schema::hasColumn('advertises', 'display_order')) {
                    $table->integer('display_order')->default(0)->after('link');
                }
                if (!Schema::hasColumn('advertises', 'status')) {
                    $table->boolean('status')->default(1)->after('display_order');
                }
                if (!Schema::hasColumn('advertises', 'location')) {
                    $table->string('location')->default('hero')->after('status');
                }
            });

            // If old fr_* columns exist, copy their values into ar_* then drop fr_*
            if (Schema::hasColumn('advertises', 'fr_title')) {
                // copy fr_title -> ar_title (only when fr_title has data)
                DB::statement("UPDATE `advertises` SET `ar_title` = `fr_title` WHERE `fr_title` IS NOT NULL");
                Schema::table('advertises', function (Blueprint $table) {
                    if (Schema::hasColumn('advertises', 'fr_title')) {
                        $table->dropColumn('fr_title');
                    }
                });
            }
            if (Schema::hasColumn('advertises', 'fr_subtitle')) {
                DB::statement("UPDATE `advertises` SET `ar_subtitle` = `fr_subtitle` WHERE `fr_subtitle` IS NOT NULL");
                Schema::table('advertises', function (Blueprint $table) {
                    if (Schema::hasColumn('advertises', 'fr_subtitle')) {
                        $table->dropColumn('fr_subtitle');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('advertises')) {
            Schema::table('advertises', function (Blueprint $table) {
                if (Schema::hasColumn('advertises', 'location')) {
                    $table->dropColumn('location');
                }
                if (Schema::hasColumn('advertises', 'status')) {
                    $table->dropColumn('status');
                }
                if (Schema::hasColumn('advertises', 'display_order')) {
                    $table->dropColumn('display_order');
                }
                if (Schema::hasColumn('advertises', 'link')) {
                    $table->dropColumn('link');
                }
                if (Schema::hasColumn('advertises', 'ar_subtitle')) {
                    $table->dropColumn('ar_subtitle');
                }
                if (Schema::hasColumn('advertises', 'ar_title')) {
                    $table->dropColumn('ar_title');
                }
                if (Schema::hasColumn('advertises', 'en_subtitle')) {
                    $table->dropColumn('en_subtitle');
                }
                if (Schema::hasColumn('advertises', 'en_title')) {
                    $table->dropColumn('en_title');
                }
                if (Schema::hasColumn('advertises', 'image')) {
                    $table->dropColumn('image');
                }
            });
        }
    }
}
