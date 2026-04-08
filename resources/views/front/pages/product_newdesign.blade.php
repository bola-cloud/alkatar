@extends('front.layouts.new_design_layout')
@section('title', isset($product) ? (langConverter($product->en_Product_Name, $product->fr_Product_Name)) : 'Product')
@section('content')

    @php
        // Determine localized title (support Arabic RTL fallback like category view)
        // Session keys used by language switchers: 'HTML_LANG', 'APP_LOCALE'
        $htmlLang = session('HTML_LANG', null);
        $langDir = session('lang_dir', null);
        $appLocale = app()->getLocale();

        // Robust check for Arabic interface based on Laravel locale
        $isDisplayAr = in_array(app()->getLocale(), ['ar', 'fr']);

        // Legacy data stores Arabic text in `fr_Product_Name` for many records.
        // Prefer `fr_Product_Name` as the Arabic source first, then `ar_Product_Name`.
        $productNameAr = $product->fr_Product_Name ?? $product->ar_Product_Name ?? null;
        $productNameEn = $product->en_Product_Name ?? $product->name ?? null;

        $localizedTitle = $isDisplayAr ? ($productNameAr ?? $productNameEn ?? __('Product')) : ($productNameEn ?? $productNameAr ?? __('Product'));
    @endphp

    {{-- reuse category banner for layout consistency --}}
    @include('front.partials.category_banner', ['title' => $localizedTitle ?? 'Product'])

    <style>
        /* Hide empty list items and common placeholder "dots" */
        .product-description-container ul:empty,
        .product-description-container li:empty,
        .product-description-container li:only-child:empty {
            display: none !important;
        }
        /* Robust cleanup: hide list items that might contain only whitespace/dots from the editor */
        .product-description-container li {
            list-style: none; /* Default: hide bullet */
            position: relative;
            padding-left: 0;
            min-height: 0;
        }
        /* Re-enable list style only for items that have actual text content */
        .product-description-container li:not(:empty) {
            list-style: disc;
            margin-left: 1.5rem;
        }

        /* Star rating styles to match Figma */
        .star-rating .bi-star-fill { color: #ffb400; margin-right: 2px; }
        .star-rating .bi-star { color: #e0e0e0; margin-right: 2px; }
    </style>
    <div class="container py-5 product-single-area">
        <input type="hidden" name="quantity" value="1" id="product_quantity">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="product-gallery d-flex">
                    <div class="gallery-thumbs me-3 d-none d-md-block" style="width:72px">
                            @if(isset($product->Primary_Image) && $product->Primary_Image)
                                @php
                                    $primary = $product->Primary_Image;
                                    $thumbUrl = asset('https://via.placeholder.com/64');
                                    if (file_exists(public_path('uploaded_files/product_image/' . $primary))) {
                                        $thumbUrl = asset('uploaded_files/product_image/' . $primary);
                                    } elseif (file_exists(public_path(ProductImage() . $primary))) {
                                        $thumbUrl = asset(ProductImage() . $primary);
                                    }
                                @endphp
                                <div class="thumb mb-2"><img src="{{ $thumbUrl }}" alt="{{ $title }}" style="width:100%; height:64px; object-fit:cover; border-radius:6px;" onerror="this.onerror=null;this.src='{{ asset(ProductImage() . 'prod.png') }}';"></div>
                            @endif
                            @foreach($product->images ?? [] as $img)
                                @php
                                    $imgName = $img->file_name ?? $img;
                                    $imgUrl = asset('new-design/images/special-offer.png');
                                    if (file_exists(public_path('uploaded_files/product_image/' . $imgName))) {
                                        $imgUrl = asset('uploaded_files/product_image/' . $imgName);
                                    } elseif (file_exists(public_path(ProductImage() . $imgName))) {
                                        $imgUrl = asset(ProductImage() . $imgName);
                                    }
                                @endphp
                                <div class="thumb mb-2"><img src="{{ $imgUrl }}" alt="{{ $title }}" style="width:100%; height:64px; object-fit:cover; border-radius:6px;" onerror="this.onerror=null;this.src='{{ asset(ProductImage() . 'prod.png') }}';"></div>
                            @endforeach
                    </div>

                    <div class="gallery-main flex-grow-1">
                        @php
                            $mainUrl = asset('https://via.placeholder.com/480x420?text=No+Image');
                            if (isset($product->Primary_Image) && $product->Primary_Image) {
                                $primary = $product->Primary_Image;
                                if (file_exists(public_path('uploaded_files/product_image/' . $primary))) {
                                    $mainUrl = asset('uploaded_files/product_image/' . $primary);
                                } elseif (file_exists(public_path(ProductImage() . $primary))) {
                                    $mainUrl = asset(ProductImage() . $primary);
                                }
                            }
                        @endphp
                        <div class="main-image border rounded p-3 text-center position-relative" style="background:#fff;">
                            <div class="position-absolute top-0 end-0 p-3 d-flex gap-2">
                                <button class="btn btn-light rounded-circle shadow-sm border lh-1" style="width:40px;height:40px;color:#84b718;" title="{{ __('Wishlist') }}">
                                    <i class="bi bi-heart"></i>
                                </button>
                                <button id="shareProduct" class="btn btn-light rounded-circle shadow-sm border lh-1" style="width:40px;height:40px;color:#84b718;" title="{{ __('Share') }}">
                                    <i class="bi bi-share"></i>
                                </button>
                            </div>
                            <img src="{{ $mainUrl }}" alt="{{ $title }}" style="max-width:100%; max-height:420px; object-fit:contain;" onerror="this.onerror=null;this.src='{{ asset(ProductImage() . 'prod.png') }}';">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="product-detail">
                    <h1 class="product-title mb-1">
                        @if($isDisplayAr)
                            {{ $productNameAr ?? $productNameEn ?? __('Product') }}
                        @else
                            {{ $productNameEn ?? $productNameAr ?? __('Product') }}
                        @endif
                    </h1>

                    <div class="mb-3 d-flex align-items-center gap-2 star-rating">
                        @php
                            $avgRating = \App\Models\ProductReview::where('product_id', $product->id)->avg('rating') ?: 5;
                            $countReview = \App\Models\ProductReview::where('product_id', $product->id)->count();
                        @endphp
                        <div class="d-flex gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= round($avgRating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </div>
                        <span class="text-muted" style="font-size: 0.9rem; margin-left: 8px;">
                            {{ $countReview }} {{ __('Review') }}
                        </span>
                    </div>

                    {{-- Sold and unit hidden --}}
                    {{-- 
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">{!! productReview($product->id) !!}</div>
                        <div class="text-muted ms-auto">{{ $product->Sold ?? 0 }} {{ __('Sold') }}</div>
                    </div>

                    @if($product->unit)
                        <div class="mb-3">
                            <span class="badge bg-light text-dark border" style="font-size:0.95rem;padding:8px 14px;">
                                <i class="bi bi-box-seam me-1"></i> {{ $product->unit }}
                            </span>
                        </div>
                    @endif 
                    --}}


                    {{-- Price block --}}
                    @php
                        $basePrice = $product->Price ?? 0;
                        if (empty($basePrice) || $basePrice == 0) {
                            if ($product->weights && $product->weights->count()) {
                                $basePrice = $product->weights->first()->price;
                            } elseif ($product->sizes && $product->sizes->count()) {
                                $firstSize = $product->sizes->first();
                                $basePrice = $firstSize?->pivot->price ?? 0;
                            }
                        }
                        $displayPrice = ($product->Discount > 0 && $product->Discount_Price) ? $product->Discount_Price : $basePrice;

                        // Custom currency formatting for OMR in Arabic
                        $currencySymbol = config('app.currency_symbol', '$');
                        $formattedBase = currencyConverter($basePrice);
                        $formattedDisplay = currencyConverter($displayPrice);

                        if ($isDisplayAr) {
                            $currencySymbol = 'ر.ع';
                            // Strip existing alpha chars (like 'OMR') from the formatted string, keep numbers and dots
                            // This is a quick fix to replace OMR with ر.ع
                            $formattedBase = str_replace('OMR', '', $formattedBase);
                            $formattedBase = trim($formattedBase) . ' ' . $currencySymbol;

                            $formattedDisplay = str_replace('OMR', '', $formattedDisplay);
                            $formattedDisplay = trim($formattedDisplay) . ' ' . $currencySymbol;
                        }
                    @endphp

                    <div class="price-block mb-4 d-flex align-items-center gap-2">
                        @if($product->Discount > 0 && $product->Discount_Price && $basePrice != $displayPrice)
                            <div class="text-muted text-decoration-line-through price-strikethrough fs-5">{{ $formattedBase }}</div>
                            <div class="h2 mb-0 text-success fw-bold">{{ $formattedDisplay }}</div>
                            <div class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2" style="font-size: 0.8rem;">
                                {{ $product->Discount }}% {{ __('Off') }}
                            </div>
                        @else
                            <div class="h2 mb-0 fw-bold" style="color: #2c3e50">{{ $formattedDisplay }}</div>
                        @endif
                    </div>

                    <hr class="my-4" style="opacity:0.1">

                    {{-- Brand --}}
                    @if(isset($product->Brand) && $product->Brand)
                        <div class="mb-4 d-flex align-items-center">
                            <span class="text-muted me-2">{{ __('Brand') }}:</span> 
                            <img src="{{ asset($product->Brand->logo ?? '') }}" alt="{{ $product->Brand->name ?? '' }}" style="height:32px; object-fit:contain;">
                        </div>
                    @endif

                    {{-- Description(s) --}}
                    @php
                        // Function to clean and check if content exists
                        $cleaner = function ($content) {
                            if (!$content)
                                return '';
                            $c = trim(strip_tags($content));
                            $c = str_replace(['&nbsp;', '&zwnj;', '&raquo;', '•'], '', $c);
                            return trim($c);
                        };

                        // Strictly use our robust isDisplayAr flag for locale detection
                        $showAr = $isDisplayAr;

                        $descAr = $product->fr_Description ?? $product->ar_Description;
                        $descEn = $product->en_Description;

                        // Decide what to show based on locale - NO fallback to avoid bilingual mess.
                        // If one field contains both languages, it's a data issue that shouldn't be fixed by code duplication.
                        $finalDesc = $showAr ? $descAr : $descEn;
                    @endphp

                    <div class="product-description-container mb-4 text-muted" style="line-height: 1.6;">
                        @php
                            // Smart Script Filter: If a field contains both languages in separate tags, 
                            // filter by active locale script to avoid "bilingual mess".
                            if ($finalDesc) {
                                // Split by common block-level endings (p, div, li, br)
                                $parts = preg_split('/(<\/p>|<\/div>|<\/li>|<br\s*\/?>)/i', $finalDesc, -1, PREG_SPLIT_DELIM_CAPTURE);
                                $filteredHtml = '';

                                for ($i = 0; $i < count($parts); $i += 2) {
                                    $content = $parts[$i];
                                    $delimiter = $parts[$i + 1] ?? '';

                                    $text = trim(strip_tags($content));
                                    if (empty($text)) {
                                        // Keep empty blocks/line breaks only if we already have some content
                                        if (!empty($filteredHtml))
                                            $filteredHtml .= $content . $delimiter;
                                        continue;
                                    }

                                    // Arabic script detection
                                    $hasAr = preg_match('/[\x{0600}-\x{06FF}]/u', $text);
                                    // Latin script detection
                                    $hasEn = preg_match('/[a-zA-Z]/', $text);

                                    if ($showAr) {
                                        // In Arabic mode, keep if has Arabic OR has strictly no Latin (symbols/numbers)
                                        if ($hasAr || !$hasEn)
                                            $filteredHtml .= $content . $delimiter;
                                    } else {
                                        // In English mode, keep if has Latin OR has strictly no Arabic
                                        if ($hasEn || !$hasAr)
                                            $filteredHtml .= $content . $delimiter;
                                    }
                                }

                                // Clean up trailing breaks/empty tags
                                $finalDesc = trim($filteredHtml) ?: $finalDesc;
                            }
                        @endphp
                        {!! $finalDesc !!}
                    </div>

                    {{-- Quantity and Add to Cart --}}
                    <div class="d-flex align-items-center gap-3 mb-5 mt-4">
                        <div class="quantity-wrapper d-flex align-items-center border rounded px-1" style="background: #f8f9fa; height: 50px;">
                            <button class="btn btn-link text-decoration-none text-dark qty-decrease p-2" type="button"><i class="bi bi-dash"></i></button>
                            <input type="text" class="form-control border-0 text-center bg-transparent p-0" value="1" id="product_quantity_main" style="width: 40px; font-weight: 600;">
                            <button class="btn btn-link text-decoration-none text-dark qty-increase p-2" type="button"><i class="bi bi-plus"></i></button>
                        </div>

                        <div class="flex-grow-1">
                             <a href="javascript:void(0)" class="btn btn-lg w-100 add-cart addCartModal shadow-sm" title="{{ __('Add to Cart') }}"
                               style="background-color: #84b718; border-color: #84b718; color: white; border-radius: 4px; font-weight: 600; padding: 12px 30px; height: 50px; display: flex; align-items: center; justify-content: center;"
                               data-id="{{ $product->id }}"
                               data-product-id="{{ $product->id }}"
                               data-price="{{ $displayPrice }}"
                               data-base-price="{{ $basePrice }}"
                               data-discount="{{ $product->Discount_Price ?? 0 }}"
                               data-percenteng="{{ $product->Discount ?? 0 }}"
                               data-name="{{ $localizedTitle }}"
                               data-sizes='@json($product->sizes()->withPivot("price")->get() ?? [])'
                               data-additions='@json($product->additions ?? [])'
                               data-weights='@json($product->weights ?? [])'
                               data-unit='{{ $product->unit ?? '' }}'
                            >
                                <i class="bi bi-bag me-2"></i> {{ __('Add to Cart') }}
                            </a>
                        </div>
                    </div>

                    {{-- Long Description Hidden as requested --}}
                    {{-- <div class="mt-4">
                        <h5>{{ __('Description') }}</h5>
                        <div class="text-muted">{!! $product->Product_Long_Description ?? $product->Long_Description ?? $product->Description ?? '' !!}</div>
                    </div> --}}

                    {{-- Customer feedback moved to its own row below --}}

                </div>
            </div>
        </div>

        {{-- New row: Reviews / Feedback placed under the product details, on the right column --}}
        @php
            // Use the loaded relation name `product_reviews` and ensure newest first
            // Only display reviews which admin marked visible (`is_visible = true`)
            $reviews = collect();
            if (!empty($product->product_reviews)) {
                $reviews = collect($product->product_reviews)->where('is_visible', true)->sortByDesc('created_at');
            }
        @endphp
        <div class="row mt-4">
            {{-- <div class="col-lg-6 d-none d-lg-block"><!-- spacer to align feedback on right --></div> --}}
            <div class="col-lg-12">
                <div class="card p-4" style="border-radius:12px;border:1px solid #f0f0f0;">
                    {{-- Heading with green underline to match design --}}
                    <h5 class="mb-3" style="position:relative;padding-bottom:12px;">
                        {{ __('Customer Feedback') }}
                        <span style="position:absolute;left:0;bottom:0;width:80px;height:3px;background:#2ecc71;border-radius:3px;"></span>
                    </h5>

                    {{-- Success flash styled like design --}}
                    @if(session('success'))
                        <div class="mb-3">
                            <div class="alert alert-success d-flex align-items-center" role="alert" style="border-radius:10px;">
                                <i class="bi bi-check-circle-fill me-2" style="font-size:1.2rem"></i>
                                <div style="flex:1">{{ session('success') }}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif

                    {{-- Reviews list (styled) --}}
                    <div id="reviews-list">
                        @if($reviews->isNotEmpty())
                            @foreach($reviews as $r)
                                @php
                                    $reviewerName = $r->user->name ?? $r->name ?? 'Anonymous';
                                    $reviewText = $r->feedback ?? $r->comment ?? $r->review ?? '';
                                    $reviewRating = $r->rating ?? $r->stars ?? 5;
                                    $reviewTime = isset($r->created_at) ? \Carbon\Carbon::parse($r->created_at)->diffForHumans() : '';
                                    $reviewAvatar = $r->user->image ?? null;
                                @endphp
                                <div class="d-flex align-items-start justify-content-between gap-3 mb-3" style="border-bottom:1px solid #f2f2f2;padding-bottom:12px;">
                                    <div class="d-flex align-items-start gap-3" style="flex:1">
                                        <div style="width:56px;flex:0 0 56px">
                                            @if($reviewAvatar)
                                                <img src="{{ asset('uploaded_files/admin_profile/' . $reviewAvatar) }}" style="width:56px;height:56px;border-radius:50%;object-fit:cover;" alt="{{ $reviewerName }}">
                                            @else
                                                <div style="width:56px;height:56px;border-radius:50%;background:#f0f0f0;display:flex;align-items:center;justify-content:center;font-weight:700;color:#2a6b2a">{{ strtoupper(substr($reviewerName, 0, 1)) }}</div>
                                            @endif
                                        </div>
                                        <div style="flex:1">
                                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                                <div>
                                                    <div style="font-weight:700">{{ $reviewerName }}</div>
                                                    <div style="margin-top:6px;display:flex;align-items:center;gap:6px;color:#ffb400;">
                                                        @for($i = 0; $i < 5; $i++)
                                                            {!! $i < $reviewRating ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>' !!}
                                                        @endfor
                                                    </div>
                                                </div>
                                                <div style="color:#7b7b7b;font-size:13px">{{ $reviewTime }}</div>
                                            </div>
                                            <div class="text-muted mt-2">{{ $reviewText }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Load more button (non-functional placeholder) --}}
                            <div class="text-center mt-3">
                                <button class="btn" style="background:#eaf7ec;color:#2a8f3a;border-radius:20px;padding:6px 18px;border:1px solid #e0f3df">{{ __('Load More') }}</button>
                            </div>
                        @else
                            <div class="text-muted">{{ __('No reviews yet. Be the first to review this product.') }}</div>
                        @endif
                    </div>

                    <hr />

                    {{-- Review form placed after the list, with a compact layout like design --}}
                    <div class="mt-3">
                        <form method="POST" action="{{ route('product.review.store', $product->id ?? null) }}" id="product-review-form">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id ?? '' }}" />
                            <input type="hidden" name="rating" id="review_rating" value="5" />
                            <div class="d-flex gap-3 align-items-start">
                                <div style="width:56px;flex:0 0 56px">
                                    @if(Auth::check() && Auth::user()->image)
                                        <img src="{{ asset('uploaded_files/admin_profile/' . Auth::user()->image) }}" style="width:56px;height:56px;border-radius:50%;object-fit:cover;" alt="{{ Auth::user()->name }}">
                                    @else
                                        <div style="width:56px;height:56px;border-radius:50%;background:#f0f0f0;display:flex;align-items:center;justify-content:center;font-weight:700;color:#2a6b2a">{{ Auth::check() ? strtoupper(substr(Auth::user()->name, 0, 1)) : 'G' }}</div>
                                    @endif
                                </div>
                                <div style="flex:1">
                                    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                                        <div style="display:flex;gap:12px;align-items:center;">
                                            <div style="font-weight:700">{{ Auth::check() ? Auth::user()->name : __('Guest') }}</div>
                                            <div id="star-picker-bottom" style="color:#ffb400;cursor:pointer;">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star star-bottom" data-value="{{ $i }}" style="font-size:1.05rem;"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-success" style="border-radius:20px;padding:8px 18px">{{ __('Submit Review') }}</button>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <textarea name="comment" id="review_comment" class="form-control" placeholder="{{ __('Write your review...') }}" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Related products (if available) --}}
        @if(isset($related) && $related->count())
            <div class="row mt-5">
                <div class="col-12">
                    <h4 class="mb-4" style="font-weight:700;">{{ __('Related Products') }}</h4>
                    {{-- reuse the home product carousel partial to ensure identical cards/layout --}}
                    @include('front.home.partials._product_carousel', ['products' => $related, 'carouselId' => 'relatedProductsCarousel'])
                </div>
            </div>
        @endif

    </div>

@endsection

@push('scripts')
    <script>
        (function($){
            // Custom Modal Logic for Product Details Page
            // This overrides logic in common.js which doesn't support modals on the product page

            const currencyLabel = "{{ __('OMR') }}";

            let pSelectedProductId = null;
            let pSelectedSizeId = null;
            let pSelectedSizePrice = 0;
            let pRefBasePrice = 0; // Reference price from the button (product price)
            let pSelectedAdditions = [];
            let pDiscountPercenteng = 0;
            let pOptionsHaveDiscount = false;

            $(document).on('click', '.addCartModal', function(e){
                e.preventDefault();
                let btn = $(this);
                pSelectedProductId = btn.data('id');
                pDiscountPercenteng = parseFloat(btn.data('percenteng')) || 0;
                pRefBasePrice = parseFloat(btn.data('price')) || 0;

                let sizes = btn.data('sizes') || [];
                let additions = btn.data('additions') || [];
                let weights = btn.data('weights') || [];
                let unit = btn.data('unit') || null;

                // Reset state
                pSelectedSizeId = null;
                pSelectedSizePrice = 0;
                pSelectedAdditions = [];

                function isEmptyVariation(val) {
                    if (val === undefined || val === null) return true;
                    if (typeof val === 'string') {
                        val = val.trim();
                        if (val === '' || val === '[]' || val === '{}') return true;
                        try {
                            let parsed = JSON.parse(val);
                            return isEmptyVariation(parsed);
                        } catch (e) {
                            return false;
                        }
                    }
                    if (Array.isArray(val)) {
                        return val.length === 0 || val.every(item => item === null || item === undefined || (typeof item === 'object' && Object.keys(item).length === 0));
                    }
                    if (typeof val === 'object') return Object.keys(val).length === 0;
                    return false;
                }

                // Simple product bypass logic: ignore unit for bypass decision
                var isBypass = isEmptyVariation(sizes) && isEmptyVariation(weights) && isEmptyVariation(additions);

                if (isBypass) {
                    var qty = $('#product_quantity').val() || 1;
                    // Pass unit if available, using qty as amount
                    var uAmount = (unit && unit.toString().trim() !== '') ? qty : null;
                    var uType = (unit && unit.toString().trim() !== '') ? unit : null;

                    window.performAddToCart(pSelectedProductId, qty, uAmount, uType, null, null, null, [], pRefBasePrice)
                        .done(function (data) {
                            window.showCartSuccess(data);
                        })
                        .fail(function (xhr) {
                            let msg = "Something went wrong!";
                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.error) {
                                msg = xhr.responseJSON.error;
                            }
                            Swal.fire({ icon: "error", title: msg });
                        });
                    return;
                }

                // Setup containers
                let sizeC = $('#sizeOptionsContainer').empty();
                $('#weightOptionsContainer').empty(); 
                let addC = $('#additionOptionsContainer').empty();

                $('#sizeOptionsSection').hide();
                $('#weightOptionsSection').hide();
                $('#additionOptionsSection').hide();
                $('#unitDisplaySection').hide();
                $('#submitSelection').hide();

                // 1. Handle Unit
                if(unit && unit.trim() !== ''){
                    $('#unitDisplayValue').text(unit);
                    let uInput = $('#unitAmount');
                    let uLower = unit.toLowerCase();
                    if(uLower.includes('kg')){
                         uInput.attr('step', '0.001').attr('min', '0.001').val('1');
                    } else {
                         uInput.attr('step', '1').attr('min', '1').val('1');
                    }
                    $('#unitDisplaySection').show();
                    $('#submitSelection').show();
                }

                // 2. Handle Sizes (with price logic)
                if(sizes.length > 0){
                    sizes.forEach(function(size){
                        let optionAdditionalPrice = 0;
                        if(size.pivot && size.pivot.price){
                            optionAdditionalPrice = parseFloat(size.pivot.price) || 0;
                        }
                        let totalBasePrice = pRefBasePrice + optionAdditionalPrice;
                        let discountedPrice = totalBasePrice;
                        if(pDiscountPercenteng > 0 && totalBasePrice > 0){
                            discountedPrice = totalBasePrice - (pDiscountPercenteng/100 * totalBasePrice);
                        }
                        let label = (['ar', 'fr'].includes(locale)) ? (size.Size_ar || size.Size) : (size.Size || size.Size_ar);
                        let dp = parseFloat(discountedPrice).toFixed(3);

                        let btnHtml = `<button class="btn btn-outline-primary size-option" 
                            data-size-id="${size.id}" 
                            data-price="${discountedPrice}" 
                            data-base-price="${totalBasePrice}">
                            ${label} - ${dp} ${currencyLabel}
                        </button>`;
                        sizeC.append(btnHtml);
                    });
                    $('#sizeOptionsSection').show();
                } else if (!unit) {
                   $('#submitSelection').show(); 
                }

                // 3. Handle Additions
                if(additions.length > 0){
                     additions.forEach(function(add){
                         let name = (locale === 'en') ? add.name : add.name_ar;
                         let html = `<label class="addition-option">
                            <input type="checkbox" data-addition-id="${add.id}" data-price="${add.price}">
                            <span class="checkmark"></span>
                            ${name} - ${add.price} + ${currencyLabel}
                         </label>`;
                         addC.append(html);
                     });
                     $('#additionOptionsSection').show();
                }

                $('#sizeModal').modal('show');
                $('#submitSelection').off('click').on('click', function(){
                    submitCustomCart();
                });
            });

            function submitCustomCart(){
                let qty = $('#product_quantity').val() || 1;
                let unitAmt = $('#unitAmount').length ? $('#unitAmount').val() : null;
                let unitType = $('#unitDisplayValue').text();

                if ($('#sizeOptionsSection').is(':visible') && !pSelectedSizeId) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "bottom-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    Toast.fire({ icon: "warning", title: localizedText.selectSize }); 
                    return;
                }

                let total = pSelectedSizePrice || pRefBasePrice; 
                pSelectedAdditions.forEach(a => total += a.price);

                let qMultiplier = 1;
                if(unitAmt && $('#unitDisplaySection').is(':visible')){
                    qMultiplier = parseFloat(unitAmt) || 1;
                } else {
                    qMultiplier = parseInt(qty) || 1;
                }
                total = total * qMultiplier;
                total = parseFloat(total.toFixed(3));

                window.performAddToCart(
                    pSelectedProductId,
                    qMultiplier, 
                    unitAmt,
                    unitType,
                    null,
                    pSelectedSizeId,
                    null,
                    pSelectedAdditions.map(a => a.id),
                    total
                ).done(function(data){
                    window.showCartSuccess(data);
                    $('#sizeModal').modal('hide');
                }).fail(function (xhr) {
                    let msg = "Something went wrong!";
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.error) {
                        msg = xhr.responseJSON.error;
                    }
                    Swal.fire({ icon: "error", title: msg });
                });
            }

        })(jQuery);
    </script>
@endpush

@push('scripts')
    <script>
        (function(){
            const qtyMain = document.getElementById('product_quantity_main');
            const qtyHidden = document.getElementById('product_quantity');
            const inc = document.querySelectorAll('.qty-increase');
            const dec = document.querySelectorAll('.qty-decrease');

            function setQty(val){
                val = parseInt(val) || 1;
                if (val < 1) val = 1;
                if (qtyMain) qtyMain.value = val;
                if (qtyHidden) qtyHidden.value = val;
            }

            // determine unit step (allow decimals for KG)
            var addBtn = document.querySelector('.add-cart.addCart');
            var unitAttr = addBtn ? (addBtn.dataset.unit || '') : '';
            var step = 1;
            if (unitAttr.toLowerCase().indexOf('kg') !== -1) {
                step = 0.001;
            }

            inc.forEach(function(btn){
                btn.addEventListener('click', function(){
                    var current = parseFloat(qtyMain.value) || 0; current = Math.round((current + step) * 1000) / 1000; setQty(current);
                });
            });
            dec.forEach(function(btn){
                btn.addEventListener('click', function(){
                    var current = parseFloat(qtyMain.value) || 0; current = Math.round((current - step) * 1000) / 1000; if (current < step) current = step; setQty(current);
                });
            });

            // sync manual edits
            if (qtyMain){
                qtyMain.addEventListener('change', function(){
                    setQty(this.value);
                });
                qtyMain.addEventListener('input', function(){
                    // allow numbers and dot for decimals when KG
                    var v = this.value;
                    // allow only digits and a single dot
                    v = v.replace(/[^0-9\.]/g, '');
                    v = v.replace(/(\..*)\./g, '$1');
                    // limit to 3 decimal places
                    if (v.indexOf('.') !== -1) {
                        var parts = v.split('.');
                        parts[1] = parts[1].substring(0,3);
                        v = parts[0] + '.' + parts[1];
                    }
                    this.value = v;
                });
            }

            // ensure hidden qty is synced before add-to-cart click
            document.addEventListener('click', function(e){
                const target = e.target.closest('.add-cart');
                if (target && qtyMain && qtyHidden){
                    qtyHidden.value = qtyMain.value || 1;
                }
            });
        })();
    </script>
        <script>
        document.addEventListener('DOMContentLoaded', function(){
            // Generic star picker handling for any picker with id starting with 'star-picker'
            const pickers = [...document.querySelectorAll('[id^="star-picker"]')];
            const ratingInput = document.getElementById('review_rating');

            function highlightPickerStars(picker, value){
                const stars = [...picker.querySelectorAll('.star, .star-bottom')];
                stars.forEach(function(s){
                    const v = parseInt(s.getAttribute('data-value')) || 0;
                    if (v <= value) {
                        s.classList.remove('bi-star');
                        s.classList.add('bi-star-fill');
                        s.classList.add('text-warning');
                    } else {
                        s.classList.remove('bi-star-fill');
                        s.classList.add('bi-star');
                        s.classList.remove('text-warning');
                    }
                });
            }

            function updateAllPickers(value){
                pickers.forEach(function(p){ highlightPickerStars(p, value); });
            }

            pickers.forEach(function(picker){
                picker.addEventListener('click', function(e){
                    const star = e.target.closest('.star, .star-bottom');
                    if(!star) return;
                    const value = parseInt(star.getAttribute('data-value')) || 5;
                    if (ratingInput) ratingInput.value = value;
                    updateAllPickers(value);
                });
            });

            // initialize pickers from hidden input (default 5)
            const initial = parseInt(ratingInput?.value) || 5;
            updateAllPickers(initial);
        });
        </script>
        <script>
            $(document).on('click', '#shareProduct', function() {
                const title = document.title;
                const text = '{{ $localizedTitle }}';
                const url = window.location.href;

                if (navigator.share) {
                    navigator.share({
                        title: title,
                        text: text,
                        url: url
                    }).catch((error) => console.log('Error sharing:', error));
                } else {
                    // Fallback: Copy to clipboard
                    const el = document.createElement('textarea');
                    el.value = url;
                    document.body.appendChild(el);
                    el.select();
                    document.execCommand('copy');
                    document.body.removeChild(el);

                    Swal.fire({
                        icon: 'success',
                        title: '{{ __("Link copied to clipboard!") }}',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        </script>
@endpush
