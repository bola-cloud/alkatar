<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gift_card_packages', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name_ar');
            $table->string('name_en');
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->decimal('price', 10, 3);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });

        // Seed default packages
        DB::table('gift_card_packages')->insert([
            [
                'key' => 'gold',
                'name_ar' => 'الباقة الذهبية',
                'name_en' => 'Gold Package',
                'description_ar' => 'رصيد إهداء بقيمة ٥٠٠ ر.ع',
                'description_en' => 'Gift credit with 500 OMR value',
                'price' => 500.000,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'silver',
                'name_ar' => 'الباقة الفضية',
                'name_en' => 'Silver Package',
                'description_ar' => 'رصيد إهداء بقيمة ٢٥٠ ر.ع',
                'description_en' => 'Gift credit with 250 OMR value',
                'price' => 250.000,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'bronze',
                'name_ar' => 'الباقة البرونزية',
                'name_en' => 'Bronze Package',
                'description_ar' => 'رصيد إهداء بقيمة ١٠٠ ر.ع',
                'description_en' => 'Gift credit with 100 OMR value',
                'price' => 100.000,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_card_packages');
    }
};
