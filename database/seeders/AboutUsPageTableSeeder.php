<?php

namespace Database\Seeders;

use App\Models\Admin\SiteContent\AboutUsPage;
use Illuminate\Database\Seeder;

class AboutUsPageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Programmatically copy original images from assets/elketar to uploaded_files/about_us_page
        $destDir = public_path('uploaded_files/about_us_page/');
        if (!file_exists($destDir)) {
            mkdir($destDir, 0777, true);
        }
        
        $srcDir = public_path('assets/elketar/');
        $filesToCopy = ['about_roaster.png', 'coffee.png', 'latee.png'];
        
        foreach ($filesToCopy as $file) {
            if (file_exists($srcDir . $file)) {
                copy($srcDir . $file, $destDir . $file);
            }
        }

        // Truncate or delete existing record with location 'about_us' to clean seed it.
        AboutUsPage::where('Location', 'about_us')->delete();

        AboutUsPage::create([
            'Location' => 'about_us',
            'Image' => 'about_roaster.png',
            
            // Hero
            'en_Title' => 'The Katar Story: More than just a cup of coffee',
            'fr_Title' => 'قصة القطار: أكثر من مجرد كوب قهوة',
            'en_Subtitle' => "We don't just sell coffee, we take you on a journey from the farm to the cup, where authenticity meets modernity in every sip.",
            'fr_Subtitle' => 'نحن لا نبيع القهوة فحسب، بل نصحبكم في رحلة من المزرعة إلى الكوب، حيث تلتقي الأصالة بالحداثة في كل رشفة.',

            // Vision
            'en_vision_label' => 'Our Vision',
            'fr_vision_label' => 'رؤيتنا',
            'en_Title_One' => 'To be the premier destination for specialty coffee lovers in the region.',
            'fr_Title_One' => 'أن نكون الوجهة الأولى لعشاق القهوة المختصة في المنطقة.',
            'en_Description_One' => 'We seek to redefine the specialty coffee concept through continuous innovation and maintaining the highest international quality standards, creating a unique sensory experience for every customer.',
            'fr_Description_One' => 'نسعى لتغيير مفهوم القهوة المختصة من خلال الابتكار المستمر والحفاظ على أعلى معايير الجودة العالمية، لنخلق تجربة حسية فريدة لكل عميل.',

            // Mission
            'en_mission_label' => 'Our Mission',
            'fr_mission_label' => 'رسالتنا',
            'en_Title_Two' => 'Spreading a culture of excellence and sustainability.',
            'fr_Title_Two' => 'نشر ثقافة التميز والاستدامة.',
            'en_Description_Two' => 'Our mission is to empower coffee farmers and deliver a product that respects both the environment and the consumer, with a focus on continuous education for our team and customers on the art of coffee roasting and brewing.',
            'fr_Description_Two' => 'رسالتنا هي تمكين مزارعي القهوة وتقديم منتج يحترم البيئة والمستهلك، مع التركيز على التعليم المستمر لفريقنا وعملائنا حول فنون تحميص وتحضير القهوة.',

            // Experience Badge
            'experience_years' => '10+',
            'en_experience_text' => 'Years of Experience',
            'fr_experience_text' => 'سنوات من الخبرة',

            // Core Values
            'en_values_title' => 'Our Core Values',
            'fr_values_title' => 'قيمنا الجوهرية',
            'en_values_subtitle' => 'The principles that guide us in every step we take towards excellence.',
            'fr_values_subtitle' => 'المبادئ التي تقودنا في كل خطوة نخطوها نحو التميز.',

            // Value 1
            'en_value_one_title' => 'Community',
            'fr_value_one_title' => 'المجتمع',
            'en_value_one_description' => 'Al-Katar is a hub for creators; we support local talents and create space for connection.',
            'fr_value_one_description' => 'القطار هو ملتقى للمبدعين، نحن ندعم المواهب المحلية ونخلق مساحة للتواصل.',

            // Value 2
            'en_value_two_title' => 'Education',
            'fr_value_two_title' => 'التعليم',
            'en_value_two_description' => 'We believe in sharing knowledge, from barista courses to coffee tasting workshops for our customers.',
            'fr_value_two_description' => 'نؤمن بمشاركة المعرفة، من دورات الباريستا إلى ورش عمل تذوق القهوة لعملائنا.',

            // Value 3
            'en_value_three_title' => 'Sustainability',
            'fr_value_three_title' => 'الاستدامة',
            'en_value_three_description' => 'We commit to fair trade practices that support farmers and protect the environment for future generations.',
            'fr_value_three_description' => 'نلتزم بممارسات تجارية عادلة تدعم المزارعين وتحافظ على البيئة للأجيال القادمة.',

            // Value 4
            'en_value_four_title' => 'Quality',
            'fr_value_four_title' => 'الجودة',
            'en_value_four_description' => 'We never compromise on the quality of our crops, selecting only the top 5% of global coffee production.',
            'fr_value_four_description' => 'لا نساوم أبداً على جودة محاصيلنا، حيث نختار أفضل 5% من إنتاج القهوة العالمي.',

            // Why Al-Katar
            'en_why_title' => 'What distinguishes Al-Katar?',
            'fr_why_title' => 'ما الذي يميز القطار؟',
            'en_why_subtitle' => 'The secret of our excellence lies in the "journey of the bean". We don\'t just import coffee; we personally travel to farms in Ethiopia, Colombia, and Brazil to build direct relationships with farmers.',
            'fr_why_subtitle' => 'يكمن سر تميزنا في "رحلة الحبة". نحن لا نستورد القهوة فحسب، بل نسافر شخصياً إلى مزارع إثيوبيا، كولومبيا، والبرازيل لبناء علاقات مباشرة مع المزارعين.',
            
            'en_why_item_one' => 'Daily roasting in small batches to guarantee freshness.',
            'fr_why_item_one' => 'تحميص يومي بدفعات صغيرة لضمان الطزاجة.',
            'en_why_item_two' => 'Innovative roasting techniques that highlight unique aromatic notes.',
            'fr_why_item_two' => 'تقنيات تحميص مبتكرة تبرز النوتات العطرية الفريدة.',
            'en_why_item_three' => 'Complete traceability of bean origin and harvest date.',
            'fr_why_item_three' => 'تتبع كامل لمصدر الحبة وتاريخ حصادها.',
            'why_image_one' => 'coffee.png',
            'why_image_two' => 'latee.png',

            // CTA
            'en_cta_title' => 'Ready to start your journey with us?',
            'fr_cta_title' => 'هل أنت مستعد لبدء رحلتك معنا؟',
            'en_cta_btn_crops' => 'Discover Our Crops',
            'fr_cta_btn_crops' => 'اكتشف محاصيلنا',
            'en_cta_btn_expert' => 'Ask an Expert',
            'fr_cta_btn_expert' => 'استعن بخبير',
        ]);
    }
}
