<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomFieldsToAboutUsPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('about_us_pages', function (Blueprint $table) {
            $table->string('experience_years')->nullable();
            $table->string('en_experience_text')->nullable();
            $table->string('fr_experience_text')->nullable();
            $table->string('en_vision_label')->nullable();
            $table->string('fr_vision_label')->nullable();
            $table->string('en_mission_label')->nullable();
            $table->string('fr_mission_label')->nullable();
            $table->string('en_values_title')->nullable();
            $table->string('fr_values_title')->nullable();
            $table->string('en_values_subtitle')->nullable();
            $table->string('fr_values_subtitle')->nullable();
            
            $table->string('en_value_one_title')->nullable();
            $table->string('fr_value_one_title')->nullable();
            $table->text('en_value_one_description')->nullable();
            $table->text('fr_value_one_description')->nullable();
            
            $table->string('en_value_two_title')->nullable();
            $table->string('fr_value_two_title')->nullable();
            $table->text('en_value_two_description')->nullable();
            $table->text('fr_value_two_description')->nullable();
            
            $table->string('en_value_three_title')->nullable();
            $table->string('fr_value_three_title')->nullable();
            $table->text('en_value_three_description')->nullable();
            $table->text('fr_value_three_description')->nullable();
            
            $table->string('en_value_four_title')->nullable();
            $table->string('fr_value_four_title')->nullable();
            $table->text('en_value_four_description')->nullable();
            $table->text('fr_value_four_description')->nullable();
            
            $table->string('en_why_title')->nullable();
            $table->string('fr_why_title')->nullable();
            $table->text('en_why_subtitle')->nullable();
            $table->text('fr_why_subtitle')->nullable();
            
            $table->string('en_why_item_one')->nullable();
            $table->string('fr_why_item_one')->nullable();
            $table->string('en_why_item_two')->nullable();
            $table->string('fr_why_item_two')->nullable();
            $table->string('en_why_item_three')->nullable();
            $table->string('fr_why_item_three')->nullable();
            
            $table->string('en_cta_title')->nullable();
            $table->string('fr_cta_title')->nullable();
            $table->string('en_cta_btn_crops')->nullable();
            $table->string('fr_cta_btn_crops')->nullable();
            $table->string('en_cta_btn_expert')->nullable();
            $table->string('fr_cta_btn_expert')->nullable();
            
            $table->string('why_image_one')->nullable();
            $table->string('why_image_two')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('about_us_pages', function (Blueprint $table) {
            $table->dropColumn([
                'experience_years',
                'en_experience_text',
                'fr_experience_text',
                'en_vision_label',
                'fr_vision_label',
                'en_mission_label',
                'fr_mission_label',
                'en_values_title',
                'fr_values_title',
                'en_values_subtitle',
                'fr_values_subtitle',
                'en_value_one_title',
                'fr_value_one_title',
                'en_value_one_description',
                'fr_value_one_description',
                'en_value_two_title',
                'fr_value_two_title',
                'en_value_two_description',
                'fr_value_two_description',
                'en_value_three_title',
                'fr_value_three_title',
                'en_value_three_description',
                'fr_value_three_description',
                'en_value_four_title',
                'fr_value_four_title',
                'en_value_four_description',
                'fr_value_four_description',
                'en_why_title',
                'fr_why_title',
                'en_why_subtitle',
                'fr_why_subtitle',
                'en_why_item_one',
                'fr_why_item_one',
                'en_why_item_two',
                'fr_why_item_two',
                'en_why_item_three',
                'fr_why_item_three',
                'en_cta_title',
                'fr_cta_title',
                'en_cta_btn_crops',
                'fr_cta_btn_crops',
                'en_cta_btn_expert',
                'fr_cta_btn_expert',
                'why_image_one',
                'why_image_two'
            ]);
        });
    }
}
