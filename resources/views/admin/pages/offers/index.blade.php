@extends('admin.master', ['menu' => 'offers'])
@section('title', isset($title) ? $title : '')
@section('content')
<div id="table-url" data-url="{{route('admin.offers')}}"></div>
<div class="row">
    <div class="col-md-12">
        <div class="breadcrumb__content">
            <div class="breadcrumb__content__left">
                <div class="breadcrumb__title">
                    <h2>{{__('offers')}}</h2>
                </div>
            </div>
            <div class="breadcrumb__content__right">
                <nav aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Home')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('Discounts')}}</li>
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
                <div class="col-md-12 d-flex justify-content-between">
                    <!-- Left side: Add offer button -->
                    <div class="col-xs-6">
                        <a href="{{route('admin.offers.create')}}" class="btn btn-md btn-info">{{ __('Add offer') }}</a>
                    </div>

                    <!-- Right side: Free Shipping button -->
                    <div class="col-xs-6 text-right">
                        @if ($free_shipping)
                            <a href="{{route('admin.offers.freeShippingInActive')}}" class="btn-action btn-lg"
                                id="free_shipping" title="active">
                                <i class="fas fa-toggle-on"></i>
                            </a>
                            Deactivate Free Shipping
                        @else
                            <a href="{{route('admin.offers.freeShippingActive')}}" class="btn-action btn-lg"
                                id="free_shipping" title="Inactive">
                                <i class="fas fa-toggle-off"></i>
                            </a>
                            Active Free Shipping
                        @endif
                    </div>
                </div>
            </div>

            <div class="customers__table">
                <table id="offersTable" class="row-border data-table-filter table-style">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Name')}}</th>
                            <th>{{ __('Type')}}</th>
                            <th>{{ __('Start Date')}}</th>
                            <th>{{ __('End Date')}}</th>
                            <th>{{ __('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--Row-->
@push('post_scripts')
    <script src="{{asset('backend/js/admin/datatables/offers.js')}}"></script>
    <!-- Page level custom scripts -->
@endpush
@endsection