<div>
    <div class="mobile-header-area d-block d-lg-none">
        <div class="container">
            <div class="menu-wrap">
                <div class="header-left">
                    <a class="brand-logo" href="{{ route('front') }}"><img class="brand-image"
                            src="{{ asset(IMG_LOGO_PATH . $allsettings['main_logo']) }}"
                            alt="{{ __('zairito') }}" /></a>
                </div>

                <div class="search-area">
                    <form action="{{ route('category.product') }}" method="get">
                        <div class="search-wrap">
                            <input type="text" class="form-control" id="search" name="search"
                                placeholder="{{ __('Search Here') }}" style="border-radius: 0 !important;" />
                            <button type="submit" class="search-btn"><i class="flaticon-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="header-right">
                    {{-- <a href="{{ route('wishlist') }}" class="wishlist-btn header-btn">
                        <div class="btn-left">
                            <i class="btn-icon flaticon-like"></i>
                            <span class="count wishListCuntFromController">{{ session()->has('wishlist') ?
                                count(session()->get('wishlist')) : '0' }}</span>
                        </div>
                    </a>
                    <a href="{{ route('compare') }}" class="compare-btn header-btn">
                        <div class="btn-left">
                            <i class="btn-icon flaticon-bar-chart"></i>
                            <span class="count CompareCuntFromController">{{ session()->has('compare') ?
                                count(session()->get('compare')) : '0' }}</span>
                        </div>
                    </a> --}}
                    <a role="button" class="cart-btn header-btn" href="{{ route('cart.content') }}">
                        <div class="btn-left">
                            <i class="btn-icon flaticon-shopping-bag"></i>
                            <span class="count totalCountItem">{{ cartCountItem() }}</span>
                        </div>
                    </a>


                    <button class="menu-bar" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasMobileMenu" aria-controls="offcanvasMobileMenu"><i
                            class="fas fa-bars"></i></button>


                </div>
            </div>

        </div>
    </div>
</div>