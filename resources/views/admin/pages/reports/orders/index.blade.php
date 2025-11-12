@extends('admin.master')

@php
    $menu = 'shipment';
    $submenu = 'orders_report';
@endphp
@section('content')
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Orders Report</h4>
            <a href="{{ route('admin.reports.orders.pdf', ['range' => $range ?? null, 'start_date' => $start_date ?? null, 'end_date' => $end_date ?? null]) }}"
                class="btn btn-sm btn-outline-primary">
                Export PDF
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.orders.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="range" class="form-label">Range</label>
                    <select name="range" id="range" class="form-select">
                        @php $r = request('range', 'month'); @endphp
                        <option value="today" {{ $r === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ $r === 'week' ? 'selected' : '' }}>Current Week (Mon–Sun)</option>
                        <option value="month" {{ $r === 'month' ? 'selected' : '' }}>Current Month</option>
                        <option value="year" {{ $r === 'year' ? 'selected' : '' }}>Current Year</option>
                        <option value="custom" {{ $r === 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                        value="{{ request('start_date') }}" placeholder="YYYY-mm-dd">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}"
                        placeholder="YYYY-mm-dd">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Apply</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-3">
        @php
            $ordersCount = isset($totals['orders_count']) ? $totals['orders_count'] : 0;
            $ordersAmount = isset($totals['order_amount']) ? $totals['order_amount'] : 0;
            $deliveryAmount = isset($totals['delivery_amount']) ? $totals['delivery_amount'] : 0;
        @endphp
        <div class="col-md-4">
            <div class="alert alert-secondary mb-2">
                <div class="small text-muted">Total Orders</div>
                <div class="fs-5 fw-bold">{{ number_format($ordersCount) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-secondary mb-2">
                <div class="small text-muted">Total Order Amount</div>
                <div class="fs-5 fw-bold">{{ number_format($ordersAmount, 3) }}</div>
            </div>
        </div>
        <!-- <div class="col-md-4">
            <div class="alert alert-secondary mb-2">
                <div class="small text-muted">Total Delivery Amount</div>
                <div class="fs-5 fw-bold">{{ number_format($deliveryAmount, 3) }}</div>
            </div>
        </div> -->
        @php $fieldMapJson = isset($fieldMap) ? json_encode($fieldMap) : '{}'; @endphp
    </div>

    <div class="card">
        <div class="card-body p-0">
            @include('admin.pages.reports.orders.partials._table', ['orders' => $orders, 'fieldMap' => $fieldMap])
        </div>
        <div class="card-footer">
            {{ $orders->links() }}
        </div>
    </div>
@endsection