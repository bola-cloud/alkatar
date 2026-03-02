<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Area;
use App\Models\DeliveryCharge;
use App\Models\State;

class MuscatAreasSeeder extends Seeder
{
    public function run()
    {
        // Helper to find parent IDs
        $state = State::where('name_en', 'Muscat')->first();
        if (!$state) {
            $this->command->error("State 'Muscat' not found. Please seed cities first.");
            return;
        }

        // We want the City (Wilayat) named 'Muscat'
        // Be careful to find the one we deduplicated to (ID 45 usually)
        $city = City::where('name_en', 'Muscat City')->where('state_id', $state->id)->first();

        // Fallback if name is just 'Muscat' (database might vary depending on seed run)
        if (!$city) {
            $city = City::where('name_en', 'Muscat')->where('state_id', $state->id)->first();
        }

        if (!$city) {
            $this->command->error("City (Wilayat) 'Muscat' not found!");
            return;
        }

        $this->command->info("Seeding areas for City: {$city->name_en} (ID: {$city->id})");

        // List of Areas in Muscat Wilayat not in Excel
        $areas = [
            ['en' => 'Sidab', 'ar' => 'سداب'],
            ['en' => 'Al Bustan', 'ar' => 'البستان'],
            ['en' => 'Qantab', 'ar' => 'قنتب'],
            ['en' => 'Riyam', 'ar' => 'ريام'],
            ['en' => 'Kalbuh', 'ar' => 'كلبوه'],
            ['en' => 'Yeti', 'ar' => 'يتي'],
            ['en' => 'Al Jissah', 'ar' => 'الجصة'],
            ['en' => 'Al Khairan', 'ar' => 'الخيران'],
            ['en' => 'As Sifah', 'ar' => 'السيفة'],
            ['en' => 'Haramel', 'ar' => 'حرامل'],
        ];

        foreach ($areas as $areaData) {
            $area = Area::firstOrCreate(
                ['city_id' => $city->id, 'name_en' => $areaData['en']],
                ['name_ar' => $areaData['ar']]
            );

            // Ensure delivery charge exists
            DeliveryCharge::firstOrCreate(
                [
                    'state_id' => $state->id,
                    'city_id' => $city->id,
                    'area_id' => $area->id
                ],
                [
                    'country' => 'Oman',
                    'charge' => 0, // Default charge
                    'status' => 1,
                ]
            );
        }

        $this->command->info("Seeded " . count($areas) . " areas for Muscat.");
    }
}
