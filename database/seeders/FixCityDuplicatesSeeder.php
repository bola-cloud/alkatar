<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\City;
use App\Models\Area;
use App\Models\DeliveryCharge;

class FixCityDuplicatesSeeder extends Seeder
{
    public function run()
    {
        // 1. Get all "New" cities (ID >= 88)
        $newCities = City::where('id', '>=', 88)->get();

        foreach ($newCities as $newCity) {
            // 2. Find matching "Old" city (ID < 88) in the SAME State
            // Normalization logic: lower case, remove 'al ', remove ' city'
            $newNameNorm = $this->normalize($newCity->name_en);

            // Fetch potential candidates in the same state
            $oldCities = City::where('id', '<', 88)
                ->where('state_id', $newCity->state_id)
                ->get();

            $match = null;
            foreach ($oldCities as $oldCity) {
                $oldNameNorm = $this->normalize($oldCity->name_en);

                // 1. Exact normalized match
                if ($newNameNorm === $oldNameNorm) {
                    $match = $oldCity;
                    break;
                }

                // 2. Levenshtein for typos (e.g. Qurayyat vs Quriyat)
                // Threshold: 3 seems safe for short names, but be careful.
                if (levenshtein($newNameNorm, $oldNameNorm) <= 3) {
                    $match = $oldCity;
                    break;
                }
            }

            if ($match) {
                $this->command->info("Merging '{$newCity->name_en}' (ID: {$newCity->id}) into '{$match->name_en}' (ID: {$match->id})");

                // 3. Move Areas to Old City
                Area::where('city_id', $newCity->id)->update(['city_id' => $match->id]);

                // 4. Update Delivery Charges linked to this New City
                // Note: These charges were for the Areas we just moved.
                // We must update their city_id to the Old City.
                DeliveryCharge::where('city_id', $newCity->id)->update(['city_id' => $match->id]);

                // 5. Delete the New City
                $newCity->delete();
            } else {
                $this->command->warn("No match found for New City: '{$newCity->name_en}' (ID: {$newCity->id}). Keeping it.");
            }
        }
    }

    private function normalize($name)
    {
        $name = strtolower($name);
        $name = str_replace('al ', '', $name);
        $name = str_replace(' city', '', $name);
        $name = str_replace('wilayat ', '', $name);
        $name = trim($name);
        return $name;
    }
}
