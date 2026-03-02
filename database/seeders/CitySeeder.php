<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = base_path('Copy of Master City list.xlsx');
        if (!file_exists($file)) {
            $this->command->error("Excel file not found at: $file");
            return;
        }

        $rows = \Maatwebsite\Excel\Facades\Excel::toCollection(new class implements \Maatwebsite\Excel\Concerns\ToCollection {
            public function collection(\Illuminate\Support\Collection $rows)
            {
            }
        }, $file);

        $sheet = $rows->first(); // Get first sheet
        $header = $sheet->first()->toArray(); // Assuming first row is header
        $data = $sheet->slice(1);

        // Map header indices
        $idx_gov = 4; // Governerate
        $idx_gov_ar = 5;
        $idx_wil = 2; // Wilayat
        $idx_wil_ar = 3;
        $idx_city = 0; // City
        $idx_city_ar = 1;

        $oman = \App\Models\Country::where('name_en', 'Oman')->first();
        if (!$oman) {
            $oman = \App\Models\Country::create(['name_en' => 'Oman', 'name_ar' => 'عُمان']);
        }

        foreach ($data as $row) {
            // Access by index since it's a collection of arrays/collections
            $row = $row->values(); // Reset keys to be safe

            // Extract values (trimming whitespace)
            $gov_en = trim($row[$idx_gov] ?? '');
            $gov_ar = trim($row[$idx_gov_ar] ?? '');
            $wil_en = trim($row[$idx_wil] ?? '');
            $wil_ar = trim($row[$idx_wil_ar] ?? '');
            $area_en = trim($row[$idx_city] ?? '');
            $area_ar = trim($row[$idx_city_ar] ?? '');

            if (empty($gov_en) || empty($wil_en))
                continue;

            // 1. State (Governorate)
            $state = \App\Models\State::firstOrCreate(
                ['country_id' => $oman->id, 'name_en' => $gov_en],
                ['name_ar' => $gov_ar]
            );

            // 2. City (Wilayat)
            $city = \App\Models\City::firstOrCreate(
                ['state_id' => $state->id, 'name_en' => $wil_en],
                ['name_ar' => $wil_ar]
            );

            // 3. Area (City from Excel)
            if (!empty($area_en)) {
                $area = \App\Models\Area::firstOrCreate(
                    ['city_id' => $city->id, 'name_en' => $area_en],
                    ['name_ar' => $area_ar]
                );

                // 4. Delivery Charge (Default 0)
                \App\Models\DeliveryCharge::firstOrCreate(
                    [
                        'country' => 'Oman',
                        'state_id' => $state->id,
                        'city_id' => $city->id,
                        'area_id' => $area->id
                    ],
                    [
                        'charge' => 0,
                        'status' => 1,
                    ]
                );
            }
        }

        $this->command->info('City seeding completed successfully.');
    }
}
