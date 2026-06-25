<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomBoxTemplate;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomBoxTemplateController extends Controller
{
    public function index(Request $request)
    {
        $data['title'] = __('Custom Box Templates');

        if ($request->ajax()) {
            $data = CustomBoxTemplate::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="action__buttons">';
                    $btn .= '<a href="' . route('admin.custom_box_templates.edit', $row->id) . '" class="btn-action"><i class="fa-solid fa-pen-to-square"></i></a>';
                    $btn .= '<a href="' . route('admin.custom_box_templates.delete', $row->id) . '" class="btn-action delete"><i class="fas fa-trash-alt"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->editColumn('color_code', function ($row) {
                    return '<span style="display:inline-block; width:24px; height:24px; border-radius:50%; border:1px solid #ccc; background-color:' . $row->color_code . ';"></span> ' . $row->color_code;
                })
                ->editColumn('price', function ($row) {
                    return number_format($row->price, 3) . ' OMR';
                })
                ->editColumn('is_active', function ($row) {
                    return $row->is_active ? '<span class="badge bg-success">' . __('Active') . '</span>' : '<span class="badge bg-danger">' . __('Inactive') . '</span>';
                })
                ->rawColumns(['action', 'color_code', 'is_active'])
                ->make(true);
        }

        return view('admin.pages.custom_box_templates.index', $data);
    }

    public function create()
    {
        $data['title'] = __('Add Custom Box Template');
        return view('admin.pages.custom_box_templates.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'color_code' => 'required|string|max:20',
            'price' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        CustomBoxTemplate::create([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'color_code' => $request->color_code,
            'price' => $request->price,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.custom_box_templates.index')->with('success', __('Template created successfully!'));
    }

    public function edit($id)
    {
        $data['title'] = __('Edit Custom Box Template');
        $data['template'] = CustomBoxTemplate::findOrFail($id);
        return view('admin.pages.custom_box_templates.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $template = CustomBoxTemplate::findOrFail($id);

        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'color_code' => 'required|string|max:20',
            'price' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $template->update([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'color_code' => $request->color_code,
            'price' => $request->price,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.custom_box_templates.index')->with('success', __('Template updated successfully!'));
    }

    public function destroy($id)
    {
        $template = CustomBoxTemplate::findOrFail($id);
        $template->delete();

        return redirect()->route('admin.custom_box_templates.index')->with('success', __('Template deleted successfully!'));
    }
}
