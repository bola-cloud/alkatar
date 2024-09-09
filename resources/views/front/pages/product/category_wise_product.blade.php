@extends('front.layouts.master')
@section('title', isset($title) ? $title : 'Home')
@section('description', isset($description) ? $description : '')
@section('keywords', isset($keywords) ? $keywords : '')
@section('content')
<style>
    .active {
        outline: 0 none;
        color: var(--hover-color);
    }
    .filter-btn{
        position: absolute;
        right: 3%;
        /* bottom: 55%; */
        color: black;
    }
</style>

<!-- Product Area Start -->
    <!-- <input type="hidden" value="{{$selected_category}}" id = "category_id"> -->
    <button class="btn btn-primary filter-btn d-lg-none btn-lg" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
    <i class="fas fa-filter"></i>
    {{ __('Sub-Categories') }}
    </button>
<div class="product-area section">
    <div>
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <div class="sidebar-widget-area mobile-sidebar">
                    <div class="sidebar-widget-header d-block d-lg-none">
                        <div class="widget-header-wrap">
                            <h5 class="offcanvas-title">{{ __('Sub-Categories') }}</h5>
                            <button type="button" class="btn-close text-reset sidebar-close"></button>
                        </div>
                    </div>

                    <div class="single-widget categories-widget">
                        <h3 class="widget-title">{{ __('Sub-Categories') }}</h3>
                        <div class="categories-list">
                            <div class="single-categorie">
                                <div class="categorie-left flex items-center gap-2">
                                    <input class="form-check-input" type="checkbox" value="all"
                                        id="product_subCategory_all">
                                    <label class="form-check-label"
                                        for="product_subCategory_all">{{ __('All') }}</label>
                                </div>
                            </div>
                            @foreach (SubCategory($selected_category) as $subCategory)
                                <div class="single-categorie">
                                    <div class="categorie-left flex items-center gap-2">
                                        <input class="form-check-input CheckSubCategory" type="checkbox"
                                            value="{{ $subCategory->id }}" id="product_subCategory_{{$subCategory->id}}">
                                        <label class="form-check-label"
                                            for="product_subCategory_{{$subCategory->id}}">{{ langConverter($subCategory->name, $subCategory->name_ar) }}</label>
                                    </div>
                                    <span class="categories-count">{{ productSubCategoryCount($subCategory->id) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                        </div>
            </div>
            <div class="col-xl-9 col-lg-8">
                <div class="product-section-top">
                </div>

                <div id="filterProduct">
                    <div class="product-list">
                        <div class="grid grid-cols-2 lg:grid-cols-3">
                            @forelse($products as $product)
                                <x-frontend.product-card :product="$product" />
                            @empty
                                <p class="grid place-items-center mt-32 font-bold text-3xl">{{__("No Products Found!")}}</p>
                            @endforelse
                        </div>
                        <div class="pagination-area" style="margin-block: 30px;">
                            <ul class="paginations text-center">
                                {{ $products->links('vendor.pagination.custom') }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- For Mobile Filter Sidebar Start -->
    <div class="offcanvas offcanvas-start !w-[250px]" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">{{ __('Filter') }}</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="sidebar-widget-area">
                <!-- <div class="single-widget search-widget p-0 bg-transparent">
                    <h3 class="widget-title">{{ __('Search Here') }}</h3>
                    <form action="#">
                        <div class="form-group">
                            <input type="text" class="form-control bg-color" id="searchWidgetMobile"
                                name="searchWidgetMobile" placeholder="{{ __('Product Store') }}" />
                            <button type="button" class="search-btn searchWidgetMobile"><i
                                    class="flaticon-search"></i></button>
                        </div>
                    </form>
                </div> -->
                <div class="single-widget categories-widget p-0 bg-transparent">
                    <h3 class="widget-title">{{ __('Sub-Categories') }}</h3>
                    <div class="categories-list">
                    <div class="single-categorie">
                                <div class="categorie-left flex items-center gap-2">
                                    <input class="form-check-input" type="checkbox" value="all"
                                        id="product_subCategoryMobile_all">
                                    <label class="form-check-label"
                                        for="product_subCategoryMobile_all">{{ __('All') }}</label>
                                </div>
                            </div>
                            @foreach (SubCategory($selected_category) as $subCategory)
                            <div class="single-categorie">
                                <div class="categorie-left">
                                    <input class="form-check-input CheckCategoryMobile" type="checkbox"
                                    value="{{ $subCategory->id }}" id="product_subCategory_{{$subCategory->id}}">
                                    <label for="product_subCategory_{{$subCategory->id}}" class="form-check-label">{{ langConverter($subCategory->name, $subCategory->name_ar) }}</label>
                                </div>
                                <span class="categories-count">{{ productSubCategoryCount($subCategory->id) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
             
            </div>
        </div>


    </div>
    <!-- For Mobile Filter Sidebar End -->

    <script>
        var specificId = {{$selected_category}};
        var element = document.getElementById(specificId);
        if (element) {
            element.classList.toggle('active');
        }

    </script>

    <script>
        document.getElementById('product_subCategory_all').addEventListener('change', function () {
            var checkboxes = document.querySelectorAll('.CheckSubCategory');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = this.checked;
            }
        });
    </script>


<script>
        document.getElementById('product_subCategoryMobile_all').addEventListener('change', function () {
            var checkboxes = document.querySelectorAll('.CheckCategoryMobile');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = this.checked;
            }
        });
    </script>

    <!-- Product Area End -->
    <div id="shortingUrl" data-url="{{ route('product.shorting') }}"></div>
    <div id="checkCategoryFilter" data-url="{{ route('product.filtering') }}"></div>

    <div id="CheckSubCategoryFilter" data-url="{{ route('product.filtering') }}"></div>
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