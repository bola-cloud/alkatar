<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Front\WholesaleRequest;
use Illuminate\Http\Request;

class WholesaleRequestController extends Controller
{
    public function index(Request $request)
    {
        $data['title'] = __('Wholesale Requests');
        $data['requests'] = WholesaleRequest::orderBy('id', 'desc')->paginate(15);
        return view('admin.pages.wholesale.index', $data);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer|in:0,1,2',
        ]);

        $wholesale = WholesaleRequest::findOrFail($id);
        $wholesale->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', __('Status updated successfully!'));
    }

    public function destroy($id)
    {
        $wholesale = WholesaleRequest::findOrFail($id);
        $wholesale->delete();

        return redirect()->route('admin.wholesale.index')->with('success', __('Successfully Deleted!'));
    }
}
