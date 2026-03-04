<!DOCTYPE html>
<html lang="en">
@include('front.layouts.include.head')
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-CDGYGLXRKV"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-CDGYGLXRKV');
</script>

@stack('post_css')

<style>
    .whats-float {
        position: fixed;
        width: 60px;
        height: 60px;
        bottom: 80px;
        right: 20px;
        background-color: #25d366;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        font-size: 30px;
        box-shadow: 2px 2px 3px #999;
        z-index: 100;
    }

    .whats-my-float {
        margin-top: 16px;
    }
</style>

<body
    class="{{ session()->has('lang_dir') && session()->get('lang_dir') == 'rtl' ? 'direction-rtl' : 'direction-ltr' }} max-w-[1600px] mx-auto px-3 md:px-10">
    <!-- Preloader Area Start -->
    <!-- <div id="preloader">
        <div id="status">
            <img src="{{ asset(IMG_PRELOADER_PATH . $allsettings['preloader']) }}" alt="img" />
        </div>
    </div> -->
    <!-- Preloader Area End -->

    @include('front.layouts.include.desktop_header')

    @include('front.layouts.include.mobile_header')

    @include('front.layouts.include.mobile_menu')

    @include('front.layouts.include.cart_sidebar_menu')

    @yield('content')
    <div id="AddToCompareItemUrl" data-url="{{ route('compare.add') }}"></div>
    <div id="AddToCartIntoSession" data-url="{{ route('add.to.cart') }}"></div>
    <div id="productWishlistUrl" data-url="{{ route('wishlist.add') }}"></div>
    <div id="currency-price-url" data-url="{{ route('currency_price') }}"></div>
    <div id="currency-symbol-url" data-url="{{ route('currency_symbol') }}"></div>
    <div id="productImgAsset" data-url="{{ asset(ProductImage()) }}"></div>

    @include('front.layouts.include.footer')

    @yield('subscribe_pop_up_modal')

    <div class="modal fade common-modal" id="trackOrderModal" tabindex="-1" aria-labelledby="trackOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="exampleModalLabel">{{ __('Track Order') }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('checkout.order_track') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">{{ __('Order Number') }}</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" name="order_number"
                                placeholder="{{ __('Enter order number') }}">
                        </div>
                        <div class="modal-btn-wrap text-end">
                            <button type="submit" class="primary-btn">{{ __('Track') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade common-modal" id="trackOrderModal" tabindex="-1" aria-labelledby="trackOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="exampleModalLabel">{{ __('Track Order') }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('checkout.order_track') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleFormControlInput2" class="form-label">{{ __('Order Number') }}</label>
                            <input type="text" class="form-control" id="exampleFormControlInput2" name="order_number"
                                placeholder="{{ __('Enter order number') }}">
                        </div>
                        <div class="modal-btn-wrap text-end">
                            <button type="submit" class="primary-btn">{{ __('Track') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade common-modal" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="">{{ __('Login') }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.sign.modal') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="{{ __('Email') }}">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="{{ __('Password') }}">
                        </div>

                        <div class="modal-btn-wrap text-end">
                            <button type="submit" class="primary-btn">{{ __('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Size Selection Modal -->
    <div class="modal fade" id="sizeModal" tabindex="-1" aria-labelledby="sizeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header flex items-center justify-between">
                    <h5 class="modal-title" id="sizeModalLabel">Select Size and Additions</h5>
                    <div>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="mb-3" id="sizeOptionsSection">
                        <h6>{{ __("Product Options") }}:</h6>
                        <div id="sizeOptionsContainer" class="d-flex flex-wrap gap-2">
                            <!-- Size options will be injected here -->
                        </div>
                    </div>

                    <div class="mb-3" id="weightOptionsSection">
                        <h6>{{ __("Weight Options") }}:</h6>
                        <div id="weightOptionsContainer" class="d-flex flex-wrap gap-2">
                            <!-- Weight options will be injected here -->
                        </div>
                    </div>

                    <div class="mb-3" id="additionOptionsSection">
                        <h6>{{ __("Addition Options") }}:</h6>
                        <div id="additionOptionsContainer" class="d-flex flex-wrap gap-2">
                            <!-- Addition options will be injected here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="submitSelection">
                        {{ __('Add To Cart') }}
                    </button>
                </div>
            </div>
        </div>


    </div>

    <a href="https://api.whatsapp.com/send?phone={{ allsetting()['call_us'] ?? '96894974726' }}" class="whats-float"
        target="_blank">
        <i class="fa fa-whatsapp whats-my-float"></i>
    </a>




    {{-- route url --}}
    <div id="DoNotSubscribe" data-url="{{ route('do.not.subscribe') }}"></div>
    <div id="SubscribeStore" data-url="{{ route('admin.subscribe.store') }}"></div>
    {{-- include file --}}
    <script>
        var localizedText = {
            productAddedToCart: @json(__('Product Added to Cart Successfully')),
            selectSize: @json(__('Select Size for Product')),
            grams: @json(__('Grams')),
        };
    </script>
    @include('front.layouts.include.script')
    @stack('post_script')

    <script>
        var locale = '{{ config('
        app.locale ') }}';
    </script>

    {{-- @include('sweetalert::alert') --}}

</body>

</html>