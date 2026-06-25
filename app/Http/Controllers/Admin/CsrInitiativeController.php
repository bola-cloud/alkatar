<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CsrInitiative;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CsrInitiativeController extends Controller
{
    public function index(Request $request)
    {
        $data['title'] = __('CSR Initiatives & Projects');

        if ($request->ajax()) {
            $data = CsrInitiative::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="action__buttons">';
                    $btn .= '<a href="' . route('admin.csr.edit', $row->id) . '" class="btn-action"><i class="fa-solid fa-pen-to-square"></i></a>';
                    $btn .= '<a href="' . route('admin.csr.delete', $row->id) . '" class="btn-action delete"><i class="fas fa-trash-alt"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->editColumn('image', function ($row) {
                    if ($row->image) {
                        $url = asset('uploaded_files/csr/' . $row->image);
                        return '<img src="' . $url . '" border="0" width="80" class="img-rounded" align="center" />';
                    }
                    return 'N/A';
                })
                ->editColumn('type', function ($row) {
                    return ucfirst($row->type);
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }

        return view('admin.pages.csr.index', $data);
    }

    public function create()
    {
        $data['title'] = __('Add CSR Initiative');
        return view('admin.pages.csr.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'type' => 'required|string|in:project,initiative,video',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            'video_url' => 'nullable|url',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = fileUpload($request->file('image'), 'uploaded_files/csr/');
        }

        $pdfName = null;
        if ($request->hasFile('pdf_file')) {
            $pdfName = fileUpload($request->file('pdf_file'), 'uploaded_files/csr/');
        }

        CsrInitiative::create([
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'type' => $request->type,
            'image' => $imageName,
            'pdf_file' => $pdfName,
            'video_url' => $request->video_url,
        ]);

        return redirect()->route('admin.csr.index')->with('success', __('CSR Initiative created successfully!'));
    }

    public function edit($id)
    {
        $data['title'] = __('Edit CSR Initiative');
        $data['initiative'] = CsrInitiative::findOrFail($id);
        return view('admin.pages.csr.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $initiative = CsrInitiative::findOrFail($id);

        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'type' => 'required|string|in:project,initiative,video',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            'video_url' => 'nullable|url',
        ]);

        $imageName = $initiative->image;
        if ($request->hasFile('image')) {
            $imageName = fileUpload($request->file('image'), 'uploaded_files/csr/', $initiative->image);
        }

        $pdfName = $initiative->pdf_file;
        if ($request->hasFile('pdf_file')) {
            $pdfName = fileUpload($request->file('pdf_file'), 'uploaded_files/csr/', $initiative->pdf_file);
        }

        $initiative->update([
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'type' => $request->type,
            'image' => $imageName,
            'pdf_file' => $pdfName,
            'video_url' => $request->video_url,
        ]);

        return redirect()->route('admin.csr.index')->with('success', __('CSR Initiative updated successfully!'));
    }

    public function destroy($id)
    {
        $initiative = CsrInitiative::findOrFail($id);
        
        // Optionally delete files from storage
        if ($initiative->image && file_exists(public_path('uploaded_files/csr/' . $initiative->image))) {
            @unlink(public_path('uploaded_files/csr/' . $initiative->image));
        }
        if ($initiative->pdf_file && file_exists(public_path('uploaded_files/csr/' . $initiative->pdf_file))) {
            @unlink(public_path('uploaded_files/csr/' . $initiative->pdf_file));
        }

        $initiative->delete();

        return redirect()->route('admin.csr.index')->with('success', __('CSR Initiative deleted successfully!'));
    }
}
