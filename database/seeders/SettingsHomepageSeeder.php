<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SettingsHomepageSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // Helper to insert or update a setting
        $put = function($key, $value) use ($now) {
            $exists = DB::table('settings')->where('slug', $key)->first();
            if ($exists) {
                DB::table('settings')->where('slug', $key)->update(['value' => json_encode($value), 'updated_at' => $now]);
            } else {
                DB::table('settings')->insert(['slug' => $key, 'value' => json_encode($value), 'created_at' => $now, 'updated_at' => $now]);
            }
        };

        // Why Choose Us (key: home_newdesign_why_choose)
        $put('home_newdesign_why_choose', [
            'en' => [
                'title' => "100% Trusted\nOrganic Food Store",
                'lead' => 'Healthy & natural food for lovers of healthy food.',
                'points' => [
                    'Healthy & natural food for lovers of healthy food.',
                    'Every day fresh and quality products for you.'
                ],
                'button' => [ 'text' => 'Shop Now', 'url' => url('/') ],
                'image' => null
            ],
            'ar' => [
                'title' => "100% موثوق\nمتجر الأغذية العضوية",
                'lead' => 'أغذية صحية وطبيعية لمحبي الطعام الصحي.',
                'points' => [
                    'أغذية صحية وطبيعية لمحبي الطعام الصحي.',
                    'يومياً منتجات طازجة وعالية الجودة من أجلك.'
                ],
                'button' => [ 'text' => 'تسوق الآن', 'url' => url('/') ],
                'image' => null
            ]
        ]);

        // Features (key: home_newdesign_features)
        $put('home_newdesign_features', [
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
        ]);

        // Sale banner (key: home_newdesign_sale_banner)
        $put('home_newdesign_sale_banner', [
            'en' => [
                'title' => 'Sale of the Month',
                'lead' => "Best deals and limited time offers. Don't miss out!",
                'button' => ['text' => 'Shop Now', 'url' => url('/')],
                'image' => null
            ],
            'ar' => [
                'title' => 'عرض الشهر',
                'lead' => 'أفضل الصفقات والعروض المحدودة. لا تفوتها!',
                'button' => ['text' => 'تسوق الآن', 'url' => url('/')],
                'image' => null
            ]
        ]);
    }
}
