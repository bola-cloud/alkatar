<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Front\PartnerRequest;
use Illuminate\Http\Request;

class PartnerRequestController extends Controller
{
    public function index(Request $request)
    {
        $data['title'] = __('Partner Requests');
        $data['requests'] = PartnerRequest::orderBy('id', 'desc')->paginate(15);
        return view('admin.pages.partner_requests.index', $data);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer|in:0,1,2',
        ]);

        $partner = PartnerRequest::findOrFail($id);
        $partner->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', __('Status updated successfully!'));
    }

    public function destroy($id)
    {
        $partner = PartnerRequest::findOrFail($id);
        $partner->delete();

        return redirect()->route('admin.partner-requests.index')->with('success', __('Successfully Deleted!'));
    }
}
