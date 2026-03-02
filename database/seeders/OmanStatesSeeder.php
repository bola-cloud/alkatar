<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\DeliveryCharge;

class OmanStatesSeeder extends Seeder
{
    public function run()
    {
        $oman = Country::create([
            'name_en' => 'Oman',
            'name_ar' => 'عُمان',
        ]);

        $governorates = [
            [
                'name_en' => 'Muscat',
                'name_ar' => 'مسقط',
                'cities' => [
                    ['name_en' => 'Muscat City', 'name_ar' => 'مسقط'],
                    ['name_en' => 'Seeb', 'name_ar' => 'السيب'],
                    ['name_en' => 'Muttrah', 'name_ar' => 'مطرح'],
                    ['name_en' => 'Bawshar', 'name_ar' => 'بوشر'],
                    ['name_en' => 'Al Amerat', 'name_ar' => 'العامرات'],
                    ['name_en' => 'Qurayyat', 'name_ar' => 'قريات'],
                ]
            ],
            [
                'name_en' => 'Dhofar',
                'name_ar' => 'ظفار',
                'cities' => [
                    ['name_en' => 'Salalah', 'name_ar' => 'صلالة'],
                    ['name_en' => 'Mirbat', 'name_ar' => 'مرباط'],
                    ['name_en' => 'Taqah', 'name_ar' => 'طاقة'],
                    ['name_en' => 'Rakhyut', 'name_ar' => 'رخيوت'],
                ]
            ],
            [
                'name_en' => 'Musandam',
                'name_ar' => 'مسندم',
                'cities' => [
                    ['name_en' => 'Khasab', 'name_ar' => 'خصب'],
                    ['name_en' => 'Bukha', 'name_ar' => 'بخاء'],
                    ['name_en' => 'Dibba Al-Baya', 'name_ar' => 'دبا البيعة'],
                    ['name_en' => 'Madha', 'name_ar' => 'مدحاء'],
                ]
            ],
            [
                'name_en' => 'Al Buraimi',
                'name_ar' => 'البريمي',
                'cities' => [
                    ['name_en' => 'Al Buraimi City', 'name_ar' => 'مدينة البريمي'],
                    ['name_en' => 'Mahdah', 'name_ar' => 'محضة'],
                    ['name_en' => 'Al Sinaiyah', 'name_ar' => 'الصناعية'],
                ]
            ],
            [
                'name_en' => 'Ad Dakhiliyah',
                'name_ar' => 'الداخلية',
                'cities' => [
                    ['name_en' => 'Nizwa', 'name_ar' => 'نزوى'],
                    ['name_en' => 'Bahla', 'name_ar' => 'بهلاء'],
                    ['name_en' => 'Samail', 'name_ar' => 'سمائل'],
                    ['name_en' => 'Adam', 'name_ar' => 'أدم'],
                ]
            ],
            [
                'name_en' => 'Al Batinah North',
                'name_ar' => 'شمال الباطنة',
                'cities' => [
                    ['name_en' => 'Sohar', 'name_ar' => 'صحار'],
                    ['name_en' => 'Saham', 'name_ar' => 'صحم'],
                    ['name_en' => 'Liwa', 'name_ar' => 'لوى'],
                    ['name_en' => 'Shinas', 'name_ar' => 'شناص'],
                ]
            ],
            [
                'name_en' => 'Al Batinah South',
                'name_ar' => 'جنوب الباطنة',
                'cities' => [
                    ['name_en' => 'Rustaq', 'name_ar' => 'الرستاق'],
                    ['name_en' => 'Barka', 'name_ar' => 'بركاء'],
                    ['name_en' => 'Al Musannah', 'name_ar' => 'المصنعة'],
                    ['name_en' => 'Nakhal', 'name_ar' => 'نخل'],
                ]
            ],
            [
                'name_en' => 'Ash Sharqiyah North',
                'name_ar' => 'شمال الشرقية',
                'cities' => [
                    ['name_en' => 'Ibra', 'name_ar' => 'إبراء'],
                    ['name_en' => 'Bidiyah', 'name_ar' => 'بدية'],
                    ['name_en' => 'Al Mudhaibi', 'name_ar' => 'المضيبي'],
                    ['name_en' => 'Dima Wa at Taiyyin', 'name_ar' => 'دماء والطائيين'],
                ]
            ],
            [
                'name_en' => 'Ash Sharqiyah South',
                'name_ar' => 'جنوب الشرقية',
                'cities' => [
                    ['name_en' => 'Sur', 'name_ar' => 'صور'],
                    ['name_en' => 'Jalan Bani Bu Ali', 'name_ar' => 'جعلان بني بو علي'],
                    ['name_en' => 'Al Kamil Wal Wafi', 'name_ar' => 'الكامل والوافي'],
                    ['name_en' => 'Masirah', 'name_ar' => 'مصيرة'],
                ]
            ],
            [
                'name_en' => 'Ad Dhahirah',
                'name_ar' => 'الظاهرة',
                'cities' => [
                    ['name_en' => 'Ibri', 'name_ar' => 'عبري'],
                    ['name_en' => 'Yanqul', 'name_ar' => 'ينقل'],
                    ['name_en' => 'Dhank', 'name_ar' => 'ضنك'],
                ]
            ],
            [
                'name_en' => 'Al Wusta',
                'name_ar' => 'الوسطى',
                'cities' => [
                    ['name_en' => 'Haima', 'name_ar' => 'هيماء'],
                    ['name_en' => 'Duqm', 'name_ar' => 'الدقم'],
                    ['name_en' => 'Mahout', 'name_ar' => 'محوت'],
                ]
            ],
        ];

        // default charge assigned to each city (adjust as needed)
        $defaultCharge = 0.0;

        foreach ($governorates as $governorateData) {
            $governorate = $oman->states()->create([
                'name_en' => $governorateData['name_en'],
                'name_ar' => $governorateData['name_ar'],
            ]);

            foreach ($governorateData['cities'] as $cityData) {
                // create the city and capture instance
                $city = $governorate->cities()->create($cityData);

                // add a delivery charge record for this city if one doesn't already exist
                if ($city && !DeliveryCharge::where('city_id', $city->id)->exists()) {
                    // Match admin creation: only insert fields submitted by admin form
                    DeliveryCharge::create([
                        'country' => $oman->name_en,
                        'charge' => $defaultCharge,
                        'city_id' => $city->id,
                        'state_id' => $governorate->id,
                    ]);
                }
            }
        }
    }
}
