@extends('admin.master', ['menu' => 'shipment', 'submenu' => 'orders_' . $status_prefix])
@section('title', isset($title) ? $title : '')
@section('content')
<div id="table-url" data-url="{{ route('admin.orders', $status_prefix) }}"></div>
<div class="row">
    <div class="col-md-12">
        <div class="breadcrumb__content">
            <div class="breadcrumb__content__left">
                <div class="breadcrumb__title">
                    <h2>{{ __('Order List') }}</h2>
                </div>
            </div>
            <div class="breadcrumb__content__right">
                <nav aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Orders') }}</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="customers__area bg-style mb-30">
            <div class="customers__table">
                <form action="{{ route('admin.orders.bulk_status_update') }}" method="POST" id="bulk-action-form">
                    @csrf
                    <div class="bulk-action-wrapper mb-3" style="display: flex; align-items: center; gap: 10px">
                        <h1 style="font-size: 1rem;">Change Status:</h1>
                        <select name="bulk_status" id="bulk-status-select" class="form-control d-inline-block mr-2"
                            style="width: 250px;">
                            <option value="">{{ __('Bulk Action') }}</option>
                            <option value="{{ ORDER_PENDING }}">{{ __('Pending') }}</option>
                            <option value="{{ ORDER_PROCESSING }}">{{ __('Processing') }}</option>
                            <option value="{{ ORDER_SHIPPED }}">{{ __('Shipped') }}</option>
                            <option value="{{ ORDER_DELIVERED }}">{{ __('Delivered') }}</option>
                            <option value="{{ ORDER_CANCELLED }}">{{ __('Cancelled') }}</option>
                            <option value="{{ ORDER_RETURN }}">{{ __('Returned') }}</option>
                        </select>
                        <button type="submit" class="btn btn-primary" id="bulk-apply-btn">{{ __('Apply') }}</button>
                    </div>


                    <table id="AdvertiseTable" class="row-border data-table-filter table-style">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all-checkbox"></th>
                                <th>{{ __('SL') }}</th>
                                <th>{{ __('Id') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Time') }}</th>
                                <th>{{ __('User') }}</th>
                                <!-- <th>{{__("State")}}</th> -->
                                <th>{{__("City")}}</th>
                                <!-- <th>{{ __('Products') }}</th> -->
                                <th>{{ __('Total Amount') }}</th>
                                <th>{{ __('Coupon Code') }}</th>
                                <th>{{ __('Payment Method') }}</th>
                                <!-- <th>{{ __('Digital Goods') }}</th> -->
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- modal -->
<div class="modal fade bd-example-modal-lg" id="dataModal" tabindex="-1" role="dialog"
    aria-labelledby="invoiceModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>
@endsection

@push('post_scripts')
    <script>
        const ROUTE_ORDER_DETAILS = "{{ route('admin.order.details') }}";
        const ROUTE_ORDER_STATUS_EDIT = "{{ route('admin.order_status_edit') }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('backend/js/admin/datatables/orders.js') }}"></script>

@endpush