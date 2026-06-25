<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomBoxTemplate;

class CustomBoxTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name_en' => 'Premium Green Box',
                'name_ar' => 'البوكس الأخضر الفاخر',
                'description_en' => 'Elegant classic look in our signature green theme.',
                'description_ar' => 'تصميم كلاسيكي مميز بهوية اللون الأخضر الخاصة بنا.',
                'color_code' => '#1A4231',
                'price' => 2.000,
                'is_active' => true,
            ],
            [
                'name_en' => 'Elegant White Box',
                'name_ar' => 'البوكس الأبيض الأنيق',
                'description_en' => 'Modern clean design for minimalist luxury.',
                'description_ar' => 'تصميم عصري جذاب وناصع يعبر عن الفخامة الهادئة.',
                'color_code' => '#FFFFFF',
                'price' => 2.000,
                'is_active' => true,
            ],
            [
                'name_en' => 'Classic Kraft Box',
                'name_ar' => 'البوكس الكرافت الكلاسيكي',
                'description_en' => 'Rustic organic style crafted from recycled materials.',
                'description_ar' => 'مظهر خشبي ريفي فاخر مصنوع من خامات كرافت طبيعية.',
                'color_code' => '#C8AD7F',
                'price' => 1.500,
                'is_active' => true,
            ],
            [
                'name_en' => 'Royal Black Box',
                'name_ar' => 'البوكس الأسود الملكي',
                'description_en' => 'Dignified dark mode aesthetic for special celebrations.',
                'description_ar' => 'فخامة ملكية بلون أسود داكن للمناسبات الخاصة والراقية.',
                'color_code' => '#111111',
                'price' => 2.500,
                'is_active' => true,
            ],
            [
                'name_en' => 'Festive Gold Box',
                'name_ar' => 'البوكس الذهبي الاحتفالي',
                'description_en' => 'Bright shiny luxury design for gifting and holidays.',
                'description_ar' => 'تصميم ذهبي احتفالي لامع مخصص للهدايا والأعياد الفخمة.',
                'color_code' => '#D4AF37',
                'price' => 3.000,
                'is_active' => true,
            ],
        ];

        foreach ($templates as $tmpl) {
            CustomBoxTemplate::updateOrCreate(
                ['name_en' => $tmpl['name_en']],
                $tmpl
            );
        }
    }
}
