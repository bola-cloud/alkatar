<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class CreateExpertRequestsAndSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Create expert_requests table
        Schema::create('expert_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->text('message');
            $table->integer('status')->default(0); // 0 = Pending, 1 = Responded, 2 = Rejected/Cancelled
            $table->timestamps();
        });

        // 2. Insert default setting for experts_email
        Setting::firstOrCreate(
            ['slug' => 'experts_email'],
            ['value' => 'b2b@al-qatar.com']
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expert_requests');
        Setting::where('slug', 'experts_email')->delete();
    }
}
