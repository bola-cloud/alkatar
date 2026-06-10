<?php

namespace Database\Seeders;

use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StoreMasterSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('order_details')->truncate();
        DB::table('orders')->truncate();
        DB::table('color_product')->truncate();
        DB::table('size_product')->truncate();
        DB::table('product_combos')->truncate();
        DB::table('product_tags')->truncate();
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::table('subcategories')->truncate();
        Schema::enableForeignKeyConstraints();

        // Get first brand or set null
        $brand = Brand::first();
        $brandId = $brand ? $brand->id : null;

        // Seed Categories
        $crops = Category::create([
            'en_Category_Name' => 'Coffee Crops',
            'en_Category_Slug' => 'coffee-crops',
            'fr_Category_Name' => 'محاصيل القهوة',
            'fr_Category_Slug' => 'coffee-crops',
            'Status' => 1,
            'show_on_home' => 1,
            'order' => 1,
        ]);

        $tools = Category::create([
            'en_Category_Name' => 'Preparation Tools',
            'en_Category_Slug' => 'preparation-tools',
            'fr_Category_Name' => 'معدات التحضير',
            'fr_Category_Slug' => 'preparation-tools',
            'Status' => 1,
            'show_on_home' => 1,
            'order' => 2,
        ]);

        // Seed subcategories under Tools Category
        $subDripTool = \App\Models\Subcategory::create([
            'category_id' => $tools->id,
            'name' => 'Drip Coffee',
            'name_ar' => 'القهوة المقطرة',
            'status' => 1
        ]);

        $subEspressoTool = \App\Models\Subcategory::create([
            'category_id' => $tools->id,
            'name' => 'Espresso',
            'name_ar' => 'الإسبريسو',
            'status' => 1
        ]);

        $subGrinderTool = \App\Models\Subcategory::create([
            'category_id' => $tools->id,
            'name' => 'Grinders',
            'name_ar' => 'المطاحن',
            'status' => 1
        ]);

        $subAccTool = \App\Models\Subcategory::create([
            'category_id' => $tools->id,
            'name' => 'Accessories',
            'name_ar' => 'الإكسسوارات',
            'status' => 1
        ]);

        $boxes = Category::create([
            'en_Category_Name' => 'Trial Boxes',
            'en_Category_Slug' => 'trial-boxes',
            'fr_Category_Name' => 'بوكسات التجربة',
            'fr_Category_Slug' => 'trial-boxes',
            'Status' => 1,
            'show_on_home' => 1,
            'order' => 3,
        ]);

        $accessories = Category::create([
            'en_Category_Name' => 'Accessories',
            'en_Category_Slug' => 'accessories',
            'fr_Category_Name' => 'الإكسسوارات',
            'fr_Category_Slug' => 'accessories',
            'Status' => 1,
            'show_on_home' => 1,
            'order' => 4,
        ]);

        $packages = Category::create([
            'en_Category_Name' => 'Packages',
            'en_Category_Slug' => 'packages',
            'fr_Category_Name' => 'الباقات المتكاملة',
            'fr_Category_Slug' => 'packages',
            'Status' => 1,
            'show_on_home' => 1,
            'order' => 5,
        ]);

        // Base product template to avoid missing field errors
        $baseProduct = [
            'Brand_Id' => $brandId,
            'Sold' => 0.00,
            'Image3' => 'n-a',
            'Image4' => 'n-a',
            'Image5' => 'n-a',
            'digital_type' => null,
            'digital_link' => null,
            'digital_file' => null,
            'license_name' => null,
            'license_key' => null,
            'affiliate_link' => null,
            'type' => 1,
            'product_type' => 'Standard',
            'show_pos' => 1,
            'synced_from_smartlife' => 0,
            'points' => 0,
            'subcategory_id' => null,
            'en_ShippingReturn' => 'Default shipping & return information.',
            'fr_ShippingReturn' => 'معلومات الشحن والاسترجاع الافتراضية.',
            'en_AdditionalInformation' => 'Additional information about the product.',
            'fr_AdditionalInformation' => 'معلومات إضافية عن المنتج.',
        ];

        // Seed Products
        // 1. Hario V60 Drip Kit
        Product::create(array_merge($baseProduct, [
            'Category_Id' => $tools->id,
            'subcategory_id' => $subDripTool->id,
            'en_Product_Name' => 'Hario V60 Drip Kit',
            'fr_Product_Name' => 'طقم تنقيط Hario V60',
            'en_Product_Slug' => 'hario-v60-drip-kit',
            'fr_Product_Slug' => 'hario-v60-drip-kit',
            'en_About' => 'Balanced extraction highlighting bright acidity.',
            'fr_About' => 'استخلاص متوازن يبرز حمضية القهوة المشرقة.',
            'ItemTag' => 'Beginner',
            'Price' => 145.000,
            'Discount' => 0.00,
            'Discount_Price' => 0.000,
            'Quantity' => 50,
            'Primary_Image' => 'card1.png',
            'Image2' => 'card1.png',
            'Status' => 1,
            'Featured_Product' => 1,
            'Best_Selling' => 1,
            'New_Arrival' => 1,
            'en_Description' => 'Hario V60 Drip Kit is the perfect starting tool for filter coffee enthusiasts. Features a spiral design for optimal airflow.',
            'fr_Description' => 'طقم تنقيط هاريو V60 هو الأداة المثالية لبدء تحضير القهوة المقطرة. يتميز بتصميم حلزوني لتدفق هواء مثالي.',
            'Voucher' => 'VCH' . rand(1000, 9999),
        ]));

        // 2. Comandante C40 Grinder
        Product::create(array_merge($baseProduct, [
            'Category_Id' => $tools->id,
            'subcategory_id' => $subGrinderTool->id,
            'en_Product_Name' => 'Comandante C40 Grinder',
            'fr_Product_Name' => 'طاحونة Comandante C40',
            'en_Product_Slug' => 'comandante-c40-grinder',
            'fr_Product_Slug' => 'comandante-c40-grinder',
            'en_About' => 'High precision in grind size consistency.',
            'fr_About' => 'دقة متناهية في تناسق جزيئات الطحن.',
            'ItemTag' => 'Professional',
            'Price' => 220.000,
            'Discount' => 0.00,
            'Discount_Price' => 0.000,
            'Quantity' => 30,
            'Primary_Image' => 'card2.png',
            'Image2' => 'card2.png',
            'Status' => 1,
            'Featured_Product' => 1,
            'Best_Selling' => 1,
            'New_Arrival' => 1,
            'en_Description' => 'Comandante C40 is globally recognized as the best hand grinder for specialty coffee. Nitro Blade burrs for ultimate durability.',
            'fr_Description' => 'طاحونة كوماندر C40 هي الأفضل عالمياً لطحن القهوة المختصة بدقة عالية. تروس نيتروبليد عالية الصلابة.',
            'Voucher' => 'VCH' . rand(1000, 9999),
        ]));

        // 3. Hario V60 Drip Kit White
        Product::create(array_merge($baseProduct, [
            'Category_Id' => $tools->id,
            'subcategory_id' => $subDripTool->id,
            'en_Product_Name' => 'Hario V60 Drip Kit White',
            'fr_Product_Name' => 'طقم تنقيط هاريو V60 - أبيض',
            'en_Product_Slug' => 'hario-v60-drip-kit-white',
            'fr_Product_Slug' => 'hario-v60-drip-kit-white',
            'en_About' => 'Balanced extraction highlighting bright acidity.',
            'fr_About' => 'استخلاص متوازن يبرز حمضية القهوة المشرقة.',
            'ItemTag' => 'Professional',
            'Price' => 145.000,
            'Discount' => 0.00,
            'Discount_Price' => 0.000,
            'Quantity' => 40,
            'Primary_Image' => 'card3.png',
            'Image2' => 'card3.png',
            'Status' => 1,
            'Featured_Product' => 1,
            'Best_Selling' => 0,
            'New_Arrival' => 1,
            'en_Description' => 'Elegant white ceramic V60 drip kit for professional brewing.',
            'fr_Description' => 'طقم تنقيط سيراميك أبيض أنيق هاريو V60 لتحضير احترافي.',
            'Voucher' => 'VCH' . rand(1000, 9999),
        ]));

        // 4. Brazil Santos Coffee Beans
        Product::create(array_merge($baseProduct, [
            'Category_Id' => $crops->id,
            'en_Product_Name' => 'Brazil Santos Coffee Beans',
            'fr_Product_Name' => 'قهوة البرازيل - سانتوس 🇧🇷',
            'en_Product_Slug' => 'brazil-santos-coffee-beans',
            'fr_Product_Slug' => 'brazil-santos-coffee-beans',
            'en_About' => 'An aromatic experience with floral and citrus notes.',
            'fr_About' => 'تجربة عطرية بنوتات الزهور والحمضيات.',
            'ItemTag' => 'Beginner',
            'Price' => 85.000,
            'Discount' => 25.00,
            'Discount_Price' => 110.000,
            'Quantity' => 100,
            'Primary_Image' => 'Background.png',
            'Image2' => 'Background.png',
            'Status' => 1,
            'Featured_Product' => 1,
            'Best_Selling' => 1,
            'New_Arrival' => 1,
            'en_Description' => 'Source/Origin: Ethiopia highlands, volcanic soil. Roast story: Medium roast. Type: Arabica. Notes: Chocolate, hazelnut, cocoa. Description: Rich and heavy body, perfect for espresso.',
            'fr_Description' => 'المصدر والمنشأ: من مرتفعات إثيوبيا الشاهقة، حيث التربة البركانية الغنية. قصة التحميص: تحميص متوسط يبرز التوازن المثالي بين القوام والحموضة. النوع: أرابيكا. الإيحاءات: شوكولاتة - بندق - كاكاو. الوصف: قهوة غنية وقوامها ثقيل، مثالية لعشاق الإسبريسو.',
            'Voucher' => 'VCH' . rand(1000, 9999),
        ]));

        // 5. Elketar Tasting Box
        Product::create(array_merge($baseProduct, [
            'Category_Id' => $boxes->id,
            'en_Product_Name' => 'Elketar Tasting Box',
            'fr_Product_Name' => 'صندوق القطار للتذوق',
            'en_Product_Slug' => 'elketar-tasting-box',
            'fr_Product_Slug' => 'elketar-tasting-box',
            'en_About' => '3 types of crops (100g each).',
            'fr_About' => '٣ أنواع محاصيل (100جم لكل نوع).',
            'ItemTag' => 'Beginner',
            'Price' => 145.000,
            'Discount' => 0.00,
            'Discount_Price' => 0.000,
            'Quantity' => 20,
            'Primary_Image' => 'trail-box.png',
            'Image2' => 'trail-box.png',
            'Status' => 1,
            'Featured_Product' => 1,
            'Best_Selling' => 1,
            'New_Arrival' => 1,
            'en_Description' => 'The perfect box to start training your sensory tasting abilities with different coffee origins.',
            'fr_Description' => 'البوكس المثالي لبدء تدريب قدرات التذوق الحسية لديك مع مصادر قهوة مختلفة.',
            'Voucher' => 'VCH' . rand(1000, 9999),
        ]));

        // 6. Ethiopia Hambela Coffee Beans
        Product::create(array_merge($baseProduct, [
            'Category_Id' => $crops->id,
            'en_Product_Name' => 'Ethiopia Hambela Coffee Beans',
            'fr_Product_Name' => 'محصول إثيوبيا - هامبيلا',
            'en_Product_Slug' => 'ethiopia-hambela-coffee-beans',
            'fr_Product_Slug' => 'ethiopia-hambela-coffee-beans',
            'en_About' => 'Whole beans | 250g.',
            'fr_About' => 'حبوب كاملة | 250 جرام.',
            'ItemTag' => 'Professional',
            'Price' => 65.000,
            'Discount' => 0.00,
            'Discount_Price' => 0.000,
            'Quantity' => 80,
            'Primary_Image' => 'Background (1).png',
            'Image2' => 'Background (1).png',
            'Status' => 1,
            'Featured_Product' => 1,
            'Best_Selling' => 1,
            'New_Arrival' => 1,
            'en_Description' => 'Ethiopian Hambela with rich fruity notes and premium quality.',
            'fr_Description' => 'قهوة إثيوبيا هامبيلا ذات الإيحاءات الفاكهية المميزة والجودة الفاخرة.',
            'Voucher' => 'VCH' . rand(1000, 9999),
        ]));

        // 7. V60 Beginner Package / باقة المبتدئ V60
        Product::create(array_merge($baseProduct, [
            'Category_Id' => $packages->id,
            'en_Product_Name' => 'V60 Beginner Package',
            'fr_Product_Name' => 'باقة المبتدئ V60',
            'en_Product_Slug' => 'v60-beginner-package',
            'fr_Product_Slug' => 'v60-beginner-package',
            'en_About' => 'Includes: V60 tool, 100 paper filters, glass server, and a 250g specialty coffee bag.',
            'fr_About' => 'تشمل: أداة V60، 100 فلاتر ورقي، سيرفر زجاجي، وكيس قهوة مختصة 250 جرام من اختيارنا.',
            'ItemTag' => 'Beginner',
            'Price' => 350.000,
            'Discount' => 20.00,
            'Discount_Price' => 280.000,
            'Quantity' => 50,
            'Primary_Image' => 'trail-box.png',
            'Image2' => 'trail-box.png',
            'Status' => 1,
            'Featured_Product' => 1,
            'Best_Selling' => 1,
            'New_Arrival' => 1,
            'product_type' => 'Combo',
            'en_Description' => 'V60 Beginner Package is designed for those starting their specialty coffee journey.',
            'fr_Description' => 'تم تصميم باقة المبتدئ V60 لمساعدتك في بدء رحلتك في عالم القهوة المختصة بكل سهولة.',
            'Voucher' => 'VCH' . rand(1000, 9999),
        ]));

        // 8. Advanced Drip Package / باقة التقطير المتقدمة
        Product::create(array_merge($baseProduct, [
            'Category_Id' => $packages->id,
            'en_Product_Name' => 'Advanced Drip Package',
            'fr_Product_Name' => 'باقة التقطير المتقدمة',
            'en_Product_Slug' => 'advanced-drip-package',
            'fr_Product_Slug' => 'advanced-drip-package',
            'en_About' => 'Includes: Smart thermal kettle, premium manual grinder, and precision scale with timer.',
            'fr_About' => 'تشمل: غلاية حرارية ذكية، طاحونة يدوية فاخرة، وميزان دقيق مع مؤقت.',
            'ItemTag' => 'Professional',
            'Price' => 1850.000,
            'Discount' => 0.00,
            'Discount_Price' => 1850.000,
            'Quantity' => 30,
            'Primary_Image' => 'ddd.png',
            'Image2' => 'ddd.png',
            'Status' => 1,
            'Featured_Product' => 1,
            'Best_Selling' => 1,
            'New_Arrival' => 1,
            'product_type' => 'Combo',
            'en_Description' => 'Advanced Drip Package for professional brewing, featuring a smart temperature control kettle.',
            'fr_Description' => 'باقة التقطير المتقدمة للمحترفين وعشاق تحضير القهوة بدقة عالية.',
            'Voucher' => 'VCH' . rand(1000, 9999),
        ]));
    }
}
