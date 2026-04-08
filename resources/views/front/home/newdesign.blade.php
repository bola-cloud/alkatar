@extends('front.layouts.new_design_layout')

@section('title', isset($title) ? $title : __('HiSpeed — New Design'))
@section('description', isset($description) ? $description : '')
@section('keywords', isset($keywords) ? $keywords : '')

@section('content')
  <!-- Hero Carousel -->
  <section class="hero-section py-4">
    <div class="container-fluid px-4">
      @php
        $slides = \App\Models\Admin\Advertise::where('location','hero')->where('status',1)->orderBy('display_order')->orderBy('id')->get();
      @endphp

      <link rel="stylesheet" href="{{ asset('admin/css/swiper-bundle.min.css') }}">
      
      <div class="swiper hero-swiper">
        <div class="swiper-wrapper">
          @foreach($slides as $i => $slide)
            @php

              $imgUrl = null;
              if (!empty($slide->image)) {
                 $imgUrl = asset($slide->image);
                 // Fallback to PromotionImage path if not full path, but avoiding file_exists check
                 // We assume the data is correct or let it break (or use client-side onerror if needed)
                 if (!str_contains($slide->image, 'uploaded_files')) {
                     $imgUrl = asset(PromotionImage() . $slide->image);
                 }
              } else {
                $imgUrl = 'https://c.animaapp.com/mhnmip5wa2i9Oh/img/bannar-big-2.png';
              }

              // localized title, subtitle and small description: prefer display locale (session HTML_LANG)
              $locale = session('HTML_LANG', app()->getLocale() ?? 'en');
              if (in_array($locale, ['ar', 'fr'])) {
                $title = $slide->ar_title ?? $slide->fr_title ?? $slide->en_title ?? __('Fresh & Healthy');
                $subtitle = $slide->ar_subtitle ?? $slide->fr_subtitle ?? $slide->en_subtitle ?? '';
                $smallDescription = $slide->ar_small_description ?? '';
              } else {
                $title = $slide->en_title ?? $slide->ar_title ?? $slide->fr_title ?? __('Fresh & Healthy');
                $subtitle = $slide->en_subtitle ?? $slide->ar_subtitle ?? $slide->fr_subtitle ?? '';
                $smallDescription = $slide->en_small_description ?? '';
              }
            @endphp

            <div class="swiper-slide">
              <div class="hero-banner d-flex align-items-center" style="background: linear-gradient(108deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0) 100%), url({{ $imgUrl }}) center/cover;">
                <div class="container container-hero-peek">
                  <div class="row">
                    <div class="col-lg-10">
                      <h1 class="display-3 fw-bold text-white mb-4">{!! $title !!}@if($subtitle)<br>{!! $subtitle !!}@endif</h1>
                      <div class="d-flex align-items-center mb-3">
                        <div class="bg-success" style="width: 3px; height: 60px;"></div>
                        <div class="ms-3">
                          @php $saleLines = preg_split('/\r?\n/', trim($smallDescription ?? ''));
                          @endphp
                          @if(!empty($saleLines) && count(array_filter($saleLines)) > 0)
                            <p class="text-white mb-1">{!! $saleLines[0] ?? '' !!}</p>
                            @if(isset($saleLines[1]) && trim($saleLines[1]) !== '')
                              <p class="text-white-50 small mb-0">{!! $saleLines[1] !!}</p>
                            @endif
                          @else
                            <p class="text-white mb-1">{{ __('Sale up to') }} <span class="badge bg-success fs-6">{{ __('30% OFF') }}</span></p>
                            <p class="text-white-50 small mb-0">{{ __('Free shipping on all your order.') }}</p>
                          @endif
                        </div>
                      </div>
                      <a href="{{ $slide->link ?? '#' }}" class="btn btn-light btn-lg rounded-pill px-5 mt-3" target="_blank">
                        {{ __('Shop Now') }} <i class="bi bi-arrow-right ms-2"></i>
                      </a>
                    </div> <!-- col-lg-10 -->
                  </div> <!-- row -->
                </div> <!-- container -->
              </div> <!-- hero-banner -->
            </div> <!-- swiper-slide -->
          @endforeach
        </div>
        <div class="swiper-pagination hero-pagination mt-4"></div>
      </div>
    </div>
  </section>


  <!-- Categories Section -->
  <section class="category-section py-5 bg-white border-bottom">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">{{ __('Browse Categories') }}</h2>
        <a href="{{ Route::has('categories.show') ? route('categories.show') : url('/categories') }}" class="btn btn-link text-warning text-decoration-none p-0">
          {{ __('View All') }} <i class="bi bi-arrow-right"></i>
        </a>
      </div>
      <div class="row">
        <div class="col-12">
          @include('front.home.partials._category_carousel', ['categories' => $allCategories])
        </div>
      </div>
    </div>
  </section>

  <!-- Today Special Offers -->
  <section class="special-offers py-5 bg-light position-relative">
    <div class="left-decorative" aria-hidden="true"></div>
    {{-- Decorative grass at top-right --}}
    <img src="{{ asset('new-design/images/grass.png') }}" alt="decorative grass" class="position-absolute" style="top:18px; right:24px; width:110px; max-width:18%; pointer-events:none; z-index:2; opacity:0.98;">

    <div class="container">
        <div class="text-center mb-5">
        <h2 class="display-5 fw-bold">
          @if(in_array(app()->getLocale(), ['ar','fr']))
            {!! __('Today Special Offers') !!}
          @else
            {{ __('Today') }} <span class="text-success">{{ __('Special') }}</span> {{ __('Offers') }}
          @endif
        </h2>
        <p class="text-muted">{{ __('Lorem Ipsum Is Simply Dummy Text Of The Printing And Typesetting Industry.') }}</p>
      </div>

      {{-- Render dynamic product carousel partial --}}
      <div class="row">
        <div class="col-12">
          {{-- Use the controller-provided $products collection (Today Special or latest 5 fallback) --}}
          @include('front.home.partials._product_carousel', ['products' => $products, 'carouselId' => 'specialOffersCarousel'])
        </div>
      </div>
    </div>
  </section>

  <!-- Best Seller Products -->
  <section class="best-sellers py-5">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">{{ __('Best Seller Products') }}</h2>
        <a href="{{ Route::has('categories.show') ? route('categories.show') : url('/categories') }}" class="btn btn-link text-warning text-decoration-none">
          {{ __('View All') }} <i class="bi bi-arrow-right"></i>
        </a>
      </div>

      <div class="row">
        <div class="col-12">
          @if(isset($bestSellers) && $bestSellers->count())
            @include('front.home.partials._product_carousel', ['products' => $bestSellers, 'carouselId' => 'bestSellersCarousel'])
          @else
            {{-- as controller provides fallback latest 5, this path should rarely run, but keep graceful fallback --}}
            <p class="text-muted">{{ __('No best seller products available.') }}</p>
          @endif
        </div>
      </div>
    </div>
  </section>

  {{-- Dynamic Featured Categories Sections --}}
  @php
    $allFeatured = $featuredCategories;
    $mainFeatured = $allFeatured->slice(0, -1);
    $lastFeatured = $allFeatured->last();
  @endphp

  @foreach($mainFeatured as $featCat)
    @php
      $catName = langConverter($featCat->en_Category_Name, $featCat->fr_Category_Name);
      $catSlug = $featCat->en_Category_Slug;
      $catProducts = $featCat->products;
    @endphp
    @if($catProducts->count() > 0)
      <section class="featured-category-section py-5">
        <div class="container">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">{{ $catName }}</h2>
            <a href="{{ route('categories.show', ['slug' => $featCat->en_Category_Slug]) }}" class="btn btn-link text-warning text-decoration-none">
              {{ __('View All') }} <i class="bi bi-arrow-right"></i>
            </a>
          </div>

          <div class="row">
            <div class="col-12">
              @include('front.home.partials._product_carousel', [
                'products' => $catProducts, 
                'carouselId' => 'catCarousel_' . $featCat->id
              ])
            </div>
          </div>
        </div>
      </section>
    @endif
  @endforeach

  <!-- Sale Banner -->
  <section class="sale-banner py-5">
    <div class="container-fluid px-4">
      @php
        // Prefer settings-based JSON value, fallback to Homepage row
        $saleImg = null;
        $displayLocale = session('HTML_LANG', app()->getLocale() ?? 'en');

        $settingJson = null;
        try {
            $settingJson = DB::table('settings')->where('slug', 'home_newdesign_sale_banner')->value('value');
        } catch (\Exception $e) {
            $settingJson = null;
        }

        $saleData = null;
        if ($settingJson) {
            $decoded = json_decode($settingJson, true);
            if (is_array($decoded)) {
                if (isset($decoded[$displayLocale])) {
                    $saleData = $decoded[$displayLocale];
                } elseif (isset($decoded['en'])) {
                    $saleData = $decoded['en'];
                }
            }
        }

        if (!$saleData) {
          // Prefer homepage_sections table if present
          $section = \App\Models\Admin\SiteContent\HomepageSection::where('section_key', 'newdesign_sale_banner')->first();
          if ($section) {
            $sdata = $section->content_en ?? [];
            $sdata_fr = $section->content_fr ?? [];
            if ($displayLocale === 'ar' && !empty($sdata_fr)) {
              $saleData = $sdata_fr;
            } elseif (!empty($sdata)) {
              $saleData = $sdata;
            }
            if (empty($saleData)) {
              $saleData = ['title'=>'','lead'=>'','button'=>['text'=>'','url'=>'#'],'image'=>$section->image ?? null];
            } else {
              $saleData['image'] = $section->image ?? ($saleData['image'] ?? null);
            }
          } else {
            $saleRow = \App\Models\Admin\SiteContent\Homepage::where('Location', 'newdesign_sale_banner')->first();
            if ($saleRow) {
              $saleData = [
                'title' => $saleRow->en_Title ?? $saleRow->fr_Title ?? '',
                'lead' => $saleRow->en_Description_One ?? $saleRow->fr_Description_One ?? '',
                'button' => [ 'text' => $saleRow->en_button_text ?? $saleRow->fr_button_text ?? '', 'url' => $saleRow->en_button_url ?? $saleRow->fr_button_url ?? '#' ],
                'image' => $saleRow->image ?? null
              ];
              // if displayLocale is non-en, prefer localized fields from row if exist
              if ($displayLocale === 'ar') {
                $saleData['title'] = $saleRow->fr_Title ?? $saleRow->en_Title ?? $saleData['title'];
                $saleData['lead'] = $saleRow->fr_Description_One ?? $saleRow->en_Description_One ?? $saleData['lead'];
                $saleData['button']['text'] = $saleRow->fr_button_text ?? $saleRow->en_button_text ?? $saleData['button']['text'];
                $saleData['button']['url'] = $saleRow->fr_button_url ?? $saleRow->en_button_url ?? $saleData['button']['url'];
              }
            }
          }
        }

          // ensure we always have an array to avoid "offset on value of type null" errors
          if (!is_array($saleData)) {
            $saleData = [
              'title' => '',
              'lead' => '',
              'button' => ['text' => '', 'url' => '#'],
              'image' => null,
            ];
          }

        // resolve image from either settings or row
        $candidate = $saleData['image'] ?? null;
        if ($candidate) {
          if (stripos($candidate, 'http://') === 0 || stripos($candidate, 'https://') === 0) {
            $saleImg = $candidate;
          } elseif (file_exists(public_path($candidate))) {
            $saleImg = asset($candidate);
          } elseif (file_exists(public_path(PromotionImage() . $candidate))) {
            $saleImg = asset(PromotionImage() . $candidate);
          } elseif (strpos($candidate, 'uploaded_files') !== false) {
            $saleImg = asset($candidate);
          }
        }

        $saleTitle = $saleData['title'] ?? '';
        $saleDesc = $saleData['lead'] ?? '';
        $saleBtnText = $saleData['button']['text'] ?? '';
        $saleBtnUrl = $saleData['button']['url'] ?? '#';
      @endphp

      <div class="row align-items-center rounded-3 p-4 best-deals" style="background: {{ $saleImg ? 'url(' . $saleImg . ') center/cover' : 'linear-gradient(90deg,#ffc107,#ff8a00)' }};">
        <div class="col-12 text-center text-white py-5">
          <h2 class="fw-bold">{!! $saleTitle ?: __('Sale of the Month') !!}</h2>
          <p class="mb-3">{!! $saleDesc ?: __('Best deals and limited time offers. Don\'t miss out!') !!}</p>
          @if($saleBtnText)
            <a href="{{ $saleBtnUrl }}" class="btn btn-light rounded-pill px-4" target="_blank">{!! $saleBtnText !!} <i class="bi bi-arrow-right ms-2"></i></a>
          @endif
        </div>
      </div>
    </div>
  </section>

  {{-- The last featured category displayed between banners per Figma design --}}
  @if($lastFeatured && $lastFeatured->products->count() > 0)
    @php
      $featCat = $lastFeatured;
      $catName = langConverter($featCat->en_Category_Name, $featCat->fr_Category_Name);
      $catSlug = $featCat->en_Category_Slug;
      $catProducts = $featCat->products;
    @endphp
    <section class="featured-category-section py-5 second-featured-section">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2 class="fw-bold">{{ $catName }}</h2>
          <a href="{{ route('categories.show', ['slug' => $catSlug]) }}" class="btn btn-link text-warning text-decoration-none">
            {{ __('View All') }} <i class="bi bi-arrow-right"></i>
          </a>
        </div>

        <div class="row">
          <div class="col-12">
            @include('front.home.partials._product_carousel', [
              'products' => $catProducts, 
              'carouselId' => 'catCarousel_' . $featCat->id
            ])
          </div>
        </div>
      </div>
    </section>
  @endif

  <!-- Why Choose Us -->
  <section class="why-choose-us py-5 bg-light">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0">
              @php
                  // Prefer settings-based JSON (home_newdesign_why_choose) then fallback to Homepage row
                  $heroUrl = null;
                  $whyData = null;
                  try {
                      $whySetting = DB::table('settings')->where('slug', 'home_newdesign_why_choose')->value('value');
                  } catch (\Exception $e) {
                      $whySetting = null;
                  }
                  if ($whySetting) {
                      $decoded = json_decode($whySetting, true);
                      if (is_array($decoded)) {
                          if (isset($decoded[session('HTML_LANG', app()->getLocale() ?? 'en')])) {
                              $whyData = $decoded[session('HTML_LANG', app()->getLocale() ?? 'en')];
                          } elseif (isset($decoded['en'])) {
                              $whyData = $decoded['en'];
                          }
                      }
                  }

                    if (!$whyData) {
                      // prefer homepage_sections table
                      $section = \App\Models\Admin\SiteContent\HomepageSection::where('section_key','newdesign_why_choose')->first();
                      if ($section) {
                        $decoded_en = $section->content_en ?? [];
                        $decoded_fr = $section->content_fr ?? [];
                        $curLocale = session('HTML_LANG', app()->getLocale() ?? 'en');
                        if ($curLocale === 'ar' && !empty($decoded_fr)) {
                          $whyData = $decoded_fr;
                        } elseif (!empty($decoded_en)) {
                          $whyData = $decoded_en;
                        }
                        // Ensure image key exists: prefer inline content image, otherwise use section image
                        if (!is_array($whyData)) $whyData = [];
                        if (empty($whyData['image'])) {
                          $whyData['image'] = $section->image ?? null;
                        }
                        $whyData = $whyData ?: ['title'=>'','lead'=>'','points'=>[],'button'=>['text'=>'','url'=>'#'],'image'=>$section->image ?? null];
                      } else {
                        $why = \App\Models\Admin\SiteContent\Homepage::where('Location', 'newdesign_why_choose')->first();
                        if ($why) {
                          $whyData = [
                            'title' => $why->en_Title ?? $why->fr_Title ?? '',
                            'lead' => $why->en_Description_One ?? $why->fr_Description_One ?? '',
                            'points' => preg_split('/\r?\n/', trim($why->en_Description_Two ?? $why->fr_Description_Two ?? '')),
                            'button' => ['text' => $why->en_button_text ?? $why->fr_button_text ?? '', 'url' => $why->en_button_url ?? $why->fr_button_url ?? '#'],
                            'image' => $why->image ?? null
                          ];
                          if (session('HTML_LANG', app()->getLocale() ?? 'en') === 'ar') {
                            $whyData['title'] = $why->fr_Title ?? $whyData['title'];
                            $whyData['lead'] = $why->fr_Description_One ?? $whyData['lead'];
                            $whyData['points'] = preg_split('/\r?\n/', trim($why->fr_Description_Two ?? $why->en_Description_Two ?? ''));
                            $whyData['button']['text'] = $why->fr_button_text ?? $whyData['button']['text'];
                            $whyData['button']['url'] = $why->fr_button_url ?? $whyData['button']['url'];
                          }
                        }
                      }
                    }

                    // ensure whyData is an array before using offsets
                    if (!is_array($whyData)) {
                      $whyData = ['title' => '', 'lead' => '', 'points' => [], 'button' => ['text' => '', 'url' => '#'], 'image' => null];
                    }
                    $candidate = $whyData['image'] ?? null;
                  if ($candidate) {
                    if (stripos($candidate, 'http://') === 0 || stripos($candidate, 'https://') === 0) {
                      $heroUrl = $candidate;
                    } elseif (file_exists(public_path($candidate))) {
                      $heroUrl = asset($candidate);
                    } elseif (file_exists(public_path(PromotionImage() . $candidate))) {
                      $heroUrl = asset(PromotionImage() . $candidate);
                    } elseif (strpos($candidate, 'uploaded_files') !== false) {
                      $heroUrl = asset($candidate);
                    }
                  }

                  $whyTitle = $whyData['title'] ?? '';
                  $whyLead = $whyData['lead'] ?? '';
                  $whyPoints = is_array($whyData['points']) ? implode("\n", $whyData['points']) : ($whyData['points'] ?? '');
                  $whyBtnText = $whyData['button']['text'] ?? '';
                  $whyBtnUrl = $whyData['button']['url'] ?? '#';
              @endphp
              @if($heroUrl)
                  <div class="intro-image text-center md:text-right">
                      <img src="{{ $heroUrl }}" alt="hero" class="inline-block max-w-full" />
                  </div>
              @endif
        </div>
        <div class="col-lg-6">
          <h2 class="fw-bold mb-4">{!! nl2br(e($whyTitle ?: __('100% Trusted\nOrganic Food Store'))) !!}</h2>
          <div class="mb-4">
            @php
              $points = preg_split('/\r?\n/', trim($whyPoints ?? ''));
            @endphp
            @if(!empty($points) && count(array_filter($points)) > 0)
              @foreach($points as $p)
                @if(trim($p) === '') @continue @endif
                <div class="d-flex align-items-start mb-3">
                  <img src="https://c.animaapp.com/mhnmip5wa2i9Oh/img/check.png" alt="Check" class="me-3" style="width: 30px;">
                  <div>
                    <h5 class="fw-semibold">{!! $p !!}</h5>
                  </div>
                </div>
              @endforeach
            @else
              <div class="d-flex align-items-start mb-3">
                <img src="https://c.animaapp.com/mhnmip5wa2i9Oh/img/check.png" alt="Check" class="me-3" style="width: 30px;">
                <div>
                  <h5 class="fw-semibold">{{ __('Healthy & natural food for lovers of healthy food.') }}</h5>
                  <p class="text-muted">{{ __('Ut quis tempus erat. Phasellus euismod bibendum magna non tristique.') }}</p>
                </div>
              </div>
              <div class="d-flex align-items-start mb-3">
                <img src="https://c.animaapp.com/mhnmip5wa2i9Oh/img/check-1.png" alt="Check" class="me-3" style="width: 30px;">
                <div>
                  <h5 class="fw-semibold">{{ __('Every day fresh and quality products for you.') }}</h5>
                  <p class="text-muted">{{ __('Maecenas vehicula a justo quis laoreet. Sed in placerat nibh.') }}</p>
                </div>
              </div>
            @endif
          </div>
          @if($whyBtnText)
            <a href="{{ $whyBtnUrl }}" class="btn btn-success rounded-pill px-4" target="_blank">{!! $whyBtnText !!} <i class="bi bi-arrow-right ms-2"></i></a>
          @else
            <button class="btn btn-success rounded-pill px-4">{{ __('Shop Now') }} <i class="bi bi-arrow-right ms-2"></i></button>
          @endif
        </div>
      </div>
    </div>
  </section>

  <!-- Features -->
  <section class="features py-5">
    <div class="container">
      <div class="row g-4">
        @php
          $featuresSection = \App\Models\Admin\SiteContent\HomepageSection::where('section_key','newdesign_features')->first();
          $displayLocale = session('HTML_LANG', app()->getLocale() ?? 'en');
          $items = [];
          if ($featuresSection) {
              $data = $displayLocale === 'ar' ? ($featuresSection->content_fr ?? []) : ($featuresSection->content_en ?? []);
              if (isset($data['items']) && is_array($data['items'])) {
                  $items = $data['items'];
              }
          }
        @endphp

        @if(!empty($items))
          @php
            // static icons to use for features (old static set)
            $staticIcons = [
              'https://c.animaapp.com/mhnmip5wa2i9Oh/img/delivery-truck-1.svg',
              'https://c.animaapp.com/mhnmip5wa2i9Oh/img/headphones-1.svg',
              'https://c.animaapp.com/mhnmip5wa2i9Oh/img/shopping-bag.svg',
              'https://c.animaapp.com/mhnmip5wa2i9Oh/img/package.svg'
            ];
          @endphp
          @foreach($items as $it)
            @php $icon = $staticIcons[$loop->index % count($staticIcons)]; @endphp
            <div class="col-6 col-md-6 col-lg-3">
              <div class="feature-card h-100 w-100 d-flex align-items-center p-4 bg-white rounded shadow-sm">
                <img src="{{ $icon }}" alt="feature-icon" class="me-3" style="width: 45px;">
                <div>
                  <h6 class="mb-1 fw-semibold">{!! $it['title'] ?? '' !!}</h6>
                  <p class="text-muted small mb-0">{!! $it['desc'] ?? '' !!}</p>
                </div>
              </div>
            </div>
          @endforeach
        @else
          <div class="col-6 col-md-6 col-lg-3">
            <div class="feature-card h-100 w-100 d-flex align-items-center p-4 bg-white rounded shadow-sm">
              <img src="https://c.animaapp.com/mhnmip5wa2i9Oh/img/delivery-truck-1.svg" alt="{{ __('Delivery') }}" class="me-3" style="width: 45px;">
              <div>
                <h6 class="mb-1 fw-semibold">{{ __('Fast delivery within the same day') }}</h6>
                <!-- <p class="text-muted small mb-0">{{ __('Free shipping on all your order.') }}</p> -->
              </div>
            </div>
          </div>
          <div class="col-6 col-md-6 col-lg-3">
            <div class="feature-card h-100 w-100 d-flex align-items-center p-4 bg-white rounded shadow-sm">
              <img src="https://c.animaapp.com/mhnmip5wa2i9Oh/img/headphones-1.svg" alt="{{ __('Support') }}" class="me-3" style="width: 45px;">
              <div>
                <h6 class="mb-1 fw-semibold">{{ __('Customer Support 24/7') }}</h6>
                <!-- <p class="text-muted small mb-0">{{ __('Instant access to Support') }}</p> -->
              </div>
            </div>
          </div>
          <div class="col-6 col-md-6 col-lg-3">
            <div class="feature-card h-100 w-100 d-flex align-items-center p-4 bg-white rounded shadow-sm">
              <img src="https://c.animaapp.com/mhnmip5wa2i9Oh/img/shopping-bag.svg" alt="{{ __('Payment') }}" class="me-3" style="width: 45px;">
              <div>
                <h6 class="mb-1 fw-semibold">{{ __('100% Secure Payment') }}</h6>
                <!-- <p class="text-muted small mb-0">{{ __('We ensure your money is safe') }}</p> -->
              </div>
            </div>
          </div>
          <div class="col-6 col-md-6 col-lg-3">
            <div class="feature-card h-100 w-100 d-flex align-items-center p-4 bg-white rounded shadow-sm">
              <img src="https://c.animaapp.com/mhnmip5wa2i9Oh/img/package.svg" alt="{{ __('Guarantee') }}" class="me-3" style="width: 45px;">
              <div>
                <h6 class="mb-1 fw-semibold">{{ __('Money-Back Guarantee') }}</h6>
                <!-- <p class="text-muted small mb-0">{{ __('30 Days Money-Back Guarantee') }}</p> -->
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>

  <!-- Testimonials -->
  <section class="testimonials py-5">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <p class="text-success text-uppercase small mb-1">{{ __('TESTIMONIAL') }}</p>
          <h2 class="fw-bold">{{ __('What Our Customer Says') }}</h2>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-12">
          <div class="swiper testimonials-swiper">
            <div class="swiper-wrapper">
              @if(isset($reviews) && $reviews->isNotEmpty())
                @foreach($reviews as $r)
                  <div class="swiper-slide">
                    <div class="card border-0 shadow-sm h-100">
                      <div class="card-body">
                        <i class="bi bi-quote text-success fs-1"></i>
                        <p class="card-text text-muted my-3">{{ \Illuminate\Support\Str::limit($r->feedback, 220) }}</p>
                        <div class="d-flex align-items-center justify-content-between">
                          <div class="d-flex align-items-center">
                            <img src="{{ isset($r->user->image) ? asset(AdminProfilePicture().$r->user->image) : Avatar::create($r->user->name ?? 'Customer')->toBase64() }}" alt="{{ $r->user->name ?? __('Customer') }}" class="rounded-circle me-3" style="width: 50px; height: 50px;">
                            <div>
                              <h6 class="mb-0">{{ $r->user->name ?? __('Customer') }}</h6>
                              <small class="text-muted">{{ __('Customer') }}</small>
                            </div>
                          </div>
                          @php
                            $rating = intval($r->rating ?? 0);
                            $stars = str_repeat('★', $rating) . str_repeat('☆', max(0, 5 - $rating));
                          @endphp
                          <span class="text-warning">{!! $stars !!}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              @else
                {{-- Fallback static slides --}}
                {{-- @for($i=0;$i<3;$i++)
                  <div class="swiper-slide">
                    <div class="card border-0 shadow-sm h-100">
                      <div class="card-body">
                        <i class="bi bi-quote text-success fs-1"></i>
                        <p class="card-text text-muted my-3">{{ __('Pellentesque eu nibh eget mauris congue mattis mattis nec tellus. Phasellus imperdiet elit eu magna dictum.') }}</p>
                        <div class="d-flex align-items-center justify-content-between">
                          <div class="d-flex align-items-center">
                            <img src="https://c.animaapp.com/mhnmip5wa2i9Oh/img/image.png" alt="{{ __('Customer') }}" class="rounded-circle me-3" style="width: 50px; height: 50px;">
                            <div>
                              <h6 class="mb-0">{{ ['Robert Fox','Dianne Russell','Eleanor Pena'][$i] }}</h6>
                              <small class="text-muted">{{ __('Customer') }}</small>
                            </div>
                          </div>
                          <span class="text-warning">★★★★★</span>
                        </div>
                      </div>
                    </div>
                  </div>
                @endfor --}}
              @endif
            </div>
          </div>
        </div>
      </div>
      <script src="{{ asset('admin/js/swiper-bundle.min.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Hero Swiper
      new Swiper('.hero-swiper', {
        slidesPerView: 1.1,
        centeredSlides: true,
        spaceBetween: 10,
        loop: true,
        autoplay: {
          delay: 5000,
          disableOnInteraction: false,
        },
        pagination: {
          el: '.hero-pagination',
          clickable: true,
        },
        breakpoints: {
          768: {
            slidesPerView: 1.2,
            spaceBetween: 24,
          },
          1200: {
            slidesPerView: 1.25,
            spaceBetween: 32,
          }
        }
      });

      // Testimonials Swiper
      new Swiper('.testimonials-swiper', {
        slidesPerView: 4,
        spaceBetween: 24,
        loop: true,
        autoplay: {
          delay: 3000,
          disableOnInteraction: false,
        },
        breakpoints: {
          0: { slidesPerView: 1 },
          576: { slidesPerView: 1 },
          768: { slidesPerView: 2 },
          992: { slidesPerView: 3 },
          1200: { slidesPerView: 4 }
        }
      });
    });
  </script>
@endsection
