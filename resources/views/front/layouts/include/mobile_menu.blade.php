<!-- mobile-menu-area area start here  -->
<div class="offcanvas offcanvas-start menu-offcanvas !w-[250px]" tabindex="-1" id="offcanvasMobileMenu">
    <div class="mobile-menu-area">
        <div class="offcanvas-header flex justify-between">
            <a class="brand-logo" href="{{ route('front') }}"><img class="brand-image"
            src="{{ asset(IMG_LOGO_PATH . $allsettings['main_logo']) }}"
            alt="{{ $allsettings['app_title'] }}" /></a>
            <button type="button" class="btn-close !text-black !text-5xl" data-bs-dismiss="offcanvas" aria-label="Close">x</button>
        </div>
        <nav class="main-menu">
            <ul class="menu-list">
                @foreach ($all_menus as $menu)
                    @if ($menu->submenus->count() == 0)
                        <li class="menu-item"><a class="menu-link"
                                href="{{ $menu->url }}">{{ langConverter($menu->en_name, $menu->fr_name) }}</a>
                        </li>
                    @elseif (strtolower($menu->en_name) === "categories")
                        <li class="menu-item">
                            <span class="menu-expand"></span>
                            <a class="menu-link" href="#">{{ langConverter($menu->en_name, $menu->fr_name) }}</a>
                            <ul class="sub-menu">
                                @foreach (Category_Des_Icon()->take(8) as $submenu)
                                    <li class="sub-menu-item"><a class="sub-menu-link"
                                            href="{{route('category.product', $submenu->id)}}">{{ langConverter($submenu->en_Category_Name, $submenu->fr_Category_Name) }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="menu-item">
                            <span class="menu-expand"></span>
                            <a class="menu-link" href="#">{{ langConverter($menu->en_name, $menu->fr_name) }}</a>
                            <ul class="sub-menu">
                                @foreach ($menu->submenus as $submenu)
                                    <li class="sub-menu-item"><a class="sub-menu-link"
                                            href="{{ $submenu->url }}">{{ langConverter($submenu->en_name, $submenu->fr_name) }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
        <div class="menu-bottom mt-10 flex flex-col gap-3">
            <div class="switcher-lang-currency">
                <div class="lang-switcher">
                    @if (app()->getLocale() == 'en')
                        @if(getLanguage('en')->status == 1)
                            <span class="flag">
                            <!-- <img src="{{ asset(IMG_LANGUAGE . 'en.png') }}" alt="united-states" /> -->
                            </span>
                            <a href="javascript:void(0)" class="lang">{{ getLanguage('en')->name }}
                                @if(getLanguage('fr')->status == 1)
                                    <i class="fas fa-angle-down"></i>
                                @endif
                            </a>
                        @endif
                    @elseif(app()->getLocale() == 'fr')
                        @if(getLanguage('fr')->status == 1)
                            <span class="flag">
                            <!-- <img src="{{ asset(IMG_LANGUAGE . 'fr.png') }}" alt="oman" /> -->
                            </span>
                            <a href="javascript:void(0)" class="lang">{{ getLanguage('fr')->name }}
                                @if(getLanguage('en')->status == 1)
                                    <i class="fas fa-angle-down"></i>
                                @endif
                            </a>
                        @endif
                    @endif

                    <ul class="{{ activeLanguage() > 1 ? 'lang-list' : '' }}">
                        @if (app()->getLocale() == 'en')
                            @if(getLanguage('fr')->status == 1)
                                <li class="single-lang"><span class="flag">
                                <!-- <img src="{{ asset(IMG_LANGUAGE . 'fr.png') }}" alt="oman"> -->
                                </span><a class="lang-text"
                                        href="{{ route('locale.switch', 'fr') }}">{{ getLanguage('fr')->name }}</a>
                                </li>
                            @endif
                        @elseif(app()->getLocale() == 'fr')
                            @if(getLanguage('en')->status == 1)
                                <li class="single-lang">
                                    <span class="flag">
                                <!-- <img src="{{ asset(IMG_LANGUAGE . 'en.png') }}" alt="united-states" /> -->
                                </span>
                                <a class="lang-text"
                                        href="{{ route('locale.switch', 'en') }}">{{ getLanguage('en')->name }}</a>
                                </li>
                            @endif
                        @endif
                    </ul>
                </div>
            </div>
            @if (Auth::user())
                @if (Auth::user()->is_admin == ACTIVE)
                    <a class="account-btn mb-3" href="{{ route('admin.dashboard') }}"><i class="user-icon flaticon-user"></i>
                        {{ __('Dashboard') }}</a>
                @else
                    <a class="account-btn mb-3" href="{{ route('user.profile') }}"><i class="user-icon flaticon-user"></i>
                        {{ __('Profile') }}</a>
                @endif
                <a class="account-btn mb-3" href="{{ route('user.logout') }}"><i class="user-icon flaticon-user"></i>
                    {{ __('Logout') }}</a>
            @else
                <a class="account-btn" href="{{ route('login') }}"><i class="user-icon flaticon-user"></i>
                    {{ __('My Account') }} </a>
            @endif
        </div>
    </div>
</div>
<!-- mobile-menu-area area end here  -->