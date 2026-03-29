<div class="navbar-header sticky-header bg-white border-bottom" style="overflow-x: clip;">
  <style>
    /* Global header/logo adjustments for mobile visibility and scaling */
    .logo-spanning {
      max-height: 60px;
      max-width: 120px;
      width: auto;
      object-fit: contain;
      display: block;
    }

    @media(max-width:991px) {
      .logo-spanning {
        max-height: 45px;
        max-width: 90px;
      }
    }
  </style>
  <div class="container-fluid px-4">
    <div class="d-flex align-items-stretch">
      <!-- Logo spanning both rows (left side) - Hidden on mobile, shown on desktop -->
      <div class="logo-container d-none d-lg-flex align-items-center pe-4">
        <a href="{{ url('/') }}" class="d-flex align-items-center">
          @php
            $logoPath = (isset($allsettings['main_logo']) && $allsettings['main_logo']) ? asset(IMG_LOGO_PATH . $allsettings['main_logo']) : 'https://c.animaapp.com/mhnmip5wa2i9Oh/img/hi-speed--4-send---final--06-3.png';
          @endphp
          <img src="{{ $logoPath }}" alt="{{ $allsettings['app_title'] ?? config('app.name', 'Logo') }}"
            class="logo-spanning">
        </a>
      </div>

      <!-- Right content: header row + navbar row stacked -->
      <div class="flex-grow-1" style="min-width: 0;">
        {{-- Mobile-only centered logo row --}}
        <div class="d-flex d-lg-none justify-content-center py-2 mb-1">
          <a href="{{ url('/') }}">
            <img src="{{ $logoPath }}" alt="{{ $allsettings['app_title'] ?? config('app.name', 'Logo') }}"
              style="max-height: 50px; width: auto; object-fit: contain;">
          </a>
        </div>
        <!-- Header top row: location + search + actions -->
        <header class="header-top-row d-flex align-items-center py-2">
          <div class="d-none d-md-flex align-items-center text-muted small me-auto">
            <i class="bi bi-geo-alt me-2"></i>
            @php
              $displayLocale = session('HTML_LANG', app()->getLocale() ?? 'en');
              $addressEN = $allsettings['address_en'] ?? $allsettings['address'] ?? null;
              $addressAR = $allsettings['address_ar'] ?? null;
              if (in_array($displayLocale, ['ar', 'fr'])) {
                $addressToShow = $addressAR ?? $addressEN ?? '';
              } else {
                $addressToShow = $addressEN ?? $addressAR ?? '';
              }
            @endphp
            <span>{{ __('Address') }}: {{ $addressToShow }}</span>
          </div>
          <div class="flex-grow-1 text-center mx-3">
            <!-- Search in header -->
            <form class="d-flex justify-content-center search-form-header" onsubmit="return false;">
              <div class="input-group position-relative" style="max-width: 500px;">
                <input id="header-search-input" class="form-control" type="search" placeholder="{{ __('Search Here') }}"
                  aria-label="Search" autocomplete="off">
                <button class="btn btn-success" type="submit">
                  <i class="bi bi-search"></i>
                </button>

                <div id="header-search-results" class="list-group position-absolute"
                  style="z-index:2000; top:100%; left:0; right:0; display:none; max-height:360px; overflow:auto;"></div>
              </div>
            </form>
          </div>
          <div class="d-flex align-items-center gap-3">
            <div class="dropdown">
              @php
                // Load languages and ensure unique locales to avoid duplicate entries
                $availableLangs = languageList() instanceof \Illuminate\Support\Collection ? languageList()->unique('locale')->values() : collect(languageList())->unique('locale')->values();
                // Use display locale (HTML_LANG) from session for UI rendering. This keeps
                // backend DB locale (APP_LOCALE / app()->getLocale()) intact while
                // showing labels in the user's chosen display language.
                $displayLocale = session('HTML_LANG', app()->getLocale() ?? 'en');
                $currentLocale = $displayLocale;
              @endphp
              <button class="btn btn-sm dropdown-toggle border-0" type="button" data-bs-toggle="dropdown">
                <img src="https://c.animaapp.com/mhnmip5wa2i9Oh/img/vector-4.svg" alt="Flag" style="width: 20px;">
                {{ getLanguage($currentLocale)->name ?? strtoupper($currentLocale) }}
              </button>
              <ul class="dropdown-menu" style="z-index: 100000 !important;">
                @foreach($availableLangs as $langItem)
                  @if($langItem->status == 1)
                    <li>
                      <a class="dropdown-item {{ $currentLocale == $langItem->locale ? 'active' : '' }}"
                        href="{{ route('locale.switch', $langItem->locale) }}">{{ $langItem->name }}</a>
                    </li>
                  @endif
                @endforeach
              </ul>
            </div>
          </div>
        </header>

        <!-- Navigation row: menu + icons -->
        <nav class="navbar navbar-expand-lg navbar-light p-0 w-100 d-flex justify-content-between align-items-center">
          {{-- Icons and Action Buttons (Moved outside collapse for mobile visibility) --}}
          <div class="d-flex align-items-center gap-2 gap-md-3 order-1 order-lg-last">
            <a href="{{ route('wishlist') }}" class="text-dark position-relative wishlist-btn header-btn">
              <i class="bi bi-heart fs-5"></i>
              <span class="badge bg-warning position-absolute top-0 start-100 translate-middle">
                <span class="count wishListCuntFromController">{{ auth()->check() ? wishlistCount() : '0' }}</span>
              </span>
            </a>
            <a href="{{ route('cart.content') }}" class="text-dark position-relative cart-btn header-btn">
              <i class="bi bi-bag fs-5"></i>
              <span class="badge bg-warning position-absolute top-0 start-100 translate-middle">
                <span class="count totalCountItem">{{ cartCountItem() }}</span>
              </span>
            </a>
            @if(auth()->check())
              <div class="dropdown">
                <a class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" href="#"
                  id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-person-circle fs-5"></i>
                  <span class="ms-2 d-none d-md-inline">{{ auth()->user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                  <li><a class="dropdown-item"
                      href="{{ route('user.profile') }}">{{ __('Profile', [], $displayLocale) }}</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item text-danger"
                      href="{{ route('user.logout') }}">{{ __('Logout', [], $displayLocale) }}</a></li>
                </ul>
              </div>
            @endif
            <a href="{{ route('user.profile') }}#subscription" class="ms-2">
              <button class="btn btn-success rounded-pill px-3 px-md-4 py-1 py-md-2" style="font-size: 0.9rem;">
                {{ __('Subscribe', [], $displayLocale) }}
                <i class="bi bi-arrow-right ms-1 ms-md-2"></i>
              </button>
            </a>
          </div>

          <button class="navbar-toggler order-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse order-3 order-lg-1" id="navbarNav">
            <ul
              class="navbar-nav {{ in_array($displayLocale ?? app()->getLocale(), ['ar', 'fr']) ? 'ms-auto' : 'me-auto' }} mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('front') ? 'active text-success fw-semibold' : '' }}"
                  href="{{ route('front') }}">{{ __('Home', [], $displayLocale) }}</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#"
                  data-bs-toggle="dropdown">{{ __('Categories', [], $displayLocale) }}</a>
                <ul class="dropdown-menu dropdown-menu-scrollable" style="max-height: 400px; overflow-y: auto;">
                  @php
                    $isAr = in_array($displayLocale ?? app()->getLocale(), ['ar', 'fr']);
                    // Sync sorting with pills: prioritize 'order' then fallback to 'id'
                    $headerCategories = \App\Models\Admin\Category::where('Status', 1)->orderBy('order', 'asc')->orderBy('id', 'asc')->get();
                  @endphp
                  {{-- All Products item matching the pills --}}
                  <li>
                    <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('categories.show') }}">
                      <i class="bi bi-grid-3x3-gap-fill me-2 ms-2 text-success" style="font-size: 1.1rem;"></i>
                      <span class="fw-bold">{{ __('All Products', [], $displayLocale) }}</span>
                    </a>
                  </li>
                  <li>
                    <hr class="dropdown-divider my-1">
                  </li>
                  @foreach($headerCategories as $cat)
                    @php
                      $catName = $isAr ? ($cat->fr_Category_Name ?? $cat->en_Category_Name) : ($cat->en_Category_Name ?? $cat->fr_Category_Name);
                    @endphp
                    <li>
                      <a class="dropdown-item d-flex align-items-center py-2"
                        href="{{ route('categories.show', ['slug' => $cat->en_Category_Slug]) }}">
                        @if($cat->Category_Icon)
                          <img src="{{ asset(CategoryImage() . $cat->Category_Icon) }}" alt=""
                            style="width: 22px; height: 22px; object-fit: contain;" class="me-2 ms-2"
                            onerror="this.style.display='none'">
                        @else
                          <i class="bi bi-tag me-2 ms-2 text-muted"></i>
                        @endif
                        <span>{{ $catName }}</span>
                      </a>
                    </li>
                  @endforeach
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item fw-bold text-success text-center py-2"
                      href="{{ route('categories.show') }}">{{ __('See All', [], $displayLocale) }}</a></li>
                </ul>
                <script>
                  document.addEventListener('DOMContentLoaded', function () {
                    const input = document.getElementById('header-search-input');
                    const resultsBox = document.getElementById('header-search-results');
                    const searchUrl = '{{ route('search.suggest') }}';
                    const productBase = '{{ url('product/single-new') }}';

                    let timer = null;
                    function hideResults() {
                      resultsBox.style.display = 'none';
                      resultsBox.innerHTML = '';
                    }

                    function showResults(items) {
                      if (!items || items.length === 0) {
                        hideResults();
                        return;
                      }
                      resultsBox.innerHTML = '';
                      items.forEach(function (it) {
                        // Priority based on current locale
                        const name = (locale === 'ar') 
                          ? (it.ar_Product_Name || it.fr_Product_Name || it.en_Product_Name || '')
                          : (it.en_Product_Name || it.fr_Product_Name || it.ar_Product_Name || '');
                        
                        const slug = it.en_Product_Slug || '';
                        const itemEl = document.createElement('a');
                        itemEl.href = productBase + '/' + encodeURIComponent(slug);
                        itemEl.className = 'list-group-item list-group-item-action d-flex align-items-center';
                        itemEl.style.gap = '12px';
                        
                        const img = document.createElement('img');
                        // Use pre-resolved and secure Primary_Image from controller, or global fallback
                        img.src = it.Primary_Image ? it.Primary_Image : '{{ asset("new-design/images/special-offer.png") }}';
                        img.alt = name;
                        img.style.width = '48px';
                        img.style.height = '48px';
                        img.style.objectFit = 'contain';
                        img.className = 'rounded border bg-light';

                        const txt = document.createElement('div');
                        txt.innerHTML = '<div class="fw-semibold text-truncate" style="max-width: 250px;">' + name + '</div>';

                        itemEl.appendChild(img);
                        itemEl.appendChild(txt);
                        resultsBox.appendChild(itemEl);
                      });
                      resultsBox.style.display = 'block';
                    }

                    input.addEventListener('input', function (e) {
                      const q = e.target.value.trim();
                      if (timer) clearTimeout(timer);
                      if (q.length < 2) {
                        hideResults();
                        return;
                      }
                      timer = setTimeout(function () {
                        fetch(searchUrl + '?query=' + encodeURIComponent(q))
                          .then(function (res) { return res.json(); })
                          .then(function (data) {
                            showResults(data || []);
                          })
                          .catch(function (err) { console.error(err); hideResults(); });
                      }, 250);
                    });

                    document.addEventListener('click', function (e) {
                      if (!resultsBox.contains(e.target) && e.target !== input) {
                        hideResults();
                      }
                    });
                  });
                </script>
              <li class="nav-item"><a class="nav-link" href="{{ route('faq') }}">{{ __('FAQ', [], $displayLocale) }}</a>
              </li>
              <li class="nav-item"><a class="nav-link"
                  href="{{ route('about.us') }}">{{ __('About Us', [], $displayLocale) }}</a></li>
              <li class="nav-item"><a class="nav-link"
                  href="{{ route('contact.us') }}">{{ __('Contact Us', [], $displayLocale) }}</a></li>
            </ul>
          </div>
        </nav>
        @if(request()->routeIs('categories.show'))
          <style>
            .sub-nav {
              border-top: 1px solid rgba(0, 0, 0, 0.04);
              background: #fff;
              position: relative;
              width: 100%;
              border-bottom: 1px solid rgba(0, 0, 0, 0.02);
            }

            .category-pills-wrapper {
              position: relative;
              display: flex;
              align-items: center;
              flex-grow: 1;
              overflow: hidden;
              min-width: 0;
            }

            .category-pills-wrapper::after {
              content: "";
              position: absolute;
              top: 0;
              right: 0;
              bottom: 0;
              width: 50px;
              background: linear-gradient(to right, rgba(255, 255, 255, 0), #fff);
              pointer-events: none;
              z-index: 2;
            }

            [dir="rtl"] .category-pills-wrapper::after {
              left: 0;
              right: auto;
              background: linear-gradient(to left, rgba(255, 255, 255, 0), #fff);
            }

            .category-pills {
              display: flex;
              gap: 8px;
              align-items: center;
              overflow-x: auto;
              -webkit-overflow-scrolling: touch;
              scrollbar-width: none;
              flex-grow: 1;
              padding-right: 50px;
              flex-wrap: nowrap;
              min-width: 0;
            }

            [dir="rtl"] .category-pills {
              padding-right: 0;
              padding-left: 50px;
            }

            .category-pills::-webkit-scrollbar {
              display: none;
            }

            .category-pill {
              display: inline-block;
              padding: 6px 14px;
              border-radius: 24px;
              background: #fff;
              color: #222 !important;
              border: 1px solid rgba(0, 0, 0, 0.1);
              font-weight: 700;
              white-space: nowrap;
              transition: all 0.2s;
              text-decoration: none;
              flex-shrink: 0;
              font-size: 14px;
            }

            .category-pill:hover {
              border-color: #000;
              background: #f8f9fa;
              color: #000 !important;
            }

            .category-pill.active {
              background: #000 !important;
              color: #fff !important;
              border-color: #000 !important;
            }

            .category-pill-more {
              background: #000 !important;
              color: #fff !important;
              border-color: #000 !important;
              margin-inline-end: 8px;
              flex-shrink: 0;
            }

            @media(max-width:767px) {
              .navbar-header .container-fluid {
                padding-inline: 10px !important;
              }

              .category-pill {
                padding: 5px 12px;
                font-size: 12px;
              }

              .sub-nav .container {
                padding-inline: 5px !important;
              }
            }

            .hover-bg-light:hover {
              background-color: #f8f9fa;
              border-color: #000 !important;
            }
          </style>

          <div class="sub-nav navbar-light">
            <div class="container d-flex align-items-center justify-content-start py-2">
              <div class="category-pills-wrapper">
                {{-- More toggler always at the front for easy access --}}
                <a href="javascript:void(0)" class="category-pill category-pill-more" data-bs-toggle="offcanvas"
                  data-bs-target="#categoryOffcanvas">
                  <i class="bi bi-grid-3x3-gap-fill"></i>
                </a>

                <div class="category-pills">
                  @php
                    $locale = session('HTML_LANG', app()->getLocale() ?? 'en');
                    $currentSlug = request()->route('slug') ?? request()->segment(2) ?? null;

                    if (empty($cats)) {
                      $cats = \App\Models\Admin\Category::where('Status', 1)->orderBy('order')->orderBy('id')->get();
                    }
                  @endphp

                  {{-- All Products pill --}}
                  <a href="{{ url('/categories') }}"
                    class="category-pill {{ (request()->routeIs('categories.show') && empty($currentSlug)) ? 'active' : '' }}">{{ __('All Products', [], $locale) }}</a>

                  @foreach($cats->take(20) as $cat)
                    @php
                      $dbPrefix = in_array($locale, ['ar', 'fr']) ? 'fr' : $locale;
                      $label = $cat->{"{$dbPrefix}_Category_Name"} ?? $cat->en_Category_Name ?? $cat->name ?? 'Category';
                      $enSlug = $cat->en_Category_Slug ?? $cat->slug ?? '';
                      $isActive = $currentSlug && $enSlug && (strtolower(trim($currentSlug)) === strtolower(trim($enSlug)));
                      $href = $enSlug ? route('categories.show', $enSlug) : '#';
                    @endphp
                    <a href="{{ $href }}" class="category-pill {{ $isActive ? 'active' : '' }}">{{ $label }}</a>
                  @endforeach
                </div>
              </div>
            </div>
          </div>

          <!-- All Categories Offcanvas -->
          <div class="offcanvas offcanvas-bottom" tabindex="-1" id="categoryOffcanvas"
            style="height: 70vh; border-top-left-radius: 20px; border-top-right-radius: 20px;">
            <div class="offcanvas-header border-bottom py-3">
              <h5 class="offcanvas-title fw-bold">{{ __('All Categories', [], $locale) }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-4">
              <div class="row g-3">
                @foreach($cats as $cat)
                  @php
                    $dbPrefix = ($locale === 'ar') ? 'fr' : $locale;
                    $label = $cat->{"{$dbPrefix}_Category_Name"} ?? $cat->en_Category_Name ?? $cat->name ?? 'Category';
                    $enSlug = $cat->en_Category_Slug ?? $cat->slug ?? '';
                    $href = $enSlug ? route('categories.show', $enSlug) : '#';
                  @endphp
                  <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ $href }}"
                      class="d-flex align-items-center p-3 border rounded text-decoration-none text-dark hover-bg-light transition-all h-100">
                      <span class="fw-semibold text-truncate">{{ $label }}</span>
                    </a>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
          <script>
            document.addEventListener('DOMContentLoaded', function () {
              const activePill = document.querySelector('.category-pill.active');
              if (activePill) {
                // Short delay to ensure browser has finished initial layout/rendering
                setTimeout(() => {
                  activePill.scrollIntoView({
                    behavior: 'smooth',
                    inline: 'center',
                    block: 'nearest'
                  });
                }, 200);
              }
            });
          </script>
        @endif
      </div>
    </div>
  </div>
</div>