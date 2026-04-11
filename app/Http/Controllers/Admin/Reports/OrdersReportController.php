<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderReportRequest;
use App\Models\Admin\Order;
use App\Models\User;
use App\Support\DateRange;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class OrdersReportController extends Controller
{
    protected array $fieldMap = [
        'order_number' => 'Order_Number',
        'customer_name' => 'users.name',
        'customer_phone' => 'users.Number',
        'date' => 'created_at',
        'order_amount' => 'Grand_Total',
        'delivery_amount' => 'Delivery_Charge',
        'user_fk' => 'User_Id',
    ];

    public function index(OrderReportRequest $request)
    {
        [$start, $end] = DateRange::from($request->input('range', 'month'), $request->input('start_date'), $request->input('end_date'));
        $query = $this->baseQuery($start, $end);

        $totals = $this->computeTotals(clone $query);
        $orders = $query
            ->orderBy($this->fieldMap['date'], 'desc')
            ->paginate(25)
            ->appends($request->only(['range', 'start_date', 'end_date']));

        $layout = view()->exists('admin.master') ? 'admin.master' : 'layouts.app';
        return view('admin.pages.reports.orders.index', [
            'orders' => $orders,
            'totals' => $totals,
            'range' => $request->input('range', 'month'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'fieldMap' => $this->fieldMap,
            'layout' => $layout,
        ]);
    }

    public function pdf(OrderReportRequest $request)
    {
        [$start, $end] = DateRange::from($request->input('range', 'month'), $request->input('start_date'), $request->input('end_date'));
        $query = $this->baseQuery($start, $end);
        $totals = $this->computeTotals(clone $query);
        $orders = $query->orderBy($this->fieldMap['date'], 'desc')->get();

        $viewData = [
            'orders' => $orders,
            'totals' => $totals,
            'range' => $request->input('range', 'month'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'fieldMap' => $this->fieldMap,
        ];

        $pdf = Pdf::loadView('admin.pages.reports.orders.pdf', $viewData)->setPaper('a4', 'portrait');

        $rangeKey = $request->input('range', 'today');
        $fileName = sprintf(
            'orders-report-%s-%s.pdf',
            $rangeKey,
            now()->format('Ymd-Hi')
        );

        return $pdf->download($fileName);
    }

    protected function baseQuery($start, $end): Builder
    {
        $dateColumn = $this->fieldMap['date'];

        $query = Order::query()
            ->with(['user'])
            ->when($dateColumn === 'created_at', function ($q) use ($start, $end, $dateColumn) {
                $q->whereBetween($dateColumn, [$start, $end]);
            }, function ($q) use ($start, $end, $dateColumn) {
                $q->whereBetween($dateColumn, [$start, $end]);
            });

        return $query;
    }
    protected function computeTotals(Builder $query): array
    {
        $sumOrderAmount = (clone $query)->sum($this->fieldMap['order_amount']);
        $sumDeliveryAmount = (clone $query)->sum($this->fieldMap['delivery_amount']);
        $countOrders = (clone $query)->count();

        return [
            'orders_count' => $countOrders,
            'order_amount' => $sumOrderAmount,
            'delivery_amount' => $sumDeliveryAmount,
        ];
    }
}

