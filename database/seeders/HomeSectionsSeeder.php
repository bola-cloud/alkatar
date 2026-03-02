<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeSectionsSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $sections = [
            'newdesign_why_choose' => [
                'en' => [
                    'title' => "100% Trusted\nOrganic Food Store",
                    'lead' => 'Healthy & natural food for lovers of healthy food.',
                    'points' => [
                        'Healthy & natural food for lovers of healthy food.',
                        'Every day fresh and quality products for you.'
                    ],
                    'button' => ['text' => 'Shop Now', 'url' => url('/')],
                ],
                'ar' => [
                    'title' => "100% موثوق\nمتجر الأغذية العضوية",
                    'lead' => 'أغذية صحية وطبيعية لمحبي الطعام الصحي.',
                    'points' => [
                        'أغذية صحية وطبيعية لمحبي الطعام الصحي.',
                        'يومياً منتجات طازجة وعالية الجودة من أجلك.'
                    ],
                    'button' => ['text' => 'تسوق الآن', 'url' => url('/')],
                ]
            ],
            'newdesign_features' => [
                'en' => [
                    'items' => [
                        [ 'title' => 'Fast delivery within 20 min', 'desc' => 'Free shipping on all your order.', 'icon' => null ],
                        [ 'title' => 'Customer Support 24/7', 'desc' => 'Instant access to Support', 'icon' => null ],
                        [ 'title' => '100% Secure Payment', 'desc' => 'We ensure your money is safe', 'icon' => null ],
                        [ 'title' => 'Money-Back Guarantee', 'desc' => '30 Days Money-Back Guarantee', 'icon' => null ],
                    ]
                ],
                'ar' => [
                    'items' => [
                        [ 'title' => 'توصيل سريع خلال 20 دقيقة', 'desc' => 'شحن مجاني على جميع طلباتك.', 'icon' => null ],
                        [ 'title' => 'دعم العملاء 24/7', 'desc' => 'وصول فوري للدعم', 'icon' => null ],
                        [ 'title' => 'دفع آمن 100%', 'desc' => 'نضمن أمان أموالك', 'icon' => null ],
                        [ 'title' => 'ضمان استرجاع المال', 'desc' => 'ضمان استرجاع الأموال خلال 30 يوماً', 'icon' => null ],
                    ]
                ]
            ],
            'newdesign_sale_banner' => [
                'en' => [
                    'title' => 'Sale of the Month',
                    'lead' => "Best deals and limited time offers. Don't miss out!",
                    'button' => ['text' => 'Shop Now', 'url' => url('/')]
                ],
                'fr' => [
                    'title' => 'Vente du mois',
                    'lead' => 'Meilleures offres et promotions limitées. Ne les manquez pas!',
                    'button' => ['text' => 'Acheter maintenant', 'url' => url('/')]
                ]
            ],
            'newdesign_brands' => [
                'en' => ['title' => 'Our Brands'],
                'fr' => ['title' => 'Nos Marques']
            ]
        ];

        foreach ($sections as $key => $data) {
            $exists = DB::table('homepage_sections')->where('section_key', $key)->first();
            if ($exists) {
                DB::table('homepage_sections')->where('section_key', $key)->update([
                    'content_en' => json_encode($data['en'] ?? null),
                    'content_fr' => json_encode($data['fr'] ?? null),
                    'updated_at' => $now
                ]);
            } else {
                DB::table('homepage_sections')->insert([
                    'section_key' => $key,
                    'content_en' => json_encode($data['en'] ?? null),
                    'content_fr' => json_encode($data['fr'] ?? null),
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
            }
        }
    }
}
