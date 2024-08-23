<?php

namespace App\Http\Controllers\Admin;

use App\Imports\ProductsImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ExcelUpdateController extends Controller
{
    public function updateSubcategory()
    {
        $filePath = public_path('products_with_subcategory_id_cleaned.xlsx');

        Excel::import(new ProductsImport, $filePath);

        return back()->with('success', 'Subcategory IDs updated successfully!');
    }
}
