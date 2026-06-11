<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;

class AddContactMapSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Setting::firstOrCreate(
            ['slug' => 'contact_map_iframe'],
            ['value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d115918.42398539268!2d46.72186835!3d24.81381395!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2f03890d489399%3A0xba974d1c98e91f55!2sRiyadh%20Saudi%20Arabia!5e0!3m2!1sen!2ssa!4v1680000000000!5m2!1sen!2ssa']
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::where('slug', 'contact_map_iframe')->delete();
    }
}
