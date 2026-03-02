<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('section_key')->unique()->comment('slug/key for the section e.g. newdesign_why_choose');
            $table->json('content_en')->nullable()->comment('JSON content for English');
            $table->json('content_fr')->nullable()->comment('JSON content for French');
            $table->string('image')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('homepage_sections');
    }
};
