@extends('front.layouts.new_design_layout')
@section('title', isset($title) ? $title : __('About Us'))
@section('description', isset($description) ? $description : '')
@section('keywords', isset($keywords) ? $keywords : '')
@section('content')
    @php
        // Compute a localized banner title from admin-managed AboutUsPage content if available
        $locale = app()->getLocale();
        if (isset($about) && $about) {
            if (in_array($locale, ['ar', 'fr'])) {
                $localizedTitle = $about->fr_Title ?? $about->fr_Subtitle ?? $about->en_Title ?? __('About Us');
            } else {
                $localizedTitle = $about->en_Title ?? $about->en_Subtitle ?? $about->fr_Title ?? __('About Us');
            }
        } else {
            $localizedTitle = __('About Us');
        }
    @endphp

    {{-- reuse category banner for layout consistency --}}
    @include('front.partials.category_banner', ['title' => $localizedTitle])
    <div class="container py-5">
        @php
            // Admin provides the AboutUsPage model instance as $about (controller supplies it)
            // Fallbacks in case admin hasn't filled content yet.
            $locale = app()->getLocale();
            $get = function ($fieldEn, $fieldFr = null) use ($about, $locale) {
                if (!$about)
                    return null;
                if (in_array($locale, ['ar', 'fr'])) {
                    // legacy: Arabic content sometimes stored in fr_* columns
                    return $about->{$fieldFr} ?? $about->{$fieldEn} ?? null;
                }
                return $about->{$fieldEn} ?? $about->{$fieldFr} ?? null;
            };

            $mainTitle = $get('en_Title', 'fr_Title');
            $mainSubtitle = $get('en_Subtitle', 'fr_Subtitle');
            $descOne = $get('en_Description_One', 'fr_Description_One');
            $descTwo = $get('en_Description_Two', 'fr_Description_Two');
            $imageUrl = $about && $about->Image ? asset(aboutUsPage() . $about->Image) : asset('new-design/images/bannar-big.png');
        @endphp

        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <!-- <h1 class="display-5 fw-bold">{!! $mainTitle ?? __('100% Trusted Organic Food Store') !!}</h1> -->
                @if($mainSubtitle)
                    <p class="text-muted">{!! $mainSubtitle !!}</p>
                @endif

                @if($descOne)
                    <div class="mt-3 text-muted">{!! $descOne !!}</div>
                @endif

                <div class="mt-4">
                    <a href="{{ url('/shop') }}" class="btn btn-success rounded-pill px-4">{{ __('Shop Now') }} <i
                            class="bi bi-arrow-right ms-2"></i></a>
                </div>
            </div>
            <div class="col-lg-6 text-end">
                <img src="{{ $imageUrl }}" alt="About Image" class="img-fluid rounded"
                    style="max-height:360px; object-fit:cover;">
            </div>
        </div>

        {{-- Features / delivery block --}}
        <div class="row align-items-center py-4">
            <div class="col-12">
                @if($descTwo)
                    <p class="text-muted">{!! $descTwo !!}</p>
                @endif

                <ul class="list-unstyled mt-3">
                    @for($i = 1; $i <= 4; $i++)
                        @php
                            $suffix = (['One', 'Two', 'Three', 'Four'][$i - 1]);
                            $title = $get('en_Title_' . $suffix, 'fr_Title_' . $suffix);
                            $desc = $get('en_Description_' . $suffix, 'fr_Description_' . $suffix);
                            
                            // Prevent duplication and hide placeholders
                            if ($i === 1) {
                                if ($desc === $descOne) $desc = null;
                                // Specifically handle the "Innovative solutions" placeholder if matched
                                if (str_contains($title, 'Innovative solutions') || str_contains($title, 'ديس ابتكار الحلول')) $title = null;
                            }

                            $icon = $about ? ($about->{'Icon_' . $suffix} ?? null) : null;
                            $iconUrl = $icon ? asset(aboutUsPage() . $icon) : null;
                        @endphp
                        @if($title || $desc)
                        <li class="d-flex align-items-start mb-2">
                            @if($iconUrl)
                                <img src="{{ $iconUrl }}" alt="feature{{ $i }}" style="width:44px; height:44px; object-fit:contain;"
                                    class="me-3">
                            @else
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width:44px;height:44px;"><i class="bi bi-check2"></i></div>
                            @endif
                            <div>
                                @if($title)<div class="fw-semibold">{!! $title !!}</div>@endif
                                @if($desc)<div class="text-muted small">{!! $desc !!}</div>@endif
                            </div>
                        </li>
                        @endif
                    @endfor
                </ul>
            </div>
        </div>

        {{-- Testimonials if provided by controller --}}
        @if(isset($testimonials) && $testimonials->count())
            <div class="row mt-5">
                <div class="col-12">
                    <h4 class="fw-bold mb-3">{{ __('What Our Customers Say') }}</h4>
                    <div class="row">
                        @foreach($testimonials as $t)
                            <div class="col-md-4 mb-3">
                                <div class="bg-white p-3 rounded shadow-sm h-100">
                                    <div class="fw-semibold">{{ $t->name }}</div>
                                    <div class="text-muted small">{{ $t->position ?? '' }}</div>
                                    <p class="mt-2 mb-0 text-muted">{{ Str::limit($t->message, 150) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>

@endsection