<!-- Header section start -->
<header class="header__area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="header__navbar">
                    <div class="header__navbar__left">
                        <button class="sidebar-toggler">
                            <img src="{{asset('admin/images/icons/header/bars.svg')}}" alt="">
                        </button>
                        <a href="{{route('front')}}" target="_blank" class="btn btn-primary text-white">{{__('Visit Site')}}</a>
                    </div>
                    <div class="switcher-lang-currency flex items-center gap-5">
                        <div class="lang-switcher">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if(app()->getLocale() == 'en')
                                        <!-- <img src="{{ asset(IMG_LANGUAGE . 'en.png') }}" alt="English" width="24" height="24" class="me-2"> -->
                                        English
                                    @elseif(app()->getLocale() == 'fr')
                                        <!-- <img src="{{ asset(IMG_LANGUAGE . 'fr.png') }}" alt="Arabic" width="24" height="24" class="me-2"> -->
                                        Arabic
                                    @endif
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                                    @if(getLanguage('en')->status == 1 && app()->getLocale() != 'en')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('locale.switch', 'en') }}">
                                                <!-- <img src="{{ asset(IMG_LANGUAGE . 'en.png') }}" alt="English" width="24" height="24" class="me-2"> -->
                                                English
                                            </a>
                                        </li>
                                    @endif
                                    @if(getLanguage('fr')->status == 1 && app()->getLocale() != 'fr')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('locale.switch', 'fr') }}">
                                                <!-- <img src="{{ asset(IMG_LANGUAGE . 'fr.png') }}" alt="Arabic" width="24" height="24" class="me-2"> -->
                                                Arabic
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="header__navbar__right">
                        <ul class="header__menu">
                            <li>
                                <a href="#" class="btn btn-dropdown user-profile" data-bs-toggle="dropdown">
                                    <img src="{{!is_null(Auth::user()->image) ? asset(AdminProfilePicture().Auth::user()->image) : Avatar::create(Auth::user()->name)->toBase64()}}" alt="{{__('icon')}}">
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{route('admin.profile')}}">
                                            <img src="{{asset('admin/images/icons/user.svg')}}" alt="icon">
                                            <span>{{__('Profile')}}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                            <img src="{{asset('admin/images/icons/logout.svg')}}" alt="icon">
                                            <span>{{__('Logout')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Header section end -->
