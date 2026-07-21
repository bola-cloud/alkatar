<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExcelProductsSeeder extends Seeder
{
    public function run()
    {
        // Clear Existing Products & Related Data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('products')->truncate();
        
        // Truncate sizes/weights to prevent old options from lingering
        if (\Illuminate\Support\Facades\Schema::hasTable('product_sizes')) {
            DB::table('product_sizes')->truncate();
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('product_weights')) {
            DB::table('product_weights')->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $defaultImage = 'card1.png'; // Sample image path found in DB

        // Categories Map
        // 1: Coffee Crops (محاصيل القهوة المختصة)
        // 2: Preparation Tools (معدات القهوة)
        // 6: Ready coffee (منتجات سريعة التحضير)

        $files = [
            [
                'path' => public_path('محاصيل القهوة المختصة 3.xlsx'),
                'category_id' => 1, 
                'type' => 'crops'
            ],
            [
                'path' => public_path('معدات القهوة (1).xlsx'),
                'category_id' => 2, 
                'type' => 'equipment'
            ],
            [
                'path' => public_path('منتجات سريعة التحضير.xlsx'),
                'category_id' => 6, 
                'type' => 'instant'
            ]
        ];

        foreach ($files as $file) {
            if (!file_exists($file['path'])) {
                $this->command->error("File not found: " . $file['path']);
                continue;
            }

            $data = \Maatwebsite\Excel\Facades\Excel::toArray(new class implements \Maatwebsite\Excel\Concerns\ToArray {
                public function array(array $array) { return $array; }
            }, $file['path']);

            $rows = $data[0];

            foreach ($rows as $index => $row) {
                // Skip empty rows
                $enName = trim($row[0] ?? '');
                $arName = trim($row[1] ?? '');

                if (empty($enName) && empty($arName)) continue;

                // Explicitly skip any headers
                if (
                    str_contains(strtolower($enName), 'crop name') || str_contains($arName, 'اسم المحصول') ||
                    str_contains(strtolower($enName), 'product name') || str_contains($arName, 'اسم المنتج') ||
                    str_contains(strtolower($enName), 'specialty coffee') || str_contains($arName, 'محاصيل القهوة') ||
                    str_contains(strtolower($enName), 'coffee equipment') || str_contains($arName, 'معدات') ||
                    str_contains(strtolower($enName), 'instant products') || str_contains($arName, 'منتجات سريعة')
                ) {
                    continue;
                }

                if ($file['type'] == 'crops') {

                    $price = $row[2] ?? 0;
                    $price = $row[2] ?? 0;
                    $weight = $row[4] ?? '';
                    $processingAr = $row[5] ?? '';
                    $processingEn = $row[6] ?? '';
                    $suggestionsAr = $row[7] ?? '';
                    $suggestionsEn = $row[8] ?? '';
                    $usesAr = $row[9] ?? '';
                    $usesEn = $row[10] ?? '';
                    $prepAr = $row[11] ?? '';
                    $prepEn = $row[12] ?? '';
                    $descAr = $row[13] ?? '';
                    $descEn = $row[14] ?? '';

                    // Append extra info to descriptions
                    $fullDescAr = "{$descAr}<br><br><strong>المعالجة:</strong> {$processingAr}<br><strong>الإيحاءات:</strong> {$suggestionsAr}<br><strong>الاستخدامات:</strong> {$usesAr}<br><strong>طريقة التحضير:</strong> {$prepAr}";
                    $fullDescEn = "{$descEn}<br><br><strong>Processing:</strong> {$processingEn}<br><strong>Suggestions:</strong> {$suggestionsEn}<br><strong>Uses:</strong> {$usesEn}<br><strong>Preparation:</strong> {$prepEn}";

                    Product::create([
                        'Category_Id' => $file['category_id'],
                        'en_Product_Name' => trim($enName) ?: trim($arName),
                        'fr_Product_Name' => trim($arName) ?: trim($enName),
                        'en_Product_Slug' => Str::slug(trim($enName) ?: trim($arName)) . '-' . rand(100, 999),
                        'fr_Product_Slug' => Str::slug(trim($arName) ?: trim($enName)) . '-' . rand(100, 999),
                        'en_Description' => $fullDescEn,
                        'fr_Description' => $fullDescAr,
                        'en_About' => '',
                        'fr_About' => '',
                        'ItemTag' => '',
                        'Price' => floatval($price),
                        'Quantity' => 100,
                        'Primary_Image' => $defaultImage,
                        'Status' => 1,
                        'product_type' => 1,
                        'en_ShippingReturn' => '',
                        'fr_ShippingReturn' => '',
                        'en_AdditionalInformation' => '',
                        'fr_AdditionalInformation' => '',
                        'Voucher' => 'no'
                    ]);

                } elseif ($file['type'] == 'equipment') {
                    $descAr = $row[2] ?? '';
                    $descAr = $row[2] ?? '';
                    $price = $row[3] ?? 0;
                    $descEn = $row[5] ?? '';

                    Product::create([
                        'Category_Id' => $file['category_id'],
                        'en_Product_Name' => trim($enName) ?: trim($arName),
                        'fr_Product_Name' => trim($arName) ?: trim($enName),
                        'en_Product_Slug' => Str::slug(trim($enName) ?: trim($arName)) . '-' . rand(100, 999),
                        'fr_Product_Slug' => Str::slug(trim($arName) ?: trim($enName)) . '-' . rand(100, 999),
                        'en_Description' => $descEn,
                        'fr_Description' => $descAr,
                        'en_About' => '',
                        'fr_About' => '',
                        'ItemTag' => '',
                        'Price' => floatval($price),
                        'Quantity' => 100,
                        'Primary_Image' => $defaultImage,
                        'Status' => 1,
                        'product_type' => 1,
                        'en_ShippingReturn' => '',
                        'fr_ShippingReturn' => '',
                        'en_AdditionalInformation' => '',
                        'fr_AdditionalInformation' => '',
                        'Voucher' => 'no'
                    ]);

                } elseif ($file['type'] == 'instant') {
                    $descEn = $row[2] ?? '';
                    $descEn = $row[2] ?? '';
                    $price = $row[3] ?? 0;
                    $descAr = $row[5] ?? '';
                    $prepAr = $row[6] ?? '';
                    $prepEn = $row[7] ?? '';

                    $fullDescAr = "{$descAr}<br><br><strong>طريقة التحضير:</strong> {$prepAr}";
                    $fullDescEn = "{$descEn}<br><br><strong>Preparation:</strong> {$prepEn}";

                    Product::create([
                        'Category_Id' => $file['category_id'],
                        'en_Product_Name' => trim($enName) ?: trim($arName),
                        'fr_Product_Name' => trim($arName) ?: trim($enName),
                        'en_Product_Slug' => Str::slug(trim($enName) ?: trim($arName)) . '-' . rand(100, 999),
                        'fr_Product_Slug' => Str::slug(trim($arName) ?: trim($enName)) . '-' . rand(100, 999),
                        'en_Description' => $fullDescEn,
                        'fr_Description' => $fullDescAr,
                        'en_About' => '',
                        'fr_About' => '',
                        'ItemTag' => '',
                        'Price' => floatval($price),
                        'Quantity' => 100,
                        'Primary_Image' => $defaultImage,
                        'Status' => 1,
                        'product_type' => 1,
                        'en_ShippingReturn' => '',
                        'fr_ShippingReturn' => '',
                        'en_AdditionalInformation' => '',
                        'fr_AdditionalInformation' => '',
                        'Voucher' => 'no'
                    ]);
                }
            }
            
            $this->command->info("Processed: " . basename($file['path']));
        }

        // Clean up unused categories
        $usedCategoryIds = Product::distinct()->pluck('Category_Id')->toArray();
        $deletedCategories = \App\Models\Admin\Category::whereNotIn('id', $usedCategoryIds)->delete();
        $this->command->info("Deleted {$deletedCategories} unused categories.");
    }
}
