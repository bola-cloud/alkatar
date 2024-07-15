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

                    // if ($data->Status == 1) {
                    //     $btn = $btn . '<a href="' . route('admin.category.inactive', $data->id) . '" class="btn-action" title="Inactive"><i class="fas fa-toggle-on"></i></a>';
                    // } else {
                    //     $btn = $btn . '<a href="' . route('admin.category.active', $data->id) . '" class="btn-action" title="Active"><i class="fas fa-toggle-off"></i></a>';
                    // }
    
                    $btn = $btn . '<a href="' . route('admin.category.delete', $data->id) . '" class="btn-action delete" title="Delete"><i class="fas fa-trash-alt"></i></a>';
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


                    return $category->en_Category_Name;
                })
                // ->editColumn('Status', function ($data) {
                //     if ($data->Status == 1) {
                //         $active = "Active";
                //         return '<span class="status active">' . $active . '</span>';
                //     } else {
                //         $active = "Inactive";
                //         return '<span class="status blocked">' . $active . '</span>';
                //     }
                // })
                ->rawColumns(['action', 'name', 'name_ar', 'category_id'])
                ->make(true);
        }
        $data['title'] = __('Subcategory List');
        return view('admin.pages.subcategory.index', $data);
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

    public function categoryUpdate(Request $request)
    {
        $id = $request->id;
        $cat = Category::whereid($id)->first();


        if ($request->icon) {
            // delete image
            if ($cat->Category_Icon) {
                $oldIcon = $cat->Category_Icon;
                if ($request->icon && $oldIcon) {
                    Storage::delete(CategoryImage() . $oldIcon);
                }

            }
            // upload image
            $icon_name = fileUpload($request['icon'], CategoryImage());
        } else {
            $icon_name = $cat->Category_Icon;
        }


        $update = $cat->update([
            'en_Category_Name' => is_null($request->en_category_name) ? $cat->en_Category_Name : $request->en_category_name,
            'en_Description' => is_null($request->en_description) ? $cat->en_Description : $request->en_description,
            'en_Category_Slug' => is_null($request->en_category_name) ? $cat->en_Category_Slug : $this->slugify($request->en_category_name),
            'fr_Category_Name' => is_null($request->fr_category_name) ? $cat->fr_Category_Name : $request->fr_category_name,
            'fr_Description' => is_null($request->fr_description) ? $cat->fr_Description : $request->fr_description,
            'fr_Category_Slug' => is_null($request->fr_category_name) ? $cat->fr_Category_Slug : $this->slugify($request->fr_category_name),
            'Category_Icon' => $icon_name,
        ]);
        if ($update) {
            return redirect()->route('admin.category')->with('success', __('Successfully Updated!'));
        }
        return redirect()->back()->with('error', __('Does not Update  !'));
    }
    // public function categoryActive($id)
    // {
    //     $inactive = Category::find($id)->update(['Status' => 1]);
    //     if ($inactive) {
    //         return redirect()->route('admin.category')->with('success', __('Successfully Active !'));
    //     }
    //     return redirect()->route('admin.category')->with('success', __('Does not Updated!'));
    // }
    // public function categoryInactive($id)
    // {
    //     $inactive = Category::find($id)->update(['Status' => 0]);
    //     if ($inactive) {
    //         return redirect()->route('admin.category')->with('success', __('Successfully Inactive !'));
    //     }
    //     return redirect()->route('admin.category')->with('success', __('Does not Updated !'));
    // }

    // public function categoryDelete($id)
    // {
    //     $delete = Category::Where('id', $id)->delete();
    //     if ($delete) {
    //         return redirect()->route('admin.category')->with('success', __('Successfully Deleted !'));
    //     }
    //     return redirect()->route('admin.category')->with('error', __('Does Not Delete!'));
    // }

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
