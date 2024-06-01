@extends('front.layouts.master')
@section('title', isset($title) ? $title : 'Home')
@section('description', isset($description) ? $description : '')
@section('keywords', isset($keywords) ? $keywords : '')
@section('content')
<!-- Product Area Start -->
<div class="product-area section">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <div class="sidebar-widget-area mobile-sidebar">
                    <div class="sidebar-widget-header d-block d-lg-none">
                        <div class="widget-header-wrap">
                            <h5 class="offcanvas-title">{{ __('Filter') }}</h5>
                            <button type="button" class="btn-close text-reset sidebar-close"></button>
                        </div>
                    </div>

                    {{-- <div class="single-widget search-widget">
                        <h3 class="widget-title">{{ __('Search Here') }}</h3>
                        <form>
                            <div class="form-group">
                                <input type="text" class="form-control" id="searchwidget" name="searchwidget"
                                    placeholder="{{ __('Product Store') }}" />
                                <button type="button" class="search-btn"><i
                                        class="flaticon-search searchWidget"></i></button>
                            </div>
                        </form>
                    </div> --}}

                    <div class="single-widget categories-widget">
                        <h3 class="widget-title">{{ __('Categories') }}</h3>
                        <div class="categories-list">
                            @foreach (Category_Des_Icon() as $category)
                            <div class="single-categorie">
                                <div class="categorie-left">
                                    <input class="form-check-input CheckCategory" type="checkbox"
                                        value="{{ $category->en_Category_Name }}">
                                    <label class="form-check-label">{{ langConverter($category->en_Category_Name, $category->fr_Category_Name ) }}</label>
                                </div>
                                <span class="categories-count">{{ productCategoryCount($category->id) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
{{-- 
                    <div class="single-widget price-widget">
                        <h3 class="widget-title">{{ __('Price') }}</h3>
                        <form>
                            <div class="price-wrap">
                                <div class="price-wrap-left">
                                    <div class="single-price">
                                        <input type="number" class="form-control" id="minPrice" name="min_price"
                                            placeholder="{{ __('$ Min') }}" min="1" />
                                    </div>
                                    <div class="single-price">
                                        <input type="number" class="form-control" id="maxPrice" name="max_price"
                                            placeholder="{{ __('$ Max') }}" />
                                    </div>
                                </div>
                                <button type="button" class="price-submit PriceSubmit"><i
                                        class="fas fa-play"></i></button>
                            </div>
                        </form>
                    </div> --}}

                    {{-- <div class="single-widget size-widget">
                        <h3 class="widget-title">{{ __('Size') }}</h3>
                        <div class="size-list">
                            @foreach ($sizes as $size)
                            <div class="single-size">
                                <input class="form-check-input checkSize" type="checkbox" id="{{ $size->id }}"
                                    value="{{ $size->Size }}">
                                <label class="form-check-label" for="{{ $size->id }}">{{ $size->Size }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="col-xl-9 col-lg-8">
                <div class="product-section-top">
                    {{-- <div class="row align-items-center">
                        <div class="col-md-3">
                            <div class="product-section-top-left">
                                <button class="sidebar-filter d-block d-lg-none" type="button"
                                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
                                    aria-controls="offcanvasExample">
                                    {{ __('Filter') }} <img src="{{ asset('frontend/assets/images/angle-down.svg') }}"
                                        alt="angle-down" />
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-5">
                            <div class="form-group flex items-center">
                                <label for="sortBy" class="w-full">Sort By:</label>
                                <select id="sortBy" class="form-control w-36" onchange="sortRecords(this.value)">
                                    <option value="latest">Latest</option>
                                    <option value="highest">Highest Price</option>
                                    <option value="lowest">Lowest Price</option>
                                </select>
                            </div>
                            <p class="!text-end font-bold text-2xl">{{ __("Total Results:", ['count' =>
                                count($products)])}}</p>
                        </div>
                    </div> --}}
                </div>

                <div id="filterProduct">
                    <div class="product-list">
                        <div class="row">
                            @forelse($products as $product)
                            <x-frontend.product-card :product="$product" />
                            @empty
                            <p class="grid place-items-center mt-32 font-bold text-3xl">{{__("No Products Found!")}}
                            @endforelse
                        </div>
                        <div class="pagination-area mt-30">
                            <ul class="paginations text-center">
                                {{ $products->links('vendor.pagination.custom') }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- For Mobile Filter Sidebar Start -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">{{ __('Filter') }}</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="sidebar-widget-area">
            <div class="single-widget search-widget p-0 bg-transparent">
                <h3 class="widget-title">{{ __('Search Here') }}</h3>
                <form action="#">
                    <div class="form-group">
                        <input type="text" class="form-control bg-color" id="searchWidgetMobile"
                            name="searchWidgetMobile" placeholder="{{ __('Product Store') }}" />
                        <button type="button" class="search-btn searchWidgetMobile"><i
                                class="flaticon-search"></i></button>
                    </div>
                </form>
            </div>
            <div class="single-widget categories-widget p-0 bg-transparent">
                <h3 class="widget-title">{{ __('Categories') }}</h3>
                <div class="categories-list">
                    @foreach (Category_Des_Icon() as $category)
                    <div class="single-categorie">
                        <div class="categorie-left">
                            <input class="form-check-input CheckCategoryMobile" type="checkbox"
                                value="{{ $category->en_Category_Name }}">
                            <label class="form-check-label">{{ $category->en_Category_Name }}</label>
                        </div>
                        <span class="categories-count">{{ productCategoryCount($category->id) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="single-widget price-widget p-0 bg-transparent">
                <h3 class="widget-title">{{ __('Price') }}</h3>
                <form>
                    <div class="price-wrap">
                        <div class="price-wrap-left">
                            <div class="single-price">
                                <input type="number" class="form-control" id="minPriceMobile" name="minprice1"
                                    placeholder="{{ currencySymbol()[currency()] }} {{ __('Min') }}" />
                            </div>
                            <div class="single-price">
                                <input type="number" class="form-control" id="maxPriceMobile" name="maxprice1"
                                    placeholder="{{ currencySymbol()[currency()] }} {{ __('Max') }}" />
                            </div>
                        </div>
                        <button type="button" class="price-submit PriceSubmitMobile"><i
                                class="fas fa-play"></i></button>
                    </div>
                </form>
            </div>

            {{-- <div class="single-widget size-widget p-0 bg-transparent">
                <h3 class="widget-title">{{ __('Size') }}</h3>
                <div class="size-list">

                    @foreach ($sizes as $size)
                    <div class="single-size">
                        <input class="form-check-input checkSizeMobile" type="checkbox" id="{{ $size->id }}"
                            value="{{ $size->Size }}">
                        <label class="form-check-label" for="{{ $size->id }}">{{ $size->Size }}</label>
                    </div>
                    @endforeach

                </div>
            </div> --}}
        </div>
    </div>


</div>
<!-- For Mobile Filter Sidebar End -->

<!-- Product Area End -->
<div id="shortingUrl" data-url="{{ route('product.shorting') }}"></div>
<div id="checkCategoryFilter" data-url="{{ route('product.filtering') }}"></div>

<div id="checkCategoryFilter" data-url="{{ route('product.filtering') }}"></div>
<div id="checkColorFilter" data-url="{{ route('product.filtering') }}"></div>
<div id="checkBrandFilter" data-url="{{ route('product.filtering') }}"></div>
<div id="checkSizeFilter" data-url="{{ route('product.filtering') }}"></div>
<div id="searchWidgetFilter" data-url="{{ route('product.filtering') }}"></div>
<div id="minMaxPriceFilter" data-url="{{ route('product.filtering') }}"></div>

<div id="AddToCompareItemUrl" data-url="{{ route('compare.add') }}"></div>
<div id="AddToCartIntoSession" data-url="{{ route('add.to.cart') }}"></div>
<div id="productWishlistUrl" data-url="{{ route('wishlist.add') }}"></div>
<div id="productImgAsset" data-url="{{ asset(ProductImage()) }}"></div>

@push('post_script')
<script src="{{ asset('frontend/assets/js/pages/product.js') }}"></script>
@endpush
@endsection