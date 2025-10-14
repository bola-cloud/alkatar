@extends('admin.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="card-title mb-3">Orders Report</h4>

                <!-- Filter Form -->
                <form method="GET" action="{{ route('admin.reports.orders.index') }}" class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Range</label>
                        <select name="range" class="form-select">
                            @php $range = request('range', $filters['range'] ?? 'month'); @endphp
                            <option value="today" {{ $range=='today'?'selected':'' }}>Today</option>
                            <option value="week" {{ $range=='week'?'selected':'' }}>Current Week (Mon–Sun)</option>
                            <option value="month" {{ $range=='month'?'selected':'' }}>Current Month</option>
                            <option value="year" {{ $range=='year'?'selected':'' }}>Current Year</option>
                            <option value="custom" {{ $range=='custom'?'selected':'' }}>Custom Range</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Start date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" placeholder="Y-m-d">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" placeholder="Y-m-d">
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100">Apply</button>
                    </div>
                </form>
                <div class="mt-2">
                    <a class="btn btn-outline-secondary" href="{{ route('admin.reports.orders.pdf', request()->query()) }}">Export PDF</a>
                </div>
            </div>
        </div>

        <!-- Totals -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="h6 text-muted">Total Orders</div>
                        <div class="h3 mb-0">{{ number_format($totals['count']) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="h6 text-muted">Total Order Amount</div>
                        <div class="h3 mb-0">{{ number_format($totals['amount'], 2) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="h6 text-muted">Total Delivery Amount</div>
                        <div class="h3 mb-0">{{ number_format($totals['delivery'], 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <div class="card-body">
                @include('admin.reports.orders.partials._table', ['orders' => $orders, 'fieldMap' => $fieldMap])
                <div class="mt-3">{{ $orders->links() }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Developer note: If this admin layout differs in your project, you can switch the layout by changing the @extends to your desired admin master. -->
@endsection
