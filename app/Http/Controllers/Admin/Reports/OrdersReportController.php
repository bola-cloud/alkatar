<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderReportRequest;
use App\Models\Admin\Order;
use App\Support\DateRange;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class OrdersReportController extends Controller
{
    /**
     * Field Map used by this report.
     * This array is auto-filled by inspecting the Order model/table fillables/columns.
     * Keys are logical names used by the report; values are actual column names in DB.
     *
     * Logical keys:
     * - date: placed_at|ordered_at|order_date|Delivery_At|created_at (fallback)
     * - total: order_amount|total|amount|Grand_Total|Sub_Total (fallback)
     * - delivery: delivery_amount|shipping_amount|delivery_fee|Delivery_Charge
     * - number: number|order_no|code|Order_Number
     * - customer_name: customer_name|client_name|name (from users.name via relation)
     * - status: status|Order_Status|Payment_Status (optional)
     */
    protected array $fieldMap = [];

    public function __construct()
    {
        // Auto-detect common columns from Order model.
        // Note: This is heuristic. Adjust in code if needed.
        $order = new Order();
        $columns = $order->getFillable();
        // Include timestamps by default even if not in fillable
        $columns = array_unique(array_merge($columns, ['created_at', 'updated_at']));

        $this->fieldMap = [
            // Date preferences (desc order). Use created_at as the reliable fallback.
            'date' => $this->firstMatch($columns, ['placed_at', 'ordered_at', 'order_date', 'Delivery_At', 'created_at']) ?? 'created_at',
            // Monetary totals
            'total' => $this->firstMatch($columns, ['order_amount', 'total', 'amount', 'Grand_Total', 'Sub_Total']) ?? 'Grand_Total',
            'delivery' => $this->firstMatch($columns, ['delivery_amount', 'shipping_amount', 'delivery_fee', 'Delivery_Charge']) ?? 'Delivery_Charge',
            // Identifiers
            'number' => $this->firstMatch($columns, ['number', 'order_no', 'code', 'Order_Number']) ?? 'Order_Number',
            // Status-like
            'status' => $this->firstMatch($columns, ['status', 'Order_Status', 'Payment_Status']) ?? 'Order_Status',
            // Customer name is derived from related users.name via User_Id foreign key
            'user_fk' => $this->firstMatch($columns, ['user_id', 'User_Id']) ?? 'User_Id',
        ];
    }

    /**
     * Try to find the first present candidate key in the provided columns array.
     */
    protected function firstMatch(array $columns, array $candidates): ?string
    {
        foreach ($candidates as $c) {
            if (in_array($c, $columns, true)) {
                return $c;
            }
        }
        return null;
    }

    public function index(OrderReportRequest $request)
    {
        $filters = $this->filters($request);

        [$query, $dateColumn] = $this->baseQuery($filters);

        // Clone the query for totals without altering pagination
        $totalsQuery = (clone $query);

        $pagination = $query
            ->with(['user'])
            ->orderByDesc($dateColumn)
            ->paginate(25)
            ->appends($request->query());

        $totals = $this->totals($totalsQuery);

        return view('admin.reports.orders.index', [
            'orders' => $pagination,
            'totals' => $totals,
            'filters' => $filters,
            'fieldMap' => $this->fieldMap, // expose for transparency
        ]);
    }

    public function pdf(OrderReportRequest $request)
    {
        $filters = $this->filters($request);
        [$query, $dateColumn] = $this->baseQuery($filters);
        $orders = $query->with(['user'])->orderByDesc($dateColumn)->get();
        $totals = $this->totals((clone $query));

        // File name: orders-report-<range>-YYYYMMDD-HHMM.pdf
        $ts = now()->format('Ymd-Hi');
        $fileName = sprintf('orders-report-%s-%s.pdf', $filters['range'], $ts);

        $pdf = PDF::loadView('admin.reports.orders.pdf', [
            'orders' => $orders,
            'totals' => $totals,
            'filters' => $filters,
            'fieldMap' => $this->fieldMap,
        ])->setPaper('a4', 'portrait');

        return $pdf->download($fileName);
    }

    /**
     * Build the base query applying the date range once, used by list and totals.
     * @return array{0: Builder, 1: string}
     */
    protected function baseQuery(array $filters): array
    {
        $dateColumn = $this->fieldMap['date'];
        $query = Order::query();

        // Apply date range filter consistently
        $query->whereBetween($dateColumn, [$filters['start'], $filters['end']]);

        return [$query, $dateColumn];
    }

    /**
     * Calculate totals from the provided query with the same filters applied.
     */
    protected function totals(Builder $query): array
    {
        $totalOrders = (clone $query)->count();
        $totalAmount = (clone $query)->sum($this->fieldMap['total']);
        $totalDelivery = (clone $query)->sum($this->fieldMap['delivery']);

        return [
            'count' => $totalOrders,
            'amount' => $totalAmount,
            'delivery' => $totalDelivery,
        ];
    }

    /**
     * Normalize filters and resolve date range.
     */
    protected function filters(Request $request): array
    {
        $range = $request->input('range', 'month');
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        $resolved = DateRange::resolve($range, $start, $end);

        return [
            'range' => $range,
            'start' => $resolved['start'],
            'end' => $resolved['end'],
        ];
    }
}
