<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

foreach (Schema::getColumnListing('products') as $column) {
    try {
        $info = DB::select('SHOW COLUMNS FROM products WHERE Field = ?', [$column])[0];
        if ($info->Null === 'NO' && $info->Default === null && $info->Key !== 'PRI') {
            echo $column . " (" . $info->Type . ")\n";
        }
    } catch (\Exception $e) {
        // Skip columns that might cause issues with the query
    }
}
