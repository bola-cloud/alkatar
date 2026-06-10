@extends('admin.master', ['menu' => 'products', 'submenu' => 'product_list'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div id="table-url" data-url="{{ route('admin.product') }}"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{ __('Product') }}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Product') }}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="customers__area bg-style mb-30">
                <div class="item-title d-flex justify-content-between mb-3">
                    <div class="col-xs-6">
                        <a href="{{route('admin.physical.product.create')}}" class="btn btn-md btn-info">{{ __('Add Product')}}</a>
                    </div>
                    <div class="col-xs-6 text-end">
                        <button id="bulkActiveBtn" class="btn btn-primary d-none">
                            <i class="fas fa-check-double me-1"></i> {{ __('Bulk Activate') }}
                        </button>
                    </div>
                </div>
                <div class="customers__table">
                    <table id="ProductTable" class="row-border data-table-filter table-style">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </div>
                                </th>
                                <th>{{ __('#') }}</th>
                                <th>{{ __('Image') }}</th>
                                <th>{{ __('Product Name') }}</th>
                                <th>{{ __('Barcode') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('subcategory') }}</th>
                                <th>{{ __('Price') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
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
        <script src="{{ asset('backend/js/admin/datatables/product.js') }}"></script>
    @endpush
@endsection