<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;

use App\Http\Controllers\Controller;
use App\Models\Admin\Addition;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use Illuminate\Support\Facades\Storage;

class AdditionController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Addition::with('product')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = '<div class="action__buttons">';
                    $btn = $btn . '<a href="' . route('admin.physical.product.addition.edit', $data->id) . '" class="btn-action" title="Edit"><i class="fas fa-pen-to-square"></i></a>';
                    // if ($data->status == 1) {
                    //     $btn = $btn . '<a href="' . route('admin.physical.product.addition.inactive', $data->id) . '" class="btn-action" title="Inactive"><i class="fas fa-toggle-on"></i></a>';
                    // } else {
                    //     $btn = $btn . '<a href="' . route('admin.physical.product.addition.active', $data->id) . '" class="btn-action" title="Active"><i class="fas fa-toggle-off"></i></a>';
                    // }
                    $btn = $btn . '<a href="' . route('admin.physical.product.addition.delete', ['id' => $data->id]) . '" class="btn-action delete" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                    $btn = $btn . '</div>';
                    return $btn;
                })
                ->editColumn('name', function ($data) {
                    return $data->name;
                })
                ->editColumn('name_ar', function ($data) {
                    return $data->name_ar;
                })
                ->editColumn('product', function ($data) {
                    return $data->product->fr_Product_Name;
                })
                ->editColumn('price', function ($data) {
                    return $data->price;
                })
                ->editColumn('status', function ($data) {
                    if ($data->status == 1) {
                        return '<span class="status active">فعال</span>';
                    } else {
                        return '<span class="status blocked">غير فعال</span>';
                    }
                })
                ->editColumn('icon', function ($data) {
                    return $data->icon ? '<img src="' . asset(ProductImage() . $data->icon) . '" width="50" height="50" alt="Addition Icon" />' : 'No Icon';
                })
                ->rawColumns(['action', 'name', 'name_ar', 'product', 'price', 'status', 'icon'])
                ->make(true);
        }
        $data['title'] = __('Addition List');
        return view('admin.pages.addition.index', $data);
    }

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
            'icon' => 'nullable|image',
            'status' => 'nullable|string',
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
            'status' => isset($data['status']) ? '1' : '0',
        ]);

        return redirect()->route('admin.physical.product.addition.create')->with('success', 'Addition created successfully');
    }

    public function edit($id)
    {
        $addition = Addition::findOrFail($id);
        $products = Product::all();
        return view('admin.pages.addition.edit', [
            'addition' => $addition,
            'products' => $products,
        ]);
    }

    public function update(Request $request, $id)
    {
        $addition = Addition::findOrFail($id);

        $data = $request->validate([
            'product_id' => 'required|integer',
            'en_addition_name' => 'required|string',
            'fr_addition_name' => 'required|string',
            'price' => 'required|numeric',
            'icon' => 'nullable|image',
            'status' => 'nullable|string',
        ]);

        if ($request->hasFile('icon')) {
            // Delete old icon if exists
            if ($addition->icon) {
                Storage::delete(ProductImage() . $addition->icon);
            }
            $icon_name = fileUpload($request->file('icon'), ProductImage());
        }



        $addition->update([
            'name' => $data['en_addition_name'],
            'name_ar' => $data['fr_addition_name'],
            'price' => $data['price'],
            'product_id' => $data['product_id'],
            'icon' => $icon_name ?? $addition->icon,
            'status' => isset($data['status']) ? '1' : '0',
        ]);

        return redirect()->route('admin.physical.product.addition.edit', $addition->id)->with('success', 'Addition updated successfully');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $addition = Addition::findOrFail($id);

        if ($addition->icon) {
            Storage::delete(ProductImage() . $addition->icon);
        }
        $addition->delete();
        return redirect()->back()->with('success', __('Addition deleted successfully'));
    }
}
