<?php
$file = 'app/Http/Controllers/Frontend/ProductController.php';
$content = file_get_contents($file);

$oldCat = "Category::with('products')->where('en_Description', null)->orWhere('Category_Icon', null)->get()";
$newCat = "Category::where('Status', 1)->get()";
$content = str_replace($oldCat, $newCat, $content);

$lines = explode("\n", $content);
foreach ($lines as $i => &$line) {
    // Avoid replacing inside additions query closure
    if (strpos($line, "\$query->where('status', 1)") === false) {
        $line = preg_replace("/->where\('status',\s*1\)/i", "->where('status', 1)->where('Quantity', '>', 0)", $line);
        $line = preg_replace("/Product::where\('status',\s*1\)/i", "Product::where('status', 1)->where('Quantity', '>', 0)", $line);
        $line = preg_replace("/->where\('Status',\s*1\)/i", "->where('Status', 1)->where('Quantity', '>', 0)", $line);
        $line = preg_replace("/Product::where\('Status',\s*1\)/i", "Product::where('Status', 1)->where('Quantity', '>', 0)", $line);
    }
}
$content = implode("\n", $lines);

file_put_contents($file, $content);
echo "ProductController updated.\n";
