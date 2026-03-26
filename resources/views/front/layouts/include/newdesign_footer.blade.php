<!-- New-design footer include (extracted from new-design/index.html) -->
<footer class="footer">
  <div class="footer-top" style="background:#EDF2EE;">
    <style>
      /* Footer social icons: default outline, green on hover */
      .footer-top .social-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(0, 0, 0, 0.08);
        color: #2b2b2b;
        transition: all .12s ease-in-out;
        background: transparent;
        text-decoration: none;
      }

      .footer-top .social-icon i {
        font-size: 14px;
      }

      .footer-top .social-icon:hover {
        background: #9fc23a;
        color: #fff;
        border-color: transparent;
        text-decoration: none;
      }
    </style>
    <div class="container d-flex align-items-center justify-content-between py-3">
      <div>
        @php
          $logoPath = (isset($allsettings['footer_logo']) && $allsettings['footer_logo']) ? asset(IMG_LOGO_PATH . $allsettings['footer_logo']) : (isset($allsettings['main_logo']) && $allsettings['main_logo'] ? asset(IMG_LOGO_PATH . $allsettings['main_logo']) : 'https://c.animaapp.com/mhnmip5wa2i9Oh/img/hi-speed--4-send---final--06-3.png');
        @endphp
        <img src="{{ $logoPath }}" alt="Logo" style="height:48px">
      </div>
      <div class="d-flex gap-3">
        @php
          $social = getSocialLink();
          function absUrl($url)
          {
            if (!$url)
              return null;
            $url = trim($url);
            if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0 || strpos($url, '//') === 0) {
              return $url;
            }
            return 'https://' . ltrim($url, '/');
          }
        @endphp

        {{-- Order: Instagram, Twitter, Facebook (display only if set) --}}
        @if($social && $social->Instagram)
          <a href="{{ absUrl($social->Instagram) }}" target="_blank" rel="noopener" class="social-icon"
            aria-label="Instagram"><i class="bi bi-instagram"></i></a>
        @endif
        @if($social && $social->Twitter)
          <a href="{{ absUrl($social->Twitter) }}" target="_blank" rel="noopener" class="social-icon"
            aria-label="Twitter"><i class="bi bi-twitter"></i></a>
        @endif
        @if($social && $social->Facebook)
          <a href="{{ absUrl($social->Facebook) }}" target="_blank" rel="noopener" class="social-icon"
            aria-label="Facebook"><i class="bi bi-facebook"></i></a>
        @endif
      </div>
    </div>
  </div>

  <div class="footer-main text-white" style="background:#002603 !important;">
    <div class="container py-5">
      <div class="row g-4 mb-5">
        <div class="col-lg-4 col-12 d-none d-lg-block">
          <h5 class="mb-3 fw-semibold mt-4">{{ __('About Hi Speed') }}</h5>
          <p class="text-muted mb-3">
            {{ langConverter(
                isset($allsettings['footer_about_en']) && !empty($allsettings['footer_about_en']) ? $allsettings['footer_about_en'] : 'It is a leading e-commerce platform in Muscat, specializing in delivering fresh and traditional produce directly from the farm to your doorstep, with a focus on quality and modern packaging.',
                isset($allsettings['footer_about_fr']) && !empty($allsettings['footer_about_fr']) ? $allsettings['footer_about_fr'] : 'هي منصة إلكترونية رائدة في مسقط، متخصصة في توفير المنتجات الطازجة والتقليدية من المزرعة إلى عتبة المنزل مباشرة، مع التركيز على الجودة والتغليف العصري.'
            ) }}
          </p>
        </div>
        <div class="col-lg-2 col-6">
          <h5 class="mb-3 fw-semibold">{{ __('My Account') }}</h5>
          <ul class="list-unstyled">
            @php
              $accountLinks = [
                ['label' => __('My Account'), 'route' => 'user.profile'],
                ['label' => __('Order History'), 'route' => 'user.profile.myOrder'],
                ['label' => __('Shopping Cart'), 'route' => 'cart.content'],
                ['label' => __('Wishlist'), 'route' => 'wishlist'],
                ['label' => __('Settings'), 'route' => 'settings']
              ];
            @endphp
            @foreach($accountLinks as $ln)
              @if(!empty($ln['route']) && Route::has($ln['route']))
                <li class="mb-2"><a href="{{ route($ln['route']) }}"
                    class="text-muted text-decoration-none hover-link">{{ $ln['label'] }}</a></li>
              @endif
            @endforeach
          </ul>
        </div>
        <div class="col-lg-2 col-6">
          <h5 class="mb-3 fw-semibold">{{ __('Helps') }}</h5>
          <ul class="list-unstyled">
            @php
              $helpLinks = [
                ['label' => __('Contact'), 'route' => 'contact.us'],
                ['label' => __('FAQ'), 'route' => 'faq'],
                // Prefer new-design terms/privacy if available
                ['label' => __('Terms & Condition'), 'route' => (Route::has('terms.conditions.new') ? 'terms.conditions.new' : 'terms.conditions')],
                ['label' => __('Privacy Policy'), 'route' => (Route::has('privacy.policy.new') ? 'privacy.policy.new' : 'privacy.policy')],
                ['label' => __('Shipping & Return'), 'route' => (Route::has('shipping.return.new') ? 'shipping.return.new' : 'shipping.return')],
              ];
            @endphp
            @foreach($helpLinks as $ln)
              @if(!empty($ln['route']) && Route::has($ln['route']))
                <li class="mb-2"><a href="{{ route($ln['route']) }}"
                    class="text-muted text-decoration-none hover-link">{{ $ln['label'] }}</a></li>
              @endif
            @endforeach
          </ul>
        </div>
        <div class="col-lg-2 col-6">
          <h5 class="mb-3 fw-semibold">{{ __('Proxy') }}</h5>
          <ul class="list-unstyled">
            @php
              $proxyLinks = [
                ['label' => __('About Us'), 'route' => 'about.us'],
                ['label' => __('Shop'), 'route' => 'all.product'],
                ['label' => __('Product'), 'route' => 'all.product'],
                ['label' => __('Products Details'), 'route' => 'single.product'],
                ['label' => __('Track order'), 'route' => 'checkout.order_track'],
              ];
            @endphp
            @foreach($proxyLinks as $ln)
              @if(!empty($ln['route']) && Route::has($ln['route']))
                @php
                  try {
                    $url = route($ln['route']);
                  } catch (\Throwable $e) {
                    // route requires parameters or failed to generate; fallback to safe pages
                    if (Route::has('all.product')) {
                      $url = route('all.product');
                    } elseif (Route::has('categories.show')) {
                      $url = route('categories.show');
                    } else {
                      $url = url('/');
                    }
                  }
                @endphp
                <li class="mb-2"><a href="{{ $url }}"
                    class="text-muted text-decoration-none hover-link">{{ $ln['label'] }}</a></li>
              @endif
            @endforeach
          </ul>
        </div>
        <div class="col-lg-2 col-6">
          <h5 class="mb-3 fw-semibold">{{ __('Categories') }}</h5>
          <ul class="list-unstyled">
            @php
              try {
                if (class_exists('\\App\\Models\\Admin\\Category')) {
                  $footerCats = \App\Models\Admin\Category::where('Status', 1)->orderBy('order')->take(6)->get();
                } elseif (class_exists('\\App\\Models\\Category')) {
                  $footerCats = \App\Models\Category::where('Status', 1)->orderBy('order')->take(6)->get();
                } else {
                  $footerCats = collect();
                }
              } catch (\Throwable $e) {
                $footerCats = collect();
              }
            @endphp

            @if($footerCats->isNotEmpty())
              @foreach($footerCats as $fc)
                @php
                  $slug = $fc->localized_slug;
                  try {
                    $url = Route::has('categories.show') ? route('categories.show', $slug) : url('/categories');
                  } catch (\Throwable $e) {
                    $url = url('/categories');
                  }
                  $label = $fc->localized_name;
                @endphp
                <li class="mb-2"><a href="{{ $url }}" class="text-muted text-decoration-none hover-link">{{ $label }}</a>
                </li>
              @endforeach
            @else
              @php $categoryUrl = Route::has('categories.show') ? route('categories.show') : url('/categories'); @endphp
              <li class="mb-2"><a href="{{ $categoryUrl }}"
                  class="text-muted text-decoration-none hover-link">{{ __('All Products') }}</a></li>
            @endif
          </ul>
        </div>
      </div>

      <div class="border-top border-secondary py-3 mt-3">
        <div class="row align-items-center">
          <div class="col-md-4 d-none d-md-block"></div>
          <div class="col-12 col-md-4 text-center mb-2 mb-md-0">
            <p class="text-muted mb-0">{{ __('HiSpeed © :year. All Rights Reserved', ['year' => date('Y')]) }}</p>
          </div>
          <div class="col-12 col-md-4">
            <div class="d-flex justify-content-center justify-content-md-end align-items-center gap-3">
              <img src="https://c.animaapp.com/mhnmip5wa2i9Oh/img/applepay.svg" alt="Apple Pay" style="height: 32px;">
              <img src="https://c.animaapp.com/mhnmip5wa2i9Oh/img/visa-logo.svg" alt="Visa" style="height: 32px;">
              <img src="https://c.animaapp.com/mhnmip5wa2i9Oh/img/discover-1.png" alt="Discover" style="height: 32px;">
              <img src="https://c.animaapp.com/mhnmip5wa2i9Oh/img/mastercard-1.png" alt="Mastercard"
                style="height: 32px;">
              <span class="text-muted ms-2">{{ __('Secure Payment') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>