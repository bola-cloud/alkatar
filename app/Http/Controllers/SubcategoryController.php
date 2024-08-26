<?php

namespace App\Http\Controllers;

use App\Models\Admin\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubcategoryController extends Controller
{
    public function subcategory(Request $request)
    {
        if ($request->ajax()) {
            $data = Subcategory::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = '<div class="action__buttons">';
                    $btn = $btn . '<a href="' . route('admin.subcategory.edit', $data->id) . '" class="btn-action" title="Edit"><i class="fas fa-pen-to-square"></i></a>';

                    if ($data->status == 1) {
                        $btn = $btn . '<a href="' . route('admin.subcategory.inactive', $data->id) . '" class="btn-action" title="Inactive"><i class="fas fa-toggle-on"></i></a>';
                    } else {
                        $btn = $btn . '<a href="' . route('admin.subcategory.active', $data->id) . '" class="btn-action" title="Active"><i class="fas fa-toggle-off"></i></a>';
                    }

                    $btn = $btn . '<a href="' . route('admin.subcategory.delete', $data->id) . '" class="btn-action delete" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                    $btn = $btn . '</div>';
                    return $btn;
                })
                ->editColumn('name', function ($data) {
                    return $data->name;
                })
                ->editColumn('name_ar', function ($data) {
                    return $data->name_ar;
                })
                ->editColumn('category_id', function ($data) {
                    $category = Category::find($data->category_id);


                    return $category?->fr_Category_Name;
                })
                ->editColumn('status', function ($data) {
                    if ($data->status == 1) {
                        $active = "Active";
                        return '<span class="status active">' . $active . '</span>';
                    } else {
                        $active = "Inactive";
                        return '<span class="status blocked">' . $active . '</span>';
                    }
                })
                ->rawColumns(['action', 'name', 'name_ar', 'category_id'])
                ->make(true);
        }
        $data['title'] = __('Subcategory List');
        return view('admin.pages.subcategory.index', $data);
    }

    public function getAllSubcategories(Request $request)
    {
        $categoryId = $request->category_id;
        $subcategories = Subcategory::where('category_id', $categoryId)->get();
        return response()->json($subcategories);
    }

    public function subCategoryCreate()
    {
        $data['title'] = __('Subcategory Create');
        return view('admin.pages.subcategory.create', $data);
    }

    public function subCategoryStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'name_ar' => 'required|string',
            'category_id' => 'required|exists:categories,id'
        ]);


        $subcategory = Subcategory::create([
            'name' => $data['name'],
            'name_ar' => $data['name_ar'],
            'category_id' => $data['category_id']
        ]);

        if ($subcategory) {
            return redirect()->route('admin.subcategory')->with('success', __('Successfully Stored !'));
        }
        return redirect()->route('admin.subcategory')->with('error', __('Does not Stored !'));
    }

    public function subCategoryEdit($id)
    {
        $data['title'] = __('Subcategory Edit');
        $data['edit'] = Subcategory::where('id', $id)->first();
        return view('admin.pages.subcategory.edit', $data);
    }

    public function subCategoryUpdate(Request $request)
    {
        $id = $request->id;
        $cat = Subcategory::whereid($id)->first();


        $update = $cat->update([
            'name' => is_null($request->name) ? $cat->name : $request->name,
            'name_ar' => is_null($request->name_ar) ? $cat->name_ar : $request->name_ar,
            'category_id' => is_null($request->category_id) ? $cat->category_id : $request->category_id
        ]);

        if ($update) {
            return redirect()->route('admin.subcategory')->with('success', __('Successfully Updated!'));
        }
        return redirect()->back()->with('error', __('Does not Update  !'));
    }
    public function subCategoryActive($id)
    {
        $inactive = Subcategory::find($id)->update(['status' => 1]);
        if ($inactive) {
            return redirect()->route('admin.subcategory')->with('success', __('Successfully Active !'));
        }
        return redirect()->route('admin.subcategory')->with('success', __('Does not Updated!'));
    }
    public function subCategoryInactive($id)
    {
        $inactive = Subcategory::find($id)->update(['status' => 0]);
        if ($inactive) {
            return redirect()->route('admin.subcategory')->with('success', __('Successfully Inactive !'));
        }
        return redirect()->route('admin.subcategory')->with('success', __('Does not Updated !'));
    }

    public function subCategoryDelete($id)
    {
        $delete = Subcategory::Where('id', $id)->delete();
        if ($delete) {
            return redirect()->route('admin.subcategory')->with('success', __('Successfully Deleted !'));
        }
        return redirect()->route('admin.subcategory')->with('error', __('Does Not Delete!'));
    }

    // public function slugify($text)
    // {
    //     // replace non letter or digits by divider
    //     $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    //     // remove unwanted characters
    //     $text = preg_replace('~[^-\w]+~', '', $text);

    //     // trim
    //     $text = trim($text, '-');

    //     // remove duplicate divider
    //     $text = preg_replace('~-+~', '-', $text);

    //     // lowercase
    //     $text = strtolower($text);

    //     if (empty($text)) {
    //         return 'n-a';
    //     }
    //     return $text;
    // }
}
