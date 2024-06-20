<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;

use App\Http\Controllers\Controller;
use App\Models\Admin\Addition;
use Illuminate\Http\Request;
use App\Models\Admin\Product;

class AdditionController extends Controller
{

    public function create()
    {
        $products = Product::all();

        return view('admin.pages.addition.create', [
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'en_addition_name' => 'required|string',
            'fr_addition_name' => 'required|string',
            'price' => 'required|numeric',
            'icon' => 'nullable|image'
        ]);



        if ($request->has("icon")) {
            $icon_name = fileUpload($request['icon'], ProductImage());
        }

        $inserted_date = Addition::create([
            'name' => $data['en_addition_name'],
            'name_ar' => $data['fr_addition_name'],
            'price' => $data['price'],
            'product_id' => $data['product_id'],
            'icon' => $icon_name ?? null,
        ]);

        return redirect()->route('admin.physical.product.addition.create')->with('success', 'Addition created successfully');
    }

    public function edit()
    {
        return "sd";
    }
}
