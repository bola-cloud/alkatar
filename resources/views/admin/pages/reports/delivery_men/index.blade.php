@extends('admin.master')

@php
    $menu = 'shipment';
    $submenu = 'delivery_men_report';
@endphp
@section('content')
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">{{ __('Delivery Men Report') }}</h4>
            <a href="{{ route('admin.reports.delivery_men.pdf', ['range' => $range ?? null, 'start_date' => $start_date ?? null, 'end_date' => $end_date ?? null, 'delivery_man_id' => $delivery_man_id ?? null]) }}"
                class="btn btn-sm btn-outline-primary">
                {{ __('Export PDF') }}
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.delivery_men.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="range" class="form-label">{{ __('Filter') }}</label>
                    <select name="range" id="range" class="form-select">
                        @php $r = request('range', 'today'); @endphp
                        <option value="today" {{ $r === 'today' ? 'selected' : '' }}>{{ __('Today') }}</option>
                        <option value="week" {{ $r === 'week' ? 'selected' : '' }}>{{ __('Current Week') }}</option>
                        <option value="month" {{ $r === 'month' ? 'selected' : '' }}>{{ __('Current Month') }}</option>
                        <option value="year" {{ $r === 'year' ? 'selected' : '' }}>{{ __('Current Year') }}</option>
                        <option value="custom" {{ $r === 'custom' ? 'selected' : '' }}>{{ __('Custom Range') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                        value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">{{ __('End Date') }}</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">{{ __('Apply Filter') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <h5 class="mb-3">{{ __('Invoices Summary by Payment Method') }}</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="alert alert-secondary mb-2">
                        <div class="small text-muted">{{ __('Total Invoices') }}</div>
                        <div class="fs-5 fw-bold">{{ number_format($overallCount) }} {{ __('Orders') }} - {{ number_format($overallTotal, 3) }} OMR</div>
                    </div>
                </div>
                @foreach($paymentTotals as $pt)
                <div class="col-md-4">
                    <div class="alert alert-info mb-2">
                        <div class="small text-muted">{{ $pt->Payment_Method ?: __('Unknown') }}</div>
                        <div class="fs-5 fw-bold">{{ number_format($pt->count) }} {{ __('Orders') }} - {{ number_format($pt->total_amount, 3) }} OMR</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>{{ __('Order Number') }}</th>
                            <th>{{ __('Delivery Man') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Total Amount') }}</th>
                            <th>{{ __('Delivery Charge') }}</th>
                            <th>{{ __('Payment Method') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->Order_Number }}</td>
                                <td>{{ $order->deliveryMan->name ?? __('Unassigned') }}</td>
                                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ number_format($order->Grand_Total, 3) }} OMR</td>
                                <td>{{ number_format($order->Delivery_Charge, 3) }} OMR</td>
                                <td>{{ $order->Payment_Method }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">{{ __('No orders found matching the filter.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($orders->hasPages())
        <div class="card-footer">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
@endsection
