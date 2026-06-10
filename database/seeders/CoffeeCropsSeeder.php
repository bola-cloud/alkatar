<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use Carbon\Carbon;

class CoffeeCropsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        // 1. Create or update the Coffee Crops category
        $category = Category::updateOrCreate(
            ['en_Category_Slug' => 'coffee-crops'],
            [
                'en_Category_Name' => 'Coffee Crops',
                'fr_Category_Name' => 'محاصيل القهوة',
                'fr_Category_Slug' => 'coffee-crops',
                'Status' => 1,
                'order' => 1,
            ]
        );

        // 1b. Create or update subcategories under this category
        $subEsc = \App\Models\Subcategory::updateOrCreate(
            ['category_id' => $category->id, 'name' => 'Espresso'],
            ['name_ar' => 'الإسبريسو', 'status' => 1]
        );

        $subDrip = \App\Models\Subcategory::updateOrCreate(
            ['category_id' => $category->id, 'name' => 'Drip Coffee'],
            ['name_ar' => 'القهوة المقطرة', 'status' => 1]
        );

        $subGrinder = \App\Models\Subcategory::updateOrCreate(
            ['category_id' => $category->id, 'name' => 'Grinders'],
            ['name_ar' => 'المطاحن', 'status' => 1]
        );

        $subWhole = \App\Models\Subcategory::updateOrCreate(
            ['category_id' => $category->id, 'name' => 'Whole Bean'],
            ['name_ar' => 'حبوب كاملة', 'status' => 1]
        );

        $subOther = \App\Models\Subcategory::updateOrCreate(
            ['category_id' => $category->id, 'name' => 'Other'],
            ['name_ar' => 'أخرى', 'status' => 1]
        );

        // 2. Create or update the HomepageSection for the Coffee Crops Slider
        DB::table('homepage_sections')->updateOrInsert(
            ['section_key' => 'newdesign_coffee_crops_slider'],
            [
                'content_en' => json_encode([
                    'images' => [
                        'assets/elketar/become_partner_hero.png',
                        'assets/elketar/hero_social.png',
                        'assets/elketar/trail-box.png'
                    ]
                ]),
                'content_fr' => json_encode([
                    'images' => [
                        'assets/elketar/become_partner_hero.png',
                        'assets/elketar/hero_social.png',
                        'assets/elketar/trail-box.png'
                    ]
                ]),
                'display_order' => 5,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        // 3. Create coffee crop products with subcategories
        $products = [
            [
                'en_Product_Name' => 'Brazil Santos',
                'fr_Product_Name' => 'برازيل سانتوس',
                'en_Product_Slug' => 'brazil-santos',
                'fr_Product_Slug' => 'brazil-santos-ar',
                'en_About' => 'Premium Single Origin Brazil Santos coffee',
                'fr_About' => 'قهوة برازيل سانتوس الفاخرة ذات المصدر الواحد',
                'Price' => 85.00,
                'Quantity' => 20,
                'en_Description' => "Source/Origin: Brazil\nRoast story: Medium roast\nType: Arabica\nNotes: Chocolate, Caramel\nDescription: A very smooth, medium body coffee with low acidity and rich chocolatey flavor notes.",
                'fr_Description' => "المصدر والمنشأ: البرازيل\nقصة التحميص: تحميص متوسط متوازن\nالنوع: أرابيكا ١٠٠٪\nالإيحاءات: شوكولاتة، كراميل\nالوصف: قهوة برازيلية ذات قوام متوازن وحلاوة طبيعية.",
                'Primary_Image' => 'product-image-1.png',
                'subcategory_id' => $subEsc->id,
            ],
            [
                'en_Product_Name' => 'Ethiopian Yirgacheffe',
                'fr_Product_Name' => 'إثيوبيا يرقاشيفي',
                'en_Product_Slug' => 'ethiopian-yirgacheffe',
                'fr_Product_Slug' => 'ethiopian-yirgacheffe-ar',
                'en_About' => 'Premium Ethiopian Yirgacheffe coffee',
                'fr_About' => 'قهوة إثيوبيا يرقاشيفي الفاخرة ذات الإيحاءات الزهرية',
                'Price' => 95.00,
                'Quantity' => 15,
                'en_Description' => "Source/Origin: Ethiopia\nRoast story: Light roast\nType: Arabica\nNotes: Floral, Citrus\nDescription: Floral aroma, sweet citrus notes with a tea-like delicate finish.",
                'fr_Description' => "المصدر والمنشأ: إثيوبيا\nقصة التحميص: تحميص خفيف\nالنوع: أرابيكا ١٠٠٪\nالإيحاءات: أزهار، حمضيات، شاي\nالوصف: قهوة إثيوبية تتميز بالنوتات الزهرية والحمضية المنعشة.",
                'Primary_Image' => 'product-image-2.png',
                'subcategory_id' => $subDrip->id,
            ],
            [
                'en_Product_Name' => 'Colombia Supremo',
                'fr_Product_Name' => 'كولومبيا سوبريمو',
                'en_Product_Slug' => 'colombia-supremo',
                'fr_Product_Slug' => 'colombia-supremo-ar',
                'en_About' => 'Premium Colombia Supremo coffee',
                'fr_About' => 'قهوة كولومبيا سوبريمو المتوازنة والغنية',
                'Price' => 90.00,
                'Quantity' => 25,
                'en_Description' => "Source/Origin: Colombia\nRoast story: Medium roast\nType: Arabica\nNotes: Caramel, Nuts\nDescription: Rich aroma, medium-high acidity, sweet caramel notes with a nutty finish.",
                'fr_Description' => "المصدر والمنشأ: كولومبيا\nقصة التحميص: تحميص متوسط\nالنوع: أرابيكا ١٠٠٪\nالإيحاءات: كراميل، مكسرات\nالوصف: قهوة كولومبية غنية برائحة زكية وقوام متوسط إلى ممتلئ.",
                'Primary_Image' => 'product-image-3.png',
                'subcategory_id' => $subEsc->id,
            ],
            [
                'en_Product_Name' => 'Yemen Harazi',
                'fr_Product_Name' => 'اليمن حرازي',
                'en_Product_Slug' => 'yemen-harazi',
                'fr_Product_Slug' => 'yemen-harazi-ar',
                'en_About' => 'Authentic Yemen Harazi coffee',
                'fr_About' => 'قهوة اليمن حرازي الأصيلة والنادرة',
                'Price' => 150.00,
                'Quantity' => 8,
                'en_Description' => "Source/Origin: Yemen\nRoast story: Medium-Dark roast\nType: Arabica\nNotes: Spices, Chocolate\nDescription: Complex profile, chocolate undertones with exotic spicy notes.",
                'fr_Description' => "المصدر والمنشأ: اليمن\nقصة التحميص: تحميص متوسط إلى غامق\nالنوع: أرابيكا ١٠٠٪\nالإيحاءات: توابل، شوكولاتة داكنة\nالوصف: قهوة يمنية عريقة بنكهات معقدة وفريدة.",
                'Primary_Image' => 'product-image-4.png',
                'subcategory_id' => $subDrip->id,
            ],
            [
                'en_Product_Name' => 'Costa Rica Tarrazu',
                'fr_Product_Name' => 'كوستاريكا تارازو',
                'en_Product_Slug' => 'costa-rica-tarrazu',
                'fr_Product_Slug' => 'costa-rica-tarrazu-ar',
                'en_About' => 'Premium Costa Rica Tarrazu coffee',
                'fr_About' => 'قهوة كوستاريكا تارازو بنكهات الفواكه المجففة',
                'Price' => 110.00,
                'Quantity' => 12,
                'en_Description' => "Source/Origin: Costa Rica\nRoast story: Medium-Light roast\nType: Arabica\nNotes: Honey, Fruits\nDescription: Sweet honey-like body, crisp acidity with hints of tropical fruits.",
                'fr_Description' => "المصدر والمنشأ: كوستاريكا\nقصة التحميص: تحميص متوسط خفيف\nالنوع: أرابيكا ١٠٠٪\nالإيحاءات: عسل، فواكه استوائية\nالوصف: قهوة كوستاريكية بحلاوة طبيعية وقوام ناعم.",
                'Primary_Image' => 'product-image-5.png',
                'subcategory_id' => $subDrip->id,
            ],
        ];

        foreach ($products as $pData) {
            Product::updateOrCreate(
                ['en_Product_Slug' => $pData['en_Product_Slug']],
                [
                    'Category_Id' => $category->id,
                    'Brand_Id' => 1,
                    'en_Product_Name' => $pData['en_Product_Name'],
                    'fr_Product_Name' => $pData['fr_Product_Name'],
                    'fr_Product_Slug' => $pData['fr_Product_Slug'],
                    'en_About' => $pData['en_About'],
                    'fr_About' => $pData['fr_About'],
                    'Price' => $pData['Price'],
                    'Discount' => 0.00,
                    'Discount_Price' => $pData['Price'],
                    'Quantity' => $pData['Quantity'],
                    'Sold' => 0.00,
                    'Primary_Image' => $pData['Primary_Image'],
                    'Image2' => $pData['Primary_Image'],
                    'Image3' => $pData['Primary_Image'],
                    'Image4' => $pData['Primary_Image'],
                    'Image5' => $pData['Primary_Image'],
                    'type' => 1,
                    'Status' => 1,
                    'en_Description' => $pData['en_Description'],
                    'fr_Description' => $pData['fr_Description'],
                    'en_ShippingReturn' => 'Shipping & Return details',
                    'fr_ShippingReturn' => 'تفاصيل الشحن والإرجاع',
                    'en_AdditionalInformation' => 'Additional Information details',
                    'fr_AdditionalInformation' => 'تفاصيل المعلومات الإضافية',
                    'Voucher' => 'Voucher-' . rand(1000, 9999),
                    'subcategory_id' => $pData['subcategory_id'],
                ]
            );
        }
    }
}
