<!-- hero-section area start here  -->
<div class="hero-section">
    <div class="hero-slider">
        @foreach ($sliders as $slider)
        @if (isset($slider->slider_link))
        <a href="{{$slider->slider_link}}">
            <div class="signle-slide"
                style="background-image: url('{{ asset(SliderImage() . $slider->Background_Image) }}');">
            </div>
        </a>
        @else
        <div class="signle-slide"
            style="background-image: url('{{ asset(SliderImage() . $slider->Background_Image) }}');">
        </div>
        @endif
        @endforeach
    </div>
</div>
<!-- hero-section area end here  -->

<!--  Categories area start here  -->
<div class="popular-categories-area section-top pb-100">
    <div class="section-header-area relative">
        <div class="flex items-center justify-center gap-5 max-w-full px-10">
            {{-- dotted line --}}
            {{-- <img src="{{asset('/title_icons/categoryTitle.png')}}" class="absolute -top-20 -right-20" /> --}}
            <img src="{{asset("assets/images/dash-line.png")}}" alt="dash line" class="max-w-full" />
            <h2 class="section-title">
                {{ langConverter(siteContentHomePage('categories')->en_Description_One,
                siteContentHomePage('categories')->fr_Description_One) }}
            </h2>
            <img src="{{asset("assets/images/dash-line.png")}}" alt="dash line" />

        </div>
    </div>

    <div class="category-box-slide">
        @foreach (Category_Des_Icon() as $item)
        <a href="{{ route('category.product', $item->id)}}" class="category_slider_item relative">
            <img src="{{ asset(CategoryImage() . $item->Category_Icon)  }}"
                alt="{{ langConverter($item->en_Category_Name, $item->fr_Category_Name) }}">

            <p>
                {{ langConverter($item->en_Category_Name, $item->fr_Category_Name) }}
            </p>
        </a>
        @endforeach
    </div>
</div>
<!--  Categories area end here  -->

<!-- On Sale area start here  -->
<div class="featured-productss-area pb-100">
    <div class="section-header-area relative">
        <div class="flex items-center justify-center gap-5 max-w-full px-10 ">
            {{-- dotted line --}}
            {{-- <img src="{{asset('/title_icons/offersTitle.png')}}" class="absolute -top-4 left-10" /> --}}
            <img src="{{asset("assets/images/dash-line.png")}}" alt="dash line" class="max-w-full" />
            <h2 class="section-title">
                {{ langConverter(siteContentHomePage('on_sale')->en_Description_One,
                siteContentHomePage('on_sale')->fr_Description_One) }}
            </h2>
            <img src="{{asset("assets/images/dash-line.png")}}" alt="dash line" />
        </div>
    </div>

    <div class="offers-box-slide">
        @forelse ($on_sales as $product)
        <x-frontend.product-card :product="$product" />
        @empty
        <div class="text-center text-5xl font-bold mt-20">
            <h3>{{ __('No Products Found') }}</h3>
        </div>
        @endforelse

    </div>
</div>
<!-- On Sale Products area end here  -->

<!-- Best seller start here  -->
<div class="featured-productss-area pb-100">
    <div class="section-header-area">
        <div class="flex items-center gap-5 justify-center max-w-full px-10">
            {{-- dotted line --}}
            <img src="{{asset("assets/images/dash-line.png")}}" alt="dash line"  />

            <h2 class="section-title">
                {{ langConverter(siteContentHomePage('best_selling')->en_Description_One,
                siteContentHomePage('best_selling')->fr_Description_One) }}
            </h2>

            <div class="text-gray-300 hidden lg:flex items-center">
                <img src="{{asset("assets/images/dash-line.png")}}" alt="dash line" />
                {{-- <img src="{{asset('/title_icons/bestSellerTitle.png')}}" /> --}}
            </div>
        </div>
    </div>

    <div class="offers-box-slide">
        @forelse($best_selling as $product)
        <x-frontend.product-card :product="$product" />
        @empty
        <div class="text-center text-5xl font-bold mt-20">
            <h3>{{ __('No Products Found') }}</h3>
        </div>
        @endforelse

    </div>
</div>
<!-- Best seller  end here  -->

<!-- product banner area start here  -->
<div class="product-banner hidden lg:block pb-100">
    <div class="row">
        @foreach ($promotion as $promo)
        <div class="col-12">
            <a href="{{$promo->Link_One}}" class="single-banner"><img class="banner-image"
                    src="{{ asset(PromotionImage() . $promo->Image_One) }}" alt="product-banner" /></a>
        </div>
        {{-- <div class="col-md-7">
            <a href="#" class="single-banner"><img class="banner-image"
                    src="{{ asset(PromotionImage() . $promo->Image_Two) }}" alt="product-banner" /></a>
        </div> --}}
    </div>
    @endforeach
</div>
<!-- product banner area end here  -->

<!-- Featured start here  -->
<div class="featured-productss-area pb-100">
    <div class="section-header-area">
        <div class="flex items-center justify-center gap-5 max-w-full px-10">
            {{-- dotted line --}}
            {{-- <div class="text-gray-300 hidden lg:flex items-center"> --}}
                {{-- <img src="{{asset('/title_icons/featuredTitle.png')}}" /> --}}
                {{-- <div class="left-dashes"></div> --}}
                <img src="{{asset("assets/images/dash-line.png")}}" alt="dash line"  />
            {{-- </div> --}}

            <h2 class="section-title">
                {{ langConverter(siteContentHomePage('featured')->en_Description_One,
                siteContentHomePage('featured')->fr_Description_One) }}
            </h2>

            <img src="{{asset("assets/images/dash-line.png")}}" alt="dash line"  />
        </div>
    </div>

    <div class="offers-box-slide">
        @forelse ($featured_products as $product)
        <x-frontend.product-card :product="$product" />

        @empty
        <div class="text-center text-5xl font-bold mt-20">
            <h3>{{ __('No Products Found') }}</h3>
        </div>
        @endforelse

    </div>
</div>
<!-- Featured end here  -->

<!-- New Arrival start here  -->
<div class="featured-productss-area pb-100">
    <div class="section-header-area">
        <div class="flex items-center justify-center gap-5 max-w-full px-20">
            {{-- <div class="text-gray-300 left-dashes hidden lg:inline-block"></div> --}}
            <img src="{{asset("assets/images/dash-line.png")}}" alt="dash line"  />

            <h2 class="section-title">
                {{ langConverter(siteContentHomePage('new_arrival')->en_Description_One,
                siteContentHomePage('new_arrival')->fr_Description_One) }}
            </h2>

            <img src="{{asset("assets/images/dash-line.png")}}" alt="dash line"  />

            {{-- <div class="text-gray-300 hidden lg:flex items-center">
                <div class="right-dashes"></div>
                <img src="{{asset('/title_icons/offersTitle.png')}}" />
            </div> --}}
        </div>
    </div>

    <div class="offers-box-slide">
        @forelse ($new_arrivals as $product)
        <x-frontend.product-card :product="$product" />

        @empty
        <div class="text-center text-5xl font-bold mt-20">
            <h3>{{ __('No Products Found') }}</h3>
        </div>
        @endforelse

    </div>
</div>
<!-- New Arrival end here  -->


  {{-- <div class="modal fade" id="sizeSelectionModal" tabindex="-1" aria-labelledby="sizeSelectionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sizeSelectionModalLabel">{{ __('Select Size') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="sizeOptions">
                        <!-- Size options will be dynamically inserted here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary" id="addToCartWithSize">{{ __('Add To Cart')
                        }}</button>
                </div>
            </div>
        </div>
    </div> --}}

<!-- Testimonial ara start here  -->
{{-- <div class="testimonial-area section section-bg">
    <div class="container">
        <div class="section-header-area text-center">
            <h3 class="sub-title">{{ __('Testimonial') }}</h3>
            <h2 class="section-title">{{ __('What People Are') }} <br />{{ __('Saying About Ourself') }}</h2>
        </div>
        <div class="testimonial-slide-top">

            <!-- Testimonial authors Float Images Start -->
            @foreach ($testimonial as $test)
            @if ($loop->iteration == 1)
            <img src="{{ asset(IMG_TESTIMONIAL . $test->Image) }}" alt="img"
                class="testimonial-float-img testimonial-left-1 position-absolute">
            @elseif ($loop->iteration == 2)
            <img src="{{ asset(IMG_TESTIMONIAL . $test->Image) }}" alt="img"
                class="testimonial-float-img testimonial-left-2 position-absolute">
            @elseif ($loop->iteration == 3)
            <img src="{{ asset(IMG_TESTIMONIAL . $test->Image) }}" alt="img"
                class="testimonial-float-img testimonial-left-3 position-absolute">
            @elseif ($loop->iteration == 4)
            <img src="{{ asset(IMG_TESTIMONIAL . $test->Image) }}" alt="img"
                class="testimonial-float-img testimonial-left-4 position-absolute">
            @elseif ($loop->iteration == 5)
            <img src="{{ asset(IMG_TESTIMONIAL . $test->Image) }}" alt="img"
                class="testimonial-float-img testimonial-right-1 position-absolute">
            @elseif ($loop->iteration == 6)
            <img src="{{ asset(IMG_TESTIMONIAL . $test->Image) }}" alt="img"
                class="testimonial-float-img testimonial-right-2 position-absolute">
            @elseif ($loop->iteration == 7)
            <img src="{{ asset(IMG_TESTIMONIAL . $test->Image) }}" alt="img"
                class="testimonial-float-img testimonial-right-3 position-absolute">
            @elseif ($loop->iteration == 8)
            <img src="{{ asset(IMG_TESTIMONIAL . $test->Image) }}" alt="img"
                class="testimonial-float-img testimonial-right-4 position-absolute">
            @endif
            @endforeach
            <!-- Testimonial authors Float Images End -->

            <img class="shape-image" src="{{ asset('frontend/assets/images/shape.png') }}" alt="shape" />
            <div class="testimonial-image-slide">
                @foreach ($testimonial as $test)
                <div class="signle-testimonial-image"><img class="testimonial-image"
                        src="{{ asset(IMG_TESTIMONIAL . $test->Image) }}" alt="testimonal-image" /></div>
                @endforeach
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="testimonail-slide">
                    @foreach ($testimonial as $test)
                    <div class="single-testimonial">
                        <p class="testimonial-text">
                            {{ langConverter($test->en_Description, $test->fr_Description) }}</p>
                        <h3 class="testimonial-title">{{ $test->Name }}</h3>
                        <ul class="review-area">
                            <li><i class="flaticon-star"></i></li>
                            <li class="{{ $test->star == 1 ? 'inactive' : '' }}"><i class="flaticon-star"></i>
                            </li>
                            <li class="{{ $test->star == 1 || $test->star == 2 ? 'inactive' : '' }}"><i
                                    class="flaticon-star"></i></li>
                            <li
                                class="{{ $test->star == 1 || $test->star == 2 || $test->star == 3 ? 'inactive' : '' }}">
                                <i class="flaticon-star"></i>
                            </li>
                            <li
                                class="{{ $test->star == 1 || $test->star == 2 || $test->star == 3 || $test->star == 4 ? 'inactive' : '' }}">
                                <i class="flaticon-star"></i>
                            </li>
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div> --}}
<!-- Testimonial ara end here  -->