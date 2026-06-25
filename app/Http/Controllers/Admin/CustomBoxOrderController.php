<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomBoxOrder;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomBoxOrderController extends Controller
{
    public function index(Request $request)
    {
        $data['title'] = __('Custom Box Orders & Fulfillment');

        if ($request->ajax()) {
            $data = CustomBoxOrder::with('order')->orderBy('id', 'desc');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('order_number', function ($row) {
                    if ($row->order) {
                        return '<a href="' . route('admin.order.view', $row->order_id) . '" style="color:#007bff; font-weight:bold;">#' . $row->order->Order_Number . '</a>';
                    }
                    return 'N/A';
                })
                ->addColumn('customer', function ($row) {
                    if ($row->order) {
                        return e($row->order->Billing_Name) . '<br><small class="text-muted">' . e($row->order->Billing_Phone) . '</small>';
                    }
                    return 'N/A';
                })
                ->editColumn('print_name', function ($row) {
                    if ($row->print_name) {
                        return '<span class="badge bg-warning text-dark font-weight-bold" style="font-size:13px; border:1px solid #ffc107;">' . e($row->print_name) . '</span><br><small class="text-danger">(' . __('Requires 2 days') . ')</small>';
                    }
                    return '<span class="text-muted">' . __('No name printed') . '</span>';
                })
                ->editColumn('details', function ($row) {
                    return '<div style="white-space:normal; max-width:250px;">' . e($row->details) . '</div>';
                })
                ->editColumn('prep_status', function ($row) {
                    $statuses = [
                        'pending' => __('Pending Design Review'),
                        'in_printing' => __('Printing Name'),
                        'assembling' => __('Assembling Items'),
                        'ready' => __('Ready for Shipment'),
                    ];

                    $options = '';
                    foreach ($statuses as $val => $lbl) {
                        $selected = ($row->prep_status == $val) ? 'selected' : '';
                        $options .= '<option value="' . $val . '" ' . $selected . '>' . $lbl . '</option>';
                    }

                    $form = '<form action="' . route('admin.custom_box_orders.update_status', $row->id) . '" method="POST" class="status-update-form" style="display:inline-block;">';
                    $form .= csrf_field();
                    $form .= '<select name="prep_status" onchange="this.form.submit()" class="form-select form-select-sm" style="font-size:12px; font-weight:bold; padding:4px 8px; border-radius:6px; cursor:pointer;">';
                    $form .= $options;
                    $form .= '</select>';
                    $form .= '</form>';

                    return $form;
                })
                ->rawColumns(['order_number', 'customer', 'print_name', 'details', 'prep_status'])
                ->make(true);
        }

        return view('admin.pages.custom_box_orders.index', $data);
    }

    public function updateStatus(Request $request, $id)
    {
        $customBoxOrder = CustomBoxOrder::findOrFail($id);
        
        $request->validate([
            'prep_status' => 'required|string|in:pending,in_printing,assembling,ready',
        ]);

        $customBoxOrder->update([
            'prep_status' => $request->prep_status,
        ]);

        return redirect()->back()->with('success', __('Preparation status updated successfully!'));
    }
}
