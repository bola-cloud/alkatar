<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Create `user_addresses` and try to migrate data from legacy `billings`/`shippings` tables if present.
     */
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->string('label')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->boolean('is_default')->default(false);
            $table->enum('address_type', ['shipping','billing','both'])->default('both');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Attempt to migrate data from legacy billing/shipping tables if they exist
        try {
            if (Schema::hasTable('billings')) {
                $billings = DB::table('billings')->get();
                foreach ($billings as $b) {
                    DB::table('user_addresses')->insert([
                        'user_id' => $b->User_Id ?? ($b->user_id ?? null),
                        'label' => 'Billing',
                        'recipient_name' => $b->Name ?? null,
                        'phone' => $b->Phone ?? null,
                        'address_line1' => $b->Street ?? ($b->street ?? ''),
                        'address_line2' => null,
                        'city' => $b->City ?? null,
                        'state' => $b->State ?? null,
                        'postal_code' => $b->Zipcode ?? null,
                        'country' => $b->Country ?? null,
                        'is_default' => true,
                        'address_type' => 'billing',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            if (Schema::hasTable('shippings')) {
                $shippings = DB::table('shippings')->get();
                foreach ($shippings as $s) {
                    DB::table('user_addresses')->insert([
                        'user_id' => $s->User_Id ?? ($s->user_id ?? null),
                        'label' => 'Shipping',
                        'recipient_name' => $s->Name ?? null,
                        'phone' => $s->Phone ?? null,
                        'address_line1' => $s->Street ?? ($s->street ?? ''),
                        'address_line2' => null,
                        'city' => $s->City ?? null,
                        'state' => $s->State ?? null,
                        'postal_code' => $s->Zipcode ?? null,
                        'country' => $s->Country ?? null,
                        'is_default' => false,
                        'address_type' => 'shipping',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // migration of legacy rows is best-effort — don't fail the migration for unexpected legacy schema
            info('user_addresses migration: legacy copy skipped or failed: '.$e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
