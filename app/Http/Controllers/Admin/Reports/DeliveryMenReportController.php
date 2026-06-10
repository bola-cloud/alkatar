<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use App\Support\DateRange;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryMenReportController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->input('range', 'today');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $delivery_man_id = $request->input('delivery_man_id');

        [$start, $end] = DateRange::from($range, $start_date, $end_date);

        $query = Order::query()
            ->with(['deliveryMan'])
            ->whereBetween('created_at', [$start, $end]);

        if (!empty($delivery_man_id)) {
            $query->where('delivery_man_id', $delivery_man_id);
        }

        // Summary calculations
        $overallCount = (clone $query)->count();
        $overallTotal = (clone $query)->sum('Grand_Total');

        $paymentTotals = (clone $query)
            ->select('Payment_Method', DB::raw('count(*) as count'), DB::raw('sum(Grand_Total) as total_amount'))
            ->groupBy('Payment_Method')
            ->get();

        $orders = $query->orderBy('created_at', 'desc')->paginate(25)->appends($request->all());

        return view('admin.pages.reports.delivery_men.index', [
            'orders' => $orders,
            'overallCount' => $overallCount,
            'overallTotal' => $overallTotal,
            'paymentTotals' => $paymentTotals,
            'range' => $range,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'delivery_man_id' => $delivery_man_id,
        ]);
    }

    public function pdf(Request $request)
    {
        $range = $request->input('range', 'today');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $delivery_man_id = $request->input('delivery_man_id');

        [$start, $end] = DateRange::from($range, $start_date, $end_date);

        $query = Order::query()
            ->with(['deliveryMan'])
            ->whereBetween('created_at', [$start, $end]);

        if (!empty($delivery_man_id)) {
            $query->where('delivery_man_id', $delivery_man_id);
        }

        // Summary calculations
        $overallCount = (clone $query)->count();
        $overallTotal = (clone $query)->sum('Grand_Total');

        $paymentTotals = (clone $query)
            ->select('Payment_Method', DB::raw('count(*) as count'), DB::raw('sum(Grand_Total) as total_amount'))
            ->groupBy('Payment_Method')
            ->get();

        $orders = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('admin.pages.reports.delivery_men.pdf', [
            'orders' => $orders,
            'overallCount' => $overallCount,
            'overallTotal' => $overallTotal,
            'paymentTotals' => $paymentTotals,
            'range' => $range,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'delivery_man_id' => $delivery_man_id,
        ])->setPaper('a4', 'portrait');

        $fileName = sprintf(
            'delivery-men-report-%s-%s.pdf',
            $range,
            now()->format('Ymd-Hi')
        );

        return $pdf->download($fileName);
    }
}
