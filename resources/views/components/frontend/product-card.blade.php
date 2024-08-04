@props(['product', 'isInDetailsPage' => false])


<div class="single-grid-product ms-1 md:ms-4" data-product-id="{{ $product->id }}">
    <div class="product-top">
        <a href="{{ route('single.product', $product->en_Product_Slug) }}">
            <img class="product-thumbnal" src="{{ asset(ProductImage() . $product->Primary_Image) }}"
                alt="{{ __('product') }}" />
        </a>
        <div class="product-flags">
            @foreach ($product->product_tags as $ppt)
                <span class="product-flag sale">{{ $ppt->tag }}</span>
            @endforeach
            @if ($product->Discount > 0)
                <span class="product-flag discount">{{ __('-') }}{{ $product->Discount }} %</span>
            @endif
        </div>

        <ul class="prdouct-btn-wrapper">
            {{-- <li class="single-product-btn">
                <a class="product-btn CompareList" data-id="{{ $product->id }}" title="{{ __('Add To Compare') }}"><i
                        class="icon flaticon-bar-chart"></i></a>
            </li> --}}
            <li class="single-product-btn">
                <a class="product-btn MyWishList" data-id="{{ $product->id }}" title="{{ __('Add To Wishlist') }}"><i
                        class="icon flaticon-like"></i></a>
            </li>
        </ul>
    </div>

    <div class="product-info text-center">
        {{-- @foreach ($product->product_tags as $ppt)
        <h4 class="product-catagory">{{ $ppt->tag }}</h4>
        @endforeach --}}
        <input type="hidden" name="quantity" value="1" id="product_quantity">
        <h3 class="product-name"><a class="product-link"
                href="{{ route('single.product', $product->en_Product_Slug) }}">{{
    langConverter($product->en_Product_Name, $product->fr_Product_Name) }}</a></h3>

        @if($product->points > 0)
            <p class="product_points">{{ __("Win Points", ['points' => $product->points]) }}</p>
        @endif

        <div class="product-price">
            @php
                $finalPrice = 0;
                $firstWeight = $product->weights->first();



                if ($firstWeight) {
                    $finalPrice = $firstWeight->price;
                } else {
                    $firstSize = $product->sizes->first();
                    $finalPrice = $firstSize?->pivot->price;
                }
            @endphp

            <span class="price">{{ currencyConverter($finalPrice) }}</span>

        </div>

        {!! productReview($product->id) !!}

        @if(!$isInDetailsPage)
            <a href="javascript:void(0)" title="{{ __('Add To Cart') }}" class="add-cart addCart"
                data-id="{{ $product->id }}" data-discount="{{$product->Discount_Price}}"
                data-name="{{ $product->en_Product_Name }}" data-sizes="{{ json_encode($product->sizes) }}"
                data-additions="{{json_encode($product->additions)}}" data-weights="{{ json_encode($product->weights) }}">
                <svg fill="#000000" class="size-8 hover:fill-white transition-colors" version="1.1" id="Capa_1"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 483.1 483.1"
                    xml:space="preserve">
                    <g>
                        <path
                            d="M434.55,418.7l-27.8-313.3c-0.5-6.2-5.7-10.9-12-10.9h-58.6c-0.1-52.1-42.5-94.5-94.6-94.5s-94.5,42.4-94.6,94.5h-58.6c-6.2,0-11.4,4.7-12,10.9l-27.8,313.3c0,0.4,0,0.7,0,1.1c0,34.9,32.1,63.3,71.5,63.3h243c39.4,0,71.5-28.4,71.5-63.3C434.55,419.4,434.55,419.1,434.55,418.7z M241.55,24c38.9,0,70.5,31.6,70.6,70.5h-141.2C171.05,55.6,202.65,24,241.55,24z M363.05,459h-243c-26,0-47.2-17.3-47.5-38.8l26.8-301.7h47.6v42.1c0,6.6,5.4,12,12,12s12-5.4,12-12v-42.1h141.2v42.1c0,6.6,5.4,12,12,12s12-5.4,12-12v-42.1h47.6l26.8,301.8C410.25,441.7,389.05,459,363.05,459z">
                        </path>
                    </g>
                </svg>
                <span class="mt-1 text-lg md:text-2xl">{{ __('Add To Cart') }}</span>
            </a>
        @endif
    </div>
</div>