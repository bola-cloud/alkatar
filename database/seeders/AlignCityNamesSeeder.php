<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\State;

class AlignCityNamesSeeder extends Seeder
{
    public function run()
    {
        // 1. Rename Cities to match Excel exactly
        $renames = [
            'Seeb' => 'Al Seeb',
            'Bawshar' => 'Baushar',
            'Muscat City' => 'Muscat',
            'Qurayyat' => 'Quriyat',
            'Al Musannah' => 'Al Musanaa',
            'Al Buraimi City' => 'Al Buraimi',
            'Mahdah' => 'Mahda',
            'Salalah' => 'Salalah', // Check if Sadah is mapped to Salalah in DB?
        ];

        foreach ($renames as $oldName => $newName) {
            $city = City::where('name_en', $oldName)->first();
            if ($city) {
                // Check if target name already exists (avoid duplicate error)
                $target = City::where('name_en', $newName)->first();
                if (!$target) {
                    $this->command->info("Renaming '$oldName' to '$newName'");
                    $city->update(['name_en' => $newName]);
                } else {
                    $this->command->warn("Target name '$newName' already exists. Skipping rename of '$oldName'.");
                }
            } else {
                $this->command->warn("City '$oldName' not found.");
            }
        }

        // 2. Sadah separation?
        // In python output: Excel has City:Sadah, Area:Sadah, State:Dhofar.
        // DB has City:Salalah, Area:Sadah.
        // Excel says Sadah is a Wilayat (City).
        // If DB put Sadah Area under Salalah City, that's a hierarchy mismatch.
        // I need to check if 'Sadah' City exists in DB.

        $sadahCity = City::where('name_en', 'Sadah')->first();
        if (!$sadahCity) {
            $this->command->info("Creating missing City 'Sadah'");
            $dhofar = State::where('name_en', 'Dhofar')->first();
            if ($dhofar) {
                $sadahCity = City::create([
                    'state_id' => $dhofar->id,
                    'name_en' => 'Sadah',
                    'name_ar' => 'سدح'
                ]);
            }
        }

        // Move Sadah Area to Sadah City if it exists and is currently under Salalah
        if ($sadahCity) {
            $sadahArea = \App\Models\Area::where('name_en', 'Sadah')->first();
            if ($sadahArea && $sadahArea->city_id != $sadahCity->id) {
                $this->command->info("Moving Area 'Sadah' to City 'Sadah'");
                $sadahArea->update(['city_id' => $sadahCity->id]);
            }
        }
    }
}
