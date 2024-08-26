<?php

namespace App\Imports;

use App\Models\Admin\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        Product::where('id', $row[0])
            ->update([
                'subcategory_id' => $row[5]
            ]);
        return Product::where('id', $row[0])->get();
    }
}
