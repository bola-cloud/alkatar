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
            @if(config('smartlife.sync_enabled'))
                <div class="alert alert-info mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>{{ __('SmartLife ERP Integration:') }}</strong>
                        {{ __('Products are automatically synced from SmartLife ERP. Product editing and deletion are disabled.') }}
                    </div>
                    <div class="d-flex align-items-center">
                        @php

                        @endphp
                        @php
                            $locale = app()->getLocale();
                            if ($locale == 'fr') {
                                $locale = 'ar';
                            }
                        @endphp
                        @if($lastSync)
                            <span class="me-3 small text-muted">
                                {{ __('Last synced:') }}
                                <strong>{{ \Carbon\Carbon::parse($lastSync)->locale($locale)->diffForHumans() }}</strong>
                            </span>
                        @endif
                        <a href="{{ route('admin.product.sync') }}" class="btn btn-sm btn-light border text-primary">
                            <i class="fas fa-sync-alt me-1"></i> {{ __('Sync Now') }}
                        </a>
                    </div>
                </div>
            @endif
            <div class="customers__area bg-style mb-30">
                <div class="mb-3 d-flex justify-content-end">
                    <button id="bulkActiveBtn" class="btn btn-primary d-none">
                        <i class="fas fa-check-double me-1"></i> {{ __('Bulk Activate') }}
                    </button>
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

    <!-- Stock Breakdown Modal -->
    <div class="modal fade" id="stockBreakdownModal" tabindex="-1" role="dialog" aria-labelledby="stockBreakdownModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="stockBreakdownModalLabel">
                        <i class="fas fa-boxes mr-2"></i>{{ __('Stock Breakdown') }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4 p-3 bg-light rounded">
                        <h4 id="sb-product-name" class="font-weight-bold text-primary mb-3"></h4>
                        <div class="h5 mb-0">
                            <span class="text-muted">{{ __('Virtual Stock Limit:') }}</span>
                            <span id="sb-virtual-stock" class="badge badge-success ml-2"
                                style="font-size: 1.2rem; padding: 0.5rem 1rem;"></span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="text-center" style="width: 40%;">{{ __('Component') }}</th>
                                    <th class="text-center" style="width: 15%;">{{ __('Required') }}</th>
                                    <th class="text-center" style="width: 20%;">{{ __('Current Stock') }}</th>
                                    <th class="text-center" style="width: 25%;">{{ __('Possible') }}</th>
                                </tr>
                            </thead>
                            <tbody id="sb-components-table">
                                <!-- Populated via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal"><i
                            class="fas fa-times mr-1"></i>{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
    @push('post_scripts')
        <script src="{{ asset('backend/js/admin/datatables/product.js') }}"></script>
    @endpush
@endsection