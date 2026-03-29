@extends('front.layouts.new_design_layout')
@section('title', isset($slug) ? ucfirst($slug) : 'Category')
@section('content')

    {{-- banner included right after the navbar so it appears as part of header --}}
    @include('front.partials.category_banner', ['title' => $title ?? (isset($slug) ? ucfirst($slug) : 'Category')])

    <div class="container py-4">
        <input type="hidden" name="quantity" value="1" id="product_quantity">
        <div class="row mt-5">
            <div class="col-12 mb-4">
                <h2 class="category-title">{{ $title ?? (isset($slug) ? ucfirst($slug) : 'Category Products') }}</h2>
                <button class="filter-btn btn btn-outline-secondary d-md-none"> <i class="bi bi-funnel-fill"></i> Filter
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @php
                    // Determine display locale for selecting which DB column to show.
                    // Use HTML_LANG (set by localeSwitch) or fall back to lang_dir to detect RTL.
                    $htmlLang = session('HTML_LANG', null);
                    $langDir = session('lang_dir', null);
                    $isDisplayAr = ($htmlLang === 'ar') || ($langDir === 'rtl');
                @endphp

                <div class="product-grid">
                    @if(!empty($products) && count($products))
                        @foreach($products as $p)
                            <div class="product-card" dir="{{ $isDisplayAr ? 'rtl' : 'ltr' }}">
                                <div class="card-wrap" style="background-color: #f8f9fa; min-height: 200px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                    <a href="{{ route('single.product.new', $p->en_Product_Slug) }}" class="card-image-link w-100 h-100 d-flex align-items-center justify-content-center">
                                        @php
                                            $imgSrc = asset(ProductImage() . 'prod.png');
                                            $pi = $p->Primary_Image ?? null;
                                            if ($pi) {
                                                if (filter_var($pi, FILTER_VALIDATE_URL)) {
                                                    $imgSrc = $pi;
                                                } elseif (strpos($pi, 'uploaded_files/') === 0) {
                                                    $imgSrc = asset($pi);
                                                } else {
                                                    $imgSrc = asset(ProductImage() . $pi);
                                                }
                                            }
                                        @endphp
                                        <img src="{{ $imgSrc }}"
                                            alt="{{ $isDisplayAr ? ($p->ar_Product_Name ?? $p->fr_Product_Name ?? $p->en_Product_Name ?? $p->name ?? __('Product')) : ($p->en_Product_Name ?? $p->ar_Product_Name ?? $p->fr_Product_Name ?? $p->name ?? __('Product')) }}"
                                            class="card-category" style="text-decoration: none !important; width: 100%; height: 200px; object-fit: contain;"
                                            onerror="this.onerror=null;this.src='{{ asset(ProductImage() . 'prod.png') }}';">
                                    </a>
                                    <a href="javascript:void(0)" class="card-heart MyWishList" data-id="{{ $p->id }}"
                                        title="{{ __('Add To Wishlist') }}">
                                        <i class="{{ isInWishlist($p->id) ? 'bi bi-heart-fill text-danger' : 'bi bi-heart' }}"></i>
                                    </a>
                                </div>
                                <div class="product-body {{ $isDisplayAr ? 'text-end' : '' }}">
                                    <div class="product-title">
                                        @php
                                            // Prefer explicit Arabic field, fall back to legacy/fr or English fields
                                            $productNameAr = $p->ar_Product_Name ?? $p->fr_Product_Name ?? null;
                                            $productNameEn = $p->en_Product_Name ?? $p->name ?? null;
                                        @endphp
                                        @if($isDisplayAr)
                                            <a href="{{ route('single.product.new', $p->en_Product_Slug) }}"
                                                style="text-decoration: none !important; color :black !important">{{ $productNameAr ?? $productNameEn ?? __('Product') }}</a>
                                        @else
                                            <a href="{{ route('single.product.new', $p->en_Product_Slug) }}"
                                                style="text-decoration: none !important; color :black !important">{{ $productNameEn ?? $productNameAr ?? __('Product') }}</a>
                                        @endif
                                    </div>
                                    <div class="product-unit mb-2">
                                        @if($p->unit)
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-box-seam"></i> {{ $p->unit }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="product-price">
                                        @php
                                            // Prefer product-level Price as base price (old design behavior)
                                            $basePrice = $p->Price ?? 0;
                                            if (empty($basePrice) || $basePrice == 0) {
                                                if ($p->weights && $p->weights->count()) {
                                                    $basePrice = $p->weights->first()->price;
                                                } elseif ($p->sizes && $p->sizes->count()) {
                                                    $firstSize = $p->sizes->first();
                                                    $basePrice = $firstSize?->pivot->price ?? 0;
                                                }
                                            }
                                            $displayPrice = ($p->Discount > 0 && $p->Discount_Price) ? $p->Discount_Price : $basePrice;
                                        @endphp

                                        @if ($p->Discount > 0 && $p->Discount_Price && $basePrice != $displayPrice)
                                            <span class="regular-price">{{ currencyConverter($basePrice) }}</span>
                                            <span class="price">{{ currencyConverter($displayPrice) }}</span>
                                        @else
                                            <span class="price">{{ currencyConverter($displayPrice) }}</span>
                                        @endif
                                    </div>
                                    <div class="rating">★★★★★</div>
                                    <div style="text-align:center;margin-top:8px">
                                        <a href="javascript:void(0)" title="{{ __('Add To Cart') }}" class="add-cart addCart"
                                            data-id="{{ $p->id }}" data-price="{{ $displayPrice }}"
                                            data-base-price="{{ $basePrice }}" data-discount="{{ $p->Discount_Price ?? 0 }}"
                                            data-percenteng="{{ $p->Discount ?? 0 }}"
                                            data-name="{{ $isDisplayAr ? ($p->ar_Product_Name ?? $p->fr_Product_Name ?? $p->en_Product_Name ?? '') : ($p->en_Product_Name ?? $p->ar_Product_Name ?? $p->fr_Product_Name ?? '') }}"
                                            data-sizes='{{ json_encode($p->sizes()->withPivot("price")->get() ?? []) }}'
                                            data-additions='{{ json_encode($p->additions ?? []) }}'
                                            data-weights='{{ json_encode($p->weights ?? []) }}' data-unit='{{ $p->unit ?? '' }}'>
                                            <i class="bi bi-bag-plus"></i>&nbsp; {{ __('Add To Cart') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                @if(method_exists($products, 'links'))
                    <div class="row">
                        <div class="col-12 mt-4">
                            <div class="pagination-wrapper">
                                {{ $products->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

@endsection