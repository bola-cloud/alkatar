@extends('admin.master', ['menu' => 'custom_box_orders'])
@section('title', isset($title) ? $title : '')
@section('content')

    <div id="table-url" data-url="{{ route('admin.custom_box_orders.index') }}"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{__('Custom Box Orders & Fulfillment')}}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('Custom Box Orders')}}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="customers__area bg-style mb-30">
                <div class="item-title">
                    <h2>{{ __('Fulfillment Checklist (2-Day Prep tracking)') }}</h2>
                </div>
                <div class="customers__table">
                    <table id="CustomOrdersTable" class="row-border data-table-filter table-style">
                        <thead>
                        <tr>
                            <th>{{ __('Order')}}</th>
                            <th>{{ __('Customer')}}</th>
                            <th>{{ __('Template')}}</th>
                            <th>{{ __('Capacity')}}</th>
                            <th>{{ __('Printed Name')}}</th>
                            <th>{{ __('Components')}}</th>
                            <th>{{ __('Status')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('post_scripts')
        <script>
            (function($) {
                "use strict";
                $(document).ready(function () {
                    $('#CustomOrdersTable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: $('#table-url').data("url"),
                        columns: [
                            {
                                data: 'order_number',
                                name: 'order_number'
                            },
                            {
                                data: 'customer',
                                name: 'customer'
                            },
                            {
                                data: 'template_name',
                                name: 'template_name'
                            },
                            {
                                data: 'capacity',
                                name: 'capacity'
                            },
                            {
                                data: 'print_name',
                                name: 'print_name'
                            },
                            {
                                data: 'details',
                                name: 'details'
                            },
                            {
                                data: 'prep_status',
                                name: 'prep_status',
                                orderable: false
                            }
                        ]
                    });
                });
            })(jQuery)
        </script>
    @endpush
@endsection
