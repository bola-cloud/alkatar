<?php

namespace App\Console\Commands;

use App\Models\Admin\Product;
use App\Models\SmartLifeProduct;
use App\Services\SmartLifeErpService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SyncSmartLifeProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smartlife:sync-products
                            {--limit=100 : Number of products to fetch per page}
                            {--shadow-only : Sync to shadow table only, not main products table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync products from SmartLife ERP to local database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!config('smartlife.sync_enabled')) {
            $this->error('SmartLife sync is disabled in configuration.');
            return 1;
        }

        $this->info('Starting SmartLife product sync...');

        $service = app(SmartLifeErpService::class);

        // Test connection first
        if (!$service->testConnection()) {
            $this->error('Failed to connect to SmartLife ERP API. Check logs for details.');
            return 1;
        }

        $this->info('Connection successful. Fetching data...');

        $limit = $this->option('limit');
        $shadowOnly = $this->option('shadow-only');

        // Fetch all products from SmartLife
        $products = $service->getAllProducts($limit);

        if (empty($products)) {
            $this->warn('No products retrieved from SmartLife ERP.');
            return 0;
        }

        // Step 0: Sync Categories first to ensure correct mapping
        if (!$shadowOnly) {
            $this->info('Syncing categories from API...');
            $apiCategories = $service->getCategories();
            if ($apiCategories) {
                $catData = $apiCategories['data'] ?? $apiCategories;
                if (is_array($catData)) {
                    $this->syncCategoriesFromApi($catData);
                }
            }

            $this->info('Syncing categories from product data...');
            $this->syncCategoriesFromProducts($products);
        }

        $this->info('Retrieved ' . count($products) . ' products from SmartLife ERP.');

        $progressBar = $this->output->createProgressBar(count($products));
        $progressBar->start();

        $successCount = 0;
        $errorCount = 0;
        $updatedCount = 0;
        $createdCount = 0;
        $comboDataToSync = []; // Collect combo data for a second pass

        foreach ($products as $productData) {
            try {
                // Check if product is Combo/Group and missing items, then fetch details
                if (
                    ($productData['type'] === 'Combo' || $productData['type'] === 'تجميعي') &&
                    empty($productData['combo_items']) && empty($productData['combo_products'])
                ) {

                    $this->info("Fetching details for Combo: " . ($productData['name'] ?? 'Unknown'));
                    $details = $service->getProductDetails($productData['id']);

                    if ($details) {
                        if (!empty($details['combo_items'])) {
                            $productData['combo_items'] = $details['combo_items'];
                        } elseif (!empty($details['combo_products'])) {
                            $productData['combo_items'] = $details['combo_products'];
                        }
                    }
                }

                // Sync to shadow table (smartlife_products)
                $smartLifeProduct = SmartLifeProduct::syncFromSmartLife($productData);

                if ($smartLifeProduct && !$shadowOnly) {
                    // Sync to main products table (without items relationship yet)
                    $result = $this->syncToMainProductsTable($smartLifeProduct, $productData);

                    if ($result['success']) {
                        $successCount++;
                        if ($result['created']) {
                            $createdCount++;
                        } else {
                            $updatedCount++;
                        }

                        // If it's a combo, store its items for the second pass
                        if ($smartLifeProduct->type === 'Combo' || $smartLifeProduct->type === 'تجميعي') {
                            $comboDataToSync[] = [
                                'smartlife_id' => $smartLifeProduct->smartlife_id,
                                'combo_items' => $productData['combo_items'] ?? []
                            ];
                        }
                    } else {
                        $errorCount++;
                    }
                } elseif ($smartLifeProduct) {
                    $successCount++;
                }

                $progressBar->advance();
            } catch (\Exception $e) {
                // ... (error handling)
                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $this->newLine(1);

        // Second Pass: Sync Combo Relationships now that all products are imported
        if (!$shadowOnly && !empty($comboDataToSync)) {
            $this->info('Processing combo relationships (Second Pass)...');
            foreach ($comboDataToSync as $combo) {
                $product = Product::where('smartlife_id', $combo['smartlife_id'])->first();
                if ($product && !empty($combo['combo_items'])) {
                    $this->syncComboItems($product, $combo['combo_items']);
                }
            }
        }
        $this->newLine(1);

        // Handle deleted items from SmartLife
        if (!$shadowOnly) {
            $this->info('Checking for deleted items in SmartLife...');
            $deletionStats = $this->handleDeletedItems($products);
            $this->info("Products soft-deleted: {$deletionStats['products']}");
            $this->info("Categories soft-deleted: {$deletionStats['categories']}");
        }

        $this->info("Sync completed!");

        // Persist last sync time to DB so it works via Worker/CLI too
        \App\Models\Setting::updateOrCreate(
            ['slug' => 'last_smartlife_sync'],
            ['value' => now()]
        );

        $this->info("Successful: $successCount");
        if (!$shadowOnly) {
            $this->info("Created: $createdCount");
            $this->info("Updated: $updatedCount");
        }
        $this->info("Errors: $errorCount");

        if ($errorCount > 0) {
            $this->warn("Check logs for error details: storage/logs/laravel.log");
        }

        return 0;
    }

    /**
     * Sync SmartLife product to main products table
     */
    private function syncToMainProductsTable(SmartLifeProduct $smartLifeProduct, array $productData)
    {
        try {
            // Find or create product by smartlife_id or barcode
            $product = Product::withTrashed()
                ->where('smartlife_id', $smartLifeProduct->smartlife_id)
                ->orWhere('barcode', $smartLifeProduct->barcode)
                ->first();

            $isNew = !$product;

            if (!$product) {
                $product = new Product();
            } else if ($product->trashed()) {
                $product->restore();
                $isNew = false;
            }

            // Map SmartLife data to Product fields
            $product->smartlife_id = $smartLifeProduct->smartlife_id;
            $product->barcode = $smartLifeProduct->barcode;

            // Product names (ERP name is usually Arabic)
            $erpName = trim($smartLifeProduct->name ?? 'Unnamed Product');
            
            // Always update Arabic name from SmartLife
            $product->fr_Product_Name = $erpName;
            // Only set English name if it is currently empty, allowing manual translations to persist
            if (empty($product->en_Product_Name)) {
                $product->en_Product_Name = $erpName;
            }

            // Explicitly set slugs if they are empty
            if (empty($product->en_Product_Slug)) {
                $product->en_Product_Slug = Str::slug($product->en_Product_Name) ?: 'product-' . $smartLifeProduct->smartlife_id;
            }
            if (empty($product->fr_Product_Slug)) {
                $product->fr_Product_Slug = Str::slug($product->fr_Product_Name) ?: 'product-ar-' . $smartLifeProduct->smartlife_id;
            }

            // Pricing
            $product->Price = $smartLifeProduct->price;
            $product->cost = $smartLifeProduct->cost;

            // Quantity and unit
            $qty = $smartLifeProduct->quantity;

            // Handle Combo Quantity Fallback: If combo qty is 0, try to find a related standard product
            if (($smartLifeProduct->type === 'Combo' || $smartLifeProduct->type === 'تجميعي') && $qty <= 0) {
                // Determine search term (e.g. "طماط عمان" from "طماط عمان كرتون")
                $name = $smartLifeProduct->name;
                $parts = explode(' ', $name);
                if (count($parts) >= 2) {
                    $searchTerm = $parts[0] . ' ' . $parts[1];
                    // Search for a Standard product with similar name that has stock
                    $related = Product::where('product_type', 'Standard')
                        ->where('fr_Product_Name', 'LIKE', $searchTerm . '%')
                        ->where('Quantity', '>', 0)
                        ->orderBy('Quantity', 'desc')
                        ->first();

                    if ($related) {
                        $qty = $related->Quantity;
                        // Log::info('Combo quantity synced from related product', [
                        //     'combo_id' => $smartLifeProduct->smartlife_id,
                        //     'related_id' => $related->id,
                        //     'qty' => $qty
                        // ]);
                    }
                }
            }

            $product->Quantity = $qty;
            $product->alert_quantity = $smartLifeProduct->alert_quantity;
            $product->unit = $smartLifeProduct->unit;

            // Product type
            // Normalize product type from SmartLife (handle Arabic/English)
            $rawType = $smartLifeProduct->type;
            if ($rawType === 'تجميعي' || stripos($rawType, 'Combo') !== false) {
                $product->product_type = 'Combo';
            } else {
                $product->product_type = 'Standard';
            }
            // Standard, Combo
            $product->type = PRODUCT_PHYSICAL; // Default to physical product

            // Status and visibility
            if ($isNew) {
                $product->Status = 0; // Inactive by default for new SmartLife products
            } else {
                // Keep existing status for updates, or force active? 
                // Usually we don't want to re-activate a banned product.
                // $product->Status = 1; 
            }

            $product->show_pos = $smartLifeProduct->show_pos;
            $product->synced_from_smartlife = true;

            // Set description if not already set or empty
            $desc = trim($smartLifeProduct->description ?? '');
            if (empty($desc)) {
                $desc = 'Synced from SmartLife ERP';
            }
            
            if (empty($product->en_Description)) {
                $product->en_Description = $desc;
            }
            if (empty($product->fr_Description)) {
                $product->fr_Description = $desc;
            }

            // Set about if not already set or empty
            if (empty($product->en_About)) {
                $product->en_About = Str::limit($desc, 150);
            }
            if (empty($product->fr_About)) {
                $product->fr_About = $product->en_About;
            }

            // Handle images: download remote image into public/uploaded_files/product_image
            if (!empty($smartLifeProduct->image)) {
                // Only set if product has no Primary_Image or if the value is a remote URL
                $shouldSaveImage = empty($product->Primary_Image) || filter_var($product->Primary_Image, FILTER_VALIDATE_URL);

                if ($shouldSaveImage) {
                    try {
                        $imageUrl = $smartLifeProduct->image;
                        // If SmartLife returns a generic no_image placeholder, use local default instead
                        $isPlaceholder = false;
                        if (!empty($imageUrl)) {
                            $lower = strtolower($imageUrl);
                            if (str_contains($lower, 'no_image') || str_contains($lower, 'no-image') || str_ends_with($lower, 'no_image.png')) {
                                $isPlaceholder = true;
                            }
                        }

                        $dir = public_path('uploaded_files/product_image');
                        if (!File::exists($dir)) {
                            File::makeDirectory($dir, 0755, true);
                        }

                        if ($isPlaceholder) {
                            // Use local default placeholder present in repo
                            $product->Primary_Image = 'prod.png';
                        } else {
                            if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                                $parsed = parse_url($imageUrl);
                                $basename = basename($parsed['path'] ?? '');
                                $ext = pathinfo($basename, PATHINFO_EXTENSION) ?: 'jpg';
                                $filename = 'sl_' . $smartLifeProduct->smartlife_id . '.' . $ext;

                                $target = $dir . DIRECTORY_SEPARATOR . $filename;

                                // Download only if file not exists
                                if (!File::exists($target)) {
                                    $resp = Http::get($imageUrl);
                                    if ($resp->successful()) {
                                        File::put($target, $resp->body());
                                    } else {
                                        Log::warning('Failed to download SmartLife image', ['url' => $imageUrl, 'status' => $resp->status()]);
                                    }
                                }

                                $product->Primary_Image = $filename;
                            } else {
                                // If it's not a valid URL, assume it's already a filename; store as-is
                                $product->Primary_Image = $smartLifeProduct->image;
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error('Error handling SmartLife product image', ['error' => $e->getMessage(), 'smartlife_id' => $smartLifeProduct->smartlife_id]);
                    }
                }
            }

            // Ensure Primary_Image has a value for products without an image from SmartLife
            if (empty($product->Primary_Image) || $product->Primary_Image === 'none') {
                $product->Primary_Image = 'prod.png';
            }

            // Map category: use smartlife_id for reliable mapping
            if (!empty($smartLifeProduct->category_id) || !empty($smartLifeProduct->category)) {
                try {
                    $catId = $smartLifeProduct->category_id;
                    $catName = trim($smartLifeProduct->category ?? '');
                    
                    $category = null;
                    if ($catId) {
                        $category = \App\Models\Admin\Category::withTrashed()->where('smartlife_id', $catId)->first();
                    }
                    
                    if (!$category && !empty($catName)) {
                        $category = \App\Models\Admin\Category::withTrashed()->where('en_Category_Name', $catName)
                            ->orWhere('fr_Category_Name', $catName)
                            ->first();
                    }

                    if (!$category && !empty($catName)) {
                        // Brand new category from ERP
                        $category = \App\Models\Admin\Category::create([
                            'smartlife_id' => $catId,
                            'en_Category_Name' => $catName,
                            'en_Category_Slug' => \Illuminate\Support\Str::slug($catName),
                            'fr_Category_Name' => $catName,
                            'fr_Category_Slug' => \Illuminate\Support\Str::slug($catName),
                            'Status' => 0 // Start as Inactive for newly synced categories
                        ]);
                    } elseif ($category) {
                        if ($category->trashed()) {
                            $category->restore();
                            $category->Status = 0; // Set to Inactive if restored from trash
                            $category->save();
                        }
                        
                        if ($catId && empty($category->smartlife_id)) {
                            // Link existing category to smartlife_id
                            $category->smartlife_id = $catId;
                            $category->save();
                        }
                    }
                    
                    if ($category) {
                        $product->Category_Id = $category->id;
                    }
                } catch (\Exception $e) {
                    Log::error('Error mapping category for SmartLife product', ['error' => $e->getMessage(), 'smartlife_id' => $smartLifeProduct->smartlife_id]);
                }
            }

            // Set default values for required fields if creating new product
            if ($isNew) {
                $product->Discount = 0;
                $product->Discount_Price = 0;
                $product->Sold = 0;
                $product->Featured_Product = 0;
                $product->Best_Selling = 0;
                $product->New_Arrival = 0;
                $product->Today_Special = 0;
                $product->On_Sale = 1;
                $product->en_ShippingReturn = $product->en_ShippingReturn ?: 'Standard shipping and return policy applies.';
                $product->fr_ShippingReturn = $product->fr_ShippingReturn ?: $product->en_ShippingReturn;
                $product->en_AdditionalInformation = $product->en_AdditionalInformation ?: 'Product synced from SmartLife ERP';
                $product->fr_AdditionalInformation = $product->fr_AdditionalInformation ?: $product->en_AdditionalInformation;
                $product->Voucher = $product->Voucher ?: '0';
            }

            $product->save();

            // Debug Log
            if ($product->id == 163 || strpos($smartLifeProduct->name, 'test combo') !== false) {
                Log::info('Debug Combo Check', [
                    'id' => $product->id,
                    'name' => $smartLifeProduct->name,
                    'type' => $smartLifeProduct->type,
                    'has_combo_items' => !empty($smartLifeProduct->combo_items),
                    'combo_items_count' => is_array($smartLifeProduct->combo_items) ? count($smartLifeProduct->combo_items) : 0
                ]);
            }

            // Sync Combo Items if applicable - MOVED TO SECOND PASS in handle()
            // if (($smartLifeProduct->type === 'Combo' || $smartLifeProduct->type === 'تجميعي') && !empty($smartLifeProduct->combo_items)) {
            //     $this->syncComboItems($product, $smartLifeProduct->combo_items);
            // }

            // Sync Options (Sizes) from SmartLife
            // The options are stored in the SmartLifeProduct model as an array (casted JSON)
            if (!empty($smartLifeProduct->options) && is_array($smartLifeProduct->options)) {
                $syncSizes = [];
                foreach ($smartLifeProduct->options as $option) {
                    $optionName = $option['name'] ?? null;

                    if (!$optionName)
                        continue;

                    // matching by Arabic or English name
                    $size = \App\Models\Admin\Size::where('Size_ar', $optionName)
                        ->orWhere('Size', $optionName)
                        ->first();

                    if (!$size) {
                        $size = \App\Models\Admin\Size::create([
                            'Size' => $optionName,
                            'Size_ar' => $optionName,
                        ]);
                    }

                    // SmartLife sends the full price for the option, so we store it directly.
                    // Default logic assumes it's an override price.
                    $price = isset($option['price']) ? (float) $option['price'] : 0;

                    // Add to sync array with pivot data
                    $syncSizes[$size->id] = ['price' => $price, 'weight' => 0];
                }

                if (!empty($syncSizes)) {
                    $product->sizes()->sync($syncSizes);
                    Log::info('Synced sizes/options for product', ['product_id' => $product->id, 'count' => count($syncSizes)]);
                }
            }

            // Log::info('SmartLife product synced to main products table', [
            //     'smartlife_id' => $smartLifeProduct->smartlife_id,
            //     'product_id' => $product->id,
            //     'action' => $isNew ? 'created' : 'updated'
            // ]);

            return ['success' => true, 'created' => $isNew];

        } catch (\Exception $e) {
            Log::error('Failed to sync SmartLife product to main products table', [
                'smartlife_id' => $smartLifeProduct->smartlife_id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return ['success' => false, 'created' => false];
        }
    }

    /**
     * Handle items deleted from SmartLife - soft delete them locally
     *
     * @param array $activeProducts Active products from SmartLife API
     * @return array Statistics of deleted items
     */
    private function handleDeletedItems(array $activeProducts)
    {
        $stats = ['products' => 0, 'categories' => 0];

        try {
            // Get all active SmartLife product IDs from API response
            $activeSmartLifeIds = collect($activeProducts)->pluck('id')->filter()->toArray();

            if (empty($activeSmartLifeIds)) {
                return $stats;
            }

            // Find products that have smartlife_id but are NOT in the active list
            // These were deleted from SmartLife and should be soft-deleted here
            $deletedProducts = Product::whereNotNull('smartlife_id')
                ->whereNotIn('smartlife_id', $activeSmartLifeIds)
                ->whereNull('deleted_at') // Only non-deleted products
                ->get();

            foreach ($deletedProducts as $product) {
                // Check if product has any orders - if yes, soft delete; if no, can hard delete
                $hasOrders = \App\Models\Admin\OrderDetails::where('Product_Id', $product->id)->exists();

                if ($hasOrders) {
                    // Soft delete: keep for order history
                    $product->deleted_reason = 'smartlife_sync';
                    $product->delete(); // This triggers soft delete

                    Log::info('Product soft-deleted (has orders)', [
                        'product_id' => $product->id,
                        'smartlife_id' => $product->smartlife_id,
                        'name' => $product->en_Product_Name
                    ]);
                } else {
                    // No orders: can be soft-deleted safely
                    $product->deleted_reason = 'smartlife_sync_no_orders';
                    $product->delete();

                    Log::info('Product soft-deleted (no orders)', [
                        'product_id' => $product->id,
                        'smartlife_id' => $product->smartlife_id,
                        'name' => $product->en_Product_Name
                    ]);
                }

                $stats['products']++;
            }

            // Handle deleted categories: soft-delete categories that no longer have active products
            // First get all active categories from SmartLife products
            $activeCategories = collect($activeProducts)
                ->pluck('category')
                ->filter()
                ->unique()
                ->toArray();

            // Find categories that only contain SmartLife-synced products and are now empty
            $allCategories = \App\Models\Admin\Category::whereNull('deleted_at')->get();

            foreach ($allCategories as $category) {
                // Check if category has any active (non-deleted) products
                $hasActiveProducts = Product::where('Category_Id', $category->id)
                    ->whereNull('deleted_at')
                    ->exists();

                // If category has no active products and its name is not in SmartLife active categories
                if (!$hasActiveProducts && !in_array($category->en_Category_Name, $activeCategories)) {
                    // Check if category had orders through its products (use trashed products too)
                    $hasOrderHistory = Product::withTrashed()
                        ->where('Category_Id', $category->id)
                        ->whereHas('order_details')
                        ->exists();

                    if ($hasOrderHistory || Product::onlyTrashed()->where('Category_Id', $category->id)->exists()) {
                        // Soft delete category to preserve order history
                        $category->deleted_reason = 'smartlife_sync_empty';
                        $category->delete();

                        Log::info('Category soft-deleted (has history)', [
                            'category_id' => $category->id,
                            'name' => $category->en_Category_Name
                        ]);

                        $stats['categories']++;
                    }
                }
            }

        } catch (\Exception $e) {
            Log::error('Error handling deleted SmartLife items', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return $stats;
    }

    private function syncComboItems(Product $parentProduct, array $comboItems)
    {
        $syncData = [];

        foreach ($comboItems as $item) {
            Log::info('Processing Combo Item', ['parent_id' => $parentProduct->id, 'item_barcode' => $item['barcode'] ?? 'N/A']);
            $childBarcode = $item['barcode'] ?? null;
            if (!$childBarcode)
                continue;

            // Find child product
            $childProduct = Product::where('barcode', $childBarcode)->first();

            if (!$childProduct) {
                // Try to find in Shadow table
                $shadow = SmartLifeProduct::where('barcode', $childBarcode)->first();
                if ($shadow) {
                    // Sync shadow to main if needed, but for now just log warning if not found
                    // Or ideally we should sync it here recursively?
                    // Safe approach: skip if not found, rely on main loop to sync child later
                    Log::warning("Combo Child product not found in main table (skipping relationship)", ['parent_id' => $parentProduct->id, 'child_barcode' => $childBarcode]);
                    continue;
                }
                Log::warning("Combo Child product barcode not found", ['parent_id' => $parentProduct->id, 'child_barcode' => $childBarcode]);
                continue;
            }

            $quantity = $item['quantity'] ?? 1;

            // Prepare sync data for pivot table
            $syncData[$childProduct->id] = ['quantity' => $quantity];
        }

        if (!empty($syncData)) {
            $parentProduct->comboItems()->sync($syncData);
            Log::info('Synced combo items', ['parent_id' => $parentProduct->id, 'count' => count($syncData)]);
        }
    }

    /**
     * Sync categories extracted from product data
     */
    private function syncCategoriesFromProducts(array $products)
    {
        try {
            $categoriesByList = [];
            foreach ($products as $p) {
                if (!empty($p['category_id'])) {
                    $id = $p['category_id'];
                    $name = trim($p['category'] ?? '');
                    if (!empty($name)) {
                        $categoriesByList[$id] = $name;
                    }
                }
            }

            if (empty($categoriesByList)) {
                $this->warn('No category data found in products.');
                return;
            }

            foreach ($categoriesByList as $smartLifeId => $name) {
                // Try to find category by smartlife_id (including deleted ones)
                $category = \App\Models\Admin\Category::withTrashed()->where('smartlife_id', $smartLifeId)->first();
                $isNewOrRestored = false;

                if (!$category) {
                    // Try to find by name (to map existing categories)
                    $category = \App\Models\Admin\Category::withTrashed()->where('en_Category_Name', $name)
                        ->orWhere('fr_Category_Name', $name)
                        ->first();
                }

                if (!$category) {
                    $category = new \App\Models\Admin\Category();
                    $category->en_Category_Name = $name;
                    $category->fr_Category_Name = $name;
                    $category->en_Category_Slug = \Illuminate\Support\Str::slug($name);
                    $category->fr_Category_Slug = \Illuminate\Support\Str::slug($name);
                    $category->Status = 0; // Start as Inactive for newly synced categories
                    $isNewOrRestored = true;
                } elseif ($category->trashed()) {
                    $category->restore();
                    $category->Status = 0; // Restore as Inactive
                    $isNewOrRestored = true;
                }

                $category->smartlife_id = $smartLifeId;
                
                // If the name in ERP contains Arabic, set it to Arabic field
                if (preg_match('/[\x{0600}-\x{06FF}]/u', $name)) {
                    $category->fr_Category_Name = $name;
                } else {
                    $category->en_Category_Name = $name;
                }

                $category->save();
            }

            $this->info('Synced ' . count($categoriesByList) . ' unique categories from product data.');

        } catch (\Exception $e) {
            $this->error('Failed to sync categories from products: ' . $e->getMessage());
            Log::error('SmartLife category extraction failed', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Sync categories directly from Taxonomy API endpoint
     */
    private function syncCategoriesFromApi(array $categories)
    {
        try {
            foreach ($categories as $cat) {
                $smartLifeId = $cat['id'] ?? null;
                $name = trim($cat['name'] ?? '');

                if (!$smartLifeId || empty($name)) continue;

                $category = \App\Models\Admin\Category::withTrashed()->where('smartlife_id', $smartLifeId)->first();

                if (!$category) {
                    $category = \App\Models\Admin\Category::withTrashed()->where('en_Category_Name', $name)
                        ->orWhere('fr_Category_Name', $name)
                        ->first();
                }

                if (!$category) {
                    $category = new \App\Models\Admin\Category();
                    $category->en_Category_Name = $name;
                    $category->fr_Category_Name = $name;
                    $category->en_Category_Slug = \Illuminate\Support\Str::slug($name);
                    $category->fr_Category_Slug = \Illuminate\Support\Str::slug($name);
                    $category->Status = 0; // New categories from sync start as Inactive
                } elseif ($category->trashed()) {
                    $category->restore();
                    $category->Status = 0; // Restored categories from sync start as Inactive
                }

                $category->smartlife_id = $smartLifeId;
                
                if (preg_match('/[\x{0600}-\x{06FF}]/u', $name)) {
                    $category->fr_Category_Name = $name;
                } else {
                    $category->en_Category_Name = $name;
                }

                $category->save();
            }

            $this->info('Synced ' . count($categories) . ' categories from API.');

        } catch (\Exception $e) {
            $this->error('Failed to sync categories from API: ' . $e->getMessage());
            Log::error('SmartLife category API extraction failed', ['error' => $e->getMessage()]);
        }
    }
}
