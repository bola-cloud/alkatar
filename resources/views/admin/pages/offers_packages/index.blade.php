@extends('admin.master', ['menu' => 'products', 'submenu' => 'offers_packages'])
@section('title', isset($title) ? $title : '')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>{{ __('Offers Packages') }}</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Offers Packages') }}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="customers__area bg-style">
                <div class="item-title d-flex justify-content-between align-items-center mb-30">
                    <h2>{{ __('Offers Packages') }}</h2>
                    <a href="{{ route('admin.offers-packages.create') }}" class="btn btn-success">
                        <i class="fa fa-plus-circle mr-1"></i> {{ __('Create Offers Package') }}
                    </a>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="customers__table">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Image') }}</th>
                                <th>{{ __('Package Name') }}</th>
                                <th>{{ __('Price Before Offer') }}</th>
                                <th>{{ __('Price After Offer') }}</th>
                                <th>{{ __('Available Quantity') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($packages as $package)
                                <tr>
                                    <td>{{ $loop->iteration + ($packages->currentPage() - 1) * $packages->perPage() }}</td>
                                    <td>
                                        <img src="{{ asset(ProductImage() . $package->Primary_Image) }}" 
                                             alt="{{ $package->localized_name }}" 
                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;"
                                             onerror="this.onerror=null;this.src='{{ asset(ProductImage() . 'prod.png') }}';">
                                    </td>
                                    <td>
                                        <strong>{{ $package->localized_name }}</strong>
                                        <div class="text-muted small">{{ $package->en_Product_Name }}</div>
                                    </td>
                                    <td>
                                        <span style="text-decoration: line-through; color: #dc3545;">
                                            {{ function_exists('currencyConverter') ? currencyConverter($package->Price) : number_format($package->Price, 3) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="font-weight: bold; color: #28a745;">
                                            {{ function_exists('currencyConverter') ? currencyConverter($package->Discount_Price) : number_format($package->Discount_Price, 3) }}
                                        </span>
                                    </td>
                                    <td>{{ $package->Quantity }}</td>
                                    <td>
                                        @if($package->Status == 1)
                                            <a href="{{ route('admin.offers-packages.status', $package->id) }}" class="badge badge-success" style="padding: 6px 12px; font-size: 0.85rem;">
                                                <i class="fas fa-check-circle mr-1"></i> {{ __('Active') }}
                                            </a>
                                        @else
                                            <a href="{{ route('admin.offers-packages.status', $package->id) }}" class="badge badge-secondary" style="padding: 6px 12px; font-size: 0.85rem;">
                                                <i class="fas fa-times-circle mr-1"></i> {{ __('Inactive') }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action__buttons" style="display: flex; gap: 8px;">
                                            <a href="{{ route('admin.offers-packages.edit', $package->id) }}" class="btn btn-primary btn-sm" title="{{ __('Edit') }}" style="font-size: 0.9rem; padding: 4px 8px;">
                                                <i class="fa fa-pen-to-square"></i>
                                            </a>
                                            <a href="javascript:void(0);" 
                                               class="btn btn-danger btn-sm delete-btn" 
                                               data-id="{{ $package->id }}"
                                               data-url="{{ route('admin.offers-packages.delete', $package->id) }}"
                                               title="{{ __('Delete') }}" 
                                               style="font-size: 0.9rem; padding: 4px 8px;">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">{{ __('No Offers Packages Found!') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $packages->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">{{ __('Delete Confirmation') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ __('Are you sure you want to delete this package?') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <a href="" id="confirmDeleteLink" class="btn btn-danger">{{ __('Delete') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('post_scripts')
    <script>
        $(document).ready(function() {
            $('.delete-btn').on('click', function() {
                var url = $(this).data('url');
                $('#confirmDeleteLink').attr('href', url);
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endpush
