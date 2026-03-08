<?php
use App\Models\Admin\Product;
use Illuminate\Support\Str;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $product = new Product();
    $product->smartlife_id = '999999';
    $product->barcode = '999999';
    $product->en_Product_Name = 'Test';
    $product->fr_Product_Name = 'Test';
    $product->Price = 10;
    $product->cost = 5;
    $product->Quantity = 10;
    $product->alert_quantity = 2;
    $product->unit = 'PC';
    $product->product_type = 'Standard';
    $product->type = 1;
    $product->Status = 0;
    $product->show_pos = 1;
    $product->synced_from_smartlife = 1;
    $product->en_Description = 'Test';
    $product->fr_Description = 'Test';
    $product->en_About = 'Test';
    $product->fr_About = 'Test';
    $product->Primary_Image = 'prod.png';
    $product->Category_Id = 1; // Assuming 1 exists
    $product->Discount = 0;
    $product->Discount_Price = 0;
    $product->Sold = 0;
    $product->Featured_Product = 0;
    $product->Best_Selling = 0;
    $product->New_Arrival = 0;
    $product->Today_Special = 0;
    $product->On_Sale = 1;
    $product->en_ShippingReturn = 'Standard';
    $product->fr_ShippingReturn = 'Standard';
    $product->en_AdditionalInformation = 'Standard';
    $product->fr_AdditionalInformation = 'Standard';
    $product->Voucher = '0';
    $product->en_Product_Slug = 'test-999999';
    $product->fr_Product_Slug = 'test-999999';
    
    $product->save();
    echo "Success!";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
