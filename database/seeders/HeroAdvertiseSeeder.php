<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Advertise;

class HeroAdvertiseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create three hero slides referencing the public new-design image
        $common = [
            'image' => 'new-design/images/bannar-big.png',
            'status' => 1,
            'location' => 'hero',
        ];

        Advertise::create(array_merge($common, [
            'en_title' => 'Fresh & Healthy',
            'en_subtitle' => 'Organic Food',
            'link' => '#',
            'display_order' => 0,
            'Image_One' => '',
            'Image_Two' => ''
        ]));

        Advertise::create(array_merge($common, [
            'en_title' => 'Seasonal Picks',
            'en_subtitle' => 'Best Quality',
            'link' => '#',
            'display_order' => 1,
            'Image_One' => '',
            'Image_Two' => ''
        ]));

        Advertise::create(array_merge($common, [
            'en_title' => 'Fresh Arrivals',
            'en_subtitle' => 'New Collections',
            'link' => '#',
            'display_order' => 2,
            'Image_One' => '',
            'Image_Two' => ''
        ]));
    }
}
