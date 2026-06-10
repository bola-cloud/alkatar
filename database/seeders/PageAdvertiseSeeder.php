<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Advertise;

class PageAdvertiseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clean existing specific page ads
        Advertise::whereIn('location', ['coffee_crops', 'technical_tools'])->delete();

        // The 3 images that are on the page
        $images = [
            'assets/elketar/trail-box.png',
            'assets/elketar/ddd.png',
            'assets/elketar/become_partner_hero.png'
        ];

        // 1. Coffee Crops Slider Images
        foreach ($images as $index => $img) {
            Advertise::create([
                'image' => $img,
                'status' => 1,
                'location' => 'coffee_crops',
                'display_order' => $index,
                'en_title' => 'Coffee Crop Banner ' . ($index + 1),
                'ar_title' => 'اعلان محصول قهوة ' . ($index + 1),
                'Image_One' => '',
                'Image_Two' => '',
                'link' => '#',
            ]);
        }

        // 2. Technical Tools Slider Images
        foreach ($images as $index => $img) {
            Advertise::create([
                'image' => $img,
                'status' => 1,
                'location' => 'technical_tools',
                'display_order' => $index,
                'en_title' => 'Preparation Tool Banner ' . ($index + 1),
                'ar_title' => 'اعلان اداة تحضير ' . ($index + 1),
                'Image_One' => '',
                'Image_Two' => '',
                'link' => '#',
            ]);
        }
    }
}
