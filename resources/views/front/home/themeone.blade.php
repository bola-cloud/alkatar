<!-- hero-section area start here  -->
<div class="hero-section">
    <div class="hero-slider">
        @foreach ($sliders as $slider)
        @if (isset($slider->slider_link))
        <a href="{{$slider->slider_link}}">
            <div class="signle-slide" style="background-image: url('{{ asset(SliderImage() . $slider->Background_Image) }}');">
            </div>
        </a>
        @else
        <div class="signle-slide" style="background-image: url('{{ asset(SliderImage() . $slider->Background_Image) }}');">
        </div>
        @endif
        @endforeach
    </div>
</div>
<!-- hero-section area end here  -->

<!--  Categories area start here  -->
<div class="popular-categories-area section-top pb-100 bg-gray-50 mb-100">
    <div class="section-header-area relative">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-center ">
                <div class="hidden md:block w-1/4 overflow-hidden">
                    <img src="{{asset("assets/images/dash-line.png")}}" alt="dash line" class="w-full" />
                </div>
                <h2 class="section-title px-4 text-center w-1/2">
                    {{ langConverter(
        siteContentHomePage('categories')->en_Description_One,
        siteContentHomePage('categories')->fr_Description_One
    ) }}
                </h2>
                <div class="hidden md:block w-1/4 overflow-hidden">
                    <img src="{{asset("assets/images/dash-line.png")}}" alt="dash line" class="w-full" />
                </div>
            </div>
        </div>
    </div>

    <div class="category-box-slide">
        @foreach (Category_Des_Icon() as $item)
        <a href="{{ route('category.product', $item->id)}}" class="category_slider_item relative ">
            <img src="{{ asset(CategoryImage() . $item->Category_Icon)  }}" alt="{{ langConverter($item->en_Category_Name, $item->fr_Category_Name) }}">

            <p class="!text-7xl">
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
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-center">
                <div class="hidden md:block w-1/4 overflow-hidden">
                    <img src="{{asset("assets/images/dash-line.png")}}" alt="dash line" class="w-full" />
                </div>
                <h2 class="section-title px-4 text-center w-1/2">
                    {{ langConverter(
        siteContentHomePage('on_sale')->en_Description_One,
        siteContentHomePage('on_sale')->fr_Description_One
    ) }}
                </h2>
                <div class="hidden md:block w-1/4 overflow-hidden">
                    <img src="{{asset("assets/images/dash-line.png")}}" alt="dash line" class="w-full" />
                </div>
            </div>
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
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-center">
                <div class="hidden md:block w-1/4 overflow-hidden">
                    <img src="{{asset("assets/images/dash-line.png")}}" alt="dash line" class="w-full" />
                </div>
                <h2 class="section-title px-4 text-center w-1/2">
                    {{ langConverter(
        siteContentHomePage('best_selling')->en_Description_One,
        siteContentHomePage('best_selling')->fr_Description_One
    ) }}
                </h2>
                <div class="hidden md:block w-1/4 overflow-hidden">
                    <img src="{{asset("assets/images/dash-line.png")}}" alt="dash line" class="w-full" />
                </div>
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
    @foreach ($promotion as $promo)
    <a href="{{$promo->Link_One}}" class="single-banner"><img class="banner-image" src="{{ asset(PromotionImage() . $promo->Image_One) }}" alt="product-banner" /></a>
    @endforeach
</div>
<!-- product banner area end here  -->

<!-- Featured start here  -->
<div class="featured-productss-area pb-100">
    <div class="section-header-area">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-center">
                <div class="hidden md:block w-1/4 overflow-hidden">
                    <img src="{{asset("assets/images/dash-line.png")}}" alt="dash line" class="w-full" />
                </div>
                <h2 class="section-title px-4 text-center w-1/2">
                    {{ langConverter(
        siteContentHomePage('featured')->en_Description_One,
        siteContentHomePage('featured')->fr_Description_One
    ) }}
                </h2>
                <div class="hidden md:block w-1/4 overflow-hidden">
                    <img src="{{asset("assets/images/dash-line.png")}}" alt="dash line" class="w-full" />
                </div>
            </div>
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
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-center">
                <div class="hidden md:block w-1/4 overflow-hidden">
                    <img src="{{asset("assets/images/dash-line.png")}}" alt="dash line" class="w-full" />
                </div>
                <h2 class="section-title px-4 text-center w-1/2">
                    {{ langConverter(
        siteContentHomePage('new_arrival')->en_Description_One,
        siteContentHomePage('new_arrival')->fr_Description_One
    ) }}
                </h2>
                <div class="hidden md:block w-1/4 overflow-hidden">
                    <img src="{{asset("assets/images/dash-line.png")}}" alt="dash line" class="w-full" />
                </div>
            </div>
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