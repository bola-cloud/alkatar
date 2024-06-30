<style>
    .header-area {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 999;
        transition: all 0.3s ease;
    }

    .header-area.sticky {
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .search-area {
        visibility: hidden;
        height: 0;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 20px;
        box-sizing: border-box;
        opacity: 0;
        transform: translateY(-20px);
        transition: all 0.3s ease, transform 0.3s ease;
    }

    .search-area.show {
        visibility: visible;
        height: auto;
        opacity: 1;
        transform: translateY(0);
    }

    .search-icon {
        cursor: pointer;
        font-size: 24px;
        margin: 0 10px;
        margin-top: 10px;
        color: #942326;
    }
</style>

<div>
    <header class="header-area d-none d-lg-block">

        <div class="header-middle">
            <div class="header-middle-wrap">
                <div class="brand-area">
                    <a class="brand-logo" href="{{ route('front') }}">
                        <img class="brand-image" src="{{ asset(IMG_LOGO_PATH . $allsettings['main_logo']) }}"
                            alt="{{ $allsettings['app_title'] }}" />
                    </a>
                </div>


                <div class="search-area">
                    <form action="{{ route('category.product') }}" method="get">
                        <div class="search-wrap">
                            <div class="form-group">
                                <input type="text" class="form-control" id="search" name="search"
                                    placeholder="{{ __('Search Here') }}" />
                                <button type="submit" class="search-btn"><i class="flaticon-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="header-bottom">
                    <nav class="menu-area">
                        <ul class="main-menu">
                            @foreach (Category_Des_Icon()->take(8) as $category)
                            <li class="menu-item">
                                <a class="menu-link" href="{{ route('category.product', $category->id) }}">
                                    {{ langConverter($category->en_Category_Name, $category->fr_Category_Name) }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        </ul>
                    </nav>
                </div>



                <div class="flex items-center gap-5">
                    <div class="header-right">

                        <i class="search-icon flaticon-search" style="margin-inline: 40px;"></i>

                        <div class="wishlist single-btn">
                            <a href="{{ route('wishlist') }}" class="wishlist-btn header-btn">
                                <div class="btn-left">
                                    <i class="btn-icon flaticon-like"></i>
                                    <span class="count wishListCuntFromController">{{ auth()->check() ? wishlistCount()
                                        : '0' }}</span>
                                </div>
                            </a>
                        </div>

                        <div class="cart single-btn">
                            <a role="button" class="cart-btn header-btn" href="{{ route('cart.content') }}">
                                <div class="btn-left">
                                    <i class="btn-icon flaticon-shopping-bag"></i>
                                    <span class="count totalCountItem">{{ cartCountItem() }}</span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="header-top-right flex items-center gap-5">
                        <div class="switcher-lang-currency flex items-center gap-5">
                            <div class="lang-switcher">
                                @if (app()->getLocale() == 'en')
                                <span class="flag">
                                    <img src="{{ asset(IMG_LANGUAGE . getLanguage('en')->thumb) }}"
                                        alt="united-states" />
                                </span>
                                <a href="javascript:void(0)" class="lang">
                                    @if (getLanguage('en')->status == 1 && getLanguage('fr')->status == 1)
                                    <i class="fas fa-angle-down"></i>
                                    @endif
                                </a>
                                @elseif(app()->getLocale() == 'fr')
                                <span class="flag">
                                    <img src="{{ asset(IMG_LANGUAGE . getLanguage('fr')->thumb) }}" alt="india" />
                                </span>
                                <a href="javascript:void(0)" class="lang">
                                    @if (getLanguage('en')->status == 1 && getLanguage('fr')->status == 1)
                                    <i class="fas fa-angle-down"></i>
                                    @endif
                                </a>
                                @endif
                                <ul
                                    class="{{ getLanguage('en')->status != 1 || getLanguage('fr')->status != 1 ? null : 'lang-list' }}">
                                    @if (app()->getLocale() == 'en')
                                    @if (getLanguage('fr')->status == 1)
                                    <li class="single-lang">
                                        <a class="lang-text" href="{{ route('locale.switch', 'fr') }}">
                                            <img src="{{ asset(IMG_LANGUAGE . getLanguage('fr')->thumb) }}" alt="india">
                                        </a>
                                    </li>
                                    @endif
                                    @elseif(app()->getLocale() == 'fr')
                                    @if (getLanguage('en')->status == 1)
                                    <li class="single-lang">
                                        <a class="lang-text" href="{{ route('locale.switch', 'en') }}">
                                            <img src="{{ asset(IMG_LANGUAGE . getLanguage('en')->thumb) }}"
                                                alt="united-states" />
                                        </a>
                                    </li>
                                    @endif
                                    @endif
                                </ul>
                            </div>
                        </div>

                        @if (Auth::user())
                        <div class="account-switcher text-center">
                            <span class="flag">
                                <img src="{{ file_exists(AdminProfilePicture() . Auth::user()->image) ? (isset(Auth::user()->image) ? asset(AdminProfilePicture() . Auth::user()->image) : Avatar::create(Auth::user()->name)->toBase64()) : Auth::user()->image }}"
                                    alt="{{ $allsettings['app_title'] }}">
                            </span>

                            <a href="javascript:void(0)" class="lang">{{ Auth::user()->name }} <i
                                    class="fas fa-angle-down"></i></a>
                            <ul class="account-list">
                                <li class="single-lang">
                                    <a class="lang-text" href="{{ route('user.profile.edit') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-8 h-8 mb-2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                        {{ __('Profile') }}
                                    </a>
                                </li>
                                <li class="single-lang">
                                    <a class="lang-text" href="{{ route('user.logout') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-8 h-8 mb-2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                                        </svg>
                                        {{ __('Logout') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        @else
                        <a href="{{ route('login') }}" class="lang text-white w-100 !text-2xl !font-bold">
                            <div class="account-switcher bg-primary-red h-[50px] w-[170px] rounded-md text-center">
                                {{__('My Account') }}
                            </div>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>

<script>
    window.addEventListener('scroll', function() {
        var header = document.querySelector('.header-area');
        header.classList.toggle('sticky', window.scrollY > 0);
    });

    document.querySelector('.search-icon').addEventListener('click', function() {
        var searchArea = document.querySelector('.search-area');
        searchArea.classList.toggle('show');
    });
</script>