<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Use raw SQL to avoid requiring doctrine/dbal for ->change()
        try {
            DB::statement('ALTER TABLE `cutomer_services` MODIFY `en_description` LONGTEXT');
            DB::statement('ALTER TABLE `cutomer_services` MODIFY `fr_description` LONGTEXT');
        } catch (\Exception $e) {
            // swallow and log — migration may fail if table doesn't exist yet
            info('Migration convert_cutomer_services_descriptions_to_longtext failed: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            DB::statement('ALTER TABLE `cutomer_services` MODIFY `en_description` TEXT');
            DB::statement('ALTER TABLE `cutomer_services` MODIFY `fr_description` TEXT');
        } catch (\Exception $e) {
            info('Rollback convert_cutomer_services_descriptions_to_longtext failed: ' . $e->getMessage());
        }
    }
};
