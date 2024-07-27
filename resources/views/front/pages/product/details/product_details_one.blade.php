@extends('front.layouts.master')
@section('title', isset($title) ? $title : 'Home')
@section('description', isset($description) ? $description : '')
@section('keywords', isset($keywords) ? $keywords : '')
@section('content')
<!-- product-single-area start here  -->
<div class="product-single-area section-top">
    <div class="container">
        <div class="product-single-details">
            <div class="row">
                <div class="col-lg-6">
                    <div class="product-single-left">
                        <div class="product-slier-big-image">
                            <div class="product-priview-slide slider slider-for">
                                <div class="single-slide">
                                    <img class="slide-image"
                                        src="{{ asset(ProductImage() . $products->Primary_Image) }}"
                                        alt="{{ __('product') }}" />
                                </div>
                                @if ($products->Image4)
                                    <div class="single-slide">
                                        <img class="slide-image" src="{{ asset(ProductImage() . $products->Image4) }}"
                                            alt="{{ __('product') }}" />
                                    </div>
                                @endif
                                @if ($products->Image3)
                                    <div class="single-slide">
                                        <img class="slide-image" src="{{ asset(ProductImage() . $products->Image3) }}"
                                            alt="{{ __('product') }}" />
                                    </div>
                                @endif
                                @if ($products->Image5)
                                    <div class="single-slide">
                                        <img class="slide-image" src="{{ asset(ProductImage() . $products->Image5) }}"
                                            alt="{{ __('product') }}" />
                                    </div>
                                @endif
                                @if ($products->Image2)
                                    <div class="single-slide">
                                        <img class="slide-image" src="{{ asset(ProductImage() . $products->Image2) }}"
                                            alt="{{ __('product') }}" />
                                    </div>
                                @endif

                            </div>
                        </div>

                        <div class="product-thumbnail-image">
                            <ul class="product-thumb-silide slider slider-nav">

                                <li class="single-item"><img class="single-item-image"
                                        src="{{ asset(ProductImage() . $products->Primary_Image) }}"
                                        alt="{{ __('product') }}" /></li>
                                @if ($products->Image4)
                                    <li class="single-item"><img class="single-item-image"
                                            src="{{ asset(ProductImage() . $products->Image4) }}"
                                            alt="{{ __('product') }}" />
                                    </li>
                                @endif
                                @if ($products->Image3)
                                    <li class="single-item"><img class="single-item-image"
                                            src="{{ asset(ProductImage() . $products->Image3) }}"
                                            alt="{{ __('product') }}" /></li>
                                @endif
                                @if ($products->Image5)
                                    <li class="single-item"><img class="single-item-image"
                                            src="{{ asset(ProductImage() . $products->Image5) }}"
                                            alt="{{ __('product') }}" /></li>
                                @endif
                                @if ($products->Image2)
                                    <li class="single-item"><img class="single-item-image"
                                            src="{{ asset(ProductImage() . $products->Image2) }}"
                                            alt="{{ __('product') }}" /></li>
                                @endif
                            </ul>
                        </div>

                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="product-single-right">
                        <div class="product-info">
                            <div class="product-name flex">
                                {{ langConverter($products->en_Product_Name, $products->fr_Product_Name) }}
                                @foreach ($products->product_tags as $ppt)
                                    <h4 class="product-catagory">{{ langConverter($ppt->tag, $ppt->tag_ar) }}</h4>
                                @endforeach
                            </div>
                            <!-- This is server side code. User can not modify it. -->
                            <div class="flex items-center gap-4 font-bold">
                                {{__("Reviews")}}
                                {!! productReview($products->id) !!}
                            </div>

                            <div class="product-price relative">
                                @if (
                                        currencyConverter($products->Price) ==
                                        currencyConverter($products->Discount_Price)
                                    )
                                                                    <span class="price">{{ currencyConverter($products->Discount_Price) }}</span>
                                @else
                                    <span class="price">{{ currencyConverter($products->Discount_Price) }}</span>
                                    <span class="regular-price">{{ currencyConverter($products->Price) }}</span>
                                    <span
                                        class="absolute start-[25rem] bg-red-100 text-red-400 rounded-full px-4 py-1 text-lg font-bold">
                                        {{__("Discount (-)")}}{{number_format($products->Discount, 0)}}%</span>
                                @endif
                            </div>

                            @if (isset($products->points) && $products->points > 0)
                                                        <p class="text-3xl text-primary-red font-bold">{{ __("Win Points", [
                                    'points' =>
                                        $products->points
                                ]) }}</p>
                            @endif


                            <p class="note-text">
                                {!! langConverter($products->en_About, $products->fr_About) !!}
                            </p>

                            <div class="product-color-area">
                                <div class="variable-single-item color-switch">
                                    <div class="product-variable-color">
                                        @foreach ($products->colors as $color)
                                            <label>
                                                <input type="hidden" name="colorId" value="{{ $color->id }}">
                                                <input name="productColor" class="color-select" type="radio"
                                                    value="{{ $color->id }}">
                                                <span style="background:{{ $color->ColorCode }};"></span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="product-size-area border-t pt-16">
                                <h4 class="size-title">{{ __('Size') }}:</h4>
                                <div class="size-switch ">
                                    @foreach ($products->sizes as $size)
                                        <div>
                                            <label for="size-{{ $size->id }}" class="size-option">
                                                <input type="radio" name="size" value="{{ $size->pivot->price }}"
                                                    class="size-radio" data-size="{{ $size->id }}" id="size-{{ $size->id }}"
                                                    {{ $loop->first ? 'checked' : '' }}>
                                                <span
                                                    class="size-label font-bold">{{ langConverter($size->Size, $size->Size_ar) }}</span>
                                                -
                                                <span class="size-price">{{ currencyConverter($size->pivot->price) }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if ($products->additions->count() > 0)
                                                <div class="product-addition-area border-t pt-16">
                                                    <h4 class="addition-title">{{ __('Additions') }}:</h4>
                                                    <div class="addition-switch">
                                                        @foreach ($products->additions as $addition)
                                                                                    <label for="addition-{{ $addition->id }}" class="addition-option">
                                                                                        <input type="checkbox" name="addition[]" value="{{ $addition->price }}"
                                                                                            class="addition-checkbox" data-addition="{{ $addition->id }}"
                                                                                            id="addition-{{ $addition->id }}">
                                                                                        <span class="addition-label font-bold">{{ langConverter(
                                                                $addition->name,
                                                                $addition->name_ar
                                                            ) }}</span> -
                                                                                        <span class="addition-price">{{ currencyConverter($addition->price) }}</span>
                                                                                    </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                            @endif



                        </div>
                        <div class="product-right-bottom">
                            <ul class="features">
                                <li class="single-feature"><img class="icon"
                                        src="{{ asset('frontend/assets/images/delivery-van-icon.svg') }}"
                                        alt="icon" /><strong class="feature-title">{{ __('Estimated Delivery:')
                                        }}</strong><span class="feature-text">{{ allsetting()['estimating_delivery']
                                        }}</span></li>
                                <li class="single-feature"><img class="icon"
                                        src="{{ asset('frontend/assets/images/shipping-return.svg') }}"
                                        alt="icon" /><strong class="feature-title">{{ __('Shipping Charge:')
                                        }}</strong><span class="feature-text">
                                        @if (@allsetting()['shipping_free_text'])
                                            {{ @allsetting()['shipping_free_text'] }}
                                        @else
                                            {{ currencyConverter(allsetting()['shipping_charge']) }}
                                        @endif
                                        {{-- {{ __('On all orders over') }}
                                        {{ currencyConverter(allsetting()['shipping_charge']) }} --}}
                                    </span>
                                </li>
                            </ul>

                            <div class="product-info">


                                <div
                                    class="prdouct-btn-wrapper d-flex lg:align-items-center gap-10 flex-col lg:flex-row ">
                                    <div class="cart-plus-minus w-full">
                                        <div class="dec qtybutton btn">-</div>
                                        <input class="cart-plus-minus-box" type="text" name="qtybutton"
                                            id="product_quantity" value="1" min="0" />
                                        <div class="inc qtybutton btn">{{ __('+') }}</div>
                                    </div>

                                    <div class="product-bottom-button d-flex w-full">
                                        <!-- <a href="javascript:void(0)" class="primary-btn buyNow"
                                            data-id="{{ $products->id }}">{{ __('Buy Now') }}</a> -->
                                        <a href="javascript:void(0)" title="{{ __('Add To Cart') }}"
                                            class="add-cart addCart" data-product-id="{{ $products->id }}"
                                            data-price="0" data-size-id="0">{{ __('Add To Cart')
                                            }}
                                            <i class="icon fas fa-plus-circle"></i></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-bottom-info mt-50">
            <div class="nav-tabs-menu">
                <ul class="nav nav-tabs" id="ProductTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="Description-tab" data-bs-toggle="tab"
                            data-bs-target="#Description" type="button" role="tab" aria-controls="Description"
                            aria-selected="true">
                            {{ __('Description') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="Reviews-tab" data-bs-toggle="tab" data-bs-target="#Reviews"
                            type="button" role="tab" aria-controls="Reviews" aria-selected="false">
                            {{ __('Reviews') }}</button>
                    </li>
                    <!-- <li class="nav-item" role="presentation">
                        <button class="nav-link" id="Shipping-Return-tab" data-bs-toggle="tab"
                            data-bs-target="#Shipping-Return" type="button" role="tab" aria-controls="Shipping-Return"
                            aria-selected="false">
                            {{ __('Shipping & Return') }}</button>
                    </li> -->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="Additional-Information-tab" data-bs-toggle="tab"
                            data-bs-target="#Additional-Information" type="button" role="tab"
                            aria-controls="Additional-Information" aria-selected="false">
                            {{ __('Additional Information') }}</button>
                    </li>
                </ul>
            </div>

            <div class="tab-content" id="ProductTabContent">

                <div class="tab-pane fade show active" id="Description" role="tabpanel"
                    aria-labelledby="Description-tab">
                    <div class="product-description">
                        {!! langConverter($products->en_Description, $products->fr_Description) !!}
                    </div>
                </div>

                <div class="tab-pane fade" id="Reviews" role="tabpanel" aria-labelledby="Reviews-tab">
                    <div class="product-reviews">
                        <div class="review-top">
                            <div class="review-top-left">
                                <span class="review-point">{{ productReviewNumber($products->id) }}</span>
                                <!-- This is server side code. User can not modify it. -->
                                {!! productReview($products->id) !!}
                                <span class="review-count">{{ productReviewerNumber($products->id) }}
                                    {{ __('Reviews') }}</span>
                            </div>
                        </div>

                        <div class="reviews-list">
                            @forelse($products->product_reviews as $review)
                                                    <div class="single-review">
                                                        <div class="reviewer">
                                                            <div class="reviewer-wrap">
                                                                <img class="reviewer-image"
                                                                    src="{{ isset($review->user->image) ? asset(AdminProfilePicture() . $review->user->image) : Avatar::create($review->user->name)->toBase64() }}"
                                                                    alt="reviewer-image" />
                                                                <h4 class="reviewer-name">{{ $review->user->name }}</h4>
                                                            </div>
                                                        </div>
                                                        <div class="review-middle">
                                                            <!-- This is server side code. User can not modify it. -->
                                                            {!! reviewRating($review->id) !!}
                                                            <span class="remiew-time">{{
                                \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</span>
                                                        </div>
                                                        <h4 class="review-meta"><span class="time">{{
                                \Carbon\Carbon::parse($review->created_at)->format('jS M Y') }}
                                                            </span> by <a class="author" href="javascript:void(0)">{{ $review->user->name }}</a>
                                                        </h4>
                                                        <p class="review-text">{{ $review->feedback }}</p>
                                                    </div>
                            @empty
                                <h1>{{ __('No Review Yet!') }}</h1>
                            @endforelse

                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="Shipping-Return" role="tabpanel" aria-labelledby="Shipping-Return-tab">
                    <div class="shipping-return-area">
                        {!! langConverter($products->en_ShippingReturn, $products->fr_ShippingReturn) !!}
                    </div>
                </div>
                <div class="tab-pane fade" id="Additional-Information" role="tabpanel"
                    aria-labelledby="Additional-Information-tab">
                    {!! langConverter($products->en_AdditionalInformation, $products->fr_AdditionalInformation) !!}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- product-single-area end here  -->

<!-- Featured Products area start here  -->
<div class="featured-productss-area section-top pb-100">
    <div class="container">
        <div class="section-header-area">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="sub-title">{{ __('Similar Products') }}</h3>
                    <h2 class="section-title">{{ __('Related Products') }}</h2>
                </div>
                <div class="col-md-6 align-self-end text-md-end">
                    <a href="{{ route('all.product') }}" class="see-btn">{{ __('See All') }}</a>
                </div>
            </div>
        </div>

        <div class="offers-box-slide">
            @forelse($similar_product as $relatedProduct)
                <x-frontend.product-card :product="$relatedProduct" :isInDetailsPage="true" />

            @empty
                <h1>{{ __('No related product found!') }}</h1>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('post_script')
    <script>
        // $(document).ready(function() {
        //     // Get the original price from the page
        //     var originalPrice = parseFloat($('.single-size input[type="radio"]:checked').val());
        //     var additionPrice = 0;

        //     // Set the initial price based on the first selected size
        //     $('.product-price .price').text('OMR ' + originalPrice.toFixed(2));
        //     $('.addCart').attr('data-price', originalPrice);
        //     $('.addCart').attr('data-size-id', $('.single-size input[type="radio"]:checked').data('size'));

        //     // Update price and data attributes when a size is selected
        //     $('.single-size').on('click', function() {
        //         var sizeRadio = $(this).find('.size-radio');
        //         sizeRadio.prop('checked', true);
        //         originalPrice = parseFloat(sizeRadio.val());
        //         $('.product-price .price').text('OMR ' + (originalPrice + additionPrice).toFixed(2));
        //         $('.addCart').attr('data-price', originalPrice + additionPrice);
        //         $('.addCart').attr('data-size-id', sizeRadio.data('size'));
        //     });

        //     // Update price and data attributes when an addition is selected
        //     $('.single-addition').on('click', function() {
        //         var additionRadio = $(this).find('.addition-radio');
        //         additionRadio.prop('checked', true);
        //         additionPrice = parseFloat(additionRadio.val());
        //         $('.product-price .price').text('OMR ' + (originalPrice + additionPrice).toFixed(2));
        //         $('.addCart').attr('data-price', originalPrice + additionPrice);
        //         $('.addCart').attr('data-addition-id', additionRadio.data('addition'));
        //     });
        // });
        $(document).ready(function () {
            // Get the initial price from the first selected size
            var sizePrice = parseFloat($('.size-switch input[type="radio"]:checked').val());
            var selectedAdditions = [];

            // Set the initial total price
            updateTotalPrice();

            // Update price and data attributes when a size is selected
            $('.size-switch input[type="radio"]').on('change', function () {
                sizePrice = parseFloat($(this).val());
                updateTotalPrice();
                $('.addCart').attr('data-size-id', $(this).data('size'));
            });

            // Handle addition selection
            $('.addition-switch input[type="checkbox"]').on('change', function () {
                var additionId = $(this).data('addition');
                var additionPrice = parseFloat($(this).val());

                if ($(this).is(':checked')) {
                    selectedAdditions.push({ id: additionId, price: additionPrice });
                } else {
                    selectedAdditions = selectedAdditions.filter(addition => addition.id !== additionId);
                }
                updateTotalPrice();
            });

            // Function to update the total price display
            function updateTotalPrice() {
                var totalPrice = sizePrice + selectedAdditions.reduce((sum, addition) => sum + addition.price, 0);
                $('.product-single-right .product-price .price').text('OMR ' + totalPrice.toFixed(2));
                $('.addCart').attr('data-price', totalPrice);
            }
        });
    </script>
@endpush